<?php
	ob_start();
	include( "list-events.php" );
	$numberOfEvents = $attributes['numberofevents'];
	$currentEvents  = 0;

	foreach ( $ede as $object ) {
		if ( $numberOfEvents != null && $numberOfEvents > 0 && $currentEvents >= $numberOfEvents ) {
			break;
		}
		$name      = ( ! empty( $object->PublicName ) ? $object->PublicName : $object->ObjectName );
		$spotsLeft = ( $object->MaxParticipantNr - $object->TotalParticipantNr );
		include( 'blocks/event_blockA.php' );
		$currentEvents++;
	}
?>
    </div><!-- /eventlist -->
<?php
	$out = ob_get_clean();
	return $out;