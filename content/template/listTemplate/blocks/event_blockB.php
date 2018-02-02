<div class="objectBlock brick <?php echo edu_get_percent_from_values( $spotsLeft, $object->MaxParticipantNr ); ?>">
	<?php if ( $showImages && !empty( $object->ImageUrl ) ) { ?>
        <div class="objectImage"
             onclick="location.href = '<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&" ); ?>';"
             style="background-image: url('<?php echo $object->ImageUrl; ?>');"></div>
	<?php } ?>
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
					( $showCourseDays ? sprintf( _n( '%1$d day', '%1$d days', $object->Days, 'eduadmin-booking' ), $object->Days ) . ( $showCourseTimes ? ', ' : '' ) : '' ) .
					( $showCourseTimes ? date( "H:i", strtotime( $object->StartTime ) ) .
					                     ' - ' .
					                     date( "H:i", strtotime( $object->EndTime ) ) : '' ) .
					"</div>";
			}
			if ( $showEventPrice ) {
				echo "<div class=\"priceInfo\">" . sprintf( __( 'From %1$s', 'eduadmin-booking' ), convertToMoney( $object->Price, $currency ) ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ) . "</div> ";
			}
			echo '<div class="spotsLeft"></div>';
			echo "<span class=\"spotsLeftInfo\">" . getSpotsLeft( $spotsLeft, $object->MaxParticipantNr, $spotLeftOption, $spotSettings, $alwaysFewSpots ) . "</span>";
		?></div>
    <div class="objectBook">
		<?php if ( $showReadMoreBtn ) : ?>
            <a class="readMoreButton cta-btn"
               href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&" ); ?>"><?php _e( "Read more", 'eduadmin-booking' ); ?></a>
		<?php endif; ?>
    </div>
</div>