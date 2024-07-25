<?php

namespace ElementorCmAddons\classes\widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Widget_Base;
use ElementorCmAddons\includes\classes\helpers\Helpers;
use ElementorCmAddons\includes\classes\helpers\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Cm_Form extends Widget_Base {

	public function get_script_depends() {
		wp_register_script(
			'cm-form-script',
			ELEMENTOR_CM_ADDONS_URL . 'assets/js/cm-form.js',
			[ 'elementor-frontend' ],
			Utils::get_version_file( ELEMENTOR_CM_ADDONS_PATH . 'assets/js/cm-form.js' ),
			true
		);

		return [ 'cm-form-script' ];
	}

	public function get_style_depends() {
		wp_register_style(
			'cm-form-style',
			ELEMENTOR_CM_ADDONS_URL . 'assets/css/cm-form.css',
			[],
			Utils::get_version_file( ELEMENTOR_CM_ADDONS_PATH . 'assets/css/cm-form.css' )
		);

		return [ 'cm-form-style' ];
	}

	public function get_name() {
		return 'cm-form';
	}

	public function get_title() {
		return __( 'CM Form', 'cmt-elementor-addons' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return array( 'general' );
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'General', 'cmt-elementor-addons' ),
			)
		);

		$this->add_control(
			'form_action',
			array(
				'label'   => __( 'Form Action', 'cmt-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'panda',
				'options' => array(
					'antelope' => __( 'Antelope', 'cmt-elementor-addons' ),
					'panda'    => __( 'Panda', 'cmt-elementor-addons' ),
					'partners' => __( 'Partners', 'cmt-elementor-addons' ),
				),
				'toggle'  => true,
			)
		);

		$this->add_control(
			'form_action_notice',
			array(
				'label'     => __( 'Don\'t forget to add the following fields to the form: "Password Field", "Date of Birth Field"', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'control-name!' => 'partners',
				),
			)
		);

		$this->add_control(
			'post_partners_reg_action',
			array(
				'label'     => __( 'Post Registration Action', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'Partners',
				'options'   => array(
					'Partners'  => __( 'Partners', 'cmt-elementor-addons' ),
					'ThankYou'  => __( 'Thank You', 'cmt-elementor-addons' ),
					'CustomUrl' => __( 'Custom Url', 'cmt-elementor-addons' ),
				),
				'condition' => array( 'form_action' => 'partners' ),
			)
		);

		$this->add_control(
			'post_panda_reg_action',
			array(
				'label'     => __( 'Post Registration Action', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'Webtrader',
				'options'   => array(
					'Webtrader'           => __( 'Webtrader', 'cmt-elementor-addons' ),
					'WebsiteSecondForm'   => __( 'Website Second form', 'cmt-elementor-addons' ),
					'WebsiteSecondFormES' => __( 'Website Second form ES', 'cmt-elementor-addons' ),
					'CMTadawul'           => __( 'CMTadawul', 'cmt-elementor-addons' ),
					'es.cmtrading.com'    => __( 'Cmtrading ES', 'cmt-elementor-addons' ),
					'ThankYou'            => __( 'Thank You', 'cmt-elementor-addons' ),
					'CustomUrl'           => __( 'Custom Url', 'cmt-elementor-addons' ),
				),
				'condition' => array( 'form_action' => 'panda' ),
			)
		);

		$this->add_control(
			'post_antelope_reg_action',
			array(
				'label'     => __( 'Post Registration Action', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'Webtrader',
				'options'   => array(
					'CRM'       => __( 'CRM', 'cmt-elementor-addons' ),
					'ThankYou'  => __( 'Thank You', 'cmt-elementor-addons' ),
					'CustomUrl' => __( 'Custom Url', 'cmt-elementor-addons' ),
				),
				'condition' => array( 'form_action' => 'antelope' ),
			)
		);

		$this->add_control(
			'registration_webinar',
			array(
				'label'        => __( 'Registration for the webinar', 'cmt-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Enable', 'cmt-elementor-addons' ),
				'label_off'    => __( 'Disable', 'cmt-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'webinar_key',
			array(
				'label'     => __( 'Webinar Key', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array( 'registration_webinar' => 'yes' ),
			)
		);

		$this->add_control(
			'show_thank_you_page',
			array(
				'label'        => __( 'Thank you page(iframe)', 'cmt-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'cmt-elementor-addons' ),
				'label_off'    => __( 'Hide', 'cmt-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'language',
			array(
				'label'   => __( 'Language', 'cmt-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'en-US',
				'options' => array(
					'en-US' => __( 'English', 'cmt-elementor-addons' ),
					'ar-SA' => __( 'Arabic', 'cmt-elementor-addons' ),
					'es-ES' => __( 'Spanish', 'cmt-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'redirect_link',
			array(
				'label'       => __( 'Redirect Link', 'cmt-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your link', 'cmt-elementor-addons' ),
				'conditions'  => array(
					'relation' => 'or',
					'terms'    => [
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'     => 'form_action',
									'operator' => '===',
									'value'    => 'panda',
								],
								[
									'name'     => 'post_panda_reg_action',
									'operator' => '===',
									'value'    => 'CustomUrl',
								],
							],
						],
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'     => 'form_action',
									'operator' => '===',
									'value'    => 'antelope',
								],
								[
									'name'     => 'post_antelope_reg_action',
									'operator' => '===',
									'value'    => 'CustomUrl',
								],
							],
						],
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'     => 'form_action',
									'operator' => '===',
									'value'    => 'partners',
								],
								[
									'name'     => 'post_partners_reg_action',
									'operator' => '===',
									'value'    => 'CustomUrl',
								],
							],
						],
					],
				),
			)
		);

		$this->add_control(
			'include_referrer',
			array(
				'label'        => __( 'Enable Referrer', 'cmt-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'cmt-elementor-addons' ),
				'label_off'    => __( 'Hide', 'cmt-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'file_download',
			array(
				'label'        => __( 'Enable File Download', 'cmt-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'cmt-elementor-addons' ),
				'label_off'    => __( 'Hide', 'cmt-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'file_url',
			array(
				'label'       => __( 'File Url', 'cmt-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter file url', 'cmt-elementor-addons' ),
				'condition'   => array( 'file_download' => 'yes' ),
				'default'     => '',
			)
		);

		$this->add_control(
			'type_form',
			array(
				'label'   => __( 'Type', 'cmt-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'std',
				'options' => array(
					'std'    => __( 'Standart', 'cmt-elementor-addons' ),
					'popup'  => __( 'Popup', 'cmt-elementor-addons' ),
					'sticky' => __( 'Sticky', 'cmt-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'popup_form_notice',
			array(
				'label'     => __( 'Add the js-cmtrading-popup class to the element on clicking on which the form will appear in the popup', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => array(
					'type_form' => 'popup',
				),
			)
		);

		$this->add_control(
			'form_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'cmt-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your title', 'cmt-elementor-addons' ),
			)
		);

		$this->add_control(
			'title_size',
			array(
				'label'     => __( 'Title Font Size', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 5,
				'max'       => 100,
				'step'      => 1,
				'default'   => 35,
				'condition' => array( 'title!' => '' ),
			)
		);

		$this->add_control(
			'title_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label'       => __( 'Sub-title', 'cmt-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter your sub-title', 'cmt-elementor-addons' ),
			)
		);

		$this->add_control(
			'subtitle_size',
			array(
				'label'     => __( 'Subtitle Font Size', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 5,
				'max'       => 100,
				'step'      => 1,
				'default'   => 20,
				'condition' => array( 'subtitle!' => '' ),
			)
		);

		$this->add_control(
			'subtitle_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'submit_text',
			array(
				'label'   => __( 'Form Button Text', 'cmt-elementor-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'REGISTER',
			)
		);

		$this->add_control(
			'submit_size',
			array(
				'label'     => __( 'Button Font Size', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 5,
				'max'       => 100,
				'step'      => 1,
				'default'   => 20,
				'condition' => array( 'submit_text!' => '' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_fields',
			array(
				'label' => __( 'Form Fields', 'elementor-cmt-elementor-addons' ),
			)
		);

		$this->add_control(
			'show_firstname',
			array(
				'label'        => __( 'Show First Name Field', 'cmt-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'cmt-elementor-addons' ),
				'label_off'    => __( 'Hide', 'cmt-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'firstname_placeholder',
			array(
				'label'     => __( 'First Name Placeholder', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_firstname' => 'yes' ),
			)
		);

		$this->add_control(
			'firstname_error',
			array(
				'label'     => __( 'First Name Error Text', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_firstname' => 'yes' ),
			)
		);

		$this->add_control(
			'firstname_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'show_lastname',
			array(
				'label'        => __( 'Show Last Name Field', 'cmt-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'cmt-elementor-addons' ),
				'label_off'    => __( 'Hide', 'cmt-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'lastname_placeholder',
			array(
				'label'     => __( 'Last name Placeholder', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_lastname' => 'yes' ),
			)
		);

		$this->add_control(
			'lastname_error',
			array(
				'label'     => __( 'Last Name Error Text', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_lastname' => 'yes' ),
			)
		);

		$this->add_control(
			'lastname_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'show_email',
			array(
				'label'        => __( 'Show Email Address Field', 'cmt-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'cmt-elementor-addons' ),
				'label_off'    => __( 'Hide', 'cmt-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'email_placeholder',
			array(
				'label'     => __( 'Email Placeholder', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_email' => 'yes' ),
			)
		);

		$this->add_control(
			'email_suggestions',
			array(
				'label'       => __( 'Email Suggestions', 'cmt-elementor-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'condition'   => array( 'show_email' => 'yes' ),
				'fields'      => [
					[
						'name'        => 'email_suggestion',
						'label'       => esc_html__( 'Suggestion', 'cmt-elementor-addons' ),
						'type'        => Controls_Manager::TEXT,
						'default'     => '',
						'label_block' => true,
					],
				],
				'default'     => [
					[
						'email_suggestion' => esc_html( 'gmail.com' ),
					],
					[
						'email_suggestion' => esc_html( 'hotmail.com' ),
					],
					[
						'email_suggestion' => esc_html( 'outlook.com' ),
					],
					[
						'email_suggestion' => esc_html( 'yahoo.com' ),
					],
					[
						'email_suggestion' => esc_html( 'skype.com' ),
					],
				],
				'title_field' => '{{{ email_suggestion }}}',
			)
		);

		$this->add_control(
			'email_error',
			array(
				'label'     => __( 'Email Error Text', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_email' => 'yes' ),
			)
		);

		$this->add_control(
			'email_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'show_country',
			array(
				'label'        => __( 'Show Country Field', 'cmt-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'cmt-elementor-addons' ),
				'label_off'    => __( 'Hide', 'cmt-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'country_placeholder',
			array(
				'label'     => __( 'Country Placeholder', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_country' => 'yes' ),
			)
		);

		$this->add_control(
			'country_error',
			array(
				'label'     => __( 'Country Error Text', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_country' => 'yes' ),
			)
		);

		$this->add_control(
			'country_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'show_phone',
			array(
				'label'        => __( 'Show Phone Field', 'cmt-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'cmt-elementor-addons' ),
				'label_off'    => __( 'Hide', 'cmt-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'phone_placeholder',
			array(
				'label'     => __( 'Phone Placeholder', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_phone' => 'yes' ),
			)
		);

		$this->add_control(
			'phone_error',
			array(
				'label'     => __( 'Phone Error Text', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_phone' => 'yes' ),
			)
		);

		$this->add_control(
			'phone_digits_error',
			array(
				'label'     => __( 'Phone Digits Error Text', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_phone' => 'yes' ),
			)
		);

		$this->add_control(
			'phone_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'password_autogenerate',
			array(
				'label'        => __( 'Autogenerate', 'cmt-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'condition'    => array( 'show_password' => 'yes' ),
				'label_on'     => __( 'Yes', 'cmt-elementor-addons' ),
				'label_off'    => __( 'No', 'cmt-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'show_password',
			array(
				'label'        => __( 'Show Password Field', 'cmt-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'cmt-elementor-addons' ),
				'label_off'    => __( 'Hide', 'cmt-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'password_placeholder',
			array(
				'label'     => __( 'Password Placeholder', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_password' => 'yes' ),
			)
		);

		$this->add_control(
			'password_error',
			array(
				'label'     => __( 'Password Error Text', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_password' => 'yes' ),
			)
		);

		$this->add_control(
			'password_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'show_birthday',
			array(
				'label'        => __( 'Show Date of Birth Field', 'cmt-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'cmt-elementor-addons' ),
				'label_off'    => __( 'Hide', 'cmt-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'birthday_placeholder',
			array(
				'label'     => __( 'Date of Birth Placeholder', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_birthday' => 'yes' ),
			)
		);

		$this->add_control(
			'birthday_error',
			array(
				'label'     => __( 'Date of Birth Error Text', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_birthday' => 'yes' ),
			)
		);

		$this->add_control(
			'birthday_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'show_promocode',
			array(
				'label'        => __( 'Show Promocode Field', 'cmt-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'cmt-elementor-addons' ),
				'label_off'    => __( 'Hide', 'cmt-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'promocode_placeholder',
			array(
				'label'     => __( 'Promocode Placeholder', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_promocode' => 'yes' ),
			)
		);

		$this->add_control(
			'promocode_error',
			array(
				'label'     => __( 'Promocode Error Text', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_promocode' => 'yes' ),
			)
		);

		$this->add_control(
			'promocode_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'show_terms',
			array(
				'label'        => __( 'Show T&C Field', 'cmt-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'cmt-elementor-addons' ),
				'label_off'    => __( 'Hide', 'cmt-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'terms_links',
			array(
				'label'     => __( 'Terms Links', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::WYSIWYG,
				'condition' => array( 'show_terms' => 'yes' ),
			)
		);

		$this->add_control(
			'terms_text',
			array(
				'label'     => __( 'Terms Text', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXTAREA,
				'rows'      => 5,
				'condition' => array( 'show_terms' => 'yes' ),
			)
		);

		$this->add_control(
			'agree_error',
			array(
				'label'     => __( 'Agree to Terms Error Text', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => array( 'show_terms' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Form Styles', 'elementor-cmt-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'form_style',
			array(
				'label'   => __( 'Form style', 'cmt-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'vertical'   => __( 'Vertical', 'cmt-elementor-addons' ),
					'horizontal' => __( 'Horizontal', 'cmt-elementor-addons' ),
				),
				'default' => 'vertical',
			)
		);

		$this->add_control(
			'font_family',
			array(
				'label'     => __( 'Form Font Family', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::FONT,
				'default'   => 'Oswald',
				'selectors' => array(
					'{{WRAPPER}} .title' => 'font-family: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'background_color',
			array(
				'label'     => __( 'Background Color', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .background' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'general_form_styles_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Title Color', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'title_font_family',
			array(
				'label'     => __( 'Title Font Family', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::FONT,
				'default'   => 'Oswald',
				'selectors' => array(
					'{{WRAPPER}} .title' => 'font-family: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'title_font_weight',
			array(
				'label'   => __( 'Title Font Weight', 'cmt-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'normal' => __( 'Regular', 'cmt-elementor-addons' ),
					'bold'   => __( 'Bold', 'cmt-elementor-addons' ),
					'light'  => __( 'Light', 'cmt-elementor-addons' ),
				),
				'default' => 'bold',
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => __( 'Sub-title Color', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .subtitle' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'subtitle_font_family',
			array(
				'label'     => __( 'Subtitle Font Family', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::FONT,
				'default'   => 'Oswald',
				'selectors' => array(
					'{{WRAPPER}} .title' => 'font-family: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'subtitle_font_weight',
			array(
				'label'   => __( 'Subtitle Font Weight', 'cmt-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'normal' => __( 'Regular', 'cmt-elementor-addons' ),
					'bold'   => __( 'Bold', 'cmt-elementor-addons' ),
					'light'  => __( 'Light', 'cmt-elementor-addons' ),
				),
				'default' => 'bold',
			)
		);

		$this->add_control(
			'title_align',
			array(
				'label'   => __( 'Title and Subtitle Text Align', 'cmt-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'left'   => __( 'Left', 'cmt-elementor-addons' ),
					'center' => __( 'Center', 'cmt-elementor-addons' ),
					'right'  => __( 'Right', 'cmt-elementor-addons' ),
				),
				'default' => 'left',
			)
		);

		$this->add_control(
			'title_styles_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'submit_background',
			array(
				'label'   => __( 'Button Background Color', 'cmt-elementor-addons' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'linear-gradient(#f8d017, #da941a)',
			)
		);

		$this->add_control(
			'submit_color',
			array(
				'label'     => __( 'Button Text Color', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .background' => 'color: {{VALUE}}',
				),
				'default'   => '#000000',
			)
		);

		$this->add_control(
			'submit_font_weight',
			array(
				'label'   => __( 'Button Font Weight', 'cmt-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'normal' => __( 'Regular', 'cmt-elementor-addons' ),
					'bold'   => __( 'Bold', 'cmt-elementor-addons' ),
					'light'  => __( 'Light', 'cmt-elementor-addons' ),
				),
				'default' => 'normal',
			)
		);

		$this->add_control(
			'submit_font_family',
			array(
				'label'     => __( 'Button Font Family', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::FONT,
				'default'   => 'Oswald',
				'selectors' => array(
					'{{WRAPPER}} .title' => 'font-family: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'submit_border_radius',
			array(
				'label'   => __( 'Button Border Radius', 'cmt-elementor-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 5,
				'max'     => 100,
				'step'    => 1,
				'default' => 0,
			)
		);

		$this->add_control(
			'submit_styles_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'terms_color',
			array(
				'label'     => __( 'Terms Text Color', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .background' => 'color: {{VALUE}}',
				),
				'default'   => '#ffffff',
			)
		);

		$this->add_control(
			'terms_styles_divider',
			array(
				'type' => Controls_Manager::DIVIDER,
			)
		);

		$this->add_control(
			'error_color',
			array(
				'label'     => __( 'Field Error Color', 'cmt-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Color::get_type(),
					'value' => Color::COLOR_1,
				),
				'selectors' => array(
					'{{WRAPPER}} .background' => 'color: {{VALUE}}',
				),
				'default'   => '#FF0000',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$settings = Helpers::instance()->lang_settings( $settings, $settings['language'] );

		$routes = [
			'antelope' => 'register_antelope',
			'partners' => 'register_partners',
			'panda'    => 'register_panda',
		];

		switch ($settings['type_form']) {
			case 'popup':
				$type_class = ' cm-form-container_popup';
				break;
			case 'sticky':
				$type_class = ' cm-form-container_sticky';
				break;
			default:
				$type_class = '';
		}

		$is_popup = $settings['type_form'] === 'popup';
		$show_thank_you_page = $settings['show_thank_you_page'];
		$popup_text = 'Creating Account...';

		if ($settings['language'] === 'ar-SA') {
			$popup_text = 'فتح حساب تداول';
		}

		if ($settings['file_download'] === 'yes' && $settings['file_url'] !== '') {
			$popup_text = 'Thank you for downloading our ebook';
		}
		?>
		<div class="cm-form-container <?php echo esc_attr( $settings['language'] . ' ' . $settings['form_style'] . $type_class ); ?>">
		<div class="cm-form-wrap" style="background-color: <?php echo esc_attr( $settings['background_color'] ); ?>;">
			<?php if ( false && $show_thank_you_page === 'yes' ) : // temp ?>
				<iframe class="cm-form-thank-you-page" src="https://lp2.cmtrading.com/thankyoupage"
				        scrolling="no"></iframe>
			<?php endif; ?>

			<?php if ( $is_popup ) : ?>
			<div class="cm-form-wrap__popup-content-wrap">
				<?php endif; ?>

				<h3 style="color: <?php echo esc_attr( $settings['title_color'] ); ?>; font-size: <?php echo esc_attr( $settings['title_size'] ); ?>px; text-align: <?php echo esc_attr( $settings['title_align'] ); ?>; font-family: <?php echo esc_attr( $settings['title_font_family'] ); ?>; font-weight: <?php echo esc_attr( $settings['title_font_weight'] ); ?>;"
				    class="cm-form-title">
					<?php echo esc_html( $settings['title'] ); ?>
				</h3>

				<h4 style="color: <?php echo esc_attr( $settings['subtitle_color'] ); ?>; font-size: <?php echo esc_attr( $settings['subtitle_size'] ); ?>px; text-align: <?php echo esc_attr( $settings['title_align'] ); ?>; font-family: <?php echo esc_attr( $settings['subtitle_font_family'] ); ?>; font-weight: <?php echo esc_attr( $settings['subtitle_font_weight'] ); ?>;"
				    class="cm-form-subtitle">
					<?php echo esc_html( $settings['subtitle'] ); ?>
				</h4>

				<form action="/" method="POST"
				      class="cm-form <?php echo esc_attr( $settings['language'] === 'ar-SA' ? ' arabic' : '' ) . esc_attr( $settings['file_download'] === 'yes' ? ' file-download' : '' ) . ( $is_popup ? ' popup-form' : '' ); ?>"
				      id="frm-lp" name="frm-lp"
				      data-route="<?php echo esc_attr( $routes[ $settings['form_action'] ] ); ?>">
					<input type="hidden" name="language" value="<?php echo esc_attr( $settings['language'] ); ?>"/>

					<?php if ( $settings['form_action'] === 'partners' ) : ?>
						<input type="hidden" name="postRegistrationAction"
						       value="<?php echo esc_attr( $settings['post_partners_reg_action'] ); ?>"/>
					<?php endif; ?>

					<?php if ( $settings['form_action'] === 'panda' ) : ?>
						<input type="hidden" name="postRegistrationAction"
						       value="<?php echo esc_attr( $settings['post_panda_reg_action'] ); ?>"/>
					<?php endif; ?>

					<?php if ( $settings['include_referrer'] ) : ?>
						<input type="hidden" name="landingPageUrl" value=""/>
					<?php endif; ?>

					<input type="hidden" name="referral" value=""/>
					<input type="hidden" name="vl-cid" value="" id="rgbc-gcid"/>

					<?php if ( $settings['post_panda_reg_action'] === 'CustomUrl' || $settings['post_partners_reg_action'] === 'CustomUrl' ) : ?>
						<input type="hidden" name="redirectToPage"
						       value="<?php echo esc_attr( $settings['redirect_link'] ); ?>"/>
					<?php endif; ?>

					<?php if ( $settings['registration_webinar'] === 'yes' ) : ?>
						<input type="hidden" name="webinar_key"
						       value="<?php echo esc_attr( $settings['webinar_key'] ); ?>"/>
					<?php endif; ?>

					<?php if ( $settings['file_download'] && $settings['file_url'] !== '' ) : ?>
						<a id="file-download-link" href="<?php echo esc_url( $settings['file_url'] ); ?>"
						   style="display: none;" download></a>
					<?php endif; ?>

					<?php if ( $settings['show_firstname'] ) : ?>
						<div class="cm-form-input-container cm-form-firstname-container">
							<input id="firstname"
							       style="font-family: <?php echo esc_attr( $settings['font_family'] ); ?>;" type="text"
							       name="firstname" class="cm-form-firstname"
							       placeholder="<?php echo esc_attr( $settings['firstname_placeholder'] ); ?>"/>
							<p id="firstname-error" class="cm-form-error"
							   style="color: <?php echo esc_attr( $settings['error_color'] ); ?>; font-family: <?php echo esc_attr( $settings['font_family'] ); ?>;"><?php echo esc_html( $settings['firstname_error'] ); ?></p>
						</div>
					<?php endif; ?>

					<?php if ( $settings['show_lastname'] ) : ?>
						<div class="cm-form-input-container cm-form-lastname-container">
							<input id="lastname"
							       style="font-family: <?php echo esc_attr( $settings['font_family'] ); ?>;" type="text"
							       name="lastname" class="cm-form-lastname"
							       placeholder="<?php echo esc_attr( $settings['lastname_placeholder'] ); ?>"/>
							<p id="lastname-error" class="cm-form-error"
							   style="color: <?php echo esc_attr( $settings['error_color'] ); ?>; font-family: <?php echo esc_attr( $settings['font_family'] ); ?>;"><?php echo esc_html( $settings['lastname_error'] ); ?></p>
						</div>
					<?php endif; ?>

					<?php if ( $settings['show_email'] ) : ?>
						<div class="cm-form-input-container cm-form-email-container">
							<input id="email" style="font-family: <?php echo esc_attr( $settings['font_family'] ); ?>;"
							       type="email" name="email" class="cm-form-email"
							       placeholder="<?php echo esc_attr( $settings['email_placeholder'] ); ?>"/>
							<p id="email-error" class="cm-form-error"
							   style="color: <?php echo esc_attr( $settings['error_color'] ); ?>; font-family: <?php echo esc_attr( $settings['font_family'] ); ?>;"><?php echo esc_html( $settings['email_error'] ); ?></p>
							<?php if ( $settings['email_suggestions'] ) : ?>
								<ul class="cm-form-suggestions cm-form-hidden">
									<?php foreach ( $settings['email_suggestions'] as $suggestion ) : ?>
										<?php if ( ! empty( $suggestion['email_suggestion'] ) ) : ?>
											<li class="cm-form-suggestions__suggestion"><span
													class="cm-form-suggestions__placeholder"></span><?php echo esc_html( '@' . $suggestion['email_suggestion'] ); ?>
											</li>
										<?php endif; ?>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( $settings['show_country'] ) :
						echo Helpers::instance()->get_country_options( $settings );
					endif; ?>

					<?php if ( $settings['show_phone'] ) : ?>
						<div class="cm-form-input-container cm-form-phone-container">
							<input id="phonecountry" type="phone"
							       style="font-family: <?php echo esc_attr( $settings['font_family'] ); ?>;"
							       name="phonecountry" value="+" class="cm-form-phone-prefix" readonly/>
							<input id="phone" style="font-family: <?php echo esc_attr( $settings['font_family'] ); ?>;"
							       type="phone" pattern="\d*" name="phone" class="cm-form-phone"
							       placeholder="<?php echo esc_attr( $settings['phone_placeholder'] ); ?>"/>
							<p id="phone-error" class="cm-form-error"
							   style="color: <?php echo esc_attr( $settings['error_color'] ); ?>; font-family: <?php echo esc_attr( $settings['font_family'] ); ?>;"><?php echo esc_html( $settings['phone_error'] ); ?></p>
							<p id="phone-digits-error" class="cm-form-error"
							   style="color: <?php echo esc_attr( $settings['error_color'] ); ?>; font-family: <?php echo esc_attr( $settings['font_family'] ); ?>;"><?php echo esc_html( $settings['phone_digits_error'] ); ?></p>
						</div>
					<?php endif; ?>

					<?php if ( $settings['show_birthday'] ) : ?>
						<div class='cm-form-input-container cm-form-birthday-container'>
							<input id='birthday' style='font-family: <?php echo esc_attr( $settings['font_family'] ); ?>;' type='date' name='birthday' class='cm-form-birthday' required/>
							<label for='birthday'><?php echo esc_html( $settings['birthday_placeholder'] ); ?></label>
							<p id='birthday-error' class='cm-form-error' style='color: <?php echo esc_attr( $settings['error_color'] ); ?>; font-family: <?php echo esc_attr( $settings['font_family'] ); ?>;'><?php echo esc_html( $settings['birthday_error'] ); ?></p>
						</div>
					<?php endif; ?>

					<?php if ( $settings['show_promotion'] === 'yes' ) : ?>
						<div class="cm-form-promotion-container">
							<div class="cm-form-promotion-field">
								<input type="checkbox" name="promotion" value="yes"/>
								<p class="cm-form-promotion"><?php echo esc_html( $settings['promotion_text'] ); ?></p>
							</div>
						</div>
					<?php endif; ?>

					<div class="cm-form-submit-container">
						<button type="submit" class="cm-form-submit"
						        style="background-color: <?php echo esc_attr( $settings['submit_bg_color'] ); ?>; color: <?php echo esc_attr( $settings['submit_color'] ); ?>; font-family: <?php echo esc_attr( $settings['submit_font_family'] ); ?>; font-size: <?php echo esc_attr( $settings['submit_font_size'] ); ?>px; font-weight: <?php echo esc_attr( $settings['submit_font_weight'] ); ?>;">
							<?php echo esc_html( $settings['submit_text'] ); ?>
						</button>
					</div>

					<?php if ( $settings['show_security_notice'] ) : ?>
						<div class="cm-form-security-container">
							<img class="cm-form-security-img"
							     src="<?php echo esc_attr( $settings['security_image']['url'] ); ?>"
							     alt="<?php echo esc_attr( $settings['security_image_alt'] ); ?>"/>
							<p class="cm-form-security-text"><?php echo esc_html( $settings['security_notice'] ); ?></p>
						</div>
					<?php endif; ?>

				</form>
			</div>
		</div>
		<?php if ( $is_popup ) : ?>
			</div>
		<?php endif;
	}

	protected function content_template() {
		?>
		<#
		switch ( settings.type_form ) {
			case 'popup':
				var typeClass = ' cm-form-container_popup';
				break;
			case 'sticky':
				var typeClass = ' cm-form-container_sticky';
				break;
			default:
				var typeClass = '';
		}
		var formClass = settings.type_form === 'popup' ? ' popup-form' : '';
		#>
		<div class='cm-form-container {{{settings.language}}} {{{settings.form_style}}} {{{typeClass}}}'>
			<div class='cm-form-wrap' style="background-color: {{{settings.background_color}}};">
				<h3 class='cm-form-title' style='color: {{{settings.title_color}}}; font-size: {{{settings.title_size}}}px; text-align: {{{settings.title_align}}}; font-family: {{{settings.title_font_family}}}; font-weight: {{{settings.title_font_weight}}}'>{{{settings.title}}}</h3>
				<h4 class='cm-form-subtitle' style='color: {{{settings.subtitle_color}}}; font-size: {{{settings.subtitle_size}}}px; text-align: {{{settings.title_align}}}; font-family: {{{settings.subtitle_font_family}}}; font-weight: {{{settings.subtitle_font_weight}}}'>{{{settings.subtitle}}}</h4>
				<form method='POST' class='cm-form {{{formClass}}}' id='frm-lp' name='frm-lp'>
					<input type='hidden' name='language' value='{{{settings.language}}}' />
					<input type="hidden" name="vl-cid" value="" id="rgbc-gcid"/>

					<# if(settings.form_action === "partners") #> <input type='hidden' name='postRegistrationAction' value='{{{settings.post_partners_reg_action}}}' />

					<# if(settings.form_action === "panda") #> <input type='hidden' name='postRegistrationAction' value='{{{settings.post_panda_reg_action}}}' />

					<# if(settings.include_referrer) #> <input type='hidden' name='landingPageUrl' value='' />

					<# if(settings.show_firstname) { #>
						<div class='cm-form-input-container cm-form-firstname-container'>
							<input id='firstname' style='font-family: {{{settings.font_family}}};' type='text' name='firstname' class='cm-form-firstname' placeholder='{{{settings.firstname_placeholder}}}' />
							<p id='firstname-error' class='cm-form-error' style='color: {{{settings.error_color}}}; font-family: {{{settings.font_family}}};'>{{{settings.firstname_error}}}</p>
						</div>
					<# } #>

					<# if(settings.show_lastname) { #>
						<div class='cm-form-input-container cm-form-lastname-container'>
							<input id='lastname' style='font-family: {{{settings.font_family}}};' type='text' name='lastname' class='cm-form-lastname' placeholder='{{{settings.lastname_placeholder}}}' />
							<p id='lastname-error' class='cm-form-error' style='color: {{{settings.error_color}}}; font-family: {{{settings.font_family}}};'>{{{settings.lastname_error}}}</p>
						</div>
					<# } #>

					<# if(settings.show_email) { #>
						<div class='cm-form-input-container cm-form-email-container'>
							<input id='email' style='font-family: {{{settings.font_family}}};' type='email' name='email' class='cm-form-email' placeholder='{{{settings.email_placeholder}}}' />
							<p id='email-error' class='cm-form-error' style='color: {{{settings.error_color}}}; font-family: {{{settings.font_family}}};'>{{{settings.email_error}}}</p>
						</div>
					<# } #>

					<# if(settings.show_country) { #>
						<div class='cm-form-input-container cm-form-country-container'>
							<select id='countryiso2' style='font-family: {{{settings.font_family}}};' name='countryiso2' class='cm-form-country'>
								<option value='' disabled selected hidden>{{{settings.country_placeholder}}}</option>
							</select>
						<p id='countryiso2-error' class='cm-form-error' style='color: {{{settings.error_color}}}; font-family: {{{settings.font_family}}};'>{{{settings.country_error}}}</p>
						</div>
					<# } #>

					<# if(settings.show_phone) { #>
						<div class='cm-form-input-container cm-form-phone-container'>
							<input id='phonecountry' type='phone' style='font-family: {{{settings.font_family}}};' name='phonecountry' value='+' class='cm-form-phone-prefix' readonly />
							<input id='phone' style='font-family: {{{settings.font_family}}};' type='phone' pattern='\d*' name='phone' class='cm-form-phone' placeholder='{{{settings.phone_placeholder}}}' />
							<p id='phone-error' class='cm-form-error' style='color: {{{settings.error_color}}}; font-family: {{{settings.font_family}}};'>{{{settings.phone_error}}}</p>
							<p id='phone-digits-error' class='cm-form-error' style='color: {{{settings.error_color}}}; font-family: {{{settings.font_family}}};'>{{{settings.phone_digits_error}}}</p>
						</div>
					<# } #>

					<# var type_password_input = settings.show_password ? 'password' : 'hidden'; #>
					<div class='cm-form-input-container cm-form-password-container'>
						<input id='password' style='font-family: {{{settings.font_family}}};' type='{{{type_password_input}}}' name='password' class='cm-form-password' placeholder='{{{settings.password_placeholder}}}' />
						<p id='password-error' class='cm-form-error' style='color: {{{settings.error_color}}}; font-family: {{{settings.font_family}}};'>{{{settings.password_error}}}</p>
					</div>

					<# if(settings.show_birthday) { #>
					<div class='cm-form-input-container cm-form-birthday-container'>
						<input id='birthday' style='font-family: {{{settings.font_family}}};' type='date' name='birthday' class='cm-form-birthday' required/>
						<label for='birthday'>{{{settings.birthday_placeholder}}}</label>
						<p id='birthday-error' class='cm-form-error' style='color: {{{settings.error_color}}}; font-family: {{{settings.font_family}}};'>{{{settings.birthday_error}}}</p>
					</div>
					<# } #>

					<# if(settings.show_promocode) { #>
					<div class='cm-form-input-container cm-form-promocode-container'>
						<input id='promocode' style='font-family: {{{settings.font_family}}};' type='text' name='promocode' class='cm-form-promocode' placeholder='{{{settings.promocode_placeholder}}}' />
						<p id='promocode-error' class='cm-form-error' style='color: {{{settings.error_color}}}; font-family: {{{settings.font_family}}};'>{{{settings.promocode_error}}}</p>
					</div>
					<# } #>

					<div class='cm-form-input-container cm-form-submit-container'>
						<button style='background: {{{settings.submit_background}}}; color: {{{settings.submit_color}}}; font-size: {{{settings.submit_size}}}px;
							font-weight: {{{settings.submit_font_weight}}}; border-radius: {{{settings.submit_border_radius}}}px; font-family: {{{settings.submit_font_family}}};'
								class='button cm-form-submit'>{{{settings.submit_text}}}</button>
					</div>

					<div class='cm-form-input-container cm-form-terms-container'>
						<div id='cm-form-terms-inner-container'>
							<# if(settings.show_terms) { #>
							<div class='cm-form-input-container cm-form-terms-container'>
								<input type='checkbox' style='font-family: {{{settings.font_family}}};' checked class='cm-form-agree' name='agree' />
								<p id='agree-error' class='cm-form-error' style='color: {{{settings.error_color}}}; font-family: {{{settings.font_family}}};'>{{{settings.agree_error}}}</p>
								<div id='cm-form-terms-inner-container'>
							<span style='color: {{{settings.terms_color}}}; font-family: {{{settings.font_family}}};' class='cm-form-terms-text'>
								{{{settings.terms_links}}}
							</span>
									<span style='color: {{{settings.terms_color}}}; font-family: {{{settings.font_family}}};' class='cm-form-terms-text'>{{{settings.terms_text}}}</span>
								</div>
							</div>
							<# } #>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php
	}

}
