<?php

namespace ElementorCmAddons\classes\routes\api\handlers;

/*
 * Class for send remote request
*/

abstract class Request_Api {

	/**
	 * Send remote request, get response
	 *
	 * @param string $url
	 * @param array $body
	 * @param bool $body_json
	 * @param string $method
	 * @param array $headers
	 * @param bool $response_json
	 *
	 * @return string|array
	 */
	public static function send_api(
		string $url,
		array $body = [],
		bool $body_json = false,
		string $method = 'GET',
		array $headers = [],
		bool $decode_json = false
	) {
		$args = [
			'headers' => $headers,
			'method'  => $method,
			'body'    => $body_json ? wp_json_encode( $body ) : $body,
			'timeout' => 45,
		];

		if ( $method === 'GET' ) {
			$url .= '?' . http_build_query( $body );
			unset( $args['body'] );
		}

		$request = wp_remote_request(
			$url,
			$args
		);

		if ( is_wp_error( $request ) ) {
			$error_message = $request->get_error_message();
			error_log( '[' . date( 'Y-m-d H:i:s' ) . "] Error: { $error_message } \n===========\n", 3, __DIR__ . '/errors.log' );
			return '';
		} else {
			$response = wp_remote_retrieve_body( $request );
			return $decode_json
				? json_decode( $response, true )
				: $response;
		}
	}
}
