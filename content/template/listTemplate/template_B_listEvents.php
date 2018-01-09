<?php
	ob_start();

	$surl    = get_home_url();
	$cat     = get_option( 'eduadmin-rewriteBaseUrl' );
	$baseUrl = $surl . '/' . $cat;

	{
		$filtering = new XFiltering();
		$f         = new XFilter( 'ShowOnWeb', '=', 'true' );
		$filtering->AddItem( $f );

		if ( $categoryID > 0 ) {
			$f = new XFilter( 'CategoryID', '=', $categoryID );
			$filtering->AddItem( $f );
			$attributes['category'] = $categoryID;
		}

		if ( ! empty( $filterCourses ) ) {
			$f = new XFilter( 'ObjectID', 'IN', join( ',', $filterCourses ) );
			$filtering->AddItem( $f );
		}

		if ( isset( $_REQUEST['eduadmin-category'] ) && ! empty( $_REQUEST['eduadmin-category'] ) ) {
			$f = new XFilter( 'CategoryID', '=', intval( $_REQUEST['eduadmin-category'] ) );
			$filtering->AddItem( $f );
			$attributes['category'] = intval( $_REQUEST['eduadmin-category'] );
		}

		$edo = $eduapi->GetEducationObjectV2( $edutoken, '', $filtering->ToString(), false );
		#if(count($filterCourses) == 0)
		{
			#set_transient('eduadmin-listCourses', $edo, 6 * HOUR_IN_SECONDS);
		}
	}

	if ( isset( $_REQUEST['searchCourses'] ) && ! empty( $_REQUEST['searchCourses'] ) ) {
		$edo = array_filter( $edo, function( $object ) {
			$name       = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
			$descrField = get_option( 'eduadmin-layout-descriptionfield', 'CourseDescriptionShort' );
			$descr      = strip_tags( $object->{$descrField} );

			$nameMatch  = stripos( $name, sanitize_text_field( $_POST['searchCourses'] ) ) !== false;
			$descrMatch = stripos( $descr, sanitize_text_field( $_POST['searchCourses'] ) ) !== false;

			return ( $nameMatch || $descrMatch );
		} );
	}

	if ( isset( $_REQUEST['eduadmin-subject'] ) && ! empty( $_REQUEST['eduadmin-subject'] ) ) {
		$subjects = get_transient( 'eduadmin-subjects' );
		if ( ! $subjects ) {
			$sorting = new XSorting();
			$s       = new XSort( 'SubjectName', 'ASC' );
			$sorting->AddItem( $s );
			$subjects = $eduapi->GetEducationSubject( $edutoken, $sorting->ToString(), '' );
			set_transient( 'eduadmin-subjects', $subjects, DAY_IN_SECONDS );
		}

		$edo = array_filter( $edo, function( $object ) {
			$subjects = get_transient( 'eduadmin-subjects' );
			foreach ( $subjects as $subj ) {
				if ( $object->ObjectID == $subj->ObjectID && $subj->SubjectID == intval( $_REQUEST['eduadmin-subject'] ) ) {
					return true;
				}
			}

			return false;
		} );
	}

