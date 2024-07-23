<?php

namespace ElementorCmAddons\classes\routes\api\clients;

use ElementorCmAddons\classes\Geo_Location;
use ElementorCmAddons\classes\routes\api\handlers\Form_Redirect_Link;
use ElementorCmAddons\classes\routes\api\handlers\Request_Api;
use ElementorCmAddons\interfaces\api\Authenticatable;

// https://doc.pandats-api.io/cmtrading/
class Panda extends Client implements Authenticatable {

	const BASE_URL_API = 'https://cmtrading.pandats-api.io/api/v3/';

	private $partner_id;
	private $partner_secret_key;

	public function __construct() {
		$this->partner_id         = get_field( 'panda_partner_id', 'option' );
		$this->partner_secret_key = get_field( 'panda_partner_secret_key', 'option' );
	}

	public function post( \WP_REST_Request $request ) {
		$data   = $this->get_data( $request, true );
		$result = $this->send( $data );

		wp_send_json( $result );
	}

	protected function send( array $data ): array {
		$response = $data['response'] ?? '';
		$request  = $data['data'] ?? '';

		if ( isset( $response['error'] ) ) {
			$link_if_customer_exists = $this->error_handler( $response['error'], $request['email'] ?? '' );
		}

		return [
			'success' => isset( $link_if_customer_exists ) || 'ok' === $response['data']['status'],
			'link'    => $link_if_customer_exists ?? Form_Redirect_Link::get( $request, $response['data'] ?? '' ),
		];
	}

	protected function get_body( array $data ): array {
		if ( ! $data ) {
			return [];
		}

		$result = [
			'email'     => sanitize_email( $data['email'] ?? '' ),
			'country'   => sanitize_text_field( $data['countryiso2'] ?? '' ),
			'firstName' => sanitize_text_field( $data['firstname'] ?? '' ),
			'lastName'  => sanitize_text_field( $data['lastname'] ?? '' ),
			'phone'     => sanitize_text_field( ( $data['phonecountry'] ?? '' ) . ( $data['phone'] ?? '' ) ),
			'language'  => $this->get_site_language( sanitize_text_field( $data['language'] ?? '' ) ),
			'ip'        => Geo_Location::instance()->get_ip_address(),
		];

		$promocode = $data['promocode'] ?? '';
		if ( $promocode ) {
			$result['promocode'] = sanitize_text_field( $promocode );
		}

		$referral_data = $this->extract_referral_data( $data );
		$referral      = $referral_data['referral'] ?? '';
		if ( $referral ) {
			$result['referral'] = sanitize_text_field( $referral );
		}

		$client_source = $referral_data['clientSource'] ?? '';
		if ( $client_source ) {
			$result['clientSource'] = sanitize_text_field( $client_source );
		}

		return $result;
	}

	private function extract_referral_data( array $data ): ?array {
		$referral_src = sanitize_text_field( $data['referral'] ?? '' );
		$landing_url  = sanitize_text_field( $data['landingPageUrl'] ?? '' );
		$vl_cid       = sanitize_text_field( $data['vl-cid'] ?? '' );

		if ( ! $referral_src ) {
			return null;
		}

		$referral_src = urldecode( $referral_src );
		$referral_arr = explode( '|', $referral_src );

		$referral_data = [];
		foreach ( $referral_arr as $item ) {
			$param = explode( '=', $item );

			if ( $param ) {
				$referral_data[ $param[0] ] = $param[1] ?? '';
			}
		}

		// add in referral url parameter to landing page
		if ( $landing_url ) {
			$referral_data['LANDING_PAGE'] = $landing_url;
		}

		if ( $vl_cid ) {
			$referral_data['vl-cid'] = $vl_cid;
			$referral_data['cid']    = $vl_cid;
		}

		$client_source = $referral_data['clientSource'] ?? '';

		if ( isset( $referral_data['clientSource'] ) ) {
			unset( $referral_data['clientSource'] );
		}

		$referral_back = [];
		foreach ( $referral_data as $key => $value ) {
			$referral_back[] = "$key=$value";
		}
		$referral = implode( '|', $referral_back );

		return [
			'clientSource' => $client_source,
			'referral'     => $referral,
		];
	}

	private function get_access_key(): string {
		return sha1( $this->partner_id . time() . $this->partner_secret_key );
	}

	public function authentication( array $data = [], array $options = [] ) {
		if ( ! $data ) {
			return [];
		}

		return Request_Api::send_api(
			self::BASE_URL_API . 'authorization',
			$data,
			true,
			'POST',
			[
				'Content-Type' => 'application/json',
			],
			true
		);
	}

	public function get_jwt_token() {
		$exist_token = get_option( 'panda_token' )
			? json_decode( get_option( 'panda_token' ), true )
			: false;

		if ( $exist_token && $exist_token['expire'] > time() ) {
			return $exist_token['token'];
		}

		$data = [
			'partnerId' => $this->partner_id,
			'time'      => time(),
			'accessKey' => $this->get_access_key(),
		];

		$authentication = $this->authentication( $data );

		if ( ! $authentication ) {
			wp_send_json_error( __( 'Error on client server. Check Request_Api log', 'cmt-elementor-addons' ) );
		}

		if ( isset( $authentication['error'] ) ) {
			wp_send_json_error( $authentication['error'][0]['description'] ?? __( 'Unknown error', 'cmt-elementor-addons' ) );
		}

		$token        = $authentication['data']['token'] ?? '';
		$token_expire = $authentication['data']['expire'] ?? '';

		if ( ! $token || ! $token_expire ) {
			wp_send_json_error( __( 'Token or token_expire not exist', 'cmt-elementor-addons' ) );
		}

		$token_data = [
			'token'  => $token,
			'expire' => strtotime( $token_expire ),
		];

		update_option( 'panda_token', wp_json_encode( $token_data ), false );

		return $token;
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

	private function error_handler( $error, $email ) {
		$error = reset( $error );

		if ( isset( $error['code'] ) && $error['code'] === 'BL002' && $email ) {
			$response = Request_Api::send_api(
				self::BASE_URL_API . 'system/loginToken',
				[
					'email' => $email,
				],
				true,
				'POST',
				[
					'Authorization' => $this->get_auth_data(),
					'Content-Type'  => 'application/json',
				],
				true
			);

			if ( ! $response ) {
				return '';
			}

			if ( isset( $response['error'] ) ) {
				wp_send_json_error( $response['error'][0]['description'] ?? __( 'system/loginToken: Unknown error', 'cmt-elementor-addons' ) );
			}

			return $response['data']['url'] ?? '';
		}

		wp_send_json_error( $error['description'] ?? __( 'Unknown error', 'cmt-elementor-addons' ) );
	}
}
