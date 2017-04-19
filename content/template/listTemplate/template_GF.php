<?php
ob_start();
global $wp_query;
global $eduapi;
global $edutoken;
$apiKey = get_option( 'eduadmin-api-key' );

if ( ! $apiKey || empty( $apiKey ) ) {
	echo 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
} else {
	$allowLocationSearch = get_option( 'eduadmin-allowLocationSearch', true );
	$allowSubjectSearch  = get_option( 'eduadmin-allowSubjectSearch', false );
	$allowCategorySearch = get_option( 'eduadmin-allowCategorySearch', false );
	$allowLevelSearch    = get_option( 'eduadmin-allowLevelSearch', false );

	$subjects = get_transient( 'eduadmin-subjects' );
	if ( ! $subjects ) {
		$sorting = new XSorting();
		$s       = new XSort( 'SubjectName', 'ASC' );
		$sorting->AddItem( $s );
		$subjects = $eduapi->GetEducationSubject( $edutoken, $sorting->ToString(), '' );
		set_transient( 'eduadmin-subjects', $subjects, DAY_IN_SECONDS );
	}

	$distinctSubjects = array();
	foreach ( $subjects as $subj ) {
		if ( ! key_exists( $subj->SubjectID, $distinctSubjects ) ) {
			$distinctSubjects[ $subj->SubjectID ] = $subj->SubjectName;
		}
	}

	$addresses = get_transient( 'eduadmin-locations' );
	if ( ! $addresses ) {
		$ft = new XFiltering();
		$f  = new XFilter( 'PublicLocation', '=', 'true' );
		$ft->AddItem( $f );
		$addresses = $eduapi->GetLocation( $edutoken, '', $ft->ToString() );
		set_transient( 'eduadmin-locations', $addresses, DAY_IN_SECONDS );
	}

	$showEvents = get_option( 'eduadmin-showEventsInList', false );

	$categories = get_transient( 'eduadmin-categories' );
	if ( ! $categories ) {
		$ft = new XFiltering();
		$f  = new XFilter( 'ShowOnWeb', '=', 'true' );
		$ft->AddItem( $f );
		$categories = $eduapi->GetCategory( $edutoken, '', $ft->ToString() );
		set_transient( 'eduadmin-categories', $categories, DAY_IN_SECONDS );
	}

	$levels = get_transient( 'eduadmin-levels' );
	if ( ! $levels ) {
		$levels = $eduapi->GetEducationLevel( $edutoken, '', '' );
		set_transient( 'eduadmin-levels', $levels, DAY_IN_SECONDS );
	}

	$courseLevels = get_transient( 'eduadmin-courseLevels' );
	if ( ! $courseLevels ) {
		$courseLevels = $eduapi->GetEducationLevelObject( $edutoken, '', '' );
		set_transient( 'eduadmin-courseLevels', $courseLevels, DAY_IN_SECONDS );
	}
	?>
	<div class="eduadmin">
		<div class="courseContainer">
			<?php
			$eds = $subjects;

			$edl = $levels;

			$filterCourses = array();

			if ( ! empty( $attributes['subject'] ) ) {
				foreach ( $eds as $subject ) {
					if ( $subject->SubjectName == $attributes['subject'] ) {
						if ( ! in_array( $subject->ObjectID, $filterCourses ) ) {
							$filterCourses[] = $subject->ObjectID;
						}
					}
				}
			}

			$categoryID = null;
			if ( ! empty( $attributes['category'] ) ) {
				$categoryID = $attributes['category'];
			}

			$showImages = get_option( 'eduadmin-showCourseImage', true );

			$customOrderBy      = null;
			$customOrderByOrder = null;
			if ( ! empty( $attributes['orderby'] ) ) {
				$customOrderBy = $attributes['orderby'];
			}

			if ( ! empty( $attributes['order'] ) ) {
				$customOrderByOrder = $attributes['order'];
			}

			$customMode = null;
			if ( ! empty( $attributes['mode'] ) ) {
				$customMode = $attributes['mode'];
			}

			if ( $showEvents || $customMode == 'event' ) {
				$str = include( "template_GF_listEvents.php" );
				echo $str;
			} else if ( ! $showEvents || $customMode == 'course' ) {
				$str = include( "template_GF_listCourses.php" );
				echo $str;
			}
			?>
		</div>
	</div>
	<?php
}
$out = ob_get_clean();
return $out;