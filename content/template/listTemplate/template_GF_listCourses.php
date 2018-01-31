<?php
ob_start();
include( "list-courses.php" );

if ( ! empty( $edo ) ) {
	?>

	<table class="gf-table">

	<?php
	$cats = array ();
	$cats2 = array();

	foreach ( $edo as $object => $item ) {
		$cats2[ $object ] = strtolower( $item->CategoryName );
		$name = strtolower( ! empty( $item->PublicName ) ? $item->PublicName : $item->ObjectName );
		$objectNames[ $object ] = $name;
	}

	array_multisort( $cats2, SORT_ASC, SORT_STRING, $objectNames, SORT_ASC, SORT_NATURAL, $edo );
	foreach ( $edo as $object ) {

		$name = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
		$events = array_filter( $ede, function( $ev ) use ( &$object ) {

			return $ev->ObjectID == $object->ObjectID;
		});

		$prices = array();
		$sortedEvents = array();
		$eventCities = array();

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

		if ( $showEventsWithoutEventsOnly && ! empty( $sortedEvents ) || 43690 == $object->CategoryID ) {
			// custom exklude for this ID
			continue;
		}

		if ( ! in_array( $object->CategoryName, $cats ) ) {
			?>
				<tr class="gf-header">
					<th>
						<?php echo esc_html($object->CategoryName);  ?>
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
			$cats[] = $object->CategoryName;
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
				$days = sprintf( _n( '%1$d day', '%1$d days', $object->Days, 'eduadmin-booking' ), $object->Days ) . ', ';

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
			<a class="readMoreButton" href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/<?php echo edu_getQueryString(); ?>"><?php _e( "Read more", 'eduadmin-booking' ); ?></a>
		</td>
	</tr>
<?php
	}
} else {
?>
	<div class="noResults"><?php _e( "Your search returned zero results", 'eduadmin-booking' ); ?></div>
<?php
}

$out = ob_get_clean() . '</table>';
return $out;