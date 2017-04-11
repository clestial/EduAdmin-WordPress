<?php
defined('ABSPATH') or die('This plugin must be run within the scope of WordPress.');
if (!function_exists('normalize_empty_atts')) {
    function normalize_empty_atts($atts) {
    	if (empty($atts))
			return $atts;
        foreach ($atts as $attribute => $value) {
            if (is_int($attribute)) {
                $atts[strtolower($value)] = true;
                unset($atts[$attribute]);
            }
        }
        return $atts;
    }
}

function eduadmin_get_list_view($attributes)
{
	$selectedTemplate = get_option('eduadmin-listTemplate', 'template_A');
	$attributes = shortcode_atts(
		array(
			'template' => $selectedTemplate,
			'category' => null,
			'subject' => null,
			'hidesearch' => false,
			'onlyevents' => false,
			'onlyempty' => false,
			'numberofevents' => null,
			'mode' => null,
			'orderby' => null,
			'order' => null
		),
		normalize_empty_atts($attributes),
		'eduadmin-listview'
	);
	$str = include(plugin_dir_path(__DIR__) . "content/template/listTemplate/" . $attributes['template'] . ".php");
	return $str;
}

function eduadmin_get_object_interest($attributes)
{
	$attributes = shortcode_atts(
		array(),
		normalize_empty_atts($attributes),
		'eduadmin-objectinterest'
	);
	$str = include(plugin_dir_path(__DIR__) . "content/template/interestRegTemplate/interestRegObject.php");
	return $str;
}

function eduadmin_get_event_interest($attributes)
{
	$attributes = shortcode_atts(
		array(),
		normalize_empty_atts($attributes),
		'eduadmin-eventinterest'
	);
	$str = include(plugin_dir_path(__DIR__) . "content/template/interestRegTemplate/interestRegEvent.php");
	return $str;
}

function eduadmin_get_detail_view($attributes)
{
	$selectedTemplate = get_option('eduadmin-detailTemplate', 'template_A');
	$attributes = shortcode_atts(
		array(
			'template' => $selectedTemplate,
			'courseid' => null,
			'customtemplate' => null
		),
		normalize_empty_atts($attributes),
		'eduadmin-detailview'
	);
	unset($_SESSION['checkEmail']);
	unset($_SESSION['needsLogin']);
	unset($_SESSION['eduadmin-loginUser']->NewCustomer);
	if (!isset($attributes['customtemplate']) || $attributes['customtemplate'] != 1)
	{
		$str = include_once(plugin_dir_path(__DIR__) . "content/template/detailTemplate/" . $attributes['template'] . ".php");
		return $str;
	}
}

function edu_no_index() {
	global $wp_query;
	$detailpage = get_option('eduadmin-detailViewPage');
	if (isset($wp_query->queried_object))
	{
		if ($detailpage !== FALSE && $detailpage == $wp_query->queried_object->ID && !isset($wp_query->query['courseId'])) {
			echo '<meta name="robots" content="noindex" />';
		}
	}
}

add_action('wp_head', 'edu_no_index');

function eduadmin_get_booking_view($attributes)
{
	if (!defined('DONOTCACHEPAGE')) {
		define('DONOTCACHEPAGE', true);
	}
	$selectedTemplate = get_option('eduadmin-bookingTemplate', 'template_A');
	$attributes = shortcode_atts(
		array(
			'template' => $selectedTemplate,
			'courseid' => null
		),
		normalize_empty_atts($attributes),
		'eduadmin-bookingview'
	);
	if (get_option('eduadmin-useLogin', false) == false || (isset($_SESSION['eduadmin-loginUser']) && ((isset($_SESSION['eduadmin-loginUser']->Contact->CustomerContactID) && $_SESSION['eduadmin-loginUser']->Contact->CustomerContactID != 0) || isset($_SESSION['eduadmin-loginUser']->NewCustomer))))
	{
		$str = include_once(plugin_dir_path(__DIR__) . "content/template/bookingTemplate/" . $attributes['template'] . ".php");
	}
	else
	{
		$str = include_once(plugin_dir_path(__DIR__) . "content/template/bookingTemplate/loginView.php");
	}

	return $str;
}

