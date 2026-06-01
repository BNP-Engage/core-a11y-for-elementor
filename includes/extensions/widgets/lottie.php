<?php
/**
 * Class CoreA11YforElementor\Extensions\Widgets\Lottie_Widget
 *
 * @package CoreA11YforElementor
 */

namespace CoreA11YforElementor\Extensions\Widgets;

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Stack;
use Elementor\Element_Base;
use Elementor\Group_Control_Box_Shadow;
use ElementorPro\Core\Isolation\Wordpress_Adapter;
use ElementorPro\Modules\Lottie\Classes\Caption_Helper;

/**
 * Class Lottie_Widget.
 *
 * @package CoreA11YforElementor\Extensions\Widgets
 */
class Lottie_Widget
{

  /**
   * Prefix for all control names
   *
   * @var string
   */
  private $prefix;

  /**
   * Image constructor.
   */
  public function __construct()
  {
    // Prefix for all new controls
    $this->prefix = 'core_a11y_';

    // Register New controls for Image widget height
    add_action('elementor/element/lottie/lottie/before_section_end', [$this, 'register_new_fallback_control'], 10, 2);

    // Change rendered content for the Accordion widget when using our custom query builder
    add_action('elementor/widget/render_content', [$this, 'register_new_render_content'], 10, 2);

  }

  /**
   * Register Image widget height control.
   *
   * @param Controls_Stack $element Elementor element.
   * @param array         $args Section arguments.
   */
  public function register_new_fallback_control(Controls_Stack $element, $args)
  {

    // SWITCHER - Full Height Image
    $element->add_control(
      'static_image',
      [
        'label' => esc_html__('Static Image', 'core-a11y-for-elementor'),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'default' => [
          'url' => \Elementor\Utils::get_placeholder_image_src(),
        ],
      ]
    );

  }

  private function wp_adapter()
  {
    return new Wordpress_Adapter();
  }

  private function get_caption($settings)
  {
    return (new Caption_Helper($this->wp_adapter(), $settings))
      ->get_caption();
  }

  // Change rendered content for the Accordion widget when using our custom query builder
  public function register_new_render_content($content, $widget)
  {

    if ('lottie' === $widget->get_name()) {
      $settings = $widget->get_active_settings();
      $caption = $this->get_caption($settings);
      $widget_caption = $caption ? '<p class="e-lottie__caption"> ' . esc_html($caption) . '</p>' : '';
      $static_image = isset($settings['static_image']) ? $settings['static_image'] : [];
      $static_image_alt = $static_image['alt'] ?? '';
      $static_image_url = $static_image['url'] ?? '';
      $widget_image = $static_image_url ? '<img src="' . $static_image_url . '" alt=" ' . $static_image_alt . '" class="e-lottie__image" loading="lazy">' : '';
      $content = '<div class="e-lottie__container"><div class="e-lottie__animation"></div>' . $widget_caption . $widget_image . '</div>';

      if (!empty($settings['custom_link']['url']) && 'custom' === $settings['link_to']) {
        $widget->add_link_attributes('url', $settings['custom_link']);
        $content = sprintf('<a class="e-lottie__container__link" %1$s>%2$s</a>', $widget->get_render_attribute_string('url'), $content);
      }
    }
    return $content;
  }

}
new Lottie_Widget();