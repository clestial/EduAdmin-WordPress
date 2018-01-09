<?php
	ob_start();

	$surl    = get_home_url();
	$cat     = get_option( 'eduadmin-rewriteBaseUrl' );
	$baseUrl = $surl . '/' . $cat;

	$filtering = new XFiltering();
	$f         = new XFilter( 'ShowOnWeb', '=', 'true' );
	$filtering->AddItem( $f );

	$showEventsWithEventsOnly    = $attributes['onlyevents'];
	$showEventsWithoutEventsOnly = $attributes['onlyempty'];

	if ( $categoryID > 0 ) {
		$f = new XFilter( 'CategoryID', '=', $categoryID );
		$filtering->AddItem( $f );
		$attributes['category'] = $categoryID;
	}

	if ( isset( $_REQUEST['eduadmin-category'] ) && ! empty( $_REQUEST['eduadmin-category'] ) ) {
		$f = new XFilter( 'CategoryID', '=', intval( $_REQUEST['eduadmin-category'] ) );
		$filtering->AddItem( $f );
		$attributes['category'] = intval( $_REQUEST['eduadmin-category'] );
	}

	if ( isset( $_REQUEST['eduadmin-city'] ) && ! empty( $_REQUEST['eduadmin-city'] ) ) {
		$f = new XFilter( 'LocationID', '=', intval( $_REQUEST['eduadmin-city'] ) );
		$filtering->AddItem( $f );
		$attributes['city'] = intval( $_REQUEST['eduadmin-city'] );
	}

	if ( isset( $_REQUEST['eduadmin-subject'] ) && ! empty( $_REQUEST['eduadmin-subject'] ) ) {
		$f = new XFilter( 'SubjectID', '=', intval( $_REQUEST['eduadmin-subject'] ) );
		$filtering->AddItem( $f );
		$attributes['subject'] = intval( $_REQUEST['eduadmin-subject'] );
	}

	if ( ! empty( $filterCourses ) ) {
		$f = new XFilter( 'ObjectID', 'IN', join( ',', $filterCourses ) );
		$filtering->AddItem( $f );
	}

	$sortOrder = get_option( 'eduadmin-listSortOrder', 'SortIndex' );

	$sort = new XSorting();

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
			$sort->AddItem( $s );
		}
	}

	$s = new XSort( $sortOrder, 'ASC' );
	$sort->AddItem( $s );

	$edo = $eduapi->GetEducationObject( $edutoken, $sort->ToString(), $filtering->ToString() );

	if ( isset( $_REQUEST['searchCourses'] ) && ! empty( $_REQUEST['searchCourses'] ) ) {
		$edo = array_filter( $edo, function( $object ) {
			$name       = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
			$descrField = get_option( 'eduadmin-layout-descriptionfield', 'CourseDescriptionShort' );
			$descr      = strip_tags( $object->{$descrField} );

			$nameMatch  = stripos( $name, sanitize_text_field( $_REQUEST['searchCourses'] ) ) !== false;
			$descrMatch = stripos( $descr, sanitize_text_field( $_REQUEST['searchCourses'] ) ) !== false;

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

	if ( isset( $_REQUEST['eduadmin-level'] ) && ! empty( $_REQUEST['eduadmin-level'] ) ) {
		$attributes['courselevel'] = $_REQUEST['eduadmin-level'];
		$edo                       = array_filter( $edo, function( $object ) {
			$cl = get_transient( 'eduadmin-courseLevels' );
			foreach ( $cl as $subj ) {
				if ( $object->ObjectID == $subj->ObjectID && $subj->EducationLevelID == intval( $_REQUEST['eduadmin-level'] ) ) {
					return true;
				}
			}

			return false;
		} );
	}

	$objIds = array();

	foreach ( $edo as $obj ) {
		$objIds[] = $obj->ObjectID;
	}

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
	}

	if ( isset( $_REQUEST['eduadmin-category'] ) && ! empty( $_REQUEST['eduadmin-category'] ) ) {
		$f = new XFilter( 'CategoryID', '=', intval( $_REQUEST['eduadmin-category'] ) );
		$filtering->AddItem( $f );
		$attributes['category'] = $_REQUEST['eduadmin-category'];
	}

	if ( isset( $_REQUEST['eduadmin-subject'] ) && ! empty( $_REQUEST['eduadmin-subject'] ) ) {
		$f = new XFilter( 'SubjectID', '=', intval( $_REQUEST['eduadmin-subject'] ) );
		$filtering->AddItem( $f );
	}

	$fetchMonths = get_option( 'eduadmin-monthsToFetch', 6 );
	if ( ! is_numeric( $fetchMonths ) ) {
		$fetchMonths = 6;
	}

	$f = new XFilter( 'CustomerID', '=', '0' );
	$filtering->AddItem( $f );

	$f = new XFilter( 'PeriodStart', '<=', date( "Y-m-d 23:59:59", strtotime( "now +" . $fetchMonths . " months" ) ) );
	$filtering->AddItem( $f );
	$f = new XFilter( 'PeriodEnd', '>=', date( "Y-m-d H:i:s", strtotime( "now" ) ) );
	$filtering->AddItem( $f );
	$f = new XFilter( 'StatusID', '=', '1' );
	$filtering->AddItem( $f );

	$f = new XFilter( 'LastApplicationDate', '>', date( "Y-m-d H:i:s" ) );
	$filtering->AddItem( $f );

	if ( ! empty( $objIds ) ) {
		$f = new XFilter( 'ObjectID', 'IN', join( ',', $objIds ) );
		$filtering->AddItem( $f );
	}

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

	foreach ( $ede as $e ) {
		$occIds[] = $e->OccationID;
	}

	$ft = new XFiltering();
	$f  = new XFilter( 'PublicPriceName', '=', 'true' );
	$ft->AddItem( $f );
	$f = new XFilter( 'OccationID', 'IN', join( ",", $occIds ) );
	$ft->AddItem( $f );
	$pricenames = $eduapi->GetPriceName( $edutoken, '', $ft->ToString() );
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
	}

	if ( isset( $_REQUEST['searchCourses'] ) && ! empty( $_REQUEST['searchCourses'] ) ) {
		$ede = array_filter( $ede, function( $object ) {
			$name = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );

			$nameMatch = stripos( $name, sanitize_text_field( $_REQUEST['searchCourses'] ) ) !== false;

			return $nameMatch;
		} );
	}

	$descrField = get_option( 'eduadmin-layout-descriptionfield', 'CourseDescriptionShort' );
	if ( stripos( $descrField, "attr_" ) !== false ) {
		$ft = new XFiltering();
		$f  = new XFilter( "AttributeID", "=", intval( substr( $descrField, 5 ) ) );
		$ft->AddItem( $f );
		$objectAttributes = $eduapi->GetObjectAttribute( $edutoken, '', $ft->ToString() );
	}

	$showNextEventDate   = get_option( 'eduadmin-showNextEventDate', false );
	$showCourseLocations = get_option( 'eduadmin-showCourseLocations', false );
	$showEventPrice      = get_option( 'eduadmin-showEventPrice', false );
	$incVat              = $eduapi->GetAccountSetting( $edutoken, 'PriceIncVat' ) == "yes";

	$showCourseDays  = get_option( 'eduadmin-showCourseDays', true );
	$showCourseTimes = get_option( 'eduadmin-showCourseTimes', true );
	$showWeekDays    = get_option( 'eduadmin-showWeekDays', false );

	$showDescr      = get_option( 'eduadmin-showCourseDescription', true );
	$showEventVenue = get_option( 'eduadmin-showEventVenueName', false );

