<?php

namespace ElementorCmAddons\classes\ajax;

use ElementorCmAddons\classes\ajax\requests\Nonce;

class Ajax {
	public function __construct() {

		$nonce = new Nonce();
		add_action(
			'wp_ajax_cm_get_refreshed_nonce',
			[ $nonce, 'refresh' ]
		);

		add_action(
			'wp_ajax_nopriv_cm_get_refreshed_nonce',
			[ $nonce, 'refresh' ]
		);

	}

}
