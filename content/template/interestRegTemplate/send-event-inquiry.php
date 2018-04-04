<?php
if ( wp_verify_nonce( $_POST['edu-interest-nonce'], 'edu-event-interest' ) ) {
	$required_fields   = array();
	$required_fields[] = 'edu-companyName';

	$missing_fields = false;
	foreach ( $required_fields as $field ) {
		if ( empty( $_REQUEST[ $field ] ) ) {
			$missing_fields = true;
		}
	}

	if ( ! empty( $_POST['email'] ) ) { // Input var okay
		exit( 500 );
	}

	if ( $missing_fields ) {
		// TODO: Show an error message that some fields are missing
		// Should not be able to happen, since we should validate the fields first
		// And then we'd have to go through the trouble to recreate all participants.
	} else {
		$inquiry                       = new EduAdmin_Data_InterestRegistrationBasic();
		$inquiry->CourseTemplateId     = intval( $_POST['objectid'] ); // Input var okay
		$inquiry->EventId              = intval( $_POST['eventid'] ); // Input var okay
		$inquiry->NumberOfParticipants = intval( $_POST['edu-participants'] ); // Input var okay
		$inquiry->CompanyName          = sanitize_text_field( $_POST['edu-companyName'] ); // Input var okay
		$inquiry->FirstName            = sanitize_text_field( $_POST['edu-contactFirstName'] ); // Input var okay
		$inquiry->LastName             = sanitize_text_field( $_POST['edu-contactLastName'] ); // Input var okay
		$inquiry->Email                = sanitize_email( $_POST['edu-emailAddress'] ); // Input var okay
		$inquiry->Phone                = sanitize_text_field( $_POST['edu-phone'] ); // Input var okay
		$inquiry->Mobile               = sanitize_text_field( $_POST['edu-mobile'] ); // Input var okay
		$inquiry->Notes                = sanitize_textarea_field( $_POST['edu-notes'] ); // Input var okay

		$inquiry = EDUAPI()->REST->InterestRegistration->CreateBasic( $inquiry );

		$inquiry_id = $inquiry['InterestRegistrationId'];
		die( '<script type="text/javascript">alert(\'' . esc_js( __( 'Thank you for your inquiry! We will be in touch!', 'eduadmin-booking' ) ) . '\'); location.href = \'' . esc_js( get_home_url() . '?edu-thankyouinquiry=' . $inquiry_id ) . '\';</script>' );
	}
}