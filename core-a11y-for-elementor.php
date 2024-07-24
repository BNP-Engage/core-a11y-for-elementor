<?php
/*
 * Plugin Name:       Core A11Y - Accessibility for Elementor
 * Description:       An extension for Elementor and Elementor Pro which adds additional functionality to help fix accessibility issues.
 * Version:           1.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            BNP Engage
 * Author URI:        https://www.bnpengage.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       core-a11y-for-elementor
 * Domain Path:       /languages
 * Requires Plugins:  elementor
 * Elementor tested up to: 3.23
 * Elementor Pro tested up to: 3.23
 */

 if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( ! class_exists( 'Core_A11Y_For_Elementor_Main' ) ) {

	/**
	 * Plugin class.
	 *
	 * The main class that initiates and runs the addon.
	 *
	 * @since 1.0.0
	 */
	final class Core_A11Y_For_Elementor_Main {

		/**
		 * Addon Version
		 *
		 * @since 1.0.0
		 * @var string The addon version.
		 */
		const VERSION = '1.0.1';

		/**
		 * Minimum Elementor Version
		 *
		 * @since 1.0.0
		 * @var string Minimum Elementor version required to run the addon.
		 */
		const MINIMUM_ELEMENTOR_VERSION = '3.16.0';

		/**
		 * Minimum PHP Version
		 *
		 * @since 1.0.0
		 * @var string Minimum PHP version required to run the addon.
		 */
		const MINIMUM_PHP_VERSION = '7.4';

		/**
		 * Instance
		 *
		 * @since 1.0.0
		 * @access private
		 * @static
		 * @var \Core_A11Y_For_Elementor\Plugin The single instance of the class.
		 */
		private static $_instance = null;

		/**
		 * Instance
		 *
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @access public
		 * @static
		 * @return \Core_A11Y_For_Elementor\Plugin An instance of the class.
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;

		}

		/**
		 * Constructor
		 *
		 * Perform some compatibility checks to make sure basic requirements are meet.
		 * If all compatibility checks pass, initialize the functionality.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function __construct() {

			if ( $this->is_compatible() ) {
				add_action( 'elementor/init', [ $this, 'init' ] );
			}

		}

		/**
		 * Compatibility Checks
		 *
		 * Checks whether the site meets the addon requirement.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function is_compatible() {

			// Check if Elementor installed and activated
			if ( ! did_action( 'elementor/loaded' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
				return false;
			}

			// Check for required Elementor version
			if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
				return false;
			}

			// Check for required PHP version
			if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
				return false;
			}

			return true;

		}

		/**
		 * Admin notice
		 *
		 * Warning when the site doesn't have Elementor installed or activated.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function admin_notice_missing_main_plugin() {

			if( isset( $_GET['activate'], $_GET['activate_nonce'] ) && wp_verify_nonce( sanitize_key( $_GET['activate_nonce'] ), 'activate_action' ) ) {
				unset( $_GET['activate'] );
			}

			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor */
				esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'core-a11y-for-elementor' ),
				'<strong>' . esc_html__( 'Core A11Y For Elementor', 'core-a11y-for-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'core-a11y-for-elementor' ) . '</strong>'
			);

			printf( wp_kses('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', [ 'strong' => [], 'p' => [], 'div' => [ 'class' => [] ] ]), wp_kses($message, [ 'strong' => [] ]) );

		}

		/**
		 * Admin notice
		 *
		 * Warning when the site doesn't have a minimum required Elementor version.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function admin_notice_minimum_elementor_version() {

			if( isset( $_GET['activate'], $_GET['activate_nonce'] ) && wp_verify_nonce( sanitize_key( $_GET['activate_nonce'] ), 'activate_action' ) ) {
				unset( $_GET['activate'] );
			}

			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'core-a11y-for-elementor' ),
				'<strong>' . esc_html__( 'Core A11Y For Elementor', 'core-a11y-for-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'core-a11y-for-elementor' ) . '</strong>',
				self::MINIMUM_ELEMENTOR_VERSION
			);

			printf( wp_kses('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', [ 'strong' => [], 'p' => [], 'div' => [ 'class' => [] ] ]), wp_kses($message, [ 'strong' => [] ]) );

		}

		/**
		 * Admin notice
		 *
		 * Warning when the site doesn't have a minimum required PHP version.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function admin_notice_minimum_php_version() {

			if( isset( $_GET['activate'], $_GET['activate_nonce'] ) && wp_verify_nonce( sanitize_key( $_GET['activate_nonce'] ), 'activate_action' ) ) {
				unset( $_GET['activate'] );
			}

			$message = sprintf(
				/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'core-a11y-for-elementor' ),
				'<strong>' . esc_html__( 'Core A11Y For Elementor', 'core-a11y-for-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'PHP', 'core-a11y-for-elementor' ) . '</strong>',
				self::MINIMUM_PHP_VERSION
			);

			printf( wp_kses('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>)'), [ 'strong' => [], 'p' => [], 'div' => [ 'class' => [] ] ], wp_kses($message, [ 'strong' => [] ]) );

		}

		/**
		 * Initialize
		 *
		 * Load the addons functionality only after Elementor is initialized.
		 *
		 * Fired by `elementor/init` action hook.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function init() {

			$this->enqueue_styles();
			$this->register_extensions_kit();
			$this->register_extensions_widgets();
		}

		/**
		 * Enqueue Styles
		 *
		 * Load style sheet for plugin.
		 *
		 */
		public function enqueue_styles( ) {

			// Theme Styles
			wp_enqueue_style( 'core-a11y-for-elementor', plugin_dir_url( __FILE__ ) . '/assets/css/core-a11y-for-elementor-public.css', array(), self::VERSION, 'all' );

		}

		/**
		 * Register Extensions - Kit
		 *
		 * Load files that extend the Theme Style Kit settings.
		 *
		 */
		public function register_extensions_kit( ) {

			// Theme Styles - Buttons
			require_once( __DIR__ . '/includes/extensions/kit/buttons.php' );

			// Theme Styles - Form Fields
			require_once( __DIR__ . '/includes/extensions/kit/form-fields.php' );

			// Theme Styles - Images
			require_once( __DIR__ . '/includes/extensions/kit/images.php' );

			// Theme Styles - Typography
			require_once( __DIR__ . '/includes/extensions/kit/typography.php' );

		}

			/**
		 * Register Extensions - Widgets
		 *
		 * Load files that extend Elementor's existing widgets.
		 *
		 */
		public function register_extensions_widgets( ) {

			// Widget - Button
			require_once( __DIR__ . '/includes/extensions/widgets/button.php' );

			// Widget - Call To Action
			require_once( __DIR__ . '/includes/extensions/widgets/call-to-action.php' );

			// Widget - Form
			require_once( __DIR__ . '/includes/extensions/widgets/form.php' );

			// Widget - Image
			require_once( __DIR__ . '/includes/extensions/widgets/image.php' );

			// Widget - Testimonial Carousel
			require_once( __DIR__ . '/includes/extensions/widgets/testimonial-carousel.php' );

		}

	}

	add_action( 'plugins_loaded', [ 'Core_A11Y_For_Elementor_Main', 'instance'] );
}