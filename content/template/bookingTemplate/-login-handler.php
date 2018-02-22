<?php
if ( wp_verify_nonce( $_POST['edu-login-ver'], 'edu-profile-login' ) && ! empty( $_POST['eduformloginaction'] ) ) {
	if ( 'checkEmail' === $_POST['eduformloginaction'] && ! empty( $_POST['eduadminloginEmail'] ) ) {
		$ft                          = new XFiltering();
		$selected_login_field        = get_option( 'eduadmin-loginField', 'Email' );
		$allow_customer_registration = get_option( 'eduadmin-allowCustomerRegistration', true );

		$login_field = get_option( 'eduadmin-loginField', 'Email' );

		$possible_persons = EDUAPI()->OData->Persons->Search(
			null,
			"$login_field eq '" . sanitize_text_field( wp_unslash( $_POST['eduadminloginEmail'] ) ) . '\'', // Input var okay.
			'CustomFields($filter=ShowOnWeb;)'
		)['value'];

		EDU()->session['needsLogin'] = false;
		EDU()->session['checkEmail'] = true;
		if ( ! empty( $possible_persons ) ) {
			foreach ( $possible_persons as $con ) {
				if ( true === $con['CanLogin'] ) {
					EDU()->session['needsLogin'] = true;
					break;
				}
			}
		}

		if ( count( $possible_persons ) >= 1 ) {
			$con = $possible_persons[0];
			if ( true === $con['CanLogin'] ) {
				EDU()->session['needsLogin'] = true;
				//die( "<script type=\"text/javascript\">location.href = './?eid=" . intval( $_REQUEST['eid'] ) . "';</script>" );
			}
			EDU()->session['needsLogin'] = false;

			$customer = EDUAPI()->OData->Customers->GetItem(
				$con['CustomerId'],
				null,
				'BillingInfo,CustomFields($filter=ShowOnWeb;)'
			);
			if ( ! empty( $customer ) ) {
				$user                                = new stdClass();
				$c1                                  = wp_json_encode( $con );
				$user->Contact                       = json_decode( $c1 );
				$c2                                  = wp_json_encode( $customer );
				$user->Customer                      = json_decode( $c2 );
				EDU()->session['eduadmin-loginUser'] = $user;
			} else {
				return;
			}
		}

		if ( $allow_customer_registration && empty( $matching_contacts ) ) {
			$contact              = new EduAdmin_Data_Person();
			$selected_login_field = get_option( 'eduadmin-loginField', 'Email' );
			switch ( $selected_login_field ) {
				case 'Email':
					$contact['Email'] = sanitize_email( $_POST['eduadminloginEmail'] );
					break;
				case 'CivicRegistrationNumber':
					$contact['CivicRegistrationNumber'] = sanitize_text_field( $_POST['eduadminloginEmail'] );
					break;
			}

			$customer = new EduAdmin_Data_Customer();

			$user                                = new stdClass();
			$user->NewCustomer                   = true;
			$c1                                  = wp_json_encode( $contact );
			$user->Contact                       = json_decode( $c1 );
			$c2                                  = wp_json_encode( $customer );
			$user->Customer                      = json_decode( $c2 );
			EDU()->session['eduadmin-loginUser'] = $user;
		} else {
			EDU()->session['needsLogin'] = true;
			EDU()->session['checkEmail'] = true;
		}
		//die( "<script type=\"text/javascript\">location.href = './?eid=" . intval( $_REQUEST['eid'] ) . "';</script>" );
	}
}
