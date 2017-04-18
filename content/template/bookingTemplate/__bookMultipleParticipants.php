<?php
$customer = new CustomerV2();
$contact = new CustomerContact();

if ( isset( $_SESSION[ 'eduadmin-loginUser' ] ) ) {
	$user = $_SESSION[ 'eduadmin-loginUser' ];
	$contact->CustomerContactID = $user->Contact->CustomerContactID;
	$customer->CustomerID = $user->Customer->CustomerID;
}

$customer->CustomerName = trim( $_POST[ 'customerName' ] );
$customer->CustomerGroupID = get_option( 'eduadmin-customerGroupId', null );
$customer->InvoiceOrgnr = trim( $_POST[ 'customerVatNo' ] );
$customer->Address1 = trim( $_POST[ 'customerAddress1' ] );
$customer->Address2 = trim( $_POST[ 'customerAddress2' ] );
$customer->Zip = trim( $_POST[ 'customerPostalCode' ] );
$customer->City = trim( $_POST[ 'customerPostalCity' ] );
$customer->Email = trim( $_POST[ 'customerEmail' ] );
$customer->CustomerReference = trim( $_POST[ 'invoiceReference' ] );

$purchaseOrderNumber = trim( $_POST[ 'purchaseOrderNumber' ] );

$customerInvoiceEmailAddress = trim( $_POST[ 'invoiceEmail' ] );

if ( ! isset( $_POST[ 'alsoInvoiceCustomer' ] ) ) {
	$customer->InvoiceName = trim( $_POST[ 'customerName' ] );
	$customer->InvoiceAddress1 = trim( $_POST[ 'customerAddress1' ] );
	$customer->InvoiceAddress2 = trim( $_POST[ 'customerAddress2' ] );
	$customer->InvoiceZip = trim( $_POST[ 'customerPostalCode' ] );
	$customer->InvoiceCity = trim( $_POST[ 'customerPostalCity' ] );
} else {
	$customer->InvoiceName = trim( $_POST[ 'invoiceName' ] );
	$customer->InvoiceAddress1 = trim( $_POST[ 'invoiceAddress1' ] );
	$customer->InvoiceAddress2 = trim( $_POST[ 'invoiceAddress2' ] );
	$customer->InvoiceZip = trim( $_POST[ 'invoicePostalCode' ] );
	$customer->InvoiceCity = trim( $_POST[ 'invoicePostalCity' ] );
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
	$matchingCustomer = $eduapi->GetCustomerV2( $edutoken, '', $ft->ToString(), false );
	if ( empty( $matchingCustomer ) ) {
		$customer->CustomerID = 0;
		$cres = $eduapi->SetCustomerV2( $edutoken, array ( $customer ) );
		$customer->CustomerID = $cres[ 0 ];
	} else {
		$customer = $matchingCustomer[ 0 ];
	}
} else if ( 'name-zip-match-overwrite' === $selectedMatch ) {
	$ft = new XFiltering();
	if ( $customer->CustomerID == 0 ) {
		if ( empty( $customer->InvoiceOrgnr ) ) {
			$ft = new XFiltering();
			$f = new XFilter( 'CustomerName', '=', $customer->CustomerName );
			$ft->AddItem( $f );
		} else {
			$ft = new XFiltering();
			$f = new XFilter( 'InvoiceOrgnr', '=', $customer->InvoiceOrgnr );
			$ft->AddItem( $f );
		}
		$f = new XFilter( 'Zip', '=', str_replace( " ", "", $customer->Zip ) );
		$ft->AddItem( $f );
	} else {
		$f = new XFilter( 'CustomerID', '=', $customer->CustomerID );
		$ft->AddItem( $f );
	}
	$matchingCustomer = $eduapi->GetCustomerV2( $edutoken, '', $ft->ToString(), false );
	if ( empty( $matchingCustomer ) ) {
		$customer->CustomerID = 0;
		$cres = $eduapi->SetCustomerV2( $edutoken, array ( $customer ) );
		$customer->CustomerID = $cres[ 0 ];
	} else {
		$customer->CustomerID = $matchingCustomer[ 0 ]->CustomerID;
		$eduapi->SetCustomerV2( $edutoken, array ( $customer ) );
	}
} else if ( 'no-match' === $selectedMatch ) {
	$customer->CustomerID = 0;
	$cres = $eduapi->SetCustomerV2( $edutoken, array ( $customer ) );
	$customer->CustomerID = $cres[ 0 ];
} else if ( 'no-match-new-overwrite' === $selectedMatch ) {
	if ( $contact->CustomerContactID == 0 ) {
		$customer->CustomerID = 0;
		$cres = $eduapi->SetCustomerV2( $edutoken, array ( $customer ) );
		$customer->CustomerID = $cres[ 0 ];
	} else {
		$ft = new XFiltering();
		$f = new XFilter( 'CustomerID', '=', $customer->CustomerID );
		$ft->AddItem( $f );
		$matchingCustomer = $eduapi->GetCustomerV2( $edutoken, '', $ft->ToString(), false );
		if ( empty( $matchingCustomer ) ) {
			$customer->CustomerID = 0;
			$cres = $eduapi->SetCustomerV2( $edutoken, array ( $customer ) );
			$customer->CustomerID = $cres[ 0 ];
		} else {
			$customer->CustomerID = $matchingCustomer[ 0 ]->CustomerID;
			$eduapi->SetCustomerV2( $edutoken, array ( $customer ) );
		}
	}
}

