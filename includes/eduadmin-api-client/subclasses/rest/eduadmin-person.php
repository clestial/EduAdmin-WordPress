<?php

/**
 * Class EduAdmin_REST_Person
 */
class EduAdmin_REST_Person extends EduAdminRESTClient {
	protected $api_url = "/v1/Person";

	/**
	 * @param EduAdmin_Data_Person|stdClass|object $person
	 * @param bool $skipDuplicateMatch
	 *
	 * @return mixed
	 */
	public function Create( $person, $skipDuplicateMatch = false ) {
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
	 * @param integer $personId
	 * @param EduAdmin_Data_Person|stdClass|object $person
	 *
	 * @return mixed
	 */
	public function Update( $personId, $person ) {
		return parent::PATCH( "/$personId",
			$person,
			get_called_class() . "|" . __FUNCTION__
		);
	}

	/**
	 * @param EduAdmin_Data_Login|stdClass|object $login
	 *
	 * @return mixed
	 */
	public function Login( $login ) {
		return parent::POST( "Login",
			$login,
			get_called_class() . "|" . __FUNCTION__
		);
	}

	/**
	 * @param integer $personId
	 * @param string $password
	 *
	 * @return mixed
	 */
	public function LoginById( $personId, $password ) {
		return parent::POST( "/$personId/Login",
			array( '' => $password ),
			get_called_class() . "|" . __FUNCTION__,
			false
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