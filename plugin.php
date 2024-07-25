<?php

namespace ElementorCmAddons;

use ElementorCmAddons\classes\widgets\Cm_Form;
use ElementorCmAddons\classes\widgets\Cm_Iframe;
use ElementorCmAddons\includes\classes\helpers\Utils;
use ElementorCmAddons\traits\Singleton;

class Plugin {

	use Singleton;

	public function enqueue_cm_scripts() {
		wp_enqueue_script(
			'elementor-cm-addons',
			ELEMENTOR_CM_ADDONS_URL . 'assets/js/core-scripts.js',
			[],
			Utils::get_version_file( ELEMENTOR_CM_ADDONS_PATH . 'assets/js/core-scripts.js' ),
			true
		);

		wp_localize_script(
			'elementor-cm-addons',
			'cmform',
			[
				'url'    => admin_url( 'admin-ajax.php' ),
				'_nonce' => wp_create_nonce( 'wp_rest' ),
			]
		);
	}

	public function widget_styles() {
		wp_enqueue_style(
			'cm-iframe',
			plugins_url( '/assets/css/cm-iframe.css', __FILE__ ),
			[],
			Utils::get_version_file( plugin_dir_path( __FILE__ ) . 'assets/css/cm-iframe.css' ),
			'all'
		);
	}

	public function admin_styles() {
		$user = wp_get_current_user();
		if ( ! in_array( 'administrator', (array) $user->roles, true ) ) {
			wp_enqueue_style( 'cm-admin-styles', plugins_url( '/assets/css/cm-admin.css', __FILE__ ), array(), '1.0.1', 'all' );
		}
	}

	public function register_widgets() {
		// Its is now safe to include Widgets files
		// Register Widgets
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Cm_Form() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Cm_Iframe() );
	}

	public function __construct() {
		if ( ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_cm_scripts' ] );
		}

		// Register frontend editor styles
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'admin_styles' ] );
		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
		// Add a Link to Plugin Settings Page in The WordPress Plugin List
		add_filter( 'plugin_action_links_cmt-elementor-addons/cm-addons.php', [ $this, 'add_plugin_settings_page_link' ] );
	}

	public function add_plugin_settings_page_link( $links ) {
		// Build and escape the URL.
		$url = esc_url(
			add_query_arg(
				'page',
				'cm-addons-settings',
				get_admin_url() . 'admin.php'
			)
		);
		// Create the link.
		$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
		// Adds the link to the end of the array.
		$links[] = $settings_link;

		return $links;
	}
}

// Instantiate Plugin Class
Plugin::instance();
