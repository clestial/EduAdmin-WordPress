<div class="eduadmin loginForm">
	<div class="loginBox">
		<h2 class="loginTitle"><?php edu_e("Login to My Pages"); ?></h2>
		<form action="" method="POST" onsubmit="">
			<input type="hidden" name="eduformloginaction" value="" />
			<input type="hidden" name="eduReturnUrl" value="<?php echo @esc_attr($_SERVER['HTTP_REFERER']); ?>" />
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
			<label>
				<div class="loginLabel"><?php echo $loginLabel; ?></div>
				<div class="loginInput">
					<input type="text" name="eduadminloginEmail"<?php echo ($selectedLoginField == "CivicRegistrationNumber" ? " class=\"eduadmin-civicRegNo\" onblur=\"eduBookingView.ValidateCivicRegNo();\"" : ""); ?> required title="<?php echo esc_attr(sprintf(edu__("Please enter your %s here"), $loginLabel)); ?>" placeholder="<?php echo esc_attr($loginLabel); ?>" value="<?php echo @esc_attr($_REQUEST["eduadminloginEmail"]); ?>" />
				</div>
			</label>
			<label>
				<div class="loginLabel"><?php edu_e("Password"); ?></div>
				<div class="loginInput">
					<input type="password" name="eduadminpassword" required title="<?php echo esc_attr(edu__("Please enter your password here")); ?>" placeholder="<?php echo esc_attr(edu__("Password")); ?>" />
				</div>
			</label>
			<?php
			$click = "";
			if ($selectedLoginField == "CivicRegistrationNumber" && get_option('eduadmin-validateCivicRegNo', false) === "true") {
				$click = "if(!eduBookingView.ValidateCivicRegNo()) { alert('" . edu__("Please enter a valid swedish civic registration number.") . "');  return false; }";
			}
			?>
			<button class="loginButton" onclick="this.form.eduadminpassword.required = true; this.form.eduformloginaction.value = 'login';<?php echo $click; ?>"><?php edu_e("Log in"); ?></button>
			<button class="forgotPasswordButton" onclick="this.form.eduadminpassword.required = false; this.form.eduadminpassword.value = ''; this.form.eduformloginaction.value = 'forgot';"><?php edu_e("Forgot password"); ?></button>
		</form>
	</div>

	<?php if (isset($_SESSION['eduadminLoginError'])) { ?>
	<div class="edu-modal warning" style="display: block; clear: both;">
		<?php echo $_SESSION['eduadminLoginError']; ?>
	</div>
	<?php unset($_SESSION['eduadminLoginError']); } ?>
	<?php if (isset($_SESSION['eduadmin-forgotPassSent']) && $_SESSION['eduadmin-forgotPassSent'] == true) {
		unset($_SESSION['eduadmin-forgotPassSent']);
	?>
	<div class="edu-modal warning" style="display: block; clear: both;">
		<?php edu_e("A new password has been sent by email."); ?>
	</div>
	<?php } else if (isset($_SESSION['eduadmin-forgotPassSent']) && $_SESSION['eduadmin-forgotPassSent'] == false) {
		unset($_SESSION['eduadmin-forgotPassSent']);
	?>
	<div class="edu-modal warning" style="display: block; clear: both;">
		<?php edu_e("Could not send a new password by email."); ?>
	</div>
	<?php } ?>
</div>
