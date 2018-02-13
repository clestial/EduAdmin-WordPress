<?php

/**
 * Class EduAdmin_REST_Participant
 */
class EduAdmin_REST_Participant extends EduAdminRESTClient {
	protected $api_url = "/v1/Participant";

	/**
	 * @param integer $participantId
	 *
	 * @return mixed
	 */
	public function CancelParticipant( $participantId ) {
		return parent::POST(
			"/$participantId/Cancel",
			array(),
			get_called_class() . "|" . __FUNCTION__
		);
	}

	/**
	 * @param integer $participantId
	 * @param EduAdmin_Data_Sessions[] $sessions
	 *
	 * @return mixed
	 */
	public function AddToSessions( $participantId, array $sessions ) {
		return parent::POST(
			"/$participantId/AddToSessions",
			$sessions,
			get_called_class() . "|" . __FUNCTION__
		);
	}

	/**
	 * @param integer $participantId
	 * @param integer[] $sessionIds
	 *
	 * @return mixed
	 */
	public function RemoveSessions( $participantId, $sessionIds ) {
		return parent::POST( "/$participantId/RemoveFromSessions",
			$sessionIds,
			get_called_class() . "|" . __FUNCTION__
		);
	}

	/**
	 * @param EduAdmin_Data_ArrivalStatus[] $arrival_status
	 *
	 * @return mixed
	 */
	public function MarkAsArrived( array $arrival_status ) {
		return parent::POST( "/Arrived",
			$arrival_status,
			get_called_class() . "|" . __FUNCTION__
		);
	}

	/**
	 * @param EduAdmin_Data_ArrivalStatus[] $arrival_status
	 *
	 * @return mixed
	 */
	public function MarkAsNotArrived( array $arrival_status ) {
		return parent::POST( "/NotArrived",
			$arrival_status,
			get_called_class() . "|" . __FUNCTION__
		);
	}

	/**
	 * @param EduAdmin_Data_GradeData|stdClass|object $grade_data
	 *
	 * @return mixed
	 */
	public function Grade( $grade_data ) {
		return parent::POST( "/Grade",
			$grade_data,
			get_called_class() . "|" . __FUNCTION__
		);
	}

	/**
	 * @param integer $participantId
	 * @param EduAdmin_Data_ParticipantData|stdClass|object $participant_data
	 *
	 * @return mixed
	 */
	public function Update( $participantId, $participant_data ) {
		return parent::PATCH( "/$participantId",
			$participant_data,
			get_called_class() . "|" . __FUNCTION__
		);
	}
}