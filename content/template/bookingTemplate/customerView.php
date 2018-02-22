<?php
if ( ! empty( $customer->BillingInfo ) ) {
	$billing_customer = $customer->BillingInfo[0];
} else {
	$billing_customer = new EduAdmin_Data_BillingInfo();
}
?>
<div class="customerView">
	<h2><?php esc_html_e( 'Customer information', 'eduadmin-booking' ); ?></h2>
	<label>
		<div class="inputLabel">
			<?php esc_html_e( 'Customer name', 'eduadmin-booking' ); ?>
		</div>
		<div class="inputHolder">
			<input type="text" required name="customerName" autocomplete="organization" placeholder="<?php esc_attr_e( 'Customer name', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $customer->CustomerName ); ?>"/>
		</div>
	</label>
	<?php
	if ( empty( $no_invoice_free_events ) || ( $no_invoice_free_events && $first_price->Price > 0 ) ) {
		?>
		<label>
			<div class="inputLabel">
				<?php esc_html_e( 'Org.No.', 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="customerVatNo" placeholder="<?php esc_attr_e( 'Org.No.', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $customer->OrganisationNumber ); ?>"/>
			</div>
		</label>        <label>
			<div class="inputLabel">
				<?php esc_html_e( 'Address 1', 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="customerAddress1" placeholder="<?php esc_attr_e( 'Address 1', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $customer->Address ); ?>"/>
			</div>
		</label>        <label>
			<div class="inputLabel">
				<?php esc_html_e( 'Address 2', 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="customerAddress2" placeholder="<?php esc_attr_e( 'Address 2', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $customer->Address2 ); ?>"/>
			</div>
		</label>        <label>
			<div class="inputLabel">
				<?php esc_html_e( 'Postal code', 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="customerPostalCode" placeholder="<?php esc_attr_e( 'Postal code', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $customer->Zip ); ?>"/>
			</div>
		</label>        <label>
			<div class="inputLabel">
				<?php esc_html_e( 'Postal city', 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="customerPostalCity" placeholder="<?php esc_attr_e( 'Postal city', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $customer->City ); ?>"/>
			</div>
		</label>        <label>
			<div class="inputLabel">
				<?php esc_html_e( 'E-mail address', 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="customerEmail" placeholder="<?php esc_attr_e( 'E-mail address', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $customer->Email ); ?>"/>
			</div>
		</label>
		<div id="invoiceView" class="invoiceView" style="<?php echo( $force_show_invoice_information ? 'display: block;' : 'display: none;' ); ?>">
			<h2><?php esc_html_e( 'Invoice information', 'eduadmin-booking' ); ?></h2>
			<label>
				<div class="inputLabel">
					<?php esc_html_e( 'Customer name', 'eduadmin-booking' ); ?>
				</div>
				<div class="inputHolder">
					<input type="text" name="invoiceName" placeholder="<?php esc_attr_e( 'Customer name', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $billing_customer->CustomerName ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel">
					<?php esc_html_e( 'Address 1', 'eduadmin-booking' ); ?>
				</div>
				<div class="inputHolder">
					<input type="text" name="invoiceAddress1" placeholder="<?php esc_attr_e( 'Address 1', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $billing_customer->Address ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel">
					<?php esc_html_e( 'Address 2', 'eduadmin-booking' ); ?>
				</div>
				<div class="inputHolder">
					<input type="text" name="invoiceAddress2" placeholder="<?php esc_attr_e( 'Address 2', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $billing_customer->Address2 ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel">
					<?php esc_html_e( 'Postal code', 'eduadmin-booking' ); ?>
				</div>
				<div class="inputHolder">
					<input type="text" name="invoicePostalCode" placeholder="<?php esc_attr_e( 'Postal code', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $billing_customer->Zip ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel">
					<?php esc_html_e( 'Postal city', 'eduadmin-booking' ); ?>
				</div>
				<div class="inputHolder">
					<input type="text" name="invoicePostalCity" placeholder="<?php esc_attr_e( 'Postal city', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $billing_customer->City ); ?>"/>
				</div>
			</label>
		</div>
		<?php if ( $show_invoice_email ) { ?>
			<label>
				<div class="inputLabel">
					<?php esc_html_e( 'Invoice e-mail address', 'eduadmin-booking' ); ?>
				</div>
				<div class="inputHolder">
					<input type="text" name="invoiceEmail" placeholder="<?php esc_attr_e( 'Invoice e-mail address', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $billing_customer->Email ); ?>"/>
				</div>
			</label>
		<?php } ?>
		<label>
			<div class="inputLabel">
				<?php esc_html_e( 'Invoice reference', 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="invoiceReference" placeholder="<?php esc_attr_e( 'Invoice reference', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $billing_customer->SellerReference ); ?>"/>
			</div>
		</label>        <label>
			<div class="inputLabel">
				<?php esc_html_e( 'Purchase order number', 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="purchaseOrderNumber" placeholder="<?php esc_attr_e( 'Purchase order number', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $purchase_order_number ); ?>"/>
			</div>
		</label>
		<?php
	}

	$customer_custom_fields = EDUAPI()->OData->CustomFields->Search(
		null,
		'ShowOnWeb and CustomFieldOwner eq \'Customer\'',
		'Alternatives'
	)['value'];

	foreach ( $customer_custom_fields as $custom_field ) {
		$data = null;
		foreach ( $customer->CustomFields as $cf ) {
			if ( $cf->CustomFieldId === $custom_field['CustomFieldId'] ) {
				switch ( $cf->CustomFieldType ) {
					case 'Checkbox':
						$data = $cf->CustomFieldChecked;
						break;
					case 'Dropdown':
						$data = $cf->CustomFieldAlternativeId;
						break;
					default:
						$data = $cf->CustomFieldValue;
						break;
				}
				break;
			}
		}
		render_attribute( $custom_field, false, '', $data );
	}
	if ( ! $no_invoice_free_events || $first_price->Price > 0 ) {
		?>
		<label<?php echo $force_show_invoice_information ? ' style="display: none;"' : ''; ?>>
			<div class="inputHolder alsoInvoiceCustomer">
				<label class="inline-checkbox" for="alsoInvoiceCustomer">
					<input type="checkbox" id="alsoInvoiceCustomer" name="alsoInvoiceCustomer" value="true" onchange="eduBookingView.UpdateInvoiceCustomer(this);"
						<?php echo $force_show_invoice_information ? 'checked' : ''; ?>/>
					<?php esc_html_e( 'Use other information for invoicing', 'eduadmin-booking' ); ?>
				</label>
			</div>
		</label>
	<?php } ?>
</div>
