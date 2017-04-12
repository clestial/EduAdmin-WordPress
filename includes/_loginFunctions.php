<?php

function loginContactPerson($loginValue, $password) {
	$eduapi = EDU()->api;
	$edutoken = EDU()->get_token();

	$loginField = get_option('eduadmin-loginField', 'Email');

	$filter = new XFiltering();
	$f = new XFilter($loginField, '=', $loginValue);
	$filter->AddItem($f);
	$f = new XFilter('Loginpass', '=', $password);
	$filter->AddItem($f);
	$f = new XFilter('CanLogin', '=', true);
	$filter->AddItem($f);
	$f = new XFilter('Disabled', '=', false);
	$filter->AddItem($f);
	$cc = $eduapi->GetCustomerContact($edutoken, '', $filter->ToString(), true);
	if (count($cc) == 1) {
		$contact = $cc[0];
		$filter = new XFiltering();
		$f = new XFilter('CustomerID', '=', $contact->CustomerID);
		$filter->AddItem($f);
		$f = new XFilter('Disabled', '=', false);
		$filter->AddItem($f);
		$customers = $eduapi->GetCustomerV2($edutoken, '', $filter->ToString(), true);
		if (count($customers) == 1) {
			$customer = $customers[0];
			$user = new stdClass;
			$c1 = json_encode($contact);
			$user->Contact = json_decode($c1);
			$c2 = json_encode($customer);
			$user->Customer = json_decode($c2);
			$_SESSION['eduadmin-loginUser'] = $user;
			return $_SESSION['eduadmin-loginUser'];
		} else {
			return null;
		}

	} else {
		return null;
	}
}

function sendForgottenPassword($loginValue) {
	$eduapi = EDU()->api;
	$edutoken = EDU()->get_token();
	$ccId = 0;

	$loginField = get_option('eduadmin-loginField', 'Email');

	$filter = new XFiltering();
	$f = new XFilter($loginField, '=', $loginValue);
	$filter->AddItem($f);
	$f = new XFilter('CanLogin', '=', true);
	$filter->AddItem($f);
	$cc = $eduapi->GetCustomerContact($edutoken, '', $filter->ToString(), false);
	if (count($cc) == 1) {
			$ccId = current($cc)->CustomerContactID;
	}

	if ($ccId > 0 && !empty(current($cc)->Email)) {
		$sent = $eduapi->SendCustomerContactPassword($edutoken, $ccId, get_bloginfo('name'));
		return $sent;
	}

	return false;
}

function logoutUser() {
	$surl = get_home_url();
	$cat = get_option('eduadmin-rewriteBaseUrl');

	$baseUrl = $surl . '/' . $cat;

	unset($_SESSION['eduadmin-loginUser']);
	unset($_SESSION['needsLogin']);
	unset($_SESSION['checkEmail']);
	wp_redirect($baseUrl . edu_getQueryString("q"));
	exit();
}

add_action(
'wp_loaded',
function() {

	$apiKey = get_option('eduadmin-api-key');

	if (!$apiKey || empty($apiKey)) {
		add_action('admin_notices', array('EduAdmin', 'SetupWarning'));
	} else {
		$key = DecryptApiKey($apiKey);
		if (!$key) {
			add_action('admin_notices', array('EduAdmin', 'SetupWarning'));
			return;
		}

		$edutoken = EDU()->get_token();

		$surl = get_home_url();
		$cat = get_option('eduadmin-rewriteBaseUrl');
		$baseUrl = $surl . '/' . $cat;

		if ( stristr( $_SERVER['REQUEST_URI'], "/$cat/profile/logout" ) !== false ) {
			logoutUser();
		}

		/* BACKEND FUNCTIONS FOR FORMS */
		if (isset($_POST['eduformloginaction'])) {
			$act = $_POST['eduformloginaction'];
			if (isset($_POST['eduadminloginEmail'])) {
				switch ($act) {
					case "login":
						$loginContact = loginContactPerson($_POST['eduadminloginEmail'], $_POST['eduadminpassword']);
						if ($loginContact) {
							if (isset($_REQUEST['eduReturnUrl']) && !empty($_REQUEST['eduReturnUrl'])) {
								wp_redirect($_REQUEST['eduReturnUrl']);
							} else {
								wp_redirect($baseUrl . "/profile/myprofile/" . edu_getQueryString());
							}
							exit();
						} else {
							$_SESSION['eduadminLoginError'] = edu__("Wrong username or password.");
						}
						break;
					case "forgot":
						$success = sendForgottenPassword($_POST['eduadminloginEmail']);
						$_SESSION['eduadmin-forgotPassSent'] = $success;
						break;
				}
			} else {
				$_SESSION['eduadminLoginError'] = edu__("You have to provide your login credentials.");
			}
		}
	}
});