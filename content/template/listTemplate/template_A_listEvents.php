<?php
ob_start();
include( "list-events.php" );
$number_of_events = $attributes['numberofevents'];
$current_events  = 0;

foreach ( $events as $event ) {
	if ( $number_of_events != null && $number_of_events > 0 && $current_events >= $number_of_events ) {
		break;
	}
	$name      = $event["EventName"];
	$spotsLeft = $event["ParticipantNumberLeft"];
	$object    = $event['CourseTemplate'];

	$eventDates = array();
	if ( ! empty( $event["EventDates"] ) ) {
		$eventDates[ $event["EventId"] ] = $event["EventDates"];
	}

	include 'blocks/event_blockA.php';
	$current_events++;
}
?>
	</div><!-- /eventlist -->
<?php
$out = ob_get_clean();

return $out;
