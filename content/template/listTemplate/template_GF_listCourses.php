<?php
ob_start();

$surl = get_home_url();
$cat = get_option( 'eduadmin-rewriteBaseUrl' );
$baseUrl = $surl . '/' . $cat;

$filtering = new XFiltering();
$f = new XFilter( 'ShowOnWeb', '=', 'true' );
$filtering->AddItem( $f );

$showEventsWithEventsOnly = $attributes[ 'onlyevents' ];
$showEventsWithoutEventsOnly = $attributes[ 'onlyempty' ];

if ( $categoryID > 0 ) {
	$f = new XFilter( 'CategoryID', '=', $categoryID );
	$filtering->AddItem( $f );
}

if ( isset( $_REQUEST[ 'eduadmin-category' ] ) ) {
	$f = new XFilter( 'CategoryID', '=', $_REQUEST[ 'eduadmin-category' ] );
	$filtering->AddItem( $f );
}

if ( isset( $_REQUEST[ 'eduadmin-city' ] ) ) {
	$f = new XFilter( 'LocationID', '=', $_REQUEST[ 'eduadmin-city' ] );
	$filtering->AddItem( $f );
}

if ( isset( $_REQUEST[ 'eduadmin-subject' ] ) ) {
	$f = new XFilter( 'SubjectID', '=', $_REQUEST[ 'eduadmin-subject' ] );
	$filtering->AddItem( $f );
}

if ( ! empty( $filterCourses ) ) {
	$f = new XFilter( 'ObjectID', 'IN', join( ',', $filterCourses ) );
	$filtering->AddItem( $f );
}

$sortOrder = get_option( 'eduadmin-listSortOrder', 'SortIndex' );

$sort = new XSorting();
$s = new XSort( $sortOrder, 'ASC' );
$sort->AddItem( $s );
$edo = $eduapi->GetEducationObject( $edutoken, $sort->ToString(), $filtering->ToString() );

if ( isset( $_REQUEST[ 'searchCourses' ] ) && ! empty( $_REQUEST[ 'searchCourses' ] ) ) {
	$edo = array_filter( $edo, function( $object ) {
		$name = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
		$descrField = get_option( 'eduadmin-layout-descriptionfield', 'CourseDescriptionShort' );
		$descr = strip_tags( $object->{$descrField});

		$nameMatch = stripos( $name, $_REQUEST[ 'searchCourses' ] ) !== false;
		$descrMatch = stripos( $descr, $_REQUEST[ 'searchCourses' ] ) !== false;
		return ( $nameMatch || $descrMatch );
	});
}

if ( isset( $_REQUEST[ 'eduadmin-subject' ] ) && ! empty( $_REQUEST[ 'eduadmin-subject' ] ) ) {
	$subjects = get_transient( 'eduadmin-subjects' );
	if ( ! $subjects ) {
		$sorting = new XSorting();
		$s = new XSort( 'SubjectName', 'ASC' );
		$sorting->AddItem( $s );
		$subjects = $eduapi->GetEducationSubject( $edutoken, $sorting->ToString(), '' );
		set_transient( 'eduadmin-subjects', $subjects, DAY_IN_SECONDS );
	}

	$edo = array_filter( $edo, function( $object ) {
		$subjects = get_transient( 'eduadmin-subjects' );
		foreach ( $subjects as $subj ) {
			if ( $object->ObjectID == $subj->ObjectID && $subj->SubjectID == $_REQUEST[ 'eduadmin-subject' ] ) {
				return true;
			}
		}
		return false;
	});
}

if ( isset( $_REQUEST[ 'eduadmin-level' ] ) && ! empty( $_REQUEST[ 'eduadmin-level' ] ) ) {
	$edo = array_filter( $edo, function( $object ) {
		$cl = get_transient( 'eduadmin-courseLevels' );
		foreach ( $cl as $subj ) {
			if ( $object->ObjectID == $subj->ObjectID && $subj->EducationLevelID == $_REQUEST[ 'eduadmin-level' ] ) {
				return true;
			}
		}
		return false;
	});
}

$objIds = array ();

