<?php
/**
 * Returns the timezone string for a site, even if it's set to a UTC offset
 *
 * Adapted from http://www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
 *
 * @return string valid PHP timezone string
 */
if ( ! function_exists( 'wp_get_timezone_string' ) ) {
	function wp_get_timezone_string() {
		$t = EDU()->start_timer( __METHOD__ );
		// if site timezone string exists, return it
		if ( $timezone = get_option( 'timezone_string' ) ) {
			EDU()->stop_timer( $t );

			return $timezone;
		}

		// get UTC offset, if it isn't set then return UTC
		if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) ) {
			EDU()->stop_timer( $t );

			return 'UTC';
		}

		// adjust UTC offset from hours to seconds
		$utc_offset *= 3600;

		// attempt to guess the timezone string from the UTC offset
		if ( $timezone = timezone_name_from_abbr( '', $utc_offset, 0 ) ) {
			EDU()->stop_timer( $t );

			return $timezone;
		}

		// last try, guess timezone string manually
		$is_dst = date( 'I' );

		foreach ( timezone_abbreviations_list() as $abbr ) {
			foreach ( $abbr as $city ) {
				if ( $city['dst'] === $is_dst && $city['offset'] === $utc_offset ) {
					EDU()->stop_timer( $t );

					return $city['timezone_id'];
				}
			}
		}

		// fallback to UTC
		EDU()->stop_timer( $t );

		return 'UTC';
	}
}

function edu_get_percent_from_values( $current_value, $max_value ) {
	if ( 0 === $current_value || 0 === $max_value ) {
		return 'percentUnknown';
	}
	$percent = ( $current_value / $max_value ) * 100;

	return edu_get_percent_class( $percent );
}

function edu_get_percent_class( $percent ) {
	if ( $percent >= 100 ) {
		return 'percent100';
	} elseif ( $percent >= 90 ) {
		return 'percent90';
	} elseif ( $percent >= 80 ) {
		return 'percent80';
	} elseif ( $percent >= 70 ) {
		return 'percent70';
	} elseif ( $percent >= 60 ) {
		return 'percent60';
	} elseif ( $percent >= 50 ) {
		return 'percent50';
	} elseif ( $percent >= 40 ) {
		return 'percent40';
	} elseif ( $percent >= 30 ) {
		return 'percent30';
	} elseif ( $percent >= 20 ) {
		return 'percent20';
	} elseif ( $percent >= 10 ) {
		return 'percent10';
	}

	return 'percent0';
}

function edu_getQueryString( $prepend = '?', $remove_parameters = array() ) {
	$t = EDU()->start_timer( __METHOD__ );
	array_push( $remove_parameters, 'eduadmin-thankyou' );
	array_push( $remove_parameters, 'q' );
	foreach ( $remove_parameters as $par ) {
		unset( $_GET[ $par ] );
	}
	if ( ! empty( $_GET ) ) {
		EDU()->stop_timer( $t );

		return $prepend . http_build_query( $_GET );
	}
	EDU()->stop_timer( $t );

	return '';
}

