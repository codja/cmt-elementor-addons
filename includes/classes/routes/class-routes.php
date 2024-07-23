<?php

namespace ElementorCmAddons\classes\routes;

use ElementorCmAddons\classes\routes\api\Register_Panda;
use ElementorCmAddons\classes\routes\api\Register_Partners;

class Routes {

	public function __construct() {
		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					'cmform/v1',
					'/register_partners',
					array(
						'methods'  => 'POST',
						'callback' => [ new Register_Partners(), 'post' ],
					)
				);
			}
		);

		add_action(
			'rest_api_init',
			function () {
				register_rest_route(
					'cmform/v1',
					'/register_panda',
					array(
						'methods'  => 'POST',
						'callback' => [ new Register_Panda(), 'post' ],
					)
				);
			}
		);
	}

}