function eduadmin_get_detailinfo($attributes)
{
	global $wp_query;
	global $eduapi;
	global $edutoken;
	$attributes = shortcode_atts(
		array(
			'courseid' => null,
			'coursename' => null,
			'coursepublicname' => null,
			'courselevel' => null,
			'courseimage' => null,
			'courseimagetext' => null,
			'coursedays' => null,
			'coursestarttime' => null,
			'courseendtime' => null,
			'courseprice' => null,
			'coursedescriptionshort' => null,
			'coursedescription' => null,
			'coursegoal' => null,
			'coursetarget' => null,
			'courseprerequisites' => null,
			'courseafter' => null,
			'coursequote' => null,
			'courseeventlist' => null,
			'showmore' => null,
			'courseattributeid' => null,
			'courseeventlistfiltercity' => null,
			'pagetitlejs' => null,
			'bookurl' => null,
			'courseinquiryurl' => null,
			'order' => null,
			'orderby' => null
			//'coursesubject' => null
		),
		normalize_empty_atts($attributes),
		'eduadmin-detailinfo'
	);

	$retStr = '';

	$courseId = 0;

	if (empty($attributes['courseid']) || $attributes['courseid'] <= 0)
	{
		if (isset($wp_query->query_vars["courseId"]))
		{
			$courseId = $wp_query->query_vars["courseId"];
		}
		else
			return 'Missing courseId in attributes';
	}
	else
	{
		$courseId = $attributes['courseid'];
	}

	$apiKey = get_option('eduadmin-api-key');

	if (!$apiKey || empty($apiKey))
	{
		return 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
	}
	else
	{
		$filtering = new XFiltering();
		$f = new XFilter('ObjectID', '=', $courseId);
		$filtering->AddItem($f);

		$f = new XFilter('ShowOnWeb', '=', 'true');
		$filtering->AddItem($f);

		$edo = get_transient('eduadmin-object_' . $courseId);
		if (!$edo)
		{
			$edo = $eduapi->GetEducationObject($edutoken, '', $filtering->ToString());
			set_transient('eduadmin-object_' . $courseId, $edo, 10);
		}

		$selectedCourse = false;
		$name = "";
		foreach ($edo as $object)
		{
			$id = $object->ObjectID;
			if ($id == $courseId)
			{
				$selectedCourse = $object;
				break;
			}
		}

		if (!$selectedCourse)
		{
			return 'Course with ID ' . $courseId . ' could not be found.';
		}
		else
		{
			 if (isset($attributes['coursename']))
			 {
			 	$retStr .= $selectedCourse->ObjectName;
			 }
			 if (isset($attributes['coursepublicname']))
			 {
			 	$retStr .= $selectedCourse->PublicName;
			 }
			 if (isset($attributes['courseimage']))
			 {
			 	$retStr .= $selectedCourse->ImageUrl;
			 }
			 if (isset($attributes['coursedays']))
			 {
			 	$retStr .= $selectedCourse->Days;
			 }
			 if (isset($attributes['coursestarttime']))
			 {
			 	$retStr .= $selectedCourse->StartTime;
			 }
			 if (isset($attributes['courseendtime']))
			 {
			 	$retStr .= $selectedCourse->EndTime;
			 }
			 if (isset($attributes['coursedescriptionshort']))
			 {
			 	$retStr .= $selectedCourse->CourseDescriptionShort;
			 }
			 if (isset($attributes['coursedescription']))
			 {
			 	$retStr .= $selectedCourse->CourseDescription;
			 }
			 if (isset($attributes['coursegoal']))
			 {
			 	$retStr .= $selectedCourse->CourseGoal;
			 }
			 if (isset($attributes['coursetarget']))
			 {
			 	$retStr .= $selectedCourse->TargetGroup;
			 }
			 if (isset($attributes['courseprerequisites']))
			 {
			 	$retStr .= $selectedCourse->Prerequisites;
			 }
			 if (isset($attributes['courseafter']))
			 {
			 	$retStr .= $selectedCourse->CourseAfter;
			 }
			 if (isset($attributes['coursequote']))
			 {
			 	$retStr .= $selectedCourse->Quote;
			 }
			 if (isset($attributes['coursesubject']))
			 {

			 	$ft = new XFiltering();
				$f = new XFilter('ObjectID', '=', $selectedCourse->ObjectID);
				$ft->AddItem($f);
			 	$courseSubject = $eduapi->GetEducationSubject($edutoken, '', $ft->ToString());
				$retStr .= print_r($courseSubject, true);
			 }
			 if (isset($attributes['courselevel']))
			 {

				$ft = new XFiltering();
				$f = new XFilter('ObjectID', '=', $selectedCourse->ObjectID);
				$ft->AddItem($f);
				$courseLevel = $eduapi->GetEducationLevelObject($edutoken, '', $ft->ToString());

				if (!empty($courseLevel))
				{
			 		$retStr .= $courseLevel[0]->Name;
				}
			 }
			 if (isset($attributes['courseattributeid']))
			 {
			 	$attrid = $attributes['courseattributeid'];
				$ft = new XFiltering();
				$f = new XFilter('ObjectID', '=', $selectedCourse->ObjectID);
				$ft->AddItem($f);
				$f = new XFilter('AttributeID', '=', $attrid);
				$ft->AddItem($f);
				$objAttr = $eduapi->GetObjectAttribute($edutoken, '', $ft->ToString());
				if (!empty($objAttr))
				{
					$attr = $objAttr[0];
					switch ($attr->AttributeTypeID)
					{
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

			 if (isset($attributes['courseprice']))
			 {

				$fetchMonths = get_option('eduadmin-monthsToFetch', 6);
				if (!is_numeric($fetchMonths)) {
					$fetchMonths = 6;
				}

			 	$ft = new XFiltering();
				$f = new XFilter('PeriodStart', '<=', date("Y-m-d 23:59:59", strtotime('now +' . $fetchMonths . ' months')));
				$ft->AddItem($f);
				$f = new XFilter('PeriodEnd', '>=', date("Y-m-d H:i:s", strtotime('now')));
				$ft->AddItem($f);
				$f = new XFilter('ShowOnWeb', '=', 'true');
				$ft->AddItem($f);
				$f = new XFilter('StatusID', '=', '1');
				$ft->AddItem($f);
				$f = new XFilter('ObjectID', '=', $selectedCourse->ObjectID);
				$ft->AddItem($f);
				$f = new XFilter('LastApplicationDate', '>=', date("Y-m-d H:i:s"));
				$ft->AddItem($f);

				if (!empty($attributes['courseeventlistfiltercity']))
				{
					$f = new XFilter('City', '=', $attributes['courseeventlistfiltercity']);
					$ft->AddItem($f);
				}

				$st = new XSorting();
				$groupByCity = get_option('eduadmin-groupEventsByCity', FALSE);
				$groupByCityClass = "";
				if ($groupByCity)
				{
					$s = new XSort('City', 'ASC');
					$st->AddItem($s);
					$groupByCityClass = " noCity";
				}
				$s = new XSort('PeriodStart', 'ASC');
				$st->AddItem($s);

				$events = $eduapi->GetEvent(
					$edutoken,
					$st->ToString(),
					$ft->ToString()
				);

				$occIds = array();

				$occIds[] = -1;

				foreach ($events as $e)
				{
					$occIds[] = $e->OccationID;
				}

				$ft = new XFiltering();
				$f = new XFilter('PublicPriceName', '=', 'true');
				$ft->AddItem($f);
				$f = new XFilter('ObjectID', 'IN', $selectedCourse->ObjectID);
				$ft->AddItem($f);
				$f = new XFilter('OccationID', 'IN', join(",", $occIds));
				$ft->AddItem($f);

				$st = new XSorting();
				$s = new XSort('Price', 'ASC');
				$st->AddItem($s);

				$incVat = $eduapi->GetAccountSetting($edutoken, 'PriceIncVat') == "yes";

				$prices = $eduapi->GetPriceName($edutoken, $st->ToString(), $ft->ToString());
				$uniquePrices = Array();
				foreach ($prices as $price)
				{
					$uniquePrices[$price->Description] = $price;
				}

				$currency = get_option('eduadmin-currency', 'SEK');
				if (count($uniquePrices) == 1) {
					$retStr .= convertToMoney(current($uniquePrices)->Price, $currency) . " " . edu__($incVat ? "inc vat" : "ex vat") . "\n";
				} else {
					foreach ($uniquePrices as $price)
					{
				 		$retStr .= sprintf('%1$s: %2$s', $price->Description, convertToMoney($price->Price, $currency)) . " " . edu__($incVat ? "inc vat" : "ex vat") . "<br />\n";
					}
				}
			 }

			 if (isset($attributes['pagetitlejs']))
			 {
				$originalTitle = get_the_title();
				$newTitle = $selectedCourse->PublicName;
				$retStr .= "
				<script type=\"text/javascript\">
				(function() {
					var title = document.title;
					//title = title.replace('" . $originalTitle . "', '" . $newTitle . "');
					document.title = '" . $newTitle . " | ' + title;
				})();
				</script>";
			 }

			 if (isset($attributes['bookurl']))
			 {
			 	$surl = get_home_url();
				$cat = get_option('eduadmin-rewriteBaseUrl');
				$baseUrl = $surl . '/' . $cat;
				$name = (!empty($selectedCourse->PublicName) ? $selectedCourse->PublicName : $selectedCourse->ObjectName);
				$retStr .= $baseUrl . '/' . makeSlugs($name) . '__' . $selectedCourse->ObjectID . '/book/' . edu_getQueryString();
			 }

			 if (isset($attributes['courseinquiryurl']))
			 {
			 	$surl = get_home_url();
				$cat = get_option('eduadmin-rewriteBaseUrl');
				$baseUrl = $surl . '/' . $cat;
				$name = (!empty($selectedCourse->PublicName) ? $selectedCourse->PublicName : $selectedCourse->ObjectName);
				$retStr .= $baseUrl . '/' . makeSlugs($name) . '__' . $selectedCourse->ObjectID . '/interest/' . edu_getQueryString();
			 }

			 if (isset($attributes['courseeventlist']))
			 {
				$fetchMonths = get_option('eduadmin-monthsToFetch', 6);
				if (!is_numeric($fetchMonths)) {
					$fetchMonths = 6;
				}

			 	$ft = new XFiltering();
				$f = new XFilter('PeriodStart', '<=', date("Y-m-d 23:59:59", strtotime('now +' . $fetchMonths . ' months')));
				$ft->AddItem($f);
				$f = new XFilter('PeriodEnd', '>=', date("Y-m-d H:i:s", strtotime('now')));
				$ft->AddItem($f);
				$f = new XFilter('ShowOnWeb', '=', 'true');
				$ft->AddItem($f);
				$f = new XFilter('StatusID', '=', '1');
				$ft->AddItem($f);
				$f = new XFilter('ObjectID', '=', $selectedCourse->ObjectID);
				$ft->AddItem($f);
				$f = new XFilter('LastApplicationDate', '>=', date("Y-m-d H:i:s"));
				$ft->AddItem($f);
				$f = new XFilter('CustomerID', '=', '0');
				$ft->AddItem($f);

				if (!empty($attributes['courseeventlistfiltercity']))
				{
					$f = new XFilter('City', '=', $attributes['courseeventlistfiltercity']);
					$ft->AddItem($f);
				}

				$st = new XSorting();
				$groupByCity = get_option('eduadmin-groupEventsByCity', FALSE);
				$groupByCityClass = "";
				if ($groupByCity)
				{
					$s = new XSort('City', 'ASC');
					$st->AddItem($s);
					$groupByCityClass = " noCity";
				}

				$customOrderBy = null;
				$customOrderByOrder = null;
				if (!empty($attributes['orderby']))
				{
					$customOrderBy = $attributes['orderby'];
				}

				if (!empty($attributes['order']))
				{
					$customOrderByOrder = $attributes['order'];
				}

				if ($customOrderBy != null)
				{
					$orderby = explode(' ', $customOrderBy);
					$sortorder = explode(' ', $customOrderByOrder);
					foreach ($orderby as $od => $v)
					{
						if (isset($sortorder[$od]))
							$or = $sortorder[$od];
						else
							$or = "ASC";

						$s = new XSort($v, $or);
						$st->AddItem($s);
					}
				}
				else
				{
					$s = new XSort('PeriodStart', 'ASC');
					$st->AddItem($s);
				}

				$events = $eduapi->GetEvent(
					$edutoken,
					$st->ToString(),
					$ft->ToString()
				);

				$occIds = array();
				$occIds[] = -1;

                $eventIds = array();
                $eventIds[] = -1;

				foreach ($events as $e)
				{
					$occIds[] = $e->OccationID;
                    $eventIds[] = $e->EventID;
				}

                 $ft = new XFiltering();
                 $f = new XFilter('EventID', 'IN', join(",", $eventIds));
                 $ft->AddItem($f);

                 $eventDays = $eduapi->GetEventDate($edutoken, '', $ft->ToString());

                 $eventDates = array();
                 foreach ($eventDays as $ed)
                 {
                     $eventDates[$ed->EventID][] = $ed;
                 }

				$ft = new XFiltering();
				$f = new XFilter('PublicPriceName', '=', 'true');
				$ft->AddItem($f);
				$f = new XFilter('OccationID', 'IN', join(",", $occIds));
				$ft->AddItem($f);

				$st = new XSorting();
				$s = new XSort('Price', 'ASC');
				$st->AddItem($s);

				$pricenames = $eduapi->GetPriceName($edutoken, $st->ToString(), $ft->ToString());
				set_transient('eduadmin-publicpricenames', $pricenames, HOUR_IN_SECONDS);

				if (!empty($pricenames))
				{
					$events = array_filter($events, function($object) {
						$pn = get_transient('eduadmin-publicpricenames');
						foreach ($pn as $subj)
						{
							if ($object->OccationID == $subj->OccationID)
							{
								return true;
							}
						}
						return false;
					});
				}

				$surl = get_home_url();
				$cat = get_option('eduadmin-rewriteBaseUrl');

				$lastCity = "";

				$showMore = isset($attributes['showmore']) && !empty($attributes['showmore']) ? $attributes['showmore'] : -1;
				$spotLeftOption = get_option('eduadmin-spotsLeft', 'exactNumbers');

				$baseUrl = $surl . '/' . $cat;
				$name = (!empty($selectedCourse->PublicName) ? $selectedCourse->PublicName : $selectedCourse->ObjectName);
				$retStr .= '<div class="eduadmin"><div class="event-table eventDays" data-eduwidget="eventlist" ' .
				'data-objectid="' . $selectedCourse->ObjectID .
				'" data-spotsleft="' . $spotLeftOption .
				'" data-showmore="' . $showMore .
				'" data-groupbycity="' . $groupByCity . '"' .
				'" data-spotsettings="' . get_option('eduadmin-spotsSettings', "1-5\n5-10\n10+") . '"' .
				'" data-fewspots="' . get_option('eduadmin-alwaysFewSpots', "3") . '"' .
				(!empty($attributes['courseeventlistfiltercity']) ? ' data-city="' . $attributes['courseeventlistfiltercity'] . '"' : '') .
				' data-fetchmonths="' . $fetchMonths . '"' .
				(isset($_REQUEST['eid']) ? ' data-event="' . $_REQUEST['eid'] . '"' : '') .
				' data-order="' . $customOrderBy . '"' .
				' data-orderby="' . $customOrderByOrder . '"' .
				' data-showvenue="' . get_option('eduadmin-showEventVenueName', false) . '"' .
				' data-eventinquiry="' . get_option('eduadmin-allowInterestRegEvent', false) . '"' .
				'>';
				$i = 0;
				$hasHiddenDates = false;

				foreach ($events as $ev)
				{
					$spotsLeft = ($ev->MaxParticipantNr - $ev->TotalParticipantNr);

					if (isset($_REQUEST['eid']))
					{
						if ($ev->EventID != $_REQUEST['eid'])
						{
							continue;
						}
					}

					if ($groupByCity && $lastCity != $ev->City)
					{
						$i = 0;
						if ($hasHiddenDates)
						{
							$retStr .= "<div class=\"eventShowMore\"><a href=\"javascript://\" onclick=\"eduDetailView.ShowAllEvents('eduev-" . $lastCity . "', this);\">" . edu__("Show all events") . "</a></div>";
						}
						$hasHiddenDates = false;
						$retStr .= '<div class="eventSeparator">' . $ev->City . '</div>';
					}

					if ($showMore > 0 && $i >= $showMore)
					{
						$hasHiddenDates = true;
					}

					$eventInterestPage = get_option('eduadmin-interestEventPage');

					$retStr .= '<div data-groupid="eduev' . ($groupByCity ? "-" . $ev->City : "") . '" class="eventItem' . ($i % 2 == 0 ? " evenRow" : " oddRow") . ($showMore > 0 && $i >= $showMore ? " showMoreHidden" : "") . '">';
					$retStr .= '
					<div class="eventDate' . $groupByCityClass . '">
						' . (isset($eventDates[$ev->EventID]) ? GetLogicalDateGroups($eventDates[$ev->EventID]) : GetOldStartEndDisplayDate($ev->PeriodStart, $ev->PeriodEnd)) . '
						' . (!isset($eventDates[$ev->EventID]) ? '<span class="eventTime">, ' . date("H:i", strtotime($ev->PeriodStart)) . ' - ' . date("H:i", strtotime($ev->PeriodEnd)) . '</span>' : '') . '
					</div>
					'. (!$groupByCity ?
					'<div class="eventCity">
						' . $ev->City . '
					</div>' : '') .
					'<div class="eventStatus' . $groupByCityClass . '">
					' .
						getSpotsLeft($spotsLeft, $ev->MaxParticipantNr)
					 . '
					</div>
					<div class="eventBook' . $groupByCityClass . '">
					' . ($ev->MaxParticipantNr == 0 || $spotsLeft > 0 ?

						'<a class="book-link" href="' . $baseUrl . '/' . makeSlugs($name) . '__' . $selectedCourse->ObjectID . '/book/?eid=' . $ev->EventID . edu_getQueryString("&", array('eid')) . '" style="text-align: center;">' . edu__("Book") . '</a>'
					:
						($eventInterestPage != false ? '<a class="inquiry-link" href="' . $baseUrl . '/' . makeSlugs($name) . '__' . $selectedCourse->ObjectID . '/book/interest/?eid=' . $ev->EventID . edu_getQueryString("&") . '">' . edu__("Inquiry") . '</a>' : '') .
						'<i class="fullBooked">' . edu__("Full") . '</i>'
					) . '
					</div>';
					$retStr .= '</div><!-- /eventitem -->';
					$lastCity = $ev->City;
					$i++;
				}
				if (empty($events))
				{
					$retStr .= '<div class="noDatesAvailable"><i>' . edu__("No available dates for the selected course") . '</i></div>';
				}
				if ($hasHiddenDates)
				{
					$retStr .= "<div class=\"eventShowMore\"><a href=\"javascript://\" onclick=\"eduDetailView.ShowAllEvents('eduev" . ($groupByCity ? "-" . $ev->City : "") . "', this);\">" . edu__("Show all events") . "</a></div>";
				}
				$retStr .= '</div></div>';
			 }
		}
	}
	return $retStr;
}

function eduadmin_get_login_widget($attributes)
{
	$attributes = shortcode_atts(
		array(
			'logintext' => 	edu__("Log in"),
			'logouttext' => edu__("Log out"),
			'guesttext' => 	edu__("Guest")
		),
		normalize_empty_atts($attributes),
		'eduadmin-loginwidget'
	);

	$surl = get_home_url();
	$cat = get_option('eduadmin-rewriteBaseUrl');

	$baseUrl = $surl . '/' . $cat;
	if (isset($_SESSION['eduadmin-loginUser']))
		$user = $_SESSION['eduadmin-loginUser'];

	return
	"<div class=\"eduadminLogin\" data-eduwidget=\"loginwidget\"
	data-logintext=\"" . esc_attr($attributes['logintext']) . "\"
	data-logouttext=\"" . esc_attr($attributes['logouttext']) . "\"
	data-guesttext=\"" . esc_attr($attributes['guesttext']) . "\">" .
	"</div>";
}

function eduadmin_get_login_view($attributes)
{
	if (!defined('DONOTCACHEPAGE')) {
		define('DONOTCACHEPAGE', true);
	}
	$attributes = shortcode_atts(
		array(
			'logintext' => 	edu__("Log in"),
			'logouttext' => edu__("Log out"),
			'guesttext' => 	edu__("Guest")
		),
		normalize_empty_atts($attributes),
		'eduadmin-loginview'
	);

	return include_once(plugin_dir_path(__DIR__) . "content/template/myPagesTemplate/login.php");
}

add_shortcode("eduadmin-listview", "eduadmin_get_list_view");
add_shortcode("eduadmin-detailview", "eduadmin_get_detail_view");
add_shortcode("eduadmin-bookingview", "eduadmin_get_booking_view");
add_shortcode('eduadmin-detailinfo', 'eduadmin_get_detailinfo');
add_shortcode('eduadmin-loginwidget', 'eduadmin_get_login_widget');
add_shortcode('eduadmin-loginview', 'eduadmin_get_login_view');
add_shortcode('eduadmin-objectinterest', 'eduadmin_get_object_interest');
add_shortcode('eduadmin-eventinterest', 'eduadmin_get_event_interest');