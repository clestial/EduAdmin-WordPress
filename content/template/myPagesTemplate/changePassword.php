<?php
	$user     = EDU()->session['eduadmin-loginUser'];
	$contact  = $user->Contact;
	$customer = $user->Customer;
	if ( isset( $_POST['eduaction'] ) && sanitize_text_field( $_POST['eduaction'] ) == "savePassword" ) {
		if ( sanitize_text_field( $_POST['currentPassword'] ) == $contact->Loginpass ) {
			if ( strlen( sanitize_text_field( $_POST['newPassword'] ) ) == 0 ) {
				$msg = __( "You must fill in a password to change it.", 'eduadmin-booking' );
			} else if ( sanitize_text_field( $_POST['newPassword'] ) != sanitize_text_field( $_POST['confirmPassword'] ) ) {
				$msg = __( "Given password does not match.", 'eduadmin-booking' );
			} else if ( sanitize_text_field( $_POST['newPassword'] ) == sanitize_text_field( $_POST['currentPassword'] ) ) {
				$msg = __( "You cannot set your password to be the same as the one before.", 'eduadmin-booking' );
			} else {
				$contact->Loginpass = trim( sanitize_text_field( $_POST['newPassword'] ) );
				EDU()->api->SetCustomerContact( EDU()->get_token(), array( $contact ) );
			}
		}
	}
?>
<div class="eduadmin">
	<?php
		$tab = "profile";
		include_once( "login_tab_header.php" );
	?>
    <h2><?php _e( "Change password", 'eduadmin-booking' ); ?></h2>
    <form action="" method="POST">
        <input type="hidden" name="eduaction" value="savePassword"/>
        <div class="eduadminContactInformation">
            <h3><?php _e( "Contact information", 'eduadmin-booking' ); ?></h3>
            <label>
                <div class="inputLabel"><?php _e( "Current password", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="password" name="currentPassword" required
                                                placeholder="<?php echo esc_attr( __( "Current password", 'eduadmin-booking' ) ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "New password", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="password" name="newPassword" required
                                                placeholder="<?php echo esc_attr( __( "New password", 'eduadmin-booking' ) ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel"><?php _e( "Confirm password", 'eduadmin-booking' ); ?></div>
                <div class="inputHolder"><input type="password" name="confirmPassword" required
                                                placeholder="<?php echo esc_attr( __( "Confirm password", 'eduadmin-booking' ) ); ?>"/>
                </div>
            </label>
        </div>
        <button class="profileSaveButton cta-btn"><?php _e( "Save", 'eduadmin-booking' ); ?></button>
    </form>
	<?php if ( isset( $msg ) ) { ?>
        <div class="edu-modal warning" style="display: block; clear: both;">
			<?php echo $msg; ?>
        </div>
	<?php } ?>
	<?php include_once( "login_tab_footer.php" ); ?>
</div>