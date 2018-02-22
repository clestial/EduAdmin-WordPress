<?php
$name   = ( ! empty( $object['CourseName'] ) ? $object['CourseName'] : $object['InternalCourseName'] );
$events = $object['Events'];

$prices        = array();
$sorted_events = array();
$event_cities  = array();

foreach ( $events as $ev ) {
	$sorted_events[ $ev['StartDate'] ] = $ev;
	if ( ! empty( $ev['City'] ) ) {
		$event_cities[ $ev['City'] ] = $ev;
	}
	foreach ( $ev['PriceNames'] as $pr ) {
		$prices[ $pr['Price'] ] = $pr;
	}
}

ksort( $sorted_events );
ksort( $event_cities );

$show_events_with_events_only    = $attributes['onlyevents'];
$show_events_without_events_only = $attributes['onlyempty'];
