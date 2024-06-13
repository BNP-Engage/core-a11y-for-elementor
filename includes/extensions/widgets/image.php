<?php
/**
 * Class CoreA11YforElementor\Extensions\Widgets\Image_Widget
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
 * Class Image_Widget.
 *
 * @package CoreA11YforElementor\Extensions\Widgets
 */
class Image_Widget {

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

    // Register New controls for Image widget height
    add_action( 'elementor/element/image/section_image/before_section_end', [ $this, 'register_new_image_controls' ], 10, 2 );

    // Register New controls for Image widget Accessibility
    add_action( 'elementor/element/image/section_style_image/before_section_end', [ $this, 'register_new_style_controls' ], 10, 2 );

  }

  /**
   * Register Image widget height control.
   *
   * @param Controls_Stack $element Elementor element.
   * @param array         $args Section arguments.
   */
  public function register_new_image_controls( Controls_Stack $element, $args ) {

    // SWITCHER - Full Height Image
    $element->add_control(
      $this->prefix.'full_height_image',
      [
        'label' => __( 'Full Height Image', 'core-a11y-for-elementor'),
        'description' => __( 'Select Yes to have image stretch to the full height of its column. This is not meant to be used if there is only one column in the section.', 'core-a11y-for-elementor'),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_on' => __( 'Yes', 'core-a11y-for-elementor'),
        'label_off' => __( 'No', 'core-a11y-for-elementor'),
        'return_value' => 'yes',
        'default' => 'no',
        'separator' => 'before',
        'prefix_class' => 'core-a11y-full-img-',
      ]
    );

    // SLIDER - Min Height
    $element->add_responsive_control(
      $this->prefix.'full_height_image_min_height',
      [
        'label' => __( 'Min Height', 'core-a11y-for-elementor' ),
        'description' => __( 'Use this if you need the image to set the height of the section, rather than its neighboring column.', 'core-a11y-for-elementor'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => [ 'px' ],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 600,
            'step' => 1,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}}.core-a11y-full-img-yes' => 'min-height: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          $this->prefix.'full_height_image' => ['yes'],
        ],
      ]
    );

  }

  /**
   * Register Image widget Accessibility controls.
   *
   * @param Controls_Stack $element Elementor element.
   * @param array         $args Section arguments.
   */
  public function register_new_style_controls( Controls_Stack $element, $args ) {

    // Set controls selectors to avoid repetition
    $control_selectors = '{{WRAPPER}}.elementor-widget-image a:focus-visible';

    // HEADING - Image Heading
    $element->add_control(
      $this->prefix.'heading_image_focus',
      [
        'type' => Controls_Manager::HEADING,
        'label' => __( 'Focus State', 'core-a11y-for-elementor' ),
        'separator' => 'before',
        'condition' => [
          'link_to!' => 'none',
        ],
      ]
    );

    // COLOR - Image Focus Border Color
    $element->add_control(
      $this->prefix.'image_focus_border_color',
      [
        'label' => __( 'Border Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $control_selectors => 'border-color: {{VALUE}};',
        ],
        'condition' => [
          'link_to!' => 'none',
        ],
      ]
    );

    // BOX SHADOW - Image Focus Box Shadow
    $element->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name' => $this->prefix.'image_focus_box_shadow',
        'label' => __( 'Box Shadow', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $control_selectors,
        'condition' => [
          'link_to!' => 'none',
        ],
      ]
    );

    // SELECT - Outline Type
    $element->add_control(
      $this->prefix.'image_focus_outline_type',
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
        'condition' => [
          'link_to!' => 'none',
        ],
      ]
    );

    // SLIDER - Outline Width
    $element->add_control(
      $this->prefix.'image_focus_outline_width',
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
          $this->prefix.'image_focus_outline_type!' => ['', 'none'],
          'link_to!' => 'none',
        ],
      ]
    );

    // SLIDER - Outline Offset
    $element->add_control(
      $this->prefix.'image_focus_outline_offset',
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
          $this->prefix.'image_focus_outline_type!' => ['', 'none'],
          'link_to!' => 'none',
        ],
      ]
    );

    // COLOR - Outline Color
    $element->add_control(
      $this->prefix.'image_focus_outline_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $control_selectors => 'outline-color: {{VALUE}};',
        ],
        'condition' => [
          $this->prefix.'image_focus_outline_type!' => ['', 'none'],
          'link_to!' => 'none',
        ],
      ]
    );

  }

}
new Image_Widget();