<?php
/**
 * Class CoreA11YforElementor\Extensions\Widgets\Form_Widget
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
use Elementor\Core\Settings\Manager;
use Elementor\Core\Base\Module;
use Elementor\Element_Base;
use Elementor\Group_Control_Box_Shadow;

/**
 * Class Form_Widget.
 *
 * @package CoreA11YforElementor\Extensions\Widgets
 */
class Form_Widget {

  /**
   * Prefix for all control names
   *
   * @var string
   */
  private $prefix;

  /**
   * Form constructor.
   */
  public function __construct() {
    // Prefix for all new controls
    $this->prefix = 'core_a11y_';

    // Register New controls for Field section
    add_action( 'elementor/element/form/section_field_style/before_section_end', [ $this, 'register_new_field_style_controls' ], 10, 2 );

    // Register New controls for Button Style section
    add_action( 'elementor/element/form/section_button_style/before_section_end', [ $this, 'register_new_button_style_controls' ], 10, 2 );

  }

  /**
   * Register Form widget Field Style controls.
   *
   * @param Controls_Stack $element Elementor element.
   * @param array         $args Section arguments.
   */
  public function register_new_field_style_controls( Controls_Stack $element, $args ) {

    // Set controls selectors to avoid repetition
    $control_selectors = '{{WRAPPER}} .elementor-field-group:not(.elementor-field-type-upload) .elementor-field:not(.elementor-select-wrapper):focus-visible';

    // HEADING - Buttons Heading
    $element->add_control(
      $this->prefix.'heading_fields_focus',
      [
        'type' => Controls_Manager::HEADING,
        'label' => __( 'Focus State', 'core-a11y-for-elementor' ),
        'separator' => 'before',
      ]
    );

    // COLOR - Button Focus Color
    $element->add_control(
      $this->prefix.'fields_focus_color',
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
      $this->prefix.'fields_focus_background_color',
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
      $this->prefix.'fields_focus_border_color',
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
        'name' => $this->prefix.'fields_focus_box_shadow',
        'label' => __( 'Box Shadow', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $control_selectors,
      ]
    );

    // TYPOGRAPHY - Button Focus Typography
    $element->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => $this->prefix.'fields_focus_typography',
        'label' => __( 'Typography', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $control_selectors,
      ]
    );

