<?php
date_default_timezone_set('UTC');
if(!function_exists('edu_api_listview_eventlist'))
{
	function edu_api_listview_eventlist($request)
	{
		header("Content-type: text/html; charset=UTF-8");
		global $eduapi;

		$edutoken = edu_decrypt("edu_js_token_crypto", $request["token"]);
		$_SESSION['eduadmin-phrases'] = $request['phrases'];

		$sorting = new XSorting();
		$s = new XSort('SubjectName', 'ASC');
		$sorting->AddItem($s);
		$subjects = $eduapi->GetEducationSubject($edutoken, $sorting->ToString(), '');

		$filterCourses = array();

		if(!empty($request['subject']))
		{
			foreach($subjects as $subject)
			{
				if($subject->SubjectID == $request['subject'])
				{
					if(!in_array($subject->ObjectID, $filterCourses))
					{
						$filterCourses[] = $subject->ObjectID;
					}
				}
			}
		}

		$filtering = new XFiltering();
		$f = new XFilter('ShowOnWeb','=','true');
		$filtering->AddItem($f);

		if(isset($request['category']))
		{
			$f = new XFilter('CategoryID', '=', $request['category']);
			$filtering->AddItem($f);
		}

		if(!empty($filterCourses))
		{
			$f = new XFilter('ObjectID', 'IN', join(',', $filterCourses));
			$filtering->AddItem($f);
		}

		if(isset($request['city']))
		{
			$f = new XFilter('LocationID', '=', $request['city']);
			$filtering->AddItem($f);
		}

		if(isset($request['courselevel']))
		{
			$f = new XFilter('EducationLevelID', '=', $request['courselevel']);
			$filtering->AddItem($f);
		}

		$fetchMonths = $request['fetchmonths'];
		if(!is_numeric($fetchMonths)) {
			$fetchMonths = 6;
		}

		$edo = $eduapi->GetEducationObjectV2($edutoken, '', $filtering->ToString(), false);

		if(!empty($request['search']))
		{
			$edo = array_filter($edo, function($object) use(&$request) {
				$name = (!empty($object->PublicName) ? $object->PublicName : $object->ObjectName);
				$descrMatch = stripos($object->CourseDescription, $request['search']) !== FALSE;
				$shortDescrMatch = stripos($object->CourseDescriptionShort, $request['search']) !== FALSE;
				$nameMatch = stripos($name, $request['search']) !== FALSE;
				/*echo "#namn!#" . $name . " state: ";
				echo $nameMatch ? "true" : "false" . "#!#<br />";
				echo "#besk!#" . $object->CourseDescription . " state: ";
				echo $descrMatch ? "true" : "false" . "#!#<br />";
				echo "#kort!#" . $object->CourseDescriptionShort . " state: ";
				echo $shortDescrMatch ? "true" : "false" . "#!#<br /><br />";*/
				return ($nameMatch || $descrMatch || $shortDescrMatch);
			});
		}

		$filtering = new XFiltering();
		$f = new XFilter('ShowOnWeb','=','true');
		$filtering->AddItem($f);

		$f = new XFilter('PeriodStart','<=', date("Y-m-d 23:59:59", strtotime("now +" . $fetchMonths . " months")));
		$filtering->AddItem($f);
		$f = new XFilter('PeriodEnd', '>=', date("Y-m-d H:i:s", strtotime("now")));
		$filtering->AddItem($f);
		$f = new XFilter('StatusID','=','1');
		$filtering->AddItem($f);

		$f = new XFilter('LastApplicationDate','>=',date("Y-m-d H:i:s"));
		$filtering->AddItem($f);

		if(!empty($filterCourses))
		{
			$f = new XFilter('ObjectID', 'IN', join(',', $filterCourses));
			$filtering->AddItem($f);
		}

		if(isset($request['city']))
		{
			$f = new XFilter('LocationID', '=', $request['city']);
			$filtering->AddItem($f);
		}

		if(isset($request['subject']))
		{
			$f = new XFilter('SubjectID', '=', $request['subject']);
			$filtering->AddItem($f);
		}

		if(isset($request['category']))
		{
			$f = new XFilter('CategoryID', '=', $request['category']);
			$filtering->AddItem($f);
		}

		$f = new XFilter('CustomerID','=','0');
		$filtering->AddItem($f);

		$f = new XFilter('ParentEventID', '=', '0');
		$filtering->AddItem($f);

		$sorting = new XSorting();

		$customOrderBy = null;
		$customOrderByOrder = null;
		if(!empty($request['orderby']))
		{
			$customOrderBy = $request['orderby'];
		}

		if(!empty($request['order']))
		{
			$customOrderByOrder = $request['order'];
		}

		if($customOrderBy != null)
		{
			$orderby = explode(' ', $customOrderBy);
			$sortorder = explode(' ', $customOrderByOrder);
			foreach($orderby as $od => $v)
			{
				if(isset($sortorder[$od]))
					$or = $sortorder[$od];
				else
					$or = "ASC";

				$s = new XSort($v, $or);
				$sorting->AddItem($s);
			}
		}
		else
		{
			$s = new XSort('PeriodStart', 'ASC');
			$sorting->AddItem($s);
		}

		$ede = $eduapi->GetEvent($edutoken, $sorting->ToString(), $filtering->ToString());

		$ede = array_filter($ede, function($object) use (&$edo) {
			$pn = $edo;
			foreach($pn as $subj)
			{
				if($object->ObjectID == $subj->ObjectID)
				{
					return true;
				}
			}
			return false;
		});

		$occIds = array();
		$evIds = array();

		foreach($ede as $e)
		{
			$occIds[] = $e->OccationID;
			$evIds[] = $e->EventID;
		}

		$ft = new XFiltering();
		$f = new XFilter('EventID', 'IN', join(",", $evIds));
		$ft->AddItem($f);

		$eventDays = $eduapi->GetEventDate($edutoken, '', $ft->ToString());

		$eventDates = array();
		foreach($eventDays as $ed)
		{
			$eventDates[$ed->EventID][] = $ed;
		}

		$ft = new XFiltering();
		$f = new XFilter('PublicPriceName', '=', 'true');
		$ft->AddItem($f);
		$f = new XFilter('OccationID', 'IN', join(",", $occIds));
		$ft->AddItem($f);
		$pricenames = $eduapi->GetPriceName($edutoken,'',$ft->ToString());

		if(!empty($pricenames))
		{
			$ede = array_filter($ede, function($object) use (&$pricenames) {
				$pn = $pricenames;
				foreach($pn as $subj)
				{
					if($object->OccationID == $subj->OccationID)
					{
						return true;
					}
				}
				return false;
			});
		}

		foreach($ede as $object)
		{
			foreach($edo as $course)
			{
				$id = $course->ObjectID;
				if($id == $object->ObjectID)
				{
					$object->Days = $course->Days;
					$object->StartTime = $course->StartTime;
					$object->EndTime = $course->EndTime;
					$object->CategoryID = $course->CategoryID;
					$object->PublicName = $course->PublicName;
					break;
				}
			}

			foreach($pricenames as $pn)
			{
				$id = $pn->OccationID;
				if($id == $object->OccationID)
				{
					$object->Price = $pn->Price;
					$object->PriceNameVat = $pn->PriceNameVat;
					break;
				}
			}
		}

		if($request['template'] == "A")
		{
			echo edu_api_listview_eventlist_template_A($ede, $eventDates, $request);
		}
		else if($request['template'] == "B")
		{
			echo edu_api_listview_eventlist_template_B($ede, $eventDates, $request);
		}

		#echo json_encode($ede);
	}
}

