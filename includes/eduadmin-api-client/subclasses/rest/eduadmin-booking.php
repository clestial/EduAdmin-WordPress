<?php

/**
 * Class EduAdmin_REST_Booking
 */
class EduAdmin_REST_Booking extends EduAdminRESTClient {
	protected $api_url = '/v1/Booking';

	/**
	 * @param integer $booking_id
	 *
	 * @return mixed
	 */
	public function DeleteBooking( $booking_id ) {
		return parent::DELETE(
			"/$booking_id",
			array(),
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer                    $booking_id
	 * @param EduAdmin_Data_MailAdvanced $mail
	 *
	 * @return mixed
	 */
	public function SendAdvancedEmail( $booking_id, EduAdmin_Data_MailAdvanced $mail ) {
		return parent::POST(
			"/$booking_id/Email/SendAdvanced",
			$mail,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer            $booking_id
	 * @param EduAdmin_Data_Mail $mail
	 *
	 * @return mixed
	 */
	public function SendEmail( $booking_id, EduAdmin_Data_Mail $mail ) {
		return parent::POST(
			"/$booking_id/Email/Send",
			$mail,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer                             $booking_id
	 * @param EduAdmin_Data_UnnamedParticipants[] $unnamed_participants
	 *
	 * @return mixed
	 */
	public function CreateUnnamedParticipants( $booking_id, array $unnamed_participants ) {
		return parent::POST(
			"/$booking_id/UnnamedParticipants",
			$unnamed_participants,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer                                    $booking_id
	 * @param EduAdmin_Data_PatchBooking|stdClass|object $patch_booking
	 *
	 * @return mixed
	 */
	public function PatchBooking( $booking_id, $patch_booking ) {
		return parent::PATCH(
			"/$booking_id",
			$patch_booking,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param EduAdmin_Data_BookingData|stdClass|object $booking_data
	 *
	 * @return mixed
	 */
	public function Create( $booking_data ) {
		return parent::POST(
			'/',
			$booking_data,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param EduAdmin_Data_BookingData|stdClass|object $booking_data
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

	/**
	 * @param integer                                           $booking_id
	 * @param EduAdmin_Data_BookingParticipants|stdClass|object $booking_participants
	 *
	 * @return mixed
	 */
	public function AddParticipantsToBooking( $booking_id, $booking_participants ) {
		return parent::POST(
			"/$booking_id/Participants",
			$booking_participants,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer                                                  $booking_id
	 * @param EduAdmin_Data_ConvertUnnamedParticipants|stdClass|object $unnamed_participants
	 *
	 * @return mixed
	 */
	public function ConvertUnnamedToParticipant( $booking_id, $unnamed_participants ) {
		return parent::POST(
			"/$booking_id/NameUnnamedParticipants",
			$unnamed_participants,
			get_called_class() . '|' . __FUNCTION__
		);
	}
}