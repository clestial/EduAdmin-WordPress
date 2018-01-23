<div class="participantView">
    <h2><?php edu_e( "Participant information" ); ?></h2>
    <div class="participantHolder" id="edu-participantHolder">
        <div id="contactPersonParticipant" class="participantItem contactPerson" style="display: none;">
            <h3>
				<?php edu_e( "Participant" ); ?>
            </h3>
            <label>
                <div class="inputLabel">
					<?php edu_e( "Participant name" ); ?>
                </div>
                <div class="inputHolder"><input type="text" readonly style="width: 50%; display: inline;"
                                                class="contactFirstName"
                                                placeholder="<?php edu_e( "Participant first name" ); ?>"/><input
                            type="text" readonly style="width: 50%; display: inline;" class="contactLastName"
                            placeholder="<?php edu_e( "Participant surname" ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel">
					<?php edu_e( "E-mail address" ); ?>
                </div>
                <div class="inputHolder">
                    <input type="email" readonly class="contactEmail"
                           placeholder="<?php edu_e( "E-mail address" ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php edu_e( "Phone number" ); ?>
                </div>
                <div class="inputHolder">
                    <input type="tel" readonly class="contactPhone" placeholder="<?php edu_e( "Phone number" ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php edu_e( "Mobile number" ); ?>
                </div>
                <div class="inputHolder">
                    <input type="tel" readonly class="contactMobile" placeholder="<?php edu_e( "Mobile number" ); ?>"/>
                </div>
            </label>
			<?php if ( $selectedCourse->RequireCivicRegistrationNumber ) { ?>
                <label>
                    <div class="inputLabel">
						<?php edu_e( "Civic Registration Number" ); ?>
                    </div>
                    <div class="inputHolder">
                        <input type="text" readonly class="contactCivReg"
                               placeholder="<?php edu_e( "Civic Registration Number" ); ?>"/>
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
				$contactAttributes = EDU()->api->GetAttribute( $edutoken, $so->ToString(), $fo->ToString() );

				$db = array();
				/*if($contact->PersonID != 0) {
					$fo = new XFiltering();
					$f = new XFilter('PersonID', '=', $contact->PersonID);
					$fo->AddItem($f);
					$db = $eduapi->GetPersonAttribute($edutoken, '', $fo->ToString());
				}*/

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
						<?php edu_e( "Price name" ); ?>
                    </div>
                    <div class="inputHolder">
                        <select name="contactPriceName" class="edudropdown participantPriceName edu-pricename" required
                                onchange="eduBookingView.UpdatePrice();">
                            <option value=""><?php edu_e( "Choose price" ); ?></option>
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
                                    (<?php echo convertToMoney( $price->Price, get_option( 'eduadmin-currency', 'SEK' ) ) . " " . edu__( $incVat ? "inc vat" : "ex vat" ); ?>
                                    )
                                </option>
							<?php } ?>
                        </select>
                    </div>
                </label>
			<?php } ?>
			<?php
				if ( count( $subEvents ) > 0 ) {
					echo "<h4>" . edu__( "Sub events" ) . "</h4>\n";
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
				<?php edu_e( "Participant" ); ?>
                <div class="removeParticipant"
                     onclick="eduBookingView.RemoveParticipant(this);"><?php edu_e( "Remove" ); ?></div>
            </h3>
            <label>
                <div class="inputLabel">
					<?php edu_e( "Participant name" ); ?>
                </div>
                <div class="inputHolder"><input type="text" style="width: 50%; display: inline;"
                                                class="participantFirstName" name="participantFirstName[]"
                                                placeholder="<?php edu_e( "Participant first name" ); ?>"/><input
                            type="text" style="width: 50%; display: inline;" class="participantLastName"
                            name="participantLastName[]" placeholder="<?php edu_e( "Participant surname" ); ?>"/></div>
            </label>
            <label>
                <div class="inputLabel">
					<?php edu_e( "E-mail address" ); ?>
                </div>
                <div class="inputHolder">
                    <input type="email" name="participantEmail[]" placeholder="<?php edu_e( "E-mail address" ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php edu_e( "Phone number" ); ?>
                </div>
                <div class="inputHolder">
                    <input type="tel" name="participantPhone[]" placeholder="<?php edu_e( "Phone number" ); ?>"/>
                </div>
            </label>
            <label>
                <div class="inputLabel">
					<?php edu_e( "Mobile number" ); ?>
                </div>
                <div class="inputHolder">
                    <input type="tel" name="participantMobile[]" placeholder="<?php edu_e( "Mobile number" ); ?>"/>
                </div>
            </label>
			<?php if ( $selectedCourse->RequireCivicRegistrationNumber ) { ?>
                <label>
                    <div class="inputLabel">
						<?php edu_e( "Civic Registration Number" ); ?>
                    </div>
                    <div class="inputHolder">
                        <input type="text" data-required="true" name="participantCivReg[]"
                               pattern="(\d{2,4})-?(\d{2,2})-?(\d{2,2})-?(\d{4,4})" class="eduadmin-civicRegNo"
                               placeholder="<?php edu_e( "Civic Registration Number" ); ?>"/>
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
				$contactAttributes = EDU()->api->GetAttribute( $edutoken, $so->ToString(), $fo->ToString() );

				foreach ( $contactAttributes as $attr ) {
					renderAttribute( $attr, true );
				}

			?>
			<?php if ( get_option( 'eduadmin-selectPricename', 'firstPublic' ) == "selectParticipant" ) { ?>
                <label>
                    <div class="inputLabel">
						<?php edu_e( "Price name" ); ?>
                    </div>
                    <div class="inputHolder">
                        <select name="participantPriceName[]" required
                                class="edudropdown participantPriceName edu-pricename"
                                onchange="eduBookingView.UpdatePrice();">
                            <option value=""><?php edu_e( "Choose price" ); ?></option>
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
                                    (<?php echo convertToMoney( $price->Price, get_option( 'eduadmin-currency', 'SEK' ) ) . " " . edu__( $incVat ? "inc vat" : "ex vat" ); ?>
                                    )
                                </option>
							<?php } ?>
                        </select>
                    </div>
                </label>
			<?php } ?>
			<?php
				if ( count( $subEvents ) > 0 ) {
					echo "<h4>" . edu__( "Sub events" ) . "</h4>\n";
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
        <a href="javascript://" class="addParticipantLink"
           onclick="eduBookingView.AddParticipant(); return false;"><?php edu_e( "Add participant" ); ?></a>
    </div>
    <div class="edu-modal warning" id="edu-warning-participants">
		<?php edu_e( "You cannot add any more participants." ); ?>
    </div>
</div>