function getSpotsLeft( $free_spots, $max_spots, $spot_option = 'exactNumbers', $spot_settings = "1-5\n5-10\n10+", $always_few_spots = 3 ) {
	$t = EDU()->start_timer( __METHOD__ );
	if ( 0 === $max_spots ) {
		EDU()->stop_timer( $t );

		return __( 'Spots left', 'eduadmin-booking' );
	}

	if ( $free_spots <= 0 ) {
		EDU()->stop_timer( $t );

		return __( 'No spots left', 'eduadmin-booking' );
	}

	switch ( $spot_option ) {
		case 'exactNumbers':
			EDU()->stop_timer( $t );

			/* translators: 1: Number of spots */

			return sprintf( _n( '%1$s spot left', '%1$s spots left', $free_spots, 'eduadmin-booking' ), $free_spots );
		case 'onlyText':
			$few_spots_limit = $always_few_spots;
			if ( $free_spots > ( $max_spots - $few_spots_limit ) ) {
				EDU()->stop_timer( $t );

				return __( 'Spots left', 'eduadmin-booking' );
			} elseif ( $free_spots <= ( $max_spots - $few_spots_limit ) && 1 !== $free_spots ) {
				EDU()->stop_timer( $t );

				return __( 'Few spots left', 'eduadmin-booking' );
			} elseif ( 1 === $free_spots ) {
				EDU()->stop_timer( $t );

				return __( 'One spot left', 'eduadmin-booking' );
			} elseif ( $free_spots <= 0 ) {
				EDU()->stop_timer( $t );

				return __( 'No spots left', 'eduadmin-booking' );
			}

			return __( 'Spots left', 'eduadmin-booking' );
		case 'intervals':
			$interval = $spot_settings;
			if ( empty( $interval ) ) {
				EDU()->stop_timer( $t );

				/* translators: 1: Number of spots */

				return sprintf( _n( '%1$s spot left', '%1$s spots left', $free_spots, 'eduadmin-booking' ), $free_spots );
			} else {
				$lines = explode( "\n", $interval );
				foreach ( $lines as $line ) {
					if ( stripos( $line, '-' ) > -1 ) {
						$range = explode( '-', $line );
						$min   = $range[0];
						$max   = $range[1];
						if ( $free_spots <= $max && $free_spots >= $min ) {
							EDU()->stop_timer( $t );

							/* translators: 1: Number of spots */

							return sprintf( __( '%1$s spots left', 'eduadmin-booking' ), $line );
						}
					} elseif ( stripos( $line, '+' ) > -1 ) {
						EDU()->stop_timer( $t );

						/* translators: 1: Number of spots */

						return sprintf( __( '%1$s spots left', 'eduadmin-booking' ), $line );
					}
				}
				EDU()->stop_timer( $t );

				/* translators: 1: Number of spots */

				return sprintf( _n( '%1$s spot left', '%1$s spots left', $free_spots, 'eduadmin-booking' ), $free_spots );
			}

		case 'alwaysFewSpots':
			$min_participants = $always_few_spots;
			if ( ( $max_spots - $free_spots ) >= $min_participants ) {
				EDU()->timers[ __METHOD__ ] = microtime( true ) - EDU()->timers[ __METHOD__ ];

				return __( 'Few spots left', 'eduadmin-booking' );
			}
			EDU()->stop_timer( $t );

			return __( 'Spots left', 'eduadmin-booking' );
		default:
			EDU()->stop_timer( $t );

			return '';
	}
}

function getUTF8( $input ) {
	$order = array( 'utf-8', 'iso-8859-1', 'iso-8859-15', 'windows-1251' );
	if ( 'UTF-8' === mb_detect_encoding( $input, $order, true ) ) {
		return $input;
	}

	return mb_convert_encoding( $input, 'utf-8', $order );
}

function dateVersion( $date ) {
	return sprintf(
		'%1$s-%2$s-%3$s.%4$s',
		date( 'Y', $date ),
		date( 'm', $date ),
		date( 'd', $date ),
		date( 'His', $date )
	);
}

function convertToMoney( $value, $currency = 'SEK', $decimal = ',', $thousand = ' ' ) {
	$d = $value;
	if ( empty( $d ) ) {
		$d = 0;
	}

	$d = sprintf( '%1$s %2$s', number_format( $d, 0, $decimal, $thousand ), $currency );

	return $d;
}

function GetDisplayDate( $in_date, $short = true ) {
	$t      = EDU()->start_timer( __METHOD__ );
	$months = $short ? EDU()->shortMonths : EDU()->months;

	$year     = date( 'Y', strtotime( $in_date ) );
	$now_year = date( 'Y' );
	EDU()->stop_timer( $t );

	return '<span class="eduadmin-dateText">' . date( 'd', strtotime( $in_date ) ) . ' ' . $months[ date( 'n', strtotime( $in_date ) ) ] . ( $now_year !== $year ? ' ' . $year : '' ) . '</span>';
}

function GetLogicalDateGroups( $dates, $short = false, $event = null, $show_days = false ) {
	$t = EDU()->start_timer( __METHOD__ );
	if ( count( $dates ) > 3 ) {
		$short     = true;
		$show_days = true;
	}

	$n_dates = getRangeFromDays( $dates, $short, $event, $show_days );
	EDU()->stop_timer( $t );

	return join( '<span class="edu-dateSeparator"></span>', $n_dates );
}

