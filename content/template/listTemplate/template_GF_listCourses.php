<?php
ob_start();
require 'list-courses.php';

if ( ! empty( $courses ) ) {
	?>
	<table class="gf-table">

	<?php
	$cats = array();
	$cats2 = array();
	$object_names = array();

	foreach ( $courses as $object => $item ) {
		$cats2[ $object ] = strtolower( $item['CategoryName'] );
		$name = strtolower( ! empty( $item['CourseName'] ) ? $item['CourseName'] : $item['InternalCourseName'] );
		$object_names[ $object ] = $name;
	}

	array_multisort( $cats2, SORT_ASC, SORT_STRING, $object_names, SORT_ASC, SORT_NATURAL, $courses );
	foreach ( $courses as $object ) {

		$name = strtolower( ! empty( $object['CourseName'] ) ? $object['CourseName'] : $object['InternalCourseName'] );
		$events = $object['Events'];

		$prices = array();
		$sorted_events = array();
		$event_cities = array();

		foreach ( $events as $ev ) {
			$sorted_events[ $ev['StartDate'] ] = $ev;
			$event_cities[ $ev['City'] ] = $ev;
		}

		ksort( $sorted_events );
		ksort( $event_cities );

		$show_events_with_events_only = $attributes[ 'onlyevents' ];
		$show_events_without_events_only = $attributes[ 'onlyempty' ];

		$show_event_venue = get_option( 'eduadmin-showEventVenueName', false );

		if ( $show_events_with_events_only && empty( $sorted_events ) ) {
					continue;
		}

		if ( $show_events_without_events_only && ! empty( $sorted_events ) || 43690 == $object['CategoryId'] ) {
			// custom exklude for this ID
			continue;
		}

		if ( ! in_array( $object['CategoryName'], $cats ) ) {
			?>
				<tr class="gf-header">
					<th>
						<?php echo esc_html( $object['CategoryName'] ); ?>
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
	<tr class="GFObjectItem" data-objectid="<?php echo esc_attr($object['CourseTemplateId']); ?>">
		<td class="GFObjectName">
			<a href="<?php echo $base_url; ?>/<?php echo make_slugs( $name ); ?>__<?php echo $object['CourseTemplateId']; ?>/<?php echo edu_get_query_string(); ?>"><?php
				echo esc_html( get_utf8( $name ) );
			?></a>
		</td>

		<?php
			$count = 4;
			if ( $show_course_locations && ! empty( $event_cities ) ) {
			    if( $object['Days'] > 0 ) {
				    $days = sprintf( _n( '%1$d day', '%1$d days', $object['Days'], 'eduadmin-booking' ), $object['Days'] ) . ', ';
				} else {
			        $days = '';
				}


				echo isset( $event_cities[ 'Stockholm' ] ) ?
					'<td>' . $days . get_old_start_end_display_date( $event_cities[ 'Stockholm' ]['StartDate'], $event_cities[ 'Stockholm' ]['EndDate'], true ) . '</td>' : '<td></td>';

				echo isset( $event_cities[ 'Göteborg' ] ) ?
					'<td>' . $days . get_old_start_end_display_date( $event_cities[ 'Göteborg' ]['StartDate'], $event_cities[ 'Göteborg' ]['EndDate'], true ) . '</td>' : '<td></td>';

				echo isset( $event_cities[ 'Växjö' ] ) ?
					'<td>' . $days . get_old_start_end_display_date( $event_cities[ 'Växjö' ]['StartDate'], $event_cities[ 'Växjö' ]['EndDate'], true ) . '</td>' : '<td></td>';

				if ( isset( $event_cities[ 'Malmö' ] ) ) {
					echo '<td>' . $days . get_old_start_end_display_date( $event_cities[ 'Malmö' ]['StartDate'], $event_cities[ 'Malmö' ]['EndDate'], true ) . '</td>';
				} elseif ( isset( $event_cities[ 'Kristianstad' ] ) ) {
					echo '<td>' . $days . get_old_start_end_display_date( $event_cities[ 'Kristianstad' ]['StartDate'], $event_cities[ 'Kristianstad' ]['EndDate'], true ) . '</td>';
				} elseif ( isset( $event_cities[ 'Sundsvall' ] ) ) {
					echo '<td>' . $days . get_old_start_end_display_date( $event_cities[ 'Sundsvall' ]['StartDate'], $event_cities[ 'Sundsvall' ]['EndDate'], true ) . '</td>';
				} else {
					echo '<td></td>';
				}
			} else {
				echo '<td></td><td></td><td></td><td></td>';
			}
		?>
		<td class="GFObjectBook">
			<a class="readMoreButton" href="<?php echo $base_url; ?>/<?php echo make_slugs( $name ); ?>__<?php echo $object->ObjectID; ?>/<?php echo edu_get_query_string(); ?>"><?php _e( "Read more", 'eduadmin-booking' ); ?></a>
		</td>
	</tr>
<?php
	}
} else {
?>
	<div class="noResults"><?php esc_html_e( 'Your search returned zero results', 'eduadmin-booking' ); ?></div>
<?php
}

$out = ob_get_clean() . '</table>';
return $out;
