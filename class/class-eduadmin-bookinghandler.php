<?php

// phpcs:disable WordPress.NamingConventions,Squiz
class EduAdmin_BookingHandler {
	public function __construct() {
		add_action( 'wp_loaded', array( $this, 'process_booking' ) );
	}

	public function process_booking() {
		if ( wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) && ! empty( $_POST['act'] ) && 'bookCourse' === sanitize_text_field( $_POST['act'] ) ) {
			$single_person_booking = get_option( 'eduadmin-singlePersonBooking', false );

			$booking_info = $single_person_booking ? $this->book_single_participant() : $this->book_multiple_participants();

			EDU()->write_debug( $booking_info );

			$event_booking = EDUAPI()->OData->Bookings->GetItem( $booking_info['BookingId'] );
			$_customer     = EDUAPI()->OData->Customers->GetItem( $booking_info['CustomId'] );
			$_contact      = EDUAPI()->OData->Persons->GetItem( $booking_info['ContactPersonId'] );

			$ebi = new EduAdmin_BookingInfo( $event_booking, $_customer, $_contact );

			$GLOBALS['edubookinginfo'] = $ebi;

			do_action( 'eduadmin-checkpaymentplugins', $ebi );

			if ( ! $ebi->NoRedirect ) {
				//wp_redirect( get_page_link( get_option( 'eduadmin-thankYouPage', '/' ) ) . '?edu-thankyou=' . $event_customer_lnk_id );
				exit();
			}
		}
	}

	private function get_single_participant_booking() {
		if ( ! wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			return null;
		}

		$event_id     = intval( $_REQUEST['eid'] );
		$booking_data = new EduAdmin_Data_BookingData();

		$selected_match = get_option( 'eduadmin-customerMatching', 'name-zip-match' );

		if ( 'no-match' === $selected_match ) {
			$booking_options                               = new EduAdmin_Data_Options();
			$booking_options->SkipDuplicateMatchOnCustomer = true;
			$booking_options->SkipDuplicateMatchOnPersons  = true;
			$booking_data->Options                         = $booking_options;
		}

		$booking_data->EventId   = $event_id;
		$booking_data->Reference = sanitize_text_field( $_POST['invoiceReference'] );

		if ( 'selectWholeEvent' === get_option( 'eduadmin-selectPricename', 'firstPublic' ) ) {
			if ( ! empty( $_POST['edu-pricename'] ) && is_numeric( $_POST['edu-pricename'] ) ) {
				$booking_data->PriceNameId = intval( $_POST['edu-pricename'] );
			}
		}

		if ( ! empty( $_POST['edu-limitedDiscountID'] ) ) {
			$booking_data->VoucherId = intval( $_POST['edu-limitedDiscountID'] );
		}

		if ( ! empty( $_POST['edu-discountCode'] ) ) {
			$booking_data->CouponCode = sanitize_text_field( $_POST['edu-discountCode'] );
		}

		$booking_data->Answers = $this->get_booking_questions();

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
		if ( isset( $_POST['contactCivRegNr'] ) ) {
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

		if ( ! isset( $_POST['alsoInvoiceCustomer'] ) ) {
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

		$booking_data->Customer = $customer;

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
		}

		$booking_data->ContactPerson = $contact;

		$send_info = new EduAdmin_Data_Mail();

		$send_info->SendToParticipants    = true;
		$send_info->SendToCustomer        = true;
		$send_info->SendToCustomerContact = true;

		$booking_data->SendConfirmationEmail = $send_info;

		return $booking_data;
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
		EDU()->write_debug($booking_data, true);
		$booking      = EDUAPI()->REST->Booking->Create( $booking_data );

		EDU()->write_debug( $booking );
die();
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

	public function book_multiple_participants() {
		if ( wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) ) {
			$event_id = intval( $_REQUEST['eid'] );

			$customer = new CustomerV2();
			$contact  = new CustomerContact();

			if ( isset( EDU()->session['eduadmin-loginUser'] ) ) {
				$user                       = EDU()->session['eduadmin-loginUser'];
				$contact->CustomerContactID = $user->Contact->CustomerContactID;
				$customer->CustomerID       = $user->Customer->CustomerID;
			}

			$customer->CustomerName      = trim( sanitize_text_field( $_POST['customerName'] ) );
			$customer->CustomerGroupID   = get_option( 'eduadmin-customerGroupId', null );
			$customer->InvoiceOrgnr      = trim( sanitize_text_field( $_POST['customerVatNo'] ) );
			$customer->Address1          = trim( sanitize_text_field( $_POST['customerAddress1'] ) );
			$customer->Address2          = trim( sanitize_text_field( $_POST['customerAddress2'] ) );
			$customer->Zip               = trim( sanitize_text_field( $_POST['customerPostalCode'] ) );
			$customer->City              = trim( sanitize_text_field( $_POST['customerPostalCity'] ) );
			$customer->Email             = sanitize_email( $_POST['customerEmail'] );
			$customer->CustomerReference = trim( sanitize_text_field( $_POST['invoiceReference'] ) );

			$purchaseOrderNumber = trim( sanitize_text_field( $_POST['purchaseOrderNumber'] ) );

			$customerInvoiceEmailAddress = sanitize_email( $_POST['invoiceEmail'] );

			if ( ! isset( $_POST['alsoInvoiceCustomer'] ) ) {
				$customer->InvoiceName     = trim( sanitize_text_field( $_POST['customerName'] ) );
				$customer->InvoiceAddress1 = trim( sanitize_text_field( $_POST['customerAddress1'] ) );
				$customer->InvoiceAddress2 = trim( sanitize_text_field( $_POST['customerAddress2'] ) );
				$customer->InvoiceZip      = trim( sanitize_text_field( $_POST['customerPostalCode'] ) );
				$customer->InvoiceCity     = trim( sanitize_text_field( $_POST['customerPostalCity'] ) );
			} else {
				$customer->InvoiceName     = trim( sanitize_text_field( $_POST['invoiceName'] ) );
				$customer->InvoiceAddress1 = trim( sanitize_text_field( $_POST['invoiceAddress1'] ) );
				$customer->InvoiceAddress2 = trim( sanitize_text_field( $_POST['invoiceAddress2'] ) );
				$customer->InvoiceZip      = trim( sanitize_text_field( $_POST['invoicePostalCode'] ) );
				$customer->InvoiceCity     = trim( sanitize_text_field( $_POST['invoicePostalCity'] ) );
			}

			if ( ! empty( $customerInvoiceEmailAddress ) ) {
				$customer->InvoiceEmail = $customerInvoiceEmailAddress;
			}

			$selectedMatch = get_option( 'eduadmin-customerMatching', 'name-zip-match' );
			if ( 'name-zip-match' === $selectedMatch ) {
				$ft = new XFiltering();
				if ( $customer->CustomerID == 0 ) {
					if ( empty( $customer->InvoiceOrgnr ) ) {
						$f = new XFilter( 'CustomerName', '=', $customer->CustomerName );
						$ft->AddItem( $f );
					} else {
						$f = new XFilter( 'InvoiceOrgnr', '=', $customer->InvoiceOrgnr );
						$ft->AddItem( $f );
					}
					$f = new XFilter( 'Zip', '=', str_replace( " ", "", $customer->Zip ) );
					$ft->AddItem( $f );
				} else {
					$f = new XFilter( 'CustomerID', '=', $customer->CustomerID );
					$ft->AddItem( $f );
				}
				$matchingCustomer = EDU()->api->GetCustomerV2( EDU()->get_token(), '', $ft->ToString(), false );
				if ( empty( $matchingCustomer ) ) {
					$customer->CustomerID = 0;
					$cres                 = EDU()->api->SetCustomerV2( EDU()->get_token(), array( $customer ) );
					$customer->CustomerID = $cres[0];
				} else {
					$customer = $matchingCustomer[0];
				}
			} elseif ( 'name-zip-match-overwrite' === $selectedMatch ) {
				$ft = new XFiltering();
				if ( $customer->CustomerID == 0 ) {
					if ( empty( $customer->InvoiceOrgnr ) ) {
						$ft = new XFiltering();
						$f  = new XFilter( 'CustomerName', '=', $customer->CustomerName );
						$ft->AddItem( $f );
					} else {
						$ft = new XFiltering();
						$f  = new XFilter( 'InvoiceOrgnr', '=', $customer->InvoiceOrgnr );
						$ft->AddItem( $f );
					}
					$f = new XFilter( 'Zip', '=', str_replace( " ", "", $customer->Zip ) );
					$ft->AddItem( $f );
				} else {
					$f = new XFilter( 'CustomerID', '=', $customer->CustomerID );
					$ft->AddItem( $f );
				}
				$matchingCustomer = EDU()->api->GetCustomerV2( EDU()->get_token(), '', $ft->ToString(), false );
				if ( empty( $matchingCustomer ) ) {
					$customer->CustomerID = 0;
					$cres                 = EDU()->api->SetCustomerV2( EDU()->get_token(), array( $customer ) );
					$customer->CustomerID = $cres[0];
				} else {
					$customer->CustomerID = $matchingCustomer[0]->CustomerID;
					EDU()->api->SetCustomerV2( EDU()->get_token(), array( $customer ) );
				}
			} elseif ( 'no-match' === $selectedMatch ) {
				$customer->CustomerID = 0;
				$cres                 = EDU()->api->SetCustomerV2( EDU()->get_token(), array( $customer ) );
				$customer->CustomerID = $cres[0];
			} elseif ( 'no-match-new-overwrite' === $selectedMatch ) {
				if ( $contact->CustomerContactID == 0 ) {
					$customer->CustomerID = 0;
					$cres                 = EDU()->api->SetCustomerV2( EDU()->get_token(), array( $customer ) );
					$customer->CustomerID = $cres[0];
				} else {
					$ft = new XFiltering();
					$f  = new XFilter( 'CustomerID', '=', $customer->CustomerID );
					$ft->AddItem( $f );
					$matchingCustomer = EDU()->api->GetCustomerV2( EDU()->get_token(), '', $ft->ToString(), false );
					if ( empty( $matchingCustomer ) ) {
						$customer->CustomerID = 0;
						$cres                 = EDU()->api->SetCustomerV2( EDU()->get_token(), array( $customer ) );
						$customer->CustomerID = $cres[0];
					} else {
						$customer->CustomerID = $matchingCustomer[0]->CustomerID;
						EDU()->api->SetCustomerV2( EDU()->get_token(), array( $customer ) );
					}
				}
			}

			if ( 0 == $customer->CustomerID ) {
				die( 'Kunde inte skapa kundposten' );
			} else {
				$so = new XSorting();
				$s  = new XSort( 'SortIndex', 'ASC' );
				$so->AddItem( $s );

				$fo = new XFiltering();
				$f  = new XFilter( 'ShowOnWeb', '=', 'true' );
				$fo->AddItem( $f );
				$f = new XFilter( 'AttributeOwnerTypeID', '=', 2 );
				$fo->AddItem( $f );
				$customerAttributes = EDU()->api->GetAttribute( EDU()->get_token(), $so->ToString(), $fo->ToString() );

				$cmpArr = array();

				foreach ( $customerAttributes as $attr ) {
					$fieldId = "edu-attr_" . $attr->AttributeID;
					if ( isset( $_POST[ $fieldId ] ) ) {
						$at              = new CustomerAttribute();
						$at->CustomerID  = $customer->CustomerID;
						$at->AttributeID = $attr->AttributeID;

						switch ( $attr->AttributeTypeID ) {
							case 1:
								$at->AttributeChecked = true;
								break;
							case 5:
								$alt                         = new AttributeAlternative();
								$alt->AttributeAlternativeID = intval( $_POST[ $fieldId ] );
								$at->AttributeAlternative[]  = $alt;
								break;
							default:
								$at->AttributeValue = sanitize_text_field( $_POST[ $fieldId ] );
								break;
						}

						$cmpArr[] = $at;
					}
				}

				EDU()->api->SetCustomerAttribute( EDU()->get_token(), $cmpArr );
			}

			$contact->CustomerID = $customer->CustomerID;

			if ( ! empty( $_POST['contactFirstName'] ) ) {
				$contact->ContactName = trim( sanitize_text_field( $_POST['contactFirstName'] ) ) . ";" . trim( sanitize_text_field( $_POST['contactLastName'] ) );
				$contact->Phone       = trim( sanitize_text_field( $_POST['contactPhone'] ) );
				$contact->Mobile      = trim( sanitize_text_field( $_POST['contactMobile'] ) );
				$contact->Email       = sanitize_email( $_POST['contactEmail'] );
				if ( isset( $_POST['contactCivReg'] ) ) {
					$contact->CivicRegistrationNumber = trim( sanitize_text_field( $_POST['contactCivReg'] ) );
				}
				if ( isset( $_POST['contactPass'] ) && ! empty( $_POST['contactPass'] ) ) {
					$contact->Loginpass = sanitize_text_field( $_POST['contactPass'] );
				}
				$contact->CanLogin    = 'true';
				$contact->PublicGroup = 'true';

				$ft = new XFiltering();
				$f  = new XFilter( 'CustomerID', '=', $customer->CustomerID );
				$ft->AddItem( $f );
				if ( $contact->CustomerContactID == 0 ) {
					$f = new XFilter( 'ContactName', '=', trim( str_replace( ';', ' ', $contact->ContactName ) ) );
					$ft->AddItem( $f );

					$f = new XFilter( 'Email', '=', $contact->Email );
					$ft->AddItem( $f );
				} else {
					$f = new XFilter( 'CustomerContactID', '=', $contact->CustomerContactID );
					$ft->AddItem( $f );
				}
				$matchingContacts = EDU()->api->GetCustomerContact( EDU()->get_token(), '', $ft->ToString(), false );
				if ( empty( $matchingContacts ) ) {
					$contact->CustomerContactID = 0;
					$contact->CustomerContactID = EDU()->api->SetCustomerContact( EDU()->get_token(), array( $contact ) )[0];
				} else {
					if ( 'name-zip-match-overwrite' === $selectedMatch ) {
						$contact->CustomerContactID = $matchingContacts[0]->CustomerContactID;
						EDU()->api->SetCustomerContact( EDU()->get_token(), array( $contact ) );
					} else {
						$contact = $matchingContacts[0];
						if ( isset( $_POST['contactPass'] ) && empty( $contact->Loginpass ) ) {
							$contact->Loginpass = sanitize_text_field( $_POST['contactPass'] );
							EDU()->api->SetCustomerContact( EDU()->get_token(), array( $contact ) );
						}
					}
				}

				$contact->ContactName = str_replace( ";", " ", $contact->ContactName );
			}

			if ( 0 !== $contact->CustomerContactID ) {
				$so = new XSorting();
				$s  = new XSort( 'SortIndex', 'ASC' );
				$so->AddItem( $s );

				$fo = new XFiltering();
				$f  = new XFilter( 'ShowOnWeb', '=', 'true' );
				$fo->AddItem( $f );
				$f = new XFilter( 'AttributeOwnerTypeID', '=', 4 );
				$fo->AddItem( $f );
				$contactAttributes = EDU()->api->GetAttribute( EDU()->get_token(), $so->ToString(), $fo->ToString() );

				$cmpArr = array();

				foreach ( $contactAttributes as $attr ) {
					$fieldId = "edu-attr_" . $attr->AttributeID;
					if ( isset( $_POST[ $fieldId ] ) ) {
						$at                    = new CustomerContactAttribute();
						$at->CustomerContactID = $contact->CustomerContactID;
						$at->AttributeID       = $attr->AttributeID;

						switch ( $attr->AttributeTypeID ) {
							case 1:
								$at->AttributeChecked = true;
								break;
							case 5:
								$alt                         = new AttributeAlternative();
								$alt->AttributeAlternativeID = intval( $_POST[ $fieldId ] );
								$at->AttributeAlternative[]  = $alt;
								break;
							default:
								$at->AttributeValue = sanitize_text_field( $_POST[ $fieldId ] );
								break;
						}

						$cmpArr[] = $at;
					}
				}

				EDU()->api->SetCustomerContactAttributes( EDU()->get_token(), $cmpArr );
			}

			$personEmail = array();
			if ( ! empty( $contact->Email ) && ! in_array( $contact->Email, $personEmail ) ) {
				$personEmail[] = $contact->Email;
			}

			$st = new XSorting();
			$s  = new XSort( 'StartDate', 'ASC' );
			$st->AddItem( $s );
			$s = new XSort( 'EndDate', 'ASC' );
			$st->AddItem( $s );

			$ft = new XFiltering();
			$f  = new XFilter( 'ParentEventID', '=', $event_id );
			$ft->AddItem( $f );
			$subEvents = EDU()->api->GetSubEvent( EDU()->get_token(), $st->ToString(), $ft->ToString() );

			$pArr = array();

			$so = new XSorting();
			$s  = new XSort( 'SortIndex', 'ASC' );
			$so->AddItem( $s );

			$fo = new XFiltering();
			$f  = new XFilter( 'ShowOnWeb', '=', 'true' );
			$fo->AddItem( $f );
			$f = new XFilter( 'AttributeOwnerTypeID', '=', 3 );
			$fo->AddItem( $f );
			$personAttributes = EDU()->api->GetAttribute( EDU()->get_token(), $so->ToString(), $fo->ToString() );

			foreach ( $_POST['participantFirstName'] as $key => $value ) {
				if ( "0" === $key ) {
					continue;
				}

				if ( ! empty( $_POST['participantFirstName'][ $key ] ) ) {
					$person               = new SubEventPerson();
					$person->CustomerID   = $customer->CustomerID;
					$person->PersonName   = trim( sanitize_text_field( $_POST['participantFirstName'][ $key ] ) ) . ";" . trim( sanitize_text_field( $_POST['participantLastName'][ $key ] ) );
					$person->PersonEmail  = sanitize_email( $_POST['participantEmail'][ $key ] );
					$person->PersonPhone  = trim( sanitize_text_field( $_POST['participantPhone'][ $key ] ) );
					$person->PersonMobile = trim( sanitize_text_field( $_POST['participantMobile'][ $key ] ) );

					$ft = new XFiltering();
					$f  = new XFilter( 'CustomerID', '=', $customer->CustomerID );
					$ft->AddItem( $f );
					$f = new XFilter( 'PersonName', '=', trim( str_replace( ';', ' ', $person->PersonName ) ) );
					$ft->AddItem( $f );
					$f = new XFilter( 'PersonEmail', '=', $person->PersonEmail );
					$ft->AddItem( $f );
					$matchingPersons = EDU()->api->GetPerson( EDU()->get_token(), '', $ft->ToString(), false );
					if ( ! empty( $matchingPersons ) ) {
						$person = $matchingPersons[0];
					}

					$cmpArr = array();

					foreach ( $personAttributes as $attr ) {
						$fieldId = "edu-attr_" . $attr->AttributeID;
						if ( isset( $_POST[ $fieldId ][ $key ] ) ) {
							$at              = new Attribute();
							$at->AttributeID = $attr->AttributeID;

							switch ( $attr->AttributeTypeID ) {
								case 1:
									//$at->AttributeChecked = true;
									break;
								case 5:
									$alt                         = new AttributeAlternative();
									$alt->AttributeAlternativeID = intval( $_POST[ $fieldId ][ $key ] );
									$at->AttributeAlternative[]  = $alt;
									break;
								default:
									$at->AttributeValue = sanitize_text_field( $_POST[ $fieldId ][ $key ] );
									break;
							}

							$cmpArr[] = $at;
						}
					}

					$person->Attribute = $cmpArr;

					$person->PersonEmail  = sanitize_email( $_POST['participantEmail'][ $key ] );
					$person->PersonPhone  = trim( sanitize_text_field( $_POST['participantPhone'][ $key ] ) );
					$person->PersonMobile = trim( sanitize_text_field( $_POST['participantMobile'][ $key ] ) );

					if ( isset( $_POST['participantCivReg'][ $key ] ) ) {
						$person->PersonCivicRegistrationNumber = trim( sanitize_text_field( $_POST['participantCivReg'][ $key ] ) );
					}

					if ( isset( $_POST['participantPriceName'][ $key ] ) ) {
						$person->OccasionPriceNameLnkID = intval( $_POST['participantPriceName'][ $key ] );
					}

					foreach ( $subEvents as $subEvent ) {
						$fieldName = "participantSubEvent_" . $subEvent->EventID;
						if ( isset( $_POST[ $fieldName ][ $key ] ) ) {
							$fieldValue            = sanitize_text_field( $_POST[ $fieldName ][ $key ] );
							$subEventInfo          = new SubEventInfo();
							$subEventInfo->EventID = $fieldValue;
							$person->SubEvents[]   = $subEventInfo;
						} elseif ( $subEvent->MandatoryParticipation ) {
							$subEventInfo          = new SubEventInfo();
							$subEventInfo->EventID = $subEvent->EventID;
							$person->SubEvents[]   = $subEventInfo;
						}
					}

					$pArr[] = $person;

					if ( ! empty( $person->PersonEmail ) && ! in_array( $person->PersonEmail, $personEmail ) ) {
						$personEmail[] = $person->PersonEmail;
					}
				}
			}

			if ( isset( $_POST['contactIsAlsoParticipant'] ) && $contact->CustomerContactID > 0 ) {
				$person                                = new SubEventPerson();
				$person->CustomerID                    = $customer->CustomerID;
				$person->CustomerContactID             = $contact->CustomerContactID;
				$person->PersonName                    = $contact->ContactName;
				$person->PersonEmail                   = $contact->Email;
				$person->PersonPhone                   = $contact->Phone;
				$person->PersonMobile                  = $contact->Mobile;
				$person->PersonCivicRegistrationNumber = $contact->CivicRegistrationNumber;
				$ft                                    = new XFiltering();
				$f                                     = new XFilter( 'CustomerID', '=', $customer->CustomerID );
				$ft->AddItem( $f );
				$f = new XFilter( 'CustomerContactID', '=', $contact->CustomerContactID );
				$ft->AddItem( $f );
				$matchingPersons = EDU()->api->GetPerson( EDU()->get_token(), '', $ft->ToString(), false );
				if ( ! empty( $matchingPersons ) ) {
					$person = $matchingPersons[0];
				}

				$cmpArr = array();

				foreach ( $personAttributes as $attr ) {
					$fieldId = "edu-attr_" . $attr->AttributeID . "-contact";
					if ( isset( $_POST[ $fieldId ] ) ) {
						$at              = new Attribute();
						$at->AttributeID = $attr->AttributeID;

						switch ( $attr->AttributeTypeID ) {
							case 1:
								//$at->AttributeChecked = true;
								break;
							case 5:
								$alt                         = new AttributeAlternative();
								$alt->AttributeAlternativeID = intval( $_POST[ $fieldId ] );
								$at->AttributeAlternative[]  = $alt;
								break;
							default:
								$at->AttributeValue = sanitize_text_field( $_POST[ $fieldId ] );
								break;
						}

						$cmpArr[] = $at;
					}
				}

				$person->Attribute = $cmpArr;

				if ( isset( $_POST['contactCivReg'] ) ) {
					$person->PersonCivicRegistrationNumber = trim( sanitize_text_field( $_POST['contactCivReg'] ) );
				}

				if ( isset( $_POST['contactPriceName'] ) ) {
					$person->OccasionPriceNameLnkID = intval( $_POST['contactPriceName'] );
				}
				$person->SubEvents = array();
				foreach ( $subEvents as $subEvent ) {
					$fieldName = "contactSubEvent_" . $subEvent->EventID;
					if ( isset( $_POST[ $fieldName ] ) ) {
						$fieldValue            = sanitize_text_field( $_POST[ $fieldName ] );
						$subEventInfo          = new SubEventInfo();
						$subEventInfo->EventID = $fieldValue;
						$person->SubEvents[]   = $subEventInfo;
					} elseif ( $subEvent->MandatoryParticipation ) {
						$subEventInfo          = new SubEventInfo();
						$subEventInfo->EventID = $subEvent->EventID;
						$person->SubEvents[]   = $subEventInfo;
					}
				}

				$pArr[] = $person;
			}

			if ( ! empty( $pArr ) ) {
				$bi                      = new BookingInfoSubEvent();
				$bi->EventID             = $event_id;
				$bi->CustomerID          = $customer->CustomerID;
				$bi->CustomerContactID   = $contact->CustomerContactID;
				$bi->SubEventPersons     = $pArr;
				$bi->PurchaseOrderNumber = $purchaseOrderNumber;
				if ( isset( $_POST['edu-pricename'] ) ) {
					$bi->OccasionPriceNameLnkID = intval( $_POST['edu-pricename'] );
				}

				if ( isset( $_POST['edu-limitedDiscountID'] ) ) {
					$bi->LimitedDiscountID = intval( $_POST['edu-limitedDiscountID'] );
				}

				if ( isset( $_POST['edu-discountCodeID'] ) && $_POST['edu-discountCodeID'] != "0" ) {
					$bi->CouponID = intval( $_POST['edu-discountCodeID'] );
				}

				$bi->CustomerReference = ( ! empty( $_POST['invoiceReference'] ) ? trim( sanitize_text_field( $_POST['invoiceReference'] ) ) : trim( str_replace( ';', ' ', $contact->ContactName ) ) );
				$event_customer_lnk_id = EDU()->api->CreateSubEventBooking(
					EDU()->get_token(),
					$bi
				);

				$answers = array();
				foreach ( $_POST as $input => $value ) {
					if ( strpos( $input, "question_" ) !== false ) {
						$question = explode( '_', $input );
						$answerID = intval( $question[1] );
						$type     = sanitize_text_field( $question[2] );

						switch ( $type ) {
							case 'radio':
							case 'check':
							case 'dropdown':
								$answerID = $value;
								break;
						}
						if ( $type === "time" ) {
							$answers[ $answerID ]['AnswerTime'] = trim( sanitize_text_field( $value ) );
						} else {
							$answers[ $answerID ] =
								array(
									'AnswerID'           => $answerID,
									'AnswerText'         => trim( sanitize_text_field( $value ) ),
									'EventID'            => $event_id,
									'EventCustomerLnkID' => $event_customer_lnk_id,
								);
						}
					}
				}

				// Spara alla frågor till eventcustomeranswerv2
				if ( ! empty( $answers ) ) {
					$sanswers = array();
					foreach ( $answers as $answer ) {
						$sanswers[] = $answer;
					}
					EDU()->api->SetEventCustomerAnswerV2( EDU()->get_token(), $sanswers );
				}

				$ai          = EDU()->api->GetAccountInfo( EDU()->get_token() )[0];
				$senderEmail = $ai->Email;
				if ( empty( $senderEmail ) ) {
					$senderEmail = 'no-reply@legaonline.se';
				}
				if ( ! empty( $personEmail ) ) {
					EDU()->api->SendConfirmationEmail( EDU()->get_token(), $event_customer_lnk_id, $senderEmail, $personEmail );
				}

				EDU()->session['eduadmin-printJS'] = true;

				if ( isset( EDU()->session['eduadmin-loginUser'] ) ) {
					$user = EDU()->session['eduadmin-loginUser'];
				} else {
					$user = new stdClass;
				}

				$jsEncContact = json_encode( $contact );
				@$user->Contact = json_decode( $jsEncContact );

				$jsEncCustomer = json_encode( $customer );
				@$user->Customer = json_decode( $jsEncCustomer );
				EDU()->session['eduadmin-loginUser'] = $user;

				$booking_info = array(
					'eventCustomerLnkId' => $event_customer_lnk_id,
					'eventId'            => $event_id,
					'customerId'         => $customer->CustomerID,
					'contactId'          => $contact->CustomerContactID,
				);

				return $booking_info;
			}
		}

		return null;
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
}
