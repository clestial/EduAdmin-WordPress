<?php
// phpcs:disable WordPress.NamingConventions,Squiz

if ( ! empty( $_POST['edu-valid-form'] ) && wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) && isset( $_POST['act'] ) && 'bookProgramme' === sanitize_text_field( $_POST['act'] ) ) {
	$error_list = apply_filters( 'edu-booking-error', array() );
	if ( ! empty( $error_list ) ) {
		echo '<div class="eduadmin">';
		foreach ( $error_list as $error ) {
			?>
			<div class="edu-modal warning">
				<?php echo esc_html( $error ); ?>
			</div>
			<?php
		}
		do_action( 'eduadmin-bookingerror', $error_list );
		echo '</div>';
	} else {
		$ebi = $GLOBALS['edubookinginfo'];
		do_action( 'eduadmin-processbooking', $ebi );
		do_action( 'eduadmin-bookingcompleted' );
	}
} else {
	$contact  = new EduAdmin_Data_ContactPerson();
	$customer = new EduAdmin_Data_Customer();

	$discount_percent             = 0.0;
	$participant_discount_percent = 0.0;
	$customer_invoice_email       = '';

	$inc_vat = EDUAPI()->REST->Organisation->GetOrganisation()['PriceIncVat'];

	if ( isset( EDU()->session['eduadmin-loginUser'] ) ) {
		$user     = EDU()->session['eduadmin-loginUser'];
		$contact  = $user->Contact;
		$customer = $user->Customer;
	}

	$no_invoice_free_events = get_option( 'eduadmin-noInvoiceFreeEvents', false );

	$first_price = current( $programme['PriceNames'] );

	$show_invoice_email             = isset( $attributes['hideinvoiceemailfield'] ) ? false === $attributes['hideinvoiceemailfield'] : false === get_option( 'eduadmin-hideInvoiceEmailField', false );
	$force_show_invoice_information = isset( $attributes['showinvoiceinformation'] ) ? false === $attributes['showinvoiceinformation'] : true === get_option( 'eduadmin-showInvoiceInformation', false );

	$block_edit_if_logged_in = get_option( 'eduadmin-blockEditIfLoggedIn', true );
	$__block                 = ( $block_edit_if_logged_in && isset( $contact->PersonId ) && 0 !== $contact->PersonId );

	$questions = EDUAPI()->REST->ProgrammeStart->BookingQuestions( $programme['ProgrammeStartId'], true );

	$booking_questions     = $questions['BookingQuestions'];
	$participant_questions = $questions['ParticipantQuestions'];

	?>
	<div class="eduadmin booking-page">
		<form action="" method="post" id="edu-booking-form">
			<input type="hidden" name="act" value="bookProgramme" />
			<input type="hidden" name="edu-programme-start" value="<?php echo intval( $_REQUEST['id'] ); ?>" />
			<input type="hidden" name="edu-valid-form" value="<?php echo esc_attr( wp_create_nonce( 'edu-booking-confirm' ) ); ?>" />
			<a href="../" class="backLink"><?php esc_html_e( '« Go back', 'eduadmin-booking' ); ?></a>

			<div class="title">
				<h1 class="courseTitle">
					<?php echo esc_html( $programme['ProgrammeStartName'] ) . ' (' . wp_kses_post( get_display_date( $programme['StartDate'] ) ) . ' - ' . wp_kses_post( get_display_date( $programme['EndDate'] ) ) . ')'; ?>
				</h1>
			</div>
			<?php

			if ( ! empty( $customer->BillingInfo ) ) {
				$billing_customer = $customer->BillingInfo[0];
			} else {
				$billing_customer = new EduAdmin_Data_BillingInfo();
			}
			if ( isset( $contact->PersonId ) && 0 !== $contact->PersonId ) {
				echo '<input type="hidden" name="edu-contactId" value="' . esc_attr( $contact->PersonId ) . '" />';
			}
			if ( isset( $customer->CustomerId ) && 0 !== $customer->CustomerId ) {
				echo '<input type="hidden" name="edu-customerId" value="' . esc_attr( $customer->CustomerId ) . '" />';
			}
			?>
			<br />
			<div class="contactView">
				<h2><?php esc_html_e( 'Contact information', 'eduadmin-booking' ); ?></h2>
				<label>
					<div class="inputLabel">
						<?php esc_html_e( 'Contact name', 'eduadmin-booking' ); ?>
					</div>
					<div class="inputHolder"><input type="text"
							<?php echo( $__block ? ' readonly' : '' ); ?>
							required onchange="eduBookingView.ContactAsParticipant();" id="edu-contactFirstName" name="contactFirstName" class="first-name" placeholder="<?php esc_attr_e( 'Contact first name', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $contact->FirstName ); ?>" /><input type="text" <?php echo( $__block ? ' readonly' : '' ); ?>
							required onchange="eduBookingView.ContactAsParticipant();" id="edu-contactLastName" class="last-name" name="contactLastName" placeholder="<?php esc_attr_e( 'Contact surname', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $contact->LastName ); ?>" />
					</div>
				</label>
				<label>
					<div class="inputLabel">
						<?php esc_html_e( 'E-mail address', 'eduadmin-booking' ); ?>
					</div>
					<div class="inputHolder">
						<input type="email" id="edu-contactEmail" required name="contactEmail"<?php echo( $__block ? ' readonly' : '' ); ?>
							onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php esc_attr_e( 'E-mail address', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $contact->Email ); ?>" />
					</div>
				</label>
				<label>
					<div class="inputLabel">
						<?php esc_html_e( 'Phone number', 'eduadmin-booking' ); ?>
					</div>
					<div class="inputHolder">
						<input type="tel" id="edu-contactPhone" name="contactPhone" onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php esc_attr_e( 'Phone number', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $contact->Phone ); ?>" />
					</div>
				</label>
				<label>
					<div class="inputLabel">
						<?php esc_html_e( 'Mobile number', 'eduadmin-booking' ); ?>
					</div>
					<div class="inputHolder">
						<input type="tel" id="edu-contactMobile" name="contactMobile" onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php esc_attr_e( 'Mobile number', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $contact->Mobile ); ?>" />
					</div>
				</label>
				<?php $selected_login_field = get_option( 'eduadmin-loginField', 'Email' ); ?>
				<?php if ( 'CivicRegistrationNumber' === $selected_login_field ) { ?>
					<label>
						<div class="inputLabel">
							<?php esc_html_e( 'Civic Registration Number', 'eduadmin-booking' ); ?>
						</div>
						<div class="inputHolder">
							<input type="text" id="edu-contactCivReg" required name="contactCivReg" pattern="(\d{2,4})-?(\d{2,2})-?(\d{2,2})-?(\d{4,4})" class="eduadmin-civicRegNo" onchange="eduBookingView.ContactAsParticipant();" placeholder="<?php esc_attr_e( 'Civic Registration Number', 'eduadmin-booking' ); ?>" value="<?php echo esc_attr( $contact->CivicRegistrationNumber ); ?>" />
						</div>
					</label>
				<?php } ?>
				<?php if ( get_option( 'eduadmin-useLogin', false ) && ! $contact->CanLogin ) { ?>
					<label>
						<div class="inputLabel">
							<?php esc_html_e( 'Please enter a password', 'eduadmin-booking' ); ?>
						</div>
						<div class="inputHolder">
							<input type="password" required name="contactPass" placeholder="<?php esc_attr_e( 'Please enter a password', 'eduadmin-booking' ); ?>" />
						</div>
					</label>
				<?php } ?>
				<div class="edu-modal warning" id="edu-warning-participants-contact">
					<?php esc_html_e( 'You cannot add any more participants.', 'eduadmin-booking' ); ?>
				</div>
			</div>
			<div class="attributeView">
				<?php
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
			</div>
			<div class="customerView">
				<h2><?php esc_html_e( 'Customer information', 'eduadmin-booking' ); ?></h2>
				<label>
					<div class="inputLabel">
						<?php esc_html_e( 'Customer name', 'eduadmin-booking' ); ?>
					</div>
					<div class="inputHolder">
						<input type="text" required name="customerName" autocomplete="organization" placeholder="<?php esc_attr_e( 'Customer name', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $customer->CustomerName ); ?>" />
					</div>
				</label>
				<?php
				if ( empty( $no_invoice_free_events ) || ( $no_invoice_free_events && $first_price['Price'] > 0 ) ) {
					?>
					<label>
						<div class="inputLabel">
							<?php esc_html_e( 'Org.No.', 'eduadmin-booking' ); ?>
						</div>
						<div class="inputHolder">
							<input type="text" name="customerVatNo" placeholder="<?php esc_attr_e( 'Org.No.', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $customer->OrganisationNumber ); ?>" />
						</div>
					</label>
					<label>
						<div class="inputLabel">
							<?php esc_html_e( 'Address 1', 'eduadmin-booking' ); ?>
						</div>
						<div class="inputHolder">
							<input type="text" name="customerAddress1" placeholder="<?php esc_attr_e( 'Address 1', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $customer->Address ); ?>" />
						</div>
					</label>
					<label>
						<div class="inputLabel">
							<?php esc_html_e( 'Address 2', 'eduadmin-booking' ); ?>
						</div>
						<div class="inputHolder">
							<input type="text" name="customerAddress2" placeholder="<?php esc_attr_e( 'Address 2', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $customer->Address2 ); ?>" />
						</div>
					</label>
					<label>
						<div class="inputLabel">
							<?php esc_html_e( 'Postal code', 'eduadmin-booking' ); ?>
						</div>
						<div class="inputHolder">
							<input type="text" name="customerPostalCode" placeholder="<?php esc_attr_e( 'Postal code', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $customer->Zip ); ?>" />
						</div>
					</label>
					<label>
						<div class="inputLabel">
							<?php esc_html_e( 'Postal city', 'eduadmin-booking' ); ?>
						</div>
						<div class="inputHolder">
							<input type="text" name="customerPostalCity" placeholder="<?php esc_attr_e( 'Postal city', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $customer->City ); ?>" />
						</div>
					</label>
					<label>
						<div class="inputLabel">
							<?php esc_html_e( 'E-mail address', 'eduadmin-booking' ); ?>
						</div>
						<div class="inputHolder">
							<input type="text" name="customerEmail" placeholder="<?php esc_attr_e( 'E-mail address', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $customer->Email ); ?>" />
						</div>
					</label>
					<div id="invoiceView" class="invoiceView" style="<?php echo( $force_show_invoice_information ? 'display: block;' : 'display: none;' ); ?>">
						<h2><?php esc_html_e( 'Invoice information', 'eduadmin-booking' ); ?></h2>
						<label>
							<div class="inputLabel">
								<?php esc_html_e( 'Customer name', 'eduadmin-booking' ); ?>
							</div>
							<div class="inputHolder">
								<input type="text" name="invoiceName" placeholder="<?php esc_attr_e( 'Customer name', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $billing_customer->CustomerName ); ?>" />
							</div>
						</label>
						<label>
							<div class="inputLabel">
								<?php esc_html_e( 'Address 1', 'eduadmin-booking' ); ?>
							</div>
							<div class="inputHolder">
								<input type="text" name="invoiceAddress1" placeholder="<?php esc_attr_e( 'Address 1', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $billing_customer->Address ); ?>" />
							</div>
						</label>
						<label>
							<div class="inputLabel">
								<?php esc_html_e( 'Address 2', 'eduadmin-booking' ); ?>
							</div>
							<div class="inputHolder">
								<input type="text" name="invoiceAddress2" placeholder="<?php esc_attr_e( 'Address 2', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $billing_customer->Address2 ); ?>" />
							</div>
						</label>
						<label>
							<div class="inputLabel">
								<?php esc_html_e( 'Postal code', 'eduadmin-booking' ); ?>
							</div>
							<div class="inputHolder">
								<input type="text" name="invoicePostalCode" placeholder="<?php esc_attr_e( 'Postal code', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $billing_customer->Zip ); ?>" />
							</div>
						</label>
						<label>
							<div class="inputLabel">
								<?php esc_html_e( 'Postal city', 'eduadmin-booking' ); ?>
							</div>
							<div class="inputHolder">
								<input type="text" name="invoicePostalCity" placeholder="<?php esc_attr_e( 'Postal city', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $billing_customer->City ); ?>" />
							</div>
						</label>
					</div>
					<?php if ( $show_invoice_email ) { ?>
						<label>
							<div class="inputLabel">
								<?php esc_html_e( 'Invoice e-mail address', 'eduadmin-booking' ); ?>
							</div>
							<div class="inputHolder">
								<input type="text" name="invoiceEmail" placeholder="<?php esc_attr_e( 'Invoice e-mail address', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( $billing_customer->Email ); ?>" />
							</div>
						</label>
					<?php } ?>
					<label>
						<div class="inputLabel">
							<?php esc_html_e( 'Invoice reference', 'eduadmin-booking' ); ?>
						</div>
						<div class="inputHolder">
							<input type="text" name="invoiceReference" placeholder="<?php esc_attr_e( 'Invoice reference', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( ! empty( $billing_customer->SellerReference ) ? $billing_customer->SellerReference : '' ); ?>" />
						</div>
					</label>
					<label>
						<div class="inputLabel">
							<?php esc_html_e( 'Purchase order number', 'eduadmin-booking' ); ?>
						</div>
						<div class="inputHolder">
							<input type="text" name="purchaseOrderNumber" placeholder="<?php esc_attr_e( 'Purchase order number', 'eduadmin-booking' ); ?>" value="<?php echo @esc_attr( ! empty( $_POST['purchaseOrderNumber'] ) ? $_POST['purchaseOrderNumber'] : '' ); ?>" />
						</div>
					</label>
					<?php
				}

				$customer_custom_fields = EDUAPI()->OData->CustomFields->Search(
					null,
					'ShowOnWeb and CustomFieldOwner eq \'Customer\'',
					'CustomFieldAlternatives'
				)['value'];

				foreach ( $customer_custom_fields as $custom_field ) {
					$data = null;
					foreach ( $customer->CustomFields as $cf ) {
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
					render_attribute( $custom_field, false, 'customer', $data );
				}
				if ( ! $no_invoice_free_events || $first_price['Price'] > 0 ) {
					?>
					<label<?php echo $force_show_invoice_information ? ' style="display: none;"' : ''; ?>>
						<div class="inputHolder alsoInvoiceCustomer">
							<label class="inline-checkbox" for="alsoInvoiceCustomer">
								<input type="checkbox" id="alsoInvoiceCustomer" name="alsoInvoiceCustomer" value="true" onchange="eduBookingView.UpdateInvoiceCustomer(this);"
									<?php echo $force_show_invoice_information ? 'checked' : ''; ?>/>
								<?php esc_html_e( 'Use other information for invoicing', 'eduadmin-booking' ); ?>
							</label>
						</div>
					</label>
				<?php } ?>
			</div>
			<div class="questionPanel">
				<?php
				if ( ! empty( $_REQUEST['eid'] ) ) {
					foreach ( $booking_questions as $question ) {
						render_question( $question, false, 'booking' );
					}
				}
				?>
			</div>
			<div class="participantView">
				<h2><?php esc_html_e( 'Participant information', 'eduadmin-booking' ); ?></h2>
				<div class="participantHolder" id="edu-participantHolder">
					<div class="participantItem template" style="display: none;">
						<h3>
							<?php esc_html_e( 'Participant', 'eduadmin-booking' ); ?>
							<?php if ( ! get_option( 'eduadmin-singlePersonBooking', false ) ) { ?>
								<div class="removeParticipant" onclick="eduBookingView.RemoveParticipant(this);"><?php esc_html_e( 'Remove', 'eduadmin-booking' ); ?></div>
							<?php } ?>
						</h3>
						<label>
							<div class="inputLabel">
								<?php esc_html_e( 'Participant name', 'eduadmin-booking' ); ?>
							</div>
							<div class="inputHolder">
								<input type="text" class="participantFirstName first-name" onchange="eduBookingView.CheckPrice(false);" name="participantFirstName[]" placeholder="<?php esc_attr_e( 'Participant first name', 'eduadmin-booking' ); ?>" /><input type="text" class="participantLastName last-name" onchange="eduBookingView.CheckPrice(false);" name="participantLastName[]" placeholder="<?php esc_attr_e( 'Participant surname', 'eduadmin-booking' ); ?>" />
							</div>
						</label>
						<label>
							<div class="inputLabel">
								<?php esc_html_e( 'E-mail address', 'eduadmin-booking' ); ?>
							</div>
							<div class="inputHolder">
								<input type="email" name="participantEmail[]" onchange="eduBookingView.CheckPrice(false);" placeholder="<?php esc_attr_e( 'E-mail address', 'eduadmin-booking' ); ?>" />
							</div>
						</label>
						<label>
							<div class="inputLabel">
								<?php esc_html_e( 'Phone number', 'eduadmin-booking' ); ?>
							</div>
							<div class="inputHolder">
								<input type="tel" name="participantPhone[]" placeholder="<?php esc_attr_e( 'Phone number', 'eduadmin-booking' ); ?>" />
							</div>
						</label>
						<label>
							<div class="inputLabel">
								<?php esc_html_e( 'Mobile number', 'eduadmin-booking' ); ?>
							</div>
							<div class="inputHolder">
								<input type="tel" name="participantMobile[]" placeholder="<?php esc_attr_e( 'Mobile number', 'eduadmin-booking' ); ?>" />
							</div>
						</label>
						<?php
						if ( ! empty( $contact_custom_fields ) ) {
							foreach ( $contact_custom_fields as $attr ) {
								render_attribute( $attr, true, 'participant' );
							}
						}

						if ( ! empty( $participant_questions ) ) {
							foreach ( $participant_questions as $question ) {
								render_question( $question, true, 'participant' );
							}
						}
						?>
					</div>
				</div>
				<?php if ( ! get_option( 'eduadmin-singlePersonBooking', false ) ) { ?>
					<div>
						<a href="javascript://" class="addParticipantLink neutral-btn" onclick="eduBookingView.AddParticipant(); return false;"><?php esc_html_e( '+ Add participant', 'eduadmin-booking' ); ?></a>
					</div>
				<?php } ?>
				<div class="edu-modal warning" id="edu-warning-participants">
					<?php esc_html_e( 'You cannot add any more participants.', 'eduadmin-booking' ); ?>
				</div>
			</div>

			<div class="submitView">
				<?php if ( get_option( 'eduadmin-useBookingTermsCheckbox', false ) && $link = get_option( 'eduadmin-bookingTermsLink', '' ) ): ?>
					<div class="confirmTermsHolder">
						<label>
							<input type="checkbox" id="confirmTerms" name="confirmTerms" value="agree" />
							<?php
							/* translators: 1: Start of link 2: End of link */
							echo wp_kses( sprintf( __( 'I agree to the %1$sTerms and Conditions%2$s', 'eduadmin-booking' ), '<a href="' . $link . '" target="_blank">', '</a>' ), wp_kses_allowed_html( 'post' ) );
							?>
						</label>
					</div>
				<?php endif; ?>
				<div class="sumTotal">
					<?php esc_html_e( 'Total sum:', 'eduadmin-booking' ); ?>
					<span id="sumValue" class="sumValue"></span>
				</div>
				<?php if ( 0 !== $programme['ParticipantNumberLeft'] ) : ?>
					<input type="submit" class="bookButton cta-btn" id="edu-book-btn" onclick="eduBookingView.UpdatePrice(); var validated = eduBookingView.CheckValidation(); return validated;" value="<?php esc_attr_e( 'Book now', 'eduadmin-booking' ); ?>" />
				<?php else : ?>
					<div class="bookButton neutral-btn cta-disabled">
						<?php esc_html_e( 'No free spots left on this event', 'eduadmin-booking' ); ?>
					</div>
				<?php endif; ?>
				<div class="edu-modal warning" id="edu-warning-terms">
					<?php esc_html_e( 'You must accept Terms and Conditions to continue.', 'eduadmin-booking' ); ?>
				</div>
				<div class="edu-modal warning" id="edu-warning-no-participants">
					<?php esc_html_e( 'You must add some participants.', 'eduadmin-booking' ); ?>
				</div>
				<div class="edu-modal warning" id="edu-warning-missing-participants">
					<?php esc_html_e( 'One or more participants is missing a name.', 'eduadmin-booking' ); ?>
				</div>
				<div class="edu-modal warning" id="edu-warning-missing-civicregno">
					<?php esc_html_e( 'One or more participants is missing their civic registration number.', 'eduadmin-booking' ); ?>
				</div>
				<?php
				$error_list = apply_filters( 'edu-booking-error', array() );
				foreach ( $error_list as $error ) {
					?>
					<div class="edu-modal warning">
						<?php esc_html( $error ); ?>
					</div>
					<?php
				}
				?>
			</div>
			<?php
			$original_title = get_the_title();
			$new_title      = $programme['ProgrammeStartName'] . ' | ' . $original_title;

			$discount_value = 0.0;
			if ( 0 !== $participant_discount_percent ) {
				$discount_value = ( $participant_discount_percent / 100 ) * $first_price['Price'];
			}
			?>
			<script type="text/javascript">
				var pricePerParticipant = <?php echo esc_js( round( $first_price['Price'] - $discount_value, 2 ) ); ?>;
				var discountPerParticipant = <?php echo esc_js( round( $participant_discount_percent / 100, 2 ) ); ?>;
				var totalPriceDiscountPercent = <?php echo esc_js( $discount_percent ); ?>;
				var currency = '<?php echo esc_js( get_option( 'eduadmin-currency', 'SEK' ) ); ?>';
				var vatText = '<?php echo esc_js( $inc_vat ? __( 'inc vat', 'eduadmin-booking' ) : __( 'ex vat', 'eduadmin-booking' ) ); ?>';
				var ShouldValidateCivRegNo = <?php echo esc_js( get_option( 'eduadmin-validateCivicRegNo', false ) ? 'true' : 'false' ); ?>;

				var edu_vat = {
					inc: '<?php echo esc_js( __( 'inc vat', 'eduadmin-booking' ) ); ?>',
					ex: '<?php echo esc_js( __( 'ex vat', 'eduadmin-booking' ) ); ?>'
				};
				(function () {
					var title = document.title;
					title = title.replace('<?php echo esc_js( $original_title ); ?>', '<?php echo esc_js( $new_title ); ?>');
					document.title = title;
					eduBookingView.ProgrammeBooking = true;
					eduBookingView.MaxParticipants = <?php echo esc_js( intval( $programme['ParticipantNumberLeft'] ) ); ?>;
					eduBookingView.AddParticipant();
					eduBookingView.CheckPrice(false);
				})();
			</script>
		</form>
	</div>
	<?php
}