<?php
/**
 * Class CoreA11YforElementor\Extensions\Widgets\Icon_Icon_Box_Widget
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
 * Class Icon_Icon_Box_Widget.
 *
 * @package CoreA11YforElementor\Extensions\Widgets
 */
class Icon_Icon_Box_Widget {

  /**
   * Prefix for all control names
   *
   * @var string
   */
  private $prefix;

  /**
   * Image constructor.
   */
  public function __construct() {
    // Prefix for all new controls
    $this->prefix = 'core_a11y_';

    // Register New controls for Icon Widget
    add_action( 'elementor/element/icon/section_icon/before_section_end', [ $this, 'register_new_icon_controls' ], 10, 2 );

    // Register New controls for Icon Box Widget
    add_action( 'elementor/element/icon-box/section_icon/before_section_end', [ $this, 'register_new_icon_controls' ], 10, 2 );

  }

  /**
   * Register presentation image control.
   *
   * @param Controls_Stack $element Elementor element.
   * @param array         $args Section arguments.
   */
  public function register_new_icon_controls( Controls_Stack $element, $args ) {

    $element->start_injection(
        array(
          'of' => 'selected_icon',
          'at' => 'after',
        )
      );

    // SWITCHER - Presentation Image
    $element->add_control(
      $this->prefix.'presentation_image',
      [
        'label' => __( 'Presentation Image', 'core-a11y-for-elementor'),
        'description' => __( 'Select Yes to add the role of \'presentation\' to purely decorative images.', 'core-a11y-for-elementor'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __( 'Yes', 'core-a11y-for-elementor'),
        'label_off' => __( 'No', 'core-a11y-for-elementor'),
        'return_value' => 'yes',
        'default' => 'no',
        'prefix_class' => 'core-a11y-pres-img-',
      ]
    );

  }

}
new Icon_Icon_Box_Widget();