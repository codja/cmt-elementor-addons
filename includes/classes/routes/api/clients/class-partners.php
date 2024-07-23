<?php

namespace ElementorCmAddons\classes\routes\api\clients;

use ElementorCmAddons\classes\Geo_Location;
use ElementorCmAddons\classes\routes\api\handlers\Form_Redirect_Link;

class Partners extends Client {
	const BASE_URL_API = 'https://partners.cmtrading.com/WebApi/api/AffiliatesCreationAPI/';

	public function post( \WP_REST_Request $request ) {
		$response = $this->get_data( $request, true );
		$success  = $response['response']['success'] ?? false;

		if ( ! $success ) {
			wp_send_json_error( $response['response']['error'] ?? __( 'Unknown error', 'cmt-elementor-addons' ) );
		}

		$result = [
			'success' => $success,
			'link'    => Form_Redirect_Link::get( $response['data'], $response['response']['data'] ),
		];

		wp_send_json( $result );
	}

	protected function send( array $data ): array {
		$success = $data['response']['success'] ?? false;

		if ( ! $success ) {
			wp_send_json_error( $data['response']['error'] ?? __( 'Unknown error', 'cmt-elementor-addons' ) );
		}

		return [
			'success' => $success,
			'link'    => Form_Redirect_Link::get( $data['data'], $data['response'] ),
		];
	}

	protected function get_body( array $data ): array {
		if ( ! $data ) {
			return [];
		}

		return [
			'Email'       => sanitize_email( $data['email'] ?? '' ),
			'Firstname'   => sanitize_text_field( $data['firstname'] ?? '' ),
			'Lastname'    => sanitize_text_field( $data['lastname'] ?? '' ),
			'Ipaddress'   => Geo_Location::instance()->get_ip_address(),
			'Phone'       => sanitize_text_field( $data['phone'] ?? '' ),
			'PhonePrefix' => sanitize_text_field( $data['phonecountry'] ?? '' ),
			'EmpId'       => 1,
			'PartnerType' => 'IB',
			'CountryCode' => sanitize_text_field( $data['countryiso2'] ?? '' ),
		];
	}
}