if(!function_exists('edu_api_listview_eventlist_template_A'))
{
	function edu_api_listview_eventlist_template_A($data, $eventDates, $request)
	{
		global $eduapi;
		$edutoken = edu_decrypt("edu_js_token_crypto", $request["token"]);
		$spotLeftOption = $request['spotsleft'];
		$alwaysFewSpots = $request['fewspots'];
		$spotSettings = $request['spotsettings'];
		$showImages = $request['showimages'];

		$showCourseDays = $request['showcoursedays'];
		$showCourseTimes = $request['showcoursetimes'];

		$showVenue = $request['showvenue'];

		$incVat = $eduapi->GetAccountSetting($edutoken, 'PriceIncVat') == "yes";

		$surl = $request['baseUrl'];
		$cat = $request['courseFolder'];
		$numberOfEvents = $request['numberofevents'];
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
					'orderby'
				);

		ob_start();


		$currentEvents = 0;

		foreach($data as $object)
		{
			if($numberOfEvents != null && $numberOfEvents > 0 && $currentEvents >= $numberOfEvents)
			{
				break;
			}
			$name = (!empty($object->PublicName) ? $object->PublicName : $object->ObjectName);
		?>
			<div class="objectItem">
				<?php if($showImages && !empty($object->ImageUrl)) { ?>
				<div class="objectImage" onclick="location.href = '<?php echo $baseUrl; ?>/<?php echo makeSlugs($name); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString("&", $removeItems); ?>';" style="background-image: url('<?php echo $object->ImageUrl; ?>');"></div>
				<?php } ?>
				<div class="objectInfoHolder">
					<div class="objectName">
						<a href="<?php echo $baseUrl; ?>/<?php echo makeSlugs($name); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString("&", $removeItems); ?>"><?php
							echo htmlentities($name);
						?></a>
					</div>
					<div class="objectDescription"><?php

				$spotsLeft = ($object->MaxParticipantNr - $object->TotalParticipantNr);
				echo /*isset($eventDates[$object->EventID]) ? edu_GetLogicalDateGroups($eventDates[$object->EventID], true, $object, true) :*/ edu_GetOldStartEndDisplayDate($object->PeriodStart, $object->PeriodEnd, true);

				if(!empty($object->City))
				{
					echo " <span class=\"cityInfo\">";
					echo $object->City;
					if($showVenue && !empty($object->AddressName))
						echo "<span class=\"venueInfo\">, " . $object->AddressName . "</span>";
					echo "</span>";
				}

				if(isset($object->Days) && $object->Days > 0) {

					echo
					"<div class=\"dayInfo\">" .
						($showCourseDays ? sprintf(edu_n('%1$d day', '%1$d days', $object->Days), $object->Days) .
						($showCourseTimes && $object->StartTime != '' && $object->EndTime != '' && !isset($eventDates[$object->EventID]) ? ', ' : '') : '') .
						($showCourseTimes && $object->StartTime != '' && $object->EndTime != '' && !isset($eventDates[$object->EventID]) ? date("H:i", strtotime($object->StartTime)) .
						' - ' .
						date("H:i", strtotime($object->EndTime)) : '') .
					"</div>";
				}

				if($request['showcourseprices'] && isset($object->Price)) {
					echo "<div class=\"priceInfo\">" . sprintf(edu__('From %1$s'), edu_ConvertToMoney($object->Price, $request['currency'])) . " " . edu__($incVat ? "inc vat" : "ex vat") . "</div> ";
				}
				echo "<span class=\"spotsLeftInfo\">" . edu_getSpotsLeft($spotsLeft, $object->MaxParticipantNr, $spotLeftOption, $spotSettings, $alwaysFewSpots) . "</span>";


		?></div>
					<div class="objectBook">
						<a class="readMoreButton" href="<?php echo $baseUrl; ?>/<?php echo makeSlugs($name); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString("&", $removeItems); ?>"><?php edu_e("Read more"); ?></a><br />
							<?php
						if($spotsLeft > 0 || $object->MaxParticipantNr == 0) {
						?>
							<a class="bookButton" href="<?php echo $baseUrl; ?>/<?php echo makeSlugs($name); ?>__<?php echo $object->ObjectID;?>/book/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString("&", $removeItems); ?>"><?php edu_e("Book"); ?></a>
						<?php
						} else {
						?>
							<i class="fullBooked"><?php edu_e("Full"); ?></i>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php
			$currentEvents++;
		}
		$out = ob_get_clean();
		return $out;
	}
}

