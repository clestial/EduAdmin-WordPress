<div class="contactView">
	<h2><?php edu_e("Contact information"); ?></h2>
	<label>
		<div class="inputLabel">
			<?php edu_e("Contact name"); ?>
		</div>
		<div class="inputHolder">
			<input type="text" style="width: 48%; display: inline;" required onchange="eduBookingView.ContactAsParticipant();" id="edu-contactFirstName" name="contactFirstName" placeholder="<?php edu_e("Contact first name"); ?>" value="<?php echo @esc_attr(explode(' ', $contact->ContactName)[0]); ?>" />
			<input type="text" style="width: 48%; display: inline;" required onchange="eduBookingView.ContactAsParticipant();" id="edu-contactLastName" name="contactLastName" placeholder="<?php edu_e("Contact surname"); ?>" value="<?php echo @esc_attr(str_replace(explode(' ', $contact->ContactName)[0], '', $contact->ContactName)); ?>" />
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php edu_e("E-mail address"); ?>
		</div>
		<div class="inputHolder">
			<input type="email" id="edu-contactEmail" required name="contactEmail" onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php edu_e("E-mail address"); ?>" value="<?php echo @esc_attr($contact->Email); ?>" />
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php edu_e("Phone number"); ?>
		</div>
		<div class="inputHolder">
			<input type="tel" id="edu-contactPhone" name="contactPhone" onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php edu_e("Phone number"); ?>" value="<?php echo @esc_attr($contact->Phone); ?>" />
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php edu_e("Mobile number"); ?>
		</div>
		<div class="inputHolder">
			<input type="tel" id="edu-contactMobile" name="contactMobile" onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php edu_e("Mobile number"); ?>" value="<?php echo @esc_attr($contact->Mobile); ?>" />
		</div>
	</label>
	<?php $selectedLoginField = get_option('eduadmin-loginField', 'Email'); ?>
	<?php if($selectedCourse->RequireCivicRegistrationNumber || $selectedLoginField == 'CivicRegistrationNumber') { ?>
	<label>
		<div class="inputLabel">
			<?php edu_e("Civic Registration Number"); ?>
		</div>
		<div class="inputHolder">
			<input type="text" id="edu-contactCivReg" required name="contactCivReg" pattern="(\d{2,4})-?(\d{2,2})-?(\d{2,2})-?(\d{4,4})" class="eduadmin-civicRegNo" onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php edu_e("Civic Registration Number"); ?>" value="<?php echo @esc_attr($contact->CivicRegistrationNumber); ?>" />
		</div>
	</label>
	<?php } ?>
	<?php if(get_option('eduadmin-useLogin', false) && empty($contact->Loginpass)) { ?>
	<label>
		<div class="inputLabel">
			<?php edu_e("Please enter a password"); ?>
		</div>
		<div class="inputHolder">
			<input type="password" required name="contactPass" placeholder="<?php edu_e("Please enter a password"); ?>" />
		</div>
	</label>
	<?php } ?>
	<div class="edu-modal warning" id="edu-warning-participants-contact">
		<?php edu_e("You cannot add any more participants."); ?>
	</div>
