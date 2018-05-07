<?php

// phpcs:disable WordPress.NamingConventions
class EduAdmin_BookingHandler {
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'process_booking' ) );
		add_action( 'wp_loaded', array( $this, 'process_programme_booking' ) );
		add_action( 'wp_loaded', array( $this, 'check_price' ) );
		add_action( 'wp_loaded', array( $this, 'check_programme_price' ) );
	}

	public function check_price() {
		if ( ! empty( $_POST['edu-valid-form'] ) && wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) && ! empty( $_POST['act'] ) && 'checkPrice' === sanitize_text_field( $_POST['act'] ) ) { // Var input okay.
			$single_person_booking = get_option( 'eduadmin-singlePersonBooking', false );

			$price_info = $single_person_booking ? $this->check_single_participant() : $this->check_multiple_participants();
			echo wp_json_encode( $price_info );
			exit( 0 );
		}
	}

	public function check_programme_price() {
		if ( ! empty( $_POST['edu-valid-form'] ) && wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) && ! empty( $_POST['act'] ) && 'checkProgrammePrice' === sanitize_text_field( $_POST['act'] ) ) { // Var input okay.
			$price_info = $this->check_programme();
			echo wp_json_encode( $price_info );
			exit( 0 );
		}
	}

	public function process_booking() {
		if ( ! empty( $_POST['edu-valid-form'] ) && wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) && ! empty( $_POST['act'] ) && 'bookCourse' === sanitize_text_field( $_POST['act'] ) ) { // Var input okay.
			$single_person_booking = get_option( 'eduadmin-singlePersonBooking', false );

			$booking_info = $single_person_booking ? $this->book_single_participant() : $this->book_multiple_participants();

			if ( ! empty( $booking_info['Errors'] ) ) {
				add_filter( 'edu-booking-error', function( $errors ) use ( $booking_info ) {
					foreach ( $booking_info['Errors'] as $error ) {
						switch ( $error['ErrorCode'] ) {
							case -1: // Exception
								$errors[] = __( 'An error has occured, please try again later!', 'eduadmin-booking' );
								break;
							case 40:
								$errors[] = __( 'Not enough spots left.', 'eduadmin-booking' );
								break;
							case 45:
								$errors[] = __( 'Person already booked on event.', 'eduadmin-booking' );
								break;
							case 100:
								$errors[] = __( 'The voucher was not found.', 'eduadmin-booking' );
								break;
							case 101:
								$errors[] = __( 'The voucher is not valid during the event period.', 'eduadmin-booking' );
								break;
							case 102:
								$errors[] = __( 'The voucher is too small for the number of participants.', 'eduadmin-booking' );
								break;
							case 103:
								$errors[] = __( 'The voucher belongs to a different customer.', 'eduadmin-booking' );
								break;
							case 104:
								$errors[] = __( 'The voucher belongs to a different customer contact.', 'eduadmin-booking' );
								break;
							case 105:
								$errors[] = __( 'The voucher is not valid for this event.', 'eduadmin-booking' );
								break;
							case 200:
								$errors[] = __( 'Person added on session where dates are overlapping.', 'eduadmin-booking' );
								break;
							default:
								$errors[] = $error['ErrorText'];
								break;
						}
					}

					return $errors;
				}, 10, 1 );

				return;
			}

			$event_booking = EDUAPI()->OData->Bookings->GetItem(
				$booking_info['BookingId'],
				null,
				'OrderRows',
				false
			);
			$_customer     = EDUAPI()->OData->Customers->GetItem(
				$booking_info['CustomerId'],
				null,
				null,
				false
			);
			$_contact      = EDUAPI()->OData->Persons->GetItem(
				$booking_info['ContactPersonId'],
				null,
				null,
				false
			);

			$ebi = new EduAdmin_BookingInfo( $event_booking, $_customer, $_contact );

			$GLOBALS['edubookinginfo'] = $ebi;

			do_action( 'eduadmin-checkpaymentplugins', $ebi );

			if ( ! $ebi->NoRedirect ) {
				wp_redirect( get_page_link( get_option( 'eduadmin-thankYouPage', '/' ) ) . '?edu-thankyou=' . $booking_info['BookingId'] );
				exit( 0 );
			}
		}
	}

	public function process_programme_booking() {
		if ( ! empty( $_POST['edu-valid-form'] ) && wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) && ! empty( $_POST['act'] ) && 'bookProgramme' === sanitize_text_field( $_POST['act'] ) ) { // Var input okay.
			$booking_info = $this->get_programme_booking();

			if ( ! empty( $booking_info['Errors'] ) ) {
				add_filter( 'edu-booking-error', function( $errors ) use ( $booking_info ) {
					foreach ( $booking_info['Errors'] as $error ) {
						switch ( $error['ErrorCode'] ) {
							case -1: // Exception
								$errors[] = __( 'An error has occured, please try again later!', 'eduadmin-booking' );
								break;
							case 40:
								$errors[] = __( 'Not enough spots left.', 'eduadmin-booking' );
								break;
							case 45:
								$errors[] = __( 'Person already booked on event.', 'eduadmin-booking' );
								break;
							case 100:
								$errors[] = __( 'The voucher was not found.', 'eduadmin-booking' );
								break;
							case 101:
								$errors[] = __( 'The voucher is not valid during the event period.', 'eduadmin-booking' );
								break;
							case 102:
								$errors[] = __( 'The voucher is too small for the number of participants.', 'eduadmin-booking' );
								break;
							case 103:
								$errors[] = __( 'The voucher belongs to a different customer.', 'eduadmin-booking' );
								break;
							case 104:
								$errors[] = __( 'The voucher belongs to a different customer contact.', 'eduadmin-booking' );
								break;
							case 105:
								$errors[] = __( 'The voucher is not valid for this event.', 'eduadmin-booking' );
								break;
							case 200:
								$errors[] = __( 'Person added on session where dates are overlapping.', 'eduadmin-booking' );
								break;
							default:
								$errors[] = $error['ErrorText'];
								break;
						}
					}

					return $errors;
				}, 10, 1 );

				return;
			}

			$event_booking = EDUAPI()->OData->ProgrammeBookings->GetItem(
				$booking_info['ProgrammeBookingId'],
				null,
				'OrderRows',
				false
			);
			$_customer     = EDUAPI()->OData->Customers->GetItem(
				$booking_info['CustomerId'],
				null,
				null,
				false
			);
			$_contact      = EDUAPI()->OData->Persons->GetItem(
				$booking_info['ContactPersonId'],
				null,
				null,
				false
			);

			$ebi = new EduAdmin_BookingInfo( $event_booking, $_customer, $_contact );

			$GLOBALS['edubookinginfo'] = $ebi;

			do_action( 'eduadmin-checkpaymentplugins', $ebi );

			if ( ! $ebi->NoRedirect ) {
				wp_redirect( get_page_link( get_option( 'eduadmin-thankYouPage', '/' ) ) . '?edu-thankyou=' . $booking_info['ProgrammeBookingId'] );
				exit( 0 );
			}
		}
	}

	private function get_programme_booking_data() {
		$programme_booking_data                   = new stdClass();
		$programme_booking_data->ProgrammeStartId = intval( $_POST['edu-programme-start'] ); // Var input okay.
		$programme_booking_data->Customer         = $this->get_customer();
		$programme_booking_data->ContactPerson    = $this->get_contact_person();
		$programme_booking_data->Participants     = $this->get_participant_data();

		$selected_match = get_option( 'eduadmin-customerMatching', 'name-zip-match' );

		if ( 'no-match' === $selected_match ) {
			$booking_options                               = new EduAdmin_Data_Options();
			$booking_options->SkipDuplicateMatchOnCustomer = true;
			$booking_options->SkipDuplicateMatchOnPersons  = true;
			$programme_booking_data->Options               = $booking_options;
		}

		return $programme_booking_data;
	}

	private function get_programme_booking() {
		$programme_booking_data = $this->get_programme_booking_data();

		$programme_booking = EDUAPI()->REST->ProgrammeBooking->Book( $programme_booking_data );

		return $programme_booking;
	}

	private function get_basic_booking_data( &$booking_data, $event_id ) {
		if ( empty( $_POST['edu-valid-form'] ) || ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			return null;
		}

		$selected_match = get_option( 'eduadmin-customerMatching', 'name-zip-match' );

		if ( 'no-match' === $selected_match ) {
			$booking_options                               = new EduAdmin_Data_Options();
			$booking_options->SkipDuplicateMatchOnCustomer = true;
			$booking_options->SkipDuplicateMatchOnPersons  = true;
			$booking_data->Options                         = $booking_options;
		}

		$send_info = new EduAdmin_Data_Mail();

		$send_info->SendToParticipants    = true;
		$send_info->SendToCustomer        = true;
		$send_info->SendToCustomerContact = true;

		$booking_data->SendConfirmationEmail = $send_info;

		$booking_data->EventId   = $event_id;
		$booking_data->Reference = sanitize_text_field( $_POST['invoiceReference'] ); // Var input okay.

		if ( 'selectWholeEvent' === get_option( 'eduadmin-selectPricename', 'firstPublic' ) && ! empty( $_POST['edu-pricename'] ) && is_numeric( $_POST['edu-pricename'] ) ) { // Var input okay.
			$booking_data->PriceNameId = intval( $_POST['edu-pricename'] ); // Var input okay.
		}

		if ( ! empty( $_POST['edu-limitedDiscountID'] ) ) { // Var input okay.
			$booking_data->VoucherId = intval( $_POST['edu-limitedDiscountID'] ); // Var input okay.
		}

		if ( ! empty( $_POST['edu-discountCode'] ) ) { // Var input okay.
			$booking_data->CouponCode = sanitize_text_field( $_POST['edu-discountCode'] ); // Var input okay.
		}

		$booking_data->Answers = $this->get_booking_questions();
	}

	private function get_single_participant_booking() {
		if ( empty( $_POST['edu-valid-form'] ) || ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			return null;
		}

		$event_id     = intval( $_REQUEST['eid'] ); // Var input okay.
		$booking_data = new EduAdmin_Data_BookingData();

		$this->get_basic_booking_data( $booking_data, $event_id );

		$customer = new stdClass();
		$contact  = $this->get_contact_person();

		$contact->AddAsParticipant = true;

		if ( isset( EDU()->session['eduadmin-loginUser'] ) ) {
			$user                 = EDU()->session['eduadmin-loginUser'];
			$contact->PersonId    = $user->Contact->PersonId;
			$customer->CustomerId = $user->Customer->CustomerId;
		}

		if ( ! empty( $_POST['edu-customerId'] ) ) { // Var input okay.
			$customer->CustomerId = intval( $_POST['edu-customerId'] ); // Var input okay.
		}

		$first = '';
		$last  = '';

		if ( ! empty( $_POST['contactFirstName'] ) ) { // Var input okay.
			$first = sanitize_text_field( $_POST['contactFirstName'] ); // Var input okay.
		}
		if ( ! empty( $_POST['contactLastName'] ) ) { // Var input okay.
			$last = sanitize_text_field( $_POST['contactLastName'] ); // Var input okay.
		}

		$customer->CustomerName    = $first . ' ' . $last;
		$customer->CustomerGroupId = intval( get_option( 'eduadmin-customerGroupId', null ) );
		if ( ! empty( $_POST['contactCivRegNr'] ) ) { // Var input okay.
			$customer->OrganisationNumber = sanitize_text_field( $_POST['contactCivRegNr'] ); // Var input okay.
		}
		if ( ! empty( $_POST['customerAddress1'] ) ) { // Var input okay.
			$customer->Address = sanitize_text_field( $_POST['customerAddress1'] ); // Var input okay.
		}
		if ( ! empty( $_POST['customerAddress2'] ) ) { // Var input okay.
			$customer->Address2 = sanitize_text_field( $_POST['customerAddress2'] ); // Var input okay.
		}
		if ( ! empty( $_POST['customerPostalCode'] ) ) { // Var input okay.
			$customer->Zip = sanitize_text_field( $_POST['customerPostalCode'] ); // Var input okay.
		}
		if ( ! empty( $_POST['customerPostalCity'] ) ) { // Var input okay.
			$customer->City = sanitize_text_field( $_POST['customerPostalCity'] ); // Var input okay.
		}
		if ( ! empty( $_POST['contactPhone'] ) ) { // Var input okay.
			$customer->Phone = sanitize_text_field( $_POST['contactPhone'] ); // Var input okay.
		}
		if ( ! empty( $_POST['contactMobile'] ) ) { // Var input okay.
			$customer->Mobile = sanitize_text_field( $_POST['contactMobile'] ); // Var input okay.
		}
		if ( ! empty( $_POST['contactEmail'] ) ) { // Var input okay.
			$customer->Email = sanitize_email( $_POST['contactEmail'] ); // Var input okay.
		}
		if ( ! empty( $_POST['invoiceEmail'] ) ) { // Var input okay.
			$customerInvoiceEmailAddress = sanitize_email( $_POST['invoiceEmail'] ); // Var input okay.
		}

		$billing_info = new stdClass();

		if ( empty( $_POST['alsoInvoiceCustomer'] ) ) { // Var input okay.
			$billing_info->CustomerName = $first . ' ' . $last;
			if ( ! empty( $_POST['customerAddress1'] ) ) { // Var input okay.
				$billing_info->Address = sanitize_text_field( $_POST['customerAddress1'] ); // Var input okay.
			}
			if ( ! empty( $_POST['customerAddress2'] ) ) { // Var input okay.
				$billing_info->Address2 = sanitize_text_field( $_POST['customerAddress2'] ); // Var input okay.
			}
			if ( ! empty( $_POST['customerPostalCode'] ) ) { // Var input okay.
				$billing_info->Zip = sanitize_text_field( $_POST['customerPostalCode'] ); // Var input okay.
			}
			if ( ! empty( $_POST['customerPostalCity'] ) ) { // Var input okay.
				$billing_info->City = sanitize_text_field( $_POST['customerPostalCity'] ); // Var input okay.
			}
		} else {
			if ( ! empty( $_POST['invoiceName'] ) ) { // Var input okay.
				$billing_info->CustomerName = sanitize_text_field( $_POST['invoiceName'] ); // Var input okay.
			}
			if ( ! empty( $_POST['invoiceAddress1'] ) ) { // Var input okay.
				$billing_info->Address = sanitize_text_field( $_POST['invoiceAddress1'] ); // Var input okay.
			}
			if ( ! empty( $_POST['invoiceAddress2'] ) ) { // Var input okay.
				$billing_info->Address2 = sanitize_text_field( $_POST['invoiceAddress2'] ); // Var input okay.
			}
			if ( ! empty( $_POST['invoicePostalCode'] ) ) { // Var input okay.
				$billing_info->Zip = sanitize_text_field( $_POST['invoicePostalCode'] ); // Var input okay.
			}
			if ( ! empty( $_POST['invoicePostalCity'] ) ) { // Var input okay.
				$billing_info->City = sanitize_text_field( $_POST['invoicePostalCity'] ); // Var input okay.
			}
		}

		if ( ! empty( $customerInvoiceEmailAddress ) ) {
			$billing_info->Email = $customerInvoiceEmailAddress;
		}

		$customer->BillingInfo = $billing_info;

		$customer->CustomFields = $this->get_customer_custom_fields();

		$booking_data->Customer      = $customer;
		$booking_data->ContactPerson = $contact;

		return $booking_data;
	}

	private function get_contact_person() {
		if ( empty( $_POST['edu-valid-form'] ) || ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			return null;
		}

		$contact = new stdClass();

		if ( ! empty( $_POST['edu-contactId'] ) ) { // Var input okay.
			$contact->PersonId = intval( $_POST['edu-contactId'] ); // Var input okay.
		}

		if ( ! empty( $_POST['contactFirstName'] ) ) { // Var input okay.
			$contact->FirstName = sanitize_text_field( $_POST['contactFirstName'] ); // Var input okay.
		}

		if ( ! empty( $_POST['contactLastName'] ) ) { // Var input okay.
			$contact->LastName = sanitize_text_field( $_POST['contactLastName'] ); // Var input okay.
		}

		if ( ! empty( $_POST['contactPhone'] ) ) { // Var input okay.
			$contact->Phone = sanitize_text_field( $_POST['contactPhone'] ); // Var input okay.
		}

		if ( ! empty( $_POST['contactMobile'] ) ) { // Var input okay.
			$contact->Mobile = sanitize_text_field( $_POST['contactMobile'] ); // Var input okay.
		}

		if ( ! empty( $_POST['contactEmail'] ) ) { // Var input okay.
			$contact->Email = sanitize_email( $_POST['contactEmail'] ); // Var input okay.
		}

		if ( ! empty( $_POST['contactCivReg'] ) ) { // Var input okay.
			$contact->CivicRegistrationNumber = sanitize_text_field( $_POST['contactCivReg'] ); // Var input okay.
		}
		if ( ! empty( $_POST['contactPass'] ) ) { // Var input okay.
			$contact->Password = sanitize_text_field( $_POST['contactPass'] ); // Var input okay.
		}

		if ( ! empty( $_POST['contactPriceName'] ) ) { // Var input okay.
			$contact->PriceNameId = intval( $_POST['contactPriceName'] ); // Var input okay.
		}

		$contact->CanLogin     = get_option( 'eduadmin-useLogin', false );
		$contact->Answers      = $this->get_contact_questions();
		$contact->CustomFields = $this->get_contact_custom_fields();
		$contact->Sessions     = $this->get_contact_sessions();

		if ( ! empty( $_POST['contactIsAlsoParticipant'] ) ) { // Var input okay.
			$contact->AddAsParticipant = true;
		}

		return $contact;
	}

	public function book_single_participant() {
		if ( empty( $_POST['edu-valid-form'] ) || ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			return null;
		}

		$booking_data = $this->get_single_participant_booking();

		$booking = EDUAPI()->REST->Booking->Create( $booking_data );

		if ( 'Oops! Something went wrong. Please contact eduadmin@multinet.freshdesk.com so we can try to fix it.' === $booking['data'] ) {
			$error_list = array();

			$std_error                 = array();
			$std_error['ErrorCode']    = -1;
			$std_error['ErrorDetails'] = 'An error has occurred, please try again!';
			$std_error['ErrorText']    = 'General error';

			$error_list['Errors'][] = $std_error;

			return $error_list;
		}

		if ( ! empty( $booking['Errors'] ) ) {
			$error_list           = array();
			$error_list['Errors'] = $booking['Errors'];

			return $error_list;
		}

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

	public function check_single_participant() {
		if ( empty( $_POST['edu-valid-form'] ) || ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			return null;
		}

		$booking_data = $this->get_single_participant_booking();

		return EDUAPI()->REST->Booking->CheckPrice( $booking_data );
	}

	private function get_multiple_participant_booking() {
		if ( empty( $_POST['edu-valid-form'] ) || ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			return null;
		}

		$event_id     = intval( $_REQUEST['eid'] ); // Var input okay.
		$booking_data = new EduAdmin_Data_BookingData();

		$this->get_basic_booking_data( $booking_data, $event_id );

		$customer = $this->get_customer();
		$contact  = $this->get_contact_person();

		if ( ! empty( $_POST['purchaseOrderNumber'] ) ) { // Var input okay.
			$booking_data->PurchaseOrderNumber = sanitize_text_field( $_POST['purchaseOrderNumber'] ); // Var input okay.
		}

		if ( ! empty( $customer->BillingInfo->SellerReference ) ) {
			$booking_data->Reference = $customer->BillingInfo->SellerReference;
		}

		if ( isset( EDU()->session['eduadmin-loginUser'] ) ) {
			$user                 = EDU()->session['eduadmin-loginUser'];
			$contact->PersonId    = $user->Contact->PersonId;
			$customer->CustomerId = $user->Customer->CustomerId;
		}

		if ( ! empty( $_POST['edu-customerId'] ) ) { // Var input okay.
			$customer->CustomerId = intval( $_POST['edu-customerId'] ); // Var input okay.
		}

		$booking_data->Customer      = $customer;
		$booking_data->ContactPerson = $contact;

		$participants = $this->get_participant_data();

		$booking_data->Participants = $participants;

		return $booking_data;
	}

	private function get_customer() {
		if ( empty( $_POST['edu-valid-form'] ) || ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			return null;
		}

		$customer = new stdClass();

		if ( ! empty( $_POST['customerName'] ) ) { // Var input okay.
			$customer->CustomerName = sanitize_text_field( $_POST['customerName'] ); // Var input okay.
		}
		$customer->CustomerGroupId = get_option( 'eduadmin-customerGroupId', null );
		if ( ! empty( $_POST['customerVatNo'] ) ) { // Var input okay.
			$customer->OrganisationNumber = sanitize_text_field( $_POST['customerVatNo'] ); // Var input okay.
		}
		if ( ! empty( $_POST['customerAddress1'] ) ) { // Var input okay.
			$customer->Address = sanitize_text_field( $_POST['customerAddress1'] ); // Var input okay.
		}
		if ( ! empty( $_POST['customerAddress2'] ) ) { // Var input okay.
			$customer->Address2 = sanitize_text_field( $_POST['customerAddress2'] ); // Var input okay.
		}
		if ( ! empty( $_POST['customerPostalCode'] ) ) { // Var input okay.
			$customer->Zip = sanitize_text_field( $_POST['customerPostalCode'] ); // Var input okay.
		}
		if ( ! empty( $_POST['customerPostalCity'] ) ) { // Var input okay.
			$customer->City = sanitize_text_field( $_POST['customerPostalCity'] ); // Var input okay.
		}
		if ( ! empty( $_POST['customerEmail'] ) ) { // Var input okay.
			$customer->Email = sanitize_email( $_POST['customerEmail'] ); // Var input okay.
		}

		$customerInvoiceEmailAddress = null;
		if ( ! empty( $_POST['invoiceEmail'] ) ) { // Var input okay.
			$customerInvoiceEmailAddress = sanitize_email( $_POST['invoiceEmail'] ); // Var input okay.
		}

		$billing_info = new stdClass();

		if ( ! isset( $_POST['alsoInvoiceCustomer'] ) ) { // Var input okay.
			if ( ! empty( $_POST['customerName'] ) ) { // Var input okay.
				$billing_info->CustomerName = sanitize_text_field( $_POST['customerName'] ); // Var input okay.
			}
			if ( ! empty( $_POST['customerAddress1'] ) ) { // Var input okay.
				$billing_info->Address = sanitize_text_field( $_POST['customerAddress1'] ); // Var input okay.
			}
			if ( ! empty( $_POST['customerAddress2'] ) ) { // Var input okay.
				$billing_info->Address2 = sanitize_text_field( $_POST['customerAddress2'] ); // Var input okay.
			}
			if ( ! empty( $_POST['customerPostalCode'] ) ) { // Var input okay.
				$billing_info->Zip = sanitize_text_field( $_POST['customerPostalCode'] ); // Var input okay.
			}
			if ( ! empty( $_POST['customerPostalCity'] ) ) { // Var input okay.
				$billing_info->City = sanitize_text_field( $_POST['customerPostalCity'] ); // Var input okay.
			}
		} else {
			if ( ! empty( $_POST['invoiceName'] ) ) { // Var input okay.
				$billing_info->CustomerName = sanitize_text_field( $_POST['invoiceName'] ); // Var input okay.
			}
			if ( ! empty( $_POST['invoiceAddress1'] ) ) { // Var input okay.
				$billing_info->Address = sanitize_text_field( $_POST['invoiceAddress1'] ); // Var input okay.
			}
			if ( ! empty( $_POST['invoiceAddress2'] ) ) { // Var input okay.
				$billing_info->Address2 = sanitize_text_field( $_POST['invoiceAddress2'] ); // Var input okay.
			}
			if ( ! empty( $_POST['invoicePostalCode'] ) ) { // Var input okay.
				$billing_info->Zip = sanitize_text_field( $_POST['invoicePostalCode'] ); // Var input okay.
			}
			if ( ! empty( $_POST['invoicePostalCity'] ) ) { // Var input okay.
				$billing_info->City = sanitize_text_field( $_POST['invoicePostalCity'] ); // Var input okay.
			}
		}

		if ( ! empty( $_POST['invoiceReference'] ) ) { // Var input okay.
			$billing_info->SellerReference = sanitize_text_field( $_POST['invoiceReference'] ); // Var input okay.
		}

		if ( ! empty( $customerInvoiceEmailAddress ) ) {
			$billing_info->Email = $customerInvoiceEmailAddress;
		}

		$customer->BillingInfo  = $billing_info;
		$customer->CustomFields = $this->get_customer_custom_fields();

		return $customer;
	}

	public function book_multiple_participants() {
		if ( empty( $_POST['edu-valid-form'] ) || ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			return null;
		}

		$booking_data = $this->get_multiple_participant_booking();

		$booking = EDUAPI()->REST->Booking->Create( $booking_data );

		if ( 'Oops! Something went wrong. Please contact eduadmin@multinet.freshdesk.com so we can try to fix it.' === $booking['data'] ) {
			$error_list = array();

			$std_error                 = array();
			$std_error['ErrorCode']    = -1;
			$std_error['ErrorDetails'] = 'An error has occurred, please try again!';
			$std_error['ErrorText']    = 'General error';

			$error_list['Errors'][] = $std_error;

			return $error_list;
		}

		if ( ! empty( $booking['Errors'] ) ) {
			$error_list           = array();
			$error_list['Errors'] = $booking['Errors'];

			return $error_list;
		}

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

	public function check_multiple_participants() {
		if ( empty( $_POST['edu-valid-form'] ) || ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			return null;
		}

		$booking_data = $this->get_multiple_participant_booking();

		return EDUAPI()->REST->Booking->CheckPrice( $booking_data );
	}

	public function check_programme() {
		if ( empty( $_POST['edu-valid-form'] ) || ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			return null;
		}

		$booking_data = $this->get_programme_booking_data();

		if ( empty( $booking_data->Customer->CustomerName ) ) {
			$booking_data->Customer->CustomerName = 'Empty';
		}

		if ( empty( $booking_data->ContactPerson->FirstName ) ) {
			$booking_data->ContactPerson->FirstName = 'Empty';
		}

		if ( 0 === count( $booking_data->Participants ) ) {
			$empty_participant            = new stdClass();
			$empty_participant->FirstName = 'Empty';
			$booking_data->Participants[] = $empty_participant;
		}

		return EDUAPI()->REST->ProgrammeBooking->CheckPrice( $booking_data );
	}

	private function get_customer_custom_fields() {
		if ( ! empty( $_POST['edu-valid-form'] ) && wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			$customer_custom_field_answers = array_filter( array_keys( $_POST ), function( $key ) { // Var input okay.
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
		if ( ! empty( $_POST['edu-valid-form'] ) && wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			$customer_custom_field_answers = array_filter( array_keys( $_POST ), function( $key ) { // Var input okay.
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
		if ( ! empty( $_POST['edu-valid-form'] ) && wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
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
					if ( ( 'note' === $custom_field_type || 'text' === $custom_field_type ) && ! empty( $_POST[ $key ] ) ) { // Var input okay.
						$answer->CustomFieldValue = sanitize_text_field( $_POST[ $key ] ); // Var input okay.
					} elseif ( 'number' === $custom_field_type && ! empty( $_POST[ $key ] ) ) { // Var input okay.
						$answer->CustomFieldValue = intval( $_POST[ $key ] ); // Var input okay.
					} elseif ( 'date' === $custom_field_type && ! empty( $_POST[ $key ] ) ) { // Var input okay.
						$answer->CustomFieldValue = date( 'c', strtotime( $_POST[ $key ] ) ); // Var input okay.
					} else {
						$answer->CustomFieldValue = null;
					}

					break;
			}

			return $answer;
		}
	}

	private function get_contact_sessions() {
		if ( ! empty( $_POST['edu-valid-form'] ) && wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			$session_keys = array_filter( array_keys( $_POST ), function( $key ) { // Var input okay.
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
		if ( ! empty( $_POST['edu-valid-form'] ) && wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			$contact_question_answers = array_filter( array_keys( $_POST ), function( $key ) { // Var input okay.
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
		if ( ! empty( $_POST['edu-valid-form'] ) && wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			$answer = new stdClass();
			switch ( $question_type ) {
				case 'dropdown':
				case 'check':
				case 'radio':
					$question_answer_id = $_POST[ $key ]; // Var input okay.
					$answer->AnswerId   = intval( $question_answer_id );
					if ( 'check' === $question_type || 'radio' === $question_type ) {
						$answer->AnswerValue = true;
					} else {
						$answer->AnswerValue = intval( $question_answer_id );
					}
					break;
				default:
					$answer->AnswerId = intval( $question_answer_id );
					if ( ( 'note' === $question_type || 'text' === $question_type ) && ! empty( $_POST[ $key ] ) ) { // Var input okay.
						$answer->AnswerValue = sanitize_text_field( $_POST[ $key ] ); // Var input okay.
					} elseif ( 'number' === $question_type && ! empty( $_POST[ $key ] ) ) { // Var input okay.
						$answer->AnswerValue = intval( $_POST[ $key ] ); // Var input okay.
					} elseif ( 'date' === $question_type && ! empty( $_POST[ $key ] ) ) { // Var input okay.
						$answer->AnswerValue = date( 'c', strtotime( $_POST[ $key ] ) ); // Var input okay.
					} else {
						$answer->AnswerValue = null;
					}

					break;
			}

			return $answer;
		}
	}

	private function get_booking_questions() {
		if ( ! empty( $_POST['edu-valid-form'] ) && wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			$booking_question_answers = array_filter( array_keys( $_POST ), function( $key ) { // Var input okay.
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
		if ( empty( $_POST['edu-valid-form'] ) || ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			return null;
		}

		$participants = array();

		foreach ( $_POST['participantFirstName'] as $key => $value ) { // Var input okay.
			if ( '0' === $key ) {
				continue;
			}

			if ( ! empty( $_POST['participantFirstName'][ $key ] ) ) { // Var input okay.
				$person            = new stdClass();
				$person->FirstName = sanitize_text_field( $_POST['participantFirstName'][ $key ] ); // Var input okay.
				if ( ! empty( $_POST['participantLastName'][ $key ] ) ) { // Var input okay.
					$person->LastName = sanitize_text_field( $_POST['participantLastName'][ $key ] ); // Var input okay.
				}
				if ( ! empty( $_POST['participantEmail'][ $key ] ) ) { // Var input okay.
					$person->Email = sanitize_email( $_POST['participantEmail'][ $key ] ); // Var input okay.
				}
				if ( ! empty( $_POST['participantPhone'][ $key ] ) ) { // Var input okay.
					$person->Phone = sanitize_text_field( $_POST['participantPhone'][ $key ] ); // Var input okay.
				}
				if ( ! empty( $_POST['participantMobile'][ $key ] ) ) { // Var input okay.
					$person->Mobile = sanitize_text_field( $_POST['participantMobile'][ $key ] ); // Var input okay.
				}

				if ( ! empty( $_POST['participantCivReg'][ $key ] ) ) { // Var input okay.
					$person->CivicRegistrationNumber = trim( sanitize_text_field( $_POST['participantCivReg'][ $key ] ) ); // Var input okay.
				}

				if ( ! empty( $_POST['participantPriceName'][ $key ] ) ) { // Var input okay.
					$person->PriceNameId = intval( $_POST['participantPriceName'][ $key ] ); // Var input okay.
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
		if ( empty( $_POST['edu-valid-form'] ) || ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			return null;
		}

		$custom_field_keys = array_filter( array_keys( $_POST ), function( $key ) use ( $index ) { // Var input okay.
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

			if ( $index === $participant_index && ! empty( $_POST[ $key ] ) && is_numeric( $field_id ) ) { // Var input okay.
				$answer = $this->get_custom_field_data( $key, $field_id, $custom_field_type );

				if ( null !== $answer->CustomFieldValue ) {
					$custom_fields[] = $answer;
				}
			}
		}

		return $custom_fields;
	}

	private function get_participant_answers( $index ) {
		if ( empty( $_POST['edu-valid-form'] ) || ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			return null;
		}

		$answers = array();

		$question_answers = array_filter( array_keys( $_POST ), function( $key ) use ( $index ) { // Var input okay.
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

			if ( $index === $question_participant_index && ! empty( $_POST[ $key ] ) && is_numeric( $question_answer_id ) ) { // Var input okay.
				$answer = $this->get_answer_data( $key, $question_answer_id, $question_type );

				if ( null !== $answer->AnswerValue ) {
					$answers[] = $answer;
				}
			}
		}

		return $answers;
	}

	private function get_participant_sessions( $index ) {
		if ( empty( $_POST['edu-valid-form'] ) || ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) { // Var input okay.
			return null;
		}

		$session_keys = array_filter( array_keys( $_POST ), function( $key ) use ( $index ) { // Var input okay.
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
				if ( ! empty( $_POST[ 'participantSubEvent_' . $session_id . '_' . $participant_index ] ) ) { // Var input okay.
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
