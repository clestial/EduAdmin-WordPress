<div class="checkEmailForm">
    <input type="hidden" name="eduformloginaction" value="checkEmail"/>
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
    <h3><?php echo sprintf( __( "Please enter your %s to continue.", 'eduadmin-booking' ), $loginLabel ); ?></h3>
    <label>
        <div class="inputLabel"><?php echo $loginLabel; ?></div>
        <div class="inputHolder">
            <input type="<?php echo $fieldType; ?>"
                   name="eduadminloginEmail"<?php echo( $selectedLoginField == "CivicRegistrationNumber" ? " class=\"eduadmin-civicRegNo\" onblur=\"eduBookingView.ValidateCivicRegNo();\"" : "" ); ?>
                   required autocomplete="off"
                   title="<?php echo esc_attr( sprintf( __( "Please enter your %s here", 'eduadmin-booking' ), $loginLabel ) ); ?>"
                   placeholder="<?php echo esc_attr( $loginLabel ); ?>"
                   value="<?php echo @esc_attr( sanitize_text_field( $_REQUEST[ "eduadminloginEmail" ] ) ); ?>"/>
        </div>
    </label>
    <input type="submit"
           class="bookingLoginButton cta-btn"<?php echo( $selectedLoginField == "CivicRegistrationNumber" && get_option( 'eduadmin-validateCivicRegNo', false ) === "true" ? " onclick=\"if(!eduBookingView.ValidateCivicRegNo()) { alert('" . __( "Please enter a valid swedish civic registration number.", 'eduadmin-booking' ) . "'); return false; }\"" : "" ); ?>
           value="<?php echo esc_attr( __( "Continue", 'eduadmin-booking' ) ); ?>"/>
</div>