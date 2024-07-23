<?php

namespace ElementorCmAddons\classes\routes\api\clients;

use ElementorCmAddons\classes\routes\api\handlers\Request_Api;

abstract class Client {

	const BASE_URL_API = '';

	const API_ENDPOINT = '';

	abstract public function post( \WP_REST_Request $request );

	abstract protected function get_body( array $data );

	protected function get_data( \WP_REST_Request $request, $response_json = false ): array {
		$nonce = $request->get_header( 'X-WP-Nonce' );

		if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
			wp_send_json_error();
		}

		$data     = $request->get_params();
		$response = Request_Api::send_api(
			$this->get_url_for_request(),
			$this->get_body( $data ),
			true,
			'POST',
			$this->get_headers(),
			$response_json
		);

		if ( ! $response ) {
			wp_send_json_error( __( 'Error on client server. Check Request_Api log', 'cmt-elementor-addons' ) );
		}

		return [
			'response' => $response,
			'data'     => $data,
		];
	}

	protected function get_site_language( $language ): ?string {
		if ( ! $language ) {
			return null;
		}

		$convert = [
			'en-US' => 'enu',
			'ar-SA' => 'ara',
			'ru-RU' => 'rus',
		];

		return $convert[ $language ] ?? 'enu';
	}

	private function get_url_for_request(): string {
		return esc_url_raw( static::BASE_URL_API . static::API_ENDPOINT );
	}

	protected function get_headers(): array {
		return [
			'Content-Type' => 'application/json',
			'Accept'       => 'application/json',
		];
	}

	private function send_error( $response ) {
		$error = (array) $response['ErrorDetails']->Message;
		wp_send_json_error( $error[0] );
	}

}
