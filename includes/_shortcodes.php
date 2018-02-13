<?php
	defined( 'ABSPATH' ) or die( 'This plugin must be run within the scope of WordPress.' );
	if ( ! function_exists( 'normalize_empty_atts' ) ) {
		function normalize_empty_atts( $atts ) {
			if ( empty( $atts ) ) {
				return $atts;
			}
			foreach ( $atts as $attribute => $value ) {
				if ( is_int( $attribute ) ) {
					$atts[ strtolower( $value ) ] = true;
					unset( $atts[ $attribute ] );
				}
			}

			return $atts;
		}
	}

	function eduadmin_get_list_view( $attributes ) {
		$t                = EDU()->StartTimer( __METHOD__ );
		$selectedTemplate = get_option( 'eduadmin-listTemplate', 'template_A' );
		$attributes       = shortcode_atts(
			array(
				'template'        => $selectedTemplate,
				'category'        => null,
				'subject'         => null,
				'subjectid'       => null,
				'hidesearch'      => false,
				'onlyevents'      => false,
				'onlyempty'       => false,
				'numberofevents'  => null,
				'mode'            => null,
				'orderby'         => null,
				'order'           => null,
				'showsearch'      => null,
				'showmore'        => null,
				'showcity'        => true,
				'showbookbtn'     => true,
				'showreadmorebtn' => true,
				'filtercity'      => null,
			),
			normalize_empty_atts( $attributes ),
			'eduadmin-listview'
		);
		$str              = include( EDUADMIN_PLUGIN_PATH . "/content/template/listTemplate/" . $attributes['template'] . ".php" );
		EDU()->StopTimer( $t );

		return $str;
	}

	function eduadmin_get_object_interest( $attributes ) {
		$t          = EDU()->StartTimer( __METHOD__ );
		$attributes = shortcode_atts(
			array(
				'courseid' => null,
			),
			normalize_empty_atts( $attributes ),
			'eduadmin-objectinterest'
		);
		$str        = include( EDUADMIN_PLUGIN_PATH . "/content/template/interestRegTemplate/interestRegObject.php" );
		EDU()->StopTimer( $t );

		return $str;
	}

	function eduadmin_get_event_interest( $attributes ) {
		$t          = EDU()->StartTimer( __METHOD__ );
		$attributes = shortcode_atts(
			array(),
			normalize_empty_atts( $attributes ),
			'eduadmin-eventinterest'
		);
		$str        = include( EDUADMIN_PLUGIN_PATH . "/content/template/interestRegTemplate/interestRegEvent.php" );
		EDU()->StopTimer( $t );

		return $str;
	}

	function eduadmin_get_detail_view( $attributes ) {
		$t                = EDU()->StartTimer( __METHOD__ );
		$selectedTemplate = get_option( 'eduadmin-detailTemplate', 'template_A' );
		$attributes       = shortcode_atts(
			array(
				'template'       => $selectedTemplate,
				'courseid'       => null,
				'customtemplate' => null,
				'showmore'       => null,
				'hide'           => null,
			),
			normalize_empty_atts( $attributes ),
			'eduadmin-detailview'
		);
		unset( EDU()->session['checkEmail'] );
		unset( EDU()->session['needsLogin'] );
		unset( EDU()->session['eduadmin-loginUser']->NewCustomer );

		EDU()->session->regenerate_id( true );

		if ( ! isset( $attributes['customtemplate'] ) || 1 != $attributes['customtemplate'] ) {
			$str = include_once( EDUADMIN_PLUGIN_PATH . "/content/template/detailTemplate/" . $attributes['template'] . ".php" );
			EDU()->StopTimer( $t );

			return $str;
		}
		EDU()->StopTimer( $t );

		return '';
	}

	function eduadmin_get_course_public_pricename( $attributes ) {
		$t = EDU()->StartTimer( __METHOD__ );
		global $wp_query;
		$attributes = shortcode_atts(
			array(
				'courseid'       => null,
				'orderby'        => null,
				'order'          => null,
				'numberofprices' => null,
			),
			normalize_empty_atts( $attributes ),
			'eduadmin_coursepublicpricename'
		);

		if ( empty( $attributes['courseid'] ) || $attributes['courseid'] <= 0 ) {
			if ( isset( $wp_query->query_vars["courseId"] ) ) {
				$courseId = $wp_query->query_vars["courseId"];
			} else {
				EDU()->StopTimer( $t );

				return 'Missing courseId in attributes';
			}
		} else {
			$courseId = $attributes['courseid'];
		}
		EDU()->StopTimer( $t );

		return include_once( EDUADMIN_PLUGIN_PATH . "/content/template/myPagesTemplate/coursePriceNames.php" );
	}

	function edu_no_index() {
		$t = EDU()->StartTimer( __METHOD__ );
		global $wp_query;
		$detailpage = get_option( 'eduadmin-detailViewPage' );
		if ( isset( $wp_query->queried_object ) ) {
			if ( $detailpage !== false && $detailpage == $wp_query->queried_object->ID && ! isset( $wp_query->query['courseId'] ) ) {
				echo '<meta name="robots" content="noindex" />';
			}
		}
		EDU()->StopTimer( $t );
	}

	add_action( 'wp_head', 'edu_no_index' );

	function eduadmin_get_booking_view( $attributes ) {
		$t = EDU()->StartTimer( __METHOD__ );
		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}
		$selectedTemplate = get_option( 'eduadmin-bookingTemplate', 'template_A' );
		$attributes       = shortcode_atts(
			array(
				'template'               => $selectedTemplate,
				'courseid'               => null,
				'hideinvoiceemailfield'  => null,
				'showinvoiceinformation' => null,
			),
			normalize_empty_atts( $attributes ),
			'eduadmin-bookingview'
		);
		if ( get_option( 'eduadmin-useLogin', false ) == false || ( isset( EDU()->session['eduadmin-loginUser'] ) && ( ( isset( EDU()->session['eduadmin-loginUser']->Contact->PersonId ) && EDU()->session['eduadmin-loginUser']->Contact->PersonId != 0 ) || isset( EDU()->session['eduadmin-loginUser']->NewCustomer ) ) ) ) {
			$str = include_once( EDUADMIN_PLUGIN_PATH . "/content/template/bookingTemplate/" . $attributes['template'] . ".php" );
		} else {
			$str = include_once( EDUADMIN_PLUGIN_PATH . "/content/template/bookingTemplate/loginView.php" );
		}
		EDU()->StopTimer( $t );

		return $str;
	}

	function eduadmin_get_detailinfo( $attributes ) {
		$t = EDU()->StartTimer( __METHOD__ );
		global $wp_query;
		$attributes = shortcode_atts(
			array(
				'courseid'                  => null,
				'coursename'                => null,
				'coursepublicname'          => null,
				'courselevel'               => null,
				'courseimage'               => null,
				'courseimagetext'           => null,
				'coursedays'                => null,
				'coursestarttime'           => null,
				'courseendtime'             => null,
				'courseprice'               => null,
				'coursedescriptionshort'    => null,
				'coursedescription'         => null,
				'coursegoal'                => null,
				'coursetarget'              => null,
				'courseprerequisites'       => null,
				'courseafter'               => null,
				'coursequote'               => null,
				'courseeventlist'           => null,
				'showmore'                  => null,
				'courseattributeid'         => null,
				'courseeventlistfiltercity' => null,
				'pagetitlejs'               => null,
				'bookurl'                   => null,
				'courseinquiryurl'          => null,
				'order'                     => null,
				'orderby'                   => null,
				//'coursesubject' => null
			),
			normalize_empty_atts( $attributes ),
			'eduadmin-detailinfo'
		);

		$retStr = '';

		if ( empty( $attributes['courseid'] ) || $attributes['courseid'] <= 0 ) {
			if ( isset( $wp_query->query_vars["courseId"] ) ) {
				$courseId = $wp_query->query_vars["courseId"];
			} else {
				EDU()->StopTimer( $t );

				return 'Missing courseId in attributes';
			}
		} else {
			$courseId = $attributes['courseid'];
		}

		$apiKey = get_option( 'eduadmin-api-key' );

		if ( ! $apiKey || empty( $apiKey ) ) {
			EDU()->StopTimer( $t );

			return 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
		} else {
			$edo = get_transient( 'eduadmin-object_' . $courseId );
			if ( ! $edo ) {
				$edo = EDUAPI()->OData->CourseTemplates->GetItem(
					$courseId,
					null,
					"Subjects,Events,CustomFields"
				);
				set_transient( 'eduadmin-object_' . $courseId, $edo, 10 );
			}

			$selectedCourse = false;
			if ( $edo ) {
				$selectedCourse = $edo;
			}

			if ( ! $selectedCourse ) {
				EDU()->StopTimer( $t );

				return 'Course with ID ' . $courseId . ' could not be found.';
			} else {
				if ( isset( $attributes['coursename'] ) ) {
					$retStr .= $selectedCourse["InternalCourseName"];
				}
				if ( isset( $attributes['coursepublicname'] ) ) {
					$retStr .= $selectedCourse["CourseName"];
				}
				if ( isset( $attributes['courseimage'] ) ) {
					$retStr .= $selectedCourse["ImageUrl"];
				}
				if ( isset( $attributes['coursedays'] ) ) {
					$retStr .= sprintf( _n( '%1$d day', '%1$d days', $selectedCourse["Days"], 'eduadmin-booking' ), $selectedCourse["Days"] );
				}
				if ( isset( $attributes['coursestarttime'] ) ) {
					$retStr .= $selectedCourse["StartTime"];
				}
				if ( isset( $attributes['courseendtime'] ) ) {
					$retStr .= $selectedCourse["EndTime"];
				}
				if ( isset( $attributes['coursedescriptionshort'] ) ) {
					$retStr .= $selectedCourse["CourseDescriptionShort"];
				}
				if ( isset( $attributes['coursedescription'] ) ) {
					$retStr .= $selectedCourse["CourseDescription"];
				}
				if ( isset( $attributes['coursegoal'] ) ) {
					$retStr .= $selectedCourse["CourseGoal"];
				}
				if ( isset( $attributes['coursetarget'] ) ) {
					$retStr .= $selectedCourse["TargetGroup"];
				}
				if ( isset( $attributes['courseprerequisites'] ) ) {
					$retStr .= $selectedCourse["Prerequisites"];
				}
				if ( isset( $attributes['courseafter'] ) ) {
					$retStr .= $selectedCourse["CourseAfter"];
				}
				if ( isset( $attributes['coursequote'] ) ) {
					$retStr .= $selectedCourse["Quote"];
				}
				if ( isset( $attributes['coursesubject'] ) ) {
					$subjectNames = array();
					foreach ( $selectedCourse["Subjects"] as $subj ) {
						$subjectNames[] = $subj["SubjectName"];
					}
					$retStr .= join( ", ", $subjectNames );
				}
				if ( isset( $attributes['courselevel'] ) ) {
					$ft = new XFiltering();
					$f  = new XFilter( 'ObjectID', '=', $selectedCourse["CourseTemplateId"] );
					$ft->AddItem( $f );
					$courseLevel = EDU()->api->GetEducationLevelObject( EDU()->get_token(), '', $ft->ToString() );

					if ( ! empty( $courseLevel ) ) {
						$retStr .= $courseLevel[0]->Name;
					}
				}
				if ( isset( $attributes['courseattributeid'] ) ) {
					$attrid = $attributes['courseattributeid'];
					$ft     = new XFiltering();
					$f      = new XFilter( 'ObjectID', '=', $selectedCourse["CourseTemplateId"] );
					$ft->AddItem( $f );
					$f = new XFilter( 'AttributeID', '=', $attrid );
					$ft->AddItem( $f );

					foreach ( $selectedCourse["CustomFields"] as $cf ) {
						if ( $cf["CustomFieldId"] == $attrid ) {
						}
					}

					$objAttr = EDU()->api->GetObjectAttribute( EDU()->get_token(), '', $ft->ToString() );
					if ( ! empty( $objAttr ) ) {
						$attr = $objAttr[0];
						switch ( $attr->AttributeTypeID ) {
							case 5:
								$value = $attr->AttributeAlternative;
								break;
							default:
								$value = $attr->AttributeValue;
								break;
						}
						$retStr .= $value;
					}
				}

				if ( isset( $attributes['courseprice'] ) ) {
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
					$f = new XFilter( 'ObjectID', '=', $selectedCourse["CourseTemplateId"] );
					$ft->AddItem( $f );
					$f = new XFilter( 'LastApplicationDate', '>=', date( "Y-m-d H:i:s" ) );
					$ft->AddItem( $f );

					if ( ! empty( $attributes['courseeventlistfiltercity'] ) ) {
						$f = new XFilter( 'City', '=', $attributes['courseeventlistfiltercity'] );
						$ft->AddItem( $f );
					}

					$st          = new XSorting();
					$groupByCity = get_option( 'eduadmin-groupEventsByCity', false );
					if ( $groupByCity ) {
						$s = new XSort( 'City', 'ASC' );
						$st->AddItem( $s );
					}
					$s = new XSort( 'PeriodStart', 'ASC' );
					$st->AddItem( $s );

					$events = EDU()->api->GetEvent(
						EDU()->get_token(),
						$st->ToString(),
						$ft->ToString()
					);

					$occIds = array();

					$occIds[] = -1;

					foreach ( $events as $e ) {
						$occIds[] = $e->OccationID;
					}

					$ft = new XFiltering();
					$f  = new XFilter( 'PublicPriceName', '=', 'true' );
					$ft->AddItem( $f );
					$f = new XFilter( 'ObjectID', 'IN', $selectedCourse["CourseTemplateId"] );
					$ft->AddItem( $f );
					$f = new XFilter( 'OccationID', 'IN', join( ",", $occIds ) );
					$ft->AddItem( $f );

					$st = new XSorting();
					$s  = new XSort( 'Price', 'ASC' );
					$st->AddItem( $s );

					$incVat = EDUAPI()->REST->Organisation->GetOrganisation()["PriceIncVat"];

					$prices       = EDU()->api->GetPriceName( EDU()->get_token(), $st->ToString(), $ft->ToString() );
					$uniquePrices = array();
					foreach ( $prices as $price ) {
						$uniquePrices[ $price->Description ] = $price;
					}

					if ( count( $uniquePrices ) == 0 ) {
						$filtering = new XFiltering();
						$f         = new XFilter( 'ObjectID', '=', $selectedCourse["CourseTemplateId"] );
						$filtering->AddItem( $f );

						$f = new XFilter( 'PublicPriceName', '=', "True" );
						$filtering->AddItem( $f );

						$sorting       = new XSorting();
						$customOrder   = null;
						$customOrderBy = null;
						if ( ! empty( $attributes['order'] ) ) {
							$customOrder = $attributes['order'];
						}

						if ( ! empty( $attributes['orderby'] ) ) {
							$customOrderBy = $attributes['orderby'];
						}

						if ( $customOrderBy != null ) {
							$orderby   = explode( ' ', $customOrderBy );
							$sortorder = explode( ' ', $customOrder );
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
							$s = new XSort( 'PriceNameID', $customOrder != null ? $customOrder : 'ASC' );
							$sorting->AddItem( $s );
						}

						$edo = get_transient( 'eduadmin-objectpublicpricename_' . $selectedCourse["CourseTemplateId"] );
						if ( ! $edo ) {
							$edo = EDU()->api->GetObjectPriceName( EDU()->get_token(), $sorting->ToString(), $filtering->ToString() );
							set_transient( 'eduadmin-objectpublicpricename_' . $selectedCourse["CourseTemplateId"], $edo, 10 );
						}

						foreach ( $edo as $price ) {
							$uniquePrices[ $price->Description ] = $price;
						}
					}

					$currency = get_option( 'eduadmin-currency', 'SEK' );
					if ( 1 == count( $uniquePrices ) ) {
						$retStr .= convertToMoney( current( $uniquePrices )->Price, $currency ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ) . "\n";
					} else {
						foreach ( $uniquePrices as $price ) {
							$retStr .= sprintf( '%1$s: %2$s', $price->Description, convertToMoney( $price->Price, $currency ) ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ) . "<br />\n";
						}
					}
				}

				if ( isset( $attributes['pagetitlejs'] ) ) {
					$newTitle = $selectedCourse["CourseName"];
					$retStr   .= "
				<script type=\"text/javascript\">
				(function() {
					var title = document.title;
					document.title = '" . $newTitle . " | ' + title;
				})();
				</script>";
				}

				if ( isset( $attributes['bookurl'] ) ) {
					$surl    = get_home_url();
					$cat     = get_option( 'eduadmin-rewriteBaseUrl' );
					$baseUrl = $surl . '/' . $cat;
					$name    = ( ! empty( $selectedCourse["CourseName"] ) ? $selectedCourse["CourseName"] : $selectedCourse["InternalCourseName"] );
					$retStr  .= $baseUrl . '/' . makeSlugs( $name ) . '__' . $selectedCourse["CourseTemplateId"] . '/book/' . edu_getQueryString();
				}

				if ( isset( $attributes['courseinquiryurl'] ) ) {
					$surl    = get_home_url();
					$cat     = get_option( 'eduadmin-rewriteBaseUrl' );
					$baseUrl = $surl . '/' . $cat;
					$name    = ( ! empty( $selectedCourse["CourseName"] ) ? $selectedCourse["CourseName"] : $selectedCourse["InternalCourseName"] );
					$retStr  .= $baseUrl . '/' . makeSlugs( $name ) . '__' . $selectedCourse["CourseTemplateId"] . '/interest/' . edu_getQueryString();
				}

				if ( isset( $attributes['courseeventlist'] ) ) {
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
					$f = new XFilter( 'ObjectID', '=', $selectedCourse["CourseTemplateId"] );
					$ft->AddItem( $f );
					$f = new XFilter( 'LastApplicationDate', '>=', date( "Y-m-d H:i:s" ) );
					$ft->AddItem( $f );
					$f = new XFilter( 'CustomerID', '=', '0' );
					$ft->AddItem( $f );

					if ( ! empty( $attributes['courseeventlistfiltercity'] ) ) {
						$f = new XFilter( 'City', '=', $attributes['courseeventlistfiltercity'] );
						$ft->AddItem( $f );
					}

					$st               = new XSorting();
					$groupByCity      = get_option( 'eduadmin-groupEventsByCity', false );
					$groupByCityClass = "";
					if ( $groupByCity ) {
						$s = new XSort( 'City', 'ASC' );
						$st->AddItem( $s );
						$groupByCityClass = " noCity";
					}

					$customOrderBy      = null;
					$customOrderByOrder = null;
					if ( ! empty( $attributes['orderby'] ) ) {
						$customOrderBy = $attributes['orderby'];
					}

					if ( ! empty( $attributes['order'] ) ) {
						$customOrderByOrder = $attributes['order'];
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
					set_transient( 'eduadmin-publicpricenames', $pricenames, HOUR_IN_SECONDS );

					if ( ! empty( $pricenames ) ) {
						$events = array_filter( $events, function( $object ) {
							$pn = get_transient( 'eduadmin-publicpricenames' );
							foreach ( $pn as $subj ) {
								if ( $object->OccationID == $subj->OccationID ) {
									return true;
								}
							}

							return false;
						} );
					}

					$surl = get_home_url();
					$cat  = get_option( 'eduadmin-rewriteBaseUrl' );

					$lastCity = "";

					$showMore       = isset( $attributes['showmore'] ) && ! empty( $attributes['showmore'] ) ? $attributes['showmore'] : -1;
					$spotLeftOption = get_option( 'eduadmin-spotsLeft', 'exactNumbers' );
					$alwaysFewSpots = get_option( 'eduadmin-alwaysFewSpots', '3' );
					$spotSettings   = get_option( 'eduadmin-spotsSettings', "1-5\n5-10\n10+" );

					$baseUrl = $surl . '/' . $cat;
					$name    = ( ! empty( $selectedCourse["CourseName"] ) ? $selectedCourse["CourseName"] : $selectedCourse["InternalCourseName"] );
					$retStr  .= '<div class="eduadmin"><div class="event-table eventDays" data-eduwidget="eventlist" ' .
					            'data-objectid="' . $selectedCourse["CourseTemplateId"] .
					                   '" data-spotsleft="' . $spotLeftOption .
					                   '" data-showmore="' . $showMore .
					                   '" data-groupbycity="' . $groupByCity . '"' .
					                   '" data-spotsettings="' . $spotSettings . '"' .
					                   '" data-fewspots="' . $alwaysFewSpots . '"' .
					            ( ! empty( $attributes['courseeventlistfiltercity'] ) ? ' data-city="' . $attributes['courseeventlistfiltercity'] . '"' : '' ) .
					            ' data-fetchmonths="' . $fetchMonths . '"' .
					                   ( isset( $_REQUEST['eid'] ) ? ' data-event="' . intval( $_REQUEST['eid'] ) . '"' : '' ) .
					                   ' data-order="' . $customOrderBy . '"' .
					                   ' data-orderby="' . $customOrderByOrder . '"' .
					                   ' data-showvenue="' . get_option( 'eduadmin-showEventVenueName', false ) . '"' .
					                   ' data-eventinquiry="' . get_option( 'eduadmin-allowInterestRegEvent', false ) . '"' .
					                   '>';
					$i              = 0;
					$hasHiddenDates = false;
					$showEventVenue = get_option( 'eduadmin-showEventVenueName', false );

					$eventInterestPage = get_option( 'eduadmin-interestEventPage' );

					foreach ( $events as $ev ) {
						$spotsLeft = ( $ev->MaxParticipantNr - $ev->TotalParticipantNr );

						if ( isset( $_REQUEST['eid'] ) ) {
							if ( $ev->EventID != intval( $_REQUEST['eid'] ) ) {
								continue;
							}
						}

						ob_start();
						include( EDUADMIN_PLUGIN_PATH . '/content/template/detailTemplate/blocks/event-item.php' );
						$retStr   .= ob_get_clean();
						$lastCity = $ev->City;
						$i++;
					}
					if ( empty( $events ) ) {
						$retStr .= '<div class="noDatesAvailable"><i>' . __( "No available dates for the selected course", 'eduadmin-booking' ) . '</i></div>';
					}
					if ( $hasHiddenDates ) {
						$retStr .= "<div class=\"eventShowMore\"><a class='neutral-btn' href=\"javascript://\" onclick=\"eduDetailView.ShowAllEvents('eduev" . ( $groupByCity ? "-" . $ev->City : "" ) . "', this);\">" . __( "Show all events", 'eduadmin-booking' ) . "</a></div>";
					}
					$retStr .= '</div></div>';
				}
			}
		}
		EDU()->StopTimer( $t );

		return $retStr;
	}

	function eduadmin_get_login_widget( $attributes ) {
		$t          = EDU()->StartTimer( __METHOD__ );
		$attributes = shortcode_atts(
			array(
				'logintext'  => __( "Log in", 'eduadmin-booking' ),
				'logouttext' => __( "Log out", 'eduadmin-booking' ),
				'guesttext'  => __( "Guest", 'eduadmin-booking' ),
			),
			normalize_empty_atts( $attributes ),
			'eduadmin-loginwidget'
		);

		$surl = get_home_url();
		$cat  = get_option( 'eduadmin-rewriteBaseUrl' );

		$baseUrl = $surl . '/' . $cat;
		if ( isset( EDU()->session['eduadmin-loginUser'] ) ) {
			$user = EDU()->session['eduadmin-loginUser'];
		}
		EDU()->StopTimer( $t );

		return
			"<div class=\"eduadminLogin\" data-eduwidget=\"loginwidget\"
	data-logintext=\"" . esc_attr( $attributes['logintext'] ) . "\"
	data-logouttext=\"" . esc_attr( $attributes['logouttext'] ) . "\"
	data-guesttext=\"" . esc_attr( $attributes['guesttext'] ) . "\">" .
			"</div>";
	}

	function eduadmin_get_login_view( $attributes ) {
		$t = EDU()->StartTimer( __METHOD__ );
		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}
		$attributes = shortcode_atts(
			array(
				'logintext'  => __( "Log in", 'eduadmin-booking' ),
				'logouttext' => __( "Log out", 'eduadmin-booking' ),
				'guesttext'  => __( "Guest", 'eduadmin-booking' ),
			),
			normalize_empty_atts( $attributes ),
			'eduadmin-loginview'
		);
		EDU()->StopTimer( $t );

		return include_once( EDUADMIN_PLUGIN_PATH . "/content/template/myPagesTemplate/login.php" );
	}

	if ( is_callable( 'add_shortcode' ) ) {
		add_shortcode( "eduadmin-listview", "eduadmin_get_list_view" );
		add_shortcode( "eduadmin-detailview", "eduadmin_get_detail_view" );
		add_shortcode( "eduadmin-bookingview", "eduadmin_get_booking_view" );
		add_shortcode( 'eduadmin-detailinfo', 'eduadmin_get_detailinfo' );
		add_shortcode( 'eduadmin-loginwidget', 'eduadmin_get_login_widget' );
		add_shortcode( 'eduadmin-loginview', 'eduadmin_get_login_view' );
		add_shortcode( 'eduadmin-objectinterest', 'eduadmin_get_object_interest' );
		add_shortcode( 'eduadmin-eventinterest', 'eduadmin_get_event_interest' );
		add_shortcode( 'eduadmin-coursepublicpricename', 'eduadmin_get_course_public_pricename' );
	}