// Copied from http://codereview.stackexchange.com/a/83095/27610
function getRangeFromDays( $days, $short, $event, $show_days ) {
	$t = EDU()->start_timer( __METHOD__ );
	sort( $days );
	$start_date  = $days[0];
	$finish_date = $days[ count( $days ) - 1 ];
	$result      = array();
	// walk through the dates, breaking at gaps
	foreach ( $days as $key => $date ) {
		if ( ( $key > 0 ) && ( strtotime( $date['StartDate'] ) - strtotime( $days[ $key - 1 ]['StartDate'] ) > 99999 ) ) {
			$result[]   = GetStartEndDisplayDate( $start_date, $days[ $key - 1 ], $short, $event, $show_days );
			$start_date = $date;
		}
	}
	// force the end
	$result[] = GetStartEndDisplayDate( $start_date, $finish_date, $short, $event, $show_days );

	if ( count( $result ) > 3 ) {
		$n_res = array();
		$ret   =
			'<span class="edu-manyDays" title="' . esc_attr__( 'Show schedule', 'eduadmin-booking' ) . '" onclick="edu_openDatePopup(this);">' .
			/* translators: 1: Number of days 2: Date range */
			esc_html( sprintf( __( '%1$d days between %2$s', 'eduadmin-booking' ), count( $days ), GetStartEndDisplayDate( $days[0], end( $days ), $short, $show_days ) ) ) .
			'</span><div class="edu-DayPopup">
<b>' . esc_html__( 'Schedule', 'eduadmin-booking' ) . '</b><br />
' . join( "<br />\n", $result ) . '
<br />
<a href="javascript://" onclick="edu_closeDatePopup(event, this);">' . esc_html__( 'Close', 'eduadmin-booking' ) . '</a>
</div>';

		$n_res[] = $ret;
		EDU()->stop_timer( $t );

		return $n_res;
	}
	EDU()->stop_timer( $t );

	return $result;
}

