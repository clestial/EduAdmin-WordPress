<div class="objectBlock brick <?php echo edu_get_percent_from_values( $spotsLeft, $event['MaxParticipantNumber'] ); ?>">
	<?php if ( $showImages && ! empty( $object["ImageUrl"] ) ) { ?>
		<div class="objectImage"
		     onclick="location.href = '<?php echo $baseUrl; ?>/<?php echo make_slugs( $name ); ?>__<?php echo $object['CourseTemplateId']; ?>/?eid=<?php echo $event['EventId']; ?><?php echo edu_get_query_string( "&" ); ?>';"
		     style="background-image: url('<?php echo $object["ImageUrl"]; ?>');"></div>
	<?php } ?>
	<div class="objectName">
		<a href="<?php echo $baseUrl; ?>/<?php echo make_slugs( $name ); ?>__<?php echo $object['CourseTemplateId']; ?>/?eid=<?php echo $event['EventId']; ?><?php echo edu_get_query_string( "&" ); ?>"><?php
			echo htmlentities( get_utf8( $name ) );
			?></a>
	</div>
	<div class="objectDescription"><?php
		echo get_old_start_end_display_date( $event['StartDate'], $event['EndDate'], true, $showWeekDays );

		if ( ! empty( $event['City'] ) && $showCity ) {
			echo " <span class=\"cityInfo\">";
			echo $event['City'];
			if ( $showEventVenue && ! empty( $event['AddressName'] ) ) {
				echo "<span class=\"venueInfo\">, " . $event['AddressName'] . "</span>";
			}
			echo "</span>";
		}

		if ( $object['Days'] > 0 ) {
			echo
				"<div class=\"dayInfo\">" .
				( $showCourseDays ? sprintf( _n( '%1$d day', '%1$d days', $object['Days'], 'eduadmin-booking' ), $object['Days'] ) .
				                    ( $showCourseTimes && $event['StartDate'] != '' && $event['EndDate'] != '' && ! isset( $eventDates[ $event['EventId'] ] ) ? ', ' : '' ) : '' ) .
				( $showCourseTimes && $event['StartDate'] != '' && $event['EndDate'] != '' && ! isset( $eventDates[ $event['EventId'] ] ) ? date( "H:i", strtotime( $event['StartDate'] ) ) .
				                                                                                                                            ' - ' .
				                                                                                                                            date( "H:i", strtotime( $event['EndDate'] ) ) : '' ) .
				"</div>";
		}
		if ( $showEventPrice && isset( $event['Price'] ) ) {
			if ( $event['Price'] == 0 ) {
				echo "<div class=\"priceInfo\">" . _x( 'Free of charge', 'The course/event has no cost', 'eduadmin-booking' ) . "</div> ";
			} else {
				echo "<div class=\"priceInfo\">" . sprintf( __( 'From %1$s', 'eduadmin-booking' ), convert_to_money( $event['Price'], $currency ) ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ) . "</div> ";
			}
		}
		echo '<div class="spotsLeft"></div>';
		echo "<span class=\"spotsLeftInfo\">" . get_spots_left( $spotsLeft, $event['MaxParticipantNumber'], $spotLeftOption, $spotSettings, $alwaysFewSpots ) . "</span>";
		?></div>
	<div class="objectBook">
		<?php if ( $showReadMoreBtn ) : ?>
			<a class="readMoreButton cta-btn"
			   href="<?php echo $baseUrl; ?>/<?php echo make_slugs( $name ); ?>__<?php echo $object['CourseTemplateId']; ?>/?eid=<?php echo $event['EventId']; ?><?php echo edu_get_query_string( "&" ); ?>"><?php _e( "Read more", 'eduadmin-booking' ); ?></a>
		<?php endif; ?>
	</div>
</div>