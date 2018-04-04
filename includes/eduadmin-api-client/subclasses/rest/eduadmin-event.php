<?php

/**
 * Class EduAdmin_REST_Event
 */
class EduAdmin_REST_Event extends EduAdminRESTClient {
	protected $api_url = '/v1/Event';

	/**
	 * @param integer $event_id
	 * @param bool|null $show_external
	 *
	 * @return mixed
	 */
	public function BookingQuestions( $event_id, $show_external = null ) {
		$params = array();
		if ( isset( $show_external ) ) {
			$params['showExternal'] = $show_external ? 'true' : 'false';
		}

		return parent::GET(
			"/$event_id/BookingQuestions",
			$params,
			get_called_class() . '|' . __FUNCTION__
		);
	}
}