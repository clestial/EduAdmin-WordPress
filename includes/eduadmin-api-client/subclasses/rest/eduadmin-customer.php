<?php

	/**
	 * Class EduAdmin_REST_Customer
	 */
	class EduAdmin_REST_Customer extends EduAdminRESTClient {
		protected $api_url = "/v1/Customer";

		/**
		 * @param EduAdmin_Data_Customer $customer
		 *
		 * @return mixed
		 */
		public function Create( EduAdmin_Data_Customer $customer ) {
			return parent::POST( "",
			                     $customer,
			                     get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param integer                $customerId
		 * @param EduAdmin_Data_Customer $customer
		 *
		 * @return mixed
		 */
		public function Update( $customerId, EduAdmin_Data_Customer $customer ) {
			return parent::PATCH( "$customerId",
			                      $customer,
			                      get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param integer      $customerId
		 * @param integer      $eventId
		 * @param integer|null $contactPersonId
		 *
		 * @return mixed
		 */
		public function GetValidVouchers( $customerId, $eventId, $contactPersonId = null ) {
			$params = array();
			if ( isset( $contactPersonId ) ) {
				$params["contactPersonId"] = $contactPersonId;
			}

			return parent::GET(
				"/$customerId/ValidVouchers/$eventId",
				$params,
				get_called_class() . "|" . __FUNCTION__
			);
		}
	}