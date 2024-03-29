<?php
/**
 * Class CoreA11YforElementor\Extensions\Widgets\Call_To_Action_Widget
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
 * Class Call_To_Action_Widget.
 *
 * @package CoreA11YforElementor\Extensions\Widgets
 */
class Call_To_Action_Widget {

  /**
   * Prefix for all control names
   *
   * @var string
   */
  private $prefix;

  /**
   * Call To Action constructor.
   */
  public function __construct() {
    // Prefix for all new controls
    $this->prefix = 'core_a11y_';

    // Register New controls for Box Style section
    add_action( 'elementor/element/call-to-action/box_style/before_section_end', [ $this, 'register_new_box_style_controls' ], 10, 2 );

    // Register New controls for Button Style section
    add_action( 'elementor/element/call-to-action/button_style/before_section_end', [ $this, 'register_new_button_style_controls' ], 10, 2 );

  }

  /**
   * Register Call To Action widget Box Style controls.
   *
   * @param Controls_Stack $element Elementor element.
   * @param array         $args Section arguments.
   */
  public function register_new_box_style_controls( Controls_Stack $element, $args ) {

    // Set controls selectors to avoid repetition
    $box_control_selectors = '{{WRAPPER}} a.elementor-cta:focus-visible';

    // HEADING - CTAs Heading
    $element->add_control(
      $this->prefix.'heading_cta_focus',
      [
        'type' => Controls_Manager::HEADING,
        'label' => __( 'Focus State', 'core-a11y-for-elementor' ),
        'separator' => 'before',
        'condition' => [
          'link_click' => 'box',
        ],
      ]
    );

    // COLOR - CTA Focus Background Color
    $element->add_control(
      $this->prefix.'cta_focus_background_color',
      [
        'label' => __( 'Background Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $box_control_selectors => 'background-color: {{VALUE}};',
        ],
        'condition' => [
          'link_click' => 'box',
        ],
      ]
    );

    // COLOR - CTA Focus Border Color
    $element->add_control(
      $this->prefix.'cta_focus_border_color',
      [
        'label' => __( 'Border Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $box_control_selectors => 'border-color: {{VALUE}};',
        ],
        'condition' => [
          'link_click' => 'box',
        ],
      ]
    );

    // BOX SHADOW - CTA Focus Box Shadow
    $element->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name' => $this->prefix.'cta_focus_box_shadow',
        'label' => __( 'Box Shadow', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $box_control_selectors,
        'condition' => [
          'link_click' => 'box',
        ],
      ]
    );

    // SELECT - Outline Type
    $element->add_control(
      $this->prefix.'cta_focus_outline_type',
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
          $box_control_selectors => 'outline-style: {{value}};',
        ],
        'condition' => [
          'link_click' => 'box',
        ],
      ]
    );

    // SLIDER - Outline Width
    $element->add_control(
      $this->prefix.'cta_focus_outline_width',
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
          $box_control_selectors => 'outline-width: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .elementor-cta' => 'overflow: visible;',
          '{{WRAPPER}}.elementor-widget-call-to-action .elementor-widget-container' => 'overflow: visible;',
        ],
        'condition' => [
          $this->prefix.'cta_focus_outline_type!' => ['', 'none'],
          'link_click' => 'box',
        ],
      ]
    );

    // SLIDER - Outline Offset
    $element->add_control(
      $this->prefix.'cta_focus_outline_offset',
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
          $box_control_selectors => 'outline-offset: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          $this->prefix.'cta_focus_outline_type!' => ['', 'none'],
          'link_click' => 'box',
        ],
      ]
    );

    // COLOR - Outline Color
    $element->add_control(
      $this->prefix.'cta_focus_outline_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $box_control_selectors => 'outline-color: {{VALUE}};',
        ],
        'condition' => [
          $this->prefix.'cta_focus_outline_type!' => ['', 'none'],
          'link_click' => 'box',
        ],
      ]
    );

  }

  /**
   * Register Call To Action widget Button Style controls.
   *
   * @param Controls_Stack $element Elementor element.
   * @param array         $args Section arguments.
   */
  public function register_new_button_style_controls( Controls_Stack $element, $args ) {

    // Set controls selectors to avoid repetition
    $button_control_selectors = '{{WRAPPER}} .elementor-cta a.elementor-cta__button:focus-visible';

    // HEADING - Button Heading
    $element->add_control(
      $this->prefix.'heading_button_focus',
      [
        'type' => Controls_Manager::HEADING,
        'label' => __( 'Focus State', 'core-a11y-for-elementor' ),
        'separator' => 'before',
        'condition' => [
          'link_click' => 'button',
          'button!' => '',
        ],
      ]
    );

    // COLOR - Button Focus Color
    $element->add_control(
      $this->prefix.'button_focus_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $button_control_selectors => 'color: {{VALUE}};',
        ],
        'condition' => [
          'link_click' => 'button',
          'button!' => '',
        ],
      ]
    );

    // COLOR - Button Focus Background Color
    $element->add_control(
      $this->prefix.'button_focus_background_color',
      [
        'label' => __( 'Background Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $button_control_selectors => 'background-color: {{VALUE}};',
        ],
        'condition' => [
          'link_click' => 'button',
          'button!' => '',
        ],
      ]
    );

    // COLOR - Button Focus Border Color
    $element->add_control(
      $this->prefix.'button_focus_border_color',
      [
        'label' => __( 'Border Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $button_control_selectors => 'border-color: {{VALUE}};',
        ],
        'condition' => [
          'link_click' => 'button',
          'button!' => '',
        ],
      ]
    );

    // BOX SHADOW - Button Focus Box Shadow
    $element->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name' => $this->prefix.'button_focus_box_shadow',
        'label' => __( 'Box Shadow', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $button_control_selectors,
        'condition' => [
          'link_click' => 'button',
          'button!' => '',
        ],
      ]
    );

    // TYPOGRAPHY - Button Focus Typography
    $element->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => $this->prefix.'button_focus_typography',
        'label' => __( 'Typography', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $button_control_selectors,
        'condition' => [
          'link_click' => 'button',
          'button!' => '',
        ],
      ]
    );

    // SELECT - Outline Type
    $element->add_control(
      $this->prefix.'button_focus_outline_type',
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
          $button_control_selectors => 'outline-style: {{value}};',
        ],
        'condition' => [
          'link_click' => 'button',
          'button!' => '',
        ],
      ]
    );

    // SLIDER - Outline Width
    $element->add_control(
      $this->prefix.'button_focus_outline_width',
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
          $button_control_selectors => 'outline-width: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          $this->prefix.'button_focus_outline_type!' => ['', 'none'],
          'link_click' => 'button',
          'button!' => '',
        ],
      ]
    );

    // SLIDER - Outline Offset
    $element->add_control(
      $this->prefix.'button_focus_outline_offset',
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
          $button_control_selectors => 'outline-offset: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          $this->prefix.'button_focus_outline_type!' => ['', 'none'],
          'link_click' => 'button',
          'button!' => '',
        ],
      ]
    );

    // COLOR - Outline Color
    $element->add_control(
      $this->prefix.'button_focus_outline_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $button_control_selectors => 'outline-color: {{VALUE}};',
        ],
        'condition' => [
          $this->prefix.'button_focus_outline_type!' => ['', 'none'],
          'link_click' => 'button',
          'button!' => '',
        ],
      ]
    );

  }

}
new Call_To_Action_Widget();