</div>
<?php
$noInvoiceFreeEvents = get_option('eduadmin-noInvoiceFreeEvents', false);
if(!$noInvoiceFreeEvents || ($noInvoiceFreeEvents && $firstPrice->Price > 0)) {
?>
<div class="customerView">
	<label>
		<div class="inputLabel">
			<?php edu_e("Address 1"); ?>
		</div>
		<div class="inputHolder">
			<input type="text" name="customerAddress1" placeholder="<?php edu_e("Address 1"); ?>" value="<?php echo @esc_attr($customer->Address1); ?>" />
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php edu_e("Address 2"); ?>
		</div>
		<div class="inputHolder">
			<input type="text" name="customerAddress2" placeholder="<?php edu_e("Address 2"); ?>" value="<?php echo @esc_attr($customer->Address2); ?>" />
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php edu_e("Postal code"); ?>
		</div>
		<div class="inputHolder">
			<input type="text" name="customerPostalCode" placeholder="<?php edu_e("Postal code"); ?>" value="<?php echo @esc_attr($customer->Zip); ?>" />
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php edu_e("Postal city"); ?>
		</div>
		<div class="inputHolder">
			<input type="text" name="customerPostalCity" placeholder="<?php edu_e("Postal city"); ?>" value="<?php echo @esc_attr($customer->City); ?>" />
		</div>
	</label>
</div>

<div class="invoiceView__wrapper">
	<label>
		<div class="inputLabel">
			<?php edu_e("Invoice e-mail address"); ?>
		</div>
		<div class="inputHolder">
			<input type="text" name="invoiceEmail" placeholder="<?php edu_e("Invoice e-mail address"); ?>" value="<?php echo @esc_attr($customerInvoiceEmail); ?>" />
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php edu_e("Invoice reference"); ?>
		</div>
		<div class="inputHolder">
			<input type="text" name="invoiceReference" placeholder="<?php edu_e("Invoice reference"); ?>" value="<?php echo @esc_attr($customer->CustomerReference); ?>" />
		</div>
	</label>
	<label>
		<div class="inputHolder alsoInvoiceCustomer">
			<input type="checkbox" id="alsoInvoiceCustomer" name="alsoInvoiceCustomer" value="true" onchange="eduBookingView.UpdateInvoiceCustomer();" />
			<label class="inline-checkbox" for="alsoInvoiceCustomer"></label>
			<?php edu_e("Use other information for invoicing"); ?>
		</div>
	</label>

	<div id="invoiceView" class="invoiceView" style="display: none;">
		<h2><?php edu_e("Invoice information"); ?></h2>
		<label>
			<div class="inputLabel">
				<?php edu_e("Customer name"); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="invoiceName" placeholder="<?php edu_e("Customer name"); ?>" value="<?php echo @esc_attr($customer->InvoiceName); ?>" />
			</div>
		</label>
		<label>
			<div class="inputLabel">
				<?php edu_e("Address 1"); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="invoiceAddress1" placeholder="<?php edu_e("Address 1"); ?>" value="<?php echo @esc_attr($customer->InvoiceAddress1); ?>" />
			</div>
		</label>
		<label>
			<div class="inputLabel">
				<?php edu_e("Address 2"); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="invoiceAddress2" placeholder="<?php edu_e("Address 2"); ?>" value="<?php echo @esc_attr($customer->InvoiceAddress2); ?>" />
			</div>
		</label>
		<label>
			<div class="inputLabel">
				<?php edu_e("Postal code"); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="invoicePostalCode" placeholder="<?php edu_e("Postal code"); ?>" value="<?php echo @esc_attr($customer->InvoiceZip); ?>" />
			</div>
		</label>
		<label>
			<div class="inputLabel">
				<?php edu_e("Postal city"); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="invoicePostalCity" placeholder="<?php edu_e("Postal city"); ?>" value="<?php echo @esc_attr($customer->InvoiceCity); ?>" />
			</div>
		</label>
	</div>
</div>
<?php } ?>
<div class="attributeView">
<?php
	$so = new XSorting();
	$s = new XSort('SortIndex', 'ASC');
	$so->AddItem($s);

	$fo = new XFiltering();
	$f = new XFilter('ShowOnWeb', '=', 'true');
	$fo->AddItem($f);
	$f = new XFilter('AttributeOwnerTypeID', '=', 4);
	$fo->AddItem($f);
	$contactAttributes = $eduapi->GetAttribute($edutoken, $so->ToString(), $fo->ToString());

	$db = array();

	if(isset($contact) && isset($contact->CustomerContactID))
	{
		if($contact->CustomerContactID != 0) {
			$fo = new XFiltering();
			$f = new XFilter('CustomerContactID', '=', $contact->CustomerContactID);
			$fo->AddItem($f);
			$db = $eduapi->GetCustomerContactAttribute($edutoken, '', $fo->ToString());
		}
	}

	foreach($contactAttributes as $attr)
	{
		$data = null;
		foreach($db as $d)
		{
			if($d->AttributeID == $attr->AttributeID) {
				switch($d->AttributeTypeID) {
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
		renderAttribute($attr, false, "", $data);
	}

	$so = new XSorting();
	$s = new XSort('SortIndex', 'ASC');
	$so->AddItem($s);

	$fo = new XFiltering();
	$f = new XFilter('ShowOnWeb', '=', 'true');
	$fo->AddItem($f);
	$f = new XFilter('AttributeOwnerTypeID', '=', 2);
	$fo->AddItem($f);
	$contactAttributes = $eduapi->GetAttribute($edutoken, $so->ToString(), $fo->ToString());

	$db = array();
	if(isset($customer) && isset($customer->CustomerID))
	{
		if($customer->CustomerID != 0) {
			$fo = new XFiltering();
			$f = new XFilter('CustomerID', '=', $customer->CustomerID);
			$fo->AddItem($f);
			$db = $eduapi->GetCustomerAttribute($edutoken, '', $fo->ToString());
		}
	}

	foreach($contactAttributes as $attr)
	{
		$data = null;
		foreach($db as $d)
		{
			if($d->AttributeID == $attr->AttributeID) {
				switch($d->AttributeTypeID) {
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
		renderAttribute($attr, false, "", $data);
	}

	$so = new XSorting();
	$s = new XSort('SortIndex', 'ASC');
	$so->AddItem($s);

	$fo = new XFiltering();
	$f = new XFilter('ShowOnWeb', '=', 'true');
	$fo->AddItem($f);
	$f = new XFilter('AttributeOwnerTypeID', '=', 3);
	$fo->AddItem($f);
	$contactAttributes = $eduapi->GetAttribute($edutoken, $so->ToString(), $fo->ToString());

	$db = array();
	/*if($contact->PersonID != 0) {
		$fo = new XFiltering();
		$f = new XFilter('PersonID', '=', $contact->PersonID);
		$fo->AddItem($f);
		$db = $eduapi->GetPersonAttribute($edutoken, '', $fo->ToString());
	}*/

	foreach($contactAttributes as $attr)
	{
		$data = null;
		foreach($db as $d)
		{
			if($d->AttributeID == $attr->AttributeID) {
				switch($d->AttributeTypeID) {
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
		renderAttribute($attr, false, "contact", $data);
	}
	?>
	<div class="participantItem contactPerson">
		<?php
			if(count($subEvents) > 0) {
				echo "<h4>" . edu__("Sub events") . "</h4>\n";
				foreach($subEvents as $subEvent)
				{
					if(isset($sePrice[$subEvent->OccasionID]) && count($sePrice[$subEvent->OccasionID]) > 0) {
						$s = current($sePrice[$subEvent->OccasionID])->Price;
					} else {
						$s = 0;
					}
					// PriceNameVat
					echo "<label>".
					"<input class=\"subEventCheckBox\" data-price=\"" . $s . "\" onchange=\"eduBookingView.UpdatePrice();\" " .
						"name=\"contactSubEvent_" . $subEvent->EventID . "\" " .
						"type=\"checkbox\"" .
						($subEvent->SelectedByDefault == true || $subEvent->MandatoryParticipation == true ? " checked=\"checked\"" : "") .
						($subEvent->MandatoryParticipation == true ? " disabled=\"disabled\"" : "") .
					" value=\"" . $subEvent->EventID . "\"> " .
						$subEvent->Description .
						($hideSubEventDateInfo ? "" : " (" . date("d/m H:i", strtotime($subEvent->StartDate)) . " - " . date("d/m H:i", strtotime($subEvent->EndDate)) . ") ") .
						($s > 0  ? " <i class=\"priceLabel\">" . convertToMoney($s) . "</i>" : "") .
					"</label>\n";
				}
			}
		?>
	</div>
</div>
