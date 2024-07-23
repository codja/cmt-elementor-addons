<?php

namespace ElementorCmAddons\classes\ajax\requests;

class Nonce {
	public function refresh() {
		wp_send_json_success(
			[
				'_nonce' => wp_create_nonce( 'wp_rest' ),
			]
		);
	}

}
