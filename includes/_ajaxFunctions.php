<?php
	defined( 'ABSPATH' ) or die( 'This plugin must be run within the scope of WordPress.' );

	function edu_listview_courselist() {
		$objectIds = $_POST['objectIds'];

		$fetchMonths = $_POST['fetchmonths'];
		if ( ! is_numeric( $fetchMonths ) ) {
			$fetchMonths = 6;
		}

		$filtering = new XFiltering();
		$f         = new XFilter( 'ShowOnWeb', '=', 'true' );
		$filtering->AddItem( $f );

		$f = new XFilter( 'PeriodStart', '<=', date( "Y-m-d 23:59:59", strtotime( "now +" . $fetchMonths . " months" ) ) );
		$filtering->AddItem( $f );
		$f = new XFilter( 'PeriodEnd', '>=', date( "Y-m-d", strtotime( "now" ) ) );
		$filtering->AddItem( $f );
		$f = new XFilter( 'StatusID', '=', '1' );
		$filtering->AddItem( $f );

		$f = new XFilter( 'LastApplicationDate', '>=', date( "Y-m-d H:i:s" ) );
		$filtering->AddItem( $f );

		if ( ! empty( $objectIds ) ) {
			$f = new XFilter( 'ObjectID', 'IN', join( ',', $objectIds ) );
			$filtering->AddItem( $f );
		}

		$f = new XFilter( 'CustomerID', '=', '0' );
		$filtering->AddItem( $f );

		$sorting = new XSorting();
		$s       = new XSort( 'PeriodStart', 'ASC' );
		$sorting->AddItem( $s );

		$ede = EDU()->api->GetEvent( EDU()->get_token(), $sorting->ToString(), $filtering->ToString() );

		$occIds = array();
		$evIds  = array();

		foreach ( $ede as $e ) {
			$occIds[] = $e->OccationID;
			$evIds[]  = $e->EventID;
		}

		$ft = new XFiltering();
		$f  = new XFilter( 'EventID', 'IN', join( ",", $evIds ) );
		$ft->AddItem( $f );

		$eventDays = EDU()->api->GetEventDate( EDU()->get_token(), '', $ft->ToString() );

		$eventDates = array();
		foreach ( $eventDays as $ed ) {
			$eventDates[ $ed->EventID ][] = $ed;
		}

		$ft = new XFiltering();
		$f  = new XFilter( 'PublicPriceName', '=', 'true' );
		$ft->AddItem( $f );
		$f = new XFilter( 'OccationID', 'IN', join( ",", $occIds ) );
		$ft->AddItem( $f );
		$pricenames = EDU()->api->GetPriceName( EDU()->get_token(), '', $ft->ToString() );

		if ( ! empty( $pricenames ) ) {
			$ede = array_filter( $ede, function( $object ) use ( &$pricenames ) {
				$pn = $pricenames;
				foreach ( $pn as $subj ) {
					if ( $object->OccationID == $subj->OccationID ) {
						return true;
					}
				}

				return false;
			} );
		}

		$returnValue = array();
		foreach ( $ede as $event ) {
			if ( ! isset( $returnValue[ $event->ObjectID ] ) ) {
				$returnValue[ $event->ObjectID ] = sprintf( __( 'Next event %1$s', 'eduadmin-booking' ), date( "Y-m-d", strtotime( $event->PeriodStart ) ) ) . " " . $event->City;
			}
		}

		return rest_ensure_response( $returnValue );
	}

	function edu_api_listview_eventlist() {
		header( "Content-type: text/html; charset=UTF-8" );

		$sorting = new XSorting();
		$s       = new XSort( 'SubjectName', 'ASC' );
		$sorting->AddItem( $s );
		$subjects = EDU()->api->GetEducationSubject( EDU()->get_token(), $sorting->ToString(), '' );

		$filterCourses = array();

		if ( ! empty( $_POST['subject'] ) ) {
			foreach ( $subjects as $subject ) {
				if ( $subject->SubjectName == $_POST['subject'] ) {
					if ( ! in_array( $subject->ObjectID, $filterCourses ) ) {
						$filterCourses[] = $subject->ObjectID;
					}
				}
			}
		}

		$filtering = new XFiltering();
		$f         = new XFilter( 'ShowOnWeb', '=', 'true' );
		$filtering->AddItem( $f );

		if ( isset( $_POST['category'] ) ) {
			$f = new XFilter( 'CategoryID', '=', sanitize_text_field( $_POST['category'] ) );
			$filtering->AddItem( $f );
		}

		if ( isset( $_POST['subjectid'] ) ) {
			$f = new XFilter( 'SubjectID', '=', sanitize_text_field( $_POST['subjectid'] ) );
			$filtering->AddItem( $f );
		}

		if ( ! empty( $filterCourses ) ) {
			$f = new XFilter( 'ObjectID', 'IN', join( ',', $filterCourses ) );
			$filtering->AddItem( $f );
		}

		if ( isset( $_POST['city'] ) ) {
			$f = new XFilter( 'LocationID', '=', sanitize_text_field( $_POST['city'] ) );
			$filtering->AddItem( $f );
		}

		if ( isset( $_POST['courselevel'] ) ) {
			$f = new XFilter( 'EducationLevelID', '=', sanitize_text_field( $_POST['courselevel'] ) );
			$filtering->AddItem( $f );
		}

		$fetchMonths = $_POST['fetchmonths'];
		if ( ! is_numeric( $fetchMonths ) ) {
			$fetchMonths = 6;
		}

		$edo = EDU()->api->GetEducationObjectV2( EDU()->get_token(), '', $filtering->ToString(), false );

		if ( ! empty( $_POST['search'] ) ) {
			$edo = array_filter( $edo, function( $object ) use ( &$request ) {
				$name            = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
				$descrMatch      = stripos( $object->CourseDescription, sanitize_text_field( $_POST['search'] ) ) !== false;
				$shortDescrMatch = stripos( $object->CourseDescriptionShort, sanitize_text_field( $_POST['search'] ) ) !== false;
				$nameMatch       = stripos( $name, sanitize_text_field( $_POST['search'] ) ) !== false;

				return ( $nameMatch || $descrMatch || $shortDescrMatch );
			} );
		}

		if ( isset( $_POST['subjectid'] ) && ! empty( $_POST['subjectid'] ) ) {
			$subjects = get_transient( 'eduadmin-subjects' );
			if ( ! $subjects ) {
				$sorting = new XSorting();
				$s       = new XSort( 'SubjectName', 'ASC' );
				$sorting->AddItem( $s );
				$subjects = EDU()->api->GetEducationSubject( EDU()->get_token(), $sorting->ToString(), '' );
				set_transient( 'eduadmin-subjects', $subjects, DAY_IN_SECONDS );
			}

			$edo = array_filter( $edo, function( $object ) {
				$subjects = get_transient( 'eduadmin-subjects' );
				foreach ( $subjects as $subj ) {
					if ( $object->ObjectID == $subj->ObjectID && $subj->SubjectID == intval( $_POST['subjectid'] ) ) {
						return true;
					}
				}

				return false;
			} );
		}

		$filtering = new XFiltering();
		$f         = new XFilter( 'ShowOnWeb', '=', 'true' );
		$filtering->AddItem( $f );

		$f = new XFilter( 'PeriodStart', '<=', date( "Y-m-d 23:59:59", strtotime( "now +" . $fetchMonths . " months" ) ) );
		$filtering->AddItem( $f );
		$f = new XFilter( 'PeriodEnd', '>=', date( "Y-m-d H:i:s", strtotime( "now" ) ) );
		$filtering->AddItem( $f );
		$f = new XFilter( 'StatusID', '=', '1' );
		$filtering->AddItem( $f );

		$f = new XFilter( 'LastApplicationDate', '>=', date( "Y-m-d H:i:s" ) );
		$filtering->AddItem( $f );

		if ( ! empty( $filterCourses ) ) {
			$f = new XFilter( 'ObjectID', 'IN', join( ',', $filterCourses ) );
			$filtering->AddItem( $f );
		}

		if ( isset( $_POST['city'] ) ) {
			$f = new XFilter( 'LocationID', '=', sanitize_text_field( $_POST['city'] ) );
			$filtering->AddItem( $f );
		}

		if ( isset( $_POST['subjectid'] ) && ! empty( $_POST['subjectid'] ) ) {
			$f = new XFilter( 'SubjectID', '=', sanitize_text_field( $_POST['subjectid'] ) );
			$filtering->AddItem( $f );
		}

		if ( isset( $_POST['category'] ) ) {
			$f = new XFilter( 'CategoryID', '=', sanitize_text_field( $_POST['category'] ) );
			$filtering->AddItem( $f );
		}

		$f = new XFilter( 'CustomerID', '=', '0' );
		$filtering->AddItem( $f );

		$f = new XFilter( 'ParentEventID', '=', '0' );
		$filtering->AddItem( $f );

		$sorting = new XSorting();

		$customOrderBy      = null;
		$customOrderByOrder = null;
		if ( ! empty( $request['orderby'] ) ) {
			$customOrderBy = $request['orderby'];
		}

		if ( ! empty( $request['order'] ) ) {
			$customOrderByOrder = $request['order'];
		}

		if ( $customOrderBy != null ) {
			$orderby   = explode( ' ', $customOrderBy );
			$sortorder = explode( ' ', $customOrderByOrder );
			foreach ( $orderby as $od => $v ) {
				if ( isset( $sortorder[ $od ] ) ) {
					$or = $sortorder[ $od ];
				} else {
					$or = "ASC";
				}

				$s = new XSort( $v, $or );
				$sorting->AddItem( $s );
			}
		} else {
			$s = new XSort( 'PeriodStart', 'ASC' );
			$sorting->AddItem( $s );
		}

		$ede = EDU()->api->GetEvent( EDU()->get_token(), $sorting->ToString(), $filtering->ToString() );

		$ede = array_filter( $ede, function( $object ) use ( &$edo ) {
			$pn = $edo;
			foreach ( $pn as $subj ) {
				if ( $object->ObjectID == $subj->ObjectID ) {
					return true;
				}
			}

			return false;
		} );

		$occIds = array();
		$evIds  = array();

		foreach ( $ede as $e ) {
			$occIds[] = $e->OccationID;
			$evIds[]  = $e->EventID;
		}

		$ft = new XFiltering();
		$f  = new XFilter( 'EventID', 'IN', join( ",", $evIds ) );
		$ft->AddItem( $f );

		$eventDays = EDU()->api->GetEventDate( EDU()->get_token(), '', $ft->ToString() );

		$eventDates = array();
		foreach ( $eventDays as $ed ) {
			$eventDates[ $ed->EventID ][] = $ed;
		}

		$ft = new XFiltering();
		$f  = new XFilter( 'PublicPriceName', '=', 'true' );
		$ft->AddItem( $f );
		$f = new XFilter( 'OccationID', 'IN', join( ",", $occIds ) );
		$ft->AddItem( $f );
		$pricenames = EDU()->api->GetPriceName( EDU()->get_token(), '', $ft->ToString() );

		if ( ! empty( $pricenames ) ) {
			$ede = array_filter( $ede, function( $object ) use ( &$pricenames ) {
				$pn = $pricenames;
				foreach ( $pn as $subj ) {
					if ( $object->OccationID == $subj->OccationID ) {
						return true;
					}
				}

				return false;
			} );
		}

		foreach ( $ede as $object ) {
			foreach ( $edo as $course ) {
				$id = $course->ObjectID;
				if ( $id == $object->ObjectID ) {
					$object->Days       = $course->Days;
					$object->StartTime  = $course->StartTime;
					$object->EndTime    = $course->EndTime;
					$object->CategoryID = $course->CategoryID;
					$object->PublicName = $course->PublicName;
					break;
				}
			}

			foreach ( $pricenames as $pn ) {
				$id = $pn->OccationID;
				if ( $id == $object->OccationID ) {
					$object->Price        = $pn->Price;
					$object->PriceNameVat = $pn->PriceNameVat;
					break;
				}
			}
		}

		if ( "A" == $_POST['template'] ) {
			edu_api_listview_eventlist_template_A( $ede, $eventDates, $_POST );
		} else if ( "B" == $_POST['template'] ) {
			edu_api_listview_eventlist_template_B( $ede, $eventDates, $_POST );
		}
		die();
	}

	function edu_api_listview_eventlist_template_A( $data, $eventDates, $request ) {
		$spotLeftOption = $request['spotsleft'];
		$alwaysFewSpots = $request['fewspots'];
		$spotSettings   = $request['spotsettings'];
		$showImages     = $request['showimages'];

		$showCourseDays  = $request['showcoursedays'];
		$showCourseTimes = $request['showcoursetimes'];
		$showWeekDays    = $request['showweekdays'];

		$showVenue = $request['showvenue'];

		$incVat = EDU()->api->GetAccountSetting( EDU()->get_token(), 'PriceIncVat' ) == "yes";

		$surl           = $request['baseUrl'];
		$cat            = $request['courseFolder'];
		$numberOfEvents = $request['numberofevents'];
		$baseUrl        = $surl . '/' . $cat;

		$removeItems = array(
			'eid',
			'showweekdays',
			'phrases',
			'module',
			'baseUrl',
			'courseFolder',
			'showmore',
			'spotsleft',
			'objectid',
			'groupbycity',
			'fewspots',
			'spotsettings',
			'numberofevents',
			'showvenue',
			'order',
			'orderby',
		);

		$currentEvents = 0;

		foreach ( $data as $object ) {
			if ( $numberOfEvents != null && $numberOfEvents > 0 && $currentEvents >= $numberOfEvents ) {
				break;
			}
			$spotsLeft = ( $object->MaxParticipantNr - $object->TotalParticipantNr );
			$name      = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
			?>
            <div class="objectItem <?php echo edu_get_percent_from_values( $spotsLeft, $object->MaxParticipantNr ); ?>">
				<?php if ( $showImages && ! empty( $object->ImageUrl ) ) { ?>
                    <div class="objectImage"
                         onclick="location.href = '<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&", $removeItems ); ?>';"
                         style="background-image: url('<?php echo $object->ImageUrl; ?>');"></div>
				<?php } ?>
                <div class="objectInfoHolder">
                    <div class="objectName">
                        <a href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&", $removeItems ); ?>"><?php
								echo htmlentities( $name );
							?></a>
                    </div>
                    <div class="objectDescription"><?php
							echo GetOldStartEndDisplayDate( $object->PeriodStart, $object->PeriodEnd, true, $showWeekDays );

							if ( ! empty( $object->City ) ) {
								echo " <span class=\"cityInfo\">";
								echo $object->City;
								if ( $showVenue && ! empty( $object->AddressName ) ) {
									echo "<span class=\"venueInfo\">, " . $object->AddressName . "</span>";
								}
								echo "</span>";
							}

							if ( isset( $object->Days ) && $object->Days > 0 ) {
								echo
									"<div class=\"dayInfo\">" .
									( $showCourseDays ? sprintf( _n( '%1$d day', '%1$d days', $object->Days, 'eduadmin-booking' ), $object->Days ) .
									                    ( $showCourseTimes && $object->StartTime != '' && $object->EndTime != '' && ! isset( $eventDates[ $object->EventID ] ) ? ', ' : '' ) : '' ) .
									( $showCourseTimes && $object->StartTime != '' && $object->EndTime != '' && ! isset( $eventDates[ $object->EventID ] ) ? date( "H:i", strtotime( $object->StartTime ) ) .
									                                                                                                                         ' - ' .
									                                                                                                                         date( "H:i", strtotime( $object->EndTime ) ) : '' ) .
									"</div>";
							}

							if ( $request['showcourseprices'] && isset( $object->Price ) ) {
								echo "<div class=\"priceInfo\">" . sprintf( __( 'From %1$s', 'eduadmin-booking' ), convertToMoney( $object->Price, $request['currency'] ) ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ) . "</div> ";
							}
							echo "<span class=\"spotsLeftInfo\">" . getSpotsLeft( $spotsLeft, $object->MaxParticipantNr, $spotLeftOption, $spotSettings, $alwaysFewSpots ) . "</span>";

						?></div>

                </div>
                <div class="objectBook">
	                <?php
		                if ( $spotsLeft > 0 || 0 == $object->MaxParticipantNr ) {
			                ?>
                            <a class="bookButton cta-btn"
                               href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/book/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&", $removeItems ); ?>"><?php _e( "Book", 'eduadmin-booking' ); ?></a>
			                <?php
		                } else {
			                ?>
                            <i class="fullBooked"><?php _e( "Full", 'eduadmin-booking' ); ?></i>
		                <?php } ?>
                    <a class="readMoreButton"
                       href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&", $removeItems ); ?>"><?php _e( "Read more", 'eduadmin-booking' ); ?></a><br/>
                </div>
            </div>
			<?php
			$currentEvents++;
		}
	}

	function edu_api_listview_eventlist_template_B( $data, $eventDates, $request ) {
		$spotLeftOption = $request['spotsleft'];
		$alwaysFewSpots = $request['fewspots'];
		$spotSettings   = $request['spotsettings'];
		$showImages     = $request['showimages'];

		$showCourseDays  = $request['showcoursedays'];
		$showCourseTimes = $request['showcoursetimes'];
		$showWeekDays    = $request['showweekdays'];

		$showVenue = $request['showvenue'];

		$incVat = EDU()->api->GetAccountSetting( EDU()->get_token(), 'PriceIncVat' ) == "yes";

		$surl           = $request['baseUrl'];
		$cat            = $request['courseFolder'];
		$numberOfEvents = $request['numberofevents'];
		$baseUrl        = $surl . '/' . $cat;

		$removeItems = array(
			'eid',
			'phrases',
			'module',
			'baseUrl',
			'courseFolder',
			'showmore',
			'spotsleft',
			'objectid',
			'groupbycity',
			'fewspots',
			'spotsettings',
			'numberofevents',
			'showvenue',
			'order',
			'orderby',
		);

		$currentEvents = 0;

		foreach ( $data as $object ) {
			if ( $numberOfEvents != null && $numberOfEvents > 0 && $currentEvents >= $numberOfEvents ) {
				break;
			}
			$name      = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
			$spotsLeft = ( $object->MaxParticipantNr - $object->TotalParticipantNr );
			?>
            <div class="objectBlock brick <?php echo edu_get_percent_from_values( $spotsLeft, $object->MaxParticipantNr ); ?>">
				<?php if ( $showImages && ! empty( $object->ImageUrl ) ) { ?>
                    <div class="objectImage"
                         onclick="location.href = '<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&", $removeItems ); ?>';"
                         style="background-image: url('<?php echo $object->ImageUrl; ?>');"></div>
				<?php } ?>
                <div class="objectName">
                    <a href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&", $removeItems ); ?>"><?php
							echo htmlentities( $name );
						?></a>
                </div>
                <div class="objectDescription"><?php
						echo GetOldStartEndDisplayDate( $object->PeriodStart, $object->PeriodEnd, true, $showWeekDays );

						if ( ! empty( $object->City ) ) {
							echo " <span class=\"cityInfo\">";
							echo $object->City;
							if ( $showVenue && ! empty( $object->AddressName ) ) {
								echo ", " . $object->AddressName;
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

						if ( $request['showcourseprices'] && isset( $object->Price ) ) {
							echo "<div class=\"priceInfo\">" . sprintf( __( 'From %1$s', 'eduadmin-booking' ), convertToMoney( $object->Price, $request['currency'] ) ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ) . "</div> ";
						}
		                echo '<div class="spotsLeft"></div>';
		                echo '<span class="spotsLeftInfo">' . getSpotsLeft( $spotsLeft, $object->MaxParticipantNr, $spotLeftOption, $spotSettings, $alwaysFewSpots ) . '</span>';
					?></div>
                <div class="objectBook">
                    <a class="readMoreButton cta-btn"
                       href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&", $removeItems ); ?>"><?php _e( "Read more", 'eduadmin-booking' ); ?></a>
                </div>
            </div>
			<?php
			$currentEvents++;
		}
	}

	function edu_api_eventlist() {
		header( "Content-type: text/html; charset=UTF-8" );
		$retStr = '';

		$objectId = $_POST['objectid'];

		$filtering = new XFiltering();
		$f         = new XFilter( 'ShowOnWeb', '=', 'true' );
		$filtering->AddItem( $f );
		$f = new XFilter( 'ObjectID', '=', $objectId );
		$filtering->AddItem( $f );

		$edo            = EDU()->api->GetEducationObject( EDU()->get_token(), '', $filtering->ToString() );
		$selectedCourse = false;
		foreach ( $edo as $object ) {
			$id = $object->ObjectID;
			if ( $id == $objectId ) {
				$selectedCourse = $object;
				break;
			}
		}

		$fetchMonths = $_POST['fetchmonths'];
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
		$f = new XFilter( 'ObjectID', '=', $objectId );
		$ft->AddItem( $f );
		$f = new XFilter( 'LastApplicationDate', '>=', date( "Y-m-d H:i:s" ) );
		$ft->AddItem( $f );

		$f = new XFilter( 'CustomerID', '=', '0' );
		$ft->AddItem( $f );

		$f = new XFilter( 'ParentEventID', '=', '0' );
		$ft->AddItem( $f );

		if ( ! empty( $_POST['city'] ) ) {
			$f = new XFilter( 'City', '=', $_POST['city'] );
			$ft->AddItem( $f );
		}

		$st               = new XSorting();
		$groupByCity      = $_POST['groupbycity'];
		$groupByCityClass = "";
		if ( $groupByCity ) {
			$s = new XSort( 'City', 'ASC' );
			$st->AddItem( $s );
			$groupByCityClass = " noCity";
		}

		$customOrderBy      = null;
		$customOrderByOrder = null;
		if ( ! empty( $_POST['orderby'] ) ) {
			$customOrderBy = $_POST['orderby'];
		}

		if ( ! empty( $_POST['order'] ) ) {
			$customOrderByOrder = $_POST['order'];
		}

		if ( $customOrderBy != null ) {
			$orderby   = explode( ' ', $customOrderBy );
			$sortorder = explode( ' ', $customOrderByOrder );
			foreach ( $orderby as $od => $v ) {
				if ( isset( $sortorder[ $od ] ) ) {
					$or = $sortorder[ $od ];
				} else {
					$or = "ASC";
				}

				$s = new XSort( $v, $or );
				$st->AddItem( $s );
			}
		} else {
			$s = new XSort( 'PeriodStart', 'ASC' );
			$st->AddItem( $s );
		}

		$events = EDU()->api->GetEvent(
			EDU()->get_token(),
			$st->ToString(),
			$ft->ToString()
		);

		$occIds   = array();
		$occIds[] = -1;

		$eventIds   = array();
		$eventIds[] = -1;

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
		$f = new XFilter( 'OccationID', 'IN', join( ",", $occIds ) );
		$ft->AddItem( $f );

		$st = new XSorting();
		$s  = new XSort( 'Price', 'ASC' );
		$st->AddItem( $s );

		$pricenames = EDU()->api->GetPriceName( EDU()->get_token(), $st->ToString(), $ft->ToString() );

		if ( ! empty( $pricenames ) ) {
			$events = array_filter( $events, function( $object ) use ( &$pricenames ) {
				$pn = $pricenames;
				foreach ( $pn as $subj ) {
					if ( $object->OccationID == $subj->OccationID ) {
						return true;
					}
				}

				return false;
			} );
		}

		$surl = $_POST['baseUrl'];
		$cat  = $_POST['courseFolder'];

		$lastCity = "";

		$showMore         = isset( $_POST['showmore'] ) && ! empty( $_POST['showmore'] ) ? $_POST['showmore'] : -1;
		$spotLeftOption   = $_POST['spotsleft'];
		$alwaysFewSpots   = $_POST['fewspots'];
		$showVenue        = $_POST['showvenue'];
		$spotSettings     = $_POST['spotsettings'];
		$showEventInquiry = isset( $_POST['eventinquiry'] ) && $_POST['eventinquiry'] == "true";
		$baseUrl          = $surl . '/' . $cat;
		$name             = ( ! empty( $selectedCourse->PublicName ) ? $selectedCourse->PublicName : $selectedCourse->ObjectName );
		$retStr           .= '<div class="eduadmin"><div class="event-table eventDays">';
		$i                = 0;
		$hasHiddenDates   = false;
		if ( ! empty( $pricenames ) ) {
			foreach ( $events as $ev ) {
				$spotsLeft = ( $ev->MaxParticipantNr - $ev->TotalParticipantNr );

				if ( isset( $request['eid'] ) ) {
					if ( $ev->EventID != $_POST['eid'] ) {
						continue;
					}
				}

				if ( $groupByCity && $lastCity != $ev->City ) {
					$i = 0;
					if ( $hasHiddenDates ) {
						$retStr .= "<div class=\"eventShowMore\"><a href=\"javascript://\" onclick=\"eduDetailView.ShowAllEvents('eduev-" . $lastCity . "', this);\">" . __( "Show all events", 'eduadmin-booking' ) . "</a></div>";
					}
					$hasHiddenDates = false;
					$retStr         .= '<div class="eventSeparator">' . $ev->City . '</div>';
				}

				if ( $showMore > 0 && $i >= $showMore ) {
					$hasHiddenDates = true;
				}

				$removeItems = array(
					'eid',
					'phrases',
					'module',
					'baseUrl',
					'courseFolder',
					'showmore',
					'spotsleft',
					'objectid',
					'groupbycity',
					'fewspots',
					'spotsettings',
				);

				$retStr   .= '<div data-groupid="eduev' . ( $groupByCity ? "-" . $ev->City : "" ) . '" class="eventItem' . ( $showMore > 0 && $i >= $showMore ? " showMoreHidden" : "" ) . '">';
				$retStr   .= '
				<div class="eventDate' . $groupByCityClass . '">
					' . ( isset( $eventDates[ $ev->EventID ] ) ? GetLogicalDateGroups( $eventDates[ $ev->EventID ] ) : GetOldStartEndDisplayDate( $ev->PeriodStart, $ev->PeriodEnd ) ) . '
					' . ( ! isset( $eventDates[ $ev->EventID ] ) || count( $eventDates[ $ev->EventID ] ) == 1 ? '<span class="eventTime">, ' . date( "H:i", strtotime( $ev->PeriodStart ) ) . ' - ' . date( "H:i", strtotime( $ev->PeriodEnd ) ) . '</span>' : '' ) . '
				</div>
				' . ( ! $groupByCity ?
						'<div class="eventCity">
					' . $ev->City .
						( $showVenue && ! empty( $ev->AddressName ) ? '<span class="venueInfo">, ' . $ev->AddressName . '</span>' : '' ) .
						'
				</div>' : '' ) .
				             '<div class="eventStatus' . $groupByCityClass . '">
				<span class="spotsLeftInfo">' .
				             getSpotsLeft( $spotsLeft, $ev->MaxParticipantNr, $spotLeftOption, $spotSettings, $alwaysFewSpots )
				             . '</span>
				</div>
				<div class="eventBook' . $groupByCityClass . '">
				' .
				             ( $ev->MaxParticipantNr == 0 || $spotsLeft > 0 ?
					             '<a class="bookButton book-link cta-btn" href="' . $baseUrl . '/' . makeSlugs( $name ) . '__' . $objectId . '/book/?eid=' . $ev->EventID . edu_getQueryString( "&", $removeItems ) . '">' . __( "Book", 'eduadmin-booking' ) . '</a>'
					             :
					             ( $showEventInquiry ?
						             '<a class="inquiry-link" href="' . $baseUrl . '/' . makeSlugs( $name ) . '__' . $objectId . '/book/interest/?eid=' . $ev->EventID . edu_getQueryString( "&", $removeItems ) . '">' . __( "Inquiry", 'eduadmin-booking' ) . '</a> '
						             :
						             ''
					             ) .
					             '<i class="fullBooked">' . __( "Full", 'eduadmin-booking' ) . '</i>'
				             ) . '
				</div>';
				$retStr   .= '</div><!-- /eventitem -->';
				$lastCity = $ev->City;
				$i++;
			}
		}
		if ( empty( $pricenames ) || empty( $events ) ) {
			$retStr .= '<div class="noDatesAvailable"><i>' . __( "No available dates for the selected course", 'eduadmin-booking' ) . '</i></div>';
		}
		if ( $hasHiddenDates ) {
			$retStr .= "<div class=\"eventShowMore\"><a href=\"javascript://\" onclick=\"eduDetailView.ShowAllEvents('eduev" . ( $groupByCity ? "-" . $ev->City : "" ) . "', this);\">" . __( "Show all events", 'eduadmin-booking' ) . "</a></div>";
		}
		$retStr .= '</div></div>';

		echo $retStr;
		die();
	}

	function edu_api_loginwidget() {
		header( "Content-type: text/html; charset=UTF-8" );
		$surl = $_POST['baseUrl'];
		$cat  = $_POST['courseFolder'];

		$loginText  = $_POST['logintext'];
		$logoutText = $_POST['logouttext'];
		$guestText  = $_POST['guesttext'];

		$baseUrl = $surl . '/' . $cat;
		if ( isset( EDU()->session['eduadmin-loginUser'] ) ) {
			$user    = EDU()->session['eduadmin-loginUser'];
			$contact = $user->Contact;
		}

		if ( isset( EDU()->session['eduadmin-loginUser'] ) &&
		     ! empty( EDU()->session['eduadmin-loginUser'] ) &&
		     isset( $contact ) &&
		     isset( $contact->CustomerContactID ) &&
		     $contact->CustomerContactID != 0
		) {
			echo
				"<div class=\"eduadminLogin\"><a href=\"" . $baseUrl . "/profile/myprofile" . edu_getQueryString( "?", array(
					'eid',
					'module',
				) ) . "\" class=\"eduadminMyProfileLink\">" .
				$contact->ContactName .
				"</a> - <a href=\"" . $baseUrl . "/profile/logout" . edu_getQueryString( "?", array(
					'eid',
					'module',
				) ) . "\" class=\"eduadminLogoutButton\">" .
				( ! empty( $logoutText ) ? $logoutText : __( 'Log out', 'eduadmin-booking' ) ) .
				"</a>" .
				"</div>";
		} else {
			echo
				"<div class=\"eduadminLogin\"><i>" .
				( ! empty( $guestText ) ? $guestText : __( 'Guest', 'eduadmin-booking' ) ) .
				"</i> - " .
				"<a href=\"" . $baseUrl . "/profile/login" . edu_getQueryString( "?", array(
					'eid',
					'module',
				) ) . "\" class=\"eduadminLoginButton\">" .
				( ! empty( $loginText ) ? $loginText : __( 'Log in', 'eduadmin-booking' ) ) .
				"</a>" .
				"</div>";
		}
		die();
	}

	function edu_api_check_coupon_code() {
		$objectID   = $_POST['objectId'];
		$categoryID = $_POST['categoryId'];
		$code       = $_POST['code'];
		$vcode      = EDU()->api->CheckCouponCode( EDU()->get_token(), $objectID, $categoryID, $code );

		return rest_ensure_response( $vcode );
	}