?>
<div style="display: none;" class="eduadmin-courselistoptions"
     data-subject="<?php echo @esc_attr( $attributes['subject'] ); ?>"
     data-category="<?php echo @esc_attr( $attributes['category'] ); ?>"
     data-city="<?php echo @esc_attr( $attributes['city'] ); ?>"
     data-courselevel="<?php echo @esc_attr( $attributes['courselevel'] ); ?>"
     data-spotsleft="<?php echo @get_option( 'eduadmin-spotsLeft', 'exactNumbers' ); ?>"
     data-spotsettings="<?php echo @get_option( 'eduadmin-spotsSettings', "1-5\n5-10\n10+" ); ?>"
     data-fewspots="<?php echo @get_option( 'eduadmin-alwaysFewSpots', "3" ); ?>"
     data-showcoursedays="<?php echo @esc_attr( $showCourseDays ); ?>"
     data-showcoursetimes="<?php echo @esc_attr( $showCourseTimes ); ?>"
     data-showweekdays="<?php echo @esc_attr( $showWeekDays ); ?>"
     data-showcourseprices="<?php echo @esc_attr( $showEventPrice ); ?>"
     data-currency="<?php echo @esc_attr( $currency ); ?>"
     data-search="<?php echo @esc_attr( sanitize_text_field( $_REQUEST['searchCourses'] ) ); ?>"
     data-showimages="<?php echo @esc_attr( $showImages ); ?>"
     data-numberofevents="<?php echo @esc_attr( $attributes['numberofevents'] ); ?>"
     data-fetchmonths="<?php echo @esc_attr( $fetchMonths ); ?>"
     data-showvenue="<?php echo @esc_attr( $showEventVenue ); ?>"
