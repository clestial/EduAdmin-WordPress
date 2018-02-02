<div class="objectItem <?php echo edu_get_percent_from_values( $spotsLeft, $object->MaxParticipantNr ); ?>">
	<?php if ( $showImages && !empty( $object->ImageUrl ) ) { ?>
        <div class="objectImage"
             onclick="location.href = '<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&" ); ?>';"
             style="background-image: url('<?php echo $object->ImageUrl; ?>');"></div>
	<?php } ?>
    <div class="objectInfoHolder">
        <div class="objectName">
            <a href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&" ); ?>"><?php
					echo htmlentities( getUTF8( $name ) );
				?></a>
        </div>
        <div class="objectDescription"><?php
		        echo GetOldStartEndDisplayDate( $object->PeriodStart, $object->PeriodEnd, true, $showWeekDays );

		        if ( !empty( $object->City ) && $showCity ) {
					echo " <span class=\"cityInfo\">";
					echo $object->City;
			        if ( $showEventVenue && !empty( $object->AddressName ) ) {
						echo "<span class=\"venueInfo\">, " . $object->AddressName . "</span>";
					}
					echo "</span>";
				}

				if ( $object->Days > 0 ) {
					echo
						"<div class=\"dayInfo\">" .
						( $showCourseDays ? sprintf( _n( '%1$d day', '%1$d days', $object->Days, 'eduadmin-booking' ), $object->Days ) .
						                    ( $showCourseTimes && $object->StartTime != '' && $object->EndTime != '' && !isset( $eventDates[ $object->EventID ] ) ? ', ' : '' ) : '' ) .
						( $showCourseTimes && $object->StartTime != '' && $object->EndTime != '' && !isset( $eventDates[ $object->EventID ] ) ? date( "H:i", strtotime( $object->StartTime ) ) .
						                                                                                                                        ' - ' .
						                                                                                                                        date( "H:i", strtotime( $object->EndTime ) ) : '' ) .
						"</div>";
				}

				if ( $showEventPrice && isset( $object->Price ) ) {
					echo "<div class=\"priceInfo\">" . sprintf( __( 'From %1$s', 'eduadmin-booking' ), convertToMoney( $object->Price, $currency ) ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ) . "</div> ";
				}

				echo "<span class=\"spotsLeftInfo\">" . getSpotsLeft( $spotsLeft, $object->MaxParticipantNr, $spotLeftOption, $spotSettings, $alwaysFewSpots ) . "</span>";

			?></div>
    </div>
    <div class="objectBook">
		<?php
			if ( $showBookBtn ) {
				if ( $spotsLeft > 0 || $object->MaxParticipantNr == 0 ) {
					?>
                    <a class="bookButton cta-btn"
                       href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/book/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&" ); ?>"><?php _e( "Book", 'eduadmin-booking' ); ?></a>
					<?php
				} else {
					?>
                    <i class="fullBooked"><?php _e( "Full", 'eduadmin-booking' ); ?></i>
					<?php
				}
			}
		?>
		<?php if ( $showReadMoreBtn ) : ?>
            <a class="readMoreButton"
               href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&" ); ?>"><?php _e( "Read more", 'eduadmin-booking' ); ?></a>
            <br/>
		<?php endif; ?>
    </div>
</div>