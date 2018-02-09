<?php
	$allowLocationSearch = get_option( 'eduadmin-allowLocationSearch', true );
	$allowSubjectSearch  = get_option( 'eduadmin-allowSubjectSearch', false );
	$allowCategorySearch = get_option( 'eduadmin-allowCategorySearch', false );
	$allowLevelSearch    = get_option( 'eduadmin-allowLevelSearch', false );

	$showSearch      = $attributes['showsearch'];
	$showMoreNumber  = $attributes['showmore'];
	$showCity        = $attributes['showcity'];
	$showBookBtn     = $attributes['showbookbtn'];
	$showReadMoreBtn = $attributes['showreadmorebtn'];

	$searchVisible = $showSearch == true || ( $attributes['hidesearch'] == false || $attributes['hidesearch'] == null );

	$subjects = get_transient( 'eduadmin-subjects' );
	if ( !$subjects ) {
		$subjects = EDUAPI()->OData->Subjects->Search(
			null,
			null,
			null,
			"SubjectName asc"
		)["value"];
		set_transient( 'eduadmin-subjects', $subjects, DAY_IN_SECONDS );
	}

	$distinctSubjects = array();
	foreach ( $subjects as $subj ) {
		if ( !key_exists( $subj["SubjectId"], $distinctSubjects ) ) {
			$distinctSubjects[ $subj["SubjectId"] ] = $subj["SubjectName"];
		}
	}

	$addresses = get_transient( 'eduadmin-locations' );
	if ( !$addresses ) {
		$addresses = EDUAPI()->OData->Locations->Search(
			"LocationId,City",
			"PublicLocation"
		)["value"];
		set_transient( 'eduadmin-locations', $addresses, DAY_IN_SECONDS );
	}

	$showEvents = get_option( 'eduadmin-showEventsInList', false );

	$categories = get_transient( 'eduadmin-categories' );
	if ( !$categories ) {
		$categories = EDUAPI()->OData->Categories->Search(
			"CategoryId,CategoryName",
			"ShowOnWeb"
		)["value"];

		set_transient( 'eduadmin-categories', $categories, DAY_IN_SECONDS );
	}

	$levels = get_transient( 'eduadmin-levels' );
	if ( !$levels ) {
		$levels = EDUAPI()->OData->CourseLevels->Search()["value"];
		set_transient( 'eduadmin-levels', $levels, DAY_IN_SECONDS );
	}