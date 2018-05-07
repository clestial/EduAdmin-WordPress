<?php
$user     = EDU()->session['eduadmin-loginUser'];
$contact  = $user->Contact;
$customer = $user->Customer;

$currency = get_option( 'eduadmin-currency', 'SEK' );
?>
<div class="eduadmin">
	<?php

	$tab = 'limitedDiscount';

	require_once 'login-tab-header.php';
	?>
	<h2><?php esc_html_e( 'Discount Cards', 'eduadmin-booking' ); ?></h2>
	<?php
	$cards = EDUAPI()->OData->Customers->GetItem( $customer->CustomerId, '', 'Vouchers' )['Vouchers'];
	?>
	<table class="myReservationsTable">
		<tr>
			<th align="left"><?php esc_html_e( 'Card name', 'eduadmin-booking' ); ?></th>
			<th align="left"><?php esc_html_e( 'Valid', 'eduadmin-booking' ); ?></th>
			<th align="right"><?php esc_html_e( 'Credits', 'eduadmin-booking' ); ?></th>
			<th align="right"><?php esc_html_e( 'Discount', 'eduadmin-booking' ); ?></th>
			<th align="right"><?php esc_html_e( 'Price', 'eduadmin-booking' ); ?></th>
		</tr>
		<?php
		if ( empty( $cards ) ) {
			?>
			<tr>
				<td colspan="4" align="center">
					<i><?php esc_html_e( 'You don\'t have any discount cards registered.', 'eduadmin-booking' ); ?></i>
				</td>
			</tr>
			<?php
		} else {
			foreach ( $cards as $card ) {
				?>
				<tr>
					<td><?php echo esc_html( $card['Description'] ); ?></td>
					<td><?php echo wp_kses_post( get_old_start_end_display_date( $card['ValidFrom'], $card['ValidTo'], true ) ); ?></td>
					<td align="right"><?php echo esc_html( $card['CreditsLeft'] . ' / ' . $card['CreditsStartValue'] ); ?></td>
					<td align="right"><?php echo esc_html( $card['DiscountPercent'] ); ?> %</td>
					<td align="right"><?php echo esc_html( convert_to_money( $card['Price'], $currency ) ); ?></td>
				</tr>
				<?php
			}
		}
		?>
	</table>
	<?php require_once 'login-tab-footer.php'; ?>
</div>
