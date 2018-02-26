<?php

/**
 * Class EduAdmin_REST_Coupon
 */
class EduAdmin_REST_Coupon extends EduAdminRESTClient {
	protected $api_url = '/v1/Coupon';

	/**
	 * @param integer $event_id
	 * @param string $coupon_code
	 *
	 * @return bool|mixed
	 */
	public function IsValid( $event_id, $coupon_code ) {
		return 'true' === parent::GET(
			'/IsValid',
			array(
				'eventId'    => $event_id,
				'couponCode' => $coupon_code,
			),
			get_called_class() . '|' . __FUNCTION__
		)['response'];
	}
}