<?php
	$user     = EDU()->session['eduadmin-loginUser'];
	$contact  = $user->Contact;
	$customer = $user->Customer;

	if ( isset( $_POST['eduaction'] ) && sanitize_text_field( $_POST['eduaction'] ) == "saveInfo" ) {
		global $eduapi;
		global $edutoken;

		$customer->CustomerName = trim( sanitize_text_field( $_POST['customerName'] ) );
		$customer->Address1     = trim( sanitize_text_field( $_POST['customerAddress'] ) );
		$customer->Address2     = trim( sanitize_text_field( $_POST['customerAddress2'] ) );
		$customer->Zip          = trim( sanitize_text_field( $_POST['customerZip'] ) );
		$customer->City         = trim( sanitize_text_field( $_POST['customerCity'] ) );
		$customer->Phone        = trim( sanitize_text_field( $_POST['customerPhone'] ) );
		$customer->Email        = trim( sanitize_email( $_POST['customerEmail'] ) );

		$customer->InvoiceName       = trim( sanitize_text_field( $_POST['customerInvoiceName'] ) );
		$customer->InvoiceAddress1   = trim( sanitize_text_field( $_POST['customerInvoiceAddress'] ) );
		$customer->InvoiceZip        = trim( sanitize_text_field( $_POST['customerInvoiceZip'] ) );
		$customer->InvoiceCity       = trim( sanitize_text_field( $_POST['customerInvoiceCity'] ) );
		$customer->InvoiceOrgnr      = trim( sanitize_text_field( $_POST['customerInvoiceOrgNr'] ) );
		$customer->CustomerReference = trim( sanitize_text_field( $_POST['customerReference'] ) );
		$customer->InvoiceEmail      = trim( sanitize_email( $_POST['customerInvoiceEmail'] ) );

		$contact->ContactName = trim( sanitize_text_field( $_POST['contactName'] ) );
		$contact->Phone       = trim( sanitize_text_field( $_POST['contactPhone'] ) );
		$contact->Mobile      = trim( sanitize_text_field( $_POST['contactMobile'] ) );
		$contact->Email       = trim( sanitize_email( $_POST['contactEmail'] ) );

		$eduapi->SetCustomerV2( $edutoken, array( $customer ) );
		$eduapi->SetCustomerContact( $edutoken, array( $contact ) );
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
                                                value="<?php echo @esc_attr( $customer->Address1 ); ?>"/></div>
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
                                                value="<?php echo @esc_attr( $customer->InvoiceName ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Address", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerInvoiceAddress"
                                                placeholder="<?php echo esc_attr( __( "Address", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $customer->InvoiceAddress1 ); ?>"/></div>
            </label>

            <label>
                <div class="inputLabel"><?php _e( "Postal code", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerInvoiceZip"
                                                placeholder="<?php echo esc_attr( __( "Postal code", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $customer->InvoiceZip ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Postal city", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerInvoiceCity"
                                                placeholder="<?php echo esc_attr( __( "Postal city", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $customer->InvoiceCity ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Org.No.", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerInvoiceOrgNr"
                                                placeholder="<?php echo esc_attr( __( "Org.No.", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $customer->InvoiceOrgnr ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Invoice e-mail address", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerInvoiceEmail"
                                                placeholder="<?php echo esc_attr( __( "Invoice e-mail address", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $customer->InvoiceEmail ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Invoice reference", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="customerReference"
                                                placeholder="<?php echo esc_attr( __( "Invoice reference", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo @esc_attr( $customer->CustomerReference ); ?>"/>
                </div>
            </label>
        </div>
        <div class="eduadminContactInformation">
            <h3><?php _e( "Contact information", 'eduadmin-booking' ); ?></h3>
            <label>
                <div class="inputLabel"><?php _e( "Contact name", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="text" name="contactName" readonly required
                                                placeholder="<?php echo esc_attr( __( "Contact name", 'eduadmin-booking' ) ); ?>"
                                                value="<?php echo esc_attr( $contact->ContactName ); ?>"/></div>
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