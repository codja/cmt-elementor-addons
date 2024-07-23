<?php

namespace ElementorCmAddons\classes\routes\api;

use ElementorCmAddons\classes\routes\api\clients\Gotow;
use ElementorCmAddons\classes\routes\api\clients\Panda;

class Register_Panda extends Panda {

	const API_ENDPOINT = 'customers';

	public function post( \WP_REST_Request $request ) {
		$data       = $this->get_data( $request, true );
		$is_webinar = $data['data']['webinar_key'] ?? '';
		$result     = $this->send( $data );

		if ( $is_webinar ) {
			$result_goto = Gotow::instance()->send( $data );
			$result      = array_merge( $result_goto, $result );
		}

		wp_send_json( $result );
	}

	protected function get_body( array $data ): array {

		if ( ! isset( $data['password'] ) ) {
			wp_send_json_error( __( 'There are not enough fields for the "Create Account" action', 'cmt-elementor-addons' ) );
		}

		$body                             = parent::get_body( $data );
		$body['password']                 = sanitize_text_field( $data['password'] );
		$body['acceptTermsAndConditions'] = $this->get_tcc_checked( sanitize_text_field( $data['agree'] ) );

		if ( isset( $data['birthday'] ) ) {
			$body['birthday'] = $this->prepare_date( $data['birthday'] );
		}

		return $body;
	}

	private function get_tcc_checked( string $agree ): bool {
		return $agree === 'on';
	}

	private function prepare_date( string $date ): string {
		$date = sanitize_text_field( $date );
		return gmdate( 'Y-m-d', strtotime( $date ) );
	}

}
