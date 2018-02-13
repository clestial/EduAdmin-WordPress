<?php

	class EduAdminLoginHandler {
		/**
		 * @var EduAdmin
		 */
		private $edu = null;

		public function __construct( $_edu ) {
			$this->edu = $_edu;
			add_action( 'wp_loaded', array( $this, 'process_login' ) );
		}

		public function process_login() {
			$surl    = get_home_url();
			$cat     = get_option( 'eduadmin-rewriteBaseUrl' );
			$baseUrl = $surl . '/' . $cat;

			$regularLogin = isset( $_POST['eduformloginaction'] ) && 'login' === sanitize_text_field( $_POST['eduformloginaction'] );

			if ( isset( $_POST['eduadminloginEmail'] ) && isset( $_POST['eduadminpassword'] ) && ! empty( $_POST['eduadminpassword'] ) ) {
				$loginField = get_option( 'eduadmin-loginField', 'Email' );

				$possiblePersons = EDUAPI()->OData->Persons->Search(
					"PersonId",
					"CanLogin and $loginField eq '" . sanitize_text_field( $_POST['eduadminloginEmail'] ) . "'"
				)["value"];

				if ( count( $possiblePersons ) == 1 ) {
					$loginResult = EDUAPI()->REST->Person->LoginById(
						$possiblePersons[0]["PersonId"],
						sanitize_text_field( $_POST['eduadminpassword'] )
					);

					if ( 200 === $loginResult["@curl"]["http_code"] ) {
						$contact = EDUAPI()->OData->Persons->GetItem(
							$loginResult["PersonId"]
						);

						unset( $contact["@odata.context"] );
						unset( $contact["@curl"] );

						$customer = EDUAPI()->OData->Customers->GetItem(
							$loginResult["CustomerId"],
							null,
							"BillingInfo"
						);

						unset( $customer["@odata.context"] );
						unset( $customer["@curl"] );

						$user           = new stdClass;
						$c1             = json_encode( $contact );
						$user->Contact  = json_decode( $c1 );
						$c2             = json_encode( $customer );
						$user->Customer = json_decode( $c2 );

						EDU()->session['eduadmin-loginUser'] = $user;

						setcookie( 'eduadmin_loginUser', json_encode( EDU()->session['eduadmin-loginUser']->Contact ), time() + 3600, COOKIEPATH, COOKIE_DOMAIN );
					}
				}

				if ( isset( $user ) ) {
					if ( $regularLogin ) {
						if ( isset( $_REQUEST['eduReturnUrl'] ) && ! empty( $_REQUEST['eduReturnUrl'] ) ) {
							wp_redirect( esc_url_raw( $_REQUEST['eduReturnUrl'] ) );
						} else {
							wp_redirect( $baseUrl . "/profile/myprofile/" . edu_getQueryString() );
						}
						exit();
					}
				} else {
					EDU()->session['eduadminLoginError'] = __( 'Wrong username or password.', 'eduadmin-booking' );
				}
			}
		}
	}