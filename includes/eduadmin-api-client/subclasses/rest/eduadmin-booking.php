<?php

	/**
	 * Class EduAdmin_REST_Booking
	 */
	class EduAdmin_REST_Booking extends EduAdminRESTClient {
		protected $api_url = "/v1/Booking";

		/**
		 * @param integer $bookingId
		 *
		 * @return mixed
		 */
		public function DeleteBooking( $bookingId ) {
			return parent::DELETE(
				"/$bookingId",
				array(),
				get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param integer                    $bookingId
		 * @param EduAdmin_Data_MailAdvanced $mail
		 *
		 * @return mixed
		 */
		public function SendAdvancedEmail( $bookingId, EduAdmin_Data_MailAdvanced $mail ) {
			return parent::POST(
				"/$bookingId/Email/SendAdvanced",
				$mail,
				get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param integer            $bookingId
		 * @param EduAdmin_Data_Mail $mail
		 *
		 * @return mixed
		 */
		public function SendEmail( $bookingId, EduAdmin_Data_Mail $mail ) {
			return parent::POST(
				"/$bookingId/Email/Send",
				$mail,
				get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param integer                             $bookingId
		 * @param EduAdmin_Data_UnnamedParticipants[] $unnamed_participants
		 *
		 * @return mixed
		 */
		public function CreateUnnamedParticipants( $bookingId, array $unnamed_participants ) {
			return parent::POST(
				"/$bookingId/UnnamedParticipants",
				$unnamed_participants,
				get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param integer                    $bookingId
		 * @param EduAdmin_Data_PatchBooking $patch_booking
		 *
		 * @return mixed
		 */
		public function PatchBooking( $bookingId, EduAdmin_Data_PatchBooking $patch_booking ) {
			return parent::PATCH(
				"/$bookingId",
				$patch_booking,
				get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param EduAdmin_Data_BookingData $booking_data
		 *
		 * @return mixed
		 */
		public function Create( EduAdmin_Data_BookingData $booking_data ) {
			return parent::POST(
				"",
				$booking_data,
				get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param EduAdmin_Data_BookingData $booking_data
		 *
		 * @return mixed
		 */
		public function CheckPrice( EduAdmin_Data_BookingData $booking_data ) {
			return parent::POST(
				"/CheckPrice",
				$booking_data,
				get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param integer                           $bookingId
		 * @param EduAdmin_Data_BookingParticipants $booking_participants
		 *
		 * @return mixed
		 */
		public function AddParticipantsToBooking( $bookingId, EduAdmin_Data_BookingParticipants $booking_participants ) {
			return parent::POST(
				"/$bookingId/Participants",
				$booking_participants,
				get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param integer                                  $bookingId
		 * @param EduAdmin_Data_ConvertUnnamedParticipants $unnamed_participants
		 *
		 * @return mixed
		 */
		public function ConvertUnnamedToParticipant( $bookingId, EduAdmin_Data_ConvertUnnamedParticipants $unnamed_participants ) {
			return parent::POST(
				"/$bookingId/NameUnnamedParticipants",
				$unnamed_participants,
				get_called_class() . "|" . __FUNCTION__
			);
		}
	}