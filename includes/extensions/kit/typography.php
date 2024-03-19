<?php
/**
 * Class CoreA11YforElementor\Extensions\Kit\Typography_Section
 *
 * @package CoreA11YforElementor
 */

namespace CoreA11YforElementor\Extensions\Kit;

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
 * Class Typography_Section.
 *
 * @package CoreA11YforElementor\Extensions\Kit
 */
class Typography_Section extends Module {

  /**
   * Tab ID to add settings to
   *
   * @var string
   */
  private $settings_tab;

  /**
   * Prefix for all control names
   *
   * @var string
   */
  private $prefix;

  /**
   * Typography constructor.
   */
  public function __construct() {
    // Set the tab
    $this->settings_tab = 'theme-style-typography';
    // Prefix for all new controls
    $this->prefix = 'core_a11y_';

    // Register New Section for Typography Accessibility
    add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_new_section_typography_accessibility' ), 20, 2 );

  }

  /**
   * Get module name.
   *
   * @return string
   */
  public function get_name() {
    return 'core-a11y-typography-accessibility';
  }

  /**
   * Register Typography Accessibility section with controls.
   *
   * @param Controls_Stack $element Elementor element.
   * @param string         $section_id Section ID.
   */
  public function register_new_section_typography_accessibility( Controls_Stack $element, $section_id ) {

    /**
     * Section Start: Typography Accessibility
     * Tab: Typography Theme Styles
     *
     */
    $element->start_controls_section(
      'section_core_a11y_typography_accessibility',
      [
        'label' => __( 'Accessibility', 'core-a11y-for-elementor' ),
        'tab' => $this->settings_tab
      ]
    );

    // Set controls selectors to avoid repetition
    $control_selectors = '{{WRAPPER}} a:focus-visible';

    // HEADING - New Control Heading
    $element->add_control(
      $this->prefix.'heading_link_focus',
      [
        'type' => Controls_Manager::HEADING,
        'label' => __( 'Link Focus State', 'core-a11y-for-elementor' ),
        'separator' => 'before',
      ]
    );

    // COLOR - Link Focus Color
    $element->add_control(
      $this->prefix.'link_focus_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $control_selectors => 'color: {{VALUE}};',
        ],
      ]
    );

    // TYPOGRAPHY - Link Focus Typography
    $element->add_group_control(
      Group_Control_Typography::get_type(),
      [
        'name' => $this->prefix.'link_focus_typography',
        'label' => __( 'Typography', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $control_selectors,
      ]
    );

    // BOX SHADOW - Link Focus Box Shadow
    $element->add_group_control(
      Group_Control_Box_Shadow::get_type(),
      [
        'name' => $this->prefix.'link_focus_box_shadow',
        'label' => __( 'Box Shadow', 'core-a11y-for-elementor' ),
        'dynamic' => [],
        'selector' => $control_selectors,
      ]
    );

    // SELECT - Outline Type
    $element->add_control(
      $this->prefix.'link_focus_outline_type',
      [
        'label' => __( 'Outline Type', 'core-a11y-for-elementor' ),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'none',
        'options' => [
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

    // DIMENSIONS - Outline Width
    $element->add_control(
      $this->prefix.'link_focus_outline_width',
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
          $this->prefix.'link_focus_outline_type!' => 'none',
        ],
      ]
    );

    // DIMENSIONS - Outline Offset
    $element->add_control(
      $this->prefix.'link_focus_outline_offset',
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
          $this->prefix.'link_focus_outline_type!' => 'none',
        ],
      ]
    );

    // COLOR - Outline Color
    $element->add_control(
      $this->prefix.'link_focus_outline_color',
      [
        'label' => __( 'Color', 'core-a11y-for-elementor' ),
        'type' => Controls_Manager::COLOR,
        'dynamic' => [],
        'selectors' => [
          $control_selectors => 'outline-color: {{VALUE}};',
        ],
        'condition' => [
          $this->prefix.'link_focus_outline_type!' => 'none',
        ],
      ]
    );

    $element->end_controls_section();

  }

}
new Typography_Section();