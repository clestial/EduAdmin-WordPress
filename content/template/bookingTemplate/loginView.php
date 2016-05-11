<?php
ob_start();
global $wp_query;
global $api;
$apiKey = get_option('eduadmin-api-key');

if(!$apiKey || empty($apiKey))
{
	echo 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
}
else
{
	//$api = new EduAdminClient();
	$key = DecryptApiKey($apiKey);
	if(!$key)
	{
		echo 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
		return;
	}
	$token = get_transient('eduadmin-token');
	if(!$token)
	{
		$token = $api->GetAuthToken($key->UserId, $key->Hash);
		set_transient('eduadmin-token', $token, HOUR_IN_SECONDS);
	}
	else
	{
		$valid = $api->ValidateAuthToken($token);
		if(!$valid)
		{
			$token = $api->GetAuthToken($key->UserId, $key->Hash);
			set_transient('eduadmin-token', $token, HOUR_IN_SECONDS);
		}
	}

	$edo = get_transient('eduadmin-listCourses');
	if(!$edo)
	{
		$filtering = new XFiltering();
		$f = new XFilter('ShowOnWeb','=','true');
		$filtering->AddItem($f);

		$edo = $api->GetEducationObject($token, '', $filtering->ToString());
		set_transient('eduadmin-listCourses', $edo, 6 * HOUR_IN_SECONDS);
	}

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
		<script>history.go(-1);</script>
		<?php
		die();
	}
	$ft = new XFiltering();
	if(isset($_REQUEST['eid']))
	{
		$eventid = $_REQUEST['eid'];
		$f = new XFilter('EventID', '=', $eventid);
		$ft->AddItem($f);
	}
	$f = new XFilter('ShowOnWeb', '=', 'true');
	$ft->AddItem($f);
	$f = new XFilter('ObjectID', '=', $selectedCourse->ObjectID);
	$ft->AddItem($f);
	$f = new XFilter('LastApplicationDate', '>=', date("Y-m-d 00:00:00", strtotime("today +1 day")));
	$ft->AddItem($f);
	$f = new XFilter('StatusID','=','1');
	$ft->AddItem($f);

	$st = new XSorting();
	$s = new XSort('PeriodStart', 'ASC');
	$st->AddItem($s);

	$events = $api->GetEvent(
		$token,
		$st->ToString(),
		$ft->ToString()
	);

	$event = $events[0];

	if(!$event)
	{
		?>
		<script>history.go(-1);</script>
		<?php
		die();
	}

	include_once("__loginHandler.php");
?>
<!-- mfunc -->
<div class="eduadmin loginForm">
	<form action="" method="post">
	<a href="../" class="backLink"><?php edu_e("« Go back"); ?></a>
	<div class="title">
		<img src="<?php echo $selectedCourse->ImageUrl; ?>" style="max-width: 8em; max-height: 8em; margin-right: 2em; float: left;" />
		<h1 style="float: left; clear: right;"><?php echo $name; ?></h1>
			<?php if(count($events) > 1) { ?>
			<select name="eid" class="dateInfo">
<?php
				foreach($events as $ev)
				{
					?>				<option value="<?php echo $ev->EventID; ?>"><?php
						echo wp_strip_all_tags(GetStartEndDisplayDate($ev->PeriodStart, $ev->PeriodEnd)) . ", ";
						echo date("H:i", strtotime($ev->PeriodStart)); ?> - <?php echo date("H:i", strtotime($ev->PeriodEnd));
						$addresses = get_transient('eduadmin-location-' . $ev->LocationAddressID);
						if(!$addresses)
						{
							$ft = new XFiltering();
							$f = new XFilter('LocationAddressID', '=', $ev->LocationAddressID);
							$ft->AddItem($f);
							$addresses = $api->GetLocationAddress($token, '', $ft->ToString());
							set_transient('eduadmin-location-' . $ev->LocationAddressID, $addresses, DAY_IN_SECONDS);
						}

						foreach($addresses as $address)
						{
							if($address->LocationAddressID === $ev->LocationAddressID)
							{
								echo ", " . $ev->AddressName . ", " . $address->Address . ", " . $address->City;
								break;
							}
						}
					?></option>
<?php
				}
?>
			</select>
			<?php
			} else {
				echo "<div class=\"dateInfo\">" . GetStartEndDisplayDate($event->PeriodStart, $event->PeriodEnd) . ", ";
				echo date("H:i", strtotime($event->PeriodStart)); ?> - <?php echo date("H:i", strtotime($event->PeriodEnd));
				$addresses = get_transient('eduadmin-location-' . $event->LocationAddressID);
				if(!$addresses)
				{
					$ft = new XFiltering();
					$f = new XFilter('LocationAddressID', '=', $event->LocationAddressID);
					$ft->AddItem($f);
					$addresses = $api->GetLocationAddress($token, '', $ft->ToString());
					set_transient('eduadmin-location-' . $event->LocationAddressID, $addresses, HOUR_IN_SECONDS);
				}

				foreach($addresses as $address)
				{
					if($address->LocationAddressID === $event->LocationAddressID)
					{
						echo ", " . $event->AddressName . ", " . $address->Address . ", " . $address->City;
						break;
					}
				}
				echo "</div>\n";
			}

	if(!isset($_SESSION['checkEmail']))
	{
		include_once("__checkEmail.php");
	}
	else if(isset($_SESSION['checkEmail']))
	{
		if(isset($_SESSION['needsLogin']) && $_SESSION['needsLogin'] == true)
		{
			include_once("__loginForm.php");
		}
		else
		{
			unset($_SESSION['checkEmail']);
			unset($_SESSION['needsLogin']);
			?><script type="text/javascript">(function() { location.href = location.href; })();</script><?php
		}
	}
?>

	</div>
</form>
</div>
<!-- /mfunc -->
<?php
}
$out = ob_get_clean();
return $out;
?>