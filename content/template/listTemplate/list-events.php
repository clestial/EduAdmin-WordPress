<?php
	$surl    = get_home_url();
	$cat     = get_option( 'eduadmin-rewriteBaseUrl' );
	$baseUrl = $surl . '/' . $cat;

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

	if ( isset( $_REQUEST['eduadmin-city'] ) && ! empty( $_REQUEST['eduadmin-city'] ) ) {
		$f = new XFilter( 'LocationID', '=', intval( $_REQUEST['eduadmin-city'] ) );
		$filtering->AddItem( $f );
	}

	if ( isset( $_REQUEST['eduadmin-category'] ) && ! empty( $_REQUEST['eduadmin-category'] ) ) {
		$f = new XFilter( 'CategoryID', '=', intval( $_REQUEST['eduadmin-category'] ) );
		$filtering->AddItem( $f );
		$attributes['category'] = intval( $_REQUEST['eduadmin-category'] );
	}

	$edo = EDU()->api->GetEducationObjectV2( EDU()->get_token(), '', $filtering->ToString(), false );

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
			$subjects = EDU()->api->GetEducationSubject( EDU()->get_token(), $sorting->ToString(), '' );
			set_transient( 'eduadmin-subjects', $subjects, DAY_IN_SECONDS );
		}

		$attributes["subjectid"] = intval( $_REQUEST['eduadmin-subject'] );

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

	$filtering = new XFiltering();
	$f         = new XFilter( 'ShowOnWeb', '=', 'true' );
	$filtering->AddItem( $f );

	if ( $categoryID > 0 ) {
		$f = new XFilter( 'CategoryID', '=', $categoryID );
		$filtering->AddItem( $f );
	}

	$fetchMonths = get_option( 'eduadmin-monthsToFetch', 6 );
	if ( ! is_numeric( $fetchMonths ) ) {
		$fetchMonths = 6;
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
		$attributes['subjectid'] = intval( $_REQUEST['eduadmin-subject'] );
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

	$ede = EDU()->api->GetEvent( EDU()->get_token(), $sorting->ToString(), $filtering->ToString() );

	if ( isset( $_REQUEST['eduadmin-subject'] ) && ! empty( $_REQUEST['eduadmin-subject'] ) ) {
		$subjects = get_transient( 'eduadmin-subjects' );
		if ( ! $subjects ) {
			$sorting = new XSorting();
			$s       = new XSort( 'SubjectName', 'ASC' );
			$sorting->AddItem( $s );
			$subjects = EDU()->api->GetEducationSubject( EDU()->get_token(), $sorting->ToString(), '' );
			set_transient( 'eduadmin-subjects', $subjects, DAY_IN_SECONDS );
		}

		$attributes['subjectid'] = intval( $_REQUEST['eduadmin-subject'] );

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

	$st = new XSorting();
	$s  = new XSort( 'Price', 'ASC' );
	$st->AddItem( $s );

	$pricenames = EDU()->api->GetPriceName( EDU()->get_token(), $st->ToString(), $ft->ToString() );
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
	$incVat          = EDU()->api->GetAccountSetting( EDU()->get_token(), 'PriceIncVat' ) == "yes";

	$showEventPrice = get_option( 'eduadmin-showEventPrice', false );
	$currency       = get_option( 'eduadmin-currency', 'SEK' );
	$showEventVenue = get_option( 'eduadmin-showEventVenueName', false );

	$spotLeftOption = get_option( 'eduadmin-spotsLeft', 'exactNumbers' );
	$alwaysFewSpots = get_option( 'eduadmin-alwaysFewSpots', '3' );
	$spotSettings   = get_option( 'eduadmin-spotsSettings', "1-5\n5-10\n10+" );
?>
<div class="eventListTable"
     data-eduwidget="listview-eventlist"
     data-template="<?php echo @esc_attr( str_replace( "template_", "", $attributes['template'] ) ); ?>"
     data-subject="<?php echo @esc_attr( $attributes['subject'] ); ?>"
     data-subjectid="<?php echo @esc_attr( $attributes['subjectid'] ); ?>"
     data-category="<?php echo @esc_attr( $attributes['category'] ); ?>"
     data-courselevel="<?php echo @esc_attr( $attributes['courselevel'] ); ?>"
     data-city="<?php echo @esc_attr( $attributes['city'] ); ?>"
     data-search="<?php echo @esc_attr( sanitize_text_field( $_REQUEST['searchCourses'] ) ); ?>"
     data-numberofevents="<?php echo @esc_attr( $attributes['numberofevents'] ); ?>"
     data-orderby="<?php echo @esc_attr( $attributes['orderby'] ); ?>"
     data-order="<?php echo @esc_attr( $attributes['order'] ); ?>"
     data-showmore="<?php echo @esc_attr( $attributes['showmore'] ); ?>"
     data-showcity="<?php echo @esc_attr( $attributes['showcity'] ); ?>"
     data-showbookbtn="<?php echo @esc_attr( $attributes['showbookbtn'] ); ?>"
     data-showreadmorebtn="<?php echo @esc_attr( $attributes['showreadmorebtn'] ); ?>"
>
