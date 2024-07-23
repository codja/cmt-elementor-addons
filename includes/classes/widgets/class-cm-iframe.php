<?php

namespace ElementorCmAddons\classes\widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Cm_Iframe extends Widget_Base {
	public function get_name() {
		return 'cm-iframe';
	}

	public function get_title() {
		return __( 'CM Iframe', 'cmt-elementor-addons' );
	}

	public function get_icon() {
		return 'eicon-site-identity';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Settings', 'elementor' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'elementor' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'elementor' ),
				'default'     => [
					'url' => '',
				],
			]
		);

		$this->add_control(
			'classes',
			[
				'label'       => __( 'Classes', 'elementor' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'https://your-link.com', 'elementor' ),
				'default'     => 'js-cmtrading-popup',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display(); ?>
		<div class="cm-iframe">
			<iframe src="<?php echo esc_url( $settings['link']['url'] ); ?>"></iframe>
			<a class="cm-iframe__link <?php echo esc_attr( $settings['classes'] ); ?>" href="#">
			</a>
		</div>
		<?php
	}

	protected function content_template() {
		?>
		<div class="cm-iframe">
			<iframe src="{{{ settings.link.url }}}"></iframe>
			<a class="cm-iframe__link {{{ settings.classes }}}" href="#">
			</a>
		</div>
		<?php
	}

}
