<?php
	$requiredFields   = array();
	$requiredFields[] = 'edu-companyName';

	$missingFields = false;
	foreach ( $requiredFields as $field ) {
		if ( empty( $_REQUEST[ $field ] ) ) {
			$missingFields = true;
		}
	}

	if ( ! empty( $_REQUEST[ 'email' ] ) ) {
		exit( 500 );
	}

	if ( $missingFields ) {
		// TODO: Show an error message that some fields are missing
		// Should not be able to happen, since we should validate the fields first
		// And then we'd have to go through the trouble to recreate all participants.
	} else {
		$inquiry                = new InterestRegObject();
		$inquiry->ObjectID      = intval( $_POST[ 'objectid' ] );
		$inquiry->ParticipantNr = intval( $_POST[ 'edu-participants' ] );
		$inquiry->CompanyName   = sanitize_text_field( $_POST[ 'edu-companyName' ] );
		$inquiry->ContactName   = sanitize_text_field( $_POST[ 'edu-contactName' ] );
		$inquiry->Email         = sanitize_email( $_POST[ 'edu-emailAddress' ] );
		$inquiry->Phone         = sanitize_text_field( $_POST[ 'edu-phone' ] );
		$inquiry->Mobile        = sanitize_text_field( $_POST[ 'edu-mobile' ] );
		$inquiry->Notes         = sanitize_textarea_field( $_POST[ 'edu-notes' ] );

		$inquiryId = EDU()->api->SetInterestRegObject( EDU()->get_token(), array( $inquiry ) )[ 0 ];

		die( "<script type=\"text/javascript\">alert('" . __( "Thank you for your inquiry! We will be in touch!", 'eduadmin-booking' ) . "'); location.href = '" . get_page_link( '/' ) . "?edu-thankyouinquiry=" . $inquiryId . "';</script>" );
	}