function GetStartEndDisplayDate( $start_date, $end_date, $short = false, $event, $show_days = false ) {
	$t         = EDU()->start_timer( __METHOD__ );
	$week_days = $short ? EDU()->shortWeekDays : EDU()->weekDays;
	$months    = $short ? EDU()->shortMonths : EDU()->months;

	$start_year  = date( 'Y', strtotime( $start_date['StartDate'] ) );
	$start_month = date( 'n', strtotime( $start_date['StartDate'] ) );
	$end_year    = date( 'Y', strtotime( $end_date['EndDate'] ) );
	$end_month   = date( 'n', strtotime( $end_date['EndDate'] ) );
	$now_year    = date( 'Y' );

	$str = '<span class="eduadmin-dateText">';

	if ( $show_days ) {
		$str .= $week_days[ date( 'N', strtotime( $start_date['StartDate'] ) ) ] . ' ';
	}
	$str .= date( 'd', strtotime( $start_date['StartDate'] ) );
	if ( date( 'Y-m-d', strtotime( $start_date['StartDate'] ) ) !== date( 'Y-m-d', strtotime( $end_date['EndDate'] ) ) ) {
		if ( $start_year === $end_year ) {
			if ( $start_month === $end_month ) {
				if ( $show_days && ( date( 'H:i', strtotime( $start_date['StartDate'] ) ) !== date( 'H:i', strtotime( $end_date['StartDate'] ) ) && date( 'H:i', strtotime( $start_date['EndDate'] ) ) !== date( 'H:i', strtotime( $end_date['EndDate'] ) ) )
				) {
					$str .= ' ' . date( 'H:i', strtotime( $start_date['StartDate'] ) ) . '-' . date( 'H:i', strtotime( $start_date['EndDate'] ) );
				}
				$str .= ' - ';
				if ( $show_days ) {
					$str .= $week_days[ date( 'N', strtotime( $end_date['EndDate'] ) ) ] . ' ';
				}
				$str .= date( 'd', strtotime( $end_date['EndDate'] ) );
				$str .= ' ';
				$str .= $months[ date( 'n', strtotime( $start_date['StartDate'] ) ) ];
				$str .= ( $now_year !== $start_year ? ' ' . $start_year : '' );
				if ( $show_days ) {
					$str .= ' ' . date( 'H:i', strtotime( $end_date['StartDate'] ) ) . '-' . date( 'H:i', strtotime( $end_date['EndDate'] ) );
				}
			} else {
				if ( $show_days && ( date( 'H:i', strtotime( $start_date['StartDate'] ) ) !== date( 'H:i', strtotime( $end_date['StartDate'] ) ) && date( 'H:i', strtotime( $start_date['EndDate'] ) ) !== date( 'H:i', strtotime( $end_date['EndDate'] ) ) )
				) {
					$str .= ' ' . date( 'H:i', strtotime( $start_date['StartDate'] ) ) . '-' . date( 'H:i', strtotime( $start_date['EndDate'] ) );
				}
				$str .= ' ';
				$str .= $months[ date( 'n', strtotime( $start_date['StartDate'] ) ) ];
				$str .= ' - ';
				if ( $show_days ) {
					$str .= $week_days[ date( 'N', strtotime( $end_date['EndDate'] ) ) ] . ' ';
				}
				$str .= date( 'd', strtotime( $end_date['EndDate'] ) );
				$str .= ' ';
				$str .= $months[ date( 'n', strtotime( $end_date['EndDate'] ) ) ];
				$str .= ( $now_year !== $start_year ? ' ' . $start_year : '' );
				if ( $show_days ) {
					$str .= ' ' . date( 'H:i', strtotime( $end_date['StartDate'] ) ) . '-' . date( 'H:i', strtotime( $end_date['EndDate'] ) );
				}
			}
		} else {
			$str .= ' ';
			$str .= $months[ date( 'n', strtotime( $start_date['StartDate'] ) ) ];
			$str .= ( $now_year !== $start_year ? ' ' . $start_year : '' );
			$str .= ' - ';
			if ( $show_days ) {
				$str .= $week_days[ date( 'N', strtotime( $end_date['EndDate'] ) ) ] . ' ';
			}
			$str .= date( 'd', strtotime( $end_date['EndDate'] ) );
			$str .= ' ';
			$str .= $months[ date( 'n', strtotime( $end_date['EndDate'] ) ) ];
			$str .= ( $now_year !== $end_year ? ' ' . $end_year : '' );
			if ( $show_days ) {
				$str .= ' ' . date( 'H:i', strtotime( $end_date['StartDate'] ) ) . '-' . date( 'H:i', strtotime( $end_date['EndDate'] ) );
			}
		}
	} else {
		$str .= ' ';
		$str .= $months[ date( 'n', strtotime( $start_date['EndDate'] ) ) ];
		$str .= ( $now_year !== $start_year ? ' ' . $start_year : '' );
		if ( $show_days ) {
			$str .= ' ' . date( 'H:i', strtotime( $start_date['StartDate'] ) ) . '-' . date( 'H:i', strtotime( $start_date['EndDate'] ) );
		}
	}

	$str .= '</span>';
	EDU()->stop_timer( $t );

	return $str;
}

