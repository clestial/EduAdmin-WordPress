<?php
$blockEditIfLoggedIn = get_option( 'eduadmin-blockEditIfLoggedIn', true );
$__block             = ( $blockEditIfLoggedIn && $contact->CustomerContactID != 0 );
?>
<div class="contactView">
	<h2><?php _e( "Contact information", 'eduadmin-booking' ); ?></h2>
	<label>
		<div class="inputLabel">
			<?php _e( "Contact name", 'eduadmin-booking' ); ?>
		</div>
		<div class="inputHolder"><input type="text"
				<?php echo( $__block ? " readonly" : "" ); ?>
				                        required onchange="eduBookingView.ContactAsParticipant();"
				                        id="edu-contactFirstName" name="contactFirstName" class="first-name"
				                        placeholder="<?php _e( "Contact first name", 'eduadmin-booking' ); ?>"
				                        value="<?php echo @esc_attr( explode( ' ', $contact->ContactName )[0] ); ?>"/><input
					type="text" <?php echo( $__block ? " readonly" : "" ); ?>
					required onchange="eduBookingView.ContactAsParticipant();" id="edu-contactLastName"
					class="last-name"
					name="contactLastName" placeholder="<?php _e( "Contact surname", 'eduadmin-booking' ); ?>"
					value="<?php echo @esc_attr( str_replace( explode( ' ', $contact->ContactName )[0], '', $contact->ContactName ) ); ?>"/>
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php _e( "E-mail address", 'eduadmin-booking' ); ?>
		</div>
		<div class="inputHolder">
			<input type="email" id="edu-contactEmail" required
			       name="contactEmail"<?php echo( $__block ? " readonly" : "" ); ?>
			       onchange="eduBookingView.ContactAsParticipant();"
			       placeholder="<?php _e( "E-mail address", 'eduadmin-booking' ); ?>"
			       value="<?php echo @esc_attr( $contact->Email ); ?>"/>
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php _e( "Phone number", 'eduadmin-booking' ); ?>
		</div>
		<div class="inputHolder">
			<input type="tel" id="edu-contactPhone" name="contactPhone"
			       onchange="eduBookingView.ContactAsParticipant();"
			       placeholder="<?php _e( "Phone number", 'eduadmin-booking' ); ?>"
			       value="<?php echo @esc_attr( $contact->Phone ); ?>"/>
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php _e( "Mobile number", 'eduadmin-booking' ); ?>
		</div>
		<div class="inputHolder">
			<input type="tel" id="edu-contactMobile" name="contactMobile"
			       onchange="eduBookingView.ContactAsParticipant();"
			       placeholder="<?php _e( "Mobile number", 'eduadmin-booking' ); ?>"
			       value="<?php echo @esc_attr( $contact->Mobile ); ?>"/>
		</div>
	</label>
	<?php $selectedLoginField = get_option( 'eduadmin-loginField', 'Email' ); ?>
	<?php if ( $selectedCourse->RequireCivicRegistrationNumber || $selectedLoginField == 'CivicRegistrationNumber' ) { ?>
		<label>
			<div class="inputLabel">
				<?php _e( "Civic Registration Number", 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" id="edu-contactCivReg" required name="contactCivReg"
				       pattern="(\d{2,4})-?(\d{2,2})-?(\d{2,2})-?(\d{4,4})" class="eduadmin-civicRegNo"
				       onchange="eduBookingView.ContactAsParticipant();"
				       placeholder="<?php _e( "Civic Registration Number", 'eduadmin-booking' ); ?>"
				       value="<?php echo @esc_attr( $contact->CivicRegistrationNumber ); ?>"/>
			</div>
		</label>
	<?php } ?>
	<?php if ( get_option( 'eduadmin-useLogin', false ) && empty( $contact->Loginpass ) ) { ?>
		<label>
			<div class="inputLabel">
				<?php _e( "Please enter a password", 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="password" required name="contactPass"
				       placeholder="<?php _e( "Please enter a password", 'eduadmin-booking' ); ?>"/>
			</div>
		</label>
	<?php } ?>
	<div class="edu-modal warning" id="edu-warning-participants-contact">
		<?php _e( "You cannot add any more participants.", 'eduadmin-booking' ); ?>
	</div>
</div>
<?php
if ( ! $noInvoiceFreeEvents || ( $noInvoiceFreeEvents && $firstPrice->Price > 0 ) ) {
	?>
	<div class="customerView">
		<label>
			<div class="inputLabel">
				<?php _e( "Address 1", 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="customerAddress1"
				       placeholder="<?php _e( "Address 1", 'eduadmin-booking' ); ?>"
				       value="<?php echo @esc_attr( $customer->Address1 ); ?>"/>
			</div>
		</label>
		<label>
			<div class="inputLabel">
				<?php _e( "Address 2", 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="customerAddress2"
				       placeholder="<?php _e( "Address 2", 'eduadmin-booking' ); ?>"
				       value="<?php echo @esc_attr( $customer->Address2 ); ?>"/>
			</div>
		</label>
		<label>
			<div class="inputLabel">
				<?php _e( "Postal code", 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="customerPostalCode"
				       placeholder="<?php _e( "Postal code", 'eduadmin-booking' ); ?>"
				       value="<?php echo @esc_attr( $customer->Zip ); ?>"/>
			</div>
		</label>
		<label>
			<div class="inputLabel">
				<?php _e( "Postal city", 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="customerPostalCity"
				       placeholder="<?php _e( "Postal city", 'eduadmin-booking' ); ?>"
				       value="<?php echo @esc_attr( $customer->City ); ?>"/>
			</div>
		</label>
	</div>

	<div class="invoiceView__wrapper">
		<?php if ( $showInvoiceEmail ) { ?>
			<label>
				<div class="inputLabel">
					<?php _e( "Invoice e-mail address", 'eduadmin-booking' ); ?>
				</div>
				<div class="inputHolder">
					<input type="text" name="invoiceEmail"
					       placeholder="<?php _e( "Invoice e-mail address", 'eduadmin-booking' ); ?>"
					       value="<?php echo @esc_attr( $customerInvoiceEmail ); ?>"/>
				</div>
			</label>
		<?php } ?>
		<label>
			<div class="inputLabel">
				<?php _e( "Invoice reference", 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="invoiceReference"
				       placeholder="<?php _e( "Invoice reference", 'eduadmin-booking' ); ?>"
				       value="<?php echo @esc_attr( $customer->CustomerReference ); ?>"/>
			</div>
		</label>
		<label style="<?php echo $forceShowInvoiceInformation ? "display: none;" : "" ?>">
			<div class="inputHolder alsoInvoiceCustomer">
				<input type="checkbox" id="alsoInvoiceCustomer" name="alsoInvoiceCustomer" value="true"
				       onchange="eduBookingView.UpdateInvoiceCustomer(this);"
					<?php echo $forceShowInvoiceInformation ? "checked" : "" ?>/>
				<label class="inline-checkbox" for="alsoInvoiceCustomer"></label>
				<?php _e( "Use other information for invoicing", 'eduadmin-booking' ); ?>
			</div>
		</label>

		<div id="invoiceView" class="invoiceView"
		     style="<?php echo( $forceShowInvoiceInformation ? 'display: block;' : 'display: none;' ); ?>">
			<h2><?php _e( "Invoice information", 'eduadmin-booking' ); ?></h2>
			<label>
				<div class="inputLabel">
					<?php _e( "Customer name", 'eduadmin-booking' ); ?>
				</div>
				<div class="inputHolder">
					<input type="text" name="invoiceName"
					       placeholder="<?php _e( "Customer name", 'eduadmin-booking' ); ?>"
					       value="<?php echo @esc_attr( $customer->InvoiceName ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel">
					<?php _e( "Address 1", 'eduadmin-booking' ); ?>
				</div>
				<div class="inputHolder">
					<input type="text" name="invoiceAddress1"
					       placeholder="<?php _e( "Address 1", 'eduadmin-booking' ); ?>"
					       value="<?php echo @esc_attr( $customer->InvoiceAddress1 ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel">
					<?php _e( "Address 2", 'eduadmin-booking' ); ?>
				</div>
				<div class="inputHolder">
					<input type="text" name="invoiceAddress2"
					       placeholder="<?php _e( "Address 2", 'eduadmin-booking' ); ?>"
					       value="<?php echo @esc_attr( $customer->InvoiceAddress2 ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel">
					<?php _e( "Postal code", 'eduadmin-booking' ); ?>
				</div>
				<div class="inputHolder">
					<input type="text" name="invoicePostalCode"
					       placeholder="<?php _e( "Postal code", 'eduadmin-booking' ); ?>"
					       value="<?php echo @esc_attr( $customer->InvoiceZip ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel">
					<?php _e( "Postal city", 'eduadmin-booking' ); ?>
				</div>
				<div class="inputHolder">
					<input type="text" name="invoicePostalCity"
					       placeholder="<?php _e( "Postal city", 'eduadmin-booking' ); ?>"
					       value="<?php echo @esc_attr( $customer->InvoiceCity ); ?>"/>
				</div>
			</label>
		</div>
	</div>
<?php } ?>
<div class="attributeView">
	<?php
	$so = new XSorting();
	$s  = new XSort( 'SortIndex', 'ASC' );
	$so->AddItem( $s );

	$fo = new XFiltering();
	$f  = new XFilter( 'ShowOnWeb', '=', 'true' );
	$fo->AddItem( $f );
	$f = new XFilter( 'AttributeOwnerTypeID', '=', 4 );
	$fo->AddItem( $f );
	$contactAttributes = EDU()->api->GetAttribute( EDU()->get_token(), $so->ToString(), $fo->ToString() );

	$db = array();

	if ( isset( $contact ) && isset( $contact->CustomerContactID ) ) {
		if ( $contact->CustomerContactID != 0 ) {
			$fo = new XFiltering();
			$f  = new XFilter( 'CustomerContactID', '=', $contact->CustomerContactID );
			$fo->AddItem( $f );
			$db = EDU()->api->GetCustomerContactAttribute( EDU()->get_token(), '', $fo->ToString() );
		}
	}

	foreach ( $contactAttributes as $attr ) {
		$data = null;
		foreach ( $db as $d ) {
			if ( $d->AttributeID == $attr->AttributeID ) {
				switch ( $d->AttributeTypeID ) {
					case 1:
						$data = $d->AttributeChecked;
						break;
					case 5:
						$data = $d->AttributeAlternative->AttributeAlternativeID;
						break;
					default:
						$data = $d->AttributeValue;
						break;
				}
				break;
			}
		}
		renderAttribute( $attr, false, "", $data );
	}

	$so = new XSorting();
	$s  = new XSort( 'SortIndex', 'ASC' );
	$so->AddItem( $s );

	$fo = new XFiltering();
	$f  = new XFilter( 'ShowOnWeb', '=', 'true' );
	$fo->AddItem( $f );
	$f = new XFilter( 'AttributeOwnerTypeID', '=', 2 );
	$fo->AddItem( $f );
	$contactAttributes = EDU()->api->GetAttribute( EDU()->get_token(), $so->ToString(), $fo->ToString() );

	$db = array();
	if ( isset( $customer ) && isset( $customer->CustomerID ) ) {
		if ( $customer->CustomerID != 0 ) {
			$fo = new XFiltering();
			$f  = new XFilter( 'CustomerID', '=', $customer->CustomerID );
			$fo->AddItem( $f );
			$db = EDU()->api->GetCustomerAttribute( EDU()->get_token(), '', $fo->ToString() );
		}
	}

	foreach ( $contactAttributes as $attr ) {
		$data = null;
		foreach ( $db as $d ) {
			if ( $d->AttributeID == $attr->AttributeID ) {
				switch ( $d->AttributeTypeID ) {
					case 1:
						$data = $d->AttributeChecked;
						break;
					case 5:
						$data = $d->AttributeAlternative->AttributeAlternativeID;
						break;
					default:
						$data = $d->AttributeValue;
						break;
				}
				break;
			}
		}
		renderAttribute( $attr, false, "", $data );
	}

	$so = new XSorting();
	$s  = new XSort( 'SortIndex', 'ASC' );
	$so->AddItem( $s );

	$fo = new XFiltering();
	$f  = new XFilter( 'ShowOnWeb', '=', 'true' );
	$fo->AddItem( $f );
	$f = new XFilter( 'AttributeOwnerTypeID', '=', 3 );
	$fo->AddItem( $f );
	$contactAttributes = EDU()->api->GetAttribute( EDU()->get_token(), $so->ToString(), $fo->ToString() );

	$db = array();

	foreach ( $contactAttributes as $attr ) {
		$data = null;
		foreach ( $db as $d ) {
			if ( $d->AttributeID == $attr->AttributeID ) {
				switch ( $d->AttributeTypeID ) {
					case 1:
						$data = $d->AttributeChecked;
						break;
					case 5:
						$data = $d->AttributeAlternative->AttributeAlternativeID;
						break;
					default:
						$data = $d->AttributeValue;
						break;
				}
				break;
			}
		}
		renderAttribute( $attr, false, "contact", $data );
	}
	?>
	<div class="participantItem contactPerson">
		<?php
		if ( count( $subEvents ) > 0 && $sePrice != null ) {
			echo "<h4>" . __( "Sub events", 'eduadmin-booking' ) . "</h4>\n";
			foreach ( $subEvents as $subEvent ) {
				if ( isset( $sePrice[ $subEvent->OccasionID ] ) && count( $sePrice[ $subEvent->OccasionID ] ) > 0 ) {
					$s = current( $sePrice[ $subEvent->OccasionID ] )->Price;

					// PriceNameVat
					echo "<label>" .
					     "<input class=\"subEventCheckBox\" data-price=\"" . $s . "\" onchange=\"eduBookingView.UpdatePrice();\" " .
					     "name=\"contactSubEvent_" . $subEvent->EventID . "\" " .
					     "type=\"checkbox\"" .
					     ( $subEvent->SelectedByDefault == true || $subEvent->MandatoryParticipation == true ? " checked=\"checked\"" : "" ) .
					     ( $subEvent->MandatoryParticipation == true ? " disabled=\"disabled\"" : "" ) .
					     " value=\"" . $subEvent->EventID . "\"> " .
					     $subEvent->Description .
					     ( $hideSubEventDateInfo ? "" : " (" . date( "d/m H:i", strtotime( $subEvent->StartDate ) ) . " - " . date( "d/m H:i", strtotime( $subEvent->EndDate ) ) . ") " ) .
					     ( $s > 0 ? " <i class=\"priceLabel\">" . convert_to_money( $s ) . "</i>" : "" ) .
					     "</label>\n";
				}
			}
		}
		?>
	</div>
</div>
