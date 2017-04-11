<div class="checkEmailForm">
	<input type="hidden" name="bookingLoginAction" value="checkEmail" />
	<?php
	$selectedLoginField = get_option('eduadmin-loginField', 'Email');
	$loginLabel = edu__("E-mail address");
	switch ($selectedLoginField) {
		case "Email":
			$loginLabel = edu__("E-mail address");
			break;
		case "CivicRegistrationNumber":
			$loginLabel = edu__("Civic Registration Number");
			break;
		case "CustomerNumber":
			$loginLabel = edu__("Customer number");
			break;
	}
	?>
	<h3><?php echo sprintf(edu__("Please enter your %s to continue."), $loginLabel); ?></h3>
	<label>
		<div class="inputLabel"><?php echo $loginLabel; ?></div>
		<div class="inputHolder">
			<input type="text" name="eduadminloginEmail"<?php echo ($selectedLoginField == "CivicRegistrationNumber" ? " class=\"eduadmin-civicRegNo\" onblur=\"eduBookingView.ValidateCivicRegNo();\"" : ""); ?> required title="<?php echo esc_attr(sprintf(edu__("Please enter your %s here"), $loginLabel)); ?>" placeholder="<?php echo esc_attr($loginLabel); ?>" value="<?php echo @esc_attr($_REQUEST["eduadminloginEmail"]); ?>" />
		</div>
	</label>
	<input type="submit" class="bookingLoginButton"<?php echo ($selectedLoginField == "CivicRegistrationNumber" && get_option('eduadmin-validateCivicRegNo', false) === "true" ? " onclick=\"if(!eduBookingView.ValidateCivicRegNo()) { alert('" . edu__("Please enter a valid swedish civic registration number.") . "'); return false; }\"" : ""); ?> value="<?php echo esc_attr(edu__("Continue")); ?>" />
</div>