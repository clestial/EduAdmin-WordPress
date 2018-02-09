<div class="objectBlock brick">
	<?php if ( $showImages && ! empty( $object["ImageUrl"] ) ) { ?>
        <div class="objectImage"
             onclick="location.href = '<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object["CourseTemplateId"]; ?>/<?php echo edu_getQueryString(); ?>';"
             style="background-image: url('<?php echo $object["ImageUrl"]; ?>');"></div>
	<?php } ?>
    <div class="objectName">
        <a href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object["CourseTemplateId"]; ?>/<?php echo edu_getQueryString(); ?>"><?php
				echo htmlentities( getUTF8( $name ) );
			?></a>
    </div>
    <div class="objectDescription"><?php
		    if ( stripos( $descrField, "attr_" ) !== false && ! empty( $objectAttributes ) ) {
				$objectDescription = array_filter( $objectAttributes, function( $oa ) use ( &$object ) {
					return $oa->ObjectID == $object["CourseTemplateId"];
				} );

				$descr = htmlspecialchars_decode( current( $objectDescription )->AttributeValue );
			} else {
			    $descr = $object[ $descrField ];
			}

			if ( $showDescr ) {
				echo "<div class\"courseDescription\">" . $descr . "</div>";
			}

		    if ( $showCourseLocations && ! empty( $eventCities ) && $showCity ) {
				$cities = join( ", ", array_keys( $eventCities ) );
				echo "<div class=\"locationInfo\">" . $cities . "</div> ";
			}

			if ( $showNextEventDate ) {
				echo "<div class=\"nextEventDate\" data-eduwidget=\"courseitem-date\" data-objectid=\"" . $object["CourseTemplateId"] . "\">";
				if ( ! empty( $sortedEvents ) ) {
					echo sprintf( __( 'Next event %1$s', 'eduadmin-booking' ), date( "Y-m-d", strtotime( current( $sortedEvents )["StartDate"] ) ) ) . " " . current( $sortedEvents )["City"];
					if ( $showEventVenue ) {
						echo "<span class=\"venueInfo\">, " . current( $sortedEvents )["AddressName"] . "</span>";
					}
				} else {
					echo "<i>" . __( 'No coming events', 'eduadmin-booking' ) . "</i>";
				}
				echo "</div> ";
			}

		    if ( $showEventPrice && ! empty( $prices ) ) {
				ksort( $prices );
				$cheapest = current( $prices );
			    echo "<div class=\"priceInfo\">" . sprintf( __( 'From %1$s', 'eduadmin-booking' ), convertToMoney( $cheapest["Price"], get_option( 'eduadmin-currency', 'SEK' ) ) ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ) . "</div> ";
			}

		    if ( $object["Days"] > 0 ) {
				echo
					"<div class=\"dayInfo\">" .
					( $showCourseDays ? sprintf( _n( '%1$d day', '%1$d days', $object["Days"], 'eduadmin-booking' ), $object["Days"] ) . ( $showCourseTimes ? ', ' : '' ) : '' ) .
					( $showCourseTimes ? date( "H:i", strtotime( $object["StartTime"] ) ) .
					                     ' - ' .
					                     date( "H:i", strtotime( $object["EndTime"] ) ) : '' ) .
					"</div>";
			}
		?></div>
    <div class="objectBook">
		<?php if ( $showReadMoreBtn ) : ?>
            <a class="readMoreButton cta-btn"
               href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object["CourseTemplateId"]; ?>/<?php echo edu_getQueryString(); ?>"><?php _e( "Read more", 'eduadmin-booking' ); ?></a>
		<?php endif; ?>
    </div>
</div>