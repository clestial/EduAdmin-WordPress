<?php
	$user     = EDU()->session['eduadmin-loginUser'];
	$contact  = $user->Contact;
	$customer = $user->Customer;

	global $eduapi;
	global $edutoken;
?>
<div class="eduadmin">
	<?php
		$tab = "limitedDiscount";
		include_once( "login_tab_header.php" );
	?>
    <h2><?php edu_e( "Discount Cards" ); ?></h2>
	<?php
		$f  = new XFiltering();
		$ft = new XFilter( 'CustomerID', '=', $customer->CustomerID );
		$f->AddItem( $ft );

		$ft = new XFilter( 'Disabled', '=', false );
		$f->AddItem( $ft );
		$cards    = $eduapi->GetLimitedDiscount( $edutoken, '', $f->ToString() );
		$currency = get_option( 'eduadmin-currency', 'SEK' );
	?>
    <table class="myReservationsTable">
        <tr>
            <th align="left"><?php edu_e( "Card name" ); ?></th>
            <th align="left"><?php edu_e( "Valid" ); ?></th>
            <th align="right"><?php edu_e( "Credits" ); ?></th>
            <th align="right"><?php edu_e( "Discount" ); ?></th>
            <th align="right"><?php edu_e( "Price" ); ?></th>
        </tr>
		<?php
			if ( empty( $cards ) ) {
				?>
                <tr>
                    <td colspan="4" align="center">
                        <i><?php edu_e( "You don't have any discount cards registered." ); ?></i>
                    </td>
                </tr>
				<?php
			} else {
				foreach ( $cards as $card ) {
					?>
                    <tr>
                        <td><?php echo $card->PublicName; ?></td>
                        <td><?php echo GetOldStartEndDisplayDate( $card->ValidFrom, $card->ValidTo, false ); ?></td>
                        <td align="right"><?php echo $card->CreditLeft . ' / ' . $card->CreditStartValue; ?></td>
                        <td align="right"><?php echo $card->DiscountPercent; ?> %</td>
                        <td align="right"><?php echo convertToMoney( $card->Price, $currency ); ?></td>
                    </tr>
				<?php }
			} ?>
    </table>
	<?php include_once( "login_tab_footer.php" ); ?>
</div>