    // SELECT - Outline Type
    $element->add_control(
      $this->prefix.'fields_focus_outline_type',
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
      $this->prefix.'fields_focus_outline_width',
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
          $this->prefix.'fields_focus_outline_type!' => ['', 'none'],
        ],
      ]
    );

    // SLIDER - Outline Offset
    $element->add_control(
      $this->prefix.'fields_focus_outline_offset',
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
          $this->prefix.'fields_focus_outline_type!' => ['', 'none'],
        ],
      ]
    );

    // COLOR - Outline Color
    $element->add_control(
      $this->prefix.'fields_focus_outline_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $control_selectors => 'outline-color: {{VALUE}};',
        ],
        'condition' => [
          $this->prefix.'fields_focus_outline_type!' => ['', 'none'],
        ],
      ]
    );

  }

  /**
   * Register Form widget Button Style controls.
   *
   * @param Controls_Stack $element Elementor element.
   * @param array         $args Section arguments.
   */
  public function register_new_button_style_controls( Controls_Stack $element, $args ) {

    // Set controls selectors to avoid repetition
    $submit_control_selectors = '{{WRAPPER}} .elementor-button[type="submit"]:focus-visible';
    $next_control_selectors = '{{WRAPPER}} .e-form__buttons__wrapper__button-next:focus-visible';
    $prev_control_selectors = '{{WRAPPER}} .e-form__buttons__wrapper__button-previous:focus-visible';

    // HEADING - Next & Submit Buttons Heading
    $element->add_control(
      $this->prefix.'heading_buttons_focus_next_submit',
      [
        'type' => Controls_Manager::HEADING,
        'label' => __( 'Focus State - Next & Submit Button', 'core-a11y-for-elementor' ),
        'separator' => 'before',
      ]
    );

    // COLOR - Button Focus Color
    $element->add_control(
      $this->prefix.'buttons_focus_next_submit_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $submit_control_selectors => 'color: {{VALUE}};',
          $next_control_selectors => 'color: {{VALUE}};',
        ],
      ]
    );

    // COLOR - Button Focus Background Color
    $element->add_control(
      $this->prefix.'buttons_focus_next_submit_background_color',
      [
        'label' => __( 'Background Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $submit_control_selectors => 'background-color: {{VALUE}};',
          $next_control_selectors => 'background-color: {{VALUE}};',
        ],
      ]
    );

    // COLOR - Button Focus Border Color
    $element->add_control(
      $this->prefix.'buttons_focus_next_submit_border_color',
      [
        'label' => __( 'Border Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $submit_control_selectors => 'border-color: {{VALUE}};',
          $next_control_selectors => 'border-color: {{VALUE}};',
        ],
      ]
    );

    // BOX SHADOW - Button Focus Box Shadow
    $element->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name' => $this->prefix.'buttons_focus_next_submit_box_shadow',
        'label' => __( 'Box Shadow', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $submit_control_selectors.', '.$next_control_selectors,
      ]
    );

    // TYPOGRAPHY - Button Focus Typography
    $element->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => $this->prefix.'buttons_focus_next_submit_typography',
        'label' => __( 'Typography', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $submit_control_selectors.', '.$next_control_selectors,
      ]
    );

    // SELECT - Outline Type
    $element->add_control(
      $this->prefix.'buttons_focus_next_submit_outline_type',
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
          $submit_control_selectors => 'outline-style: {{value}};',
          $next_control_selectors => 'outline-style: {{value}};',
        ],
      ]
    );

    // SLIDER - Outline Width
    $element->add_control(
      $this->prefix.'buttons_focus_next_submit_outline_width',
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
          $submit_control_selectors => 'outline-width: {{SIZE}}{{UNIT}};',
          $next_control_selectors => 'outline-width: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          $this->prefix.'buttons_focus_next_submit_outline_type!' => ['', 'none'],
        ],
      ]
    );

    // SLIDER - Outline Offset
    $element->add_control(
      $this->prefix.'buttons_focus_next_submit_outline_offset',
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
          $submit_control_selectors => 'outline-offset: {{SIZE}}{{UNIT}};',
          $next_control_selectors => 'outline-offset: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          $this->prefix.'buttons_focus_next_submit_outline_type!' => ['', 'none'],
        ],
      ]
    );

    // COLOR - Outline Color
    $element->add_control(
      $this->prefix.'buttons_focus_next_submit_outline_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $submit_control_selectors => 'outline-color: {{VALUE}};',
          $next_control_selectors => 'outline-color: {{VALUE}};',
        ],
        'condition' => [
          $this->prefix.'buttons_focus_next_submit_outline_type!' => ['', 'none'],
        ],
      ]
    );

    // HEADING - Previous Buttons Heading
    $element->add_control(
      $this->prefix.'heading_buttons_focus_previous',
      [
        'type' => Controls_Manager::HEADING,
        'label' => __( 'Focus State - Previous Button', 'core-a11y-for-elementor' ),
        'separator' => 'before',
      ]
    );

    // COLOR - Button Focus Color
    $element->add_control(
      $this->prefix.'buttons_focus_previous_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $prev_control_selectors => 'color: {{VALUE}};',
        ],
      ]
    );

    // COLOR - Button Focus Background Color
    $element->add_control(
      $this->prefix.'buttons_focus_previous_background_color',
      [
        'label' => __( 'Background Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $prev_control_selectors => 'background-color: {{VALUE}};',
        ],
      ]
    );

    // COLOR - Button Focus Border Color
    $element->add_control(
      $this->prefix.'buttons_focus_previous_border_color',
      [
        'label' => __( 'Border Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $prev_control_selectors => 'border-color: {{VALUE}};',
        ],
      ]
    );

    // BOX SHADOW - Button Focus Box Shadow
    $element->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name' => $this->prefix.'buttons_focus_previous_box_shadow',
        'label' => __( 'Box Shadow', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $prev_control_selectors,
      ]
    );

    // TYPOGRAPHY - Button Focus Typography
    $element->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => $this->prefix.'buttons_focus_previous_typography',
        'label' => __( 'Typography', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $prev_control_selectors,
      ]
    );

    // SELECT - Outline Type
    $element->add_control(
      $this->prefix.'buttons_focus_previous_outline_type',
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
          $prev_control_selectors => 'outline-style: {{value}};',
        ],
      ]
    );

    // SLIDER - Outline Width
    $element->add_control(
      $this->prefix.'buttons_focus_previous_outline_width',
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
          $prev_control_selectors => 'outline-width: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          $this->prefix.'buttons_focus_previous_outline_type!' => ['', 'none'],
        ],
      ]
    );

    // SLIDER - Outline Offset
    $element->add_control(
      $this->prefix.'buttons_focus_previous_outline_offset',
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
          $prev_control_selectors => 'outline-offset: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          $this->prefix.'buttons_focus_previous_outline_type!' => ['', 'none'],
        ],
      ]
    );

    // COLOR - Outline Color
    $element->add_control(
      $this->prefix.'buttons_focus_previous_outline_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $prev_control_selectors => 'outline-color: {{VALUE}};',
        ],
        'condition' => [
          $this->prefix.'buttons_focus_previous_outline_type!' => ['', 'none'],
        ],
      ]
    );

  }

}
new Form_Widget();