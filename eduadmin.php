<?php
	defined( 'ABSPATH' ) or die( 'This plugin must be run within the scope of WordPress.' );
	define( 'EDUADMIN_PLUGIN_PATH', dirname( __FILE__ ) );
	defined( 'WP_SESSION_COOKIE' ) or define( 'WP_SESSION_COOKIE', 'eduadmin-cookie' );

	/*
	 * Plugin Name:	EduAdmin Booking
	 * Plugin URI:	http://www.eduadmin.se
	 * Description:	EduAdmin plugin to allow visitors to book courses at your website
	 * Tags:	booking, participants, courses, events, eduadmin, lega online
	 * Version:	1.0.15
	 * GitHub Plugin URI: multinetinteractive/eduadmin-wordpress
	 * GitHub Plugin URI: https://github.com/multinetinteractive/eduadmin-wordpress
	 * Requires at least: 4.7
	 * Tested up to: 4.9.1
	 * Author:	Chris Gårdenberg, MultiNet Interactive AB
	 * Author URI:	http://www.multinet.se
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
			 * @var EduAdminClient
			 */
			public $api = null;
			/**
			 * @var string
			 */
			private $token = null;
			/**
			 * @var EduAdminBookingHandler
			 */
			public $bookingHandler = null;
			/**
			 * @var \EduAdminLoginHandler
			 */
			public $loginHandler = null;
			/**
			 * @var \EduAdminAPIController
			 */
			public $restController = null;
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
			 * @return EduAdmin
			 */
			public static function instance() {
				if ( is_null( self::$_instance ) ) {
					self::$_instance = new self();
				}

				return self::$_instance;
			}

			public function __construct() {
				$this->timers               = array();
				$this->timers[ __METHOD__ ] = microtime( true );
				$this->includes();
				$this->init_hooks();

				do_action( 'eduadmin_loaded' );
				$this->timers[ __METHOD__ ] = microtime( true ) - $this->timers[ __METHOD__ ];
			}

			/**
			 * @return mixed|null|string Returnerar en API-token från Lega Online
			 */
			public function get_token() {
				$this->timers[ __METHOD__ ] = microtime( true );
				$apiKey                     = get_option( 'eduadmin-api-key' );
				if ( ! $apiKey || empty( $apiKey ) ) {
					add_action( 'admin_notices', array( $this, 'SetupWarning' ) );
					$this->timers[ __METHOD__ ] = microtime( true ) - $this->timers[ __METHOD__ ];

					return '';
				} else {
					$key = DecryptApiKey( $apiKey );
					if ( ! $key ) {
						add_action( 'admin_notices', array( $this, 'SetupWarning' ) );
						$this->timers[ __METHOD__ ] = microtime( true ) - $this->timers[ __METHOD__ ];

						return '';
					}

					$edutoken = get_transient( 'eduadmin-token' );
					if ( ! $edutoken ) {
						$edutoken = $this->api->GetAuthToken( $key->UserId, $key->Hash );
						set_transient( 'eduadmin-token', $edutoken, HOUR_IN_SECONDS );
					} else {
						if ( false === get_transient( 'eduadmin-validatedToken_' . $edutoken ) ) {
							$valid = $this->api->ValidateAuthToken( $edutoken );
							if ( ! $valid ) {
								$edutoken = $this->api->GetAuthToken( $key->UserId, $key->Hash );
								set_transient( 'eduadmin-token', $edutoken, HOUR_IN_SECONDS );
							}
							set_transient( 'eduadmin-validatedToken_' . $edutoken, true, 10 * MINUTE_IN_SECONDS );
						}
					}
					$this->token                = $edutoken;
					$this->timers[ __METHOD__ ] = microtime( true ) - $this->timers[ __METHOD__ ];

					return $this->token;
				}
			}

			private function includes() {
				$this->timers[ __METHOD__ ] = microtime( true );
				include_once( 'libraries/class-recursive-arrayaccess.php' );
				include_once( 'libraries/class-wp-session.php' );
				include_once( 'libraries/wp-session.php' );

				$this->session = WP_Session::get_instance();

				include_once( 'includes/loApiClasses.php' );
				include_once( 'includes/_apiFunctions.php' );
				include_once( 'class/class-eduadmin-bookinginfo.php' );
				include_once( 'class/class-eduadmin-bookinghandler.php' );
				include_once( 'class/class-eduadmin-loginhandler.php' );

				include_once( 'includes/plugin/edu-integration.php' ); // Integration interface
				include_once( 'includes/plugin/edu-integrationloader.php' ); // Integration loader
				include_once( 'includes/loApiClient.php' );

				$this->api = new EduAdminClient();
				global $eduapi;
				global $edutoken;
				$eduapi   = $this->api;
				$edutoken = $this->get_token();
				include_once( 'includes/_options.php' );
				include_once( 'includes/_ajaxFunctions.php' );
				include_once( 'includes/_rewrites.php' );
				include_once( 'includes/_shortcodes.php' );

				include_once( 'includes/_translationFunctions.php' );
				include_once( 'includes/_questionFunctions.php' );
				include_once( 'includes/_attributeFunctions.php' );
				include_once( 'includes/_textFunctions.php' );
				include_once( 'includes/_loginFunctions.php' );

				include_once( 'class/controller-eduadmin-api.php' );

				$this->restController       = new EduAdminAPIController( $this );
				$this->bookingHandler       = new EduAdminBookingHandler( $this );
				$this->loginHandler         = new EduAdminLoginHandler( $this );
				$this->timers[ __METHOD__ ] = microtime( true ) - $this->timers[ __METHOD__ ];
			}

			private function init_hooks() {
				$this->timers[ __METHOD__ ] = microtime( true );
				register_activation_hook( __FILE__, 'eduadmin_activate_rewrite' );

				add_action( 'after_switch_theme', array( $this, 'new_theme' ) );
				add_action( 'init', array( $this, 'init' ) );
				add_action( 'plugins_loaded', array( $this, 'load_language' ) );
				add_action( 'wp_footer', 'edu_getTimers' );

				register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
				$this->timers[ __METHOD__ ] = microtime( true ) - $this->timers[ __METHOD__ ];
			}

			public function init() {
				$this->timers[ __METHOD__ ] = microtime( true );
				$this->integrations         = new EDU_IntegrationLoader();
				$this->restController->register_routes();
				edu_LoadPhrases();
				$this->timers[ __METHOD__ ] = microtime( true ) - $this->timers[ __METHOD__ ];
			}

			public static function SetupWarning() {
				?>
                <div class="notice notice-warning is-dismissable">
                    <p><?php echo sprintf( __( 'Please complete the configuration: %1$sEduAdmin - Api Authentication%2$s', 'eduadmin-booking' ), '<a href="' . admin_url() . 'admin.php?page=eduadmin-settings">', '</a>' ); ?></p>
                </div>
				<?php
			}

			/**
			 * @return string Returns the users IP adress
			 */
			public function get_ip_adress() {
				$ipCheck = array( 'HTTP_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' );
				foreach ( $ipCheck as $header ) {
					if ( isset( $_SERVER[ $header ] ) && ! empty( $_SERVER[ $header ] ) ) {
						return $_SERVER[ $header ];
					}
				}

				return "UNKNOWN";
			}

			public function load_language() {
				$this->timers[ __METHOD__ ] = microtime( true );
				$locale                     = apply_filters( 'plugin_locale', get_locale(), 'eduadmin-booking' );
				load_textdomain( 'eduadmin-booking', WP_LANG_DIR . '/eduadmin/' . 'eduadmin-booking' . '-' . $locale . '.mo' );
				load_plugin_textdomain( 'eduadmin-booking', false, EDUADMIN_PLUGIN_PATH . '/languages' );
				$this->timers[ __METHOD__ ] = microtime( true ) - $this->timers[ __METHOD__ ];
			}

			public function new_theme() {
				update_option( 'eduadmin-options_have_changed', true );
			}

			public function deactivate() {
				eduadmin_deactivate_rewrite();
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
                        <p><?php echo __( 'Could not set timezone', 'eduadmin-booking' ); ?></p>
                    </div>
					<?php
				} );
			}
		}

		/* Handle plugin-settings */
		add_action(
			'wp_loaded',
			function() {
				EDU()->timers[ __METHOD__ ] = microtime( true );
				if ( isset( $_POST['option_page'] ) && 'eduadmin-plugin-settings' === sanitize_text_field( $_POST['option_page'] ) ) {
					$integrations = EDU()->integrations->integrations;
					foreach ( $integrations as $integration ) {
						do_action( 'eduadmin-plugin-save_' . $integration->id );
					}
					add_action( 'admin_notices', function() {
						?>
                        <div class="notice notice-success is-dismissible">
                            <p><?php _e( 'Plugin settings saved', 'eduadmin-booking' ); ?></p>
                        </div>
						<?php
					} );
				}
				EDU()->timers[ __METHOD__ ] = microtime( true ) - EDU()->timers[ __METHOD__ ];
			} );
	endif;