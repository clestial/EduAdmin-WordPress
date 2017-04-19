<?php
$user     = $_SESSION['eduadmin-loginUser'];
$contact  = $user->Contact;
$customer = $user->Customer;

if ( isset( $_POST['eduaction'] ) && $_POST['eduaction'] == "saveInfo" ) {
	global $eduapi;
	global $edutoken;

	$customer->CustomerName = trim( $_POST['customerName'] );
	$customer->Address1     = trim( $_POST['customerAddress'] );
	$customer->Address2     = trim( $_POST['customerAddress2'] );
	$customer->Zip          = trim( $_POST['customerZip'] );
	$customer->City         = trim( $_POST['customerCity'] );
	$customer->Phone        = trim( $_POST['customerPhone'] );
	$customer->Email        = trim( $_POST['customerEmail'] );

	$customer->InvoiceName       = trim( $_POST['customerInvoiceName'] );
	$customer->InvoiceAddress1   = trim( $_POST['customerInvoiceAddress'] );
	$customer->InvoiceZip        = trim( $_POST['customerInvoiceZip'] );
	$customer->InvoiceCity       = trim( $_POST['customerInvoiceCity'] );
	$customer->InvoiceOrgnr      = trim( $_POST['customerInvoiceOrgNr'] );
	$customer->CustomerReference = trim( $_POST['customerReference'] );
	$customer->InvoiceEmail      = trim( $_POST['customerInvoiceEmail'] );

	$contact->ContactName = trim( $_POST['contactName'] );
	$contact->Phone       = trim( $_POST['contactPhone'] );
	$contact->Mobile      = trim( $_POST['contactMobile'] );
	$contact->Email       = trim( $_POST['contactEmail'] );

	$eduapi->SetCustomerV2( $edutoken, array( $customer ) );
	$eduapi->SetCustomerContact( $edutoken, array( $contact ) );
}
?>

