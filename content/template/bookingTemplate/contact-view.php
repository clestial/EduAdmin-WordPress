<?php
$block_edit_if_logged_in = get_option( 'eduadmin-blockEditIfLoggedIn', true );
$__block                 = ( $block_edit_if_logged_in && isset( $contact->PersonId ) && 0 !== $contact->PersonId );
if ( isset( $contact->PersonId ) && 0 !== $contact->PersonId ) {
	echo '<input type="hidden" name="edu-contactId" value="' . esc_attr( $contact->PersonId ) . '" />';
}
?>
<div class="contactView">
	<h2><?php esc_html_e( 'Contact information', 'eduadmin-booking' ); ?></h2>
	<label>
		<div class="inputLabel">
			<?php esc_html_e( 'Contact name', 'eduadmin-booking' ); ?>
		</div>
		<div class="inputHolder"><input type="text"
				<?php echo( $__block ? ' readonly' : '' ); ?>
				required onchange="eduBookingView.ContactAsParticipant();" autocomplete="given-name" class="first-name" id="edu-contactFirstName" name="contactFirstName" placeholder="<?php esc_attr_e( 'Contact first name', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $contact->FirstName ); ?>" /><input type="text" <?php echo( $__block ? ' readonly' : '' ); ?>
				required onchange="eduBookingView.ContactAsParticipant();" id="edu-contactLastName" class="last-name" autocomplete="family-name" name="contactLastName" placeholder="<?php esc_attr_e( 'Contact surname', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $contact->LastName ); ?>" />
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php esc_html_e( 'E-mail address', 'eduadmin-booking' ); ?>
		</div>
		<div class="inputHolder">
			<input type="email" id="edu-contactEmail" required name="contactEmail"<?php echo( $__block ? ' readonly' : '' ); ?> autocomplete="email" onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php esc_attr_e( 'E-mail address', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $contact->Email ); ?>" />
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php esc_html_e( 'Phone number', 'eduadmin-booking' ); ?>
		</div>
		<div class="inputHolder">
			<input type="tel" id="edu-contactPhone" name="contactPhone" autocomplete="tel" onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php esc_attr_e( 'Phone number', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $contact->Phone ); ?>" />
		</div>
	</label>
	<label>
		<div class="inputLabel">
			<?php esc_html_e( 'Mobile number', 'eduadmin-booking' ); ?>
		</div>
		<div class="inputHolder">
			<input type="tel" id="edu-contactMobile" name="contactMobile" autocomplete="tel" onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php esc_attr_e( 'Mobile number', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $contact->Mobile ); ?>" />
		</div>
	</label>
	<?php $selected_login_field = get_option( 'eduadmin-loginField', 'Email' ); ?>
	<?php if ( $selected_course['RequireCivicRegistrationNumber'] || 'CivicRegistrationNumber' === $selected_login_field ) { ?>
		<label>
			<div class="inputLabel">
				<?php esc_html_e( 'Civic Registration Number', 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" id="edu-contactCivReg" class="eduadmin-civicRegNo" pattern="(\d{2,4})-?(\d{2,2})-?(\d{2,2})-?(\d{4,4})" <?php echo( 'CivicRegistrationNumber' === $selected_login_field ? 'required' : '' ); ?> name="contactCivReg" onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php esc_attr_e( 'Civic Registration Number', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $contact->CivicRegistrationNumber ); ?>" />
			</div>
		</label>
	<?php } ?>
	<?php
	if ( get_option( 'eduadmin-useLogin', false ) && ! $contact->CanLogin ) {
		?>
		<label>
			<div class="inputLabel">
				<?php esc_html_e( 'Please enter a password', 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="password" required name="contactPass" autocomplete="new-password" placeholder="<?php esc_attr_e( 'Please enter a password', 'eduadmin-booking' ); ?>" />
			</div>
		</label>
		<?php
	}

	$contact_custom_fields = EDUAPI()->OData->CustomFields->Search(
		null,
		'ShowOnWeb and CustomFieldOwner eq \'Person\'',
		'CustomFieldAlternatives'
	)['value'];

	foreach ( $contact_custom_fields as $custom_field ) {
		$data = null;
		foreach ( $contact->CustomFields as $cf ) {
			if ( $cf->CustomFieldId === $custom_field['CustomFieldId'] ) {
				switch ( $cf->CustomFieldType ) {
					case 'Checkbox':
						$data = $cf->CustomFieldChecked;
						break;
					case 'Dropdown':
						$data = $cf->CustomFieldAlternativeId;
						break;
					default:
						$data = $cf->CustomFieldValue;
						break;
				}
				break;
			}
		}
		render_attribute( $custom_field, false, 'contact', $data );
	}

	?>

	<label>
		<div class="inputHolder contactIsAlsoParticipant">
			<label class="inline-checkbox" for="contactIsAlsoParticipant">
				<input type="checkbox" id="contactIsAlsoParticipant" name="contactIsAlsoParticipant" value="true" onchange="if(eduBookingView.CheckParticipantCount()) { eduBookingView.ContactAsParticipant(); } else { this.checked = false; return false; }" />
				<?php esc_html_e( 'I am also participating', 'eduadmin-booking' ); ?>
			</label>
		</div>
	</label>
	<div class="edu-modal warning" id="edu-warning-participants-contact">
		<?php esc_html_e( 'You cannot add any more participants.', 'eduadmin-booking' ); ?>
	</div>
</div>
