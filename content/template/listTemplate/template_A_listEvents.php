<?php
	ob_start();
	include( "list-events.php" );
?>
    <div class="eventListTable"
         data-eduwidget="listview-eventlist"
         data-template="A"
         data-subject="<?php echo @esc_attr( $attributes['subject'] ); ?>"
         data-subjectid="<?php echo @esc_attr( $attributes['subjectid'] ); ?>"
         data-category="<?php echo @esc_attr( $attributes['category'] ); ?>"
         data-courselevel="<?php echo @esc_attr( $attributes['courselevel'] ); ?>"
         data-city="<?php echo @esc_attr( $attributes['city'] ); ?>"
         data-spotsleft="<?php echo @esc_attr( $spotLeftOption ); ?>"
         data-spotsettings="<?php echo @esc_attr( $spotSettings ); ?>"
         data-fewspots="<?php echo @esc_attr( $alwaysFewSpots ); ?>"
         data-showcoursedays="<?php echo @esc_attr( $showCourseDays ); ?>"
         data-showcoursetimes="<?php echo @esc_attr( $showCourseTimes ); ?>"
         data-showweekdays="<?php echo @esc_attr( $showWeekDays ); ?>"
         data-showcourseprices="<?php echo @esc_attr( $showEventPrice ); ?>"
         data-currency="<?php echo @esc_attr( $currency ); ?>"
         data-search="<?php echo @esc_attr( sanitize_text_field( $_REQUEST['searchCourses'] ) ); ?>"
         data-showimages="<?php echo @esc_attr( $showImages ); ?>"
         data-numberofevents="<?php echo @esc_attr( $attributes['numberofevents'] ); ?>"
         data-fetchmonths="<?php echo @esc_attr( $fetchMonths ); ?>"
         data-orderby="<?php echo @esc_attr( $attributes['orderby'] ); ?>"
         data-order="<?php echo @esc_attr( $attributes['order'] ); ?>"
         data-showvenue="<?php echo @esc_attr( $showEventVenue ); ?>"
    >
		<?php

			$numberOfEvents = $attributes['numberofevents'];
			$currentEvents  = 0;

			foreach ( $ede as $object ) {
				if ( $numberOfEvents != null && $numberOfEvents > 0 && $currentEvents >= $numberOfEvents ) {
					break;
				}
				$name      = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
				$spotsLeft = ( $object->MaxParticipantNr - $object->TotalParticipantNr );
				?>
                <div class="objectItem <?php echo edu_get_percent_from_values( $spotsLeft, $object->MaxParticipantNr ); ?>">
					<?php if ( $showImages && ! empty( $object->ImageUrl ) ) { ?>
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
								echo /*isset($eventDates[$object->EventID]) ? GetLogicalDateGroups($eventDates[$object->EventID], true, $object, true) :*/
								GetOldStartEndDisplayDate( $object->PeriodStart, $object->PeriodEnd, true, $showWeekDays );

								if ( ! empty( $object->City ) && $showCity ) {
									echo " <span class=\"cityInfo\">";
									echo $object->City;
									if ( $showEventVenue && ! empty( $object->AddressName ) ) {
										echo "<span class=\"venueInfo\">, " . $object->AddressName . "</span>";
									}
									echo "</span>";
								}

								if ( $object->Days > 0 ) {
									echo
										"<div class=\"dayInfo\">" .
										( $showCourseDays ? sprintf( edu_n( '%1$d day', '%1$d days', $object->Days ), $object->Days ) .
										                    ( $showCourseTimes && $object->StartTime != '' && $object->EndTime != '' && ! isset( $eventDates[ $object->EventID ] ) ? ', ' : '' ) : '' ) .
										( $showCourseTimes && $object->StartTime != '' && $object->EndTime != '' && ! isset( $eventDates[ $object->EventID ] ) ? date( "H:i", strtotime( $object->StartTime ) ) .
										                                                                                                                         ' - ' .
										                                                                                                                         date( "H:i", strtotime( $object->EndTime ) ) : '' ) .
										"</div>";
								}

								if ( $showEventPrice && isset( $object->Price ) ) {
									echo "<div class=\"priceInfo\">" . sprintf( edu__( 'From %1$s' ), convertToMoney( $object->Price, $currency ) ) . " " . edu__( $incVat ? "inc vat" : "ex vat" ) . "</div> ";
								}

		                        echo "<span class=\"spotsLeftInfo\">" . getSpotsLeft( $spotsLeft, $object->MaxParticipantNr, $spotLeftOption, $spotSettings, $alwaysFewSpots ) . "</span>";

							?></div>
                    </div>
                    <div class="objectBook">
		                <?php if ( $showReadMoreBtn ) : ?>
                            <a class="readMoreButton"
                               href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&" ); ?>"><?php edu_e( "Read more" ); ?></a>
                            <br/>
		                <?php endif; ?>
		                <?php
			                if ( $showBookBtn ) {
				                if ( $spotsLeft > 0 || $object->MaxParticipantNr == 0 ) {
					                ?>
                                    <a class="bookButton"
                                       href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/book/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&" ); ?>"><?php edu_e( "Book" ); ?></a>
					                <?php
				                } else {
					                ?>
                                    <i class="fullBooked"><?php edu_e( "Full" ); ?></i>
					                <?php
				                }
			                }
		                ?>
                    </div>
                </div>
				<?php
				$currentEvents ++;
			}
		?>
    </div><!-- /eventlist -->
<?php
	$out = ob_get_clean();
	return $out;