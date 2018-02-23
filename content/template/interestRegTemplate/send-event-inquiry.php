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

	if ( ! empty( $_REQUEST['email'] ) ) {
		exit( 500 );
	}

	if ( $missing_fields ) {
		// TODO: Show an error message that some fields are missing
		// Should not be able to happen, since we should validate the fields first
		// And then we'd have to go through the trouble to recreate all participants.
	} else {
		$inquiry                       = new EduAdmin_Data_InterestRegistrationBasic();
		$inquiry->CourseTemplateId     = intval( $_POST['objectid'] );
		$inquiry->EventId              = intval( $_POST['eventid'] );
		$inquiry->NumberOfParticipants = intval( $_POST['edu-participants'] );
		$inquiry->CompanyName          = sanitize_text_field( $_POST['edu-companyName'] );
		$inquiry->FirstName            = sanitize_text_field( $_POST['edu-contactFirstName'] );
		$inquiry->LastName             = sanitize_text_field( $_POST['edu-contactLastName'] );
		$inquiry->Email                = sanitize_email( $_POST['edu-emailAddress'] );
		$inquiry->Phone                = sanitize_text_field( $_POST['edu-phone'] );
		$inquiry->Mobile               = sanitize_text_field( $_POST['edu-mobile'] );
		$inquiry->Notes                = sanitize_textarea_field( $_POST['edu-notes'] );

		$inquiry    = EDUAPI()->REST->InterestRegistration->CreateBasic( $inquiry );
		$inquiry_id = $inquiry['InterestRegistrationId'];

		die( '<script type="text/javascript">alert(\'' . esc_js( __( 'Thank you for your inquiry! We will be in touch!', 'eduadmin-booking' ) ) . '\'); location.href = \'' . esc_js( get_page_link( '/' ) . '?edu-thankyouinquiry=' . $inquiry_id ) . '\';</script>' );
	}
}