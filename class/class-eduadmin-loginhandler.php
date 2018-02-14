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
		$surl     = get_home_url();
		$cat      = get_option( 'eduadmin-rewriteBaseUrl' );
		$base_url = $surl . '/' . $cat;

		$regular_login = ! empty( $_POST['eduformloginaction'] ) && 'login' === sanitize_text_field( wp_unslash( $_POST['eduformloginaction'] ) ); // Input var okay.

		if ( ! empty( $_POST['eduadminloginEmail'] ) && ! empty( $_POST['eduadminpassword'] ) ) { // Input var okay.
			$login_field = get_option( 'eduadmin-loginField', 'Email' );

			$possible_persons = EDUAPI()->OData->Persons->Search(
				'PersonId',
				"CanLogin and $login_field eq '" . sanitize_text_field( wp_unslash( $_POST['eduadminloginEmail'] ) ) . '\'' // Input var okay.
			)['value'];

			if ( 1 === count( $possible_persons ) ) {
				$login_result = EDUAPI()->REST->Person->LoginById(
					$possible_persons[0]['PersonId'],
					sanitize_text_field( $_POST['eduadminpassword'] ) // Input var okay.
				);

				if ( 200 === $login_result['@curl']['http_code'] ) {
					$contact = EDUAPI()->OData->Persons->GetItem(
						$login_result['PersonId']
					);

					unset( $contact['@odata.context'] );
					unset( $contact['@curl'] );

					$customer = EDUAPI()->OData->Customers->GetItem(
						$login_result['CustomerId'],
						null,
						'BillingInfo'
					);

					unset( $customer['@odata.context'] );
					unset( $customer['@curl'] );

					$user           = new stdClass;
					$c1             = wp_json_encode( $contact );
					$user->Contact  = json_decode( $c1 );
					$c2             = wp_json_encode( $customer );
					$user->Customer = json_decode( $c2 );

					EDU()->session['eduadmin-loginUser'] = $user;

					setcookie( 'eduadmin_loginUser', wp_json_encode( EDU()->session['eduadmin-loginUser']->Contact ), time() + 3600, COOKIEPATH, COOKIE_DOMAIN );
				}
			}

			if ( isset( $user ) ) {
				if ( $regular_login ) {
					if ( ! empty( $_POST['eduReturnUrl'] ) ) {
						wp_safe_redirect( esc_url_raw( wp_unslash( $_POST['eduReturnUrl'] ) ) ); // Input var okay.
					} else {
						wp_safe_redirect( esc_url_raw( $base_url . '/profile/myprofile/' . edu_getQueryString() ) );
					}
					exit();
				}
			} else {
				EDU()->session['eduadminLoginError'] = __( 'Wrong username or password.', 'eduadmin-booking' );
			}
		}
	}
}