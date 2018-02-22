<div class="participantView">
	<h2><?php esc_html_e( 'Participant information', 'eduadmin-booking' ); ?></h2>
	<div class="participantHolder" id="edu-participantHolder">
		<?php
		require_once 'participants/contact-participant.php';
		require_once 'participants/participant-template.php';
		?>
	</div>
	<div>
		<a href="javascript://" class="addParticipantLink neutral-btn" onclick="eduBookingView.AddParticipant(); return false;"><?php esc_html_e( '+ Add participant', 'eduadmin-booking' ); ?></a>
	</div>
	<div class="edu-modal warning" id="edu-warning-participants">
		<?php esc_html_e( 'You cannot add any more participants.', 'eduadmin-booking' ); ?>
	</div>
</div>
