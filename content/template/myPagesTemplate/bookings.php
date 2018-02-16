<?php
$user     = EDU()->session['eduadmin-loginUser'];
$contact  = $user->Contact;
$customer = $user->Customer;

?>
<div class="eduadmin">
	<?php
	$tab = 'bookings';
	include_once( 'login_tab_header.php' );
	?>
	<h2><?php esc_html_e( 'Reservations', 'eduadmin-booking' ); ?></h2>
	<?php

	$bookings = EDUAPI()->OData->Bookings->Search(
		null,
		'Customer/CustomerId eq ' . $customer->CustomerId . ' and NumberOfParticipants gt 0',
		'Participants,UnnamedParticipants',
		'Created desc'
	);

	$eventIds = array();

	foreach ( $bookings['value'] as $booking ) {
		if ( ! in_array( $booking['EventId'], $eventIds, true ) ) {
			$eventIds[] = $booking['EventId'];
		}
	}

	if ( ! empty( $eventIds ) ) {
		$events = EDUAPI()->OData->Events->Search(
			null,
			'(EventId eq ' . join( ' or EventId eq ', $eventIds ) . ')'
		);
	} else {
		$events = null;
	}

	EDU()->write_debug( $events );
	$filtering = new XFiltering();
	$f         = new XFilter( 'CustomerID', '=', $customer->CustomerId );
	$filtering->AddItem( $f );
	$f = new XFilter( 'ParticipantNr', '>', 0 );
	$filtering->AddItem( $f );

	$sorting = new XSorting();
	$s       = new XSort( 'Created', 'DESC' );
	$sorting->AddItem( $s );
	$bookings = EDU()->api->GetEventBooking( EDU()->get_token(), $sorting->ToString(), $filtering->ToString() );

	$eclIds = array();
	foreach ( $bookings as $book ) {
		$eclIds[] = $book->EventCustomerLnkID;
	}

	$filtering = new XFiltering();
	$f         = new XFilter( 'EventCustomerLnkID', 'IN', join( ',', $eclIds ) );
	$filtering->AddItem( $f );

	$f = new XFilter( 'Canceled', '=', 'false' );
	$filtering->AddItem( $f );

	$participants = EDU()->api->GetEventParticipantV2( EDU()->get_token(), $sorting->ToString(), $filtering->ToString() );

	$partPerEvent = array();
	foreach ( $participants as $p ) {
		$partPerEvent[ $p->EventCustomerLnkID ][] = $p;
	}

	$currency = get_option( 'eduadmin-currency', 'SEK' );
	?>
	<table class="myReservationsTable">
		<tr>
			<th align="left"><?php esc_html_e( 'Booked', 'eduadmin-booking' ); ?></th>
			<th align="left"><?php esc_html_e( 'Course', 'eduadmin-booking' ); ?></th>
			<th align="left"><?php esc_html_e( 'Dates', 'eduadmin-booking' ); ?></th>
			<th align="right"><?php esc_html_e( 'Participants', 'eduadmin-booking' ); ?></th>
			<th align="right"><?php esc_html_e( 'Price', 'eduadmin-booking' ); ?></th>
		</tr>
		<?php
		if ( empty( $bookings ) ) {
			?>
			<tr>
				<td colspan="5" align="center"><i><?php esc_html_e( 'No courses booked', 'eduadmin-booking' ); ?></i>
				</td>
			</tr>
			<?php
		} else {
			foreach ( $bookings as $book ) {
				if ( array_key_exists( $book->EventCustomerLnkID, $partPerEvent ) ) {
					$book->Participants = $partPerEvent[ $book->EventCustomerLnkID ];
				} else {
					$book->Participants = array();
				}
				?>
				<tr>
					<td><?php echo getDisplayDate( $book->Created, true ); ?></td>
					<td><?php echo $book->EventDescription; ?></td>
					<td><?php echo GetOldStartEndDisplayDate( $book->PeriodStart, $book->PeriodEnd, true ); ?></td>
					<td align="right"><?php echo $book->ParticipantNr; ?></td>
					<td align="right"><?php echo convertToMoney( $book->TotalPrice, $currency ); ?></td>
				</tr>
				<?php
				if ( count( $book->Participants ) > 0 ) {
					?>
					<tr class="edu-participants-row">
						<td colspan="5">
							<table class="edu-event-participantList">
								<tr>
									<th align="left"
									    class="edu-participantList-name"><?php esc_html_e( 'Participant name', 'eduadmin-booking' ); ?></th>
									<th align="center"
									    class="edu-participantList-arrived"><?php esc_html_e( 'Arrived', 'eduadmin-booking' ); ?></th>
									<th align="right"
									    class="edu-participantList-grade"><?php esc_html_e( 'Grade', 'eduadmin-booking' ); ?></th>
								</tr>
								<?php
								foreach ( $book->Participants as $participant ) {
									?>
									<tr>
										<td align="left"><?php echo esc_html( $participant->PersonName ); ?></td>
										<td align="center"><?php echo '1' === $participant->Arrived ? '&#9745;' : '&#9744;'; ?></td>
										<td align="right"><?php echo( ! empty( $participant->GradeName ) ? esc_html( $participant->GradeName ) : '<i>' . esc_html__( 'Not graded', 'eduadmin-booking' ) . '</i>' ); ?></td>
									</tr>
									<?php
								}
								?>
							</table>
						</td>
					</tr>
				<?php } ?>
				<?php
			}
		}
		?>
	</table>
	<?php require_once( 'login_tab_footer.php' ); ?>
</div>
