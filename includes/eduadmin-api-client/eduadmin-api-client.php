<?php
	if ( ! class_exists( 'EduAdmin_OData_Client' ) ) {
		/**
		 * Class EduAdmin_OData_Client
		 */
		class EduAdmin_OData_Client {
			protected static $_instance = null;
			/**
			 * @var string User Agent used by the REST client
			 */

			/**
			 * @var EduAdminToken API Token
			 */
			public $api_token = null;
			/**
			 * @var array
			 */
			public $timers;
			/**
			 * @var EduAdminRESTClient
			 */
			public $rest = null;

			/**
			 * @return EduAdmin_OData_Client|null
			 */
			public static function instance() {
				if ( is_null( self::$_instance ) ) {
					self::$_instance = new self();
				}

				return self::$_instance;
			}

			/**
			 * @var EduAdmin_ODataHolder|null
			 */
			public $OData = null;
			/**
			 * @var EduAdmin_RESTHolder|null
			 */
			public $REST = null;

			/**
			 * EduAdmin_OData_Client constructor.
			 */
			public function __construct() {
				$this->timers = array();
				$t            = $this->StartTimer( 'InitRESTClient' );
				$this->includes();
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

			private function includes() {
				include_once( "rest-client.php" );
				include_once( "odata-client.php" );
				include_once( "eduadmin-api-classes.php" );

				/* OData Classes */
				include_once( "subclasses/odata/eduadmin-bookings.php" );
				include_once( "subclasses/odata/eduadmin-categories.php" );
				include_once( "subclasses/odata/eduadmin-courselevels.php" );
				include_once( "subclasses/odata/eduadmin-coursetemplates.php" );
				include_once( "subclasses/odata/eduadmin-customergroups.php" );
				include_once( "subclasses/odata/eduadmin-customers.php" );
				include_once( "subclasses/odata/eduadmin-events.php" );
				include_once( "subclasses/odata/eduadmin-grades.php" );
				include_once( "subclasses/odata/eduadmin-interestregistrations.php" );
				include_once( "subclasses/odata/eduadmin-locations.php" );
				include_once( "subclasses/odata/eduadmin-personnel.php" );
				include_once( "subclasses/odata/eduadmin-persons.php" );
				include_once( "subclasses/odata/eduadmin-programmebookings.php" );
				include_once( "subclasses/odata/eduadmin-programmes.php" );
				include_once( "subclasses/odata/eduadmin-programmestarts.php" );
				include_once( "subclasses/odata/eduadmin-regions.php" );
				include_once( "subclasses/odata/eduadmin-reports.php" );
				/* /OData Classes */

				/* REST Classes */
				include_once( "subclasses/rest/eduadmin-booking.php" );
				include_once( "subclasses/rest/eduadmin-coupon.php" );
				include_once( "subclasses/rest/eduadmin-customer.php" );
				include_once( "subclasses/rest/eduadmin-event.php" );
				include_once( "subclasses/rest/eduadmin-interestregistration.php" );
				include_once( "subclasses/rest/eduadmin-organisation.php" );
				include_once( "subclasses/rest/eduadmin-participant.php" );
				include_once( "subclasses/rest/eduadmin-person.php" );
				include_once( "subclasses/rest/eduadmin-personnel.php" );
				include_once( "subclasses/rest/eduadmin-programmebooking.php" );
				include_once( "subclasses/rest/eduadmin-report.php" );
				/* /REST Classes */

				$this->rest = new EduAdminRESTClient();

				// Load OData classes
				$this->OData = new EduAdmin_ODataHolder();

				$this->OData->Bookings              = new EduAdmin_OData_Bookings();
				$this->OData->Categories            = new EduAdmin_OData_Categories();
				$this->OData->CourseLevels          = new EduAdmin_OData_CourseLevels();
				$this->OData->CourseTemplates       = new EduAdmin_OData_CourseTemplates();
				$this->OData->CustomerGroups        = new EduAdmin_OData_CustomerGroups();
				$this->OData->Customers             = new EduAdmin_OData_Customers();
				$this->OData->Events                = new EduAdmin_OData_Events();
				$this->OData->Grades                = new EduAdmin_OData_Grades();
				$this->OData->InterestRegistrations = new EduAdmin_OData_InterestRegistrations();
				$this->OData->Locations             = new EduAdmin_OData_Locations();
				$this->OData->Personnel             = new EduAdmin_OData_Personnel();
				$this->OData->Persons               = new EduAdmin_OData_Persons();
				$this->OData->ProgrammeBookings     = new EduAdmin_OData_ProgrammeBookings();
				$this->OData->Programmes            = new EduAdmin_OData_Programmes();
				$this->OData->ProgrammeStarts       = new EduAdmin_OData_ProgrammeStarts();
				$this->OData->Regions               = new EduAdmin_OData_Regions();
				$this->OData->Reports               = new EduAdmin_OData_Reports();

				// Load REST classes
				$this->REST = new EduAdmin_RESTHolder();

				$this->REST->Booking              = new EduAdmin_REST_Booking();
				$this->REST->Coupon               = new EduAdmin_REST_Coupon();
				$this->REST->Customer             = new EduAdmin_REST_Customer();
				$this->REST->Event                = new EduAdmin_REST_Event();
				$this->REST->InterestRegistration = new EduAdmin_REST_InterestRegistration();
				$this->REST->Organisation         = new EduAdmin_REST_Organisation();
				$this->REST->Participant          = new EduAdmin_REST_Participant();
				$this->REST->Person               = new EduAdmin_REST_Person();
				$this->REST->Personnel            = new EduAdmin_REST_Personnel();
				$this->REST->ProgrammeBooking     = new EduAdmin_REST_ProgrammeBooking();
				$this->REST->Report               = new EduAdmin_REST_Report();

				add_action( 'eduadmin-showtimers', array( $this, 'RenderTimers' ) );
			}

			public function RenderTimers() {
				echo "<!-- EduAdmin API (OData/REST) Client - Timers -->\n";
				$totalValue = 0;
				foreach ( EDUAPI()->timers as $timer => $value ) {
					echo "<!-- " . $timer . ": " . round( $value * 1000, 2 ) . "ms -->\n";
					$totalValue += $value;
				}
				echo "<!-- EduAdmin Total: " . round( $totalValue * 1000, 2 ) . "ms -->\n";
				echo "<!-- /EduAdmin API (OData/REST) Client - Timers -->\n";
			}

			/**
			 * @param $api_user
			 * @param $api_pass
			 */
			public function SetCredentials( $api_user, $api_pass ) {
				EduAdminRESTClient::$api_user = $api_user;
				EduAdminRESTClient::$api_pass = $api_pass;
			}

			/**
			 * @return EduAdminToken
			 * @throws Exception
			 */
			public function GetToken() {
				if ( ! isset( EduAdminRESTClient::$api_user ) || ! isset( EduAdminRESTClient::$api_pass ) ) {
					throw new Exception( "You must use SetCredentials before fetching token." );
				}

				$result          = $this->rest->POST( "/token", array(
					'username'   => EduAdminRESTClient::$api_user,
					'password'   => EduAdminRESTClient::$api_pass,
					'grant_type' => 'password',
				), "GetToken", false );
				$this->api_token = new EduAdminToken( $result );

				return $this->api_token;
			}

			/**
			 * @param EduAdminToken $token
			 */
			public function SetToken( $token ) {
				$this->api_token = $token;
			}
		}

		/**
		 * @return EduAdmin_OData_Client|null
		 */
		function EDUAPI() {
			return EduAdmin_OData_Client::instance();
		}

		$GLOBALS['edu-api'] = EDUAPI();
	}