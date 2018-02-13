<?php
	$blockEditIfLoggedIn = get_option( 'eduadmin-blockEditIfLoggedIn', true );
	$__block             = ( $blockEditIfLoggedIn && $contact->PersonId != 0 );
?>
<div class="contactView">
    <h2><?php _e( "Contact information", 'eduadmin-booking' ); ?></h2>
    <label>
        <div class="inputLabel">
			<?php _e( "Contact name", 'eduadmin-booking' ); ?>
        </div>
        <div class="inputHolder"><input type="text"
				<?php echo( $__block ? " readonly" : "" ); ?>
                                        required onchange="eduBookingView.ContactAsParticipant();"
                                        autocomplete="given-name" class="first-name"
                                        id="edu-contactFirstName" name="contactFirstName"
                                        placeholder="<?php _e( "Contact first name", 'eduadmin-booking' ); ?>"
                                        value="<?php echo @esc_attr( $contact->FirstName ); ?>"/><input
                    type="text" <?php echo( $__block ? " readonly" : "" ); ?>
                    required onchange="eduBookingView.ContactAsParticipant();" id="edu-contactLastName"
                    class="last-name"
                    autocomplete="family-name"
                    name="contactLastName" placeholder="<?php _e( "Contact surname", 'eduadmin-booking' ); ?>"
                    value="<?php echo @esc_attr( $contact->LastName ); ?>"/>
        </div>
    </label>
    <label>
        <div class="inputLabel">
			<?php _e( "E-mail address", 'eduadmin-booking' ); ?>
        </div>
        <div class="inputHolder">
            <input type="email" id="edu-contactEmail" required
                   name="contactEmail"<?php echo( $__block ? " readonly" : "" ); ?> autocomplete="email"
                   onchange="eduBookingView.ContactAsParticipant();"
                   placeholder="<?php _e( "E-mail address", 'eduadmin-booking' ); ?>"
                   value="<?php echo @esc_attr( $contact->Email ); ?>"/>
        </div>
    </label>
    <label>
        <div class="inputLabel">
			<?php _e( "Phone number", 'eduadmin-booking' ); ?>
        </div>
        <div class="inputHolder">
            <input type="tel" id="edu-contactPhone" name="contactPhone" autocomplete="tel"
                   onchange="eduBookingView.ContactAsParticipant();"
                   placeholder="<?php _e( "Phone number", 'eduadmin-booking' ); ?>"
                   value="<?php echo @esc_attr( $contact->Phone ); ?>"/>
        </div>
    </label>
    <label>
        <div class="inputLabel">
			<?php _e( "Mobile number", 'eduadmin-booking' ); ?>
        </div>
        <div class="inputHolder">
            <input type="tel" id="edu-contactMobile" name="contactMobile" autocomplete="tel"
                   onchange="eduBookingView.ContactAsParticipant();"
                   placeholder="<?php _e( "Mobile number", 'eduadmin-booking' ); ?>"
                   value="<?php echo @esc_attr( $contact->Mobile ); ?>"/>
        </div>
    </label>
	<?php $selectedLoginField = get_option( 'eduadmin-loginField', 'Email' ); ?>
	<?php if ( $selectedCourse->RequireCivicRegistrationNumber || $selectedLoginField == 'CivicRegistrationNumber' ) { ?>
        <label>
            <div class="inputLabel">
				<?php _e( "Civic Registration Number", 'eduadmin-booking' ); ?>
            </div>
            <div class="inputHolder">
                <input type="text" id="edu-contactCivReg" class="eduadmin-civicRegNo"
                       pattern="(\d{2,4})-?(\d{2,2})-?(\d{2,2})-?(\d{4,4})" required name="contactCivReg"
                       onchange="eduBookingView.ContactAsParticipant();"
                       placeholder="<?php _e( "Civic Registration Number", 'eduadmin-booking' ); ?>"
                       value="<?php echo @esc_attr( $contact->CivicRegistrationNumber ); ?>"/>
            </div>
        </label>
	<?php } ?>
	<?php if ( get_option( 'eduadmin-useLogin', false ) && ! $contact->CanLogin ) { ?>
        <label>
            <div class="inputLabel">
				<?php _e( "Please enter a password", 'eduadmin-booking' ); ?>
            </div>
            <div class="inputHolder">
                <input type="password" required name="contactPass" autocomplete="new-password"
                       placeholder="<?php _e( "Please enter a password", 'eduadmin-booking' ); ?>"/>
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
		$f = new XFilter( 'AttributeOwnerTypeID', '=', 4 );
		$fo->AddItem( $f );
		$contactAttributes = EDU()->api->GetAttribute( EDU()->get_token(), $so->ToString(), $fo->ToString() );

		$db = array();
		if ( isset( $contact ) && isset( $contact->CustomerContactID ) ) {
			if ( $contact->CustomerContactID != 0 ) {
				$fo = new XFiltering();
				$f  = new XFilter( 'CustomerContactID', '=', $contact->CustomerContactID );
				$fo->AddItem( $f );
				$db = EDU()->api->GetCustomerContactAttribute( EDU()->get_token(), '', $fo->ToString() );
			}
		}

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
			renderAttribute( $attr, false, "", $data );
		}

	?>

    <label>
        <div class="inputHolder contactIsAlsoParticipant">
            <input type="checkbox" id="contactIsAlsoParticipant" name="contactIsAlsoParticipant" value="true"
                   onchange="if(eduBookingView.CheckParticipantCount()) { eduBookingView.UpdatePrice(); } else { this.checked = false; return false; }"/>
            <label class="inline-checkbox" for="contactIsAlsoParticipant"></label>
			<?php _e( "I am also participating", 'eduadmin-booking' ); ?>
        </div>
    </label>
    <div class="edu-modal warning" id="edu-warning-participants-contact">
		<?php _e( "You cannot add any more participants.", 'eduadmin-booking' ); ?>
    </div>
</div>