function GetOldStartEndDisplayDate( $startDate, $endDate, $short = false, $showWeekDays = false ) {
	if ( ! isset( $startDate ) && ! isset( $endDate ) ) {
		return "";
	}
	$t        = EDU()->start_timer( __METHOD__ );
	$weekDays = $short ? EDU()->shortWeekDays : EDU()->weekDays;
	$months   = $short ? EDU()->shortMonths : EDU()->months;

	$startYear  = date( 'Y', strtotime( $startDate ) );
	$startMonth = date( 'n', strtotime( $startDate ) );
	$endYear    = date( 'Y', strtotime( $endDate ) );
	$endMonth   = date( 'n', strtotime( $endDate ) );
	$nowYear    = date( 'Y' );
	$str        = '<span class="eduadmin-dateText">';
	if ( $showWeekDays ) {
		$str .= $weekDays[ date( 'N', strtotime( $startDate ) ) ] . ' ';
	}
	$str .= date( 'd', strtotime( $startDate ) );
	if ( date( 'Y-m-d', strtotime( $startDate ) ) != date( 'Y-m-d', strtotime( $endDate ) ) ) {
		if ( $startYear === $endYear ) {
			if ( $startMonth === $endMonth ) {
				$str .= '-';
				if ( $showWeekDays ) {
					$str .= $weekDays[ date( 'N', strtotime( $endDate ) ) ] . ' ';
				}
				$str .= date( 'd', strtotime( $endDate ) );
				$str .= ' ';
				$str .= $months[ date( 'n', strtotime( $startDate ) ) ];
				$str .= ( $nowYear != $startYear ? ' ' . $startYear : '' );
			} else {
				$str .= ' ';
				$str .= $months[ date( 'n', strtotime( $startDate ) ) ];
				$str .= ' - ';
				if ( $showWeekDays ) {
					$str .= $weekDays[ date( 'N', strtotime( $endDate ) ) ] . ' ';
				}
				$str .= date( 'd', strtotime( $endDate ) );
				$str .= ' ';
				$str .= $months[ date( 'n', strtotime( $endDate ) ) ];
				$str .= ( $nowYear != $startYear ? ' ' . $startYear : '' );
			}
		} else {
			$str .= ' ';
			$str .= $months[ date( 'n', strtotime( $startDate ) ) ];
			$str .= ( $nowYear != $startYear ? ' ' . $startYear : '' );
			$str .= ' - ';
			if ( $showWeekDays ) {
				$str .= $weekDays[ date( 'N', strtotime( $endDate ) ) ] . ' ';
			}
			$str .= date( 'd', strtotime( $endDate ) );
			$str .= ' ';
			$str .= $months[ date( 'n', strtotime( $endDate ) ) ];
			$str .= ( $nowYear != $endYear ? ' ' . $endYear : '' );
		}
	} else {
		$str .= ' ';
		$str .= $months[ date( 'n', strtotime( $startDate ) ) ];
		$str .= ( $nowYear != $startYear ? ' ' . $startYear : '' );
	}
	$str .= '</span>';
	EDU()->stop_timer( $t );

	return $str;
}

function DateComparer( $a, $b ) {
	$a_date = date( 'Y-m-d H:i:s', strtotime( $a['StartDate'] ) );
	$b_date = date( 'Y-m-d H:i:s', strtotime( $b['StartDate'] ) );
	if ( $a_date === $b_date ) {
		return 0;
	}

	return ( $a_date < $b_date ? -1 : 1 );
}

function KeySort( $key ) {
	return function( $a, $b ) use ( $key ) {
		return strcmp( $a->{$key}, $b->{$key} );
	};
}

if ( ! function_exists( 'my_str_split' ) ) {
	// Credits go to https://code.google.com/p/php-slugs/
	function my_str_split( $string ) {
		$s_array = array();
		$slen    = strlen( $string );
		for ( $i = 0; $i < $slen; $i++ ) {
			$s_array[ $i ] = $string{$i};
		}

		return $s_array;
	}
}

