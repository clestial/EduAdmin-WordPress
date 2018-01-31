<?php

	/**
	 * Class EduAdmin_REST_Person
	 */
	class EduAdmin_REST_Person extends EduAdminRESTClient {
		protected $api_url = "/v1/Person";

		/**
		 * @param EduAdmin_Data_Person $person
		 * @param bool                 $skipDuplicateMatch
		 *
		 * @return mixed
		 */
		public function Create( EduAdmin_Data_Person $person, $skipDuplicateMatch = false ) {
			$query = array();
			if ( $skipDuplicateMatch ) {
				$query["skipDuplicateMatch"] = "true";
			}

			return parent::POST( "?" . http_build_query( $query ),
			                     $person,
			                     get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param EduAdmin_Data_Person $person
		 *
		 * @return mixed
		 */
		public function Update( EduAdmin_Data_Person $person ) {
			return parent::PATCH( "",
			                      $person,
			                      get_called_class() . "|" . __FUNCTION__
			);
		}

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
		 * @param integer $personId
		 * @param string  $password
		 *
		 * @return mixed
		 */
		public function LoginById( $personId, $password ) {
			return parent::POST( "/$personId/Login",
			                     array(
				                     'password' => $password,
			                     ),
			                     get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param integer $personId
		 *
		 * @return mixed
		 */
		public function SendResetPasswordEmailById( $personId ) {
			return parent::POST( "/$personId/SendResetPasswordEmail",
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