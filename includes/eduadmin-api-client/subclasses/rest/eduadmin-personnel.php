<?php

	/**
	 * Class EduAdmin_REST_Personnel
	 */
	class EduAdmin_REST_Personnel extends EduAdminRESTClient {
		protected $api_url = "/v1/Personnel";

		/**
		 * @param EduAdmin_Data_Login $login
		 *
		 * @return mixed
		 */
		public function Login( EduAdmin_Data_Login $login ) {
			return parent::POST( "Login",
			                     $login,
			                     get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param integer $personnelId
		 * @param string  $password
		 *
		 * @return mixed
		 */
		public function LoginById( $personnelId, $password ) {
			return parent::POST( "/$personnelId/Login",
			                     array(
				                     'password' => $password,
			                     ),
			                     get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param integer $personnelId
		 *
		 * @return mixed
		 */
		public function SendResetPasswordEmailById( $personnelId ) {
			return parent::POST( "/$personnelId/SendResetPasswordEmail",
			                     array(),
			                     get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param string $email
		 *
		 * @return mixed
		 */
		public function SendResetPasswordEmail( $email ) {
			return parent::POST( "/SendResetPasswordEmail",
			                     array(
				                     'email' => $email,
			                     ),
			                     get_called_class() . "|" . __FUNCTION__
			);
		}
	}