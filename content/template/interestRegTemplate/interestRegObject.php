<?php
	ob_start();
	global $wp_query;
	$apiKey = get_option( 'eduadmin-api-key' );

	if ( ! $apiKey || empty( $apiKey ) ) {
		echo 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
	} else {
		if ( isset( $_REQUEST['act'] ) && sanitize_text_field( $_REQUEST['act'] ) == 'objectInquiry' ) {
			include_once( "sendObjectInquiry.php" );
		}

		$edo = get_transient( 'eduadmin-listCourses' );
		if ( ! $edo ) {
			$filtering = new XFiltering();
			$f         = new XFilter( 'ShowOnWeb', '=', 'true' );
			$filtering->AddItem( $f );

			$edo = EDU()->api->GetEducationObject( EDU()->get_token(), '', $filtering->ToString() );
			set_transient( 'eduadmin-listCourses', $edo, 6 * HOUR_IN_SECONDS );
		}

		$courseId = 0;

		if ( null != $attributes && isset( $attributes['courseid'] ) ) {
			$courseId = intval( $attributes['courseid'] );
		} else {
			$courseId = $wp_query->query_vars["courseId"];
		}

		$selectedCourse = false;
		$name           = "";
		foreach ( $edo as $object ) {
			$name = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
			$id   = $object->ObjectID;
			if ( $id == $courseId ) {
				$selectedCourse = $object;
				break;
			}
		}
		if ( ! $selectedCourse ) {
			?>
            <script>history.go(-1);</script>
			<?php
			die();
		}

		?>
        <div class="eduadmin">
            <a href="../" class="backLink"><?php _e( "« Go back", 'eduadmin-booking' ); ?></a>
            <div class="title">
                <img src="<?php echo $selectedCourse->ImageUrl; ?>" class="courseImage"/>
                <h1 class="courseTitle"><?php echo $name; ?> - <?php _e( "Inquiry", 'eduadmin-booking' ); ?>
                    <small><?php echo( ! empty( $courseLevel ) ? $courseLevel[0]->Name : "" ); ?></small>
                </h1>
            </div>
            <hr/>
            <div class="textblock">
	            <?php _e( "Please fill out the form below to send a inquiry to us about this course.", 'eduadmin-booking' ); ?>
                <hr/>
                <form action="" method="POST">
                    <input type="hidden" name="objectid" value="<?php echo $selectedCourse->ObjectID; ?>"/>
                    <input type="hidden" name="act" value="objectInquiry"/>
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
					<textarea name="edu-notes" placeholder="<?php _e( "Notes", 'eduadmin-booking' ); ?>">
					</textarea>
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