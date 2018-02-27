<div class="questionPanel">
	<?php
	if ( isset( $_REQUEST['eid'] ) ) {
		// VatPercent EventBookingAnswer

		foreach ( $booking_questions as $question ) {
			render_question( $question, false, 'booking' );
		}
	}
	?>
</div>
