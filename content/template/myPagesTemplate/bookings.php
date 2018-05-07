<?php
$user     = EDU()->session['eduadmin-loginUser'];
$contact  = $user->Contact;
$customer = $user->Customer;

?>
<div class="eduadmin">
	<?php
	$tab = 'bookings';
	require_once 'login-tab-header.php';
	?>
	<h2><?php esc_html_e( 'Reservations', 'eduadmin-booking' ); ?></h2>
	<?php

	$events = EDUAPI()->OData->Events->Search(
		null,
		'Bookings/any(b:b/Customer/CustomerId eq ' . $customer->CustomerId . ')',
		'Bookings($expand=Participants;$filter=Customer/CustomerId eq ' . $customer->CustomerId . ' and NumberOfParticipants gt 0;)'
	);

	$bookings = array();
	foreach ( $events['value'] as $ev ) {
		$_bookings = $ev['Bookings'];
		foreach ( $_bookings as $booking ) {
			unset( $ev['Bookings'] );
			$booking['Event'] = $ev;

			$bookings[ $booking['Created'] . '-' . $booking['BookingId'] ] = $booking;
		}
	}

	krsort( $bookings );

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
				$name = $book['Event']['EventName'] !== $book['Event']['CourseName'] ? $book['Event']['EventName'] : $book['Event']['CourseName'];
				if ( empty( $name ) ) {
					$name = $book['Event']['InternalCourseName'];
				}
				?>
				<tr>
					<td><?php echo wp_kses_post( get_display_date( $book['Created'], true ) ); ?></td>
					<td><?php echo esc_html( $name ); ?></td>
					<td><?php echo wp_kses_post( get_old_start_end_display_date( $book['Event']['StartDate'], $book['Event']['EndDate'], true ) ); ?></td>
					<td align="right"><?php echo esc_html( $book['NumberOfParticipants'] ); ?></td>
					<td align="right"><?php echo esc_html( convert_to_money( $book['TotalPriceIncVat'], $currency ) ); ?></td>
				</tr>
				<?php
				if ( ! empty( $book['Participants'] ) ) {
					?>
					<tr class="edu-participants-row">
						<td colspan="5">
							<table class="edu-event-participantList">
								<tr>
									<th align="left" class="edu-participantList-name"><?php esc_html_e( 'Participant name', 'eduadmin-booking' ); ?></th>
									<th align="center" class="edu-participantList-arrived"><?php esc_html_e( 'Arrived', 'eduadmin-booking' ); ?></th>
									<th align="right" class="edu-participantList-grade"><?php esc_html_e( 'Grade', 'eduadmin-booking' ); ?></th>
								</tr>
								<?php
								foreach ( $book['Participants'] as $participant ) {
									?>
									<tr>
										<td align="left"><?php echo esc_html( $participant['FirstName'] . ' ' . $participant['LastName'] ); ?></td>
										<td align="center"><?php echo true === $participant['Arrived'] ? '&#9745;' : '&#9744;'; ?></td>
										<td align="right"><?php echo( ! empty( $participant['GradeName'] ) ? esc_html( $participant['GradeName'] ) : '<i>' . esc_html__( 'Not graded', 'eduadmin-booking' ) . '</i>' ); ?></td>
									</tr>
									<?php
								}
								?>
							</table>
						</td>
					</tr>
					<?php
				}
			}
		}
		?>
	</table>
	<?php require_once 'login-tab-footer.php'; ?>
</div>
