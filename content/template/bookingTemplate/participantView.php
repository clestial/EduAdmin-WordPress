<div class="participantView">
    <h2><?php _e( "Participant information", 'eduadmin-booking' ); ?></h2>
    <div class="participantHolder" id="edu-participantHolder">
        <div id="contactPersonParticipant" class="participantItem contactPerson" style="display: none;">
            <h3>
				<?php _e( "Participant", 'eduadmin-booking' ); ?>
            </h3>
            <label>
                <div class="inputLabel">
					<?php _e( "Participant name", 'eduadmin-booking' ); ?>
                </div>
                <div class="inputHolder"><input type="text" readonly
                                                class="contactFirstName" class="first-name"
                                                placeholder="<?php _e( "Participant first name", 'eduadmin-booking' ); ?>"/><input
                            type="text" readonly class="contactLastName last-name"
                            placeholder="<?php _e( "Participant surname", 'eduadmin-booking' ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel">
					<?php _e( "E-mail address", 'eduadmin-booking' ); ?>
                </div>
                <div class="inputHolder">
                    <input type="email" readonly class="contactEmail"
                           placeholder="<?php _e( "E-mail address", 'eduadmin-booking' ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php _e( "Phone number", 'eduadmin-booking' ); ?>
                </div>
                <div class="inputHolder">
                    <input type="tel" readonly class="contactPhone"
                           placeholder="<?php _e( "Phone number", 'eduadmin-booking' ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php _e( "Mobile number", 'eduadmin-booking' ); ?>
                </div>
                <div class="inputHolder">
                    <input type="tel" readonly class="contactMobile"
                           placeholder="<?php _e( "Mobile number", 'eduadmin-booking' ); ?>"/>
                </div>
            </label>
			<?php if ( $selectedCourse->RequireCivicRegistrationNumber ) { ?>
                <label>
                    <div class="inputLabel">
						<?php _e( "Civic Registration Number", 'eduadmin-booking' ); ?>
                    </div>
                    <div class="inputHolder">
                        <input type="text" readonly class="contactCivReg"
                               placeholder="<?php _e( "Civic Registration Number", 'eduadmin-booking' ); ?>"/>
                    </div>
                </label>
			<?php } ?>
			<?php

				$so = new XSorting();
				$s  = new XSort( 'SortIndex', 'ASC' );
				$so->AddItem( $s );

				$fo = new XFiltering();
				$f  = new XFilter( 'ShowOnWeb', '=', 'true' );
				$fo->AddItem( $f );
				$f = new XFilter( 'AttributeOwnerTypeID', '=', 3 );
				$fo->AddItem( $f );
				$contactAttributes = EDU()->api->GetAttribute( EDU()->get_token(), $so->ToString(), $fo->ToString() );

				$db = array();

				foreach ( $contactAttributes as $attr ) {
					$data = null;
					foreach ( $db as $d ) {
						if ( $d->AttributeID == $attr->AttributeID ) {
							switch ( $d->AttributeTypeID ) {
								case 1:
									$data = $d->AttributeChecked;
									break;
								case 5:
									$data = $d->AttributeAlternative->AttributeAlternativeID;
									break;
								default:
									$data = $d->AttributeValue;
									break;
							}
							break;
						}
					}
					renderAttribute( $attr, false, "contact", $data );
				}

			?>
			<?php if ( get_option( 'eduadmin-selectPricename', 'firstPublic' ) == "selectParticipant" ) { ?>
                <label>
                    <div class="inputLabel">
						<?php _e( "Price name", 'eduadmin-booking' ); ?>
                    </div>
                    <div class="inputHolder">
                        <select name="contactPriceName" class="edudropdown participantPriceName edu-pricename" required
                                onchange="eduBookingView.UpdatePrice();">
                            <option data-price="0" value=""><?php _e( "Choose price", 'eduadmin-booking' ); ?></option>
							<?php foreach ( $prices as $price ) { ?>
                                <option
                                        data-price="<?php echo esc_attr( $price->Price ); ?>"
                                        date-discountpercent="<?php echo esc_attr( $price->DiscountPercent ); ?>"
                                        data-pricelnkid="<?php echo esc_attr( $price->OccationPriceNameLnkID ); ?>"
                                        data-maxparticipants="<?php echo @esc_attr( $price->MaxPriceNameParticipantNr ); ?>"
                                        data-currentparticipants="<?php echo @esc_attr( $price->ParticipantNr ); ?>"
									<?php if ( $price->MaxPriceNameParticipantNr > 0 && $price->ParticipantNr >= $price->MaxPriceNameParticipantNr ) { ?>
                                        disabled
									<?php } ?>
                                        value="<?php echo esc_attr( $price->OccationPriceNameLnkID ); ?>">
									<?php echo trim( $price->Description ); ?>
                                    (<?php echo convertToMoney( $price->Price, get_option( 'eduadmin-currency', 'SEK' ) ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ); ?>
                                    )
                                </option>
							<?php } ?>
                        </select>
                    </div>
                </label>
			<?php } ?>
			<?php
				if ( count( $subEvents ) > 0 ) {
					echo "<h4>" . __( "Sub events", 'eduadmin-booking' ) . "</h4>\n";
					foreach ( $subEvents as $subEvent ) {
						if ( count( $sePrice[ $subEvent->OccasionID ] ) > 0 ) {
							$s = current( $sePrice[ $subEvent->OccasionID ] )->Price;
						} else {
							$s = 0;
						}
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
					echo "<br />";
				}
			?>
        </div>
        <div class="participantItem template" style="display: none;">
            <h3>
				<?php _e( "Participant", 'eduadmin-booking' ); ?>
                <div class="removeParticipant"
                     onclick="eduBookingView.RemoveParticipant(this);"><?php _e( "Remove", 'eduadmin-booking' ); ?></div>
            </h3>
            <label>
                <div class="inputLabel">
					<?php _e( "Participant name", 'eduadmin-booking' ); ?>
                </div>
                <div class="inputHolder"><input type="text"
                                                class="participantFirstName first-name" name="participantFirstName[]"
                                                placeholder="<?php _e( "Participant first name", 'eduadmin-booking' ); ?>"/><input
                            type="text" class="participantLastName last-name"
                            name="participantLastName[]"
                            placeholder="<?php _e( "Participant surname", 'eduadmin-booking' ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel">
					<?php _e( "E-mail address", 'eduadmin-booking' ); ?>
                </div>
                <div class="inputHolder">
                    <input type="email" name="participantEmail[]"
                           placeholder="<?php _e( "E-mail address", 'eduadmin-booking' ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php _e( "Phone number", 'eduadmin-booking' ); ?>
                </div>
                <div class="inputHolder">
                    <input type="tel" name="participantPhone[]"
                           placeholder="<?php _e( "Phone number", 'eduadmin-booking' ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php _e( "Mobile number", 'eduadmin-booking' ); ?>
                </div>
                <div class="inputHolder">
                    <input type="tel" name="participantMobile[]"
                           placeholder="<?php _e( "Mobile number", 'eduadmin-booking' ); ?>"/>
                </div>
            </label>
			<?php if ( $selectedCourse->RequireCivicRegistrationNumber ) { ?>
                <label>
                    <div class="inputLabel">
						<?php _e( "Civic Registration Number", 'eduadmin-booking' ); ?>
                    </div>
                    <div class="inputHolder">
                        <input type="text" data-required="true" name="participantCivReg[]"
                               pattern="(\d{2,4})-?(\d{2,2})-?(\d{2,2})-?(\d{4,4})" class="eduadmin-civicRegNo"
                               placeholder="<?php _e( "Civic Registration Number", 'eduadmin-booking' ); ?>"/>
                    </div>
                </label>
			<?php } ?>
			<?php

				$so = new XSorting();
				$s  = new XSort( 'SortIndex', 'ASC' );
				$so->AddItem( $s );

				$fo = new XFiltering();
				$f  = new XFilter( 'ShowOnWeb', '=', 'true' );
				$fo->AddItem( $f );
				$f = new XFilter( 'AttributeOwnerTypeID', '=', 3 );
				$fo->AddItem( $f );
				$contactAttributes = EDU()->api->GetAttribute( EDU()->get_token(), $so->ToString(), $fo->ToString() );

				foreach ( $contactAttributes as $attr ) {
					renderAttribute( $attr, true );
				}

			?>
			<?php if ( get_option( 'eduadmin-selectPricename', 'firstPublic' ) == "selectParticipant" ) { ?>
                <label>
                    <div class="inputLabel">
						<?php _e( "Price name", 'eduadmin-booking' ); ?>
                    </div>
                    <div class="inputHolder">
                        <select name="participantPriceName[]" required
                                class="edudropdown participantPriceName edu-pricename"
                                onchange="eduBookingView.UpdatePrice();">
                            <option data-price="0" value=""><?php _e( "Choose price", 'eduadmin-booking' ); ?></option>
							<?php foreach ( $prices as $price ) { ?>
                                <option
                                        data-price="<?php echo esc_attr( $price->Price ); ?>"
                                        date-discountpercent="<?php echo esc_attr( $price->DiscountPercent ); ?>"
                                        data-pricelnkid="<?php echo esc_attr( $price->OccationPriceNameLnkID ); ?>"
                                        data-maxparticipants="<?php echo @esc_attr( $price->MaxPriceNameParticipantNr ); ?>"
                                        data-currentparticipants="<?php echo @esc_attr( $price->ParticipantNr ); ?>"
									<?php if ( $price->MaxPriceNameParticipantNr > 0 && $price->ParticipantNr >= $price->MaxPriceNameParticipantNr ) { ?>
                                        disabled
									<?php } ?>
                                        value="<?php echo esc_attr( $price->OccationPriceNameLnkID ); ?>">
									<?php echo trim( $price->Description ); ?>
                                    (<?php echo convertToMoney( $price->Price, get_option( 'eduadmin-currency', 'SEK' ) ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ); ?>
                                    )
                                </option>
							<?php } ?>
                        </select>
                    </div>
                </label>
			<?php } ?>
			<?php
				if ( count( $subEvents ) > 0 ) {
					echo "<h4>" . __( "Sub events", 'eduadmin-booking' ) . "</h4>\n";
					foreach ( $subEvents as $subEvent ) {
						if ( count( $sePrice[ $subEvent->OccasionID ] ) > 0 ) {
							$s = current( $sePrice[ $subEvent->OccasionID ] )->Price;
						} else {
							$s = 0;
						}
						// PriceNameVat
						echo "<label>" .
						     "<input class=\"subEventCheckBox\" data-price=\"" . $s . "\" onchange=\"eduBookingView.UpdatePrice();\" " .
						     "name=\"participantSubEvent_" . $subEvent->EventID . "[]\" " .
						     "type=\"checkbox\"" .
						     ( $subEvent->SelectedByDefault == true || $subEvent->MandatoryParticipation == true ? " checked=\"checked\"" : "" ) .
						     ( $subEvent->MandatoryParticipation == true ? " disabled=\"disabled\"" : "" ) .
						     " value=\"" . $subEvent->EventID . "\"> " .
						     $subEvent->Description .
						     ( $hideSubEventDateInfo ? "" : " (" . date( "d/m H:i", strtotime( $subEvent->StartDate ) ) . " - " . date( "d/m H:i", strtotime( $subEvent->EndDate ) ) . ") " ) .
						     ( $s > 0 ? " <i class=\"priceLabel\">" . convertToMoney( $s ) . "</i>" : "" ) .
						     "</label>\n";
					}
					echo "<br />";
				}
			?>
        </div>
    </div>
    <div>
        <a href="javascript://" class="addParticipantLink neutral-btn"
           onclick="eduBookingView.AddParticipant(); return false;">+ <?php _e( "Add participant", 'eduadmin-booking' ); ?></a>
    </div>
    <div class="edu-modal warning" id="edu-warning-participants">
		<?php _e( "You cannot add any more participants.", 'eduadmin-booking' ); ?>
    </div>
</div>