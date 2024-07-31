<?php

namespace ElementorCmAddons\classes\routes\api\clients;

use ElementorCmAddons\classes\routes\api\handlers\Form_Redirect_Link;
use ElementorCmAddons\classes\routes\api\handlers\Request_Api;

class Antelope extends Client {

	const REFERRAL_PARAMS = [
		'referral'           => 'referral',
		'sc'                 => 'clientSource',
		'trackingcampaignId' => 'campaignCode',
	];

	const BASE_URL_API = 'https://api.cmtrading.com/SignalsServer/api/';

	const API_ENDPOINT = 'registerUser';

	public function post( \WP_REST_Request $request ) {
		if ( ! defined( 'ANTILOPE_API_AFFILIATE_KEY' ) ) {
			wp_send_json_error( esc_html__( 'Check constants', 'cmt-elementor-addons' ) );
		}

		$response = $this->get_data( $request, true );
		$success  = $response['response']['success'] ?? false;

		if ( ! $success ) {
			wp_send_json_error( $response['response']['error']['errorDetails'] ?? esc_html__( 'Unknown error', 'cmt-elementor-addons' ) );
		}

		$result = [
			'success' => $success,
			'link'    => Form_Redirect_Link::get( $response['data'] ?? null, $response['response'] ?? null ),
		];

		wp_send_json( $result );
	}

	protected function send_request( $data, $response_json = false ) {
		if ( ! $data ) {
			return null;
		}

		return Request_Api::send_api(
			$this->get_url_for_request() . '?' . http_build_query( $this->get_body( $data ) ),
			[],
			false,
			'POST',
			$this->get_headers(),
			$response_json
		);
	}

	protected function send( array $data ): array {
		$response = $data['response'] ?? '';
		$request  = $data['data'] ?? '';

		$is_error = isset( $response['error'] );
		if ( $is_error ) {
			$link_if_customer_exists = $this->error_handler( $response['error'] );
		}

		return [
			'success' => ! empty( $link_if_customer_exists ) || $response['success'] ?? false,
			'link'    => $link_if_customer_exists ?? Form_Redirect_Link::get( $request, $response ),
		];
	}

	protected function get_body( array $data ): array {
		if ( ! $data ) {
			return [];
		}

		$result = [
			'firstname'  => sanitize_text_field( $data['firstname'] ?? '' ),
			'lastname'   => sanitize_text_field( $data['lastname'] ?? '' ),
			'email'      => sanitize_email( $data['email'] ?? '' ),
			'telephone'  => sanitize_text_field( ( $data['phonecountry'] ?? '' ) . ( $data['phone'] ?? '' ) ),
			'countryiso' => sanitize_text_field( $data['countryiso2'] ?? '' ),
			'password'   => sanitize_text_field( $data['password'] ?? '' ),
			'apikey'     => ANTILOPE_API_AFFILIATE_KEY,
		];

		$promocode = $data['promocode'] ?? '';
		if ( $promocode ) {
			$result['promocode'] = sanitize_text_field( $promocode );
		}

		$referral_data = $this->extract_referral_data( $data );
		foreach ( self::REFERRAL_PARAMS as $param => $key ) {
			if ( ! empty( $referral_data[ $key ] ) ) {
				$result[ $param ] = sanitize_text_field( $referral_data[ $key ] );
			}
		}

		return $result;
	}

	private function extract_referral_data( array $data ): ?array {
		$referral_src = sanitize_text_field( $data['referral'] ?? '' );
		$cid          = sanitize_text_field( $data['cid'] ?? '' );

		if ( ! $referral_src ) {
			return null;
		}

		// Decode the URL-encoded 'referral' string and split it into key-value pairs
		$referral_src = urldecode( $referral_src );
		$referral_arr = explode( '|', $referral_src );

		// Initialize an array to store the parsed referral data
		$referral_data = [];
		foreach ( $referral_arr as $item ) {
			$param = explode( '=', $item );

			// Only add valid key-value pairs to the referral data array
			if ( ! empty( $param[0] && ! empty( $param[1] ) ) ) {
				$referral_data[ $param[0] ] = $param[1] ?? '';
			}
		}

		// Add the 'cid' to the referral data if it exists
		if ( $cid ) {
			$referral_data['cid'] = $cid;
		}

		// Extract and remove 'clientSource' from referral data
		$client_source = $referral_data['clientSource'] ?? '';
		if ( isset( $referral_data['clientSource'] ) ) {
			unset( $referral_data['clientSource'] );
		}

		// Extract 'campaign_code' from referral data
		$campaign_code = $referral_data['campaign_code'] ?? '';

		// Rebuild the 'referral' string from the remaining referral data
		$referral_back = [];
		foreach ( $referral_data as $key => $value ) {
			$referral_back[] = "$key=$value";
		}
		$referral = implode( '|', $referral_back );

		return [
			'clientSource'       => $client_source,
			'trackingcampaignId' => $campaign_code,
			'referral'           => $referral,
		];
	}

	private function error_handler( $error ) {
		$code_if_customer_exists = 2;
		if ( $code_if_customer_exists === ( $error['errorCode'] ?? 0 ) ) {
			$error_text    = esc_html__( 'User exists', 'cmt-elementor-addons' );
			$error_details = $error['errorDetails'] ?? '';

			if ( ! $error_details ) {
				wp_send_json_error( $error_text );
			}

			$parse_error = explode( ' ', $error_details );
			$user_id     = $parse_error ? end( $parse_error ) : false;
			if ( ! $user_id ) {
				wp_send_json_error( $error_text );
			}

			$response = Request_Api::send_api(
				self::BASE_URL_API . 'regenerateUserAutologinUrl?' . http_build_query(
					[
						'userId' => $user_id,
						'apikey' => ANTILOPE_API_AFFILIATE_KEY
					]
				),
				[],
				false,
				'POST',
				$this->get_headers(),
				true
			);

			$status = $response['success'] ?? false;
			if ( ! $status ) {
				wp_send_json_error( $error_text );
			}

			return $response['result'] ?? '';
		}

		wp_send_json_error( $error['errorDetails'] ?? __( 'Unknown error', 'cmt-elementor-addons' ) );

		return null;
	}
}
