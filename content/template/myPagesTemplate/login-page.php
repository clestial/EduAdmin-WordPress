<div class="eduadmin loginForm">
	<div class="loginBox">
		<h2 class="loginTitle"><?php esc_html_e( 'Login to My Pages', 'eduadmin-booking' ); ?></h2>
		<form action="" method="POST" onsubmit="">
			<input type="hidden" name="edu-login-ver" value="<?php echo esc_attr( wp_create_nonce( 'edu-profile-login' ) ); ?>" />
			<input type="hidden" name="eduformloginaction" value="" />
			<input type="hidden" name="eduReturnUrl" value="<?php echo esc_attr( ! empty( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '' ); ?>" />
			<?php
			$selected_login_field = get_option( 'eduadmin-loginField', 'Email' );
			$login_label          = __( 'E-mail address', 'eduadmin-booking' );
			$field_type           = 'text';
			switch ( $selected_login_field ) {
				case 'Email':
					$login_label = __( 'E-mail address', 'eduadmin-booking' );
					$field_type  = 'email';
					break;
				case 'CivicRegistrationNumber':
					$login_label = __( 'Civic Registration Number', 'eduadmin-booking' );
					$field_type  = 'text';
					break;
				case 'CustomerNumber':
					$login_label = __( 'Customer number', 'eduadmin-booking' );
					$field_type  = 'text';
					break;
			}
			?>
			<label>
				<div class="loginLabel"><?php echo esc_html( $login_label ); ?></div>
				<div class="loginInput">
					<input type="<?php echo esc_attr( $field_type ); ?>" name="eduadminloginEmail"<?php echo( 'CivicRegistrationNumber' === $selected_login_field ? ' class="eduadmin-civicRegNo" onblur="eduBookingView.ValidateCivicRegNo();"' : '' ); ?>
						required autocomplete="off" title="<?php echo esc_attr( sprintf( __( 'Please enter your %s here', 'eduadmin-booking' ), $login_label ) ); ?>" placeholder="<?php echo esc_attr( $login_label ); ?>" value="<?php echo esc_attr( ! empty( $_POST['eduadminloginEmail'] ) ? sanitize_text_field( $_POST['eduadminloginEmail'] ) : '' ); ?>" />
				</div>
			</label>
			<label>
				<div class="loginLabel"><?php esc_html_e( 'Password', 'eduadmin-booking' ); ?></div>
				<div class="loginInput">
					<input type="password" autocomplete="off" name="eduadminpassword" required title="<?php echo esc_attr__( 'Please enter your password here', 'eduadmin-booking' ); ?>" placeholder="<?php echo esc_attr__( 'Password', 'eduadmin-booking' ); ?>" />
				</div>
			</label>
			<?php
			$click = '';
			if ( 'CivicRegistrationNumber' === $selected_login_field && get_option( 'eduadmin-validateCivicRegNo', false ) === 'true' ) {
				$click = 'if(!eduBookingView.ValidateCivicRegNo()) { alert(\'' . __( 'Please enter a valid swedish civic registration number.', 'eduadmin-booking' ) . '\');  return false; }';
			}
			?>
			<button class="loginButton cta-btn" onclick="this.form.eduadminpassword.required = true; this.form.eduformloginaction.value = 'login';<?php echo esc_attr( $click ); ?>"><?php esc_html_e( 'Log in', 'eduadmin-booking' ); ?></button>
			<button class="forgotPasswordButton neutral-btn" onclick="this.form.eduadminpassword.required = false; this.form.eduadminpassword.value = ''; this.form.eduformloginaction.value = 'forgot';"><?php esc_html_e( 'Forgot password', 'eduadmin-booking' ); ?></button>
		</form>
	</div>

	<?php
	if ( isset( EDU()->session['eduadminLoginError'] ) ) {
		?>
		<div class="edu-modal warning" style="display: block; clear: both;">
			<?php echo esc_html( EDU()->session['eduadminLoginError'] ); ?>
		</div>
		<?php
		unset( EDU()->session['eduadminLoginError'] );
	}

	if ( ! empty( EDU()->session['eduadmin-forgotPassSent'] ) && true === EDU()->session['eduadmin-forgotPassSent'] ) {
		unset( EDU()->session['eduadmin-forgotPassSent'] );
		?>
		<div class="edu-modal warning" style="display: block; clear: both;">
			<?php esc_html_e( 'A new password has been sent by email.', 'eduadmin-booking' ); ?>
		</div>
		<?php
	} elseif ( ! empty( EDU()->session['eduadmin-forgotPassSent'] ) && false === EDU()->session['eduadmin-forgotPassSent'] ) {
		unset( EDU()->session['eduadmin-forgotPassSent'] );
		?>
		<div class="edu-modal warning" style="display: block; clear: both;">
			<?php esc_html_e( 'Could not send a new password by email.', 'eduadmin-booking' ); ?>
		</div>
		<?php
	}
	?>
</div>
