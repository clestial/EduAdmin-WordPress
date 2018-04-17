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
		echo '<td><i>Left intentionally empty</i></td>';
		echo '<td>' . esc_html( $programme_start['ParticipantNumberLeft'] > 0 ? __( 'Yes', 'eduadmin-booking' ) : __( 'No', 'eduadmin-booking' ) ) . '</td>';
		echo '<td><a href="' . esc_url( get_home_url() . '/programmes/' . make_slugs( $programme['ProgrammeName'] ) . '_' . $programme['ProgrammeId'] . '/book/?id=' . $programme_start['ProgrammeStartId'] ) . '" class="submit-programme">' . esc_html__( 'Book', 'eduadmin-booking' ) . '</a></td>';
		echo '</tr>';
	}
	echo '</table>';
	echo '</div>';
	EDU()->write_debug( $grouped_programme );
}
