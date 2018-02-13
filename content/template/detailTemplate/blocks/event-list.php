<?php
	$showEventVenue = get_option( 'eduadmin-showEventVenueName', false );
	$spotLeftOption = get_option( 'eduadmin-spotsLeft', 'exactNumbers' );
	$alwaysFewSpots = get_option( 'eduadmin-alwaysFewSpots', '3' );
	$spotSettings   = get_option( 'eduadmin-spotsSettings', "1-5\n5-10\n10+" );

	$objectInterestPage     = get_option( 'eduadmin-interestObjectPage' );
	$allowInterestRegObject = get_option( 'eduadmin-allowInterestRegObject', false );

	$eventInterestPage     = get_option( 'eduadmin-interestEventPage' );
	$allowInterestRegEvent = get_option( 'eduadmin-allowInterestRegEvent', false );
	$showMore              = isset( $attributes['showmore'] ) && ! empty( $attributes['showmore'] ) ? $attributes['showmore'] : -1;
?>
<div class="event-table eventDays"
     data-eduwidget="eventlist"
     data-objectid="<?php echo esc_attr( $selectedCourse["CourseTemplateId"] ); ?>"
     data-spotsleft="<?php echo @esc_attr( $spotLeftOption ); ?>"
     data-spotsettings="<?php echo @esc_attr( $spotSettings ); ?>"
     data-fewspots="<?php echo @esc_attr( $alwaysFewSpots ); ?>"
     data-showmore="<?php echo @esc_attr( $showMore ); ?>"
     data-groupbycity="<?php echo $groupByCity; ?>"
     data-fetchmonths="<?php echo $fetchMonths; ?>"
	<?php echo( isset( $_REQUEST['eid'] ) ? ' data-eid="' . intval( $_REQUEST['eid'] ) . '"' : '' ); ?>
     data-showvenue="<?php echo @esc_attr( $showEventVenue ); ?>"
     data-eventinquiry="<?php echo @esc_attr( get_option( 'eduadmin-allowInterestRegEvent', false ) ); ?>"
>
	<?php
		$i = 0;
		if ( ! empty( $prices ) ) {
			foreach ( $events as $ev ) {
				if ( isset( $_REQUEST['eid'] ) ) {
					if ( $ev["EventId"] != $_REQUEST['eid'] ) {
						continue;
					}
				}
				include( 'event-item.php' );
				$lastCity = $ev["City"];
				$i++;
			}
		}
		if ( empty( $prices ) || empty( $events ) ) {
			?>
            <div class="noDatesAvailable">
                <i><?php _e( "No available dates for the selected course", 'eduadmin-booking' ); ?></i>
            </div>
			<?php
		}
		if ( $hasHiddenDates ) {
			echo "<div class=\"eventShowMore\"><a class='neutral-btn' href=\"javascript://\" onclick=\"eduDetailView.ShowAllEvents('eduev" . ( $groupByCity ? "-" . $ev["City"] : "" ) . "', this);\">" . __( "Show all events", 'eduadmin-booking' ) . "</a></div>";
		}
	?>
</div>