if ( ! function_exists( 'noDiacritics' ) ) {
	function noDiacritics( $string ) {
		//cyrylic transcription
		$cyrylic_from = array(
			'А',
			'Б',
			'В',
			'Г',
			'Д',
			'Е',
			'Ё',
			'Ж',
			'З',
			'И',
			'Й',
			'К',
			'Л',
			'М',
			'Н',
			'О',
			'П',
			'Р',
			'С',
			'Т',
			'У',
			'Ф',
			'Х',
			'Ц',
			'Ч',
			'Ш',
			'Щ',
			'Ъ',
			'Ы',
			'Ь',
			'Э',
			'Ю',
			'Я',
			'а',
			'б',
			'в',
			'г',
			'д',
			'е',
			'ё',
			'ж',
			'з',
			'и',
			'й',
			'к',
			'л',
			'м',
			'н',
			'о',
			'п',
			'р',
			'с',
			'т',
			'у',
			'ф',
			'х',
			'ц',
			'ч',
			'ш',
			'щ',
			'ъ',
			'ы',
			'ь',
			'э',
			'ю',
			'я',
		);
		$cyrylic_to   = array(
			'A',
			'B',
			'W',
			'G',
			'D',
			'Ie',
			'Io',
			'Z',
			'Z',
			'I',
			'J',
			'K',
			'L',
			'M',
			'N',
			'O',
			'P',
			'R',
			'S',
			'T',
			'U',
			'F',
			'Ch',
			'C',
			'Tch',
			'Sh',
			'Shtch',
			'',
			'Y',
			'',
			'E',
			'Iu',
			'Ia',
			'a',
			'b',
			'w',
			'g',
			'd',
			'ie',
			'io',
			'z',
			'z',
			'i',
			'j',
			'k',
			'l',
			'm',
			'n',
			'o',
			'p',
			'r',
			's',
			't',
			'u',
			'f',
			'ch',
			'c',
			'tch',
			'sh',
			'shtch',
			'',
			'y',
			'',
			'e',
			'iu',
			'ia',
		);

		$from = array(
			'Á',
			'À',
			'Â',
			'Ä',
			'Ă',
			'Ā',
			'Ã',
			'Å',
			'Ą',
			'Æ',
			'Ć',
			'Ċ',
			'Ĉ',
			'Č',
			'Ç',
			'Ď',
			'Đ',
			'Ð',
			'É',
			'È',
			'Ė',
			'Ê',
			'Ë',
			'Ě',
			'Ē',
			'Ę',
			'Ə',
			'Ġ',
			'Ĝ',
			'Ğ',
			'Ģ',
			'á',
			'à',
			'â',
			'ä',
			'ă',
			'ā',
			'ã',
			'å',
			'ą',
			'æ',
			'ć',
			'ċ',
			'ĉ',
			'č',
			'ç',
			'ď',
			'đ',
			'ð',
			'é',
			'è',
			'ė',
			'ê',
			'ë',
			'ě',
			'ē',
			'ę',
			'ə',
			'ġ',
			'ĝ',
			'ğ',
			'ģ',
			'Ĥ',
			'Ħ',
			'I',
			'Í',
			'Ì',
			'İ',
			'Î',
			'Ï',
			'Ī',
			'Į',
			'Ĳ',
			'Ĵ',
			'Ķ',
			'Ļ',
			'Ł',
			'Ń',
			'Ň',
			'Ñ',
			'Ņ',
			'Ó',
			'Ò',
			'Ô',
			'Ö',
			'Õ',
			'Ő',
			'Ø',
			'Ơ',
			'Œ',
			'ĥ',
			'ħ',
			'ı',
			'í',
			'ì',
			'i',
			'î',
			'ï',
			'ī',
			'į',
			'ĳ',
			'ĵ',
			'ķ',
			'ļ',
			'ł',
			'ń',
			'ň',
			'ñ',
			'ņ',
			'ó',
			'ò',
			'ô',
			'ö',
			'õ',
			'ő',
			'ø',
			'ơ',
			'œ',
			'Ŕ',
			'Ř',
			'Ś',
			'Ŝ',
			'Š',
			'Ş',
			'Ť',
			'Ţ',
			'Þ',
			'Ú',
			'Ù',
			'Û',
			'Ü',
			'Ŭ',
			'Ū',
			'Ů',
			'Ų',
			'Ű',
			'Ư',
			'Ŵ',
			'Ý',
			'Ŷ',
			'Ÿ',
			'Ź',
			'Ż',
			'Ž',
			'ŕ',
			'ř',
			'ś',
			'ŝ',
			'š',
			'ş',
			'ß',
			'ť',
			'ţ',
			'þ',
			'ú',
			'ù',
			'û',
			'ü',
			'ŭ',
			'ū',
			'ů',
			'ų',
			'ű',
			'ư',
			'ŵ',
			'ý',
			'ŷ',
			'ÿ',
			'ź',
			'ż',
			'ž',
		);
		$to   = array(
			'A',
			'A',
			'A',
			'A',
			'A',
			'A',
			'A',
			'A',
			'A',
			'AE',
			'C',
			'C',
			'C',
			'C',
			'C',
			'D',
			'D',
			'D',
			'E',
			'E',
			'E',
			'E',
			'E',
			'E',
			'E',
			'E',
			'G',
			'G',
			'G',
			'G',
			'G',
			'a',
			'a',
			'a',
			'a',
			'a',
			'a',
			'a',
			'a',
			'a',
			'ae',
			'c',
			'c',
			'c',
			'c',
			'c',
			'd',
			'd',
			'd',
			'e',
			'e',
			'e',
			'e',
			'e',
			'e',
			'e',
			'e',
			'g',
			'g',
			'g',
			'g',
			'g',
			'H',
			'H',
			'I',
			'I',
			'I',
			'I',
			'I',
			'I',
			'I',
			'I',
			'IJ',
			'J',
			'K',
			'L',
			'L',
			'N',
			'N',
			'N',
			'N',
			'O',
			'O',
			'O',
			'O',
			'O',
			'O',
			'O',
			'O',
			'CE',
			'h',
			'h',
			'i',
			'i',
			'i',
			'i',
			'i',
			'i',
			'i',
			'i',
			'ij',
			'j',
			'k',
			'l',
			'l',
			'n',
			'n',
			'n',
			'n',
			'o',
			'o',
			'o',
			'o',
			'o',
			'o',
			'o',
			'o',
			'o',
			'R',
			'R',
			'S',
			'S',
			'S',
			'S',
			'T',
			'T',
			'T',
			'U',
			'U',
			'U',
			'U',
			'U',
			'U',
			'U',
			'U',
			'U',
			'U',
			'W',
			'Y',
			'Y',
			'Y',
			'Z',
			'Z',
			'Z',
			'r',
			'r',
			's',
			's',
			's',
			's',
			'B',
			't',
			't',
			'b',
			'u',
			'u',
			'u',
			'u',
			'u',
			'u',
			'u',
			'u',
			'u',
			'u',
			'w',
			'y',
			'y',
			'y',
			'z',
			'z',
			'z',
		);

		$from = array_merge( $from, $cyrylic_from );
		$to   = array_merge( $to, $cyrylic_to );

		$newstring = str_replace( $from, $to, $string );

		return $newstring;
	}
}

