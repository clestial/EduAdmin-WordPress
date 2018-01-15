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

			$regularLogin = isset ( $_POST['eduformloginaction'] ) && sanitize_text_field( $_POST['eduformloginaction'] ) == "login";

			if ( isset( $_POST['eduadminloginEmail'] ) && isset( $_POST['eduadminpassword'] ) && ! empty( $_POST['eduadminpassword'] ) ) {
				$eduapi   = EDU()->api;
				$edutoken = EDU()->get_token();

				$loginField = get_option( 'eduadmin-loginField', 'Email' );

				$filter = new XFiltering();
				$f      = new XFilter( $loginField, '=', sanitize_text_field( $_POST['eduadminloginEmail'] ) );
				$filter->AddItem( $f );
				$f = new XFilter( 'Loginpass', '=', sanitize_text_field( $_POST['eduadminpassword'] ) );
				$filter->AddItem( $f );
				$f = new XFilter( 'CanLogin', '=', true );
				$filter->AddItem( $f );
				$f = new XFilter( 'Disabled', '=', false );
				$filter->AddItem( $f );
				$cc = $eduapi->GetCustomerContact( $edutoken, '', $filter->ToString(), true );
				if ( count( $cc ) == 1 ) {
					$contact = $cc[0];
					$filter  = new XFiltering();
					$f       = new XFilter( 'CustomerID', '=', $contact->CustomerID );
					$filter->AddItem( $f );
					$f = new XFilter( 'Disabled', '=', false );
					$filter->AddItem( $f );
					$customers = $eduapi->GetCustomerV2( $edutoken, '', $filter->ToString(), true );
					if ( count( $customers ) == 1 ) {
						$customer                            = $customers[0];
						$user                                = new stdClass;
						$c1                                  = json_encode( $contact );
						$user->Contact                       = json_decode( $c1 );
						$c2                                  = json_encode( $customer );
						$user->Customer                      = json_decode( $c2 );
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
					EDU()->session['eduadminLoginError'] = edu__( "Wrong username or password." );
				}
			}
		}
	}