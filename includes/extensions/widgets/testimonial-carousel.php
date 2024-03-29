<?php
/**
 * Class CoreA11YforElementor\Extensions\Widgets\Testimonial_Carousel_Widget
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
 * Class Testimonial_Carousel_Widget.
 *
 * @package CoreA11YforElementor\Extensions\Widgets
 */
class Testimonial_Carousel_Widget {

  /**
   * Prefix for all control names
   *
   * @var string
   */
  private $prefix;

  /**
   * Testimonial Carousel constructor.
   */
  public function __construct() {
    // Prefix for all new controls
    $this->prefix = 'core_a11y_';

    // Register New controls for Navigation section
    add_action( 'elementor/element/testimonial-carousel/section_navigation/before_section_end', [ $this, 'register_new_navigation_style_controls' ], 10, 2 );

  }

  /**
   * Register Testimonial Carousel widget Navigation Style controls.
   *
   * @param Controls_Stack $element Elementor element.
   * @param array         $args Section arguments.
   */
  public function register_new_navigation_style_controls( Controls_Stack $element, $args ) {

    //
    // -- Controls for arrows focus state, if visible -- //
    //

    // Set controls selectors to avoid repetition
    $arrows_control_selectors = '{{WRAPPER}}.elementor-widget-testimonial-carousel .elementor-swiper-button:focus-visible';

    // HEADING - Button Heading
    $element->add_control(
      $this->prefix.'heading_arrows_focus',
      [
        'type' => Controls_Manager::HEADING,
        'label' => __( 'Arrow Focus State', 'core-a11y-for-elementor' ),
        'separator' => 'before',
        'condition' => [
          'show_arrows' => 'yes',
        ],
      ]
    );

    // COLOR - Button Focus Color
    $element->add_control(
      $this->prefix.'arrows_focus_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $arrows_control_selectors => 'color: {{VALUE}};',
          '{{WRAPPER}}.elementor-widget-testimonial-carousel .elementor-swiper-button:focus-visible svg' => 'fill: {{VALUE}};'
        ],
        'condition' => [
          'show_arrows' => 'yes',
        ],
      ]
    );

    // BOX SHADOW - Button Focus Box Shadow
    $element->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name' => $this->prefix.'arrows_focus_box_shadow',
        'label' => __( 'Box Shadow', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $arrows_control_selectors,
        'condition' => [
          'show_arrows' => 'yes',
        ],
      ]
    );

    // SELECT - Outline Type
    $element->add_control(
      $this->prefix.'arrows_focus_outline_type',
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
          $arrows_control_selectors => 'outline-style: {{value}};',
        ],
        'condition' => [
          'show_arrows' => 'yes',
        ],
      ]
    );

    // SLIDER - Outline Width
    $element->add_control(
      $this->prefix.'arrows_focus_outline_width',
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
          $arrows_control_selectors => 'outline-width: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          $this->prefix.'arrows_focus_outline_type!' => ['', 'none'],
          'show_arrows' => 'yes',
        ],
      ]
    );

    // SLIDER - Outline Offset
    $element->add_control(
      $this->prefix.'arrows_focus_outline_offset',
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
          $arrows_control_selectors => 'outline-offset: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          $this->prefix.'arrows_focus_outline_type!' => ['', 'none'],
          'show_arrows' => 'yes',
        ],
      ]
    );

    // COLOR - Outline Color
    $element->add_control(
      $this->prefix.'arrows_focus_outline_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $arrows_control_selectors => 'outline-color: {{VALUE}};',
        ],
        'condition' => [
          $this->prefix.'arrows_focus_outline_type!' => ['', 'none'],
          'show_arrows' => 'yes',
        ],
      ]
    );

    //
    // -- Controls for pagination focus state, if visible -- //
    //

    // Set controls selectors to avoid repetition
    $pagination_control_selectors = '{{WRAPPER}}.elementor-widget-testimonial-carousel .swiper-pagination-bullet:focus-visible';

    // HEADING - Button Heading
    $element->add_control(
      $this->prefix.'heading_pagination_focus',
      [
        'type' => Controls_Manager::HEADING,
        'label' => __( 'Pagination Focus State', 'core-a11y-for-elementor' ),
        'separator' => 'before',
        'condition' => [
          'pagination' => 'bullets',
        ],
      ]
    );

    // COLOR - Button Focus Color
    $element->add_control(
      $this->prefix.'pagination_focus_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $pagination_control_selectors => 'color: {{VALUE}};',
        ],
        'condition' => [
          'pagination' => 'bullets',
        ],
      ]
    );

    // BOX SHADOW - Button Focus Box Shadow
    $element->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name' => $this->prefix.'pagination_focus_box_shadow',
        'label' => __( 'Box Shadow', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $pagination_control_selectors,
        'condition' => [
          'pagination' => 'bullets',
        ],
      ]
    );

    // SELECT - Outline Type
    $element->add_control(
      $this->prefix.'pagination_focus_outline_type',
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
          $pagination_control_selectors => 'outline-style: {{value}};',
        ],
        'condition' => [
          'pagination' => 'bullets',
        ],
      ]
    );

    // SLIDER - Outline Width
    $element->add_control(
      $this->prefix.'pagination_focus_outline_width',
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
          $pagination_control_selectors => 'outline-width: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          $this->prefix.'pagination_focus_outline_type!' => ['', 'none'],
          'pagination' => 'bullets',
        ],
      ]
    );

    // SLIDER - Outline Offset
    $element->add_control(
      $this->prefix.'pagination_focus_outline_offset',
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
          $pagination_control_selectors => 'outline-offset: {{SIZE}}{{UNIT}};',
        ],
        'condition' => [
          $this->prefix.'pagination_focus_outline_type!' => ['', 'none'],
          'pagination' => 'bullets',
        ],
      ]
    );

    // COLOR - Outline Color
    $element->add_control(
      $this->prefix.'pagination_focus_outline_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $pagination_control_selectors => 'outline-color: {{VALUE}};',
        ],
        'condition' => [
          $this->prefix.'pagination_focus_outline_type!' => ['', 'none'],
          'pagination' => 'bullets',
        ],
      ]
    );

  }
}
new Testimonial_Carousel_Widget();