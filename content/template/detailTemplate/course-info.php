<?php
$courseId    = $wp_query->query_vars["courseId"];
$groupByCity = get_option( 'eduadmin-groupEventsByCity', false );
$edo         = get_transient( 'eduadmin-object_' . $courseId );
if ( ! $edo ) {
	$fetchMonths = get_option( 'eduadmin-monthsToFetch', 6 );
	if ( ! is_numeric( $fetchMonths ) ) {
		$fetchMonths = 6;
	}

	$expands = array();

	$expands['Subjects']   = "";
	$expands['Categories'] = "";
	$expands['PriceNames'] = '$filter=PublicPriceName;';
	$expands['Events']     =
		'$filter=' .
		'HasPublicPriceName' .
		' and StatusId eq 1' .
		' and CustomerId eq null' .
		' and LastApplicationDate ge ' . date( "c" ) .
		' and StartDate le ' . date( "c", strtotime( "now 23:59:59 +" . $fetchMonths . " months" ) ) .
		' and EndDate ge ' . date( "c", strtotime( "now" ) ) .
		';' .
		'$expand=PriceNames($filter=PublicPriceName),EventDates' .
		';' .
		'$orderby=' . ( $groupByCity ? 'City asc,' : '' ) . 'StartDate asc' .
		';';

	$expands['CustomFields'] = '$filter=ShowOnWeb';

	$expandArr = array();
	foreach ( $expands as $key => $value ) {
		if ( empty( $value ) ) {
			$expandArr[] = $key;
		} else {
			$expandArr[] = $key . "(" . $value . ")";
		}
	}

	$edo = EDUAPI()->OData->CourseTemplates->GetItem(
		$courseId,
		null,
		join( ",", $expandArr )
	);
	set_transient( 'eduadmin-object_' . $courseId, $edo, 10 );
}

$selectedCourse = false;
$name           = "";
if ( $edo ) {
	$name           = ( ! empty( $edo["CourseName"] ) ? $edo["CourseName"] : $edo["InternalCourseName"] );
	$selectedCourse = $edo;
}

$surl    = get_home_url();
$cat     = get_option( 'eduadmin-rewriteBaseUrl' );
$baseUrl = $surl . '/' . $cat;

$events = $selectedCourse["Events"];

$prices = array();

foreach ( $selectedCourse["PriceNames"] as $pn ) {
	$prices[ $pn["PriceNameId"] ] = $pn;
}

foreach ( $events as $e ) {
	foreach ( $e["PriceNames"] as $pn ) {
		$prices[ $pn["PriceNameId"] ] = $pn;
	}
}

$courseLevel = get_transient( 'eduadmin-courseLevel-' . $selectedCourse["CourseTemplateId"] );
if ( ! $courseLevel && $selectedCourse["EducationLevelId"] != null ) {
	$courseLevel = EDUAPI()->OData->CourseLevels->GetItem( $selectedCourse["EducationLevelId"] );
	set_transient( 'eduadmin-courseLevel-' . $selectedCourse["CourseTemplateId"], $courseLevel, HOUR_IN_SECONDS );
}

$incVat      = EDUAPI()->REST->Organisation->GetOrganisation()["PriceIncVat"];
$showHeaders = get_option( 'eduadmin-showDetailHeaders', true );

$hideSections = array();
if ( isset( $attributes['hide'] ) ) {
	$hideSections = explode( ',', $attributes['hide'] );
}