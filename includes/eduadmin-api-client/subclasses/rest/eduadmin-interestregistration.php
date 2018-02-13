<?php

/**
 * Class EduAdmin_REST_InterestRegistration
 */
class EduAdmin_REST_InterestRegistration extends EduAdminRESTClient {
	protected $api_url = "/v1/InterestRegistration";

	/**
	 * @param EduAdmin_Data_InterestRegistrationBasic|stdClass|object $basic
	 *
	 * @return mixed
	 */
	public function CreateBasic( $basic ) {
		return parent::POST( "CreateBasic",
			$basic,
			get_called_class() . "|" . __FUNCTION__
		);
	}

	/**
	 * @param EduAdmin_Data_InterestRegistrationComplete|stdClass|object $complete
	 *
	 * @return mixed
	 */
	public function CreateComplete( $complete ) {
		return parent::POST( "CreateComplete",
			$complete,
			get_called_class() . "|" . __FUNCTION__
		);
	}
}