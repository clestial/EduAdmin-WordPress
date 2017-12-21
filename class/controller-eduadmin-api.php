<?php

	class EduAdminAPIController {
		var $namespace;
		/**
		 * @var EduAdmin
		 */
		private $edu = null;

		public function __construct( $_edu ) {
			$this->namespace = "edu/v1";
			$edu             = $_edu;
		}

		public function register_routes() {
			register_rest_route( $this->namespace, '/authenticate', array(
				'methods'  => 'POST',
				'callback' => array( $this, 'authenticate' ),
				'args'     => array(
					'key' => array( 'required' => true ),
				),
			) );

			register_rest_route( $this->namespace, '/courselist', array(
				'methods'  => 'POST',
				'callback' => 'edu_listview_courselist',
				'args'     => array(
					'token'           => array( 'required' => true ),
					'objectIds'       => array( 'required' => true ),
					'showcoursedays'  => array(),
					'showcoursetimes' => array(),
					'currency'        => array(),
					'city'            => array(),
					'groupbycity'     => array(),
					'baseUrl'         => array(),
					'courseFolder'    => array(),
					'showmore'        => array(),
					'spotsleft'       => array(),
					'fewspots'        => array(),
					'spotsettings'    => array(),
					'eid'             => array(),
					'numberofevents'  => array(),
					'fetchmonths'     => array(),
					'showvenue'       => array(),
					'eventinquiry'    => array(),
				),
			) );

			register_rest_route( $this->namespace, '/courselist/events', array(
				'methods'  => 'POST',
				'callback' => 'edu_api_listview_eventlist',
				'args'     => array(
					'token'           => array( 'required' => true ),
					'showcoursedays'  => array(),
					'showcoursetimes' => array(),
					'currency'        => array(),
					'city'            => array(),
					'groupbycity'     => array(),
					'baseUrl'         => array(),
					'courseFolder'    => array(),
					'showmore'        => array(),
					'spotsleft'       => array(),
					'fewspots'        => array(),
					'spotsettings'    => array(),
					'eid'             => array(),
					'numberofevents'  => array(),
					'fetchmonths'     => array(),
					'showvenue'       => array(),
					'eventinquiry'    => array(),
				),
			) );

			register_rest_route( $this->namespace, '/eventlist', array(
				'methods'  => 'POST',
				'callback' => 'edu_api_eventlist',
				'args'     => array(
					'token'          => array( 'required' => true ),
					'objectid'       => array( 'required' => true ),
					'city'           => array(),
					'groupbycity'    => array(),
					'baseUrl'        => array(),
					'courseFolder'   => array(),
					'showmore'       => array(),
					'spotsleft'      => array(),
					'fewspots'       => array(),
					'spotsettings'   => array(),
					'eid'            => array(),
					'numberofevents' => array(),
					'fetchmonths'    => array(),
					'showvenue'      => array(),
					'eventinquiry'   => array(),
				),
			) );

			register_rest_route( $this->namespace, '/loginwidget', array(
				'methods'  => 'POST',
				'callback' => 'edu_api_loginwidget',
				'args'     => array(
					'baseUrl'      => array(),
					'courseFolder' => array(),
					'logintext'    => array(),
					'logouttext'   => array(),
					'guesttext'    => array(),
				),
			) );

			register_rest_route( $this->namespace, '/coupon/check', array(
				'methods'  => 'POST',
				'callback' => 'edu_api_check_coupon_code',
				'args'     => array(
					'token'      => array( 'required' => true ),
					'code'       => array( 'required' => true ),
					'objectId'   => array( 'required' => true ),
					'categoryId' => array( 'required' => true ),
				),
			) );
		}

		public function authenticate( $data ) {
			if ( empty( $data['key'] ) ) {
				return rest_ensure_response( new WP_Error( 'required_param', __( 'Missing parameter key', 'eduadmin-booking' ) ) );
			}
			$info = DecryptApiKey( $_POST['key'] );

			if ( ! isset( $_COOKIE['edu-usertoken'] ) ) {
				$token = EDU()->api->GetAuthToken( $info->UserId, $info->Hash );
				setcookie( 'edu-usertoken', $token, null, COOKIEPATH, COOKIE_DOMAIN, false, true );
			} else {
				$valid = EDU()->api->ValidateAuthToken( $_COOKIE['edu-usertoken'] );
				if ( ! $valid ) {
					$token = EDU()->api->GetAuthToken( $info->UserId, $info->Hash );
					setcookie( 'edu-usertoken', $token, null, COOKIEPATH, COOKIE_DOMAIN, false, true );
				} else {
					$token = $_COOKIE['edu-usertoken'];
				}
			}

			return rest_ensure_response( edu_encrypt( 'edu_js_token_crypto', $token ) );
		}
	}