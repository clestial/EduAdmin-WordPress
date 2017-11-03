<?php
if ( count( $subEvents ) > 0 && $sePrice != null ) {
	echo "<h4>" . edu__( "Sub events" ) . "</h4>\n";
	foreach ( $subEvents as $subEvent ) {
		if ( isset( $sePrice[ $subEvent->OccasionID ] ) && count( $sePrice[ $subEvent->OccasionID ] ) > 0 ) {
			$s = current( $sePrice[ $subEvent->OccasionID ] )->Price;

			// PriceNameVat
			echo "<label>" .
			     "<input class=\"subEventCheckBox\" data-price=\"" . $s . "\" onchange=\"eduBookingView.UpdatePrice();\" " .
			     "name=\"contactSubEvent_" . $subEvent->EventID . "\" " .
			     "type=\"checkbox\"" .
			     ( $subEvent->SelectedByDefault == true || $subEvent->MandatoryParticipation == true ? " checked=\"checked\"" : "" ) .
			     ( $subEvent->MandatoryParticipation == true ? " disabled=\"disabled\"" : "" ) .
			     " value=\"" . $subEvent->EventID . "\"> " .
			     $subEvent->Description .
			     ( $hideSubEventDateInfo ? "" : " (" . date( "d/m H:i", strtotime( $subEvent->StartDate ) ) . " - " . date( "d/m H:i", strtotime( $subEvent->EndDate ) ) . ") " ) .
			     ( $s > 0 ? " <i class=\"priceLabel\">" . convertToMoney( $s ) . "</i>" : "" ) .
			     "</label>\n";
		}
	}
}