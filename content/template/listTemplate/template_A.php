<?php
ob_start();
global $wp_query;
global $eduapi;
global $edutoken;
$apiKey = get_option('eduadmin-api-key');

if (!$apiKey || empty($apiKey)) {
	echo 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
} else {
	$allowLocationSearch = get_option('eduadmin-allowLocationSearch', true);
	$allowSubjectSearch = get_option('eduadmin-allowSubjectSearch', false);
	$allowCategorySearch = get_option('eduadmin-allowCategorySearch', false);
	$allowLevelSearch = get_option('eduadmin-allowLevelSearch', false);

	$subjects = get_transient('eduadmin-subjects');
	if (!$subjects) {
		$sorting = new XSorting();
		$s = new XSort('SubjectName', 'ASC');
		$sorting->AddItem($s);
		$subjects = $eduapi->GetEducationSubject($edutoken, $sorting->ToString(), '');
		set_transient('eduadmin-subjects', $subjects, DAY_IN_SECONDS);
	}

	$distinctSubjects = array();
	foreach ($subjects as $subj) {
		if (!key_exists($subj->SubjectID, $distinctSubjects)) {
			$distinctSubjects[$subj->SubjectID] = $subj->SubjectName;
		}
	}

	$addresses = get_transient('eduadmin-locations');
	if (!$addresses) {
		$ft = new XFiltering();
		$f = new XFilter('PublicLocation', '=', 'true');
		$ft->AddItem($f);
		$addresses = $eduapi->GetLocation($edutoken, '', $ft->ToString());
		set_transient('eduadmin-locations', $addresses, DAY_IN_SECONDS);
	}

	$showEvents = get_option('eduadmin-showEventsInList', FALSE);

	$categories = get_transient('eduadmin-categories');
	if (!$categories) {
		$ft = new XFiltering();
		$f = new XFilter('ShowOnWeb', '=', 'true');
		$ft->AddItem($f);
		$categories = $eduapi->GetCategory($edutoken, '', $ft->ToString());
		set_transient('eduadmin-categories', $categories, DAY_IN_SECONDS);
	}

	$levels = get_transient('eduadmin-levels');
	if (!$levels) {
		$levels = $eduapi->GetEducationLevel($edutoken, '', '');
		set_transient('eduadmin-levels', $levels, DAY_IN_SECONDS);
	}

	$courseLevels = get_transient('eduadmin-courseLevels');
	if (!$courseLevels) {
		$courseLevels = $eduapi->GetEducationLevelObject($edutoken, '', '');
		set_transient('eduadmin-courseLevels', $courseLevels, DAY_IN_SECONDS);
	}
?>
<div class="eduadmin">
	<?php if ($attributes['hidesearch'] == false) { ?>
	<form method="POST" class="search-form">
		<table style="width: 100%;">
			<tr>
				<?php if ($allowLocationSearch && !empty($addresses) && $showEvents) { ?>
				<td style="width: 15%;">
					<select name="eduadmin-city">
						<option value=""><?php edu_e("Choose city"); ?></option>
						<?php
						$addedCities = array();
						foreach ($addresses as $address) {
							$city = trim($address->City);
							if (!in_array($address->LocationID, $addedCities) && !empty($city)) {
								echo '<option value="' . $address->LocationID . '"' . (isset($_REQUEST['eduadmin-city']) && $_REQUEST['eduadmin-city'] == $address->LocationID ? " selected=\"selected\"" : "") . '>' . trim($address->City) . '</option>';
								$addedCities[] = $address->LocationID;
							}
						}
						?>
					</select>
				</td>
				<?php } ?>
				<?php if ($allowSubjectSearch && !empty($distinctSubjects)) { ?>
				<td style="width: 15%;">
					<select name="eduadmin-subject">
						<option value=""><?php edu_e("Choose subject"); ?></option>
						<?php
						foreach ($distinctSubjects as $subj => $val) {
							echo '<option value="' . $subj . '"' . (isset($_REQUEST['eduadmin-subject']) && $_REQUEST['eduadmin-subject'] == $subj ? " selected=\"selected\"" : "") . '>' . $val . '</option>';
						}
						?>
					</select>
				</td>
				<?php } ?>
				<?php if ($allowCategorySearch && !empty($categories)) { ?>
				<td style="width: 15%;">
					<select name="eduadmin-category">
						<option value=""><?php edu_e("Choose category"); ?></option>
						<?php
						foreach ($categories as $subj) {
							echo '<option value="' . $subj->CategoryID . '"' . (isset($_REQUEST['eduadmin-category']) && $_REQUEST['eduadmin-category'] == $subj->CategoryID ? " selected=\"selected\"" : "") . '>' . $subj->CategoryName . '</option>';
						}
						?>
					</select>
				</td>
				<?php } ?>
				<?php if ($allowLevelSearch && !empty($levels)) { ?>
				<td style="width: 15%;">
					<select name="eduadmin-level">
						<option value=""><?php edu_e("Choose course level"); ?></option>
						<?php
						foreach ($levels as $level) {
							echo '<option value="' . $level->EducationLevelID . '"' . (isset($_REQUEST['eduadmin-level']) && $_REQUEST['eduadmin-level'] == $level->EducationLevelID ? " selected=\"selected\"" : "") . '>' . $level->Name . '</option>';
						}
						?>
					</select>
				</td>
				<?php } ?>
				<td>
					<input class="edu-searchTextBox" type="search" name="searchCourses" results="10" autosave="edu-course-search_<?php echo session_id(); ?>" placeholder="<?php edu_e("Search courses"); ?>"<?php if (isset($_REQUEST['searchCourses'])) { echo " value=\"" . $_REQUEST['searchCourses'] . "\""; }?> />
				</td>
				<td style="width: 10%;">
					<input type="submit" class="searchButton" style="width: 100%;" value="<?php edu_e("Search"); ?>" />
				</td>
			</tr>
		</table>
		<?php
		if (isset($_REQUEST['searchCourses'])) {
			?>
			<script type="text/javascript">
			(function() { jQuery('.edu-searchTextBox')[0].scrollIntoView(true); jQuery('.edu-searchTextBox').focus(); })();
			</script>
			<?php
		}
		?>
	</form>
	<?php } ?>
<?php
	$eds = $subjects;

	$edl = $levels;

	$filterCourses = array();

	if (!empty($attributes['subject'])) {
		foreach ($eds as $subject) {

			if ($subject->SubjectName == $attributes['subject']) {
				if (!in_array($subject->ObjectID, $filterCourses)) {
					$filterCourses[] = $subject->ObjectID;
				}
			}
		}
	}

	$categoryID = null;
	if (!empty($attributes['category'])) {
		$categoryID = $attributes['category'];
	}

	$showImages = get_option('eduadmin-showCourseImage', TRUE);

	$customOrderBy = null;
	$customOrderByOrder = null;
	if (!empty($attributes['orderby'])) {
		$customOrderBy = $attributes['orderby'];
	}

	if (!empty($attributes['order'])) {
		$customOrderByOrder = $attributes['order'];
	}

	$customMode = null;
	if (!empty($attributes['mode'])) {
		$customMode = $attributes['mode'];
	}

	if ($showEvents || $customMode == 'event') {
		$str = include("template_A_listEvents.php");
		echo $str;
	} else if (!$showEvents || $customMode == 'course') {
		$str = include("template_A_listCourses.php");
		echo $str;
	}
?>
</div>
<?php
}
$out = ob_get_clean();
return $out;