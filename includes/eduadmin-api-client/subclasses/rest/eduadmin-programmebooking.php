<?php

/**
 * Class EduAdmin_REST_ProgrammeBooking
 */
class EduAdmin_REST_ProgrammeBooking extends EduAdminRESTClient {
	protected $api_url = "/v1/ProgrammeBooking";

	/**
	 * @param EduAdmin_Data_ProgrammeBooking|stdClass|object $programmeBooking
	 *
	 * @return mixed
	 */
	public function Book( $programmeBooking ) {
		return parent::POST(
			"",
			$programmeBooking,
			get_called_class() . "|" . __FUNCTION__
		);
	}

	/**
	 * @param integer $programmeBookingId
	 * @param EduAdmin_Data_Mail|stdClass|object $pbEmail
	 *
	 * @return mixed
	 */
	public function SendEmail( $programmeBookingId, $pbEmail ) {
		return parent::POST(
			"/$programmeBookingId/Email/Send",
			$pbEmail,
			get_called_class() . "|" . __FUNCTION__
		);
	}

	/**
	 * @param integer $programmeBookingId
	 * @param EduAdmin_Data_MailAdvanced|stdClass|object $pbEmail
	 *
	 * @return mixed
	 */
	public function SendEmailAdvanced( $programmeBookingId, $pbEmail ) {
		return parent::POST(
			"/$programmeBookingId/Email/SendAdvanced",
			$pbEmail,
			get_called_class() . "|" . __FUNCTION__
		);
	}

	/**
	 * @param integer $programmeBookingId
	 * @param EduAdmin_Data_ProgrammeBooking_Patch|stdClass|object $patch
	 *
	 * @return mixed
	 */
	public function PatchBooking( $programmeBookingId, $patch ) {
		return parent::PATCH(
			"/$programmeBookingId",
			$patch,
			get_called_class() . "|" . __FUNCTION__
		);
	}
}
