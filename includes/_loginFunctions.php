<?php

function loginContactPerson($email, $password)
{
	global $api;
	global $token;
	$filter = new XFiltering();
	$f = new XFilter('Email', '=', $email);
	$filter->AddItem($f);
	$f = new XFilter('Loginpass', '=', $password);
	$filter->AddItem($f);
	$f = new XFilter('CanLogin', '=', true);
	$filter->AddItem($f);
	$cc = $api->GetCustomerContact($token, '', $filter->ToString(), true);
	if(count($cc) == 1)
	{
		$contact = $cc[0];
		$filter = new XFiltering();
		$f = new XFilter('CustomerID', '=', $contact->CustomerID);
		$filter->AddItem($f);
		$customers = $api->GetCustomer($token, '', $filter->ToString(), true);
		if(count($customers) == 1)
		{
			$customer = $customers[0];
			$user = new stdClass;
			$user->Contact = $contact;
			$user->Customer = $customer;
			$_SESSION['eduadmin-loginUser'] = $user;
			return $_SESSION['eduadmin-loginUser'];
		}
		else
		{
			return null;
		}

	}
	else
	{
		return null;
	}
}

function sendForgottenPassword($email) {
	global $api;
	global $token;
	$ccId = 0;

	$filter = new XFiltering();
	$f = new XFilter('Email', '=', $email);
	$filter->AddItem($f);
	$f = new XFilter('CanLogin', '=', true);
	$filter->AddItem($f);
	$cc = $api->GetCustomerContact($token, '', $filter->ToString(), false);
	if(count($cc) == 1)
		$ccId = current($cc)->CustomerContactID;

	if($ccId > 0) {
		$sent = $api->SendCustomerContactPassword($token, $ccId, get_bloginfo( 'name' ));
		return $sent;
	}

	return false;
}

function logoutUser()
{
	$surl = get_site_url();
	$cat = get_option('eduadmin-rewriteBaseUrl');

	$baseUrl = $surl . '/' . $cat;

	unset($_SESSION['eduadmin-loginUser']);
	echo("<script>location.href = '" . $baseUrl. "';</script>");
}

$apiUserId = get_option('eduadmin-api_user_id');
$apiHash = get_option('eduadmin-api_hash');

if(!$apiUserId || !$apiHash || (empty($apiUserId) || empty($apiHash)))
{
	echo 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
}
else
{
	$api = new EduAdminClient();
	$token = get_transient('eduadmin-token');
	if(!$token)
	{
		$token = $api->GetAuthToken($apiUserId, $apiHash);
		set_transient('eduadmin-token', $token, HOUR_IN_SECONDS);
	}
	else
	{
		$valid = $api->ValidateAuthToken($token);
		if(!$valid)
		{
			$token = $api->GetAuthToken($apiUserId, $apiHash);
			set_transient('eduadmin-token', $token, HOUR_IN_SECONDS);
		}
	}

	/* BACKEND FUNCTIONS FOR FORMS */
	if(isset($_POST['eduformloginaction']))
	{
		$act = $_POST['eduformloginaction'];
		if(isset($_POST['eduadminloginEmail']))
		{
			switch($act)
			{
				case "login":
					$loginContact = loginContactPerson($_POST['eduadminloginEmail'], $_POST['eduadminpassword']);
					if($loginContact)
					{
						$surl = get_site_url();
						$cat = get_option('eduadmin-rewriteBaseUrl');

						$baseUrl = $surl . '/' . $cat;
						echo("<script>location.href = '" . $baseUrl. "/profile/myprofile/';</script>");
					}
					else
					{
						$_SESSION['eduadminLoginError'] = edu__("Wrong email or password.");
					}
					break;
				case "forgot":
					$success = sendForgottenPassword($_POST['eduadminloginEmail']);
					$_SESSION['eduadmin-forgotPassSent'] = $success;
					break;
			}
		}
		else
		{
			$_SESSION['eduadminLoginError'] = edu__("You have to provide your e-mail adress.");
		}
	}
}
?>