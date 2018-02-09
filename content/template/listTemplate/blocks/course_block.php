<?php
	$name   = ( !empty( $object["CourseName"] ) ? $object["CourseName"] : $object["InternalCourseName"] );
	$events = $object["Events"];

	$prices       = array();
	$sortedEvents = array();
	$eventCities  = array();

	foreach ( $events as $ev ) {
		$sortedEvents[ $ev["StartDate"] ] = $ev;
		if ( !empty( $ev["City"] ) ) {
			$eventCities[ $ev["City"] ] = $ev;
		}
		foreach ( $ev["PriceNames"] as $pr ) {
			$prices[ $pr["Price"] ] = $pr;
		}
	}

	ksort( $sortedEvents );
	ksort( $eventCities );

	$showEventsWithEventsOnly    = $attributes['onlyevents'];
	$showEventsWithoutEventsOnly = $attributes['onlyempty'];