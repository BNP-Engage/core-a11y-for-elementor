<?php
/**
 * Class CoreA11YforElementor\Extensions\Widgets\Buttons_Widget
 *
 * @package CoreA11YforElementor
 */

namespace CoreA11YforElementor\Extensions\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Group_Control_Box_Shadow;

/**
 * Class Buttons_Widget.
 *
 * @package CoreA11YforElementor\Extensions\Widgets
 */
class Buttons_Widget {

  /**
   * Prefix for all control names
   *
   * @var string
   */
  private $prefix;

  /**
   * Buttons constructor.
   */
  public function __construct() {
    // Prefix for all new controls
    $this->prefix = 'core_a11y_';

    // Register New controls for Button widget Accessibility
    add_action( 'elementor/element/button/section_style/before_section_end', [ $this, 'register_new_style_controls' ], 10, 2 );

  }

  /**
   * Register Buttons widget Accessibility controls.
   *
   * @param Controls_Stack $element Elementor element.
   * @param array         $args Section arguments.
   */
  public function register_new_style_controls( Controls_Stack $element, $args ) {

    // Set controls selectors to avoid repetition
    $control_selectors = '{{WRAPPER}} a.elementor-button:focus-visible';

    // HEADING - Buttons Heading
    $element->add_control(
      $this->prefix.'heading_buttons_focus',
      [
        'type' => Controls_Manager::HEADING,
        'label' => __( 'Focus State', 'core-a11y-for-elementor' ),
        'separator' => 'before',
      ]
    );

    // COLOR - Button Focus Color
    $element->add_control(
      $this->prefix.'buttons_focus_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $control_selectors => 'color: {{VALUE}};',
        ],
      ]
    );

    // COLOR - Button Focus Background Color
    $element->add_control(
      $this->prefix.'buttons_focus_background_color',
      [
        'label' => __( 'Background Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $control_selectors => 'background-color: {{VALUE}};',
        ],
      ]
    );

    // COLOR - Button Focus Border Color
    $element->add_control(
      $this->prefix.'buttons_focus_border_color',
      [
        'label' => __( 'Border Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $control_selectors => 'border-color: {{VALUE}};',
        ],
      ]
    );

    // BOX SHADOW - Button Focus Box Shadow
    $element->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name' => $this->prefix.'buttons_focus_box_shadow',
        'label' => __( 'Box Shadow', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $control_selectors,
      ]
    );

    // TYPOGRAPHY - Button Focus Typography
    $element->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => $this->prefix.'buttons_focus_typography',
        'label' => __( 'Typography', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $control_selectors,
      ]
    );

    // SELECT - Outline Type
    $element->add_control(
      $this->prefix.'buttons_focus_outline_type',
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
      $this->prefix.'buttons_focus_outline_width',
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
          $this->prefix.'buttons_focus_outline_type!' => ['', 'none'],
        ],
      ]
    );

    // SLIDER - Outline Offset
    $element->add_control(
      $this->prefix.'buttons_focus_outline_offset',
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
          $this->prefix.'buttons_focus_outline_type!' => ['', 'none'],
        ],
      ]
    );

    // COLOR - Outline Color
    $element->add_control(
      $this->prefix.'buttons_focus_outline_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $control_selectors => 'outline-color: {{VALUE}};',
        ],
        'condition' => [
          $this->prefix.'buttons_focus_outline_type!' => ['', 'none'],
        ],
      ]
    );

  }

}
new Buttons_Widget();