if ( 0 == $customer->CustomerID ) {
	die( 'Kunde inte skapa kundposten' );
} else {
	$so = new XSorting();
	$s = new XSort( 'SortIndex', 'ASC' );
	$so->AddItem( $s );

	$fo = new XFiltering();
	$f = new XFilter( 'ShowOnWeb', '=', 'true' );
	$fo->AddItem( $f );
	$f = new XFilter( 'AttributeOwnerTypeID', '=', 2 );
	$fo->AddItem( $f );
	$customerAttributes = $eduapi->GetAttribute( $edutoken, $so->ToString(), $fo->ToString() );

	$cmpArr = array ();

	foreach ( $customerAttributes as $attr ) {
		$fieldId = "edu-attr_" . $attr->AttributeID;
		if ( isset( $_POST[ $fieldId ] ) ) {
			$at = new CustomerAttribute();
			$at->CustomerID = $customer->CustomerID;
			$at->AttributeID = $attr->AttributeID;

			switch ( $attr->AttributeTypeID ) {
				case 1:
					$at->AttributeChecked = true;
					break;
				case 5:
					$alt = new AttributeAlternative();
					$alt->AttributeAlternativeID = $_POST[ $fieldId ];
					$at->AttributeAlternative[ ] = $alt;
					break;
				default:
					$at->AttributeValue = $_POST[ $fieldId ];
					break;
			}

			$cmpArr[ ] = $at;
		}
	}

	$res = $eduapi->SetCustomerAttribute( $edutoken, $cmpArr );
}

$contact->CustomerID = $customer->CustomerID;

if ( ! empty( $_POST[ 'contactFirstName' ] ) ) {
	$contact->ContactName = trim( $_POST[ 'contactFirstName' ] ) . ";" . trim( $_POST[ 'contactLastName' ] );
	$contact->Phone = trim( $_POST[ 'contactPhone' ] );
	$contact->Mobile = trim( $_POST[ 'contactMobile' ] );
	$contact->Email = trim( $_POST[ 'contactEmail' ] );
	if ( isset( $_POST[ 'contactCivReg' ] ) ) {
		$contact->CivicRegistrationNumber = trim( $_POST[ 'contactCivReg' ] );
	}
	if ( isset( $_POST[ 'contactPass' ] ) ) {
		$contact->Loginpass = $_POST[ 'contactPass' ];
	}
	$contact->CanLogin = 'true';
	$contact->PublicGroup = 'true';

	$ft = new XFiltering();
	$f = new XFilter( 'CustomerID', '=', $customer->CustomerID );
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
	$matchingContacts = $eduapi->GetCustomerContact( $edutoken, '', $ft->ToString(), false );
	if ( empty( $matchingContacts ) ) {
		$contact->CustomerContactID = 0;
		$contact->CustomerContactID = $eduapi->SetCustomerContact( $edutoken, array ( $contact ) )[ 0 ];
	} else {
		if ( 'name-zip-match-overwrite' === $selectedMatch ) {
			$contact->CustomerContactID = $matchingContacts[ 0 ]->CustomerContactID;
			$eduapi->SetCustomerContact( $edutoken, array ( $contact ) );
		} else {
			$contact = $matchingContacts[ 0 ];
			if ( isset( $_POST[ 'contactPass' ] ) && empty( $contact->Loginpass ) ) {
				$contact->Loginpass = $_POST[ 'contactPass' ];
				$eduapi->SetCustomerContact( $edutoken, array ( $contact ) );
			}
		}
	}

	$contact->ContactName = str_replace( ";", " ", $contact->ContactName );
}

