<?php
	$eds = $subjects;

	$edl = $levels;

	$filterCourses = array();

	if ( !empty( $attributes['subject'] ) ) {
		foreach ( $eds as $subject ) {
			if ( $subject->SubjectName == $attributes['subject'] ) {
				if ( !in_array( $subject->ObjectID, $filterCourses ) ) {
					$filterCourses[] = $subject->ObjectID;
				}
			}
		}
	}

	$categoryID = null;
	if ( !empty( $attributes['category'] ) ) {
		$categoryID = $attributes['category'];
	}

	$showImages = get_option( 'eduadmin-showCourseImage', true );

	$customOrderBy      = null;
	$customOrderByOrder = null;
	if ( !empty( $attributes['orderby'] ) ) {
		$customOrderBy = $attributes['orderby'];
	}

	if ( !empty( $attributes['order'] ) ) {
		$customOrderByOrder = $attributes['order'];
	}

	$customMode = null;
	if ( !empty( $attributes['mode'] ) ) {
		$customMode = $attributes['mode'];
	}

	if ( $customMode != null ) {
		if ( $customMode == 'event' ) {
			$str = include( $attributes["template"] . "_listEvents.php" );
			echo $str;
		} else if ( $customMode == 'course' ) {
			$str = include( $attributes["template"] . "_listCourses.php" );
			echo $str;
		}
	} else {
		if ( $showEvents ) {
			$str = include( $attributes["template"] . "_listEvents.php" );
			echo $str;
		} else if ( !$showEvents ) {
			$str = include( $attributes["template"] . "_listCourses.php" );
			echo $str;
		}
	}