<div class="eduadmin">
	<?php
	$tab = "profile";
	include_once( "login_tab_header.php" );
	?>
	<h2><?php edu_e( "My profile" ); ?></h2>
	<form action="" method="POST">
		<input type="hidden" name="eduaction" value="saveInfo" />
		<div class="eduadminCompanyInformation">
			<h3><?php edu_e( "Company information" ); ?></h3>
			<label>
				<div class="inputLabel"><?php edu_e( "Customer name" ); ?></div>
				<div class="inputHolder"><input type="text" name="customerName" required
												placeholder="<?php echo esc_attr( edu__( "Customer name" ) ); ?>"
												value="<?php echo @esc_attr( $customer->CustomerName ); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e( "Address" ); ?></div>
				<div class="inputHolder"><input type="text" name="customerAddress"
												placeholder="<?php echo esc_attr( edu__( "Address" ) ); ?>"
												value="<?php echo @esc_attr( $customer->Address1 ); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e( "Address 2" ); ?></div>
				<div class="inputHolder"><input type="text" name="customerAddress2"
												placeholder="<?php echo esc_attr( edu__( "Address 2" ) ); ?>"
												value="<?php echo @esc_attr( $customer->Address2 ); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e( "Postal code" ); ?></div>
				<div class="inputHolder"><input type="text" name="customerZip"
												placeholder="<?php echo esc_attr( edu__( "Postal code" ) ); ?>"
												value="<?php echo @esc_attr( $customer->Zip ); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e( "Postal city" ); ?></div>
				<div class="inputHolder"><input type="text" name="customerCity"
												placeholder="<?php echo esc_attr( edu__( "Postal city" ) ); ?>"
												value="<?php echo @esc_attr( $customer->City ); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e( "E-mail address" ); ?></div>
				<div class="inputHolder"><input type="text" name="customerEmail"
												placeholder="<?php echo esc_attr( edu__( "E-mail address" ) ); ?>"
												value="<?php echo @esc_attr( $customer->Email ); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e( "Phone" ); ?></div>
				<div class="inputHolder"><input type="text" name="customerPhone"
												placeholder="<?php echo esc_attr( edu__( "Phone" ) ); ?>"
												value="<?php echo @esc_attr( $customer->Phone ); ?>" /></div>
			</label>
		</div>
		<div class="eduadminInvoiceInformation">
			<h3><?php edu_e( "Invoice information" ); ?></h3>
			<label>
				<div class="inputLabel"><?php edu_e( "Customer name" ); ?></div>
				<div class="inputHolder"><input type="text" name="customerInvoiceName"
												placeholder="<?php echo esc_attr( edu__( "Customer name" ) ); ?>"
												value="<?php echo @esc_attr( $customer->InvoiceName ); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e( "Address" ); ?></div>
				<div class="inputHolder"><input type="text" name="customerInvoiceAddress"
												placeholder="<?php echo esc_attr( edu__( "Address" ) ); ?>"
												value="<?php echo @esc_attr( $customer->InvoiceAddress1 ); ?>" /></div>
			</label>

			<label>
				<div class="inputLabel"><?php edu_e( "Postal code" ); ?></div>
				<div class="inputHolder"><input type="text" name="customerInvoiceZip"
												placeholder="<?php echo esc_attr( edu__( "Postal code" ) ); ?>"
												value="<?php echo @esc_attr( $customer->InvoiceZip ); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e( "Postal city" ); ?></div>
				<div class="inputHolder"><input type="text" name="customerInvoiceCity"
												placeholder="<?php echo esc_attr( edu__( "Postal city" ) ); ?>"
												value="<?php echo @esc_attr( $customer->InvoiceCity ); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e( "Org.No." ); ?></div>
				<div class="inputHolder"><input type="text" name="customerInvoiceOrgNr"
												placeholder="<?php echo esc_attr( edu__( "Org.No." ) ); ?>"
												value="<?php echo @esc_attr( $customer->InvoiceOrgnr ); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e( "Invoice e-mail address" ); ?></div>
				<div class="inputHolder"><input type="text" name="customerInvoiceEmail"
												placeholder="<?php echo esc_attr( edu__( "Invoice e-mail address" ) ); ?>"
												value="<?php echo @esc_attr( $customer->InvoiceEmail ); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e( "Invoice reference" ); ?></div>
				<div class="inputHolder"><input type="text" name="customerReference"
												placeholder="<?php echo esc_attr( edu__( "Invoice reference" ) ); ?>"
												value="<?php echo @esc_attr( $customer->CustomerReference ); ?>" />
				</div>
			</label>
		</div>
		<div class="eduadminContactInformation">
			<h3><?php edu_e( "Contact information" ); ?></h3>
			<label>
				<div class="inputLabel"><?php edu_e( "Contact name" ); ?></div>
				<div class="inputHolder"><input type="text" name="contactName" readonly required
												placeholder="<?php echo esc_attr( edu__( "Contact name" ) ); ?>"
												value="<?php echo esc_attr( $contact->ContactName ); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e( "Phone" ); ?></div>
				<div class="inputHolder"><input type="text" name="contactPhone"
												placeholder="<?php echo esc_attr( edu__( "Phone" ) ); ?>"
												value="<?php echo esc_attr( $contact->Phone ); ?>" /></div>
			</label>

			<label>
				<div class="inputLabel"><?php edu_e( "Mobile" ); ?></div>
				<div class="inputHolder"><input type="text" name="contactMobile"
												placeholder="<?php echo esc_attr( edu__( "Mobile" ) ); ?>"
												value="<?php echo esc_attr( $contact->Mobile ); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e( "E-mail address" ); ?></div>
				<div class="inputHolder"><input type="text" name="contactEmail" readonly required
												placeholder="<?php echo esc_attr( edu__( "E-mail address" ) ); ?>"
												value="<?php echo esc_attr( $contact->Email ); ?>" /></div>
			</label>
			<a href="<?php echo $baseUrl; ?>/profile/changepassword"><?php edu_e( "Change password" ); ?></a>
		</div>
		<button class="profileSaveButton"><?php edu_e( "Save" ); ?></button>
	</form>
	<?php include_once( "login_tab_footer.php" ); ?>
</div>