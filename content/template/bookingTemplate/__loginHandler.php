<?php
	if ( isset( $_REQUEST['eduformloginaction'] ) && ! empty( $_REQUEST['eduformloginaction'] ) ) {
		if ( $_REQUEST['eduformloginaction'] === "checkEmail" && ! empty( $_REQUEST['eduadminloginEmail'] ) ) {
			$ft                        = new XFiltering();
			$selectedLoginField        = get_option( 'eduadmin-loginField', 'Email' );
			$allowCustomerRegistration = get_option( "eduadmin-allowCustomerRegistration", true );

			$f = new XFilter( $selectedLoginField, '=', trim( sanitize_text_field( $_REQUEST['eduadminloginEmail'] ) ) );
			$ft->AddItem( $f );

			$f = new XFilter( 'Disabled', '=', false );
			$ft->AddItem( $f );

			$matchingContacts            = EDU()->api->GetCustomerContact( EDU()->get_token(), '', $ft->ToString(), true );
			EDU()->session['needsLogin'] = false;
			EDU()->session['checkEmail'] = true;
			if ( ! empty( $matchingContacts ) ) {
				foreach ( $matchingContacts as $con ) {
					if ( $con->CanLogin == 1 ) {
						EDU()->session['needsLogin'] = true;
						break;
					}
				}
			}

			if ( count( $matchingContacts ) >= 1 ) {
				$con = $matchingContacts[0];
				if ( $con->CanLogin == 1 ) {
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
				if ( count( $customers ) == 1 ) {
					$customer                            = $customers[0];
					$user                                = new stdClass;
					$c1                                  = json_encode( $con );
					$user->Contact                       = json_decode( $c1 );
					$c2                                  = json_encode( $customer );
					$user->Customer                      = json_decode( $c2 );
					EDU()->session['eduadmin-loginUser'] = $user;
				} else {
					return;
				}
			}

			if ( $allowCustomerRegistration && empty( $matchingContacts ) ) {
				$contact            = new CustomerContact;
				$selectedLoginField = get_option( 'eduadmin-loginField', 'Email' );
				switch ( $selectedLoginField ) {
					case "Email":
						$contact->Email = sanitize_email( $_REQUEST['eduadminloginEmail'] );
						break;
					case "CivicRegistrationNumber":
						$contact->CivicRegistrationNumber = sanitize_text_field( $_REQUEST['eduadminloginEmail'] );
						break;
				}

				$customer = new Customer;

				$user                                = new stdClass;
				$user->NewCustomer                   = true;
				$c1                                  = json_encode( $contact );
				$user->Contact                       = json_decode( $c1 );
				$c2                                  = json_encode( $customer );
				$user->Customer                      = json_decode( $c2 );
				EDU()->session['eduadmin-loginUser'] = $user;
			} else {
				EDU()->session['needsLogin']         = true;
				EDU()->session['checkEmail']         = true;
				EDU()->session['eduadminLoginError'] = __( "Could not find any users with that info.", 'eduadmin-booking' );
			}
			die( "<script type=\"text/javascript\">location.href = './?eid=" . intval( $_REQUEST['eid'] ) . "';</script>" );
		} else if ( $_REQUEST['eduformloginaction'] == "forgot" ) {
			$success                                  = sendForgottenPassword( sanitize_text_field( $_POST['eduadminloginEmail'] ) );
			EDU()->session['eduadmin-forgotPassSent'] = $success;
		}
	}