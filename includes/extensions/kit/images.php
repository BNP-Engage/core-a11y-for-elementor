<?php
/**
 * Class CoreA11YforElementor\Extensions\Kit\Images_Section
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
 * Class Images_Section.
 *
 * @package CoreA11YforElementor\Extensions\Kit
 */
class Images_Section extends Module {

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
   * Images constructor.
   */
  public function __construct() {
    // Set the tab
    $this->settings_tab = 'theme-style-images';
    // Prefix for all new controls
    $this->prefix = 'core_a11y_';

    // Register New Section for Images Accessibility
    add_action( 'elementor/element/kit/section_images/after_section_end', array( $this, 'register_new_section_images_accessibility' ), 20, 2 );

  }

  /**
   * Get module name.
   *
   * @return string
   */
  public function get_name() {
    return 'core-a11y-for-elementor-images-accessibility';
  }

  /**
   * Register Images Accessibility section with controls.
   *
   * @param Controls_Stack $element Elementor element.
   * @param string         $section_id Section ID.
   */
  public function register_new_section_images_accessibility( Controls_Stack $element, $section_id ) {

    /**
     * Section Start: Images Accessibility
     * Tab: Button Theme Styles
     *
     */
    $element->start_controls_section(
      'section_core_a11y_images_accessibility',
      [
        'label' => __( 'Accessibility', 'core-a11y-for-elementor' ),
        'tab' => $this->settings_tab
      ]
    );

    // Set controls selectors to avoid repetition
    $control_sectors = '.ce-full-img-yes';

    // HEADING - Images Heading
    $element->add_control(
      $this->prefix.'heading_images',
      [
        'type' => Controls_Manager::HEADING,
        'label' => __( 'Full Height Image Options', 'core-a11y-for-elementor' ),
        'separator' => 'before',
      ]
    );

    // SLIDER - Min Height
    $element->add_responsive_control(
      $this->prefix.'full_height_image_min_height',
      [
        'label' => __( 'Min Height', 'core-a11y-for-elementor' ),
        'description' => __( 'Use this to set a fallback min-height on all images using the Full Height option.', 'core-a11y-for-elementor'),
        'type' => Controls_Manager::SLIDER,
        'size_units' => [ 'px' ],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 600,
            'step' => 1,
          ],
        ],
        'selectors' => [
          $control_sectors => 'min-height: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $element->end_controls_section();

  }

}
new Images_Section();