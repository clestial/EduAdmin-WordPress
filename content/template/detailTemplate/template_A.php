<?php
ob_start();
global $wp_query;
global $eduapi;
global $edutoken;
$apiKey = get_option('eduadmin-api-key');

if(!$apiKey || empty($apiKey))
{
	echo 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
}
else
{
	$edo = get_transient('eduadmin-listCourses');
	if(!$edo)
	{
		$filtering = new XFiltering();
		$f = new XFilter('ShowOnWeb','=','true');
		$filtering->AddItem($f);

		$edo = $eduapi->GetEducationObject($edutoken, '', $filtering->ToString());
		set_transient('eduadmin-listCourses', $edo, 6 * HOUR_IN_SECONDS);
	}

	$surl = get_home_url();
	$cat = get_option('eduadmin-rewriteBaseUrl');
	$baseUrl = $surl . '/' . $cat;

	$selectedCourse = false;
	$name = "";
	foreach($edo as $object)
	{
		$name = (!empty($object->PublicName) ? $object->PublicName : $object->ObjectName);
		$id = $object->ObjectID;
		if(makeSlugs($name) == $wp_query->query_vars['courseSlug'] && $id == $wp_query->query_vars["courseId"])
		{
			$selectedCourse = $object;
			break;
		}
	}
	if(!$selectedCourse)
	{
		?>
		<script type="text/javascript">location.href = '<?php echo $baseUrl; ?>';</script>
		<?php
		die();
	}

	$fetchMonths = get_option('eduadmin-monthsToFetch', 6);
	if(!is_numeric($fetchMonths)) {
		$fetchMonths = 6;
	}

	$ft = new XFiltering();
	$f = new XFilter('PeriodStart', '<=', date("Y-m-d 00:00:00", strtotime('now +' . $fetchMonths . ' months')));
	$ft->AddItem($f);
	$f = new XFilter('PeriodEnd', '>=', date("Y-m-d 00:00:00", strtotime('now +1 day')));
	$ft->AddItem($f);
	$f = new XFilter('ShowOnWeb', '=', 'true');
	$ft->AddItem($f);
	$f = new XFilter('StatusID', '=', '1');
	$ft->AddItem($f);
	$f = new XFilter('ObjectID', '=', $selectedCourse->ObjectID);
	$ft->AddItem($f);
	$f = new XFilter('LastApplicationDate', '>=', date("Y-m-d 00:00:00"));
	$ft->AddItem($f);

	$f = new XFilter('CustomerID','=','0');
	$ft->AddItem($f);

	$st = new XSorting();
	$groupByCity = get_option('eduadmin-groupEventsByCity', FALSE);
	$groupByCityClass = "";
	if($groupByCity)
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

	$eventIds = array();
	$eventIds[] = -1;

	foreach($events as $e)
	{
		$occIds[] = $e->OccationID;
		$eventIds[] = $e->EventID;
	}

	$ft = new XFiltering();
	$f = new XFilter('EventID', 'IN', join(",", $eventIds));
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
	set_transient('eduadmin-publicpricenames', $pricenames, HOUR_IN_SECONDS);

	if(!empty($pricenames))
	{
		$events = array_filter($events, function($object) {
			$pn = get_transient('eduadmin-publicpricenames');
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

	$courseLevel = get_transient('eduadmin-courseLevel-' . $selectedCourse->ObjectID);
	if(!$courseLevel)
	{
		$ft = new XFiltering();
		$f = new XFilter('ObjectID', '=', $selectedCourse->ObjectID);
		$ft->AddItem($f);
		$courseLevel = $eduapi->GetEducationLevelObject($edutoken, '', $ft->ToString());
		set_transient('eduadmin-courseLevel-' . $selectedCourse->ObjectID, $courseLevel, HOUR_IN_SECONDS);
	}

	$lastCity = "";

	$incVat = $eduapi->GetAccountSetting($edutoken, 'PriceIncVat') == "yes";

	$showHeaders = get_option('eduadmin-showDetailHeaders', true);
?>
<div class="eduadmin">
	<a href="../" class="backLink"><?php edu_e("« Go back"); ?></a>
	<div class="title">
		<img src="<?php echo $selectedCourse->ImageUrl; ?>" class="courseImage" />
		<h1 class="courseTitle"><?php echo $name; ?> <small><?php echo (!empty($courseLevel) ? $courseLevel[0]->Name : ""); ?></small></h1>
	</div>
	<hr />
	<div class="textblock">
		<?php if(!empty($selectedCourse->CourseDescription)) { ?>
			<?php if($showHeaders) { ?>
		<h3><?php edu_e("Course description"); ?></h3>
			<?php } ?>
		<div>
		<?php
			echo $selectedCourse->CourseDescription;
		?>
		</div>
		<?php } ?>
		<?php if(!empty($selectedCourse->CourseGoal)) { ?>
			<?php if($showHeaders) { ?>
		<h3><?php edu_e("Course goal"); ?></h3>
			<?php } ?>
		<div>
		<?php
			echo $selectedCourse->CourseGoal;
		?>
		</div>
		<?php } ?>
		<?php if(!empty($selectedCourse->TargetGroup)) { ?>
			<?php if($showHeaders) { ?>
		<h3><?php edu_e("Target group"); ?></h3>
			<?php } ?>
		<div>
		<?php
			echo $selectedCourse->TargetGroup;
		?>
		</div>
		<?php } ?>
		<?php if(!empty($selectedCourse->Prerequisites)) { ?>
			<?php if($showHeaders) { ?>
		<h3><?php edu_e("Prerequisites"); ?></h3>
			<?php } ?>
		<div>
		<?php
			echo $selectedCourse->Prerequisites;
		?>
		</div>
		<?php } ?>
		<?php if(!empty($selectedCourse->CourseAfter)) { ?>
			<?php if($showHeaders) { ?>
		<h3><?php edu_e("After the course"); ?></h3>
			<?php } ?>
		<div>
		<?php
			echo $selectedCourse->CourseAfter;
		?>
		</div>
		<?php } ?>
		<?php if(!empty($selectedCourse->Quote)) { ?>
			<?php if($showHeaders) { ?>
		<h3><?php edu_e("Quotes"); ?></h3>
			<?php } ?>
		<div>
		<?php
			echo $selectedCourse->Quote;
		?>
		</div>
		<?php } ?>
	</div>
	<div class="eventInformation">
		<?php if(!empty($selectedCourse->StartTime) && !empty($selectedCourse->EndTime)) { ?>
		<h3><?php edu_e("Time"); ?></h3>
		<?php
			echo ($selectedCourse->Days > 0 ? sprintf(edu_n('%1$d day', '%1$d days', $selectedCourse->Days), $selectedCourse->Days) . ', ' : '') . date("H:i", strtotime($selectedCourse->StartTime)) . ' - ' . date("H:i", strtotime($selectedCourse->EndTime));
		?>
		<?php } ?>
		<?php

		$occIds = Array();
		$occIds[] = -1;
		foreach($events as $ev)
		{
			$occIds[] = $ev->OccationID;
		}

		$ft = new XFiltering();
		$f = new XFilter('PublicPriceName', '=', 'true');
		$ft->AddItem($f);
		$f = new XFilter('OccationID', 'IN', join(',', $occIds));
		$ft->AddItem($f);

		$st = new XSorting();
		$s = new XSort('Price', 'ASC');
		$st->AddItem($s);

		$prices = $eduapi->GetPriceName($edutoken, $st->ToString(), $ft->ToString());
		$uniquePrices = Array();

		foreach($prices as $price)
		{
			$uniquePrices[$price->Description] = $price;
		}

		if(!empty($prices)) {
		?>
		<h3><?php edu_e("Price"); ?></h3>
		<?php
			$currency = get_option('eduadmin-currency', 'SEK');
			// PriceNameVat
			foreach($uniquePrices as $price) {
		?>
		<?php echo sprintf('%1$s: %2$s', $price->Description, convertToMoney($price->Price, $currency)) . " " . edu__($incVat ? "inc vat" : "ex vat"); ?><br />
		<?php
			}
		} ?>
	</div>
<?php
$showEventVenue = get_option('eduadmin-showEventVenueName', false);
?>
	<div class="event-table eventDays"
		data-eduwidget="eventlist"
		data-objectid="<?php echo esc_attr($selectedCourse->ObjectID); ?>"
		data-spotsleft="<?php echo get_option('eduadmin-spotsLeft', 'exactNumbers'); ?>"
		data-spotsettings="<?php echo get_option('eduadmin-spotsSettings', "1-5\n5-10\n10+"); ?>"
		data-fewspots="<?php echo get_option('eduadmin-alwaysFewSpots', "3"); ?>"
		data-showmore="0"
		data-groupbycity="<?php echo $groupByCity; ?>"
		data-fetchmonths="<?php echo $fetchMonths; ?>"
		<?php echo (isset($_REQUEST['eid']) ? ' data-event="' . $_REQUEST['eid'] . '"' : ''); ?>
		data-showvenue="<?php echo @esc_attr($showEventVenue); ?>"
		data-eventinquiry="<?php echo @esc_attr(get_option('eduadmin-allowInterestRegEvent', false)); ?>"
	>
	<?php
	$i = 0;
	if(!empty($prices))
	{
		foreach($events as $ev)
		{
			if($groupByCity && $lastCity != $ev->City)
			{
				$i = 0;
				echo '<div class="eventSeparator">';
				echo $ev->City;

				echo '</div>';
			}
			if(isset($_REQUEST['eid']))
			{
				if($ev->EventID != $_REQUEST['eid'])
				{
					continue;
				}
			}
		?>
			<div class="eventItem">
				<div class="eventDate<?php echo $groupByCityClass; ?>">
					<?php echo isset($eventDates[$ev->EventID]) ? GetLogicalDateGroups($eventDates[$ev->EventID]) : GetOldStartEndDisplayDate($ev->PeriodStart, $ev->PeriodEnd); ?>
					<?php echo (!isset($eventDates[$ev->EventID]) ? "<span class=\"eventTime\">, " . date("H:i", strtotime($ev->PeriodStart)) .' - ' . date("H:i", strtotime($ev->PeriodEnd)) . "</span>" : ""); ?>
				</div>
				<?php if(!$groupByCity) { ?>
				<div class="eventCity">
					<?php
					echo $ev->City;
					if($showEventVenue && !empty($ev->AddressName))
						echo "<span class=\"venueInfo\">, " . $ev->AddressName . "</span>";
					?>
				</div>
				<?php } ?>
				<div class="eventStatus<?php echo $groupByCityClass; ?>">
				<?php
					$spotsLeft = ($ev->MaxParticipantNr - $ev->TotalParticipantNr);
					echo "<span class=\"spotsLeftInfo\">" . getSpotsLeft($spotsLeft, $ev->MaxParticipantNr) . "</span>";
				?>
				</div>
				<div class="eventBook<?php echo $groupByCityClass; ?>">
				<?php
				if($ev->MaxParticipantNr == 0 ||$spotsLeft > 0) {
				?>
					<a class="book-link" href="<?php echo $baseUrl; ?>/<?php echo makeSlugs($name); ?>__<?php echo $object->ObjectID; ?>/book/?eid=<?php echo $ev->EventID; ?><?php echo edu_getQueryString("&"); ?>" style="text-align: center;"><?php edu_e("Book"); ?></a>
				<?php
				} else {
				?>
				<?php
				$eventInterestPage = get_option('eduadmin-interestEventPage');
				$allowInterestRegEvent = get_option('eduadmin-allowInterestRegEvent', false);
				if($allowInterestRegEvent && $eventInterestPage != false) {
				?>
					<a class="inquiry-link" href="<?php echo $baseUrl; ?>/<?php echo makeSlugs($name); ?>__<?php echo $object->ObjectID; ?>/book/interest/?eid=<?php echo $ev->EventID; ?><?php echo edu_getQueryString("&"); ?>"><?php edu_e("Inquiry"); ?></a>
				<?php
				}
				?>
					<i class="fullBooked"><?php edu_e("Full"); ?></i>
				<?php } ?>
				</div>
			</div>
		<?php
			$lastCity = $ev->City;
			$i++;
		}
	}
	if(empty($prices) || empty($events))
	{
	?>
	<div class="noDatesAvailable">
		<i><?php edu_e("No available dates for the selected course"); ?></i>
	</div>
	<?php
	}
	?>
	</div>
	<?php
	$objectInterestPage = get_option('eduadmin-interestObjectPage');
	$allowInterestRegObject = get_option('eduadmin-allowInterestRegObject', false);
	if($allowInterestRegObject && $objectInterestPage != false) {
	?>
	<br />
	<div class="inquiry">
		<a class="inquiry-link" href="<?php echo $baseUrl; ?>/<?php echo makeSlugs($name); ?>__<?php echo $object->ObjectID; ?>/interest/<?php echo edu_getQueryString("?"); ?>"><?php edu_e("Send inquiry about this course"); ?></a>
	</div>
	<?php
	}
	?>
</div>
<?php
}
$out = ob_get_clean();
return $out;