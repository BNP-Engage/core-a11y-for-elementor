<?php
/**
 * Plugin Name:     Core A11Y For Elementor
 * Requires Plugins: elementor
 * Plugin URI:      https://www.bnpengage.com
 * Description:     An extension for Elementor and Elementor Pro which adds additional functionality for accessibility.
 * Author:          bnpengage
 * Author URI:      https://www.bnpengage.com
 * Text Domain:     core-a11y-for-elementor
 * Domain Path:     /languages
 * Version:         0.1.0
 * Elementor tested up to: 3.20.0
 * Elementor Pro tested up to: 3.20.0
 *
 * @package         Core A11Y For Elementor
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function core_a11y_for_elementor() {

	// Load plugin file
	require_once( __DIR__ . '/includes/plugin.php' );

	// Run the plugin
	\Core_A11Y_For_Elementor\Plugin::instance();

}
add_action( 'plugins_loaded', 'core_a11y_for_elementor' );