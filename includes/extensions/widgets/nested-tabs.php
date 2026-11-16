<?php
/**
 * Class CoreA11YforElementor\Extensions\Widgets\Nested_Tabs_Widget
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
use Elementor\Core\Settings\Manager;
use Elementor\Core\Base\Module;
use Elementor\Element_Base;
use Elementor\Group_Control_Box_Shadow;

/**
 * Class Nested_Tabs_Widget.
 *
 * @package CoreA11YforElementor\Extensions\Widgets
 */
class Nested_Tabs_Widget {

  /**
   * Prefix for all control names
   *
   * @var string
   */
  private $prefix;

  /**
   * Nested Tabs constructor.
   */
  public function __construct() {
    // Prefix for all new controls
    $this->prefix = 'core_a11y_';

    // Register New controls for Navigation section
    add_action( 'elementor/element/nested-tabs/section_tabs_style/before_section_end', [ $this, 'register_new_tabs_style_controls' ], 10, 2 );

  }

  /**
   * Register Nested Tabs widget Navigation Style controls.
   *
   * @param Controls_Stack $element Elementor element.
   * @param array         $args Section arguments.
   */
  public function register_new_tabs_style_controls( Controls_Stack $element, $args ) {

    //
    // -- Controls for arrows focus state, if visible -- //
    //

    // Set controls selectors to avoid repetition
    $control_selectors = '{{WRAPPER}}.elementor-widget-n-tabs .e-n-tab-title:focus-visible';

    // HEADING - Button Heading
    $element->add_control(
      $this->prefix.'heading_focus',
      [
        'type' => Controls_Manager::HEADING,
        'label' => __( 'Focus State', 'core-a11y-for-elementor' ),
        'separator' => 'before',
      ]
    );

    // COLOR - Button Focus Color
    $element->add_control(
      $this->prefix.'focus_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $control_selectors => 'color: {{VALUE}};',
        ],
      ]
    );

    // COLOR - Button Focus Color
    $element->add_control(
      $this->prefix.'focus_bg_color',
      [
        'label' => __( 'Background Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $control_selectors => 'background-color: {{VALUE}};',
        ],
      ]
    );

    // BOX SHADOW - Button Focus Box Shadow
    $element->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name' => $this->prefix.'focus_box_shadow',
        'label' => __( 'Box Shadow', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $control_selectors,
      ]
    );

    // SELECT - Outline Type
    $element->add_control(
      $this->prefix.'focus_outline_type',
      [
        'label' => __( 'Outline Type', 'core-a11y-for-elementor' ),
        'type' => \Elementor\Controls_Manager::SELECT,
        // 'default' => 'none',
        'options' => [
          ''  => __( 'Default', 'core-a11y-for-elementor' ),
          'none'  => __( 'None', 'core-a11y-for-elementor' ),
          'solid' => __( 'Solid', 'core-a11y-for-elementor' ),
          'double' => __( 'Double', 'core-a11y-for-elementor' ),
          'dotted' => __( 'Dotted', 'core-a11y-for-elementor' ),
          'dashed' => __( 'Dashed', 'core-a11y-for-elementor' ),
          'groove' => __( 'Groove', 'core-a11y-for-elementor' ),
        ],
        'selectors' => [
          $control_selectors => 'outline-style: {{value}};',
        ],
      ]
    );

    // SLIDER - Outline Width
    $element->add_control(
      $this->prefix.'focus_outline_width',
      [
        'label' => __( 'Width', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::SLIDER,
        'size_units' => [ 'px' ],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 20,
            'step' => 1,
          ],
        ],
        'selectors' => [
          $control_selectors => 'outline-width: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          $this->prefix.'focus_outline_type!' => ['', 'none'],
        ],
      ]
    );

    // SLIDER - Outline Offset
    $element->add_control(
      $this->prefix.'focus_outline_offset',
      [
        'label' => __( 'Offset', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::SLIDER,
        'size_units' => [ 'px' ],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 20,
            'step' => 1,
          ],
        ],
        'selectors' => [
          $control_selectors => 'outline-offset: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          $this->prefix.'focus_outline_type!' => ['', 'none'],
        ],
      ]
    );

    // COLOR - Outline Color
    $element->add_control(
      $this->prefix.'focus_outline_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $control_selectors => 'outline-color: {{VALUE}};',
        ],
        'condition' => [
          $this->prefix.'focus_outline_type!' => ['', 'none'],
        ],
      ]
    );

  }
}
new Nested_Tabs_Widget();