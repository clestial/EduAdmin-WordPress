<?php
	ob_start();
	global $wp_query;
	global $eduapi;
	global $edutoken;
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

			$edo = $eduapi->GetEducationObject( $edutoken, '', $filtering->ToString() );
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
            <a href="../" class="backLink"><?php edu_e( "« Go back" ); ?></a>
            <div class="title">
                <img src="<?php echo $selectedCourse->ImageUrl; ?>" class="courseImage"/>
                <h1 class="courseTitle"><?php echo $name; ?> - <?php edu_e( "Inquiry" ); ?>
                    <small><?php echo( ! empty( $courseLevel ) ? $courseLevel[0]->Name : "" ); ?></small>
                </h1>
            </div>
            <hr/>
            <div class="textblock">
				<?php edu_e( "Please fill out the form below to send a inquiry to us about this course." ); ?>
                <hr/>
                <form action="" method="POST">
                    <input type="hidden" name="objectid" value="<?php echo $selectedCourse->ObjectID; ?>"/>
                    <input type="hidden" name="act" value="objectInquiry"/>
                    <input type="hidden" name="email"/>
                    <label>
                        <div class="inputLabel"><?php edu_e( "Customer name" ); ?> *</div>
                        <div class="inputHolder">
                            <input type="text" required name="edu-companyName"
                                   placeholder="<?php edu_e( "Customer name" ); ?>"/>
                        </div>
                    </label>
                    <label>
                        <div class="inputLabel"><?php edu_e( "Contact name" ); ?> *</div>
                        <div class="inputHolder">
                            <input type="text" required name="edu-contactName"
                                   placeholder="<?php edu_e( "Contact name" ); ?>"/>
                        </div>
                    </label>
                    <label>
                        <div class="inputLabel"><?php edu_e( "E-mail address" ); ?> *</div>
                        <div class="inputHolder">
                            <input type="email" required name="edu-emailAddress"
                                   placeholder="<?php edu_e( "E-mail address" ); ?>"/>
                        </div>
                    </label>
                    <label>
                        <div class="inputLabel"><?php edu_e( "Phone number" ); ?></div>
                        <div class="inputHolder">
                            <input type="tel" name="edu-phone" placeholder="<?php edu_e( "Phone number" ); ?>"/>
                        </div>
                    </label>
                    <label>
                        <div class="inputLabel"><?php edu_e( "Mobile number" ); ?></div>
                        <div class="inputHolder">
                            <input type="tel" name="edu-mobile" placeholder="<?php edu_e( "Mobile number" ); ?>"/>
                        </div>
                    </label>
                    <label>
                        <div class="inputLabel"><?php edu_e( "Notes" ); ?></div>
                        <div class="inputHolder">
					<textarea name="edu-notes" placeholder="<?php edu_e( "Notes" ); ?>">
					</textarea>
                        </div>
                    </label>
					<?php if ( get_option( 'eduadmin-singlePersonBooking', false ) ) { ?>
                        <input type="hidden" name="edu-participants" value="1"/>
					<?php } else { ?>
                        <label>
                            <div class="inputLabel"><?php edu_e( "Participants" ); ?> *</div>
                            <div class="inputHolder">
                                <input type="number" min="1" required name="edu-participants"
                                       placeholder="<?php edu_e( "Participants" ); ?>"/>
                            </div>
                        </label>
					<?php } ?>

                    <input type="submit" class="bookButton" value="<?php edu_e( "Send inquiry" ); ?>"/>
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