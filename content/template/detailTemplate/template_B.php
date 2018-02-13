<?php
	ob_start();
	global $wp_query;
	$apiKey = get_option( 'eduadmin-api-key' );

	if ( ! $apiKey || empty( $apiKey ) ) {
		echo 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
	} else {
		include_once( 'course-info.php' );
		if ( ! $selectedCourse ) {
			?>
            <script type="text/javascript">location.href = '<?php echo $baseUrl; ?>';</script>
			<?php
			die();
		}

		?>
        <div class="eduadmin">
            <a href="../" class="backLink"><?php _e( "« Go back", 'eduadmin-booking' ); ?></a>
            <div class="title">
				<?php if ( ! empty( $selectedCourse["ImageUrl"] ) ) : ?>
                    <img class="courseImage" src="<?php echo $selectedCourse["ImageUrl"]; ?>"/>
				<?php endif; ?>
                <h1 class="courseTitle"><?php echo $name; ?>
                    <small class="courseLevel"><?php echo( $courseLevel != null ? $courseLevel["Name"] : "" ); ?></small>
                </h1>
            </div>
            <hr/>
            <div class="textblock leftBlock">
				<?php if ( ! in_array( 'description', $hideSections ) && ! empty( $selectedCourse["CourseDescription"] ) ) { ?>
					<?php if ( $showHeaders ) { ?>
                        <h3><?php _e( "Course description", 'eduadmin-booking' ); ?></h3>
					<?php } ?>
                    <div>
						<?php
							echo $selectedCourse["CourseDescription"];
						?>
                    </div>
				<?php } ?>
				<?php if ( ! in_array( 'goal', $hideSections ) && ! empty( $selectedCourse["CourseGoal"] ) ) { ?>
					<?php if ( $showHeaders ) { ?>
                        <h3><?php _e( "Course goal", 'eduadmin-booking' ); ?></h3>
					<?php } ?>
                    <div>
						<?php
							echo $selectedCourse["CourseGoal"];
						?>
                    </div>
				<?php } ?>
				<?php if ( ! in_array( 'target', $hideSections ) && ! empty( $selectedCourse["TargetGroup"] ) ) { ?>
					<?php if ( $showHeaders ) { ?>
                        <h3><?php _e( "Target group", 'eduadmin-booking' ); ?></h3>
					<?php } ?>
                    <div>
						<?php
							echo $selectedCourse["TargetGroup"];
						?>
                    </div>
				<?php } ?>
				<?php if ( ! in_array( 'prerequisites', $hideSections ) && ! empty( $selectedCourse["Prerequisites"] ) ) { ?>
				<?php if ( $showHeaders ) { ?>
                    <h3><?php _e( "Prerequisites", 'eduadmin-booking' ); ?></h3>
				<?php } ?>
                <div>
					<?php
						echo $selectedCourse["Prerequisites"];
					?>
                </div>
            </div>
            <div class="textblock rightBlock">
				<?php } ?>
				<?php if ( ! in_array( 'after', $hideSections ) && ! empty( $selectedCourse["CourseAfter"] ) ) { ?>
					<?php if ( $showHeaders ) { ?>
                        <h3><?php _e( "After the course", 'eduadmin-booking' ); ?></h3>
					<?php } ?>
                    <div>
						<?php
							echo $selectedCourse["CourseAfter"];
						?>
                    </div>
				<?php } ?>
				<?php if ( ! in_array( 'quote', $hideSections ) && ! empty( $selectedCourse["Quote"] ) ) { ?>
					<?php if ( $showHeaders ) { ?>
                        <h3><?php _e( "Quotes", 'eduadmin-booking' ); ?></h3>
					<?php } ?>
                    <div>
						<?php
							echo $selectedCourse["Quote"];
						?>
                    </div>
				<?php } ?>
            </div>
            <div class="eventInformation">
				<?php if ( ! in_array( 'time', $hideSections ) && ! empty( $selectedCourse["StartTime"] ) && ! empty( $selectedCourse["EndTime"] ) ) { ?>
                    <h3><?php _e( "Time", 'eduadmin-booking' ); ?></h3>
					<?php
					echo ( $selectedCourse["Days"] > 0 ? sprintf( _n( '%1$d day', '%1$d days', $selectedCourse["Days"], 'eduadmin-booking' ), $selectedCourse["Days"] ) . ', ' : '' ) .
					     date( "H:i", strtotime( $selectedCourse["StartTime"] ) ) . ' - ' . date( "H:i", strtotime( $selectedCourse["EndTime"] ) );
					?>
				<?php } ?>
				<?php

					if ( ! in_array( 'price', $hideSections ) && ! empty( $prices ) ) {
						?>
                        <h3><?php _e( "Price", 'eduadmin-booking' ); ?></h3>
						<?php
						$currency = get_option( 'eduadmin-currency', 'SEK' );
						if ( count( $prices ) == 1 ) {
							?>
							<?php echo sprintf( '%1$s %2$s', current( $prices )["PriceNameDescription"], convertToMoney( current( $prices )["Price"], $currency ) ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ); ?>
							<?php
						} else {
							foreach ( $prices as $up ) {
								?>
								<?php echo sprintf( '%1$s %2$s', $up["PriceNameDescription"], convertToMoney( $up["Price"], $currency ) ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ); ?>
                                <br/>
								<?php
							}
						}
					} ?>
            </div>
			<?php
				include( 'blocks/event-list.php' );
				if ( $allowInterestRegObject && $objectInterestPage != false ) {
					?>
                    <br/>
                    <div class="inquiry">
                        <a class="inquiry-link"
                           href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $selectedCourse["CourseTemplateId"]; ?>/interest/<?php echo edu_getQueryString( "?" ); ?>"><?php _e( "Send inquiry about this course", 'eduadmin-booking' ); ?></a>
                    </div>
					<?php
				}
			?>
        </div>
	<?php }
	$out = ob_get_clean();

	return $out;
