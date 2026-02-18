<?php
/**
 * Class CoreA11YforElementor\Extensions\Widgets\Counter_Widget
 *
 * @package CoreA11YforElementor
 */

namespace CoreA11YforElementor\Extensions\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Group_Control_Box_Shadow;

/**
 * Class Counter_Widget.
 *
 * @package CoreA11YforElementor\Extensions\Widgets
 */
class Counter_Widget {

  /**
   * Prefix for all control names
   *
   * @var string
   */
  private $prefix;

  /**
   * Counter constructor.
   */
  public function __construct() {
    // Prefix for all new controls
    $this->prefix = 'core_a11y_';

    // Register New controls for Counter widget height
    add_action( 'elementor/element/counter/section_counter/before_section_end', [ $this, 'register_new_counter_controls' ], 10, 2 );

  }

  /**
   * Register Counter widget height control.
   *
   * @param Controls_Stack $element Elementor element.
   * @param array         $args Section arguments.
   */
  public function register_new_counter_controls( Controls_Stack $element, $args ) {

    $element->start_injection(
      array(
        'of' => 'duration',
        'at' => 'after',
      )
    );

    // SWITCHER - Respect Reduced Motion
    $element->add_control(
      $this->prefix.'counter_reduced_motion',
      [
        'label' => __( 'Respect Reduced Motion', 'core-a11y-for-elementor'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __( 'Yes', 'core-a11y-for-elementor'),
        'label_off' => __( 'No', 'core-a11y-for-elementor'),
        'return_value' => 'yes',
        'default' => 'yes',
        'prefix_class' => 'core-a11y-counter-hide-animation-',
      ]
    );

    $element->add_control(
      $this->prefix.'counter_note',
      [
        'type' => \Elementor\Controls_Manager::RAW_HTML,
        'raw' => esc_html__( 'Disrespecting a user\'s reduced motion preference will negatively impact your WCAG score, and is not best practice. Use with caution!', 'core-a11y-for-elementor' ),
        'content_classes' => 'cuxce-alert cuxce-alert-warning',
        'condition' => [
          $this->prefix.'counter_reduced_motion!' => ['yes'],
        ],
      ]
    );

    $element->end_injection();

  }

}
new Counter_Widget();