<?php
if ( wp_verify_nonce( $_POST['edu-login-ver'], 'edu-profile-login' ) && ! empty( $_POST['eduformloginaction'] ) ) {
	if ( 'checkEmail' === $_POST['eduformloginaction'] && ! empty( $_POST['eduadminloginEmail'] ) ) {
		$ft                          = new XFiltering();
		$selected_login_field        = get_option( 'eduadmin-loginField', 'Email' );
		$allow_customer_registration = get_option( 'eduadmin-allowCustomerRegistration', true );

		$f = new XFilter( $selected_login_field, '=', trim( sanitize_text_field( $_POST['eduadminloginEmail'] ) ) );
		$ft->AddItem( $f );

		$f = new XFilter( 'Disabled', '=', false );
		$ft->AddItem( $f );

		$matching_contacts           = EDU()->api->GetCustomerContact( EDU()->get_token(), '', $ft->ToString(), true );
		EDU()->session['needsLogin'] = false;
		EDU()->session['checkEmail'] = true;
		if ( ! empty( $matching_contacts ) ) {
			foreach ( $matching_contacts as $con ) {
				if ( 1 === $con->CanLogin ) {
					EDU()->session['needsLogin'] = true;
					break;
				}
			}
		}

		if ( count( $matching_contacts ) >= 1 ) {
			$con = $matching_contacts[0];
			if ( 1 === $con->CanLogin ) {
				EDU()->session['needsLogin'] = true;
				die( "<script type=\"text/javascript\">location.href = './?eid=" . intval( $_REQUEST['eid'] ) . "';</script>" );
			}
			EDU()->session['needsLogin'] = false;
			$filter                      = new XFiltering();
			$f                           = new XFilter( 'CustomerID', '=', $con->CustomerID );
			$filter->AddItem( $f );
			$f = new XFilter( 'Disabled', '=', false );
			$filter->AddItem( $f );
			$customers = EDU()->api->GetCustomer( EDU()->get_token(), '', $filter->ToString(), true );
			if ( 1 === count( $customers ) ) {
				$customer                            = $customers[0];
				$user                                = new stdClass();
				$c1                                  = json_encode( $con );
				$user->Contact                       = json_decode( $c1 );
				$c2                                  = json_encode( $customer );
				$user->Customer                      = json_decode( $c2 );
				EDU()->session['eduadmin-loginUser'] = $user;
			} else {
				return;
			}
		}

		if ( $allow_customer_registration && empty( $matching_contacts ) ) {
			$contact              = new CustomerContact();
			$selected_login_field = get_option( 'eduadmin-loginField', 'Email' );
			switch ( $selected_login_field ) {
				case 'Email':
					$contact->Email = sanitize_email( $_POST['eduadminloginEmail'] );
					break;
				case 'CivicRegistrationNumber':
					$contact->CivicRegistrationNumber = sanitize_text_field( $_POST['eduadminloginEmail'] );
					break;
			}

			$customer = new Customer();

			$user                                = new stdClass();
			$user->NewCustomer                   = true;
			$c1                                  = json_encode( $contact );
			$user->Contact                       = json_decode( $c1 );
			$c2                                  = json_encode( $customer );
			$user->Customer                      = json_decode( $c2 );
			EDU()->session['eduadmin-loginUser'] = $user;
		} else {
			EDU()->session['needsLogin']         = true;
			EDU()->session['checkEmail']         = true;
			EDU()->session['eduadminLoginError'] = __( 'Could not find any users with that info.', 'eduadmin-booking' );
		}
		die( "<script type=\"text/javascript\">location.href = './?eid=" . intval( $_REQUEST['eid'] ) . "';</script>" );
	} elseif ( 'forgot' === $_POST['eduformloginaction'] ) {
		$success                                  = sendForgottenPassword( sanitize_text_field( $_POST['eduadminloginEmail'] ) );
		EDU()->session['eduadmin-forgotPassSent'] = $success;
	}
}
