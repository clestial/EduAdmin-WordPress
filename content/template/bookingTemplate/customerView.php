<div class="customerView">
    <h2><?php _e( "Customer information", 'eduadmin-booking' ); ?></h2>
    <label>
        <div class="inputLabel">
			<?php _e( "Customer name", 'eduadmin-booking' ); ?>
        </div>
        <div class="inputHolder">
            <input type="text" required name="customerName" autocomplete="organization"
                   placeholder="<?php _e( "Customer name", 'eduadmin-booking' ); ?>"
                   value="<?php echo @esc_attr( $customer->CustomerName ); ?>"/>
        </div>
    </label>
	<?php
		if ( ! $noInvoiceFreeEvents || ( $noInvoiceFreeEvents && $firstPrice->Price > 0 ) ) {
			?>
            <label>
                <div class="inputLabel">
					<?php _e( "Org.No.", 'eduadmin-booking' ); ?>
                </div>
                <div class="inputHolder">
                    <input type="text" name="customerVatNo" placeholder="<?php _e( "Org.No.", 'eduadmin-booking' ); ?>"
                           value="<?php echo @esc_attr( $customer->OrganisationNumber ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php _e( "Address 1", 'eduadmin-booking' ); ?>
                </div>
                <div class="inputHolder">
                    <input type="text" name="customerAddress1"
                           placeholder="<?php _e( "Address 1", 'eduadmin-booking' ); ?>"
                           value="<?php echo @esc_attr( $customer->Address ); ?>"/>
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
            <label>
                <div class="inputLabel">
					<?php _e( "E-mail address", 'eduadmin-booking' ); ?>
                </div>
                <div class="inputHolder">
                    <input type="text" name="customerEmail"
                           placeholder="<?php _e( "E-mail address", 'eduadmin-booking' ); ?>"
                           value="<?php echo @esc_attr( $customer->Email ); ?>"/>
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
                               value="<?php echo @esc_attr( $customer->BillingInfo->CustomerName ); ?>"/>
                    </div>
                </label>
                <label>
                    <div class="inputLabel">
						<?php _e( "Address 1", 'eduadmin-booking' ); ?>
                    </div>
                    <div class="inputHolder">
                        <input type="text" name="invoiceAddress1"
                               placeholder="<?php _e( "Address 1", 'eduadmin-booking' ); ?>"
                               value="<?php echo @esc_attr( $customer->BillingInfo->Address ); ?>"/>
                    </div>
                </label>
                <label>
                    <div class="inputLabel">
						<?php _e( "Address 2", 'eduadmin-booking' ); ?>
                    </div>
                    <div class="inputHolder">
                        <input type="text" name="invoiceAddress2"
                               placeholder="<?php _e( "Address 2", 'eduadmin-booking' ); ?>"
                               value="<?php echo @esc_attr( $customer->BillingInfo->Address2 ); ?>"/>
                    </div>
                </label>
                <label>
                    <div class="inputLabel">
						<?php _e( "Postal code", 'eduadmin-booking' ); ?>
                    </div>
                    <div class="inputHolder">
                        <input type="text" name="invoicePostalCode"
                               placeholder="<?php _e( "Postal code", 'eduadmin-booking' ); ?>"
                               value="<?php echo @esc_attr( $customer->BillingInfo->Zip ); ?>"/>
                    </div>
                </label>
                <label>
                    <div class="inputLabel">
						<?php _e( "Postal city", 'eduadmin-booking' ); ?>
                    </div>
                    <div class="inputHolder">
                        <input type="text" name="invoicePostalCity"
                               placeholder="<?php _e( "Postal city", 'eduadmin-booking' ); ?>"
                               value="<?php echo @esc_attr( $customer->BillingInfo->City ); ?>"/>
                    </div>
                </label>
            </div>
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
            <label>
                <div class="inputLabel">
					<?php _e( "Purchase order number", 'eduadmin-booking' ); ?>
                </div>
                <div class="inputHolder">
                    <input type="text" name="purchaseOrderNumber"
                           placeholder="<?php _e( "Purchase order number", 'eduadmin-booking' ); ?>"
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
		if ( ! $noInvoiceFreeEvents || $firstPrice->Price > 0 ) {
			?>
            <label style="<?php echo $forceShowInvoiceInformation ? "display: none;" : "" ?>">
                <div class="inputHolder alsoInvoiceCustomer">
                    <input type="checkbox" id="alsoInvoiceCustomer" name="alsoInvoiceCustomer" value="true"
                           onchange="eduBookingView.UpdateInvoiceCustomer(this);"
						<?php echo $forceShowInvoiceInformation ? "checked" : "" ?>/>
                    <label class="inline-checkbox" for="alsoInvoiceCustomer">
						<?php _e( "Use other information for invoicing", 'eduadmin-booking' ); ?>
                    </label>
                </div>
            </label>
		<?php } ?>
</div>