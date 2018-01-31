<?php

	/**
	 * Class EduAdmin_REST_Coupon
	 */
	class EduAdmin_REST_Coupon extends EduAdminRESTClient {
		protected $api_url = "/v1/Coupon";

		/**
		 * @param integer $eventId
		 * @param string  $couponCode
		 *
		 * @return bool|mixed
		 */
		public function IsValid( $eventId, $couponCode ) {
			return parent::GET(
				"/IsValid",
				array(
					'eventId'    => $eventId,
					'couponCode' => $couponCode,
				),
				get_called_class() . "|" . __FUNCTION__
			);
		}
	}