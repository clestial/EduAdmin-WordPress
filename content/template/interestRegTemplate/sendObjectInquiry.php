<?php
$requiredFields   = array();
$requiredFields[] = 'edu-companyName';

$missingFields = false;
foreach ( $requiredFields as $field ) {
	if ( empty( $_REQUEST[ $field ] ) ) {
		$missingFields = true;
	}
}

if ( $missingFields ) {
	// TODO: Show an error message that some fields are missing
	// Should not be able to happen, since we should validate the fields first
	// And then we'd have to go through the trouble to recreate all participants.
} else {
	$inquiry                = new InterestRegObject();
	$inquiry->ObjectID      = $_POST['objectid'];
	$inquiry->ParticipantNr = $_POST['edu-participants'];
	$inquiry->CompanyName   = $_POST['edu-companyName'];
	$inquiry->ContactName   = $_POST['edu-contactName'];
	$inquiry->Email         = $_POST['edu-emailAddress'];
	$inquiry->Phone         = $_POST['edu-phone'];
	$inquiry->Mobile        = $_POST['edu-mobile'];
	$inquiry->Notes         = $_POST['edu-notes'];

	$inquiryId = $eduapi->SetInterestRegObject( $edutoken, array( $inquiry ) )[0];

	die( "<script type=\"text/javascript\">alert('" . edu__( "Thank you for your inquiry! We will be in touch!" ) . "'); location.href = '" . get_page_link( '/' ) . "?edu-thankyouinquiry=" . $inquiryId . "';</script>" );
}