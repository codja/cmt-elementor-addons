<?php

namespace ElementorCmAddons\interfaces\api;

interface Authenticatable {

	public function get_auth_data(): string;

	public function get_jwt_token();

	public function authentication( array $data = [], array $options = [] );

}
