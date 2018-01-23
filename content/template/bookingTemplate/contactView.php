<?php
	$blockEditIfLoggedIn = get_option( 'eduadmin-blockEditIfLoggedIn', true );
	$__block             = ( $blockEditIfLoggedIn && $contact->CustomerContactID != 0 );
?>
<div class="contactView">
    <h2><?php edu_e( "Contact information" ); ?></h2>
    <label>
        <div class="inputLabel">
			<?php edu_e( "Contact name" ); ?>
        </div>
        <div class="inputHolder"><input type="text"
                                        style="width: 50%; display: inline;"<?php echo( $__block ? " readonly" : "" ); ?>
                                        required onchange="eduBookingView.ContactAsParticipant();"
                                        autocomplete="given-name"
                                        id="edu-contactFirstName" name="contactFirstName"
                                        placeholder="<?php edu_e( "Contact first name" ); ?>"
                                        value="<?php echo @esc_attr( explode( ' ', $contact->ContactName )[0] ); ?>"/><input
                    type="text" style="width: 50%; display: inline;"<?php echo( $__block ? " readonly" : "" ); ?>
                    required onchange="eduBookingView.ContactAsParticipant();" id="edu-contactLastName"
                    autocomplete="family-name"
                    name="contactLastName" placeholder="<?php edu_e( "Contact surname" ); ?>"
                    value="<?php echo @esc_attr( str_replace( explode( ' ', $contact->ContactName )[0], '', $contact->ContactName ) ); ?>"/>
        </div>
    </label>
    <label>
        <div class="inputLabel">
			<?php edu_e( "E-mail address" ); ?>
        </div>
        <div class="inputHolder">
            <input type="email" id="edu-contactEmail" required
                   name="contactEmail"<?php echo( $__block ? " readonly" : "" ); ?> autocomplete="email"
                   onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php edu_e( "E-mail address" ); ?>"
                   value="<?php echo @esc_attr( $contact->Email ); ?>"/>
        </div>
    </label>
    <label>
        <div class="inputLabel">
			<?php edu_e( "Phone number" ); ?>
        </div>
        <div class="inputHolder">
            <input type="tel" id="edu-contactPhone" name="contactPhone" autocomplete="tel"
                   onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php edu_e( "Phone number" ); ?>"
                   value="<?php echo @esc_attr( $contact->Phone ); ?>"/>
        </div>
    </label>
    <label>
        <div class="inputLabel">
			<?php edu_e( "Mobile number" ); ?>
        </div>
        <div class="inputHolder">
            <input type="tel" id="edu-contactMobile" name="contactMobile" autocomplete="tel"
                   onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php edu_e( "Mobile number" ); ?>"
                   value="<?php echo @esc_attr( $contact->Mobile ); ?>"/>
        </div>
    </label>
	<?php $selectedLoginField = get_option( 'eduadmin-loginField', 'Email' ); ?>
	<?php if ( $selectedCourse->RequireCivicRegistrationNumber || $selectedLoginField == 'CivicRegistrationNumber' ) { ?>
        <label>
            <div class="inputLabel">
				<?php edu_e( "Civic Registration Number" ); ?>
            </div>
            <div class="inputHolder">
                <input type="text" id="edu-contactCivReg" class="eduadmin-civicRegNo"
                       pattern="(\d{2,4})-?(\d{2,2})-?(\d{2,2})-?(\d{4,4})" required name="contactCivReg"
                       onchange="eduBookingView.ContactAsParticipant();"
                       placeholder="<?php edu_e( "Civic Registration Number" ); ?>"
                       value="<?php echo @esc_attr( $contact->CivicRegistrationNumber ); ?>"/>
            </div>
        </label>
	<?php } ?>
	<?php if ( get_option( 'eduadmin-useLogin', false ) && empty( $contact->Loginpass ) ) { ?>
        <label>
            <div class="inputLabel">
				<?php edu_e( "Please enter a password" ); ?>
            </div>
            <div class="inputHolder">
                <input type="password" required name="contactPass" autocomplete="new-password"
                       placeholder="<?php edu_e( "Please enter a password" ); ?>"/>
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
		$contactAttributes = EDU()->api->GetAttribute( $edutoken, $so->ToString(), $fo->ToString() );

		$db = array();
		if ( isset( $contact ) && isset( $contact->CustomerContactID ) ) {
			if ( $contact->CustomerContactID != 0 ) {
				$fo = new XFiltering();
				$f  = new XFilter( 'CustomerContactID', '=', $contact->CustomerContactID );
				$fo->AddItem( $f );
				$db = EDU()->api->GetCustomerContactAttribute( $edutoken, '', $fo->ToString() );
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
	        <?php edu_e( "I am also participating" ); ?>
        </div>
    </label>
    <div class="edu-modal warning" id="edu-warning-participants-contact">
		<?php edu_e( "You cannot add any more participants." ); ?>
    </div>
</div>