if ( 0 !== $contact->CustomerContactID ) {
	$so = new XSorting();
	$s = new XSort( 'SortIndex', 'ASC' );
	$so->AddItem( $s );

	$fo = new XFiltering();
	$f = new XFilter( 'ShowOnWeb', '=', 'true' );
	$fo->AddItem( $f );
	$f = new XFilter( 'AttributeOwnerTypeID', '=', 4 );
	$fo->AddItem( $f );
	$contactAttributes = $eduapi->GetAttribute( $edutoken, $so->ToString(), $fo->ToString() );

	$cmpArr = array ();

	foreach ( $contactAttributes as $attr ) {
		$fieldId = "edu-attr_" . $attr->AttributeID;
		if ( isset( $_POST[ $fieldId ] ) ) {
			$at = new CustomerContactAttribute();
			$at->CustomerContactID = $contact->CustomerContactID;
			$at->AttributeID = $attr->AttributeID;

			switch ( $attr->AttributeTypeID ) {
				case 1:
					$at->AttributeChecked = true;
					break;
				case 5:
					$alt = new AttributeAlternative();
					$alt->AttributeAlternativeID = $_POST[ $fieldId ];
					$at->AttributeAlternative[ ] = $alt;
					break;
				default:
					$at->AttributeValue = $_POST[ $fieldId ];
					break;
			}

			$cmpArr[ ] = $at;
		}
	}

	$res = $eduapi->SetCustomerContactAttributes( $edutoken, $cmpArr );
}

$persons = array ();
$personEmail = array ();
if ( ! empty( $contact->Email ) && ! in_array( $contact->Email, $personEmail ) ) {
	$personEmail[ ] = $contact->Email;
}

$st = new XSorting();
$s = new XSort( 'StartDate', 'ASC' );
$st->AddItem( $s );
$s = new XSort( 'EndDate', 'ASC' );
$st->AddItem( $s );

$ft = new XFiltering();
$f = new XFilter( 'ParentEventID', '=', $eventId );
$ft->AddItem( $f );
$subEvents = $eduapi->GetSubEvent( $edutoken, $st->ToString(), $ft->ToString() );

$pArr = array ();

$so = new XSorting();
$s = new XSort( 'SortIndex', 'ASC' );
$so->AddItem( $s );

$fo = new XFiltering();
$f = new XFilter( 'ShowOnWeb', '=', 'true' );
$fo->AddItem( $f );
$f = new XFilter( 'AttributeOwnerTypeID', '=', 3 );
$fo->AddItem( $f );
$personAttributes = $eduapi->GetAttribute( $edutoken, $so->ToString(), $fo->ToString() );