foreach ( $edo as $obj ) {
	$objIds[ ] = $obj->ObjectID;
}

$filtering = new XFiltering();
$f = new XFilter( 'ShowOnWeb', '=', 'true' );
$filtering->AddItem( $f );

if ( $categoryID > 0 ) {
	$f = new XFilter( 'CategoryID', '=', $categoryID );
	$filtering->AddItem( $f );
}

if ( isset( $_REQUEST[ 'eduadmin-city' ] ) ) {
	$f = new XFilter( 'LocationID', '=', $_REQUEST[ 'eduadmin-city' ] );
	$filtering->AddItem( $f );
}

if ( isset( $_REQUEST[ 'eduadmin-category' ] ) ) {
	$f = new XFilter( 'CategoryID', '=', $_REQUEST[ 'eduadmin-category' ] );
	$filtering->AddItem( $f );
}

if ( isset( $_REQUEST[ 'eduadmin-subject' ] ) ) {
	$f = new XFilter( 'SubjectID', '=', $_REQUEST[ 'eduadmin-subject' ] );
	$filtering->AddItem( $f );
}

$fetchMonths = get_option( 'eduadmin-monthsToFetch', 6 );
if ( ! is_numeric( $fetchMonths ) ) {
	$fetchMonths = 6;
}

$f = new XFilter( 'CustomerID', '=', '0' );
$filtering->AddItem( $f );

$f = new XFilter( 'PeriodStart', '<=', date( "Y-m-d 23:59:59", strtotime( "now +" . $fetchMonths . " months" ) ) );
$filtering->AddItem( $f );
$f = new XFilter( 'PeriodEnd', '>=', date( "Y-m-d H:i:s", strtotime( "now" ) ) );
$filtering->AddItem( $f );
$f = new XFilter( 'StatusID', '=', '1' );
$filtering->AddItem( $f );

$f = new XFilter( 'LastApplicationDate', '>', date( "Y-m-d H:i:s" ) );
$filtering->AddItem( $f );

if ( ! empty( $objIds ) ) {
	$f = new XFilter( 'ObjectID', 'IN', join( ',', $objIds ) );
	$filtering->AddItem( $f );
}

$sorting = new XSorting();
if ( $customOrderBy != null ) {
	$orderby = explode( ' ', $customOrderBy );
	$sortorder = explode( ' ', $customOrderByOrder );
	foreach ( $orderby as $od => $v ) {
		if ( isset( $sortorder[ $od ] ) ) {
					$or = $sortorder[ $od ];
		} else {
					$or = "ASC";
		}

		$s = new XSort( $v, $or );
		$sorting->AddItem( $s );
	}
} else {
	$s = new XSort( 'PeriodStart', 'ASC' );
	$sorting->AddItem( $s );
}

$ede = $eduapi->GetEvent( $edutoken, $sorting->ToString(), $filtering->ToString() );

if ( isset( $_REQUEST[ 'eduadmin-subject' ] ) && ! empty( $_REQUEST[ 'eduadmin-subject' ] ) ) {
	$subjects = get_transient( 'eduadmin-subjects' );
	if ( ! $subjects ) {
		$sorting = new XSorting();
		$s = new XSort( 'SubjectName', 'ASC' );
		$sorting->AddItem( $s );
		$subjects = $eduapi->GetEducationSubject( $edutoken, $sorting->ToString(), '' );
		set_transient( 'eduadmin-subjects', $subjects, DAY_IN_SECONDS );
	}

	$ede = array_filter( $ede, function( $object ) {
		$subjects = get_transient( 'eduadmin-subjects' );
		foreach ( $subjects as $subj ) {
			if ( $object->ObjectID == $subj->ObjectID && $subj->SubjectID == $_REQUEST[ 'eduadmin-subject' ] ) {
				return true;
			}
		}
		return false;
	});
}

if ( isset( $_REQUEST[ 'eduadmin-level' ] ) && ! empty( $_REQUEST[ 'eduadmin-level' ] ) ) {
	$ede = array_filter( $ede, function( $object ) {
		$cl = get_transient( 'eduadmin-courseLevels' );
		foreach ( $cl as $subj ) {
			if ( $object->ObjectID == $subj->ObjectID && $subj->EducationLevelID == $_REQUEST[ 'eduadmin-level' ] ) {
				return true;
			}
		}
		return false;
	});
}
$occIds = array ();

