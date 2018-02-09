<?php
	defined( 'ABSPATH' ) or die( 'This plugin must be run within the scope of WordPress.' );

	function edu_listview_courselist() {
		$objectIds = $_POST['objectIds'];

		$fetchMonths = $_POST['fetchmonths'];
		if ( ! is_numeric( $fetchMonths ) ) {
			$fetchMonths = 6;
		}

		$filters = array();
		$expands = array();
		$sorting = array();

		$expands['PriceNames'] = "";
		$expands['Events']     =
			'$filter=' .
			'HasPublicPriceName' .
			' and StatusId eq 1' .
			' and CustomerId eq null' .
			' and LastApplicationDate ge ' . date( "c" ) .
			' and StartDate le ' . date( "c", strtotime( "now 23:59:59 +" . $fetchMonths . " months" ) ) .
			' and EndDate ge ' . date( "c", strtotime( "now" ) ) .
			';' .
			'$expand=PriceNames' .
			';' .
			'$top=1' .
			';' .
			'$orderby=StartDate asc' .
			';';

		$filters[] = "ShowOnWeb";

		$expandArr = array();
		foreach ( $expands as $key => $value ) {
			if ( empty( $value ) ) {
				$expandArr[] = $key;
			} else {
				$expandArr[] = $key . "(" . $value . ")";
			}
		}

		$edo     = EDUAPI()->OData->CourseTemplates->Search(
			"CourseTemplateId",
			join( " and ", $filters ),
			join( ",", $expandArr ),
			join( ",", $sorting )
		);
		$courses = $edo["value"];

		$returnValue = array();
		foreach ( $courses as $event ) {
			if ( ! isset( $returnValue[ $event["CourseTemplateId"] ] ) && count( $event["Events"] ) > 0 ) {
				$returnValue[ $event["CourseTemplateId"] ] = sprintf( __( 'Next event %1$s', 'eduadmin-booking' ), date( "Y-m-d", strtotime( $event["Events"][0]["StartDate"] ) ) ) . " " . $event["Events"][0]["City"];
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
		$showMoreNumber  = $request['showmore'];
		$showCity        = $request['showcity'];
		$showBookBtn     = $request['showbookbtn'];
		$showReadMoreBtn = $request['showreadmorebtn'];

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

		$showImages = get_option( 'eduadmin-showCourseImage', true );

		$numberOfEvents = $request['numberofevents'];

		$surl    = get_home_url();
		$cat     = get_option( 'eduadmin-rewriteBaseUrl' );
		$baseUrl = $surl . '/' . $cat;

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
			include( EDUADMIN_PLUGIN_PATH . '/content/template/listTemplate/blocks/event_blockA.php' );
			$currentEvents++;
		}
	}

	function edu_api_listview_eventlist_template_B( $data, $eventDates, $request ) {
		$showMoreNumber  = $request['showmore'];
		$showCity        = $request['showcity'];
		$showBookBtn     = $request['showbookbtn'];
		$showReadMoreBtn = $request['showreadmorebtn'];

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

		$showImages = get_option( 'eduadmin-showCourseImage', true );

		$numberOfEvents = $request['numberofevents'];

		$surl    = get_home_url();
		$cat     = get_option( 'eduadmin-rewriteBaseUrl' );
		$baseUrl = $surl . '/' . $cat;

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
			include( EDUADMIN_PLUGIN_PATH . '/content/template/listTemplate/blocks/event_blockB.php' );
			$currentEvents++;
		}
	}

	function edu_api_eventlist() {
		header( "Content-type: text/html; charset=UTF-8" );

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

		$surl    = get_home_url();
		$cat     = get_option( 'eduadmin-rewriteBaseUrl' );
		$baseUrl = $surl . '/' . $cat;

		$lastCity = "";

		$showMore         = isset( $_POST['showmore'] ) && ! empty( $_POST['showmore'] ) ? $_POST['showmore'] : -1;
		$spotLeftOption   = $_POST['spotsleft'];
		$alwaysFewSpots   = $_POST['fewspots'];
		$showEventVenue   = $_POST['showvenue'];
		$spotSettings     = $_POST['spotsettings'];
		$showEventInquiry = isset( $_POST['eventinquiry'] ) && $_POST['eventinquiry'] == "true";
		$name             = ( ! empty( $selectedCourse->PublicName ) ? $selectedCourse->PublicName : $selectedCourse->ObjectName );
		echo '<div class="eduadmin"><div class="event-table eventDays">';
		$i              = 0;
		$hasHiddenDates = false;
		if ( ! empty( $pricenames ) ) {
			foreach ( $events as $ev ) {
				$spotsLeft = ( $ev->MaxParticipantNr - $ev->TotalParticipantNr );

				if ( isset( $request['eid'] ) ) {
					if ( $ev->EventID != $_POST['eid'] ) {
						continue;
					}
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

				include( EDUADMIN_PLUGIN_PATH . '/content/template/detailTemplate/blocks/event-item.php' );
				$lastCity = $ev->City;
				$i++;
			}
		}
		if ( empty( $pricenames ) || empty( $events ) ) {
			echo '<div class="noDatesAvailable"><i>' . __( "No available dates for the selected course", 'eduadmin-booking' ) . '</i></div>';
		}
		if ( $hasHiddenDates ) {
			echo "<div class=\"eventShowMore\"><a class='neutral-btn' href=\"javascript://\" onclick=\"eduDetailView.ShowAllEvents('eduev" . ( $groupByCity ? "-" . $ev->City : "" ) . "', this);\">" . __( "Show all events", 'eduadmin-booking' ) . "</a></div>";
		}
		echo '</div></div>';

		die();
	}

	function edu_api_loginwidget() {
		header( "Content-type: text/html; charset=UTF-8" );
		$surl    = get_home_url();
		$cat     = get_option( 'eduadmin-rewriteBaseUrl' );
		$baseUrl = $surl . '/' . $cat;

		$loginText  = $_POST['logintext'];
		$logoutText = $_POST['logouttext'];
		$guestText  = $_POST['guesttext'];

		if ( isset( EDU()->session['eduadmin-loginUser'] ) ) {
			$user    = EDU()->session['eduadmin-loginUser'];
			$contact = $user->Contact;
		}

		if ( isset( EDU()->session['eduadmin-loginUser'] ) &&
		     ! empty( EDU()->session['eduadmin-loginUser'] ) &&
		     isset( $contact ) &&
		     isset( $contact->PersonId ) &&
		     $contact->PersonId != 0
		) {
			echo
				"<div class=\"eduadminLogin\"><a href=\"" . $baseUrl . "/profile/myprofile" . edu_getQueryString( "?", array(
					'eid',
					'module',
				) ) . "\" class=\"eduadminMyProfileLink\">" .
				trim( $contact->FirstName . " " . $contact->LastName ) .
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
		$eventId = $_POST['eventId'];
		$code    = $_POST['code'];
		$vcode   = EDUAPI()->REST->Coupon->IsValid( $eventId, $code );

		return rest_ensure_response( $vcode );
	}