foreach ( $_POST[ 'participantFirstName' ] as $key => $value ) {
	if ( "0" === $key ) {
		continue;
	}

	if ( ! empty( $_POST[ 'participantFirstName' ][ $key ] ) ) {
		$person = new SubEventPerson();
		$person->CustomerID = $customer->CustomerID;
		$person->PersonName = trim( $_POST[ 'participantFirstName' ][ $key ] ) . ";" . trim( $_POST[ 'participantLastName' ][ $key ] );
		$person->PersonEmail = trim( $_POST[ 'participantEmail' ][ $key ] );
		$person->PersonPhone = trim( $_POST[ 'participantPhone' ][ $key ] );
		$person->PersonMobile = trim( $_POST[ 'participantMobile' ][ $key ] );

		$ft = new XFiltering();
		$f = new XFilter( 'CustomerID', '=', $customer->CustomerID );
		$ft->AddItem( $f );
		$f = new XFilter( 'PersonName', '=', trim( str_replace( ';', ' ', $person->PersonName ) ) );
		$ft->AddItem( $f );
		$f = new XFilter( 'PersonEmail', '=', $person->PersonEmail );
		$ft->AddItem( $f );
		$matchingPersons = $eduapi->GetPerson( $edutoken, '', $ft->ToString(), false );
		if ( ! empty( $matchingPersons ) ) {
			$person = $matchingPersons[ 0 ];
		}

		$cmpArr = array ();

		foreach ( $personAttributes as $attr ) {
			$fieldId = "edu-attr_" . $attr->AttributeID;
			if ( isset( $_POST[ $fieldId ][ $key ] ) ) {
				$at = new Attribute();
				$at->AttributeID = $attr->AttributeID;

				switch ( $attr->AttributeTypeID ) {
					case 1:
						//$at->AttributeChecked = true;
						break;
					case 5:
						$alt = new AttributeAlternative();
						$alt->AttributeAlternativeID = $_POST[ $fieldId ][ $key ];
						$at->AttributeAlternative[ ] = $alt;
						break;
					default:
						$at->AttributeValue = $_POST[ $fieldId ][ $key ];
						break;
				}

				$cmpArr[ ] = $at;
			}
		}

		$person->Attribute = $cmpArr;

		$person->PersonEmail = trim( $_POST[ 'participantEmail' ][ $key ] );
		$person->PersonPhone = trim( $_POST[ 'participantPhone' ][ $key ] );
		$person->PersonMobile = trim( $_POST[ 'participantMobile' ][ $key ] );

		if ( isset( $_POST[ 'participantCivReg' ][ $key ] ) ) {
			$person->PersonCivicRegistrationNumber = trim( $_POST[ 'participantCivReg' ][ $key ] );
		}

		if ( isset( $_POST[ 'participantPriceName' ][ $key ] ) ) {
			$person->OccasionPriceNameLnkID = trim( $_POST[ 'participantPriceName' ][ $key ] );
		}

		foreach ( $subEvents as $subEvent ) {
			$fieldName = "participantSubEvent_" . $subEvent->EventID;
			if ( isset( $_POST[ $fieldName ][ $key ] ) ) {
				$fieldValue = $_POST[ $fieldName ][ $key ];
				$subEventInfo = new SubEventInfo();
				$subEventInfo->EventID = $fieldValue;
				$person->SubEvents[ ] = $subEventInfo;
			} else if ( $subEvent->MandatoryParticipation ) {
				$subEventInfo = new SubEventInfo();
				$subEventInfo->EventID = $subEvent->EventID;
				$person->SubEvents[ ] = $subEventInfo;
			}
		}

		$pArr[ ] = $person;

		if ( ! empty( $person->PersonEmail ) && ! in_array( $person->PersonEmail, $personEmail ) ) {
			$personEmail[ ] = $person->PersonEmail;
		}
	}
}

if ( isset( $_POST[ 'contactIsAlsoParticipant' ] ) && $contact->CustomerContactID > 0 ) {
	$person = new SubEventPerson();
	$person->CustomerID = $customer->CustomerID;
	$person->CustomerContactID = $contact->CustomerContactID;
	$person->PersonName = $contact->ContactName;
	$person->PersonEmail = $contact->Email;
	$person->PersonPhone = $contact->Phone;
	$person->PersonMobile = $contact->Mobile;
	$person->PersonCivicRegistrationNumber = $contact->CivicRegistrationNumber;
	$ft = new XFiltering();
	$f = new XFilter( 'CustomerID', '=', $customer->CustomerID );
	$ft->AddItem( $f );
	$f = new XFilter( 'CustomerContactID', '=', $contact->CustomerContactID );
	$ft->AddItem( $f );
	$matchingPersons = $eduapi->GetPerson( $edutoken, '', $ft->ToString(), false );
	if ( ! empty( $matchingPersons ) ) {
		$person = $matchingPersons[ 0 ];
	}

	$cmpArr = array ();

	foreach ( $personAttributes as $attr ) {
		$fieldId = "edu-attr_" . $attr->AttributeID . "-contact";
		if ( isset( $_POST[ $fieldId ] ) ) {
			$at = new Attribute();
			$at->AttributeID = $attr->AttributeID;

			switch ( $attr->AttributeTypeID ) {
				case 1:
					//$at->AttributeChecked = true;
					break;
				case 5:
					$alt = new AttributeAlternative();
					$alt->AttributeAlternativeID = $_POST[ $fieldId ];
					$at->AttributeAlternative[ ] = $alt;
					break;
				default:
					$at->AttributeValue = $_POST[ $fieldId ];
					break;
			}

			$cmpArr[ ] = $at;
		}
	}

	$person->Attribute = $cmpArr;

	if ( isset( $_POST[ 'contactCivReg' ] ) ) {
		$person->PersonCivicRegistrationNumber = trim( $_POST[ 'contactCivReg' ] );
	}

	if ( isset( $_POST[ 'contactPriceName' ] ) ) {
		$person->OccasionPriceNameLnkID = trim( $_POST[ 'contactPriceName' ] );
	}
	$person->SubEvents = array ();
	foreach ( $subEvents as $subEvent ) {
		$fieldName = "contactSubEvent_" . $subEvent->EventID;
		if ( isset( $_POST[ $fieldName ] ) ) {
			$fieldValue = $_POST[ $fieldName ];
			$subEventInfo = new SubEventInfo();
			$subEventInfo->EventID = $fieldValue;
			$person->SubEvents[ ] = $subEventInfo;
		} else if ( $subEvent->MandatoryParticipation ) {
			$subEventInfo = new SubEventInfo();
			$subEventInfo->EventID = $subEvent->EventID;
			$person->SubEvents[ ] = $subEventInfo;
		}
	}

	$pArr[ ] = $person;
}

