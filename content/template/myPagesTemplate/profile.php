<?php
// phpcs:disable WordPress.NamingConventions,Squiz
$user             = EDU()->session['eduadmin-loginUser'];
$contact          = $user->Contact;
$customer         = $user->Customer;
$invoice_customer = $user->Customer->BillingInfo[0];

if ( ! empty( $_POST['eduaction'] ) && wp_verify_nonce( $_POST['edu-profile-nonce'], 'edu-save-profile' ) && 'saveInfo' === sanitize_text_field( $_POST['eduaction'] ) ) {
	$patch_customer               = new stdClass();
	$patch_customer->CustomerName = sanitize_text_field( $_POST['customerName'] );
	$patch_customer->Address      = sanitize_text_field( $_POST['customerAddress'] );
	$patch_customer->Address2     = sanitize_text_field( $_POST['customerAddress2'] );
	$patch_customer->Zip          = sanitize_text_field( $_POST['customerZip'] );
	$patch_customer->City         = sanitize_text_field( $_POST['customerCity'] );
	$patch_customer->Phone        = sanitize_text_field( $_POST['customerPhone'] );
	$patch_customer->Email        = sanitize_email( $_POST['customerEmail'] );

	$patch_customer->BillingInfo                     = new stdClass();
	$patch_customer->BillingInfo->CustomerName       = sanitize_text_field( $_POST['customerInvoiceName'] );
	$patch_customer->BillingInfo->Address            = sanitize_text_field( $_POST['customerInvoiceAddress'] );
	$patch_customer->BillingInfo->Zip                = sanitize_text_field( $_POST['customerInvoiceZip'] );
	$patch_customer->BillingInfo->City               = sanitize_text_field( $_POST['customerInvoiceCity'] );
	$patch_customer->BillingInfo->OrganisationNumber = sanitize_text_field( $_POST['customerInvoiceOrgNr'] );
	$patch_customer->BillingInfo->SellerReference    = sanitize_text_field( $_POST['customerReference'] );
	$patch_customer->BillingInfo->Email              = sanitize_email( $_POST['customerInvoiceEmail'] );

	$patch_contact         = new stdClass();
	$patch_contact->Phone  = sanitize_text_field( $_POST['contactPhone'] );
	$patch_contact->Mobile = sanitize_text_field( $_POST['contactMobile'] );
	$patch_contact->Email  = sanitize_email( $_POST['contactEmail'] );

	EDUAPI()->REST->Customer->Update( $customer->CustomerId, $patch_customer );
	EDUAPI()->REST->Person->Update( $contact->PersonId, $patch_contact );
}
?>

<div class="eduadmin">
	<?php
	$tab = 'profile';
	require_once 'login-tab-header.php';
	?>
	<h2><?php esc_html_e( 'My profile', 'eduadmin-booking' ); ?></h2>
	<form action="" method="POST">
		<input type="hidden" name="eduaction" value="saveInfo" />
		<input type="hidden" name="edu-profile-nonce" value="<?php echo esc_attr( wp_create_nonce( 'edu-save-profile' ) ); ?>" />
		<div class="eduadminCompanyInformation">
			<h3><?php esc_html_e( 'Company information', 'eduadmin-booking' ); ?></h3>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'Customer name', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerName" required placeholder="<?php echo esc_attr__( 'Customer name', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $customer->CustomerName ); ?>" />
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'Address', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerAddress" placeholder="<?php echo esc_attr__( "Address", 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $customer->Address ); ?>" />
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'Address 2', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerAddress2" placeholder="<?php echo esc_attr__( 'Address 2', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $customer->Address2 ); ?>" />
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'Postal code', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerZip" placeholder="<?php echo esc_attr__( "Postal code", 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $customer->Zip ); ?>" />
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'Postal city', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerCity" placeholder="<?php echo esc_attr__( 'Postal city', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $customer->City ); ?>" />
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'E-mail address', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerEmail" placeholder="<?php echo esc_attr__( 'E-mail address', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $customer->Email ); ?>" />
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'Phone', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerPhone" placeholder="<?php echo esc_attr__( 'Phone', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $customer->Phone ); ?>" />
				</div>
			</label>
		</div>
		<div class="eduadminInvoiceInformation">
			<h3><?php esc_html_e( 'Invoice information', 'eduadmin-booking' ); ?></h3>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'Customer name', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerInvoiceName" placeholder="<?php echo esc_attr__( 'Customer name', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $invoice_customer->CustomerName ); ?>" />
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'Address', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerInvoiceAddress" placeholder="<?php echo esc_attr__( 'Address', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $invoice_customer->Address ); ?>" />
				</div>
			</label>

			<label>
				<div class="inputLabel"><?php esc_html_e( 'Postal code', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerInvoiceZip" placeholder="<?php echo esc_attr__( 'Postal code', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $invoice_customer->Zip ); ?>" />
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'Postal city', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerInvoiceCity" placeholder="<?php echo esc_attr__( 'Postal city', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $invoice_customer->City ); ?>" />
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'Org.No.', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerInvoiceOrgNr" placeholder="<?php echo esc_attr__( 'Org.No.', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $invoice_customer->OrganisationNumber ); ?>" />
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'Invoice e-mail address', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerInvoiceEmail" placeholder="<?php echo esc_attr__( 'Invoice e-mail address', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $invoice_customer->Email ); ?>" />
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'Invoice reference', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="customerReference" placeholder="<?php echo esc_attr__( 'Invoice reference', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $invoice_customer->SellerReference ); ?>" />
				</div>
			</label>
		</div>
		<div class="eduadminContactInformation">
			<h3><?php esc_html_e( 'Contact information', 'eduadmin-booking' ); ?></h3>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'Contact name', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="contactName" readonly required placeholder="<?php echo esc_attr__( 'Contact name', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $contact->FirstName . " " . $contact->LastName ); ?>" />
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'Phone', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="contactPhone" placeholder="<?php echo esc_attr__( 'Phone', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $contact->Phone ); ?>" />
				</div>
			</label>

			<label>
				<div class="inputLabel"><?php esc_html_e( 'Mobile', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="contactMobile" placeholder="<?php echo esc_attr__( 'Mobile', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $contact->Mobile ); ?>" />
				</div>
			</label>
			<label>
				<div class="inputLabel"><?php esc_html_e( 'E-mail address', 'eduadmin-booking' ); ?></div>
				<div class="inputHolder">
					<input type="text" name="contactEmail" readonly required placeholder="<?php echo esc_attr__( 'E-mail address', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $contact->Email ); ?>" />
				</div>
			</label>
			<a href="<?php echo esc_url( $base_url . '/profile/changepassword' ); ?>"><?php esc_html_e( 'Change password', 'eduadmin-booking' ); ?></a>
		</div>
		<button class="profileSaveButton cta-btn"><?php esc_html_e( 'Save', 'eduadmin-booking' ); ?></button>
	</form>
	<?php require_once 'login-tab-footer.php'; ?>
</div>
