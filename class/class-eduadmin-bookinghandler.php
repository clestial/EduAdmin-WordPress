<?php

	class EduAdminBookingHandler {
		/**
		 * @var EduAdmin
		 */
		private $edu = null;

		public function __construct( $_edu ) {
			$this->edu = $_edu;

			add_action( 'wp_loaded', array( $this, 'process_booking' ) );
		}

		public function process_booking() {
			if ( isset( $_POST['act'] ) && 'bookCourse' === sanitize_text_field( $_POST['act'] ) ) {
				$singlePersonBooking = get_option( 'eduadmin-singlePersonBooking', false );
				$bookingInfo         = array();

				$eventCustomerLnkID = 0;

				if ( $singlePersonBooking ) {
					include_once( EDUADMIN_PLUGIN_PATH . '/content/template/bookingTemplate/__bookSingleParticipant.php' );
				} else {
					include_once( EDUADMIN_PLUGIN_PATH . '/content/template/bookingTemplate/__bookMultipleParticipants.php' );
				}

				$filter = new XFiltering();
				$f      = new XFilter( 'EventCustomerLnkID', '=', $bookingInfo['eventCustomerLnkId'] );
				$filter->AddItem( $f );

				$eventBooking = EDU()->api->GetEventBookingV2( EDU()->get_token(), '', $filter->ToString() )[0];

				$filter = new XFiltering();
				$f      = new XFilter( 'CustomerID', '=', $bookingInfo['customerId'] );
				$filter->AddItem( $f );

				$_customer = EDU()->api->GetCustomerV3( EDU()->get_token(), '', $filter->ToString(), false )[0];

				$filter = new XFiltering();
				$f      = new XFilter( 'CustomerContactID', '=', $bookingInfo['contactId'] );
				$filter->AddItem( $f );

				$_contact = EDU()->api->GetCustomerContactV2( EDU()->get_token(), '', $filter->ToString(), false )[0];

				$ebi                       = new EduAdminBookingInfo( $eventBooking, $_customer, $_contact );
				$GLOBALS['edubookinginfo'] = $ebi;

				do_action( 'eduadmin-checkpaymentplugins', $ebi );

				if ( !$ebi->NoRedirect ) {
					wp_redirect( get_page_link( get_option( 'eduadmin-thankYouPage', '/' ) ) . "?edu-thankyou=" . $eventCustomerLnkID );
					exit();
				}
			}
		}
	}