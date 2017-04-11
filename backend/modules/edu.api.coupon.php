<?php
date_default_timezone_set('UTC');

if (!function_exists('edu_api_check_coupon_code'))
{
	function edu_api_check_coupon_code($request) {
		header("Content-type: application/json; charset=UTF-8");
		global $eduapi;

		$edutoken = edu_decrypt("edu_js_token_crypto", $request["token"]);

		$objectID = $request['objectId'];
		$categoryID = $request['categoryId'];
		$code = $request['code'];
		$eduapi->debug = true;
		$vcode = $eduapi->CheckCouponCode($edutoken, $objectID, $categoryID, $code);
		$eduapi->debug = false;
		return json_encode($vcode);
	}
}

if (isset($_REQUEST['module']) && $_REQUEST['module'] == "check_coupon_code")
{
	echo edu_api_check_coupon_code($_REQUEST);
}