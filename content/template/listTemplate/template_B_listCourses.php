<?php
	ob_start();
	include( "list-courses.php" );
?>
    <div class="course-holder tmpl_B">
<?php

	foreach ( $edo as $object ) {
		$name   = ( !empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
		$events = array_filter( $ede, function( $ev ) use ( &$object ) {
			return $ev->ObjectID == $object->ObjectID;
		} );

		$prices       = array();
		$sortedEvents = array();
		$eventCities  = array();
		foreach ( $pricenames as $pr ) {
			foreach ( $events as $ev ) {
				if ( $ev->OccationID == $pr->OccationID ) {
					$prices[ $pr->Price ] = $pr;
				}
				$sortedEvents[ $ev->PeriodStart ] = $ev;
				if ( !empty( $ev->City ) ) {
					$eventCities[ $ev->City ] = $ev;
				}
			}
		}

		ksort( $sortedEvents );
		ksort( $eventCities );

		if ( $showEventsWithEventsOnly && empty( $sortedEvents ) ) {
			continue;
		}

		if ( $showEventsWithoutEventsOnly && !empty( $sortedEvents ) ) {
			continue;
		}
		include( 'blocks/course_blockB.php' );
	}
?></div><?php
	$out = ob_get_clean();
	return $out;