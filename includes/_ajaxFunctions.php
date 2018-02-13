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

		$fetchMonths = $_POST['fetchmonths'];
		if ( ! is_numeric( $fetchMonths ) ) {
			$fetchMonths = 6;
		}

		$filters = array();
		$expands = array();
		$sorting = array();

		$expands['Subjects']   = "";
		$expands['Categories'] = "";
		$expands['PriceNames'] = '$filter=PublicPriceName';
		$expands['Events']     =
			'$filter=' .
			'HasPublicPriceName' .
			' and StatusId eq 1' .
			' and CustomerId eq null' .
			' and LastApplicationDate ge ' . date( "c" ) .
			' and StartDate le ' . date( "c", strtotime( "now 23:59:59 +" . $fetchMonths . " months" ) ) .
			' and EndDate ge ' . date( "c", strtotime( "now" ) ) .
			';' .
			'$expand=PriceNames,EventDates' .
			';' .
			'$orderby=StartDate asc' .
			';';

		$expands['CustomFields'] = '$filter=ShowOnWeb';

		$filters[] = "ShowOnWeb";

		if ( isset( $_POST['category'] ) && ! empty( $_POST['category'] ) ) {
			$filters[] = "CategoryId eq " . intval( sanitize_text_field( $_POST['category'] ) );
		}

		if ( isset( $_POST['city'] ) && ! empty( $_POST['city'] ) ) {
			$filters[] = 'Events/any(e:e/LocationId eq ' . intval( $_POST['city'] ) . ')';
		}

		if ( isset( $_POST['subjectid'] ) && ! empty( $_POST['subjectid'] ) ) {
			$filters[] = 'Subjects/any(s:s/SubjectId eq ' . intval( $_POST['subjectid'] ) . ')';
		}

		if ( ! empty( $_POST['subject'] ) ) {
			$filters[] = 'Subjects/any(s:s/SubjectName eq \'' . sanitize_text_field( $_POST['subject'] ) . '\')';
		}

		if ( isset( $_POST['courselevel'] ) && ! empty( $_POST['courselevel'] ) ) {
			$filters[] = 'EducationLevelId eq ' . intval( sanitize_text_field( $_POST['courselevel'] ) );
		}

		$sortOrder = get_option( 'eduadmin-listSortOrder', 'SortIndex' );

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
					$or = "asc";
				}

				$sorting[] = $v . ' ' . strtolower( $or );
			}
		}

		$sorting[] = $sortOrder . ' asc';

		$expandArr = array();
		foreach ( $expands as $key => $value ) {
			if ( empty( $value ) ) {
				$expandArr[] = $key;
			} else {
				$expandArr[] = $key . "(" . $value . ")";
			}
		}

		$edo     = EDUAPI()->OData->CourseTemplates->Search(
			null,
			join( " and ", $filters ),
			join( ",", $expandArr ),
			join( ",", $sorting )
		);
		$courses = $edo["value"];

		if ( isset( $_REQUEST['searchCourses'] ) && ! empty( $_REQUEST['searchCourses'] ) ) {
			$courses = array_filter( $courses, function( $object ) {
				$name       = ( ! empty( $object["CourseName"] ) ? $object["CourseName"] : $object["InternalCourseName"] );
				$descrField = get_option( 'eduadmin-layout-descriptionfield', 'CourseDescriptionShort' );
				$descr      = '';
				if ( stripos( $descrField, "attr_" ) !== false ) {
					$attrId = intval( substr( $descrField, 5 ) );
					foreach ( $object['CustomFields'] as $custom_field ) {
						if ( $attrId === $custom_field['CustomFieldId'] ) {
							$descr = strip_tags( $custom_field['CustomFieldValue'] );
							break;
						}
					}
				} else {
					$descr = strip_tags( $object[ $descrField ] );
				}

				$nameMatch  = stripos( $name, sanitize_text_field( $_REQUEST['searchCourses'] ) ) !== false;
				$descrMatch = stripos( $descr, sanitize_text_field( $_REQUEST['searchCourses'] ) ) !== false;

				return ( $nameMatch || $descrMatch );
			} );
		}

		$events = array();
		foreach ( $courses as $object ) {
			foreach ( $object["Events"] as $event ) {
				$event['CourseTemplate'] = $object;
				unset( $event['CourseTemplate']['Events'] );

				$pricenames = array();
				foreach ( $object['PriceNames'] as $pn ) {
					$pricenames[] = $pn['Price'];
				}
				foreach ( $event['PriceNames'] as $pn ) {
					$pricenames[] = $pn['Price'];
				}

				$minPrice       = min( $pricenames );
				$event['Price'] = $minPrice;

				$events[] = $event;
			}
		}

		if ( "A" == $_POST['template'] ) {
			edu_api_listview_eventlist_template_A( $events, $_POST );
		} else if ( "B" == $_POST['template'] ) {
			edu_api_listview_eventlist_template_B( $events, $_POST );
		}
		die();
	}

	function edu_api_listview_eventlist_template_A( $data, $request ) {
		$showMoreNumber  = $request['showmore'];
		$showCity        = $request['showcity'];
		$showBookBtn     = $request['showbookbtn'];
		$showReadMoreBtn = $request['showreadmorebtn'];

		$showCourseDays  = get_option( 'eduadmin-showCourseDays', true );
		$showCourseTimes = get_option( 'eduadmin-showCourseTimes', true );
		$showWeekDays    = get_option( 'eduadmin-showWeekDays', false );
		$incVat          = EDUAPI()->REST->Organisation->GetOrganisation()["PriceIncVat"];

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

		foreach ( $data as $event ) {
			if ( $numberOfEvents != null && $numberOfEvents > 0 && $currentEvents >= $numberOfEvents ) {
				break;
			}

			$name      = $event["EventName"];
			$spotsLeft = $event["ParticipantNumberLeft"];
			$object    = $event['CourseTemplate'];

			$eventDates = array();
			if ( ! empty( $event["EventDates"] ) ) {
				$eventDates[ $event["EventId"] ] = $event["EventDates"];
			}

			include( EDUADMIN_PLUGIN_PATH . '/content/template/listTemplate/blocks/event_blockA.php' );
			$currentEvents++;
		}
	}

	function edu_api_listview_eventlist_template_B( $data, $request ) {
		$showMoreNumber  = $request['showmore'];
		$showCity        = $request['showcity'];
		$showBookBtn     = $request['showbookbtn'];
		$showReadMoreBtn = $request['showreadmorebtn'];

		$showCourseDays  = get_option( 'eduadmin-showCourseDays', true );
		$showCourseTimes = get_option( 'eduadmin-showCourseTimes', true );
		$showWeekDays    = get_option( 'eduadmin-showWeekDays', false );
		$incVat          = EDUAPI()->REST->Organisation->GetOrganisation()["PriceIncVat"];

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

		foreach ( $data as $event ) {
			if ( $numberOfEvents != null && $numberOfEvents > 0 && $currentEvents >= $numberOfEvents ) {
				break;
			}

			$name      = $event["EventName"];
			$spotsLeft = $event["ParticipantNumberLeft"];
			$object    = $event['CourseTemplate'];

			$eventDates = array();
			if ( ! empty( $event["EventDates"] ) ) {
				$eventDates[ $event["EventId"] ] = $event["EventDates"];
			}

			include( EDUADMIN_PLUGIN_PATH . '/content/template/listTemplate/blocks/event_blockB.php' );
			$currentEvents++;
		}
	}

	function edu_api_eventlist() {
		header( "Content-type: text/html; charset=UTF-8" );

		$objectId = $_POST['objectid'];

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

		$courseId = $objectId;
		$edo      = get_transient( 'eduadmin-object_' . $courseId );
		if ( ! $edo ) {
			$fetchMonths = get_option( 'eduadmin-monthsToFetch', 6 );
			if ( ! is_numeric( $fetchMonths ) ) {
				$fetchMonths = 6;
			}

			$groupByCity = get_option( 'eduadmin-groupEventsByCity', false );

			$expands = array();

			$expands['Subjects']   = "";
			$expands['Categories'] = "";
			$expands['PriceNames'] = '$filter=PublicPriceName;';
			$expands['Events']     =
				'$filter=' .
				'HasPublicPriceName' .
				' and StatusId eq 1' .
				' and CustomerId eq null' .
				' and LastApplicationDate ge ' . date( "c" ) .
				' and StartDate le ' . date( "c", strtotime( "now 23:59:59 +" . $fetchMonths . " months" ) ) .
				' and EndDate ge ' . date( "c", strtotime( "now" ) ) .
				';' .
				'$expand=PriceNames($filter=PublicPriceName),EventDates' .
				';' .
				'$orderby=' . ( $groupByCity ? 'City asc,' : '' ) . 'StartDate asc' .
				';';

			$expands['CustomFields'] = '$filter=ShowOnWeb';

			$expandArr = array();
			foreach ( $expands as $key => $value ) {
				if ( empty( $value ) ) {
					$expandArr[] = $key;
				} else {
					$expandArr[] = $key . "(" . $value . ")";
				}
			}

			$edo = EDUAPI()->OData->CourseTemplates->GetItem(
				$courseId,
				null,
				join( ",", $expandArr )
			);
			set_transient( 'eduadmin-object_' . $courseId, $edo, 10 );
		}

		$selectedCourse = false;
		$name           = "";
		if ( $edo ) {
			$name           = ( ! empty( $edo["CourseName"] ) ? $edo["CourseName"] : $edo["InternalCourseName"] );
			$selectedCourse = $edo;
		}

		$surl    = get_home_url();
		$cat     = get_option( 'eduadmin-rewriteBaseUrl' );
		$baseUrl = $surl . '/' . $cat;

		$events = $selectedCourse["Events"];

		$prices = array();

		foreach ( $selectedCourse["PriceNames"] as $pn ) {
			$prices[ $pn["PriceNameId"] ] = $pn;
		}

		foreach ( $events as $e ) {
			foreach ( $e["PriceNames"] as $pn ) {
				$prices[ $pn["PriceNameId"] ] = $pn;
			}
		}

		$lastCity = "";

		$showMore         = isset( $_POST['showmore'] ) && ! empty( $_POST['showmore'] ) ? $_POST['showmore'] : -1;
		$spotLeftOption   = $_POST['spotsleft'];
		$alwaysFewSpots   = $_POST['fewspots'];
		$showEventVenue   = $_POST['showvenue'];
		$spotSettings     = $_POST['spotsettings'];
		$showEventInquiry = isset( $_POST['eventinquiry'] ) && $_POST['eventinquiry'] == "true";

		echo '<div class="eduadmin"><div class="event-table eventDays">';
		$i              = 0;
		$hasHiddenDates = false;
		if ( ! empty( $prices ) ) {
			foreach ( $events as $ev ) {

				if ( isset( $_POST['eid'] ) ) {
					if ( $ev['EventId'] != $_POST['eid'] ) {
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
				$lastCity = $ev['City'];
				$i++;
			}
		}
		if ( empty( $prices ) || empty( $events ) ) {
			echo '<div class="noDatesAvailable"><i>' . __( "No available dates for the selected course", 'eduadmin-booking' ) . '</i></div>';
		}
		if ( $hasHiddenDates ) {
			echo "<div class=\"eventShowMore\"><a class='neutral-btn' href=\"javascript://\" onclick=\"eduDetailView.ShowAllEvents('eduev" . ( $groupByCity ? "-" . $lastCity : "" ) . "', this);\">" . __( "Show all events", 'eduadmin-booking' ) . "</a></div>";
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