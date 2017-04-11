<?php

function DecryptApiKey($key) {
	$decrypted = explode('|', base64_decode($key));
	if(count($decrypted) == 2)
	{
		$apiKey = new stdClass();
		$apiKey->UserId = $decrypted[0];
		$apiKey->Hash = $decrypted[1];
		return $apiKey;
	}
	return false;
}

function edu_getTimers() {
	global $eduapi;
	if($eduapi->timers)
	{
		echo "<!-- EduAdmin Booking - Timers -->\n";
		$totalValue = 0;
		foreach($eduapi->timers as $timer => $value)
		{
			echo "<!-- " . $timer . ": " . round($value * 1000, 2) . "ms -->\n";
			$totalValue += $value;
		}
		echo "<!-- EduAdmin Total: " . round($totalValue * 1000, 2) . "ms -->\n";
		echo "<!-- /EduAdmin Booking - Timers -->\n";
	}
}
