<?php
	if ( isset( $customer->CustomerId ) && isset( $contact->PersonId ) && $eventid > 0 ) {
		$cCards = EDUAPI()->REST->Customer->GetValidVouchers( $customer->CustomerId, $eventid, $contact->PersonId );
		unset( $cCards['@curl'] );
		?>
        <div class="discountCardView">
			<?php
				if ( 0 !== count( $cCards ) ) {
					?>
                    <h2><?php _e( "Discount cards", 'eduadmin-booking' ); ?></h2>

					<?php
					foreach ( $cCards as $card ) {
						if ( $card["ValidForNumberOfParticipants"] > 0 ) {
							$enoughCredits = true;
							?>
                            <label class="discountCardItem">
                                <input type="radio"
                                       name="edu-limitedDiscountID"
	                                <?php if ( ! $enoughCredits ) : ?>
                                        disabled readonly title="<?php _e( "Not enough uses left on this card.", 'eduadmin-booking' ); ?>"
									<?php endif; ?>
                                       value="<?php echo $card["VoucherId"]; ?>"/>
	                            <?php echo $card["Description"]; ?>&nbsp;
                                <i>(<?php echo sprintf( _n( "Valid for %s participant", "Valid for %s participants", $card["ValidForNumberOfParticipants"], 'eduadmin-booking' ), $card["ValidForNumberOfParticipants"] ); ?>
                                    )</i>
                            </label>
							<?php
						}
					}
				}
			?>
            <br/>
        </div>
		<?php
	}