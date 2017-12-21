<?php
	function edu_LoadPhrases() {
		EDU()->timers[ __METHOD__ ] = microtime( true );

		if ( EDU()->phrases && ! empty( EDU()->phrases ) && is_array( EDU()->phrases ) ) {
			EDU()->timers[ __METHOD__ ] = microtime( true ) - EDU()->timers[ __METHOD__ ];

			return EDU()->phrases;
		}

		$phrases = get_transient( 'eduadmin-phrases' );
		if ( ! $phrases ) {
			$phrases         = get_option( 'eduadmin-phrases' );
			$file            = file_get_contents( EDUADMIN_PLUGIN_PATH . '/includes/defaultPhrases.json' );
			$originalPhrases = json_decode( $file );
			if ( ! $phrases ) {
				$phrases = $originalPhrases;
				update_option( 'eduadmin-phrases', json_encode( $phrases ) );
			} else {
				$phrases = json_decode( $phrases, true );
				foreach ( $originalPhrases as $ph => $val ) {
					if ( ! isset( $phrases[ $ph ] ) ) {
						$phrases[ $ph ] = $val;
					}
				}
			}

			set_transient( 'eduadmin-phrases', $phrases, DAY_IN_SECONDS );
		}

		$nPhrases = array();
		foreach ( $phrases as $p => $ph ) {
			$nPhrases[ $p ] = array();
			if ( ! is_array( $ph ) ) {
				$nPhrases[ $p ]["OldPhrase"] = $ph;
			} else {
				$nPhrases[ $p ]["OldPhrase"] = $ph["OldPhrase"];
			}
			$nPhrases[ $p ]["NewPhrase"] = __( $p, "eduadmin-booking" );
		}

		if ( is_array( $nPhrases ) ) {
			EDU()->phrases = $nPhrases;
		} else {
			EDU()->phrases = (array) $nPhrases;
		}

		EDU()->timers[ __METHOD__ ] = microtime( true ) - EDU()->timers[ __METHOD__ ];

		return EDU()->phrases;
	}

	function edu__( $key ) {
		$phrases = edu_LoadPhrases();
		if ( ! array_key_exists( $key, $phrases ) ) {
			$phrases[ $key ] = $key;
			update_option( 'eduadmin-phrases', json_encode( $phrases ) );
		}

		if ( isset( $phrases[ $key ]["OldPhrase"] ) ) {
			if ( $phrases[ $key ]["OldPhrase"] != $key ) {
				return $phrases[ $key ]["OldPhrase"];
			}
		}

		return __( $key, "eduadmin-booking" ); //$phrases[$key];
	}

	function edu_e( $key ) {
		echo edu__( $key );
	}

	function edu_n( $single, $plural, $number ) {
		return $number === 1 ? edu__( $single ) : edu__( $plural );
	}