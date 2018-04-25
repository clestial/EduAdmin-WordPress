<?php
$grouped_programmes = array();

foreach ( $programme['ProgrammeStarts'] as $programme_start ) {
	$key = date( 'Y-m', strtotime( $programme_start['StartDate'] ) );

	$grouped_programmes[ $key ][] = $programme_start;
}

foreach ( $grouped_programmes as $group => $grouped_programme ) {
	echo '<h2>' . esc_html( $group ) . '</h2>';
	echo '<div class="scrollable">';
	echo '<table class="programme-list">';
	echo '<tr>';
	echo '<th>' . esc_html__( 'Start', 'eduadmin-booking' ) . '</th>';
	echo '<th>' . esc_html__( 'Schedule', 'eduadmin-booking' ) . '</th>';
	echo '<th>' . esc_html__( 'Spots left', 'eduadmin-booking' ) . '</th>';
	echo '<th></th>';
	echo '</tr>';
	foreach ( $grouped_programme as $programme_start ) {
		echo '<tr>';
		echo '<td>' . wp_kses_post( get_display_date( $programme_start['StartDate'] ) ) . '</td>';
		echo '<td>';

		if ( 0 === count( $programme_start['Events'] ) ) {
			echo '<i>' . esc_html__( 'No planned events', 'eduadmin-booking' ) . '</i>';
		} else {
			echo '<span class="edu-manyDays" onclick="edu_openDatePopup(this);">' . esc_html__( 'Show', 'eduadmin-booking' ) . '</span>';
			echo '<div class="edu-DayPopup">';
			echo esc_html( $programme['ProgrammeName'] );
			echo ' - ';
			echo wp_kses_post( get_display_date( $programme_start['StartDate'] ) );
			echo '<a style="float: right;" href="javascript://" onclick="edu_closeDatePopup(event, this);">' . esc_html__( 'Close', 'eduadmin-booking' ) . '</a>';
			echo '<div class="scrollable-full-height">';
			$events_per_day = array();
			foreach ( $programme_start['Events'] as $event ) {
				$events_per_day[ date( 'Y-m-d', strtotime( $event['StartDate'] ) ) ][] = $event;
			}

			foreach ( $events_per_day as $day => $_events ) {
				echo '<b>' . esc_html( $day ) . '</b><br />';
				foreach ( $_events as $ev ) {
					echo esc_html(
						     date( 'H:i', strtotime( $ev['StartDate'] ) ) . '-' .
						     date( 'H:i', strtotime( $ev['EndDate'] ) ) . ' ' .
						     $ev['EventName']
					     ) . '<br />';
				}
			}
			echo '</div>';
			echo '</div>';
		}

		echo '</td>';
		echo '<td>' . esc_html( $programme_start['ParticipantNumberLeft'] > 0 ? __( 'Yes', 'eduadmin-booking' ) : __( 'No', 'eduadmin-booking' ) ) . '</td>';
		echo '<td><a href="' . esc_url( get_home_url() . '/programmes/' . make_slugs( $programme['ProgrammeName'] ) . '_' . $programme['ProgrammeId'] . '/book/?id=' . $programme_start['ProgrammeStartId'] ) . '" class="submit-programme">' . esc_html__( 'Book', 'eduadmin-booking' ) . '</a></td>';
		echo '</tr>';
	}
	echo '</table>';
	echo '</div>';
}