if ( ! empty( $pArr ) ) {
	$bi = new BookingInfoSubEvent();
	$bi->EventID = $eventId;
	$bi->CustomerID = $customer->CustomerID;
	$bi->CustomerContactID = $contact->CustomerContactID;
	$bi->SubEventPersons = $pArr;
	$bi->PurchaseOrderNumber = $purchaseOrderNumber;
	if ( isset( $_POST[ 'edu-pricename' ] ) ) {
		$bi->OccasionPriceNameLnkID = $_POST[ 'edu-pricename' ];
	}

	if ( isset( $_POST[ 'edu-discountCodeID' ] ) && $_POST[ 'edu-discountCodeID' ] != "0" ) {
		$bi->CouponID = $_POST[ 'edu-discountCodeID' ];
	}

	$bi->CustomerReference = ( ! empty( $_POST[ 'invoiceReference' ] ) ? trim( $_POST[ 'invoiceReference' ] ) : trim( str_replace( ';', ' ', $contact->ContactName ) ) );
	$eventCustomerLnkID = $eduapi->CreateSubEventBooking(
		$edutoken,
		$bi
	);

	$answers = array ();
	foreach ( $_POST as $input => $value ) {
		if ( strpos( $input, "question_" ) !== false ) {
			$question = explode( '_', $input );
			$answerID = $question[ 1 ];
			$type = $question[ 2 ];

			switch ( $type ) {
				case 'radio':
				case 'check':
				case 'dropdown':
					$answerID = $value;
					break;
			}
			if ( $type === "time" ) {
				$answers[ $answerID ][ 'AnswerTime' ] = trim( $value );
			} else {
				$answers[ $answerID ] =
				array (
					'AnswerID' => $answerID,
					'AnswerText' => trim( $value ),
					'EventID' => $eventId,
					'EventCustomerLnkID' => $eventCustomerLnkID
				);
			}
		}
	}

	// Spara alla frågor till eventcustomeranswerv2
	if ( ! empty( $answers ) ) {
		$sanswers = array ();
		foreach ( $answers as $answer ) {
			$sanswers[ ] = $answer;
		}
		$eduapi->SetEventCustomerAnswerV2( $edutoken, $sanswers );
	}

	$ai = $eduapi->GetAccountInfo( $edutoken )[ 0 ];
	$senderEmail = $ai->Email;
	if ( empty( $senderEmail ) ) {
		$senderEmail = 'no-reply@legaonline.se';
	}
	if ( ! empty( $personEmail ) ) {
		$eduapi->SendConfirmationEmail( $edutoken, $eventCustomerLnkID, $senderEmail, $personEmail );
	}

	$_SESSION[ 'eduadmin-printJS' ] = true;

	$user = $_SESSION[ 'eduadmin-loginUser' ];
	$jsEncContact = json_encode( $contact );
	@$user->Contact = json_decode( $jsEncContact );

	$jsEncCustomer = json_encode( $customer );
	@$user->Customer = json_decode( $jsEncCustomer );
	$_SESSION[ 'eduadmin-loginUser' ] = $user;

	$bookingInfo = array(
		'eventCustomerLnkId' => $eventCustomerLnkID,
		'eventId'            => $eventId,
		'customerId'         => $customer->CustomerID,
		'contactId'          => $contact->CustomerContactID,
	);


}