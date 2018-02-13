<?php
	$user            = EDU()->session['eduadmin-loginUser'];
	$contact         = $user->Contact;
	$customer        = $user->Customer;
	$invoiceCustomer = $user->Customer->BillingInfo[0];

	if ( isset( $_POST['eduaction'] ) && sanitize_text_field( $_POST['eduaction'] ) == "saveInfo" ) {
		$patchCustomer               = new stdClass;
		$patchCustomer->CustomerName = trim( sanitize_text_field( $_POST['customerName'] ) );
		$patchCustomer->Address      = trim( sanitize_text_field( $_POST['customerAddress'] ) );
		$patchCustomer->Address2     = trim( sanitize_text_field( $_POST['customerAddress2'] ) );
		$patchCustomer->Zip          = trim( sanitize_text_field( $_POST['customerZip'] ) );
		$patchCustomer->City         = trim( sanitize_text_field( $_POST['customerCity'] ) );
		$patchCustomer->Phone        = trim( sanitize_text_field( $_POST['customerPhone'] ) );
		$patchCustomer->Email        = trim( sanitize_email( $_POST['customerEmail'] ) );

		$patchCustomer->BillingInfo                     = new stdClass;
		$patchCustomer->BillingInfo->CustomerName       = trim( sanitize_text_field( $_POST['customerInvoiceName'] ) );
		$patchCustomer->BillingInfo->Address            = trim( sanitize_text_field( $_POST['customerInvoiceAddress'] ) );
		$patchCustomer->BillingInfo->Zip                = trim( sanitize_text_field( $_POST['customerInvoiceZip'] ) );
		$patchCustomer->BillingInfo->City               = trim( sanitize_text_field( $_POST['customerInvoiceCity'] ) );
		$patchCustomer->BillingInfo->OrganisationNumber = trim( sanitize_text_field( $_POST['customerInvoiceOrgNr'] ) );
		$patchCustomer->BillingInfo->SellerReference    = trim( sanitize_text_field( $_POST['customerReference'] ) );
		$patchCustomer->BillingInfo->Email              = trim( sanitize_email( $_POST['customerInvoiceEmail'] ) );

		$patchContact         = new stdClass;
		$patchContact->Phone  = trim( sanitize_text_field( $_POST['contactPhone'] ) );
		$patchContact->Mobile = trim( sanitize_text_field( $_POST['contactMobile'] ) );
		$patchContact->Email  = trim( sanitize_email( $_POST['contactEmail'] ) );

		EDUAPI()->REST->Customer->Update( $customer->CustomerId, $patchCustomer );
		EDUAPI()->REST->Person->Update( $contact->PersonId, $patchContact );
	}
?>

<div class="eduadmin">
	<?php
		$tab = "profile";
		include_once( "login_tab_header.php" );
	?>
    <h2><?php _e( "My profile", 'eduadmin-booking' ); ?></h2>
    <form action="" method="POST">
        <input type="hidden" name="eduaction" value="saveInfo"/>
        <div class="eduadminCompanyInformation">
            <h3><?php _e( "Company information", 'eduadmin-booking' ); ?></h3>
            <label>
                <div class="inputLabel"><?php _e( "Customer name", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerName" required
                                                placeholder="<?php echo esc_attr( __( "Customer name", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $customer->CustomerName ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Address", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerAddress"
                                                placeholder="<?php echo esc_attr( __( "Address", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $customer->Address ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Address 2", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerAddress2"
                                                placeholder="<?php echo esc_attr( __( "Address 2", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $customer->Address2 ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Postal code", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerZip"
                                                placeholder="<?php echo esc_attr( __( "Postal code", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $customer->Zip ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Postal city", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerCity"
                                                placeholder="<?php echo esc_attr( __( "Postal city", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $customer->City ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "E-mail address", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerEmail"
                                                placeholder="<?php echo esc_attr( __( "E-mail address", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $customer->Email ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Phone", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerPhone"
                                                placeholder="<?php echo esc_attr( __( "Phone", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $customer->Phone ); ?>"/></div>
            </label>
        </div>
        <div class="eduadminInvoiceInformation">
            <h3><?php _e( "Invoice information", 'eduadmin-booking' ); ?></h3>
            <label>
                <div class="inputLabel"><?php _e( "Customer name", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerInvoiceName"
                                                placeholder="<?php echo esc_attr( __( "Customer name", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $invoiceCustomer->CustomerName ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Address", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerInvoiceAddress"
                                                placeholder="<?php echo esc_attr( __( "Address", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $invoiceCustomer->Address ); ?>"/></div>
            </label>

            <label>
                <div class="inputLabel"><?php _e( "Postal code", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerInvoiceZip"
                                                placeholder="<?php echo esc_attr( __( "Postal code", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $invoiceCustomer->Zip ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Postal city", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerInvoiceCity"
                                                placeholder="<?php echo esc_attr( __( "Postal city", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $invoiceCustomer->City ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Org.No.", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerInvoiceOrgNr"
                                                placeholder="<?php echo esc_attr( __( "Org.No.", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $invoiceCustomer->OrganisationNumber ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Invoice e-mail address", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerInvoiceEmail"
                                                placeholder="<?php echo esc_attr( __( "Invoice e-mail address", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $invoiceCustomer->Email ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Invoice reference", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerReference"
                                                placeholder="<?php echo esc_attr( __( "Invoice reference", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $invoiceCustomer->SellerReference ); ?>"/>
                </div>
            </label>
        </div>
        <div class="eduadminContactInformation">
            <h3><?php _e( "Contact information", 'eduadmin-booking' ); ?></h3>
            <label>
                <div class="inputLabel"><?php _e( "Contact name", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder">
                    <input type="text" name="contactName" readonly required
                           placeholder="<?php echo esc_attr( __( "Contact name", 'eduadmin-booking' ) ); ?>"
                           value="<?php echo esc_attr( $contact->FirstName . " " . $contact->LastName ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Phone", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="contactPhone"
                                                placeholder="<?php echo esc_attr( __( "Phone", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo esc_attr( $contact->Phone ); ?>"/></div>
            </label>

            <label>
                <div class="inputLabel"><?php _e( "Mobile", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="contactMobile"
                                                placeholder="<?php echo esc_attr( __( "Mobile", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo esc_attr( $contact->Mobile ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "E-mail address", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="contactEmail" readonly required
                                                placeholder="<?php echo esc_attr( __( "E-mail address", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo esc_attr( $contact->Email ); ?>"/></div>
            </label>
            <a href="<?php echo $baseUrl; ?>/profile/changepassword"><?php _e( "Change password", 'eduadmin-booking' ); ?></a>
        </div>
        <button class="profileSaveButton cta-btn"><?php _e( "Save", 'eduadmin-booking' ); ?></button>
    </form>
	<?php include_once( "login_tab_footer.php" ); ?>
</div>