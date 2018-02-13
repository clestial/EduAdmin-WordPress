<?php
	ob_start();
	global $wp_query;
	$apiKey = get_option( 'eduadmin-api-key' );

	if ( ! $apiKey || empty( $apiKey ) ) {
		echo 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
	} else {
		if ( isset( $_REQUEST['act'] ) && sanitize_text_field( $_REQUEST['act'] ) == 'eventInquiry' ) {
			include_once( "sendEventInquiry.php" );
		}

		$courseId = $wp_query->query_vars["courseId"];
		$edo      = get_transient( 'eduadmin-object_' . $courseId );
		if ( ! $edo ) {
			$edo = EDUAPI()->OData->CourseTemplates->GetItem(
				$courseId,
				null,
				"Subjects,Events,CustomFields"
			);
			set_transient( 'eduadmin-object_' . $courseId, $edo, 10 );
		}

		$selectedCourse = false;
		$name           = "";
		if ( $edo ) {
			$name           = ( ! empty( $edo["CourseName"] ) ? $edo["CourseName"] : $edo["InternalCourseName"] );
			$selectedCourse = $edo;
		}

		if ( ! $selectedCourse ) {
			?>
            <script>history.go(-1);</script>
			<?php
			die();
		}

		$ft = new XFiltering();
		if ( isset( $_REQUEST['eid'] ) ) {
			$eventid = intval( $_REQUEST['eid'] );
			$f       = new XFilter( 'EventID', '=', $eventid );
			$ft->AddItem( $f );
		}
		$f = new XFilter( 'ShowOnWeb', '=', 'true' );
		$ft->AddItem( $f );
		$f = new XFilter( 'ObjectID', '=', $selectedCourse["CourseTemplateId"] );
		$ft->AddItem( $f );
		$f = new XFilter( 'LastApplicationDate', '>=', date( "Y-m-d H:i:s" ) );
		$ft->AddItem( $f );
		$f = new XFilter( 'StatusID', '=', '1' );
		$ft->AddItem( $f );

		$st = new XSorting();
		$s  = new XSort( 'PeriodStart', 'ASC' );
		$st->AddItem( $s );

		$events = EDU()->api->GetEvent(
			EDU()->get_token(),
			$st->ToString(),
			$ft->ToString()
		);

		if ( count( $events ) == 0 ) {
			?>
            <script>history.go(-1);</script>
			<?php
			die();
		}

		$event = $events[0];

		?>
        <div class="eduadmin">
            <a href="../../" class="backLink"><?php _e( "« Go back", 'eduadmin-booking' ); ?></a>
            <div class="title">
				<?php if ( ! empty( $selectedCourse["ImageUrl"] ) ) : ?>
                    <img src="<?php echo $selectedCourse["ImageUrl"]; ?>" class="courseImage"/>
				<?php endif; ?>
                <h1 class="courseTitle"><?php echo $name; ?> - <?php _e( "Inquiry", 'eduadmin-booking' ); ?>
                    <small><?php echo( ! empty( $courseLevel ) ? $courseLevel[0]->Name : "" ); ?></small>
                </h1>
            </div>
			<?php
				echo "<div class=\"dateInfo\">" . GetOldStartEndDisplayDate( $event->PeriodStart, $event->PeriodEnd ) . ", ";
				echo date( "H:i", strtotime( $event->PeriodStart ) ); ?>
            - <?php echo date( "H:i", strtotime( $event->PeriodEnd ) );
				$addresses = get_transient( 'eduadmin-location-' . $event->LocationAddressID );
				if ( ! $addresses ) {
					$ft = new XFiltering();
					$f  = new XFilter( 'LocationAddressID', '=', $event->LocationAddressID );
					$ft->AddItem( $f );
					$addresses = EDU()->api->GetLocationAddress( EDU()->get_token(), '', $ft->ToString() );
					set_transient( 'eduadmin-location-' . $event->LocationAddressID, $addresses, HOUR_IN_SECONDS );
				}

				foreach ( $addresses as $address ) {
					if ( $address->LocationAddressID === $event->LocationAddressID ) {
						echo ", " . $event->AddressName . ", " . $address->Address . ", " . $address->City;
						break;
					}
				}
				echo "</div>";
			?>
            <hr/>
            <div class="textblock">
				<?php _e( "Please fill out the form below to send a inquiry to us about this course.", 'eduadmin-booking' ); ?>
                <hr/>
                <form action="" method="POST">
                    <input type="hidden" name="objectid" value="<?php echo $selectedCourse["CourseTemplateId"]; ?>"/>
                    <input type="hidden" name="eventid" value="<?php echo $event->EventID; ?>"/>
                    <input type="hidden" name="act" value="eventInquiry"/>
                    <input type="hidden" name="email"/>
                    <label>
                        <div class="inputLabel"><?php _e( "Customer name", 'eduadmin-booking' ); ?> *</div>
                        <div class="inputHolder">
                            <input type="text" required name="edu-companyName"
                                   placeholder="<?php _e( "Customer name", 'eduadmin-booking' ); ?>"/>
                        </div>
                    </label>
                    <label>
                        <div class="inputLabel"><?php _e( "Contact name", 'eduadmin-booking' ); ?> *</div>
                        <div class="inputHolder">
                            <input type="text" required name="edu-contactName"
                                   placeholder="<?php _e( "Contact name", 'eduadmin-booking' ); ?>"/>
                        </div>
                    </label>
                    <label>
                        <div class="inputLabel"><?php _e( "E-mail address", 'eduadmin-booking' ); ?> *</div>
                        <div class="inputHolder">
                            <input type="email" required name="edu-emailAddress"
                                   placeholder="<?php _e( "E-mail address", 'eduadmin-booking' ); ?>"/>
                        </div>
                    </label>
                    <label>
                        <div class="inputLabel"><?php _e( "Phone number", 'eduadmin-booking' ); ?></div>
                        <div class="inputHolder">
                            <input type="tel" name="edu-phone"
                                   placeholder="<?php _e( "Phone number", 'eduadmin-booking' ); ?>"/>
                        </div>
                    </label>
                    <label>
                        <div class="inputLabel"><?php _e( "Mobile number", 'eduadmin-booking' ); ?></div>
                        <div class="inputHolder">
                            <input type="tel" name="edu-mobile"
                                   placeholder="<?php _e( "Mobile number", 'eduadmin-booking' ); ?>"/>
                        </div>
                    </label>
                    <label>
                        <div class="inputLabel"><?php _e( "Notes", 'eduadmin-booking' ); ?></div>
                        <div class="inputHolder">
                            <textarea name="edu-notes"
                                      placeholder="<?php _e( "Notes", 'eduadmin-booking' ); ?>"></textarea>
                        </div>
                    </label>
					<?php if ( get_option( 'eduadmin-singlePersonBooking', false ) ) { ?>
                        <input type="hidden" name="edu-participants" value="1"/>
					<?php } else { ?>
                        <label>
                            <div class="inputLabel"><?php _e( "Participants", 'eduadmin-booking' ); ?> *</div>
                            <div class="inputHolder">
                                <input type="number" min="1" required name="edu-participants"
                                       placeholder="<?php _e( "Participants", 'eduadmin-booking' ); ?>"/>
                            </div>
                        </label>
					<?php } ?>
                    <input type="submit" class="bookButton cta-btn"
                           value="<?php _e( "Send inquiry", 'eduadmin-booking' ); ?>"/>
                </form>
            </div>
        </div>
		<?php
		$originalTitle = get_the_title();
		$newTitle      = $name . " | " . $originalTitle;
		?>
        <script type="text/javascript">
            (function () {
                var title = document.title;
                title = title.replace('<?php echo $originalTitle; ?>', '<?php echo $newTitle; ?>');
                document.title = title;
            })();
        </script>
		<?php
	}

	$out = ob_get_clean();

	return $out;