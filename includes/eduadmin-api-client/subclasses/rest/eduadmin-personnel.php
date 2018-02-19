<?php

/**
 * Class EduAdmin_REST_Personnel
 */
class EduAdmin_REST_Personnel extends EduAdminRESTClient {
	protected $api_url = '/v1/Personnel';

	/**
	 * @param EduAdmin_Data_Login|stdClass|object $login
	 *
	 * @return mixed
	 */
	public function Login( $login ) {
		return parent::POST( 'Login',
			$login,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer $personnel_id
	 * @param string $password
	 *
	 * @return mixed
	 */
	public function LoginById( $personnel_id, $password ) {
		return parent::POST( "/$personnel_id/Login",
			array(
				'password' => $password,
			),
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer $personnel_id
	 *
	 * @return mixed
	 */
	public function SendResetPasswordEmailById( $personnel_id ) {
		return parent::POST( "/$personnel_id/SendResetPasswordEmail",
			array(),
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param string $email
	 *
	 * @return mixed
	 */
	public function SendResetPasswordEmail( $email ) {
		return parent::POST( '/SendResetPasswordEmail',
			array(
				'email' => $email,
			),
			get_called_class() . '|' . __FUNCTION__
		);
	}
}