<?php
	ob_start();
	global $wp_query;
	global $eduapi;
	global $edutoken;
	$apiKey = get_option( 'eduadmin-api-key' );

	if ( ! $apiKey || empty( $apiKey ) ) {
		echo 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
	} else {
		$edo = get_transient( 'eduadmin-listCourses' );
		if ( ! $edo ) {
			$filtering = new XFiltering();
			$f         = new XFilter( 'ShowOnWeb', '=', 'true' );
			$filtering->AddItem( $f );

			$edo = EDU()->api->GetEducationObject( $edutoken, '', $filtering->ToString() );
			set_transient( 'eduadmin-listCourses', $edo, 6 * HOUR_IN_SECONDS );
		}

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
		$f = new XFilter( 'ObjectID', '=', $selectedCourse->ObjectID );
		$ft->AddItem( $f );
		$f = new XFilter( 'LastApplicationDate', '>=', date( "Y-m-d H:i:s" ) );
		$ft->AddItem( $f );
		$f = new XFilter( 'StatusID', '=', '1' );
		$ft->AddItem( $f );
		$f = new XFilter( 'CustomerID', '=', '0' );
		$ft->AddItem( $f );

		$st = new XSorting();
		$s  = new XSort( 'PeriodStart', 'ASC' );
		$st->AddItem( $s );

		$events = EDU()->api->GetEvent(
			$edutoken,
			$st->ToString(),
			$ft->ToString()
		);

		$event = $events[0];

		if ( ! $event ) {
			?>
            <script>history.go(-1);</script>
			<?php
			die();
		}

		include_once( "__loginHandler.php" );
		?>
        <div class="eduadmin loginForm">
            <form action="" method="post">
                <a href="../" class="backLink"><?php _e( "« Go back", 'eduadmin-booking' ); ?></a>
                <div class="title">
                    <img class="courseImage" src="<?php echo $selectedCourse->ImageUrl; ?>"/>
                    <h1 class="courseTitle"><?php echo $name; ?></h1>
					<?php if ( count( $events ) > 1 ) { ?>
                        <div class="dateSelectLabel"><?php _e( "Select the event you want to book", 'eduadmin-booking' ); ?></div>
                        <select name="eid" class="dateInfo">
							<?php
								foreach ( $events as $ev ) {
									?>
                                    <option value="<?php echo $ev->EventID; ?>"><?php
											echo wp_strip_all_tags( GetOldStartEndDisplayDate( $ev->PeriodStart, $ev->PeriodEnd ) ) . ", ";
											echo date( "H:i", strtotime( $ev->PeriodStart ) ); ?>
                                        - <?php echo date( "H:i", strtotime( $ev->PeriodEnd ) );
											$addresses = get_transient( 'eduadmin-location-' . $ev->LocationAddressID );
											if ( ! $addresses ) {
												$ft = new XFiltering();
												$f  = new XFilter( 'LocationAddressID', '=', $ev->LocationAddressID );
												$ft->AddItem( $f );
												$addresses = EDU()->api->GetLocationAddress( $edutoken, '', $ft->ToString() );
												set_transient( 'eduadmin-location-' . $ev->LocationAddressID, $addresses, DAY_IN_SECONDS );
											}

											foreach ( $addresses as $address ) {
												if ( $address->LocationAddressID === $ev->LocationAddressID ) {
													echo ", " . $ev->AddressName . ", " . $address->Address . ", " . $address->City;
													break;
												}
											}
										?></option>
									<?php
								}
							?>
                        </select>
						<?php
					} else {
						echo "<div class=\"dateInfo\">" . GetOldStartEndDisplayDate( $event->PeriodStart, $event->PeriodEnd ) . ", ";
						echo date( "H:i", strtotime( $event->PeriodStart ) ); ?> - <?php echo date( "H:i", strtotime( $event->PeriodEnd ) );
						$addresses = get_transient( 'eduadmin-location-' . $event->LocationAddressID );
						if ( ! $addresses ) {
							$ft = new XFiltering();
							$f  = new XFilter( 'LocationAddressID', '=', $event->LocationAddressID );
							$ft->AddItem( $f );
							$addresses = EDU()->api->GetLocationAddress( $edutoken, '', $ft->ToString() );
							set_transient( 'eduadmin-location-' . $event->LocationAddressID, $addresses, HOUR_IN_SECONDS );
						}

						foreach ( $addresses as $address ) {
							if ( $address->LocationAddressID === $event->LocationAddressID ) {
								echo ", " . $event->AddressName . ", " . $address->Address . ", " . $address->City;
								break;
							}
						}
						echo "</div>\n";
					}

						if ( ! isset( EDU()->session['checkEmail'] ) ) {
							include_once( "__checkEmail.php" );
						} else if ( isset( EDU()->session['checkEmail'] ) ) {
							if ( isset( EDU()->session['needsLogin'] ) && EDU()->session['needsLogin'] == true ) {
								include_once( "__loginForm.php" );
							} else {
								unset( EDU()->session['checkEmail'] );
								unset( EDU()->session['needsLogin'] );
								?>
                                <script type="text/javascript">(function () {
                                        location.reload(true);
                                    })();</script><?php
							}
						}
					?>

                </div>
            </form>
        </div>
		<?php
	}
	$out = ob_get_clean();
	return $out;