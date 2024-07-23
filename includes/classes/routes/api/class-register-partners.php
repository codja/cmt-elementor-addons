<?php

namespace ElementorCmAddons\classes\routes\api;

use ElementorCmAddons\classes\routes\api\clients\Gotow;
use ElementorCmAddons\classes\routes\api\clients\Partners;

class Register_Partners extends Partners {

	const API_ENDPOINT = 'Create';

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

}
