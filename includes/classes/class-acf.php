<?php

namespace ElementorCmAddons\classes;

use ElementorCmAddons\classes\routes\api\clients\Gotow;

class ACF {

	public function __construct() {
		//      options page
		//      https://www.advancedcustomfields.com/resources/options-page/
		add_action( 'init', [ $this, 'register_options_page' ] );

		//      in this hook you need register your fields
		//      https://www.advancedcustomfields.com/resources/register-fields-via-php/
		add_action( 'init', [ $this, 'register_fields' ] );

		add_action( 'acf/save_post', [ $this, 'save_settings' ], 5 );
		add_filter( 'acf/validate_save_post', [ $this, 'acf_custom_validate' ] );
	}

	public function register_options_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page(
				array(
					'page_title' => 'CM Addons',
					'menu_title' => 'CM Addons',
					'menu_slug'  => 'cm-addons-settings',
					'capability' => 'edit_posts',
					'icon_url'   => 'dashicons-portfolio',
					'position'   => 30,
					'redirect'   => false,
				)
			);
		}
	}

	public function save_settings() {
		$screen = get_current_screen();
		if ( strpos( $screen->id, 'cm-addons-settings' ) ) {
			$exist_creds = get_option( 'goto_creds' );
			$exist_code  = get_field( 'goto_code', 'option' );
			// @codingStandardsIgnoreStart
			$client_id     = sanitize_text_field( $_POST['acf']['field_634fef580a9a0'] ?? '' );
			$client_secret = sanitize_text_field( $_POST['acf']['field_634fef730a9a1'] ?? '' );
			$new_code      = sanitize_text_field( $_POST['acf']['field_634fef940a9a2'] ?? '' );
			// @codingStandardsIgnoreEnd
			if (
				! $exist_creds
				|| $new_code !== $exist_code
			) {
				Gotow::instance()->first_get_creds(
					[
						'client_id'     => $client_id,
						'client_secret' => $client_secret,
						'code'          => $new_code,
					]
				);
			}

			$exist_panda_partner_id = get_field( 'panda_partner_id', 'option' );
			$exist_panda_secret_key = get_field( 'panda_partner_secret_key', 'option' );
			$new_panda_partner_id   = sanitize_text_field( $_POST['acf']['field_634feeea0a99d'] ?? '' ); //phpcs:ignore
			$new_panda_secret_key   = sanitize_text_field( $_POST['acf']['field_634fef180a99e'] ?? '' ); //phpcs:ignore
			if (
				$exist_panda_partner_id !== $new_panda_partner_id
				|| $exist_panda_secret_key !== $new_panda_secret_key
			) {
				delete_option( 'panda_token' );
			}
		}
	}

	public function acf_custom_validate() {
		$acf_data  = $_POST['acf'] ?? null; // phpcs:ignore
		$client_id = 'field_634fef580a9a0';

		if ( isset( $acf_data[ $client_id ] ) ) {
			$client_secret       = 'field_634fef730a9a1';
			$code                = 'field_634fef940a9a2';
			$exist_client_id     = get_field( 'goto_client_id', 'option' );
			$exist_client_secret = get_field( 'goto_client_secret', 'option' );
			$exist_code          = get_field( 'goto_code', 'option' );

			if ( $acf_data[ $client_id ] !== $exist_client_id && $acf_data[ $code ] === $exist_code ) {
				acf_add_validation_error(
					"acf[{$code}]",
					esc_html__( 'When changing the CLIENT_ID, it is necessary to update the code', 'cmt-elementor-addons' )
				);
			}

			if ( $acf_data[ $client_secret ] !== $exist_client_secret && $acf_data[ $code ] === $exist_code ) {
				acf_add_validation_error(
					"acf[{$code}]",
					esc_html__( 'When changing the CLIENT_SECRET, it is necessary to update the code', 'cmt-elementor-addons' )
				);
			}
		}

	}

	public function register_fields() {
		if ( function_exists( 'acf_add_local_field_group' ) ) {
			acf_add_local_field_group(
				array(
					'key'                   => 'group_634fee97cd45a',
					'title'                 => 'Settings',
					'fields'                => array(
						array(
							'key'               => 'field_634feec90a99c',
							'label'             => 'Panda',
							'name'              => '',
							'type'              => 'tab',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'placement'         => 'left',
							'endpoint'          => 0,
						),
						array(
							'key'               => 'field_634feeea0a99d',
							'label'             => 'Partner ID',
							'name'              => 'panda_partner_id',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 1,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
						array(
							'key'               => 'field_634fef180a99e',
							'label'             => 'Partner Secret Key',
							'name'              => 'panda_partner_secret_key',
							'type'              => 'password',
							'instructions'      => '',
							'required'          => 1,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
						),
						array(
							'key'               => 'field_634fef3b0a99f',
							'label'             => 'GoTo',
							'name'              => '',
							'type'              => 'tab',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'placement'         => 'left',
							'endpoint'          => 0,
						),
						array(
							'key'               => 'field_634fef580a9a0',
							'label'             => 'Client ID',
							'name'              => 'goto_client_id',
							'type'              => 'text',
							'instructions'      => '',
							'required'          => 1,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
						array(
							'key'               => 'field_634fef730a9a1',
							'label'             => 'Client Secret',
							'name'              => 'goto_client_secret',
							'type'              => 'password',
							'instructions'      => '',
							'required'          => 1,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
						),
						array(
							'key'               => 'field_634fef940a9a2',
							'label'             => 'Code',
							'name'              => 'goto_code',
							'type'              => 'textarea',
							'instructions'      => 'Need Code? How can get it, click <a href="https://developer.goto.com/guides/Authentication/03_HOW_accessToken/" target="_blank">here</a>',
							'required'          => 1,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'maxlength'         => '',
							'rows'              => 4,
							'new_lines'         => '',
						),
					),
					'location'              => array(
						array(
							array(
								'param'    => 'options_page',
								'operator' => '==',
								'value'    => 'cm-addons-settings',
							),
						),
					),
					'menu_order'            => 0,
					'position'              => 'normal',
					'style'                 => 'default',
					'label_placement'       => 'top',
					'instruction_placement' => 'label',
					'hide_on_screen'        => '',
					'active'                => true,
					'description'           => '',
				)
			);
		}
	}

}
