<?php
	defined( 'ABSPATH' ) or die( 'This plugin must be run within the scope of WordPress.' );
	define( 'EDUADMIN_PLUGIN_PATH', dirname( __FILE__ ) );
	defined( 'WP_SESSION_COOKIE' ) or define( 'WP_SESSION_COOKIE', 'eduadmin-cookie' );

	/*
	 * Plugin Name:	EduAdmin Booking
	 * Plugin URI:	http://www.eduadmin.se
	 * Description:	EduAdmin plugin to allow visitors to book courses at your website
	 * Tags:	booking, participants, courses, events, eduadmin, lega online
	 * Version:	1.0.26
	 * GitHub Plugin URI: multinetinteractive/eduadmin-wordpress
	 * GitHub Plugin URI: https://github.com/multinetinteractive/eduadmin-wordpress
	 * Requires at least: 4.7
	 * Tested up to: 4.9.2
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
			 * @var string
			 */
			public $version;
			/** @var array */
			public $weekDays;
			/** @var array */
			public $shortWeekDays;
			/** @var array */
			public $months;
			/** @var array */
			public $shortMonths;

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
				$t             = $this->StartTimer( __METHOD__ );
				$this->version = $this->get_version();
				$this->includes();
				$this->init_hooks();

				do_action( 'eduadmin_loaded' );
				$this->StopTimer( $t );
			}

			/**
			 * @param string $name Name of the timer
			 *
			 * @return string Returns the unique name for the created timer
			 */
			public function StartTimer( $name ) {
				$timer_id                                = count( $this->timers ) + 1;
				$this->timers[ $name . "_" . $timer_id ] = microtime( true );

				return $name . "_" . $timer_id;
			}

			/**
			 * @param string $name The unique name of the timer (Returned from StartTimer)
			 */
			public function StopTimer( $name ) {
				$this->timers[ $name ] = microtime( true ) - $this->timers[ $name ];
			}

			/**
			 * @param stdClass|array|object|null $object
			 */
			public function __writeDebug( $object ) {
				echo "<xmp>" . print_r( $object, true ) . "</xmp>";
			}

			public function get_version() {
				if ( function_exists( 'get_plugin_data' ) ) {
					$pData = get_plugin_data( __FILE__ );

					return $pData['Version'];
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
					$pData           = get_file_data( __FILE__, $default_headers );

					return $pData['Version'];
				}
			}

			/**
			 * @return mixed|null|string Returnerar en API-token från Lega Online
			 */
			public function get_token() {
				$t      = $this->StartTimer( __METHOD__ );
				$apiKey = get_option( 'eduadmin-api-key' );
				if ( ! $apiKey || empty( $apiKey ) ) {
					add_action( 'admin_notices', array( $this, 'SetupWarning' ) );
					$this->StopTimer( $t );

					return '';
				} else {
					$key = DecryptApiKey( $apiKey );
					if ( ! $key ) {
						add_action( 'admin_notices', array( $this, 'SetupWarning' ) );
						$this->StopTimer( $t );

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
					$this->token = $edutoken;
					$this->StopTimer( $t );

					return $this->token;
				}
			}

			private function includes() {
				$t = $this->StartTimer( __METHOD__ );
				include_once( 'includes/eduadmin-api-client/eduadmin-api-client.php' );
				if ( !class_exists( 'Recursive_ArrayAccess' ) ) {
					include_once( 'libraries/class-recursive-arrayaccess.php' );
				}

				if ( !class_exists( 'WP_Session' ) ) {
					include_once( 'libraries/class-wp-session.php' );
					include_once( 'libraries/wp-session.php' );
				}

				$this->session = WP_Session::get_instance();

				include_once( 'includes/loApiClasses.php' );
				include_once( 'includes/_apiFunctions.php' );
				include_once( 'class/class-eduadmin-bookinginfo.php' );
				include_once( 'class/class-eduadmin-bookinghandler.php' );
				include_once( 'class/class-eduadmin-loginhandler.php' );

				include_once( 'includes/plugin/edu-integration.php' ); // Integration interface
				include_once( 'includes/plugin/edu-integrationloader.php' ); // Integration loader
				include_once( 'includes/loApiClient.php' );

				if ( is_wp_error( $this->get_new_api_token() ) ) {
					add_action( 'admin_notices', array( $this, 'SetupWarning' ) );
				}

				$this->api = new EduAdminClient( $this->version );

				include_once( 'includes/_options.php' );
				include_once( 'includes/_ajaxFunctions.php' );
				include_once( 'includes/_rewrites.php' );
				include_once( 'includes/_shortcodes.php' );

				include_once( 'includes/_questionFunctions.php' );
				include_once( 'includes/_attributeFunctions.php' );
				include_once( 'includes/_textFunctions.php' );
				include_once( 'includes/_loginFunctions.php' );

				include_once( 'class/controller-eduadmin-api.php' );

				$this->restController = new EduAdminAPIController( $this );
				$this->bookingHandler = new EduAdminBookingHandler( $this );
				$this->loginHandler   = new EduAdminLoginHandler( $this );
				$this->StopTimer( $t );
			}

			public function call_home() {
				global $wp_version;
				$usageData   = array(
					'siteUrl'       => get_site_url(),
					'siteName'      => get_option( 'blogname' ),
					'wpVersion'     => $wp_version,
					'token'         => get_option( 'eduadmin-api-key' ),
					'pluginVersion' => $this->version,
				);
				$callHomeUrl = 'https://ws10.multinet.se/edu-plugin/wp_phone_home.php';
				wp_remote_post( $callHomeUrl, array( 'body' => $usageData ) );
			}

			private function init_hooks() {
				$t = $this->StartTimer( __METHOD__ );
				register_activation_hook( __FILE__, 'eduadmin_activate_rewrite' );

				add_action( 'after_switch_theme', array( $this, 'new_theme' ) );
				add_action( 'init', array( $this, 'init' ) );
				add_action( 'plugins_loaded', array( $this, 'load_language' ) );
				add_action( 'eduadmin_call_home', array( $this, 'call_home' ) );
				add_action( 'wp_footer', 'edu_getTimers' );

				register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
				$this->StopTimer( $t );
			}

			public function init() {
				$t                  = $this->StartTimer( __METHOD__ );
				$this->integrations = new EDU_IntegrationLoader();
				$this->restController->register_routes();
				$this->weekDays = array(
					1 => __( 'monday', 'eduadmin-booking' ),
					2 => __( 'tuesday', 'eduadmin-booking' ),
					3 => __( 'wednesday', 'eduadmin-booking' ),
					4 => __( 'thursday', 'eduadmin-booking' ),
					5 => __( 'friday', 'eduadmin-booking' ),
					6 => __( 'saturday', 'eduadmin-booking' ),
					7 => __( 'sunday', 'eduadmin-booking' ),
				);

				$this->shortWeekDays = array(
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

				$this->shortMonths = array(
					1  => __( 'jan', 'eduadmin-booking' ),
					2  => __( 'feb', 'eduadmin-booking' ),
					3  => __( 'mar', 'eduadmin-booking' ),
					4  => __( 'apr', 'eduadmin-booking' ),
					5  => __( 'may', 'eduadmin-booking' ),
					6  => __( 'jun', 'eduadmin-booking' ),
					7  => __( 'jul', 'eduadmin-booking' ),
					8  => __( 'aug', 'eduadmin-booking' ),
					9  => __( 'sep', 'eduadmin-booking' ),
					10 => __( 'oct', 'eduadmin-booking' ),
					11 => __( 'nov', 'eduadmin-booking' ),
					12 => __( 'dec', 'eduadmin-booking' ),
				);

				$this->StopTimer( $t );
			}

			public static function OldApiKeyWarning() {
				?>
                <div class="notice notice-warning is-dismissable">
                    <p><?php echo sprintf( __( 'You are using an old API key, please change to the new key: %1$sEduAdmin - Api Authentication%2$s<br />
If you need help with getting a new API-key, contact the %3$sMultiNet Support%4$s', 'eduadmin-booking' ),
					                       '<a href="' . admin_url() . 'admin.php?page=eduadmin-settings">', '</a>',
					                       '<a href="https://support.eduadmin.se/support/tickets/new" target="_blank">', '</a>' ); ?></p>
                </div>
				<?php
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
				$t      = $this->StartTimer( __METHOD__ );
				$locale = apply_filters( 'plugin_locale', get_locale(), 'eduadmin-booking' );
				load_textdomain( 'eduadmin-booking', WP_LANG_DIR . '/eduadmin/' . 'eduadmin-booking' . '-' . $locale . '.mo' );
				load_plugin_textdomain( 'eduadmin-booking', false, EDUADMIN_PLUGIN_PATH . '/languages' );

				if ( ! wp_next_scheduled( 'eduadmin_call_home' ) ) {
					wp_schedule_event( time(), 'hourly', 'eduadmin_call_home' );
				}

				$this->StopTimer( $t );
			}

			public function new_theme() {
				update_option( 'eduadmin-options_have_changed', true );
			}

			public function deactivate() {
				eduadmin_deactivate_rewrite();
				wp_clear_scheduled_hook( 'eduadmin_call_home' );
			}

			private function get_new_api_token() {
				$newKey = get_option( 'eduadmin-newapi-key', null );
				if ( $newKey != null ) {
					$key = DecryptApiKey( $newKey );
					EDUAPI()->SetCredentials( $key->UserId, $key->Hash );
				} else {
					$oldKey = get_option( 'eduadmin-api-key', null );
					if ( $oldKey != null ) {
						$key = DecryptApiKey( $oldKey );
						EDUAPI()->SetCredentials( $key->UserId, $key->Hash );
						//add_action( 'admin_notices', array( $this, 'OldApiKeyWarning' ) );
					} else {
						EDUAPI()->SetCredentials( '', '' );
					}
				}

				$currentToken = get_transient( 'eduadmin-newapi-token' );
				if ( $currentToken == null || ! $currentToken->IsValid() ) {
					$currentToken = EDUAPI()->GetToken();
					if ( empty( $currentToken->Issued ) ) {
						return new WP_Error( 'broke', __( "Faulty credentials for EduAdmin API provided, please correct this and try again. Or contact MultiNet support to get a new key.", 'eduadmin-booking' ) );
					}
					set_transient( 'eduadmin-newapi-token', $currentToken, WEEK_IN_SECONDS );
				}

				EDUAPI()->SetToken( $currentToken );

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
				$t = EDU()->StartTimer( __METHOD__ );
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
				EDU()->StopTimer( $t );
			} );
	endif;
