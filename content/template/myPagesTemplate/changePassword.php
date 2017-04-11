<?php
$user = $_SESSION['eduadmin-loginUser'];
$contact = $user->Contact;
$customer = $user->Customer;
if (isset($_POST['eduaction']) && $_POST['eduaction'] == "savePassword") {

	if ($_POST['currentPassword'] == $contact->Loginpass)
	{
		if (strlen($_POST['newPassword']) == 0)
		{
			$msg = edu__("You must fill in a password to change it.");
		}
		else if ($_POST['newPassword'] != $_POST['confirmPassword'])
		{
			$msg = edu__("Given password does not match.");
		}
		else if ($_POST['newPassword'] == $_POST['currentPassword'])
		{
			$msg = edu__("You cannot set your password to be the same as the one before.");
		}
		else
		{
			global $eduapi;
			global $edutoken;

			$contact->Loginpass = trim($_POST['newPassword']);
			$eduapi->SetCustomerContact($edutoken, array($contact));
		}
	}
}
?>
<div class="eduadmin">
<?php
$tab = "profile";
include_once("login_tab_header.php");
?>
<h2><?php edu_e("Change password"); ?></h2>
<form action="" method="POST">
		<input type="hidden" name="eduaction" value="savePassword" />
		<div class="eduadminContactInformation">
			<h3><?php edu_e("Contact information"); ?></h3>
			<label>
				<div class="inputLabel"><?php edu_e("Current password"); ?></div>
				<div class="inputHolder"><input type="password" name="currentPassword" required placeholder="<?php echo esc_attr(edu__("Current password")); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e("New password"); ?></div>
				<div class="inputHolder"><input type="password" name="newPassword" required placeholder="<?php echo esc_attr(edu__("New password")); ?>" /></div>
			</label>
			<label>
				<div class="inputLabel"><?php edu_e("Confirm password"); ?></div>
				<div class="inputHolder"><input type="password" name="confirmPassword" required placeholder="<?php echo esc_attr(edu__("Confirm password")); ?>" /></div>
			</label>
		</div>
		<button class="profileSaveButton"><?php edu_e("Save"); ?></button>
</form>
<?php if (isset($msg)) { ?>
	<div class="edu-modal warning" style="display: block; clear: both;">
		<?php echo $msg; ?>
	</div>
<?php } ?>
<?php include_once("login_tab_footer.php"); ?>
</div>