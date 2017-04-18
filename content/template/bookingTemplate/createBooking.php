<?php
$eventId = $_REQUEST['eid'];

$singlePersonBooking = get_option( 'eduadmin-singlePersonBooking', false );
$bookingInfo         = array();
$eventCustomerLinkID = 0;
if ( $singlePersonBooking ) {
	include_once( '__bookSingleParticipant.php' );
} else {
	include_once( '__bookMultipleParticipants.php' );
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

$ebi = new EduAdminBookingInfo( $eventBooking, $_customer, $_contact );
do_action( 'eduadmin-processbooking', $ebi );

do_action( 'eduadmin-bookingcompleted', $ebi );
if ( ! $ebi->NoRedirect ) {
	die( "<script type=\"text/javascript\">location.href = '" . get_page_link( get_option( 'eduadmin-thankYouPage', '/' ) ) . "?edu-thankyou=" . $eventCustomerLnkID . "';</script>" );
}