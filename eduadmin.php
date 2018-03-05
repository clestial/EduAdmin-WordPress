<?php
// phpcs:disable WordPress.NamingConventions
defined( 'ABSPATH' ) || die( 'This plugin must be run within the scope of WordPress.' );
define( 'EDUADMIN_PLUGIN_PATH', dirname( __FILE__ ) );
defined( 'WP_SESSION_COOKIE' ) || define( 'WP_SESSION_COOKIE', 'eduadmin-cookie' );

/*
 * Plugin Name:	EduAdmin Booking
 * Plugin URI:	https://www.eduadmin.se
 * Description:	EduAdmin plugin to allow visitors to book courses at your website
 * Tags:	booking, participants, courses, events, eduadmin, lega online
 * Version:	2.0
 * GitHub Plugin URI: multinetinteractive/eduadmin-wordpress
 * GitHub Plugin URI: https://github.com/multinetinteractive/eduadmin-wordpress
 * Requires at least: 4.7
 * Tested up to: 4.9
 * Author:	Chris Gårdenberg, MultiNet Interactive AB
 * Author URI:	https://www.multinet.com
 * License:	GPL3
 * Text Domain:	eduadmin-booking
 * Domain Path: /languages
 */
/*
	EduAdmin Booking plugin
	Copyright (C) 2015-2017 Chris Gårdenberg, MultiNet Interactive AB

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! class_exists( 'EduAdmin' ) ) :

	final class EduAdmin {
		/**
		 * @var EduAdmin
		 */
		protected static $_instance = null;
		/**
		 * @var EDU_IntegrationLoader
		 */
		public $integrations = null;
		/**
		 * @var string
		 */
		private $token = null;
		/**
		 * @var EduAdmin_BookingHandler
		 */
		public $booking_handler = null;
		/**
		 * @var \EduAdmin_LoginHandler
		 */
		public $login_handler = null;
		/**
		 * @var \EduAdmin_APIController
		 */
		public $rest_controller = null;
		/**
		 * @var WP_Session
		 */
		public $session = null;
		/**
		 * @var array
		 */
		public $timers;
		/**
		 * @var array
		 */
		public $phrases;
		/**
		 * @var string
		 */
		public $version;
		/** @var array */
		public $week_days;
		/** @var array */
		public $short_week_days;
		/** @var array */
		public $months;
		/** @var array */
		public $short_months;

		/**
		 * @return EduAdmin
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function __construct() {
			$this->timers  = array();
			$t             = $this->start_timer( __METHOD__ );
			$this->version = $this->get_version();
			$this->includes();
			$this->init_hooks();
			do_action( 'eduadmin_loaded' );
			$this->stop_timer( $t );
		}

		/**
		 * @param string $name Name of the timer
		 *
		 * @return string Returns the unique name for the created timer
		 */
		public function start_timer( $name ) {
			$timer_id                                = count( $this->timers ) + 1;
			$this->timers[ $name . '_' . $timer_id ] = microtime( true );

			return $name . '_' . $timer_id;
		}

		/**
		 * @param string $name The unique name of the timer (Returned from StartTimer)
		 */
		public function stop_timer( $name ) {
			$this->timers[ $name ] = microtime( true ) - $this->timers[ $name ];
		}

		/**
		 * @param stdClass|array|object|null $object
		 * @param bool                       $as_json
		 */
		public function write_debug( $object, $as_json = false ) {
			if ( $as_json ) {
				echo '<xmp>' . json_encode( $object, JSON_PRETTY_PRINT ) . '</xmp>';

				return;
			}

			ob_start();
			var_dump( $object );

			echo '<xmp>' . ob_get_clean() . '</xmp>';
		}

		public function get_version() {
			if ( function_exists( 'get_plugin_data' ) ) {
				$p_data = get_plugin_data( __FILE__ );

				return $p_data['Version'];
			} else {
				$default_headers = array(
					'Name'        => 'Plugin Name',
					'PluginURI'   => 'Plugin URI',
					'Version'     => 'Version',
					'Description' => 'Description',
					'Author'      => 'Author',
					'AuthorURI'   => 'Author URI',
					'TextDomain'  => 'Text Domain',
					'DomainPath'  => 'Domain Path',
					'Network'     => 'Network',
					// Site Wide Only is deprecated in favor of Network.
					'_sitewide'   => 'Site Wide Only',
				);

				$p_data = get_file_data( __FILE__, $default_headers );

				return $p_data['Version'];
			}
		}

		private function includes() {
			$t = $this->start_timer( __METHOD__ );

			include_once 'includes/eduadmin-api-client/eduadmin-api-client.php';

			if ( ! class_exists( 'Recursive_ArrayAccess' ) ) {
				include_once 'libraries/class-recursive-arrayaccess.php';
			}

			if ( ! class_exists( 'WP_Session' ) ) {
				include_once 'libraries/class-wp-session.php';
				include_once 'libraries/wp-session.php';
			}

			$this->session = WP_Session::get_instance();

			include_once 'includes/edu-api-functions.php';
			include_once 'class/class-eduadmin-bookinginfo.php';
			include_once 'class/class-eduadmin-bookinghandler.php';
			include_once 'class/class-eduadmin-loginhandler.php';

			include_once 'includes/plugin/class-edu-integration.php'; // Integration interface
			include_once 'includes/plugin/class-edu-integrationloader.php'; // Integration loader

			if ( is_wp_error( $this->get_new_api_token() ) ) {
				add_action( 'admin_notices', array( $this, 'setup_warning' ) );
			}

			include_once 'includes/edu-options.php';
			include_once 'includes/edu-ajax-functions.php';
			include_once 'includes/edu-rewrites.php';
			include_once 'includes/edu-shortcodes.php';

			include_once 'includes/edu-question-functions.php';
			include_once 'includes/edu-attribute-functions.php';
			include_once 'includes/edu-text-functions.php';
			include_once 'includes/edu-login-functions.php';

			include_once 'class/class-eduadmin-apicontroller.php';

			$this->rest_controller = new EduAdmin_APIController();
			$this->booking_handler = new EduAdmin_BookingHandler();
			$this->login_handler   = new EduAdmin_LoginHandler();
			$this->stop_timer( $t );
		}

		public function call_home() {
			global $wp_version;
			$usage_data    = array(
				'siteUrl'       => get_site_url(),
				'siteName'      => get_option( 'blogname' ),
				'wpVersion'     => $wp_version,
				'token'         => get_option( 'eduadmin-api-key' ),
				'pluginVersion' => $this->version,
			);
			$call_home_url = 'https://ws10.multinet.se/edu-plugin/wp_phone_home.php';
			wp_remote_post( $call_home_url, array( 'body' => $usage_data ) );
		}

		private function init_hooks() {
			$t = $this->start_timer( __METHOD__ );
			register_activation_hook( __FILE__, 'eduadmin_activate_rewrite' );

			add_action( 'after_switch_theme', array( $this, 'new_theme' ) );
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'plugins_loaded', array( $this, 'load_language' ) );
			add_action( 'eduadmin_call_home', array( $this, 'call_home' ) );
			add_action( 'wp_footer', 'edu_get_timers' );

			register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
			$this->stop_timer( $t );
		}

		public function init() {
			$t                  = $this->start_timer( __METHOD__ );
			$this->integrations = new EDU_IntegrationLoader();
			$this->rest_controller->register_routes();
			$this->week_days = array(
				1 => __( 'monday', 'eduadmin-booking' ),
				2 => __( 'tuesday', 'eduadmin-booking' ),
				3 => __( 'wednesday', 'eduadmin-booking' ),
				4 => __( 'thursday', 'eduadmin-booking' ),
				5 => __( 'friday', 'eduadmin-booking' ),
				6 => __( 'saturday', 'eduadmin-booking' ),
				7 => __( 'sunday', 'eduadmin-booking' ),
			);

			$this->short_week_days = array(
				1 => __( 'mon', 'eduadmin-booking' ),
				2 => __( 'tue', 'eduadmin-booking' ),
				3 => __( 'wed', 'eduadmin-booking' ),
				4 => __( 'thu', 'eduadmin-booking' ),
				5 => __( 'fri', 'eduadmin-booking' ),
				6 => __( 'sat', 'eduadmin-booking' ),
				7 => __( 'sun', 'eduadmin-booking' ),
			);

			$this->months = array(
				1  => __( 'january', 'eduadmin-booking' ),
				2  => __( 'february', 'eduadmin-booking' ),
				3  => __( 'march', 'eduadmin-booking' ),
				4  => __( 'april', 'eduadmin-booking' ),
				5  => __( 'may', 'eduadmin-booking' ),
				6  => __( 'june', 'eduadmin-booking' ),
				7  => __( 'july', 'eduadmin-booking' ),
				8  => __( 'august', 'eduadmin-booking' ),
				9  => __( 'september', 'eduadmin-booking' ),
				10 => __( 'october', 'eduadmin-booking' ),
				11 => __( 'november', 'eduadmin-booking' ),
				12 => __( 'december', 'eduadmin-booking' ),
			);

			$this->short_months = array(
				1  => __( 'jan', 'eduadmin-booking' ),
				2  => __( 'feb', 'eduadmin-booking' ),
				3  => __( 'mar', 'eduadmin-booking' ),
				4  => __( 'apr', 'eduadmin-booking' ),
				5  => _x( 'may', 'short form of the month may', 'eduadmin-booking' ),
				6  => __( 'jun', 'eduadmin-booking' ),
				7  => __( 'jul', 'eduadmin-booking' ),
				8  => __( 'aug', 'eduadmin-booking' ),
				9  => __( 'sep', 'eduadmin-booking' ),
				10 => __( 'oct', 'eduadmin-booking' ),
				11 => __( 'nov', 'eduadmin-booking' ),
				12 => __( 'dec', 'eduadmin-booking' ),
			);

			$this->stop_timer( $t );
		}

		public static function setup_warning() {
			?>
			<div class="notice notice-warning is-dismissable">
				<p>
					<?php
					/* translators: 1: start of link 2: end of link */
					echo esc_html( sprintf( __( 'Please complete the configuration: %1$sEduAdmin - Api Authentication%2$s', 'eduadmin-booking' ), '<a href="' . admin_url() . 'admin.php?page=eduadmin-settings">', '</a>' ) );
					?>
				</p>
			</div>
			<?php
		}

		/**
		 * @return string Returns the users IP adress
		 */
		public function get_ip_adress() {
			$ip_check = array( 'HTTP_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' );
			foreach ( $ip_check as $header ) {
				if ( ! empty( $_SERVER[ $header ] ) ) { // Var input okay.
					return $_SERVER[ $header ]; // Var input okay.
				}
			}

			return 'UNKNOWN';
		}

		public function load_language() {
			$t = $this->start_timer( __METHOD__ );
			load_plugin_textdomain( 'eduadmin-booking', false, EDUADMIN_PLUGIN_PATH . '/languages' );

			if ( ! wp_next_scheduled( 'eduadmin_call_home' ) ) {
				wp_schedule_event( time(), 'hourly', 'eduadmin_call_home' );
			}

			$this->stop_timer( $t );
		}

		public function new_theme() {
			update_option( 'eduadmin-options_have_changed', true );
		}

		public function deactivate() {
			eduadmin_deactivate_rewrite();
			wp_clear_scheduled_hook( 'eduadmin_call_home' );
		}

		private function get_new_api_token() {
			$new_key = get_option( 'eduadmin-newapi-key', null );

			if ( null !== $new_key ) {
				$key = edu_decrypt_api_key( $new_key );
				EDUAPI()->SetCredentials( $key->UserId, $key->Hash );
			} else {
				$old_key = get_option( 'eduadmin-api-key', null );
				if ( null !== $old_key ) {
					$key = edu_decrypt_api_key( $old_key );
					EDUAPI()->SetCredentials( $key->UserId, $key->Hash );
				} else {
					EDUAPI()->SetCredentials( '', '' );
				}
			}

			$current_token = get_transient( 'eduadmin-newapi-token' );
			if ( false === $current_token || ! $current_token->IsValid() ) {
				try {
					$current_token = EDUAPI()->GetToken();
				} catch ( Exception $ex ) {
					return new WP_Error( 'broke', __( 'Could not fetch a new access token for the EduAdmin API, please contact MultiNet support.', 'eduadmin-booking' ) );
				}

				if ( empty( $current_token->Issued ) ) {
					return new WP_Error( 'broke', __( 'The key for the EduAdmin API is not configured to work with the new API, please contact MultiNet support.', 'eduadmin-booking' ) );
				}
				set_transient( 'eduadmin-newapi-token', $current_token, WEEK_IN_SECONDS );
			}

			EDUAPI()->SetToken( $current_token );

			return null;
		}
	}

	/**
	 * @return EduAdmin
	 */
	function EDU() {
		return EduAdmin::instance();
	}

	$GLOBALS['eduadmin'] = EDU();
	if ( function_exists( 'wp_get_timezone_string' ) ) {
		date_default_timezone_set( wp_get_timezone_string() );
		if ( false === @ini_set( 'date.timezone', wp_get_timezone_string() ) ) {
			add_action( 'admin_notices', function() {
				?>
				<div class="notice notice-warning is-dismissable">
					<p><?php echo esc_html__( 'Could not set timezone', 'eduadmin-booking' ); ?></p>
				</div>
				<?php
			} );
		}
	}

	/* Handle plugin-settings */
	add_action(
		'wp_loaded',
		function() {
			$t = EDU()->start_timer( __METHOD__ );
			if ( ! empty( $_POST['plugin-settings-nonce'] ) && wp_verify_nonce( $_POST['plugin-settings-nonce'], 'eduadmin-plugin-settings' ) ) {
				if ( ! empty( $_POST['option_page'] ) && 'eduadmin-plugin-settings' === sanitize_text_field( $_POST['option_page'] ) ) { // Input var okay.
					$integrations = EDU()->integrations->integrations;
					foreach ( $integrations as $integration ) {
						do_action( 'eduadmin-plugin-save_' . $integration->id );
					}
					add_action( 'admin_notices', function() {
						?>
						<div class="notice notice-success is-dismissible">
							<p><?php esc_html_e( 'Plugin settings saved', 'eduadmin-booking' ); ?></p>
						</div>
						<?php
					} );
				}
			}
			EDU()->stop_timer( $t );
		}
	);

	add_action( 'in_plugin_update_message-eduadmin-booking/eduadmin.php',
		function( $current_plugin_metadata, $new_plugin_metadata ) {
			if ( ! empty( $new_plugin_metadata->upgrade_notice ) && strlen( trim( $new_plugin_metadata->upgrade_notice ) ) > 0 ) {
				echo '<p style="background-color: #d54e21; padding: 10px; color: #f9f9f9; margin-top: 10px"><strong>' . esc_html__( 'Important Upgrade Notice', 'eduadmin-booking' ) . ':</strong> ';
				echo esc_html( $new_plugin_metadata->upgrade_notice ), '</p>';
			}
		},
		        10,
		        2
	);
endif;
