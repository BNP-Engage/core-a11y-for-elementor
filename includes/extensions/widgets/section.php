<?php
/**
 * Class CoreA11YforElementor\Extensions\Kit\Section_Style
 *
 * @package CoreA11YforElementor
 */

namespace CoreA11YforElementor\Extensions\Kit;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Stack;
use Elementor\Core\Settings\Manager;
use Elementor\Core\Base\Module;
use Elementor\Element_Base;

/**
 * Class Section_Style.
 *
 * @package CoreElements\Elementor\Kit\Sections
 */
class Section_Style extends Module {

	/**
	 * Tab ID to add settings to
	 *
	 * @var string
	 */
  private $settings_tab;

	/**
	 * Outer_Section_Padding constructor.
	 */
	public function __construct() {
		// Set the tab
		$this->settings_tab = 'theme-style-core-a11y-for-elementor';
    
    // Register change to Section widget
    add_action( 'elementor/element/section/section_background/before_section_end', array( $this, 'tweak_section_widget' ) );

  }

	/**
	 * Get module name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'core-a11y-for-elementor-outer-section-padding';
	}

  
  /**
	 * Tweak default Section widget.
	 *
	 * @param Element_Base $element Element_Base Class.
	 */
	public function tweak_section_widget( Element_Base $element ) {
		$element->start_injection(
			array(
				'of' => 'background_play_on_mobile',
				'at' => 'after',
			)
		);

    // SWITCHER - Respect Reduced Motion
    $element->add_control(
      'core_a11y_video_reduced_motion',
      [
        'label' => __( 'Respect Reduced Motion', 'core-a11y-for-elementor'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __( 'Yes', 'core-a11y-for-elementor'),
        'label_off' => __( 'No', 'core-a11y-for-elementor'),
        'return_value' => 'yes',
        'default' => 'yes',
        'prefix_class' => 'core-a11y-hide-video-bg-',
        'condition' => [
          'background_background' => ['video']
        ]
      ]
    );

    $element->add_control(
      'core_a11y_bg_note',
      [
        'type' => \Elementor\Controls_Manager::RAW_HTML,
        'raw' => esc_html__( 'Disrespecting a user\'s reduced motion preference will negatively impact your WCAG score, and is not best practice. Use with caution!', 'core-elements' ),
        'content_classes' => 'cuxce-alert cuxce-alert-warning',
        'condition' => [
          'core_a11y_video_reduced_motion!' => ['yes'],
          'background_background' => ['video']
        ],
      ]
    );

		$element->end_injection();
	}

}
new Section_Style();