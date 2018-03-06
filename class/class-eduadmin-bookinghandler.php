<?php

// phpcs:disable WordPress.NamingConventions
class EduAdmin_BookingHandler {
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'process_booking' ) );
	}

	public function process_booking() {
		if ( ! empty( $_POST['edu-valid-form'] ) && wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) && ! empty( $_POST['act'] ) && 'bookCourse' === sanitize_text_field( $_POST['act'] ) ) {
			$single_person_booking = get_option( 'eduadmin-singlePersonBooking', false );

			$booking_info = $single_person_booking ? $this->book_single_participant() : $this->book_multiple_participants();

			$event_booking = EDUAPI()->OData->Bookings->GetItem( $booking_info['BookingId'] );
			$_customer     = EDUAPI()->OData->Customers->GetItem( $booking_info['CustomId'] );
			$_contact      = EDUAPI()->OData->Persons->GetItem( $booking_info['ContactPersonId'] );

			$ebi = new EduAdmin_BookingInfo( $event_booking, $_customer, $_contact );

			$GLOBALS['edubookinginfo'] = $ebi;

			do_action( 'eduadmin-checkpaymentplugins', $ebi );

			if ( ! $ebi->NoRedirect ) {
				wp_redirect( get_page_link( get_option( 'eduadmin-thankYouPage', '/' ) ) . '?edu-thankyou=' . $booking_info['BookingId'] );
				exit();
			}
		}
	}

	private function get_basic_booking_data( &$booking_data, $event_id ) {
		if ( ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			return null;
		}

		$selected_match = get_option( 'eduadmin-customerMatching', 'name-zip-match' );

		if ( 'no-match' === $selected_match ) {
			$booking_options                               = new EduAdmin_Data_Options();
			$booking_options->SkipDuplicateMatchOnCustomer = true;
			$booking_options->SkipDuplicateMatchOnPersons  = true;
			$booking_data->Options                         = $booking_options;
		}

		$booking_data->EventId   = $event_id;
		$booking_data->Reference = sanitize_text_field( $_POST['invoiceReference'] );

		if ( 'selectWholeEvent' === get_option( 'eduadmin-selectPricename', 'firstPublic' ) && ! empty( $_POST['edu-pricename'] ) && is_numeric( $_POST['edu-pricename'] ) ) {
			$booking_data->PriceNameId = intval( $_POST['edu-pricename'] );
		}

		if ( ! empty( $_POST['edu-limitedDiscountID'] ) ) {
			$booking_data->VoucherId = intval( $_POST['edu-limitedDiscountID'] );
		}

		if ( ! empty( $_POST['edu-discountCode'] ) ) {
			$booking_data->CouponCode = sanitize_text_field( $_POST['edu-discountCode'] );
		}

		$booking_data->Answers = $this->get_booking_questions();
	}

	private function get_single_participant_booking() {
		if ( ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			return null;
		}

		$event_id     = intval( $_REQUEST['eid'] );
		$booking_data = new EduAdmin_Data_BookingData();

		$this->get_basic_booking_data( $booking_data, $event_id );

		$customer = new stdClass();
		$contact  = new stdClass();

		$contact->AddAsParticipant = true;

		if ( isset( EDU()->session['eduadmin-loginUser'] ) ) {
			$user                 = EDU()->session['eduadmin-loginUser'];
			$contact->PersonId    = $user->Contact->PersonId;
			$customer->CustomerId = $user->Customer->CustomerId;
		}

		$first                     = sanitize_text_field( $_POST['contactFirstName'] );
		$last                      = sanitize_text_field( $_POST['contactLastName'] );
		$customer->CustomerName    = $first . ' ' . $last;
		$customer->CustomerGroupId = intval( get_option( 'eduadmin-customerGroupId', null ) );
		if ( ! empty( $_POST['contactCivRegNr'] ) ) {
			$customer->OrganisationNumber = sanitize_text_field( $_POST['contactCivRegNr'] );
		}
		$customer->Address  = sanitize_text_field( $_POST['customerAddress1'] );
		$customer->Address2 = sanitize_text_field( $_POST['customerAddress2'] );
		$customer->Zip      = sanitize_text_field( $_POST['customerPostalCode'] );
		$customer->City     = sanitize_text_field( $_POST['customerPostalCity'] );
		$customer->Phone    = sanitize_text_field( $_POST['contactPhone'] );
		$customer->Mobile   = sanitize_text_field( $_POST['contactMobile'] );
		$customer->Email    = sanitize_email( $_POST['contactEmail'] );

		$customerInvoiceEmailAddress = sanitize_email( $_POST['invoiceEmail'] );

		$billing_info = new stdClass();

		if ( empty( $_POST['alsoInvoiceCustomer'] ) ) {
			$billing_info->CustomerName = $first . ' ' . $last;
			$billing_info->Address      = sanitize_text_field( $_POST['customerAddress1'] );
			$billing_info->Address2     = sanitize_text_field( $_POST['customerAddress2'] );
			$billing_info->Zip          = sanitize_text_field( $_POST['customerPostalCode'] );
			$billing_info->City         = sanitize_text_field( $_POST['customerPostalCity'] );
		} else {
			$billing_info->CustomerName = sanitize_text_field( $_POST['invoiceName'] );
			$billing_info->Address      = sanitize_text_field( $_POST['invoiceAddress1'] );
			$billing_info->Address2     = sanitize_text_field( $_POST['invoiceAddress2'] );
			$billing_info->Zip          = sanitize_text_field( $_POST['invoicePostalCode'] );
			$billing_info->City         = sanitize_text_field( $_POST['invoicePostalCity'] );
		}

		if ( ! empty( $customerInvoiceEmailAddress ) ) {
			$billing_info->Email = $customerInvoiceEmailAddress;
		}

		$customer->BillingInfo = $billing_info;

		$customer->CustomFields = $this->get_customer_custom_fields();

		$booking_data->Customer      = $customer;
		$booking_data->ContactPerson = $this->get_contact_person( $contact );

		$send_info = new EduAdmin_Data_Mail();

		$send_info->SendToParticipants    = true;
		$send_info->SendToCustomer        = true;
		$send_info->SendToCustomerContact = true;

		$booking_data->SendConfirmationEmail = $send_info;

		return $booking_data;
	}

	private function get_contact_person( &$contact ) {
		if ( ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			return null;
		}
		if ( ! empty( $_POST['contactFirstName'] ) ) {
			$contact->FirstName = sanitize_text_field( $_POST['contactFirstName'] );
			$contact->LastName  = sanitize_text_field( $_POST['contactLastName'] );
			$contact->Phone     = sanitize_text_field( $_POST['contactPhone'] );
			$contact->Mobile    = sanitize_text_field( $_POST['contactMobile'] );
			$contact->Email     = sanitize_email( $_POST['contactEmail'] );

			if ( ! empty( $_POST['contactCivReg'] ) ) {
				$contact->CivicRegistrationNumber = sanitize_text_field( $_POST['contactCivReg'] );
			}
			if ( ! empty( $_POST['contactPass'] ) ) {
				$contact->Password = sanitize_text_field( $_POST['contactPass'] );
			}

			$contact->CanLogin     = true;
			$contact->Answers      = $this->get_contact_questions();
			$contact->CustomFields = $this->get_contact_custom_fields();
			$contact->Sessions     = $this->get_contact_sessions();

			if ( ! empty( $_POST['contactIsAlsoParticipant'] ) ) {
				$contact->AddAsParticipant = true;
			}
		}

		return $contact;
	}

	public function check_single_participant_price() {
		if ( ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			return null;
		}

		$booking_data = $this->get_single_participant_booking();

		$res = EDUAPI()->REST->Booking->CheckPrice( $booking_data );
		EDU()->write_debug( $res );
	}

	public function book_single_participant() {
		if ( ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			return null;
		}

		$booking_data = $this->get_single_participant_booking();

		$booking = EDUAPI()->REST->Booking->Create( $booking_data );

		EDU()->session['eduadmin-printJS'] = true;

		$user = EDU()->login_handler->get_login_user( $booking['ContactPersonId'], $booking['CustomerId'] );

		EDU()->session['eduadmin-loginUser'] = $user;

		$booking_info = array(
			'BookingId'       => $booking['BookingId'],
			'EventId'         => $booking['EventId'],
			'CustomerId'      => $booking['CustomerId'],
			'ContactPersonId' => $booking['ContactPersonId'],
		);

		return $booking_info;
	}

	private function get_multiple_participant_booking() {
		if ( ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			return null;
		}

		$event_id     = intval( $_REQUEST['eid'] );
		$booking_data = new EduAdmin_Data_BookingData();

		$this->get_basic_booking_data( $booking_data, $event_id );

		$customer = new stdClass();
		$contact  = new stdClass();

		if ( isset( EDU()->session['eduadmin-loginUser'] ) ) {
			$user                 = EDU()->session['eduadmin-loginUser'];
			$contact->PersonId    = $user->Contact->PersonId;
			$customer->CustomerId = $user->Customer->CustomerId;
		}

		$customer->CustomerName       = sanitize_text_field( $_POST['customerName'] );
		$customer->CustomerGroupId    = get_option( 'eduadmin-customerGroupId', null );
		$customer->OrganisationNumber = sanitize_text_field( $_POST['customerVatNo'] );
		$customer->Address            = sanitize_text_field( $_POST['customerAddress1'] );
		$customer->Address2           = sanitize_text_field( $_POST['customerAddress2'] );
		$customer->Zip                = sanitize_text_field( $_POST['customerPostalCode'] );
		$customer->City               = sanitize_text_field( $_POST['customerPostalCity'] );
		$customer->Email              = sanitize_email( $_POST['customerEmail'] );

		if ( ! empty( $_POST['purchaseOrderNumber'] ) ) {
			$booking_data->PurchaseOrderNumber = sanitize_text_field( $_POST['purchaseOrderNumber'] );
		}

		$customerInvoiceEmailAddress = sanitize_email( $_POST['invoiceEmail'] );

		$billing_info = new stdClass();

		if ( ! isset( $_POST['alsoInvoiceCustomer'] ) ) {
			$billing_info->CustomerName = sanitize_text_field( $_POST['customerName'] );
			$billing_info->Address      = sanitize_text_field( $_POST['customerAddress1'] );
			$billing_info->Address2     = sanitize_text_field( $_POST['customerAddress2'] );
			$billing_info->Zip          = sanitize_text_field( $_POST['customerPostalCode'] );
			$billing_info->City         = sanitize_text_field( $_POST['customerPostalCity'] );
		} else {
			$billing_info->CustomerName = sanitize_text_field( $_POST['invoiceName'] );
			$billing_info->Address      = sanitize_text_field( $_POST['invoiceAddress1'] );
			$billing_info->Address2     = sanitize_text_field( $_POST['invoiceAddress2'] );
			$billing_info->Zip          = sanitize_text_field( $_POST['invoicePostalCode'] );
			$billing_info->City         = sanitize_text_field( $_POST['invoicePostalCity'] );
		}

		$billing_info->SellerReference = sanitize_text_field( $_POST['invoiceReference'] );

		$booking_data->Reference = $billing_info->SellerReference;

		if ( ! empty( $customerInvoiceEmailAddress ) ) {
			$billing_info->Email = $customerInvoiceEmailAddress;
		}

		$customer->BillingInfo  = $billing_info;
		$customer->CustomFields = $this->get_customer_custom_fields();

		$booking_data->Customer      = $customer;
		$booking_data->ContactPerson = $this->get_contact_person( $contact );

		$participants = $this->get_participant_data();

		$booking_data->Participants = $participants;

		return $booking_data;
	}

	public function book_multiple_participants() {
		if ( ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			return null;
		}

		$booking_data = $this->get_multiple_participant_booking();

		$booking = EDUAPI()->REST->Booking->Create( $booking_data );

		EDU()->session['eduadmin-printJS'] = true;

		$user = EDU()->login_handler->get_login_user( $booking['ContactPersonId'], $booking['CustomerId'] );

		EDU()->session['eduadmin-loginUser'] = $user;

		$booking_info = array(
			'BookingId'       => $booking['BookingId'],
			'EventId'         => $booking['EventId'],
			'CustomerId'      => $booking['CustomerId'],
			'ContactPersonId' => $booking['ContactPersonId'],
		);

		return $booking_info;
	}

	private function get_customer_custom_fields() {
		if ( wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			$customer_custom_field_answers = array_filter( array_keys( $_POST ), function( $key ) {
				if ( is_string( $key ) ) {
					return edu_starts_with( $key, 'edu-attr_' ) && edu_ends_with( $key, '-customer' );
				}

				return false;
			} );

			$customer_answers = array();

			foreach ( $customer_custom_field_answers as $key ) {
				$custom_field = explode( '_', str_replace( array( 'edu-attr_', '-customer' ), '', $key ) );

				$custom_field_id   = $custom_field[0];
				$custom_field_type = $custom_field[1];

				$answer = $this->get_custom_field_data( $key, $custom_field_id, $custom_field_type );

				if ( null !== $answer->CustomFieldValue ) {
					$customer_answers[] = $answer;
				}
			}

			return $customer_answers;
		}
	}

	private function get_contact_custom_fields() {
		if ( wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			$customer_custom_field_answers = array_filter( array_keys( $_POST ), function( $key ) {
				if ( is_string( $key ) ) {
					return edu_starts_with( $key, 'edu-attr_' ) && edu_ends_with( $key, '-contact' );
				}

				return false;
			} );

			$customer_answers = array();

			foreach ( $customer_custom_field_answers as $key ) {
				$custom_field = explode( '_', str_replace( array( 'edu-attr_', '-contact' ), '', $key ) );

				$custom_field_id   = $custom_field[0];
				$custom_field_type = $custom_field[1];

				$answer = $this->get_custom_field_data( $key, $custom_field_id, $custom_field_type );

				if ( null !== $answer->CustomFieldValue ) {
					$customer_answers[] = $answer;
				}
			}

			return $customer_answers;
		}
	}

	private function get_custom_field_data( $key, $custom_field_id, $custom_field_type ) {
		if ( wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			$answer = new stdClass();
			switch ( $custom_field_type ) {
				case 'dropdown':
				case 'check':
				case 'radio':
					$answer->CustomFieldId = intval( $custom_field_id );
					if ( 'check' === $custom_field_type || 'radio' === $custom_field_type ) {
						$answer->CustomFieldValue = true;
					} else {
						$answer->CustomFieldValue = intval( $custom_field_id );
					}
					break;
				default:
					$answer->CustomFieldId = intval( $custom_field_id );
					if ( 'note' === $custom_field_type || 'text' === $custom_field_type ) {
						$answer->CustomFieldValue = $_POST[ $key ];
					} elseif ( 'number' === $custom_field_type ) {
						$answer->CustomFieldValue = intval( $_POST[ $key ] );
					} elseif ( 'date' === $custom_field_type && ! empty( $_POST[ $key ] ) ) {
						$answer->CustomFieldValue = date( 'c', strtotime( $_POST[ $key ] ) );
					} else {
						$answer->CustomFieldValue = null;
					}

					break;
			}

			return $answer;
		}
	}

	private function get_contact_sessions() {
		if ( wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			$session_keys = array_filter( array_keys( $_POST ), function( $key ) {
				if ( is_string( $key ) ) {
					return edu_starts_with( $key, 'contactSubEvent_' );
				}

				return false;
			} );

			$sessions = array();

			foreach ( $session_keys as $key ) {
				$session_id = str_replace( array( 'contactSubEvent_' ), '', $key );
				if ( is_numeric( $session_id ) ) {
					$session            = new stdClass();
					$session->SessionId = intval( $session_id );
					$sessions[]         = $session;
				}
			}

			return $sessions;
		}
	}

	private function get_contact_questions() {
		if ( wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			$contact_question_answers = array_filter( array_keys( $_POST ), function( $key ) {
				if ( is_string( $key ) ) {
					return edu_starts_with( $key, 'question_' ) && edu_ends_with( $key, '-contact' );
				}

				return false;
			} );

			$contact_qanswers = array();

			foreach ( $contact_question_answers as $key ) {
				$question_data = explode( '_', str_replace( array( 'question_', '-contact' ), '', $key ) );

				$question_answer_id = $question_data[0];
				$question_type      = $question_data[1];

				$answer = $this->get_answer_data( $key, $question_answer_id, $question_type );

				if ( null !== $answer->AnswerValue ) {
					$contact_qanswers[] = $answer;
				}
			}

			return $contact_qanswers;
		}
	}

	private function get_answer_data( $key, $question_answer_id, $question_type ) {
		if ( wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			$answer = new stdClass();
			switch ( $question_type ) {
				case 'dropdown':
				case 'check':
				case 'radio':
					$question_answer_id = $_POST[ $key ];
					$answer->AnswerId   = intval( $question_answer_id );
					if ( 'check' === $question_type || 'radio' === $question_type ) {
						$answer->AnswerValue = true;
					} else {
						$answer->AnswerValue = intval( $question_answer_id );
					}
					break;
				default:
					$answer->AnswerId = intval( $question_answer_id );
					if ( 'note' === $question_type || 'text' === $question_type ) {
						$answer->AnswerValue = $_POST[ $key ];
					} elseif ( 'number' === $question_type ) {
						$answer->AnswerValue = intval( $_POST[ $key ] );
					} elseif ( 'date' === $question_type && ! empty( $_POST[ $key ] ) ) {
						$answer->AnswerValue = date( 'c', strtotime( $_POST[ $key ] ) );
					} else {
						$answer->AnswerValue = null;
					}

					break;
			}

			return $answer;
		}
	}

	private function get_booking_questions() {
		if ( wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			$booking_question_answers = array_filter( array_keys( $_POST ), function( $key ) {
				if ( is_string( $key ) ) {
					return edu_starts_with( $key, 'question_' ) && edu_ends_with( $key, '-booking' );
				}

				return false;
			} );

			$booking_qanswers = array();
			foreach ( $booking_question_answers as $key ) {
				$question_data = explode( '_', str_replace( array( 'question_', '-booking' ), '', $key ) );

				$question_answer_id = $question_data[0];
				$question_type      = $question_data[1];

				$answer = $this->get_answer_data( $key, $question_answer_id, $question_type );
				if ( null !== $answer->AnswerValue ) {
					$booking_qanswers[] = $answer;
				}
			}

			return $booking_qanswers;
		}
	}

	private function get_participant_data() {
		if ( ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			return null;
		}

		$participants = array();

		foreach ( $_POST['participantFirstName'] as $key => $value ) {
			if ( '0' === $key ) {
				continue;
			}

			if ( ! empty( $_POST['participantFirstName'][ $key ] ) ) {
				$person            = new stdClass();
				$person->FirstName = sanitize_text_field( $_POST['participantFirstName'][ $key ] );
				$person->LastName  = sanitize_text_field( $_POST['participantLastName'][ $key ] );
				$person->Email     = sanitize_email( $_POST['participantEmail'][ $key ] );
				$person->Phone     = sanitize_text_field( $_POST['participantPhone'][ $key ] );
				$person->Mobile    = sanitize_text_field( $_POST['participantMobile'][ $key ] );

				if ( isset( $_POST['participantCivReg'][ $key ] ) ) {
					$person->CivicRegistrationNumber = trim( sanitize_text_field( $_POST['participantCivReg'][ $key ] ) );
				}

				if ( isset( $_POST['participantPriceName'][ $key ] ) ) {
					$person->PriceNameId = intval( $_POST['participantPriceName'][ $key ] );
				}

				$person->CustomFields = $this->get_participant_custom_fields( $key );

				$person->Answers = $this->get_participant_answers( $key );

				$person->Sessions = $this->get_participant_sessions( $key );

				$participants[] = $person;
			}
		}

		return $participants;
	}

	private function get_participant_custom_fields( $index ) {
		if ( ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			return null;
		}

		$custom_field_keys = array_filter( array_keys( $_POST ), function( $key ) use ( $index ) {
			if ( is_string( $key ) ) {
				return edu_starts_with( $key, 'edu-attr_' ) && edu_ends_with( $key, '-participant_' . $index );
			}

			return false;
		} );

		$custom_fields = array();

		foreach ( $custom_field_keys as $key ) {
			$cf_data = explode( '_', str_replace( array( 'edu-attr_', '-participant' ), '', $key ) );

			$field_id          = intval( $cf_data[0] );
			$custom_field_type = $cf_data[1];
			$participant_index = intval( $cf_data[2] );

			if ( $index === $participant_index && ! empty( $_POST[ $key ] ) && is_numeric( $field_id ) ) {
				$answer = $this->get_custom_field_data( $key, $field_id, $custom_field_type );

				if ( null !== $answer->CustomFieldValue ) {
					$custom_fields[] = $answer;
				}
			}
		}

		return $custom_fields;
	}

	private function get_participant_answers( $index ) {
		if ( ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			return null;
		}

		$answers = array();

		$question_answers = array_filter( array_keys( $_POST ), function( $key ) use ( $index ) {
			if ( is_string( $key ) ) {
				return edu_starts_with( $key, 'question_' ) && edu_ends_with( $key, '-participant_' . $index );
			}

			return false;
		} );

		foreach ( $question_answers as $key ) {
			$question_data = explode( '_', str_replace( array( 'question_', '-participant' ), '', $key ) );

			$question_answer_id = intval( $question_data[0] );
			$question_type      = $question_data[1];

			$question_participant_index = intval( $question_data[2] );

			if ( $index === $question_participant_index && ! empty( $_POST[ $key ] ) && is_numeric( $question_answer_id ) ) {
				$answer = $this->get_answer_data( $key, $question_answer_id, $question_type );

				if ( null !== $answer->AnswerValue ) {
					$answers[] = $answer;
				}
			}
		}

		return $answers;
	}

	private function get_participant_sessions( $index ) {
		if ( ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			return null;
		}

		$session_keys = array_filter( array_keys( $_POST ), function( $key ) use ( $index ) {
			if ( is_string( $key ) ) {
				return edu_starts_with( $key, 'participantSubEvent_' ) && edu_ends_with( $key, '_' . $index );
			}

			return false;
		} );

		$sessions = array();

		foreach ( $session_keys as $key ) {
			$session = explode( '_', str_replace( array( 'participantSubEvent_' ), '', $key ) );

			$session_id        = intval( $session[0] );
			$participant_index = intval( $session[1] );

			if ( $index === $participant_index ) {
				if ( ! empty( $_POST[ 'participantSubEvent_' . $session_id . '_' . $participant_index ] ) ) {
					if ( is_numeric( $session_id ) ) {
						$session            = new stdClass();
						$session->SessionId = intval( $session_id );
						$sessions[]         = $session;
					}
				}
			}
		}

		return $sessions;
	}
}
