<?php
	ob_start();
	include( "list-events.php" );
	$numberOfEvents = $attributes['numberofevents'];
	$currentEvents  = 0;

	foreach ( $events as $event ) {
		if ( $numberOfEvents != null && $numberOfEvents > 0 && $currentEvents >= $numberOfEvents ) {
			break;
		}
		$name      = $event["EventName"];
		$spotsLeft = $event["ParticipantNumberLeft"];
		$object    = $event['CourseTemplate'];

		$eventDates = array();
		if ( ! empty( $event["EventDates"] ) ) {
			$eventDates[ $event["EventId"] ] = $event["EventDates"];
		}

		include( 'blocks/event_blockA.php' );
		$currentEvents++;
	}
?>
    </div><!-- /eventlist -->
<?php
	$out = ob_get_clean();

	return $out;