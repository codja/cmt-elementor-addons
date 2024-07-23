<?php
/**
 * Plugin Name: Elementor CM Addons
 * Description: Elementor CM Addons.
 * Plugin URI:  https://elementor.com/
 * Version:     1.3.0
 * Author:      Dane
 * Author URI:  https://elementor.com/
 * Text Domain: cmt-elementor-addons
 */

namespace ElementorCmAddons;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'ELEMENTOR_CM_ADDONS_PATH', plugin_dir_path( __FILE__ ) );

require_once ELEMENTOR_CM_ADDONS_PATH . 'vendor/autoload.php';
require_once ELEMENTOR_CM_ADDONS_PATH . 'autoload.php';

final class Elementor_Cm_Addons {
	const VERSION                   = '1.3.0';
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
	const MINIMUM_PHP_VERSION       = '7.0';

	public function __construct() {
		// Load translation
		add_action( 'init', array( $this, 'i18n' ) );
		// Init Plugin
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function i18n() {
		load_plugin_textdomain( 'cmt-elementor-addons' );
	}

	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );
			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once 'plugin.php';
	}

	public function admin_notice_missing_main_plugin() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'cmt-elementor-addons' ),
			'<strong>' . esc_html__( 'Elementor Hello World', 'cmt-elementor-addons' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'cmt-elementor-addons' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'cmt-elementor-addons' ),
			'<strong>' . esc_html__( 'Elementor Hello World', 'cmt-elementor-addons' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'cmt-elementor-addons' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'cmt-elementor-addons' ),
			'<strong>' . esc_html__( 'Elementor Hello World', 'cmt-elementor-addons' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'cmt-elementor-addons' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}

// Instantiate Elementor_Hello_World.
new Elementor_Cm_Addons();
