<div class="questionPanel">
	<?php
	if ( ! empty( $_REQUEST['eid'] ) ) {
		foreach ( $booking_questions as $question ) {
			render_question( $question, false, 'booking' );
		}
	}
	?>
</div>
