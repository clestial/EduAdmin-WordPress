<?php
$show_event_venue = get_option( 'eduadmin-showEventVenueName', false );
$spot_left_option = get_option( 'eduadmin-spotsLeft', 'exactNumbers' );
$always_few_spots = get_option( 'eduadmin-alwaysFewSpots', '3' );
$spot_settings    = get_option( 'eduadmin-spotsSettings', "1-5\n5-10\n10+" );

$object_interest_page      = get_option( 'eduadmin-interestObjectPage' );
$allow_interest_reg_object = get_option( 'eduadmin-allowInterestRegObject', false );

$event_interest_page      = get_option( 'eduadmin-interestEventPage' );
$allow_interest_reg_event = get_option( 'eduadmin-allowInterestRegEvent', false );
$show_more                = ! empty( $attributes['showmore'] ) ? $attributes['showmore'] : -1;

$has_hidden_dates = false;

?>
<div class="event-table eventDays" data-eduwidget="eventlist" data-objectid="<?php echo esc_attr( $selected_course['CourseTemplateId'] ); ?>" data-spotsleft="<?php echo esc_attr( $spot_left_option ); ?>" data-spotsettings="<?php echo esc_attr( $spot_settings ); ?>" data-fewspots="<?php echo esc_attr( $always_few_spots ); ?>" data-showmore="<?php echo esc_attr( $show_more ); ?>" data-groupbycity="<?php echo esc_attr( $group_by_city ); ?>" data-fetchmonths="<?php echo esc_attr( $fetch_months ); ?>"
	<?php echo( isset( $_GET['eid'] ) ? ' data-eid="' . intval( $_GET['eid'] ) . '"' : '' ); ?>
		data-showvenue="<?php echo esc_attr( $show_event_venue ); ?>" data-eventinquiry="<?php echo esc_attr( $allow_interest_reg_event ); ?>">
	<?php
	$i = 0;
	if ( ! empty( $prices ) ) {
		foreach ( $events as $ev ) {
			if ( ! empty( $_GET['eid'] ) ) { // Input var okay.
				if ( $ev['EventId'] !== $_GET['eid'] ) { // Input var okay.
					continue;
				}
			}
			include 'event-item.php';
			$last_city = $ev['City'];
			$i++;
		}
	}
	if ( empty( $prices ) || empty( $events ) ) {
		?>
		<div class="noDatesAvailable">
			<i><?php esc_html_e( 'No available dates for the selected course', 'eduadmin-booking' ); ?></i>
		</div>
		<?php
	}
	if ( $has_hidden_dates ) {
		echo '<div class="eventShowMore"><a class="neutral-btn" href="javascript://" onclick="eduDetailView.ShowAllEvents(\'eduev' . esc_attr( $group_by_city ? '-' . $last_city : '' ) . '\', this);">' . esc_html__( 'Show all events', 'eduadmin-booking' ) . '</a></div>';
	}
	?>
</div>
