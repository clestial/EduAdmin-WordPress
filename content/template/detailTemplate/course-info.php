<?php
if ( ! empty( $wp_query->query_vars['courseId'] ) ) {
	$course_id = $wp_query->query_vars['courseId'];
} elseif ( ! empty( $attributes['courseid'] ) ) {
	$course_id = $attributes['courseid'];
} else {
	$course_id = null;
}
$group_by_city       = get_option( 'eduadmin-groupEventsByCity', false );
$group_by_city_class = '';
$edo                 = get_transient( 'eduadmin-object_' . $course_id . '_json' );
if ( ! $edo ) {
	$fetch_months = get_option( 'eduadmin-monthsToFetch', 6 );
	if ( ! is_numeric( $fetch_months ) ) {
		$fetch_months = 6;
	}

	$expands = array();

	$expands['Subjects']   = '';
	$expands['Categories'] = '';
	$expands['PriceNames'] = '$filter=PublicPriceName;';
	$expands['Events']     =
		'$filter=' .
		'HasPublicPriceName' .
		' and StatusId eq 1' .
		' and CustomerId eq null' .
		' and LastApplicationDate ge ' . date( 'c' ) .
		' and StartDate le ' . date( 'c', strtotime( 'now 23:59:59 +' . $fetch_months . ' months' ) ) .
		' and EndDate ge ' . date( 'c', strtotime( 'now' ) ) .
		';' .
		'$expand=PriceNames($filter=PublicPriceName),EventDates' .
		';' .
		'$orderby=' . ( $group_by_city ? 'City asc,' : '' ) . 'StartDate asc' .
		';';

	$expands['CustomFields'] = '$filter=ShowOnWeb';

	$expand_arr = array();
	foreach ( $expands as $key => $value ) {
		if ( empty( $value ) ) {
			$expand_arr[] = $key;
		} else {
			$expand_arr[] = $key . '(' . $value . ')';
		}
	}

	$edo = wp_json_encode( EDUAPI()->OData->CourseTemplates->GetItem(
		$course_id,
		null,
		join( ',', $expand_arr )
	) );
	set_transient( 'eduadmin-object_' . $course_id . '_json', $edo, 10 );
}

$selected_course = false;
$name            = '';
if ( $edo ) {
	$selected_course = json_decode( $edo, true );
	$name            = ( ! empty( $selected_course['CourseName'] ) ? $selected_course['CourseName'] : $selected_course['InternalCourseName'] );
}

$surl     = get_home_url();
$cat      = get_option( 'eduadmin-rewriteBaseUrl' );
$base_url = $surl . '/' . $cat;

$events = $selected_course['Events'];

$prices = array();

if ( ! empty( $selected_course['PriceNames'] ) ) {
	foreach ( $selected_course['PriceNames'] as $pn ) {
		$prices[ $pn['PriceNameId'] ] = $pn;
	}
}

foreach ( $events as $e ) {
	foreach ( $e['PriceNames'] as $pn ) {
		$prices[ $pn['PriceNameId'] ] = $pn;
	}
}

$course_level = get_transient( 'eduadmin-courseLevel-' . $selected_course['CourseTemplateId'] );
if ( ! $course_level && ! empty( $selected_course['CourseLevelId'] ) ) {
	$course_level = EDUAPI()->OData->CourseLevels->GetItem( $selected_course['CourseLevelId'] );
	set_transient( 'eduadmin-courseLevel-' . $selected_course['CourseTemplateId'], $course_level, HOUR_IN_SECONDS );
}

$inc_vat      = EDUAPI()->REST->Organisation->GetOrganisation()['PriceIncVat'];
$show_headers = get_option( 'eduadmin-showDetailHeaders', true );

$hide_sections = array();
if ( isset( $attributes['hide'] ) ) {
	$hide_sections = explode( ',', $attributes['hide'] );
}
