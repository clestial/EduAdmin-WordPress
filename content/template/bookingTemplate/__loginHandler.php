<?php
if ( isset( $_REQUEST['bookingLoginAction'] ) && ! empty( $_REQUEST['bookingLoginAction'] ) ) {
	if ( $_REQUEST['bookingLoginAction'] === "checkEmail" && ! empty( $_REQUEST['eduadminloginEmail'] ) ) {
		$ft                 = new XFiltering();
		$selectedLoginField = get_option( 'eduadmin-loginField', 'Email' );

		$f = new XFilter( $selectedLoginField, '=', trim( $_REQUEST['eduadminloginEmail'] ) );
		$ft->AddItem( $f );

		$f = new XFilter( 'Disabled', '=', false );
		$ft->AddItem( $f );

		$matchingContacts       = $eduapi->GetCustomerContact( $edutoken, '', $ft->ToString(), true );
		$_SESSION['needsLogin'] = false;
		$_SESSION['checkEmail'] = true;
		if ( ! empty( $matchingContacts ) ) {
			foreach ( $matchingContacts as $con ) {
				if ( $con->CanLogin == 1 ) {
					$_SESSION['needsLogin'] = true;
					break;
				}
			}
		}

		if ( count( $matchingContacts ) >= 1 ) {
			$con = $matchingContacts[0];
			if ( $con->CanLogin == 1 ) {
				$_SESSION['needsLogin'] = true;

				return;
			}
			$_SESSION['needsLogin'] = false;
			$filter                 = new XFiltering();
			$f                      = new XFilter( 'CustomerID', '=', $con->CustomerID );
			$filter->AddItem( $f );
			$f = new XFilter( 'Disabled', '=', false );
			$filter->AddItem( $f );
			$customers = $eduapi->GetCustomer( $edutoken, '', $filter->ToString(), true );
			if ( count( $customers ) == 1 ) {
				$customer                       = $customers[0];
				$user                           = new stdClass;
				$c1                             = json_encode( $con );
				$user->Contact                  = json_decode( $c1 );
				$c2                             = json_encode( $customer );
				$user->Customer                 = json_decode( $c2 );
				$_SESSION['eduadmin-loginUser'] = $user;
			} else {
				return;
			}
		}


		if ( empty( $matchingContacts ) ) {
			$contact            = new CustomerContact;
			$selectedLoginField = get_option( 'eduadmin-loginField', 'Email' );
			switch ( $selectedLoginField ) {
				case "Email":
					$contact->Email = $_REQUEST['eduadminloginEmail'];
					break;
				case "CivicRegistrationNumber":
					$contact->CivicRegistrationNumber = $_REQUEST['eduadminloginEmail'];
					break;
			}

			$customer = new Customer;

			$user                           = new stdClass;
			$user->NewCustomer              = true;
			$c1                             = json_encode( $contact );
			$user->Contact                  = json_decode( $c1 );
			$c2                             = json_encode( $customer );
			$user->Customer                 = json_decode( $c2 );
			$_SESSION['eduadmin-loginUser'] = $user;
		}
		die( "<script type=\"text/javascript\">location.href = location.href;</script>" );
	} else if ( $_REQUEST['bookingLoginAction'] == "loginEmail" && ! empty( $_REQUEST['eduadminloginEmail'] ) && ! empty( $_REQUEST['eduadminpassword'] ) ) {
		$user = loginContactPerson( $_REQUEST['eduadminloginEmail'], $_REQUEST['eduadminpassword'] );
		if ( $user != null ) {
			die( "<script type=\"text/javascript\">location.href = location.href;</script>" );
		} else {
			$_SESSION['eduadminLoginError'] = edu__( "Wrong email or password." );
		}
	} else if ( $_REQUEST['bookingLoginAction'] == "forgot" ) {
		$success                             = sendForgottenPassword( $_POST['eduadminloginEmail'] );
		$_SESSION['eduadmin-forgotPassSent'] = $success;
	}
}