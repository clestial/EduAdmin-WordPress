<?php
ob_start();
include( "list-courses.php" );

if ( ! empty( $courses ) ) {
	?>
	<table class="gf-table">

	<?php
	$cats = array ();
	$cats2 = array();
	$objectNames = array();

	foreach ( $courses as $object => $item ) {
		$cats2[ $object ] = strtolower( $item['CategoryName'] );
		$name = strtolower( ! empty( $item['CourseName'] ) ? $item['CourseName'] : $item['InternalCourseName'] );
		$objectNames[ $object ] = $name;
	}

	array_multisort( $cats2, SORT_ASC, SORT_STRING, $objectNames, SORT_ASC, SORT_NATURAL, $courses );
	foreach ( $courses as $object ) {

		$name = strtolower( ! empty( $object['CourseName'] ) ? $object['CourseName'] : $object['InternalCourseName'] );
		$events = $object['Events'];

		$prices = array();
		$sortedEvents = array();
		$eventCities = array();

		foreach ( $events as $ev ) {
			$sortedEvents[ $ev['StartDate'] ] = $ev;
			$eventCities[ $ev['City'] ] = $ev;
		}

		ksort( $sortedEvents );
		ksort( $eventCities );

		$showEventsWithEventsOnly = $attributes[ 'onlyevents' ];
		$showEventsWithoutEventsOnly = $attributes[ 'onlyempty' ];

		$showEventVenue = get_option( 'eduadmin-showEventVenueName', false );

		if ( $showEventsWithEventsOnly && empty( $sortedEvents ) ) {
					continue;
		}

		if ( $showEventsWithoutEventsOnly && ! empty( $sortedEvents ) || 43690 == $object['CategoryId'] ) {
			// custom exklude for this ID
			continue;
		}

		if ( ! in_array( $object['CategoryName'], $cats ) ) {
			?>
				<tr class="gf-header">
					<th>
						<?php echo esc_html( $object['CategoryName'] );  ?>
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
			$cats[] = $object['CategoryName'];
		}
?>
	<tr class="GFObjectItem" data-objectid="<?php echo $object['CourseTemplateId']; ?>">
		<td class="GFObjectName">
			<a href="<?php echo $baseUrl; ?>/<?php echo make_slugs( $name ); ?>__<?php echo $object['CourseTemplateId']; ?>/<?php echo edu_get_query_string(); ?>"><?php
				echo htmlentities( get_utf8( $name ) );
			?></a>
		</td>

		<?php
			$count = 4;
			if ( $showCourseLocations && ! empty( $eventCities ) ) {
			    if( $object['Days'] > 0 ) {
				    $days = sprintf( _n( '%1$d day', '%1$d days', $object['Days'], 'eduadmin-booking' ), $object['Days'] ) . ', ';
				} else {
			        $days = '';
				}


				echo isset( $eventCities[ 'Stockholm' ] ) ?
					'<td>' . $days . get_old_start_end_display_date( $eventCities[ 'Stockholm' ]['StartDate'], $eventCities[ 'Stockholm' ]['EndDate'], true ) . '</td>' : '<td></td>';

				echo isset( $eventCities[ 'Göteborg' ] ) ?
					'<td>' . $days . get_old_start_end_display_date( $eventCities[ 'Göteborg' ]['StartDate'], $eventCities[ 'Göteborg' ]['EndDate'], true ) . '</td>' : '<td></td>';

				echo isset( $eventCities[ 'Växjö' ] ) ?
					'<td>' . $days . get_old_start_end_display_date( $eventCities[ 'Växjö' ]['StartDate'], $eventCities[ 'Växjö' ]['EndDate'], true ) . '</td>' : '<td></td>';

				if ( isset( $eventCities[ 'Malmö' ] ) ) {
					echo '<td>' . $days . get_old_start_end_display_date( $eventCities[ 'Malmö' ]['StartDate'], $eventCities[ 'Malmö' ]['EndDate'], true ) . '</td>';
				} elseif ( isset( $eventCities[ 'Kristianstad' ] ) ) {
					echo '<td>' . $days . get_old_start_end_display_date( $eventCities[ 'Kristianstad' ]['StartDate'], $eventCities[ 'Kristianstad' ]['EndDate'], true ) . '</td>';
				} elseif ( isset( $eventCities[ 'Sundsvall' ] ) ) {
					echo '<td>' . $days . get_old_start_end_display_date( $eventCities[ 'Sundsvall' ]['StartDate'], $eventCities[ 'Sundsvall' ]['EndDate'], true ) . '</td>';
				} else {
					echo '<td></td>';
				}
			} else {
				echo '<td></td><td></td><td></td><td></td>';
			}
		?>
		<td class="GFObjectBook">
			<a class="readMoreButton" href="<?php echo $baseUrl; ?>/<?php echo make_slugs( $name ); ?>__<?php echo $object->ObjectID; ?>/<?php echo edu_get_query_string(); ?>"><?php _e( "Read more", 'eduadmin-booking' ); ?></a>
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