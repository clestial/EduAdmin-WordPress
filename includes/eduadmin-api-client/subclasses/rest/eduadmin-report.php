<?php

/**
 * Class EduAdmin_REST_Report
 */
class EduAdmin_REST_Report extends EduAdminRESTClient {
	protected $api_url = '/v1/Report';

	/**
	 * @param integer $report_id
	 * @param EduAdmin_Data_ReportOptions|stdClass|object $options
	 *
	 * @return mixed
	 */
	public function CreateUrl( $report_id, $options ) {
		return parent::POST( "/$report_id/CreateUrl", $options, get_called_class() . '|' . __FUNCTION__ );
	}
}