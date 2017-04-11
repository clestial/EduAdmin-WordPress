<?php
include_once("loApiClient.php");
if (isset($_REQUEST['aui']) && isset($_REQUEST['ah'])) {
	$lo = new EduAdminClient();
	$token = $lo->GetAuthToken($_REQUEST['aui'], $_REQUEST['ah']);
	$validToken = $lo->ValidateAuthToken($token);

	echo json_encode($validToken);
}