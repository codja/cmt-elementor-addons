<?php

namespace ElementorCmAddons\classes\routes\api\handlers;

/*
 * Class for create link for redirecting after submit form
*/

use ElementorCmAddons\classes\Panda_DB;
use Mobile_Detect;

abstract class Form_Redirect_Link {

	public static function get( array $data, array $data_from_api ): string {
		$action  = isset( $data['postRegistrationAction'] )
					? sanitize_text_field( $data['postRegistrationAction'] )
					: '';
		$default = get_home_url();

		switch ( $action ) {
			case 'CustomUrl':
				$result = isset( $data['redirectToPage'] )
					? sanitize_text_field( $data['redirectToPage'] )
					: $default;
				break;
			case 'ThankYou':
				$result = $default . '/thank-you';
				break;
			case 'Webtrader':
				$result = self::strip_param_from_url( $data_from_api['loginToken'] ?? '', 'action' );
				break;
			case 'WebsiteSecondForm':
				$result = self::get_link_for_second_form( $data['email'] ?? '' );
				$result = $result ? $result : $default;
				break;
			case 'WebsiteSecondFormES':
				$result = self::get_link_for_second_form( $data['email'] ?? '', 'https://es.cmtrading.com' );
				$result = $result ? $result : $default;
				break;
			case 'Partners':
				$result = $data_from_api['autoLoginUrl'] ?? $default;
				break;
			case 'CMTadawul':
				$result = 'https://www.cmtadawul.com/';
				break;
			case 'es.cmtrading.com':
				$result = self::strip_param_from_url( $data_from_api['loginToken'] ?? '', 'action' );
				$result = str_replace( 'www.cmtrading.com', 'es.cmtrading.com', $result );
				break;
			case 'CRM':
				$result = $data_from_api['result']['brokerLoginUrl'] ?? $default;
				break;
			default:
				$result = $default;
				break;
		}

		return esc_url_raw( $result );
	}

	private static function strip_param_from_url( $url, $param = '' ): string {
		if ( ! $url ) {
			return get_home_url();
		}

		if ( ! $param ) {
			return $url;
		}

		$base_url = strtok( $url, '?' );
		$query    = wp_parse_url( $url )['query'];

		parse_str( $query, $parameters );
		unset( $parameters[ $param ] );
		$new_query = http_build_query( $parameters );

		if ( class_exists( 'Mobile_Detect' ) ) {
			$detect = new Mobile_Detect();

			// Any mobile device (phones or tablets).
			if ( $detect->isMobile() ) {
				$base_url = str_replace( '/webtrader/', '/mobile/', $base_url );
			}
		}

		return $base_url . '?' . $new_query;
	}

	private static function get_link_for_second_form( $email, $host = 'www.cmtrading.com' ): ?string {
		if ( ! $email || ! is_email( $email ) ) {
			return null;
		}

		$email       = sanitize_email( $email );
		$customer_id = Panda_DB::instance()->get_user_register_data( 'email', $email, 'customer_id' );

		if ( ! $customer_id || ! isset( $customer_id['customer_id'] ) ) {
			return null;
		}

		return add_query_arg(
			[
				'clientid' => $customer_id['customer_id'],
				'action'   => 'personDetailsForm',
			],
			$host
		);
	}

}
