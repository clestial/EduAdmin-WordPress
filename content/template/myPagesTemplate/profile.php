<?php
$user            = EDU()->session['eduadmin-loginUser'];
$contact         = $user->Contact;
$customer        = $user->Customer;
$invoiceCustomer = $user->Customer->BillingInfo[0];

if ( ! empty( $_POST['eduaction'] ) && wp_verify_nonce( $_POST['edu-profile-nonce'], 'edu-save-profile' ) && 'saveInfo' === sanitize_text_field( $_POST['eduaction'] ) ) {
	$patch_customer               = new stdClass();
	$patch_customer->CustomerName = trim( sanitize_text_field( $_POST['customerName'] ) );
	$patch_customer->Address      = trim( sanitize_text_field( $_POST['customerAddress'] ) );
	$patch_customer->Address2     = trim( sanitize_text_field( $_POST['customerAddress2'] ) );
	$patch_customer->Zip          = trim( sanitize_text_field( $_POST['customerZip'] ) );
	$patch_customer->City         = trim( sanitize_text_field( $_POST['customerCity'] ) );
	$patch_customer->Phone        = trim( sanitize_text_field( $_POST['customerPhone'] ) );
	$patch_customer->Email        = trim( sanitize_email( $_POST['customerEmail'] ) );

	$patch_customer->BillingInfo                     = new stdClass;
	$patch_customer->BillingInfo->CustomerName       = trim( sanitize_text_field( $_POST['customerInvoiceName'] ) );
	$patch_customer->BillingInfo->Address            = trim( sanitize_text_field( $_POST['customerInvoiceAddress'] ) );
	$patch_customer->BillingInfo->Zip                = trim( sanitize_text_field( $_POST['customerInvoiceZip'] ) );
	$patch_customer->BillingInfo->City               = trim( sanitize_text_field( $_POST['customerInvoiceCity'] ) );
	$patch_customer->BillingInfo->OrganisationNumber = trim( sanitize_text_field( $_POST['customerInvoiceOrgNr'] ) );
	$patch_customer->BillingInfo->SellerReference    = trim( sanitize_text_field( $_POST['customerReference'] ) );
	$patch_customer->BillingInfo->Email              = trim( sanitize_email( $_POST['customerInvoiceEmail'] ) );

	$patch_contact         = new stdClass;
	$patch_contact->Phone  = trim( sanitize_text_field( $_POST['contactPhone'] ) );
	$patch_contact->Mobile = trim( sanitize_text_field( $_POST['contactMobile'] ) );
	$patch_contact->Email  = trim( sanitize_email( $_POST['contactEmail'] ) );

	EDUAPI()->REST->Customer->Update( $customer->CustomerId, $patch_customer );
	EDUAPI()->REST->Person->Update( $contact->PersonId, $patch_contact );
}
?>

<div class="eduadmin">
	<?php
	$tab = 'profile';
	require_once 'login_tab_header.php';
	?>
	<h2><?php esc_html_e( 'My profile', 'eduadmin-booking' ); ?></h2>
	<form action="" method="POST">
		<input type="hidden" name="eduaction" value="saveInfo"/>
		<input type="hidden" name="edu-profile-nonce" value="<?php echo esc_attr( wp_create_nonce( 'edu-save-profile' ) ); ?>"/>
		<div class="eduadminCompanyInformation">
			<h3><?php esc_html_e( "Company information", 'eduadmin-booking' ); ?></h3>
			<label>
				<div class="inputLabel"><?php esc_html_e( "Customer name", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerName" required placeholder="<?php echo esc_attr( __( "Customer name", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $customer->CustomerName ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( "Address", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerAddress" placeholder="<?php echo esc_attr( __( "Address", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $customer->Address ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( "Address 2", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerAddress2" placeholder="<?php echo esc_attr( __( "Address 2", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $customer->Address2 ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( "Postal code", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerZip" placeholder="<?php echo esc_attr( __( "Postal code", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $customer->Zip ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( "Postal city", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerCity" placeholder="<?php echo esc_attr( __( "Postal city", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $customer->City ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( "E-mail address", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerEmail" placeholder="<?php echo esc_attr( __( "E-mail address", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $customer->Email ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( "Phone", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerPhone" placeholder="<?php echo esc_attr( __( "Phone", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $customer->Phone ); ?>"/>
				</div>
			</label>
		</div>
		<div class="eduadminInvoiceInformation">
			<h3><?php esc_html_e( "Invoice information", 'eduadmin-booking' ); ?></h3>
			<label>
				<div class="inputLabel"><?php esc_html_e( "Customer name", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerInvoiceName" placeholder="<?php echo esc_attr( __( "Customer name", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $invoiceCustomer->CustomerName ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( "Address", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerInvoiceAddress" placeholder="<?php echo esc_attr( __( "Address", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $invoiceCustomer->Address ); ?>"/>
				</div>
			</label>

			<label>
				<div class="inputLabel"><?php esc_html_e( "Postal code", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerInvoiceZip" placeholder="<?php echo esc_attr( __( "Postal code", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $invoiceCustomer->Zip ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( "Postal city", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerInvoiceCity" placeholder="<?php echo esc_attr( __( "Postal city", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $invoiceCustomer->City ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( "Org.No.", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerInvoiceOrgNr" placeholder="<?php echo esc_attr( __( "Org.No.", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $invoiceCustomer->OrganisationNumber ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( "Invoice e-mail address", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerInvoiceEmail" placeholder="<?php echo esc_attr( __( "Invoice e-mail address", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $invoiceCustomer->Email ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( "Invoice reference", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerReference" placeholder="<?php echo esc_attr( __( "Invoice reference", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $invoiceCustomer->SellerReference ); ?>"/>
				</div>
			</label>
		</div>
		<div class="eduadminContactInformation">
			<h3><?php esc_html_e( "Contact information", 'eduadmin-booking' ); ?></h3>
			<label>
				<div class="inputLabel"><?php esc_html_e( "Contact name", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="contactName" readonly required placeholder="<?php echo esc_attr( __( "Contact name", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $contact->FirstName . " " . $contact->LastName ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( "Phone", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="contactPhone" placeholder="<?php echo esc_attr( __( "Phone", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $contact->Phone ); ?>"/>
				</div>
			</label>

			<label>
				<div class="inputLabel"><?php esc_html_e( "Mobile", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="contactMobile" placeholder="<?php echo esc_attr( __( "Mobile", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $contact->Mobile ); ?>"/>
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( "E-mail address", 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="contactEmail" readonly required placeholder="<?php echo esc_attr( __( "E-mail address", 'eduadmin-booking' ) ); ?>" value="<?php echo esc_attr( $contact->Email ); ?>"/>
				</div>
			</label>
			<a href="<?php echo $base_url; ?>/profile/changepassword"><?php _e( "Change password", 'eduadmin-booking' ); ?></a>
		</div>
		<button class="profileSaveButton cta-btn"><?php _e( "Save", 'eduadmin-booking' ); ?></button>
	</form>
	<?php include_once( "login_tab_footer.php" ); ?>
</div>