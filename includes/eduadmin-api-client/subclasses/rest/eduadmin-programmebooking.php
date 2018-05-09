<?php

/**
 * Class EduAdmin_REST_ProgrammeBooking
 */
class EduAdmin_REST_ProgrammeBooking extends EduAdminRESTClient {
	protected $api_url = '/v1/ProgrammeBooking';

	/**
	 * @param EduAdmin_Data_ProgrammeBooking|stdClass|object $programme_booking
	 *
	 * @return mixed
	 */
	public function Book( $programme_booking ) {
		return parent::POST(
			'/',
			$programme_booking,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer $programme_booking_id
	 * @param EduAdmin_Data_Mail|stdClass|object $pb_email
	 *
	 * @return mixed
	 */
	public function SendEmail( $programme_booking_id, $pb_email ) {
		return parent::POST(
			"/$programme_booking_id/Email/Send",
			$pb_email,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer $programme_booking_id
	 * @param EduAdmin_Data_MailAdvanced|stdClass|object $pb_email
	 *
	 * @return mixed
	 */
	public function SendEmailAdvanced( $programme_booking_id, $pb_email ) {
		return parent::POST(
			"/$programme_booking_id/Email/SendAdvanced",
			$pb_email,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer $programme_booking_id
	 * @param EduAdmin_Data_ProgrammeBooking_Patch|stdClass|object $patch
	 *
	 * @return mixed
	 */
	public function PatchBooking( $programme_booking_id, $patch ) {
		return parent::PATCH(
			"/$programme_booking_id",
			$patch,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param EduAdmin_Data_ProgrammeBooking|stdClass|object $booking_data
	 *
	 * @return mixed
	 */
	public function CheckPrice( $booking_data ) {
		return parent::POST(
			'/CheckPrice',
			$booking_data,
			get_called_class() . '|' . __FUNCTION__
		);
	}
}
