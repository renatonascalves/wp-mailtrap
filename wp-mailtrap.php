<?php
/**
 * Loader for the WP Mailtrap plugin
 * 
 * @package User Private Files
 */

/**
 * WP Mailtrap
 *
 * @wordpress-plugin
 * Plugin Name: WP Mailtrap
 * Plugin URI:  http://ralv.es
 * Description: WP Mailtrap - A plugin for email testing in WordPress with the Mailtrap API
 * Version:     0.0.1
 * Author:      Renato Alves
 * Author URI:  http://ralv.es
 * Text Domain: wp-mailtrap
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists('wp_Mailtrap') ) :
	/**
	* WP Mailtrap Class
	*
	* The main Class
	*
	* @since 1.0.0
	*/
	class wp_Mailtrap {

		/**
		 * Main instance
		 *
		 * @since 1.0.0
		 * 
		 * @return instance
		 */
		private static function instance() {

			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been run previously
			if ( null === $instance ) {
				$instance = new wp_Mailtrap;
				$instance->plugin_action();
			}

			// Always return the instance
			return $instance;
		}

		/**
		 * A dummy constructor to prevent wp_Mailtrap from being loaded more than once.
		 *
		 * @since 1.0.0
		 * 
		 * @see wp_Mailtrap::instance()
		 */
		private function __construct() { /* Do nothing here */ }

		/**
		 * Add(s) the Action(s) for the wp_Mailtrap
		 * 
		 * @since 1.0.0
		 *
		 * @return array
		 */
		private function plugin_action() {
			add_action('phpmailer_init', array($this, 'mailtrap_info' ) );
		}

		/**
		 * Add the phpmailer information
		 * 
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function mailtrap_info( $phpmailer ) {
		    $phpmailer->isSMTP();
		    $phpmailer->Host = 'mailtrap.io';
		    $phpmailer->SMTPAuth = true;
		    $phpmailer->Port = 2525;
		    $phpmailer->Username = 'username';
		    $phpmailer->Password = 'password';
		}
	}
endif;

/**
 * The main function responsible for returning the one true wp_Mailtrap Instance to functions everywhere.
 *
 * @return wp_Mailtrap The one true wp_Mailtrap Instance.
 */
function wp_Mailtrap() {
	return wp_Mailtrap::instance();
}
add_action( 'plugins_loaded', 'wp_Mailtrap');

// That's it! =)
