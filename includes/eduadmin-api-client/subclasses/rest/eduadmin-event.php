<?php

/**
 * Class EduAdmin_REST_Event
 */
class EduAdmin_REST_Event extends EduAdminRESTClient {
	protected $api_url = "/v1/Event";

	/**
	 * @param integer $eventId
	 * @param bool|null $showExternal
	 *
	 * @return mixed
	 */
	public function BookingQuestions( $eventId, $showExternal = null ) {
		$params = array();
		if ( isset( $showExternal ) ) {
			$params['showExternal'] = $showExternal ? 'true' : 'false';
		}

		return parent::GET(
			"/$eventId/BookingQuestions",
			$params,
			get_called_class() . "|" . __FUNCTION__
		);
	}
}