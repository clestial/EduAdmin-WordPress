<div class="checkEmailForm">
	<input type="hidden" name="edu-login-ver" value="<?php echo esc_attr( wp_create_nonce( 'edu-profile-login' ) ); ?>" />
	<input type="hidden" name="eduformloginaction" value="checkEmail" />
	<?php

	$login_value = ! empty( $_POST['eduadminloginEmail'] ) ? sanitize_text_field( $_POST['eduadminloginEmail'] ) : '';

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
	<h3>
		<?php
		/* translators: %s is the chosen field to use for login */
		echo esc_html( sprintf( __( 'Please enter your %s to continue.', 'eduadmin-booking' ), $login_label ) );
		?>
	</h3>
	<label>
		<div class="inputLabel"><?php echo esc_html( $login_label ); ?></div>
		<div class="inputHolder">
			<input type="<?php echo esc_attr( $field_type ); ?>" name="eduadminloginEmail"<?php echo( 'CivicRegistrationNumber' === $selected_login_field ? ' class="eduadmin-civicRegNo" onblur="eduBookingView.ValidateCivicRegNo();"' : '' ); ?>
				required autocomplete="off" title="<?php
			/* translators: %s is the chosen field to use for login */
			echo esc_attr( sprintf( __( 'Please enter your %s here', 'eduadmin-booking' ), $login_label ) );
			?>" placeholder="<?php echo esc_attr( $login_label ); ?>" value="<?php echo esc_attr( $login_value ); ?>" />
		</div>
	</label>
	<input type="submit" class="bookingLoginButton cta-btn"<?php echo( 'CivicRegistrationNumber' === $selected_login_field && 'true' === get_option( 'eduadmin-validateCivicRegNo', false ) ? ' onclick="if(!eduBookingView.ValidateCivicRegNo()) { alert(\'' . esc_js( __( 'Please enter a valid swedish civic registration number.', 'eduadmin-booking' ) ) . '\'); return false; }"' : '' ); ?>
		value="<?php echo esc_attr__( 'Continue', 'eduadmin-booking' ); ?>" />
</div>