#$ede = get_transient('eduadmin-listEvents');
#if(!$ede || count($filterCourses) > 0)
	{
		$filtering = new XFiltering();
		$f         = new XFilter( 'ShowOnWeb', '=', 'true' );
		$filtering->AddItem( $f );

		if ( $categoryID > 0 ) {
			$f = new XFilter( 'CategoryID', '=', $categoryID );
			$filtering->AddItem( $f );
		}

		if ( isset( $_REQUEST['eduadmin-city'] ) && ! empty( $_REQUEST['eduadmin-city'] ) ) {
			$f = new XFilter( 'LocationID', '=', intval( $_REQUEST['eduadmin-city'] ) );
			$filtering->AddItem( $f );
			$attributes['city'] = intval( $_REQUEST['eduadmin-city'] );
		}

		if ( isset( $_REQUEST['eduadmin-category'] ) && ! empty( $_REQUEST['eduadmin-category'] ) ) {
			$f = new XFilter( 'CategoryID', '=', intval( $_REQUEST['eduadmin-category'] ) );
			$filtering->AddItem( $f );
			$attributes['category'] = intval( $_REQUEST['eduadmin-category'] );
		}

		if ( isset( $_REQUEST['eduadmin-subject'] ) && ! empty( $_REQUEST['eduadmin-subject'] ) ) {
			$f = new XFilter( 'SubjectID', '=', intval( $_REQUEST['eduadmin-subject'] ) );
			$filtering->AddItem( $f );
			$attributes['subject'] = intval( $_REQUEST['eduadmin-subject'] );
		}

		$fetchMonths = get_option( 'eduadmin-monthsToFetch', 6 );
		if ( ! is_numeric( $fetchMonths ) ) {
			$fetchMonths = 6;
		}

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

		$f = new XFilter( 'CustomerID', '=', '0' );
		$filtering->AddItem( $f );

		$f = new XFilter( 'ParentEventID', '=', '0' );
		$filtering->AddItem( $f );

		$sorting = new XSorting();
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

		$ede = $eduapi->GetEvent( $edutoken, $sorting->ToString(), $filtering->ToString() );
		#if(count($filterCourses) == 0)
		{
			#set_transient('eduadmin-listEvents', $ede, HOUR_IN_SECONDS);
		}
	}

	if ( isset( $_REQUEST['eduadmin-subject'] ) && ! empty( $_REQUEST['eduadmin-subject'] ) ) {
		$subjects = get_transient( 'eduadmin-subjects' );
		if ( ! $subjects ) {
			$sorting = new XSorting();
			$s       = new XSort( 'SubjectName', 'ASC' );
			$sorting->AddItem( $s );
			$subjects = $eduapi->GetEducationSubject( $edutoken, $sorting->ToString(), '' );
			set_transient( 'eduadmin-subjects', $subjects, DAY_IN_SECONDS );
		}

		$attributes['subject'] = intval( $_REQUEST['eduadmin-subject'] );

		$ede = array_filter( $ede, function( $object ) {
			$subjects = get_transient( 'eduadmin-subjects' );
			foreach ( $subjects as $subj ) {
				if ( $object->ObjectID == $subj->ObjectID && $subj->SubjectID == intval( $_REQUEST['eduadmin-subject'] ) ) {
					return true;
				}
			}

			return false;
		} );
	}

	if ( isset( $_REQUEST['eduadmin-level'] ) && ! empty( $_REQUEST['eduadmin-level'] ) ) {
		$attributes['courselevel'] = intval( $_REQUEST['eduadmin-level'] );
		$ede                       = array_filter( $ede, function( $object ) {
			$cl = get_transient( 'eduadmin-courseLevels' );
			foreach ( $cl as $subj ) {
				if ( $object->ObjectID == $subj->ObjectID && $subj->EducationLevelID == intval( $_REQUEST['eduadmin-level'] ) ) {
					return true;
				}
			}

			return false;
		} );
	}

	$occIds = array();
	$evIds  = array();

	foreach ( $ede as $e ) {
		$occIds[] = $e->OccationID;
		$evIds[]  = $e->EventID;
	}

	$ft = new XFiltering();
	$f  = new XFilter( 'EventID', 'IN', join( ",", $evIds ) );
	$ft->AddItem( $f );

	$eventDays = $eduapi->GetEventDate( $edutoken, '', $ft->ToString() );

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

	$pricenames = $eduapi->GetPriceName( $edutoken, $st->ToString(), $ft->ToString() );
	set_transient( 'eduadmin-publicpricenames', $pricenames, HOUR_IN_SECONDS );

	if ( ! empty( $pricenames ) ) {
		$ede = array_filter( $ede, function( $object ) {
			$pn = get_transient( 'eduadmin-publicpricenames' );
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

	if ( isset( $_REQUEST['searchCourses'] ) && ! empty( $_REQUEST['searchCourses'] ) ) {
		$ede = array_filter( $ede, function( $object ) {
			$name = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );

			$nameMatch = stripos( $name, sanitize_text_field( $_REQUEST['searchCourses'] ) ) !== false;

			return $nameMatch;
		} );
	}

	$showCourseDays  = get_option( 'eduadmin-showCourseDays', true );
	$showCourseTimes = get_option( 'eduadmin-showCourseTimes', true );
	$showWeekDays    = get_option( 'eduadmin-showWeekDays', false );
	$incVat          = $eduapi->GetAccountSetting( $edutoken, 'PriceIncVat' ) == "yes";

	$showEventPrice = get_option( 'eduadmin-showEventPrice', false );
	$currency       = get_option( 'eduadmin-currency', 'SEK' );
	$showEventVenue = get_option( 'eduadmin-showEventVenueName', false );

	$spotLeftOption = get_option( 'eduadmin-spotsLeft', 'exactNumbers' );
	$alwaysFewSpots = get_option( 'eduadmin-alwaysFewSpots', '3' );
	$spotSettings   = get_option( 'eduadmin-spotsSettings', "1-5\n5-10\n10+" );

?>
    <div class="eventListTable"
         data-eduwidget="listview-eventlist"
         data-template="B"
         data-subject="<?php echo @esc_attr( $attributes['subject'] ); ?>"
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
    ><?php

	$numberOfEvents = $attributes['numberofevents'];
	$currentEvents  = 0;

	foreach ( $ede as $object ) {
		if ( $numberOfEvents != null && $numberOfEvents > 0 && $currentEvents >= $numberOfEvents ) {
			break;
		}
		$name = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
		?>
        <div class="objectBlock brick">
			<?php if ( $showImages && ! empty( $object->ImageUrl ) ) { ?>
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

					$spotsLeft = ( $object->MaxParticipantNr - $object->TotalParticipantNr );
					echo /*isset($eventDates[$object->EventID]) ? GetLogicalDateGroups($eventDates[$object->EventID]) :*/
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
							( $showCourseDays ? sprintf( edu_n( '%1$d day', '%1$d days', $object->Days ), $object->Days ) . ( $showCourseTimes ? ', ' : '' ) : '' ) .
							( $showCourseTimes ? date( "H:i", strtotime( $object->StartTime ) ) .
							                     ' - ' .
							                     date( "H:i", strtotime( $object->EndTime ) ) : '' ) .
							"</div>";
					}
					if ( $showEventPrice ) {
						echo "<div class=\"priceInfo\">" . sprintf( edu__( 'From %1$s' ), convertToMoney( $object->Price, $currency ) ) . " " . edu__( $incVat ? "inc vat" : "ex vat" ) . "</div> ";
					}
					echo '<br />';
		            echo "<span class=\"spotsLeftInfo\">" . getSpotsLeft( $spotsLeft, $object->MaxParticipantNr, $spotLeftOption, $spotSettings, $alwaysFewSpots ) . "</span>";
				?></div>
            <div class="objectBook">
				<?php if ( $showReadMoreBtn ) : ?>
                    <a class="readMoreButton"
                       href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString( "&" ); ?>"><?php edu_e( "Read more" ); ?></a>
				<?php endif; ?>
            </div>
        </div>
		<?php
		$currentEvents ++;
	}
?>
    </div><?php
	$out = ob_get_clean();
	return $out;