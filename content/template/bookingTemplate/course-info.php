<?php
$course_id     = $wp_query->query_vars['courseId'];
$group_by_city = get_option( 'eduadmin-groupEventsByCity', false );

$fetch_months = get_option( 'eduadmin-monthsToFetch', 6 );
if ( ! is_numeric( $fetch_months ) ) {
	$fetch_months = 6;
}

if ( empty( $edo ) ) {

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
		'$expand=PriceNames($filter=PublicPriceName),EventDates,Sessions($expand=PriceNames($filter=PublicPriceName;);$filter=HasPublicPriceName;),PaymentMethods' .
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

	$edo = EDUAPI()->OData->CourseTemplates->GetItem(
		$course_id,
		null,
		join( ',', $expand_arr )
	);
	set_transient( 'eduadmin-object_' . $course_id, $edo, 10 );
}

$selected_course = false;
$name            = '';
if ( $edo ) {
	$name            = ( ! empty( $edo['CourseName'] ) ? $edo['CourseName'] : $edo['InternalCourseName'] );
	$selected_course = $edo;
}

if ( ! $selected_course || ( is_array( $selected_course['Events'] ) && 0 === count( $selected_course['Events'] ) ) ) {
	?>
	<script>history.go(-1);</script>
	<?php
	die();
}

$events = $selected_course['Events'];
$event  = $events[0];
if ( isset( $_GET['eid'] ) && is_numeric( $_GET['eid'] ) ) {
	$eventid = intval( $_GET['eid'] );
	foreach ( $events as $ev ) {
		if ( $eventid === $ev['EventId'] ) {
			$event    = $ev;
			$events   = array();
			$events[] = $ev;
			break;
		}
	}
}

$questions = EDUAPI()->REST->Event->BookingQuestions( $event['EventId'], true );

$booking_questions     = $questions['BookingQuestions'];
$participant_questions = $questions['ParticipantQuestions'];