></div><?php

	foreach ( $edo as $object ) {
		$name   = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
		$events = array_filter( $ede, function( $ev ) use ( &$object ) {
			return $ev->ObjectID == $object->ObjectID;
		} );

		$prices       = array();
		$sortedEvents = array();
		$eventCities  = array();
		foreach ( $pricenames as $pr ) {
			foreach ( $events as $ev ) {
				if ( $ev->OccationID == $pr->OccationID ) {
					$prices[ $pr->Price ] = $pr;
				}
				$sortedEvents[ $ev->PeriodStart ] = $ev;
				$eventCities[ $ev->City ]         = $ev;
			}
		}

		ksort( $sortedEvents );
		ksort( $eventCities );

		if ( $showEventsWithEventsOnly && empty( $sortedEvents ) ) {
			continue;
		}

		if ( $showEventsWithoutEventsOnly && ! empty( $sortedEvents ) ) {
			continue;
		}
		?>
        <div class="objectBlock brick">
			<?php if ( $showImages && ! empty( $object->ImageUrl ) ) { ?>
                <div class="objectImage"
                     onclick="location.href = '<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/<?php echo edu_getQueryString(); ?>';"
                     style="background-image: url('<?php echo $object->ImageUrl; ?>');"></div>
			<?php } ?>
            <div class="objectName">
                <a href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/<?php echo edu_getQueryString(); ?>"><?php
						echo htmlentities( getUTF8( $name ) );
					?></a>
            </div>
            <div class="objectDescription"><?php
					if ( stripos( $descrField, "attr_" ) !== false && ! empty( $objectAttributes ) ) {
						$objectDescription = array_filter( $objectAttributes, function( $oa ) use ( &$object ) {
							return $oa->ObjectID == $object->ObjectID;
						} );

						$descr = htmlspecialchars_decode( current( $objectDescription )->AttributeValue );
					} else {
						$descr = $object->{$descrField};
					}

					if ( $showDescr ) {
						echo "<div class\"courseDescription\">" . $descr . "</div>";
					}

					if ( $showCourseLocations && ! empty( $eventCities ) && $showCity ) {
						$cities = join( ", ", array_keys( $eventCities ) );
						echo "<div class=\"locationInfo\">" . $cities . "</div> ";
					}

					if ( $showNextEventDate ) {
						echo "<div class=\"nextEventDate\" data-eduwidget=\"courseitem-date\" data-objectid=\"" . $object->ObjectID . "\">";
						if ( ! empty( $sortedEvents ) ) {
							echo sprintf( edu__( 'Next event %1$s' ), date( "Y-m-d", strtotime( current( $sortedEvents )->PeriodStart ) ) ) . " " . current( $sortedEvents )->City;
							if ( $showEventVenue ) {
								echo "<span class=\"venueInfo\">, " . current( $sortedEvents )->AddressName . "</span>";
							}
						} else {
							echo "<i>" . edu__( 'No coming events' ) . "</i>";
						}
						echo "</div> ";
					}

					if ( $showEventPrice && ! empty( $prices ) ) {
						ksort( $prices );
						$cheapest = current( $prices );
						echo "<div class=\"priceInfo\">" . sprintf( edu__( 'From %1$s' ), convertToMoney( $cheapest->Price, get_option( 'eduadmin-currency', 'SEK' ) ) ) . " " . edu__( $incVat ? "inc vat" : "ex vat" ) . "</div> ";
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
				?></div>
            <div class="objectBook">
				<?php if ( $showReadMoreBtn ) : ?>
                    <a class="readMoreButton"
                       href="<?php echo $baseUrl; ?>/<?php echo makeSlugs( $name ); ?>__<?php echo $object->ObjectID; ?>/<?php echo edu_getQueryString(); ?>"><?php edu_e( "Read more" ); ?></a>
				<?php endif; ?>
            </div>
        </div>
		<?php
	}
	$out = ob_get_clean();
	return $out;