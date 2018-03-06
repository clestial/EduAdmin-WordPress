<div class="participantItem template" style="display: none;">
	<h3>
		<?php esc_html_e( 'Participant', 'eduadmin-booking' ); ?>
		<div class="removeParticipant" onclick="eduBookingView.RemoveParticipant(this);"><?php esc_html_e( 'Remove', 'eduadmin-booking' ); ?></div>
	</h3>
	<label>
		<div class="inputLabel">
			<?php esc_html_e( 'Participant name', 'eduadmin-booking' ); ?>
		</div>
		<div class="inputHolder">
			<input type="text" class="participantFirstName first-name" name="participantFirstName[]" placeholder="<?php esc_attr_e( 'Participant first name', 'eduadmin-booking' ); ?>"/><input type="text" class="participantLastName last-name" name="participantLastName[]" placeholder="<?php esc_attr_e( 'Participant surname', 'eduadmin-booking' ); ?>"/>
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php esc_html_e( 'E-mail address', 'eduadmin-booking' ); ?>
		</div>
		<div class="inputHolder">
			<input type="email" name="participantEmail[]" placeholder="<?php esc_attr_e( 'E-mail address', 'eduadmin-booking' ); ?>"/>
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php esc_html_e( 'Phone number', 'eduadmin-booking' ); ?>
		</div>
		<div class="inputHolder">
			<input type="tel" name="participantPhone[]" placeholder="<?php esc_attr_e( 'Phone number', 'eduadmin-booking' ); ?>"/>
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php esc_html_e( 'Mobile number', 'eduadmin-booking' ); ?>
		</div>
		<div class="inputHolder">
			<input type="tel" name="participantMobile[]" placeholder="<?php esc_attr_e( 'Mobile number', 'eduadmin-booking' ); ?>"/>
		</div>
	</label>
	<?php if ( $selected_course['RequireCivicRegistrationNumber'] ) { ?>
		<label>
			<div class="inputLabel">
				<?php esc_html_e( 'Civic Registration Number', 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" data-required="true" name="participantCivReg[]" pattern="(\d{2,4})-?(\d{2,2})-?(\d{2,2})-?(\d{4,4})" class="eduadmin-civicRegNo" placeholder="<?php esc_attr_e( 'Civic Registration Number', 'eduadmin-booking' ); ?>"/>
			</div>
		</label>
	<?php } ?>
	<?php

	foreach ( $contact_custom_fields as $attr ) {
		render_attribute( $attr, true, 'participant' );
	}

	?>
	<?php if ( 'selectParticipant' === get_option( 'eduadmin-selectPricename', 'firstPublic' ) ) { ?>
		<label>
			<div class="inputLabel">
				<?php esc_html_e( 'Price name', 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<select name="participantPriceName[]" data-required="true" class="edudropdown participantPriceName edu-pricename" onchange="eduBookingView.UpdatePrice();">
					<option data-price="0" value=""><?php esc_html_e( 'Choose price', 'eduadmin-booking' ); ?></option>
					<?php foreach ( $unique_prices as $price ) { ?>
						<option data-price="<?php echo esc_attr( $price['Price'] ); ?>" date-discountpercent="<?php echo esc_attr( $price['DiscountPercent'] ); ?>" data-maxparticipants="<?php echo esc_attr( $price['MaxParticipantNumber'] ); ?>" data-currentparticipants="<?php echo esc_attr( $price['NumberOfParticipants'] ); ?>"
							<?php if ( $price['MaxParticipantNumber'] > 0 && $price['NumberOfParticipants'] >= $price['MaxParticipantNumber'] ) { ?>
								disabled
							<?php } ?>
								value="<?php echo esc_attr( $price['PriceNameId'] ); ?>">
							<?php echo esc_html( $price['PriceNameDescription'] ); ?>
							(<?php echo esc_html( convert_to_money( $price['Price'], get_option( 'eduadmin-currency', 'SEK' ) ) . ' ' . ( $inc_vat ? __( 'inc vat', 'eduadmin-booking' ) : __( 'ex vat', 'eduadmin-booking' ) ) ); ?>)
						</option>
					<?php } ?>
				</select>
			</div>
		</label>
	<?php } ?>
	<?php
	if ( ! empty( $event['Sessions'] ) ) {
		echo '<h4>' . esc_html__( 'Sub events', 'eduadmin-booking' ) . "</h4>\n";
		foreach ( $event['Sessions'] as $sub_event ) {
			if ( count( $sub_event['PriceNames'] ) > 0 ) {
				$s = current( $sub_event['PriceNames'] )['Price'];
			} else {
				$s = 0;
			}

			echo '<label>';
			echo '<input class="subEventCheckBox" data-price="' . esc_attr( $s ) . '" onchange=eduBookingView.UpdatePrice();" data-replace="name|index" ';
			echo 'data-name-template="participantSubEvent_' . esc_attr( $sub_event['SessionId'] ) . '_{{index}}" ';
			echo 'name="participantSubEvent_' . esc_attr( $sub_event['SessionId'] ) . '_-1" ';
			echo 'type="checkbox"';
			echo( $sub_event['SelectedByDefault'] || $sub_event['MandatoryParticipation'] ? ' checked="checked"' : '' );
			echo( $sub_event['MandatoryParticipation'] ? ' disabled="disabled"' : '' );
			echo ' value="' . esc_attr( $sub_event['SessionId'] ) . '"> ';
			echo esc_html( wp_strip_all_tags( $sub_event['SessionName'] ) );
			echo esc_html( $hide_sub_event_date_info ? '' : ' (' . date( 'd/m H:i', strtotime( $sub_event['StartDate'] ) ) . ' - ' . date( 'd/m H:i', strtotime( $sub_event['EndDate'] ) ) . ') ' );
			echo( intval( $s ) > 0 ? '&nbsp;<i class="priceLabel">' . esc_html( convert_to_money( $s ) ) . '</i>' : '' );
			echo "</label>\n";
		}
		echo '<br />';
	}

	foreach ( $participant_questions as $question ) {
		render_question( $question, true, 'participant' );
	}
	?>
</div>