if(!function_exists('edu_api_listview_eventlist_template_B'))
{
	function edu_api_listview_eventlist_template_B($data, $eventDates, $request)
	{
		global $eduapi;
		$edutoken = edu_decrypt("edu_js_token_crypto", $request["token"]);

		$spotLeftOption = $request['spotsleft'];
		$alwaysFewSpots = $request['fewspots'];
		$spotSettings = $request['spotsettings'];
		$showImages = $request['showimages'];

		$showCourseDays = $request['showcoursedays'];
		$showCourseTimes = $request['showcoursetimes'];

		$showVenue = $request['showvenue'];

		$incVat = $eduapi->GetAccountSetting($edutoken, 'PriceIncVat') == "yes";

		$surl = $request['baseUrl'];
		$cat = $request['courseFolder'];
		$numberOfEvents = $request['numberofevents'];
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
					'orderby'
				);
		ob_start();

		$currentEvents = 0;

		foreach($data as $object)
		{
			if($numberOfEvents != null && $numberOfEvents > 0 && $currentEvents >= $numberOfEvents)
			{
				break;
			}
			$name = (!empty($object->PublicName) ? $object->PublicName : $object->ObjectName);
		?>
			<div class="objectBlock brick">
				<?php if($showImages && !empty($object->ImageUrl)) { ?>
				<div class="objectImage" onclick="location.href = '<?php echo $baseUrl; ?>/<?php echo makeSlugs($name); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString("&", $removeItems); ?>';" style="background-image: url('<?php echo $object->ImageUrl; ?>');"></div>
				<?php } ?>
				<div class="objectName">
					<a href="<?php echo $baseUrl; ?>/<?php echo makeSlugs($name); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString("&", $removeItems); ?>"><?php
						echo htmlentities($name);
					?></a>
				</div>
				<div class="objectDescription"><?php

				$spotsLeft = ($object->MaxParticipantNr - $object->TotalParticipantNr);
				echo /*isset($eventDates[$object->EventID]) ? edu_GetLogicalDateGroups($eventDates[$object->EventID]) :*/ edu_GetOldStartEndDisplayDate($object->PeriodStart, $object->PeriodEnd, true);

				if(!empty($object->City))
				{
					echo " <span class=\"cityInfo\">";
					echo $object->City;
					if($showVenue && !empty($object->AddressName))
						echo ", " . $object->AddressName;
					echo "</span>";
				}

				if($object->Days > 0) {
					echo
					"<div class=\"dayInfo\">" .
						($showCourseDays ? sprintf(edu_n('%1$d day', '%1$d days', $object->Days), $object->Days) . ($showCourseTimes ? ', ' : '') : '') .
						($showCourseTimes ? date("H:i", strtotime($object->StartTime)) .
						' - ' .
						date("H:i", strtotime($object->EndTime)) : '') .
					"</div>";
				}

				if($request['showcourseprices'] && isset($object->Price)) {
					echo "<div class=\"priceInfo\">" . sprintf(edu__('From %1$s'), edu_ConvertToMoney($object->Price, $request['currency'])) . " " . edu__($incVat ? "inc vat" : "ex vat") . "</div> ";
				}
				echo '<br />' . edu_getSpotsLeft($spotsLeft, $object->MaxParticipantNr, $spotLeftOption, $spotSettings, $alwaysFewSpots);
		?></div>
				<div class="objectBook">
					<a class="readMoreButton" href="<?php echo $baseUrl; ?>/<?php echo makeSlugs($name); ?>__<?php echo $object->ObjectID; ?>/?eid=<?php echo $object->EventID; ?><?php echo edu_getQueryString("&", $removeItems); ?>"><?php edu_e("Read more"); ?></a>
				</div>
			</div>
		<?php
			$currentEvents++;
		}
		$out = ob_get_clean();
		return $out;
	}
}

if(isset($_REQUEST['module']) && $_REQUEST['module'] == "listview_eventlist")
{
	echo edu_api_listview_eventlist($_REQUEST);
}