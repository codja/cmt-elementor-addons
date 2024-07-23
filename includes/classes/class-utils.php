<?php

namespace ElementorCmAddons\classes;

abstract class Utils {

	public static function get_version_file( $file ): ?int {
		return filemtime( $file );
	}

}
