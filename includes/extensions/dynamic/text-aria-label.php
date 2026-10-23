<?php

use Elementor\Core\DynamicTags\Data_Tag;
use ElementorPro\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Core_A11y_Text_Aria_Label extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'core-a11y-for-elementor-text-aria-label';
	}

	public function get_title() {
		return __( 'Text with Custom aria-label', 'core-a11y-for-elementor' );
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function get_group() {
		return 'core-a11y-for-elementor';
	}

  protected function register_controls() {
    $this->add_control(
      'pre_sr_text',
      [
        'label'   => __( 'SR Only Text (Before)', 'core-a11y-for-elementor' ),
        'type'    => \Elementor\Controls_Manager::TEXT,
        'dynamic'		=> [ 'active' => true ],
      ]
    );
    $this->add_control(
      'visible_text',
      [
        'label'   => __( 'Visible Text', 'core-a11y-for-elementor' ),
        'type'    => \Elementor\Controls_Manager::TEXT,
        'dynamic'		=> [ 'active' => true ],
      ]
    );
    $this->add_control(
      'post_sr_text',
      [
        'label'   => __( 'SR Only Text (After)', 'core-a11y-for-elementor' ),
        'type'    => \Elementor\Controls_Manager::TEXT,
        'dynamic'		=> [ 'active' => true ],
      ]
    );
  }

	public function render() {
		$settings    = $this->get_settings();
    $pre_sr_text    = isset( $settings['pre_sr_text'] ) ? $settings['pre_sr_text'] : '';
    $visible_text    = isset( $settings['visible_text'] ) ? $settings['visible_text'] : '';
    $post_sr_text    = isset( $settings['post_sr_text'] ) ? $settings['post_sr_text'] : '';

    $value = '';

    if ( !empty($pre_sr_text) ) {
			$value .= '<span class="sr-only">'.esc_html($pre_sr_text).'</span>';
		}

    if ( !empty($visible_text) ) {
			$value .= esc_html($visible_text);
		}

    if ( !empty($post_sr_text) ) {
			$value .= '<span class="sr-only">'.esc_html($post_sr_text).'</span>';
		}

		if ( !empty($value) ) {
			echo $value;
		}
	}
}