if ( ! function_exists( 'makeSlugs' ) ) {
	function makeSlugs( $string, $maxlen = 0 ) {
		$t              = EDU()->start_timer( __METHOD__ );
		$new_string_tab = array();
		$string         = strtolower( noDiacritics( $string ) );
		if ( function_exists( 'str_split' ) ) {
			$string_tab = str_split( $string );
		} else {
			$string_tab = my_str_split( $string );
		}

		$numbers = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-' );

		foreach ( $string_tab as $letter ) {
			if ( in_array( $letter, range( 'a', 'z' ), true ) || in_array( $letter, $numbers, true ) ) {
				$new_string_tab[] = $letter;
			} elseif ( ' ' === $letter ) {
				$new_string_tab[] = '-';
			}
		}

		if ( ! empty( $new_string_tab ) ) {
			$new_string = implode( $new_string_tab );
			if ( $maxlen > 0 ) {
				$new_string = substr( $new_string, 0, $maxlen );
			}

			$new_string = removeDuplicates( '--', '-', $new_string );
		} else {
			$new_string = '';
		}
		EDU()->stop_timer( $t );

		return $new_string;
	}
}

if ( ! function_exists( 'checkSlug' ) ) {
	function checkSlug( $s_slug ) {
		if ( preg_match( '/^[a-zA-Z0-9]+[a-zA-Z0-9\_\-]*$/', $s_slug ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'removeDuplicates' ) ) {
	function removeDuplicates( $s_search, $s_replace, $s_subject ) {
		$t = EDU()->start_timer( __METHOD__ );
		$i = 0;
		do {
			$s_subject = str_replace( $s_search, $s_replace, $s_subject );
			$pos       = strpos( $s_subject, $s_search );

			$i++;
			if ( $i > 100 ) {
				die( 'removeDuplicates() loop error' );
			}
		} while ( false !== $pos );
		EDU()->stop_timer( $t );

		return $s_subject;
	}
}