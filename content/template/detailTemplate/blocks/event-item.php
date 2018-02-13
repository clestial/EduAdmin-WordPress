<?php
	if ( $groupByCity && $lastCity != $ev[ "City" ] ) {
		$i = 0;
		if ( $hasHiddenDates ) {
			echo "<div class=\"eventShowMore\"><a class='neutral-btn' href=\"javascript://\" onclick=\"eduDetailView.ShowAllEvents('eduev-" . $lastCity . "', this);\">" . __( "Show all events", 'eduadmin-booking' ) . "</a></div>";
		}
		$hasHiddenDates = false;
		echo '<div class="eventSeparator">' . $ev[ "City" ] . '</div>';
	}

	if ( $showMore > 0 && $i >= $showMore ) {
		$hasHiddenDates = true;
	}

	$eventDates = array();
	if ( ! empty( $ev[ "EventDates" ] ) ) {
		$eventDates[ $ev[ "EventId" ] ] = $ev[ "EventDates" ];
	}

?>
<div data-groupid="eduev<?php echo( $groupByCity ? "-" . $ev[ "City" ] : "" ); ?>"
     class="eventItem<?php echo( $showMore > 0 && $i >= $showMore ? " showMoreHidden" : "" ); ?>">
    <div class="eventDate<?php echo $groupByCityClass; ?>">
		<?php echo isset( $eventDates[ $ev[ "EventId" ] ] ) ? GetLogicalDateGroups( $eventDates[ $ev[ "EventId" ] ] ) : GetOldStartEndDisplayDate( $ev[ "StartDate" ], $ev[ "EndDate" ] ); ?>
		<?php echo( ! isset( $eventDates[ $ev[ "EventId" ] ] ) || count( $eventDates[ $ev[ "EventId" ] ] ) == 1 ? "<span class=\"eventTime\">, " . date( "H:i", strtotime( $ev[ "StartDate" ] ) ) . ' - ' . date( "H:i", strtotime( $ev[ "EndDate" ] ) ) . "</span>" : "" ); ?>
    </div>
	<?php if ( ! $groupByCity ) { ?>
        <div class="eventCity">
			<?php
				echo $ev[ "City" ];
				if ( $showEventVenue && ! empty( $ev[ "AddressName" ] ) ) {
					echo "<span class=\"venueInfo\">, " . $ev[ "AddressName" ] . "</span>";
				}
			?>
        </div>
	<?php } ?>
    <div class="eventStatus<?php echo $groupByCityClass; ?>">
		<?php
			$spotsLeft = $ev[ "ParticipantNumberLeft" ];
			echo "<span class=\"spotsLeftInfo\">" . getSpotsLeft( $spotsLeft, $ev[ "MaxParticipantNumber" ], $spotLeftOption, $spotSettings, $alwaysFewSpots ) . "</span>";
		?>
    </div>
    <div class="eventBook<?php echo $groupByCityClass; ?>">
		<?php
			if ( $ev[ "MaxParticipantNumber" ] == 0 || $spotsLeft > 0 ) {
				?>
                <a class="bookButton book-link cta-btn"
                   href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $selectedCourse[ "CourseTemplateId" ]; ?>/book/?eid=<?php echo $ev[ "EventId" ]; ?><?php echo edu_getQueryString( "&" ); ?>"
                ><?php _e( "Book", 'eduadmin-booking' ); ?></a>
				<?php
			} else {
				?>
				<?php

				if ( $allowInterestRegEvent && $eventInterestPage != false ) {
					?>
                    <a class="inquiry-link"
                       href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $selectedCourse[ "CourseTemplateId" ]; ?>/book/interest/?eid=<?php echo $ev[ "EventId" ]; ?><?php echo edu_getQueryString( "&" ); ?>"><?php _e( "Inquiry", 'eduadmin-booking' ); ?></a>
					<?php
				}
				?>
                <i class="fullBooked"><?php _e( "Full", 'eduadmin-booking' ); ?></i>
			<?php } ?>
    </div>
</div>