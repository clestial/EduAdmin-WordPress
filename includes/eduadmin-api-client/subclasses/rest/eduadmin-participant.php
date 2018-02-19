<?php

/**
 * Class EduAdmin_REST_Participant
 */
class EduAdmin_REST_Participant extends EduAdminRESTClient {
	protected $api_url = '/v1/Participant';

	/**
	 * @param integer $participant_id
	 *
	 * @return mixed
	 */
	public function CancelParticipant( $participant_id ) {
		return parent::POST(
			"/$participant_id/Cancel",
			array(),
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer $participant_id
	 * @param EduAdmin_Data_Sessions[] $sessions
	 *
	 * @return mixed
	 */
	public function AddToSessions( $participant_id, array $sessions ) {
		return parent::POST(
			"/$participant_id/AddToSessions",
			$sessions,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer $participant_id
	 * @param integer[] $session_ids
	 *
	 * @return mixed
	 */
	public function RemoveSessions( $participant_id, $session_ids ) {
		return parent::POST( "/$participant_id/RemoveFromSessions",
			$session_ids,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param EduAdmin_Data_ArrivalStatus[] $arrival_status
	 *
	 * @return mixed
	 */
	public function MarkAsArrived( array $arrival_status ) {
		return parent::POST( '/Arrived',
			$arrival_status,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param EduAdmin_Data_ArrivalStatus[] $arrival_status
	 *
	 * @return mixed
	 */
	public function MarkAsNotArrived( array $arrival_status ) {
		return parent::POST( '/NotArrived',
			$arrival_status,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param EduAdmin_Data_GradeData|stdClass|object $grade_data
	 *
	 * @return mixed
	 */
	public function Grade( $grade_data ) {
		return parent::POST( '/Grade',
			$grade_data,
			get_called_class() . '|' . __FUNCTION__
		);
	}

	/**
	 * @param integer $participant_id
	 * @param EduAdmin_Data_ParticipantData|stdClass|object $participant_data
	 *
	 * @return mixed
	 */
	public function Update( $participant_id, $participant_data ) {
		return parent::PATCH( "/$participant_id",
			$participant_data,
			get_called_class() . '|' . __FUNCTION__
		);
	}
}