<?php
	ob_start();
	global $wp_query;
	$apiKey = get_option( 'eduadmin-api-key' );

	if ( ! $apiKey || empty( $apiKey ) ) {
		echo 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
	} else {
		$surl    = get_home_url();
		$cat     = get_option( 'eduadmin-rewriteBaseUrl' );
		$baseUrl = $surl . '/' . $cat;

		$filtering = new XFiltering();
		$f         = new XFilter( 'ShowOnWeb', '=', 'true' );
		$filtering->AddItem( $f );

		$edo = EDU()->api->GetEducationObject( EDU()->get_token(), '', $filtering->ToString() );

		$selectedCourse = false;
		$name           = "";
		foreach ( $edo as $object ) {
			$name = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
			$id   = $object->ObjectID;
			if ( makeSlugs( $name ) == $wp_query->query_vars['courseSlug'] && $id == $wp_query->query_vars["courseId"] ) {
				$selectedCourse = $object;
				break;
			}
		}
		if ( ! $selectedCourse ) {
			?>
            <script type="text/javascript">location.href = '<?php echo $baseUrl; ?>';</script>
			<?php
			die();
		}

		$fetchMonths = get_option( 'eduadmin-monthsToFetch', 6 );
		if ( ! is_numeric( $fetchMonths ) ) {
			$fetchMonths = 6;
		}

		$ft = new XFiltering();
		$f  = new XFilter( 'PeriodStart', '<=', date( "Y-m-d 23:59:59", strtotime( 'now +' . $fetchMonths . ' months' ) ) );
		$ft->AddItem( $f );
		$f = new XFilter( 'PeriodEnd', '>=', date( "Y-m-d H:i:s", strtotime( 'now' ) ) );
		$ft->AddItem( $f );
		$f = new XFilter( 'ShowOnWeb', '=', 'true' );
		$ft->AddItem( $f );
		$f = new XFilter( 'StatusID', '=', '1' );
		$ft->AddItem( $f );
		$f = new XFilter( 'ObjectID', '=', $selectedCourse->ObjectID );
		$ft->AddItem( $f );
		$f = new XFilter( 'LastApplicationDate', '>=', date( "Y-m-d H:i:s" ) );
		$ft->AddItem( $f );

		$f = new XFilter( 'CustomerID', '=', '0' );
		$ft->AddItem( $f );

		$st               = new XSorting();
		$groupByCity      = get_option( 'eduadmin-groupEventsByCity', false );
		$groupByCityClass = "";
		if ( $groupByCity ) {
			$s = new XSort( 'City', 'ASC' );
			$st->AddItem( $s );
			$groupByCityClass = " noCity";
		}
		$s = new XSort( 'PeriodStart', 'ASC' );
		$st->AddItem( $s );

		$events = EDU()->api->GetEvent(
			EDU()->get_token(),
			$st->ToString(),
			$ft->ToString()
		);

		$incVat      = EDU()->api->GetAccountSetting( EDU()->get_token(), 'PriceIncVat' ) == "yes";
		$showHeaders = get_option( 'eduadmin-showDetailHeaders', true );

		$hideSections = array();
		if ( isset( $attributes['hide'] ) ) {
			$hideSections = explode( ',', $attributes['hide'] );
		}

		?>
        <div class="eduadmin">
            <a href="../" class="backLink"><?php _e( "« Go back", 'eduadmin-booking' ); ?></a>
            <div class="title">
	            <?php if ( ! empty( $selectedCourse->ImageUrl ) ) : ?>
                    <img class="courseImage" src="<?php echo $selectedCourse->ImageUrl; ?>"/>
	            <?php endif; ?>
                <h1 class="courseTitle"><?php echo $name; ?></h1>
            </div>
            <hr/>
            <div class="textblock leftBlock">
	            <?php if ( ! in_array( 'description', $hideSections ) && ! empty( $selectedCourse->CourseDescription ) ) { ?>
					<?php if ( $showHeaders ) { ?>
                        <h3><?php _e( "Course description", 'eduadmin-booking' ); ?></h3>
					<?php } ?>
                    <div>
						<?php
							echo $selectedCourse->CourseDescription;
						?>
                    </div>
				<?php } ?>
	            <?php if ( ! in_array( 'goal', $hideSections ) && ! empty( $selectedCourse->CourseGoal ) ) { ?>
					<?php if ( $showHeaders ) { ?>
                        <h3><?php _e( "Course goal", 'eduadmin-booking' ); ?></h3>
					<?php } ?>
                    <div>
						<?php
							echo $selectedCourse->CourseGoal;
						?>
                    </div>
				<?php } ?>
	            <?php if ( ! in_array( 'target', $hideSections ) && ! empty( $selectedCourse->TargetGroup ) ) { ?>
					<?php if ( $showHeaders ) { ?>
                        <h3><?php _e( "Target group", 'eduadmin-booking' ); ?></h3>
					<?php } ?>
                    <div>
						<?php
							echo $selectedCourse->TargetGroup;
						?>
                    </div>
				<?php } ?>
	            <?php if ( ! in_array( 'prerequisites', $hideSections ) && ! empty( $selectedCourse->Prerequisites ) ) { ?>
				<?php if ( $showHeaders ) { ?>
                    <h3><?php _e( "Prerequisites", 'eduadmin-booking' ); ?></h3>
				<?php } ?>
                <div>
					<?php
						echo $selectedCourse->Prerequisites;
					?>
                </div>
            </div>
            <div class="textblock rightBlock">
				<?php } ?>
	            <?php if ( ! in_array( 'after', $hideSections ) && ! empty( $selectedCourse->CourseAfter ) ) { ?>
					<?php if ( $showHeaders ) { ?>
                        <h3><?php _e( "After the course", 'eduadmin-booking' ); ?></h3>
					<?php } ?>
                    <div>
						<?php
							echo $selectedCourse->CourseAfter;
						?>
                    </div>
				<?php } ?>
	            <?php if ( ! in_array( 'quote', $hideSections ) && ! empty( $selectedCourse->Quote ) ) { ?>
					<?php if ( $showHeaders ) { ?>
                        <h3><?php _e( "Quotes", 'eduadmin-booking' ); ?></h3>
					<?php } ?>
                    <div>
						<?php
							echo $selectedCourse->Quote;
						?>
                    </div>
				<?php } ?>
            </div>
            <div class="eventInformation">
	            <?php if ( ! in_array( 'time', $hideSections ) && ! empty( $selectedCourse->StartTime ) && ! empty( $selectedCourse->EndTime ) ) { ?>
                    <h3><?php _e( "Time", 'eduadmin-booking' ); ?></h3>
					<?php
		            echo ( $selectedCourse->Days > 0 ? sprintf( _n( '%1$d day', '%1$d days', $selectedCourse->Days, 'eduadmin-booking' ), $selectedCourse->Days ) . ', ' : '' ) .
		                 date( "H:i", strtotime( $selectedCourse->StartTime ) ) . ' - ' . date( "H:i", strtotime( $selectedCourse->EndTime ) );
					?>
				<?php } ?>
				<?php

					$occIds   = Array();
					$occIds[] = - 1;

					$eventIds   = array();
					$eventIds[] = - 1;

					foreach ( $events as $e ) {
						$occIds[]   = $e->OccationID;
						$eventIds[] = $e->EventID;
					}

					$ft = new XFiltering();
					$f  = new XFilter( 'EventID', 'IN', join( ",", $eventIds ) );
					$ft->AddItem( $f );

					$eventDays = EDU()->api->GetEventDate( EDU()->get_token(), '', $ft->ToString() );

					$eventDates = array();
					foreach ( $eventDays as $ed ) {
						$eventDates[ $ed->EventID ][] = $ed;
					}

					$ft = new XFiltering();
					$f  = new XFilter( 'PublicPriceName', '=', 'true' );
					$ft->AddItem( $f );
					$f = new XFilter( 'ObjectID', 'IN', $selectedCourse->ObjectID );
					$ft->AddItem( $f );
					$f = new XFilter( 'OccationID', 'IN', join( ',', $occIds ) );
					$ft->AddItem( $f );

					$st = new XSorting();
					$s  = new XSort( 'Price', 'ASC' );
					$st->AddItem( $s );

					$prices       = EDU()->api->GetPriceName( EDU()->get_token(), $st->ToString(), $ft->ToString() );
					$uniquePrices = Array();
					foreach ( $prices as $price ) {
						$uniquePrices[ $price->Description ] = $price;
					}

					if ( ! in_array( 'price', $hideSections ) && ! empty( $prices ) ) {
						?>
                        <h3><?php _e( "Price", 'eduadmin-booking' ); ?></h3>
						<?php
						$currency = get_option( 'eduadmin-currency', 'SEK' );
						if ( count( $uniquePrices ) == 1 ) {
							?>
							<?php echo sprintf( '%1$s %2$s', current( $uniquePrices )->Description, convertToMoney( current( $uniquePrices )->Price, $currency ) ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ); ?>
							<?php
						} else {
							foreach ( $uniquePrices as $up ) {
								?>
								<?php echo sprintf( '%1$s %2$s', $up->Description, convertToMoney( $up->Price, $currency ) ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ); ?>
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
                           href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/interest/<?php echo edu_getQueryString( "?" ); ?>"><?php _e( "Send inquiry about this course", 'eduadmin-booking' ); ?></a>
                    </div>
			        <?php
		        }
	        ?>
        </div>
	<?php }
	$out = ob_get_clean();
	return $out;