foreach ( $ede as $e ) {
	$occIds[ ] = $e->OccationID;
}

$ft = new XFiltering();
$f = new XFilter( 'PublicPriceName', '=', 'true' );
$ft->AddItem( $f );
$f = new XFilter( 'OccationID', 'IN', join( ",", $occIds ) );
$ft->AddItem( $f );
$pricenames = $eduapi->GetPriceName( $edutoken, '', $ft->ToString() );
set_transient( 'eduadmin-publicpricenames', $pricenames, HOUR_IN_SECONDS );

if ( ! empty( $pricenames ) ) {
	$ede = array_filter( $ede, function( $object ) {
		$pn = get_transient( 'eduadmin-publicpricenames' );
		foreach ( $pn as $subj ) {
			if ( $object->OccationID == $subj->OccationID ) {
				return true;
			}
		}
		return false;
	});
}

foreach ( $ede as $object ) {
	foreach ( $edo as $course ) {
		$id = $course->ObjectID;
		if ( $id == $object->ObjectID ) {
			$object->Days = $course->Days;
			$object->StartTime = $course->StartTime;
			$object->EndTime = $course->EndTime;
			$object->CategoryID = $course->CategoryID;
			$object->PublicName = $course->PublicName;
			break;
		}
	}
}

if ( isset( $_REQUEST[ 'searchCourses' ] ) && ! empty( $_REQUEST[ 'searchCourses' ] ) ) {
	$ede = array_filter( $ede, function( $object ) {
		$name = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );

		$nameMatch = stripos( $name, $_REQUEST[ 'searchCourses' ] ) !== false;
		return $nameMatch;
	});
}

