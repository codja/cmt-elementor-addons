<?php

namespace ElementorCmAddons\classes\routes\api\clients;

use ElementorCmAddons\classes\routes\api\handlers\Request_Api;
use ElementorCmAddons\interfaces\api\Authenticatable;
use ElementorCmAddons\traits\Singleton;

// https://developer.goto.com/guides/Get%20Started/00_Ref-Get-Started/
class Gotow extends Client implements Authenticatable {

	use Singleton;

	const BASE_URL_API = 'https://api.getgo.com/G2W/rest/v2/';

	private $client_id;
	private $client_secret;
	private $credentials;

	public function __construct() {
		$this->client_id     = get_field( 'goto_client_id', 'option' );
		$this->client_secret = get_field( 'goto_client_secret', 'option' );
		$this->credentials   = get_option( 'goto_creds' )
			? json_decode( get_option( 'goto_creds' ), true )
			: null;
	}

	public function post( \WP_REST_Request $request ) {
		// TODO: Implement post() method.
	}

	public function first_get_creds( array $options ): bool {
		if ( ! $options ) {
			return false;
		}

		$data = [
			'grant_type' => 'authorization_code',
			'code'       => $options['code'] ?? '',
		];

		$authentication = $this->authentication( $data, $options );

		if ( ! $authentication ) {
			return false;
		}

		if ( isset( $authentication['error'] ) || isset( $authentication['int_err_code'] ) ) {
			return false;
		}

		$authentication['expire'] = $authentication['expires_in'] + strtotime( '- 1 minute' );

		return update_option( 'goto_creds', wp_json_encode( $authentication ), false );
	}

	public function send( array $data ): array {
		if ( ! $data ) {
			return [];
		}

		$response = Request_Api::send_api(
			$this->get_url_for_request( sanitize_text_field( $data['data']['webinar_key'] ?? '' ) ),
			$this->get_body( $data['data'] ?? [] ),
			true,
			'POST',
			$this->get_headers(),
			true
		);

		if ( ! $response ) {
			wp_send_json_error( __( 'Error when registering for the webinar', 'cmt-elementor-addons' ) );
		}

		switch ( true ) {
			case isset( $response['errorCode'] ):
				wp_send_json_error( $response['description'] );
				break;
			case isset( $response['int_err_code'] ):
				wp_send_json_error( $response['msg'] );
				break;
		}

		return [
			'webinar_join_url' => $response['joinUrl'] ?? '',
		];
	}

	protected function get_body( array $data ): array {

		return [
			'firstName' => sanitize_text_field( $data['firstname'] ?? '' ),
			'lastName'  => sanitize_text_field( $data['lastname'] ?? '' ),
			'email'     => sanitize_email( $data['email'] ?? '' ),
			'phone'     => sanitize_text_field( ( $data['phonecountry'] ?? '' ) . ( $data['phone'] ?? '' ) ),
		];
	}

	private function get_url_for_request( $webinar_key ): ?string {
		if ( ! $webinar_key ) {
			return null;
		}

		if ( ! isset( $this->credentials['organizer_key'] ) ) {
			wp_send_json_error( __( 'Organizer Key not found. Check permissions for goto account', 'cmt-elementor-addons' ) );
		}

		return esc_url_raw( self::BASE_URL_API . '/organizers/' . $this->credentials['organizer_key'] . "/webinars/{$webinar_key}/registrants" );
	}

	public function get_auth_data(): string {
		return 'Bearer ' . $this->get_jwt_token();
	}

	protected function get_headers(): array {
		return [
			'Authorization' => $this->get_auth_data(),
			'Content-Type'  => 'application/json',
		];
	}

	public function authentication( array $data = [], array $options = [] ) {
		if ( ! $data ) {
			return [];
		}

		return Request_Api::send_api(
			'https://api.getgo.com/oauth/v2/token',
			$data,
			false,
			'POST',
			[
				'Authorization' => 'Basic ' . $this->get_basic_auth_header( $options ),
				'Accept'        => 'application/json',
				'Content-type'  => 'application/x-www-form-urlencoded',
			],
			true
		);
	}

	public function get_jwt_token() {

		if ( $this->credentials && isset( $this->credentials['expire'] ) && $this->credentials['expire'] >= time() && isset( $this->credentials['access_token'] ) ) {
			return $this->credentials['access_token'];
		}

		$data = [
			'grant_type'    => 'refresh_token',
			'refresh_token' => $this->credentials['refresh_token'] ?? '',
		];

		$authentication = $this->authentication( $data );

		if ( ! $authentication ) {
			wp_send_json_error( __( 'Error on client server. Check Request_Api log', 'cmt-elementor-addons' ) );
		}

		if ( isset( $authentication['error'] ) ) {
			wp_send_json_error( $authentication['error'][0]['description'] );
		}

		$authentication['expire'] = $authentication['expires_in'] + strtotime( '- 1 minute' );

		update_option( 'goto_creds', wp_json_encode( $authentication ), false );

		return $authentication['access_token'];
	}

	private function get_basic_auth_header( array $options ): string {
		$client_id     = $options && isset( $options['client_id'] ) ? $options['client_id'] : $this->client_id;
		$client_secret = $options && isset( $options['client_secret'] ) ? $options['client_secret'] : $this->client_secret;
		return base64_encode( $client_id . ':' . $client_secret ); // phpcs:ignore
	}
}
