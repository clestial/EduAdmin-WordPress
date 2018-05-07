<?php

/**
 * Class EduAdmin_REST_ProgrammeStart
 */
class EduAdmin_REST_ProgrammeStart extends EduAdminRESTClient {
	protected $api_url = '/v1/ProgrammeStart';

	/**
	 * @param integer   $programme_start_id
	 * @param bool|null $show_external
	 *
	 * @return mixed
	 */
	public function BookingQuestions( $programme_start_id, $show_external = null ) {
		$params = array();
		if ( isset( $show_external ) ) {
			$params['showExternal'] = $show_external ? 'true' : 'false';
		}

		return parent::GET(
			"/$programme_start_id/BookingQuestions",
			$params,
			get_called_class() . '|' . __FUNCTION__
		);
	}
}
