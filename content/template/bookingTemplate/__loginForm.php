<div class="checkLoginForm">
    <input type="hidden" name="eduformloginaction" value=""/>
    <h3><?php edu_e( "Please login to continue." ); ?></h3>
	<?php
		$selectedLoginField = get_option( 'eduadmin-loginField', 'Email' );
		$loginLabel         = edu__( "E-mail address" );
		$fieldType          = "text";
		switch ( $selectedLoginField ) {
			case "Email":
				$loginLabel = edu__( "E-mail address" );
				$fieldType  = "email";
				break;
			case "CivicRegistrationNumber":
				$loginLabel = edu__( "Civic Registration Number" );
				$fieldType  = "text";
				break;
			case "CustomerNumber":
				$loginLabel = edu__( "Customer number" );
				$fieldType  = "text";
				break;
		}
	?>
    <label>
        <div class="loginLabel"><?php echo $loginLabel; ?></div>
        <div class="loginInput">
            <input type="<?php echo $fieldType; ?>"
                   name="eduadminloginEmail"<?php echo( $selectedLoginField == "CivicRegistrationNumber" ? " class=\"eduadmin-civicRegNo\" onblur=\"eduBookingView.ValidateCivicRegNo();\"" : "" ); ?>
                   required autocomplete="off"
                   title="<?php echo esc_attr( sprintf( edu__( "Please enter your %s here" ), $loginLabel ) ); ?>"
                   placeholder="<?php echo esc_attr( $loginLabel ); ?>"
                   value="<?php echo @esc_attr( sanitize_text_field( $_REQUEST["eduadminloginEmail"] ) ); ?>"/>
        </div>
    </label>
    <label>
        <div class="loginLabel"><?php edu_e( "Password" ); ?></div>
        <div class="loginInput">
            <input type="password" autocomplete="off" name="eduadminpassword" required
                   title="<?php echo esc_attr( edu__( "Please enter your password here" ) ); ?>"
                   placeholder="<?php echo esc_attr( edu__( "Password" ) ); ?>"/>
        </div>
    </label>
	<?php
		$click = "";
		if ( $selectedLoginField == "CivicRegistrationNumber" && get_option( 'eduadmin-validateCivicRegNo', false ) === "true" ) {
			$click = "if(!eduBookingView.ValidateCivicRegNo()) { alert('" . edu__( "Please enter a valid swedish civic registration number." ) . "');  return false; }";
		}
	?>
    <button class="loginButton"
            onclick="this.form.eduadminpassword.required = true; this.form.eduformloginaction.value = 'loginEmail';<?php echo $click; ?>"><?php edu_e( "Log in" ); ?></button>
    <button class="forgotPasswordButton"
            onclick="this.form.eduadminpassword.required = false; this.form.eduadminpassword.value = ''; this.form.eduformloginaction.value = 'forgot';"><?php edu_e( "Forgot password" ); ?></button>
</div>
<?php if ( isset( EDU()->session['eduadminLoginError'] ) ) { ?>
    <div class="edu-modal warning" style="display: block; clear: both;">
	    <?php echo EDU()->session['eduadminLoginError']; ?>
    </div>
	<?php unset( EDU()->session['eduadminLoginError'] );
} ?>
<?php if ( isset( EDU()->session['eduadmin-forgotPassSent'] ) && EDU()->session['eduadmin-forgotPassSent'] == true ) {
	unset( EDU()->session['eduadmin-forgotPassSent'] );
	?>
    <div class="edu-modal warning" style="display: block; clear: both;">
		<?php edu_e( "A new password has been sent by email." ); ?>
    </div>
<?php } else if ( isset( EDU()->session['eduadmin-forgotPassSent'] ) && EDU()->session['eduadmin-forgotPassSent'] == false ) {
	unset( EDU()->session['eduadmin-forgotPassSent'] );
	?>
    <div class="edu-modal warning" style="display: block; clear: both;">
		<?php edu_e( "Could not send a new password by email." ); ?>
    </div>
<?php } ?>
