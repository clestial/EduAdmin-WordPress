<?php
	if ( isset( $customer->CustomerID ) && isset( $contact->CustomerContactID ) ) {
		$f  = new XFiltering();
		$ft = new XFilter( 'CustomerID', '=', $customer->CustomerID );
		$f->AddItem( $ft );

		$cards = $eduapi->GetLimitedDiscount( $edutoken, '', $f->ToString() );

		$cCards   = array();
		$cCardIds = array();
		foreach ( $cards as $card ) {
			$addCard = false;
			if ( empty( $card->CustomerContactID ) || empty( $card->CategoryID ) ) {
				$addCard = true;
			}

			if ( $card->CustomerContactID == $contact->CustomerContactID ) {
				$addCard = true;
			}
			if ( $card->CategoryID == $selectedCourse->CategoryID ) {
				$addCard = true;
			}

			if ( ! empty( $card->CategoryID ) && $card->CategoryID != $selectedCourse->CategoryID ) {
				$addCard = false;
			}
			if ( ! empty( $card->CustomerContactID ) && $card->CustomerContactID != $contact->CustomerContactID ) {
				$addCard = false;
			}

			if ( $addCard ) {
				$cCards[]   = $card;
				$cCardIds[] = $card->LimitedDiscountID;
			}
		}

		$f  = new XFiltering();
		$ft = new XFilter( 'LimitedDiscountID', 'in', join( ',', $cCardIds ) );
		$f->AddItem( $ft );

		$objectCards = $eduapi->GetLimitedDiscountObjectStatus( $edutoken, '', $f->ToString() );
		$cCardIds    = array();

		$cardCosts = array();

		foreach ( $objectCards as $oCard ) {
			$addCard = false;
			if ( $oCard->ObjectID == $selectedCourse->ObjectID ) {
				$addCard = true;
			}

			if ( $addCard && ! in_array( $oCard->LimitedDiscountID, $cCardIds ) ) {
				$cCardIds[]                             = $oCard->LimitedDiscountID;
				$cardCosts[ $oCard->LimitedDiscountID ] = $oCard->CreditCount;
			}
		}

		if ( count( $objectCards ) > 0 && count( $cCardIds ) == 0 ) {
			$cCards = array();
		}

		array_filter( $cCards, function( $card ) use ( &$cCardIds ) {
			$valid = false;
			foreach ( $cCardIds as $cid ) {
				if ( $cid == $card->LimitedDiscountID ) {

					$valid = true;

					if ( $card->CreditLeft <= 0 ) {
						$valid = false;
					}

					if ( $card->ValidFrom != null && $card->ValidFrom > date( "Y-m-d H:i:s" ) ) {
						$valid = false;
					}

					if ( $card->ValidTo != null && $card->ValidTo < date( "Y-m-d H:i:s" ) ) {
						$valid = false;
					}
				}
			}

			return $valid;
		} );
		?>
        <div class="discountCardView">
			<?php
				if ( 0 !== count( $cCards ) ) {
					?>
                    <h2><?php edu_e( "Discount cards" ); ?></h2>

					<?php
					foreach ( $cCards as $card ) {
						if ( $card->CreditLeft > 0 ) {
							$enoughCredits = ( $card->CreditLeft >= $cardCosts[ $card->LimitedDiscountID ] );
							?>
                            <label class="discountCardItem">
                                <input type="radio"
                                       name="edu-limitedDiscountID"
									<?php if ( ! $enoughCredits ) : ?>
                                        disabled readonly title="<?php edu_e( "Not enough uses left on this card." ); ?>"
									<?php endif; ?>
                                       value="<?php echo $card->LimitedDiscountID; ?>"/>
								<?php echo $card->PublicName; ?>
                                <i>(<?php echo sprintf( edu__( "Uses left: %s / %s" ), $card->CreditLeft, $card->CreditStartValue ); ?>
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