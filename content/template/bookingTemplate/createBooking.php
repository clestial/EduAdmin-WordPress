<?php
$eventId = $_REQUEST['eid'];

$singlePersonBooking = get_option('eduadmin-singlePersonBooking', false);
if ($singlePersonBooking) {
	include_once("__bookSingleParticipant.php");
} else {
	include_once("__bookMultipleParticipants.php");
}