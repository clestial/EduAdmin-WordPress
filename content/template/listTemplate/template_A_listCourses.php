<?php
	ob_start();
	include( "list-courses.php" );
?>
    <div class="course-holder tmpl_A"><?php
	if ( ! empty( $edo ) ) {
		foreach ( $edo as $object ) {
			$name   = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
			$events = array_filter( $ede, function( $ev ) use ( &$object ) {
				return $ev->ObjectID == $object->ObjectID;
			} );

			$prices       = array();
			$sortedEvents = array();
			$eventCities  = array();

			foreach ( $events as $ev ) {
				$sortedEvents[ $ev->PeriodStart ] = $ev;
				if ( ! empty( $ev->City ) ) {
					$eventCities[ $ev->City ] = $ev;
				}
			}

			foreach ( $pricenames as $pr ) {
				if ( isset( $object->ObjectID ) && isset( $pr->ObjectID ) ) {
					if ( $object->ObjectID == $pr->ObjectID ) {
						$prices[ $pr->Price ] = $pr;
					}
				}
			}

			ksort( $sortedEvents );
			ksort( $eventCities );
			$showEventsWithEventsOnly    = $attributes['onlyevents'];
			$showEventsWithoutEventsOnly = $attributes['onlyempty'];
			if ( $showEventsWithEventsOnly && empty( $sortedEvents ) ) {
				continue;
			}

			if ( $showEventsWithoutEventsOnly && ! empty( $sortedEvents ) ) {
				continue;
			}
			include( 'blocks/course_blockA.php' );
		}
	} else {
		?>
        <div class="noResults"><?php _e( "Your search returned zero results", 'eduadmin-booking' ); ?></div>
		<?php
	}
?></div><?php
	$out = ob_get_clean();
	return $out;