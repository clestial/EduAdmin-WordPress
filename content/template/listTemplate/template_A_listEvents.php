<?php
ob_start();
require 'list-events.php';
$number_of_events = $attributes['numberofevents'];
$current_events   = 0;

foreach ( $events as $event ) {
	if ( null !== $number_of_events && $number_of_events > 0 && $current_events >= $number_of_events ) {
		break;
	}
	$name       = $event['EventName'];
	$spots_left = $event['ParticipantNumberLeft'];
	$object     = $event['CourseTemplate'];

	$event_dates = array();
	if ( ! empty( $event['EventDates'] ) ) {
		$event_dates[ $event['EventId'] ] = $event['EventDates'];
	}

	include 'blocks/event_blockA.php';
	$current_events++;
}
?>
	</div><!-- /eventlist -->
<?php
$out = ob_get_clean();

return $out;