$descrField = get_option( 'eduadmin-layout-descriptionfield', 'CourseDescriptionShort' );
if ( stripos( $descrField, "attr_" ) !== false ) {
	$ft = new XFiltering();
	$f = new XFilter( "AttributeID", "=", substr( $descrField, 5 ) );
	$ft->AddItem( $f );
	$objectAttributes = $eduapi->GetObjectAttribute( $edutoken, '', $ft->ToString() );
}
if ( ! empty( $edo ) ) {
	$showNextEventDate = get_option( 'eduadmin-showNextEventDate', false );
	$showCourseLocations = get_option( 'eduadmin-showCourseLocations', false );
	$incVat = $eduapi->GetAccountSetting( $edutoken, 'PriceIncVat' ) == "yes";
	$showEventPrice = get_option( 'eduadmin-showEventPrice', false );

	$showCourseDays = get_option( 'eduadmin-showCourseDays', true );
	$showCourseTimes = get_option( 'eduadmin-showCourseTimes', true );

	$showDescr = get_option( 'eduadmin-showCourseDescription', true );
	$currency = get_option( 'eduadmin-currency', 'SEK' );

	?>

	<table class="gf-table">

	<?php
	$cats = array ();

	foreach ( $edo as $object => $item ) {
		$categories[ $object ] = strtolower( $item->CategoryName );
		$name = strtolower( ! empty( $item->PublicName ) ? $item->PublicName : $item->ObjectName );
		$objectNames[ $object ] = $name;
	}

	array_multisort( $categories, SORT_ASC, SORT_STRING, $objectNames, SORT_ASC, SORT_NATURAL, $edo );
	foreach ( $edo as $object ) {

		$name = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
		$events = array_filter( $ede, function( $ev ) use ( &$object ) {

			return $ev->ObjectID == $object->ObjectID;
		});

		$prices = array ();
		$sortedEvents = array ();
		$eventCities = array ();

		foreach ( $events as $ev ) {
			$sortedEvents[ $ev->PeriodStart ] = $ev;
			$eventCities[ $ev->City ] = $ev;
		}

		foreach ( $pricenames as $pr ) {
			if ( isset( $object->ObjectID ) && isset( $pr->ObjectID ) ) {
				if ( $object->ObjectID == $pr->ObjectID ) {
					$prices[ $pr->Price ] = $pr;
				}
			}
		}

		ksort( $sortedEvents );
		ksort( $eventCities );
		$showEventsWithEventsOnly = $attributes[ 'onlyevents' ];
		$showEventsWithoutEventsOnly = $attributes[ 'onlyempty' ];

		$showEventVenue = get_option( 'eduadmin-showEventVenueName', false );

		if ( $showEventsWithEventsOnly && empty( $sortedEvents ) ) {
					continue;
		}

		if ( $showEventsWithoutEventsOnly && ! empty( $sortedEvents ) || $object->CategoryID == 43690 ) {
			// custom exklude for this ID
			continue;
		}

		if ( ! in_array( $object->CategoryName, $cats ) ) {
			?>
				<tr class="gf-header">
					<th>
						<?= $object->CategoryName  ?>
					</th>
					<th>
						Stockholm
					</th>
					<th>
						Göteborg
					</th>
					<th>
						Växjö
					</th>
					<th>
						Annan ort
					</th>
					<th>
					</th>
				</tr>

			<?php
			$cats[ ] = $object->CategoryName;
		}
?>
	<tr class="GFObjectItem" data-objectid="<?php echo $object->ObjectID; ?>">
		<td class="GFObjectName">
			<a href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/<?php echo edu_getQueryString(); ?>"><?php
				echo htmlentities( getUTF8( $name ) );
			?></a>
		</td>

		<?php
			$count = 4;
			if ( $showCourseLocations && ! empty( $eventCities ) ) {
				$days = sprintf( edu_n( '%1$d day', '%1$d days', $object->Days ), $object->Days ) . ', ';

				echo isset( $eventCities[ 'Stockholm' ] ) ?
					'<td>' . $days . GetOldStartEndDisplayDate( $eventCities[ 'Stockholm' ]->PeriodStart, $eventCities[ 'Stockholm' ]->PeriodEnd, true ) . '</td>' : '<td></td>';

				echo isset( $eventCities[ 'Göteborg' ] ) ?
					'<td>' . $days . GetOldStartEndDisplayDate( $eventCities[ 'Göteborg' ]->PeriodStart, $eventCities[ 'Göteborg' ]->PeriodEnd, true ) . '</td>' : '<td></td>';

				echo isset( $eventCities[ 'Växjö' ] ) ?
					'<td>' . $days . GetOldStartEndDisplayDate( $eventCities[ 'Växjö' ]->PeriodStart, $eventCities[ 'Växjö' ]->PeriodEnd, true ) . '</td>' : '<td></td>';

				if ( isset( $eventCities[ 'Malmö' ] ) ) {
					echo '<td>' . $days . GetOldStartEndDisplayDate( $eventCities[ 'Malmö' ]->PeriodStart, $eventCities[ 'Malmö' ]->PeriodEnd, true ) . '</td>';
				} elseif ( isset( $eventCities[ 'Kristianstad' ] ) ) {
					echo '<td>' . $days . GetOldStartEndDisplayDate( $eventCities[ 'Kristianstad' ]->PeriodStart, $eventCities[ 'Kristianstad' ]->PeriodEnd, true ) . '</td>';
				} elseif ( isset( $eventCities[ 'Sundsvall' ] ) ) {
					echo '<td>' . $days . GetOldStartEndDisplayDate( $eventCities[ 'Sundsvall' ]->PeriodStart, $eventCities[ 'Sundsvall' ]->PeriodEnd, true ) . '</td>';
				} else {
					echo '<td></td>';
				}
			} else {
				echo '<td></td><td></td><td></td><td></td>';
			}
		?>
		<td class="GFObjectBook">
			<a class="readMoreButton" href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/<?php echo edu_getQueryString(); ?>"><?php edu_e( "Read more" ); ?></a>
		</td>
	</tr>
<?php
	}
} else {
?>
	<div class="noResults"><?php edu_e( "Your search returned zero results" ); ?></div>
<?php
}

$out = ob_get_clean() . '</table>';
return $out;