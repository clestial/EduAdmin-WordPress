<div class="eduadmin loginForm">
    <div class="loginBox">
        <h2 class="loginTitle"><?php _e( "Login to My Pages", 'eduadmin-booking' ); ?></h2>
        <form action="" method="POST" onsubmit="">
            <input type="hidden" name="eduformloginaction" value=""/>
            <input type="hidden" name="eduReturnUrl" value="<?php echo @esc_attr( $_SERVER['HTTP_REFERER'] ); ?>"/>
			<?php
				$selectedLoginField = get_option( 'eduadmin-loginField', 'Email' );
				$loginLabel         = __( "E-mail address", 'eduadmin-booking' );
				$fieldType          = "text";
				switch ( $selectedLoginField ) {
					case "Email":
						$loginLabel = __( "E-mail address", 'eduadmin-booking' );
						$fieldType  = "email";
						break;
					case "CivicRegistrationNumber":
						$loginLabel = __( "Civic Registration Number", 'eduadmin-booking' );
						$fieldType  = "text";
						break;
					case "CustomerNumber":
						$loginLabel = __( "Customer number", 'eduadmin-booking' );
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
                           title="<?php echo esc_attr( sprintf( __( "Please enter your %s here", 'eduadmin-booking' ), $loginLabel ) ); ?>"
                           placeholder="<?php echo esc_attr( $loginLabel ); ?>"
                           value="<?php echo @esc_attr( sanitize_text_field( $_REQUEST["eduadminloginEmail"] ) ); ?>"/>
                </div>
            </label>
            <label>
                <div class="loginLabel"><?php _e( "Password", 'eduadmin-booking' ); ?></div>
                <div class="loginInput">
                    <input type="password" autocomplete="off" name="eduadminpassword" required
                           title="<?php echo esc_attr( __( "Please enter your password here", 'eduadmin-booking' ) ); ?>"
                           placeholder="<?php echo esc_attr( __( "Password", 'eduadmin-booking' ) ); ?>"/>
                </div>
            </label>
			<?php
				$click = "";
				if ( $selectedLoginField == "CivicRegistrationNumber" && get_option( 'eduadmin-validateCivicRegNo', false ) === "true" ) {
					$click = "if(!eduBookingView.ValidateCivicRegNo()) { alert('" . __( "Please enter a valid swedish civic registration number.", 'eduadmin-booking' ) . "');  return false; }";
				}
			?>
            <button class="loginButton cta-btn"
                    onclick="this.form.eduadminpassword.required = true; this.form.eduformloginaction.value = 'login';<?php echo $click; ?>"><?php _e( "Log in", 'eduadmin-booking' ); ?></button>
            <button class="forgotPasswordButton"
                    onclick="this.form.eduadminpassword.required = false; this.form.eduadminpassword.value = ''; this.form.eduformloginaction.value = 'forgot';"><?php _e( "Forgot password", 'eduadmin-booking' ); ?></button>
        </form>
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
	        <?php _e( "A new password has been sent by email.", 'eduadmin-booking' ); ?>
        </div>
	<?php } else if ( isset( EDU()->session['eduadmin-forgotPassSent'] ) && EDU()->session['eduadmin-forgotPassSent'] == false ) {
		unset( EDU()->session['eduadmin-forgotPassSent'] );
		?>
        <div class="edu-modal warning" style="display: block; clear: both;">
	        <?php _e( "Could not send a new password by email.", 'eduadmin-booking' ); ?>
        </div>
	<?php } ?>
</div>
