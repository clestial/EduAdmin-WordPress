<?php

/**
 * Class EduAdmin_REST_Customer
 */
class EduAdmin_REST_Customer extends EduAdminRESTClient {
	protected $api_url = '/v1/Customer';

	/**
	 * @param EduAdmin_Data_Customer|stdClass|object $customer
	 *
	 * @return mixed
	 */
	public function Create( $customer ) {
		return parent::POST( '/',
			$customer,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer $customer_id
	 * @param EduAdmin_Data_Customer|stdClass|object $customer
	 *
	 * @return mixed
	 */
	public function Update( $customer_id, $customer ) {
		return parent::PATCH( "/$customer_id",
			$customer,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer $customer_id
	 * @param integer $event_id
	 * @param integer|null $contact_person_id
	 *
	 * @return mixed
	 */
	public function GetValidVouchers( $customer_id, $event_id, $contact_person_id = null ) {
		$params = array();
		if ( isset( $contact_person_id ) ) {
			$params['contactPersonId'] = $contact_person_id;
		}

		return parent::GET(
			"/$customer_id/ValidVouchers/$event_id",
			$params,
			get_called_class() . '|' . __FUNCTION__
		);
	}
}