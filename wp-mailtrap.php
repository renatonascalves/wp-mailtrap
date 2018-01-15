<?php
/**
 * Loader for the WP Mailtrap plugin
 *
 * @package WP Mailtrap
 */

/**
 * WP Mailtrap
 *
 * @wordpress-plugin
 * Plugin Name: WP Mailtrap
 * Plugin URI:  http://ralv.es
 * Description: WP Mailtrap - A plugin for email testing in WordPress with the Mailtrap API
 * Version:     2.0.0
 * Author:      Renato Alves
 * Author URI:  http://ralv.es
 * Text Domain: wp-mailtrap
 * Domain Path: /languages/
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'wp_Mailtrap' ) ) :

	/**
	* WP Mailtrap Class
	*
	* The main Class
	*
	* @since 1.0.0
	*/
	final class wp_Mailtrap {

		/**
		 * Main instance
		 *
		 * @since 1.0.0
		 *
		 * @return instance
		 */
		public static function instance() {

			// Store the instance locally to avoid private static replication.
			static $instance = null;

			// Only run these methods if they haven't been run previously.
			if ( null === $instance ) {
				$instance = new wp_Mailtrap();
				$instance->plugin_action();
			}

			// Always return the instance.
			return $instance;
		}

		/**
		 * A dummy constructor to prevent wp_Mailtrap from being loaded more than once.
		 *
		 * @since 1.0.0
		 *
		 * @see wp_Mailtrap::instance()
		 */
		private function __construct() {
			/* Do nothing here */
		}

		/**
		 * A dummy magic method to prevent wp_Mailtrap from being cloned.
		 *
		 * @since 1.0.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-mailtrap' ), '1.0.0' );
		}

		/**
		 * A dummy magic method to prevent wp_Mailtrap from being unserialized.
		 *
		 * @since 1.0.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-mailtrap' ), '1.0.0' );
		}

		/**
		 * Add(s) the Action(s) for the wp_Mailtrap
		 *
		 * @since 1.0.0
		 */
		private function plugin_action() {
			add_action( 'phpmailer_init', array( $this, 'mailtrap_info' ) );
			add_action( 'admin_menu', array( $this, 'mailtrap_menu' ) );
		}

		/**
		 * Add the Mailtrap information to phpmailer
		 *
		 * @since 1.0.0
		 */
		public function mailtrap_info( $phpmailer ) {
			$phpmailer->isSMTP();
			$phpmailer->Host     = 'smtp.mailtrap.io';
			$phpmailer->SMTPAuth = true;
			$phpmailer->Port     = esc_attr( get_option( 'wp_mailtrap_port', '2525' ) );
			$phpmailer->Username = esc_attr( get_option( 'wp_mailtrap_username' ) );
			$phpmailer->Password = esc_attr( get_option( 'wp_mailtrap_pwd' ) );
		}

		/**
		 * Creates the WP Mailtrap Page Menu
		 *
		 * @since 1.0.0
		 *
		 * @uses add_submenu_page() For page creation
		 */
		public function mailtrap_menu() {
			add_options_page(
				'WP Mailtrap Settings',
				'WP Mailtrap',
				'manage_options',
				'wp-mailtrap.php',
				array(
					$this,
					'mailtrap_options_page',
				)
			);
		}

		/**
		 * Adds the WP Mailtrap Options
		 *
		 * @since 1.0.0
		 *
		 * @todo Add nounce
		 * @todo Add the proper url for the form POST action
		 */
		public function mailtrap_options_page() {

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html_e('You do not have sufficient permissions to access this page. Contact the administrator.', 'wp-mailtrap' ) );
			}

			if ( ! empty( $_POST['update'] ) ) {

				if ( $_POST['wp_mailtrap_port'] ) {
					update_option( 'wp_mailtrap_port', esc_attr( $_POST['wp_mailtrap_port'] ) );
				}

				if ( $_POST['wp_mailtrap_username'] ) {
					update_option( 'wp_mailtrap_username', esc_attr( $_POST['wp_mailtrap_username'] ) );
				}

				if ( $_POST['wp_mailtrap_pwd'] ) {
					update_option( 'wp_mailtrap_pwd', esc_attr( $_POST['wp_mailtrap_pwd'] ) );
				}
				?>

				<div class="updated settings-error" id="setting-error-settings_updated">
					<p><strong><?php esc_html_e( 'WP Mailtrap Settings Saved.', 'wp-mailtrap' ); ?></strong></p>
				</div>

			<?php
			}

			$wp_mailtrap_port 		= get_option( 'wp_mailtrap_port' );
			$wp_mailtrap_username 	= get_option( 'wp_mailtrap_username' );
			$wp_mailtrap_pwd 		= get_option( 'wp_mailtrap_pwd' );
			?>

			<div class="wrap">
				<h2><?php esc_html_e( 'WP Mailtrap Settings', 'wp-mailtrap' ); ?></h2>

				<form method="POST" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">

					<p><?php _e( 'To use this plugin, you will need a Mailtrap account. They have a <a href="https://mailtrap.io/" title="Mailtrap">free version</a>', 'wp-mailtrap' ); ?></p>

					<table class="form-table">
						<tbody>

							<tr valign="top">
								<th scope="row">
									<label for="wp_mailtrap_port"><?php esc_html_e( 'Port Number:', 'wp-mailtrap' ); ?></label>
								</th>

								<td>
									<input id="wp_mailtrap_port" class="regular-text" type="text" name="wp_mailtrap_port" value="<?php echo esc_attr( $wp_mailtrap_port ); ?>">
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label for="wp_mailtrap_username"><?php esc_html_e( 'Inbox Username:', 'wp-mailtrap' ); ?></label>
								</th>

								<td>
									<input id="wp_mailtrap_username" class="regular-text" type="text" name="wp_mailtrap_username" value="<?php echo esc_attr( $wp_mailtrap_username ); ?>">
								</td>
							</tr>

							<tr valign="top">
								<th scope="row">
									<label for="wp_mailtrap_pwd"><?php esc_html_e( 'Inbox Password:', 'wp-mailtrap' ); ?></label>
								</th>

								<td>
									<input id="wp_mailtrap_pwd" class="regular-text" type="text" name="wp_mailtrap_pwd" value="<?php echo esc_attr( $wp_mailtrap_pwd ); ?>">
								</td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="update" value="update">
					<p class="submit"><input id="submit" class="button-primary" type="submit" value="<?php echo esc_attr( 'Save Changes', 'wp-mailtrap' ); ?>" name="submit"></p>
				</form>
			</div>

		<?php }
	}

	/**
	 * The main function responsible for returning the one true wp_Mailtrap Instance to functions everywhere.
	 *
	 * @since 1.0.0
	 *
	 * @return wp_Mailtrap The one true wp_Mailtrap Instance.
	 */
	function wp_Mailtrap() {
		return wp_Mailtrap::instance();
	}
	add_action( 'plugins_loaded', 'wp_Mailtrap' );

endif;

// That's it! =)
