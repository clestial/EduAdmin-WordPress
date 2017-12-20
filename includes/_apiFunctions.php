<?php

	function DecryptApiKey( $key ) {
		$decrypted = explode( '|', base64_decode( $key ) );
		if ( count( $decrypted ) == 2 ) {
			$apiKey         = new stdClass();
			$apiKey->UserId = $decrypted[0];
			$apiKey->Hash   = $decrypted[1];

			return $apiKey;
		}

		return false;
	}

	if ( ! function_exists( 'edu_encrypt' ) ) {
		function edu_encrypt( $key, $toEncrypt ) {
			return base64_encode( openssl_encrypt( $toEncrypt, "AES-128-ECB", md5( $key ), OPENSSL_RAW_DATA, "" ) );
		}
	}

	if ( ! function_exists( 'edu_decrypt' ) ) {
		function edu_decrypt( $key, $toDecrypt ) {
			return rtrim( openssl_decrypt( base64_decode( $toDecrypt ), "AES-128-ECB", md5( $key ), OPENSSL_RAW_DATA, "" ) );
		}
	}

	function edu_getTimers() {
		global $eduapi;
		if ( $eduapi->timers ) {
			echo "<!-- EduAdmin Booking - Timers -->\n";
			$totalValue = 0;
			foreach ( $eduapi->timers as $timer => $value ) {
				echo "<!-- " . $timer . ": " . round( $value * 1000, 2 ) . "ms -->\n";
				$totalValue += $value;
			}
			echo "<!-- EduAdmin Total: " . round( $totalValue * 1000, 2 ) . "ms -->\n";
			echo "<!-- /EduAdmin Booking - Timers -->\n";
		}
	}
