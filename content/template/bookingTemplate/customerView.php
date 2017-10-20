<div class="customerView">
    <h2><?php edu_e( "Customer information" ); ?></h2>
    <label>
        <div class="inputLabel">
			<?php edu_e( "Customer name" ); ?>
        </div>
        <div class="inputHolder">
            <input type="text" required name="customerName" placeholder="<?php edu_e( "Customer name" ); ?>"
                   value="<?php echo @esc_attr( $customer->CustomerName ); ?>"/>
        </div>
    </label>
	<?php
		$noInvoiceFreeEvents = get_option( 'eduadmin-noInvoiceFreeEvents', false );
		if ( ! $noInvoiceFreeEvents || ( $noInvoiceFreeEvents && $firstPrice->Price > 0 ) ) {
			?>
            <label>
                <div class="inputLabel">
					<?php edu_e( "Org.No." ); ?>
                </div>
                <div class="inputHolder">
                    <input type="text" name="customerVatNo" placeholder="<?php edu_e( "Org.No." ); ?>"
                           value="<?php echo @esc_attr( $customer->InvoiceOrgnr ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php edu_e( "Address 1" ); ?>
                </div>
                <div class="inputHolder">
                    <input type="text" name="customerAddress1" placeholder="<?php edu_e( "Address 1" ); ?>"
                           value="<?php echo @esc_attr( $customer->Address1 ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php edu_e( "Address 2" ); ?>
                </div>
                <div class="inputHolder">
                    <input type="text" name="customerAddress2" placeholder="<?php edu_e( "Address 2" ); ?>"
                           value="<?php echo @esc_attr( $customer->Address2 ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php edu_e( "Postal code" ); ?>
                </div>
                <div class="inputHolder">
                    <input type="text" name="customerPostalCode" placeholder="<?php edu_e( "Postal code" ); ?>"
                           value="<?php echo @esc_attr( $customer->Zip ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php edu_e( "Postal city" ); ?>
                </div>
                <div class="inputHolder">
                    <input type="text" name="customerPostalCity" placeholder="<?php edu_e( "Postal city" ); ?>"
                           value="<?php echo @esc_attr( $customer->City ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php edu_e( "E-mail address" ); ?>
                </div>
                <div class="inputHolder">
                    <input type="text" name="customerEmail" placeholder="<?php edu_e( "E-mail address" ); ?>"
                           value="<?php echo @esc_attr( $customer->Email ); ?>"/>
                </div>
            </label>
            <div id="invoiceView" class="invoiceView" style="display: none;">
                <h2><?php edu_e( "Invoice information" ); ?></h2>
                <label>
                    <div class="inputLabel">
						<?php edu_e( "Customer name" ); ?>
                    </div>
                    <div class="inputHolder">
                        <input type="text" name="invoiceName" placeholder="<?php edu_e( "Customer name" ); ?>"
                               value="<?php echo @esc_attr( $customer->InvoiceName ); ?>"/>
                    </div>
                </label>
                <label>
                    <div class="inputLabel">
						<?php edu_e( "Address 1" ); ?>
                    </div>
                    <div class="inputHolder">
                        <input type="text" name="invoiceAddress1" placeholder="<?php edu_e( "Address 1" ); ?>"
                               value="<?php echo @esc_attr( $customer->InvoiceAddress1 ); ?>"/>
                    </div>
                </label>
                <label>
                    <div class="inputLabel">
						<?php edu_e( "Address 2" ); ?>
                    </div>
                    <div class="inputHolder">
                        <input type="text" name="invoiceAddress2" placeholder="<?php edu_e( "Address 2" ); ?>"
                               value="<?php echo @esc_attr( $customer->InvoiceAddress2 ); ?>"/>
                    </div>
                </label>
                <label>
                    <div class="inputLabel">
						<?php edu_e( "Postal code" ); ?>
                    </div>
                    <div class="inputHolder">
                        <input type="text" name="invoicePostalCode" placeholder="<?php edu_e( "Postal code" ); ?>"
                               value="<?php echo @esc_attr( $customer->InvoiceZip ); ?>"/>
                    </div>
                </label>
                <label>
                    <div class="inputLabel">
						<?php edu_e( "Postal city" ); ?>
                    </div>
                    <div class="inputHolder">
                        <input type="text" name="invoicePostalCity" placeholder="<?php edu_e( "Postal city" ); ?>"
                               value="<?php echo @esc_attr( $customer->InvoiceCity ); ?>"/>
                    </div>
                </label>
            </div>
            <label>
                <div class="inputLabel">
					<?php edu_e( "Invoice e-mail address" ); ?>
                </div>
                <div class="inputHolder">
                    <input type="text" name="invoiceEmail" placeholder="<?php edu_e( "Invoice e-mail address" ); ?>"
                           value="<?php echo @esc_attr( $customerInvoiceEmail ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php edu_e( "Invoice reference" ); ?>
                </div>
                <div class="inputHolder">
                    <input type="text" name="invoiceReference" placeholder="<?php edu_e( "Invoice reference" ); ?>"
                           value="<?php echo @esc_attr( $customer->CustomerReference ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php edu_e( "Purchase order number" ); ?>
                </div>
                <div class="inputHolder">
                    <input type="text" name="purchaseOrderNumber"
                           placeholder="<?php edu_e( "Purchase order number" ); ?>"
                           value="<?php echo @esc_attr( $purchaseOrderNumber ); ?>"/>
                </div>
            </label>
			<?php
		}
		$so = new XSorting();
		$s  = new XSort( 'SortIndex', 'ASC' );
		$so->AddItem( $s );

		$fo = new XFiltering();
		$f  = new XFilter( 'ShowOnWeb', '=', 'true' );
		$fo->AddItem( $f );
		$f = new XFilter( 'AttributeOwnerTypeID', '=', 2 );
		$fo->AddItem( $f );
		$contactAttributes = $eduapi->GetAttribute( $edutoken, $so->ToString(), $fo->ToString() );

		$db = array();
		if ( isset( $customer ) && isset( $customer->CustomerID ) ) {
			if ( $customer->CustomerID != 0 ) {
				$fo = new XFiltering();
				$f  = new XFilter( 'CustomerID', '=', $customer->CustomerID );
				$fo->AddItem( $f );
				$db = $eduapi->GetCustomerAttribute( $edutoken, '', $fo->ToString() );
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
		if ( $noInvoiceFreeEvents && $firstPrice->Price > 0 ) {
			?>
            <label>
                <div class="inputHolder alsoInvoiceCustomer">
                    <input type="checkbox" id="alsoInvoiceCustomer" name="alsoInvoiceCustomer" value="true"
                           onchange="eduBookingView.UpdateInvoiceCustomer();"/>
                    <label class="inline-checkbox" for="alsoInvoiceCustomer"></label>
					<?php edu_e( "Use other information for invoicing" ); ?>
                </div>
            </label>
		<?php } ?>
</div>