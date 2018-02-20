<?php
ob_start();
global $wp_query;
$api_key = get_option( 'eduadmin-api-key' );

if ( ! $api_key || empty( $api_key ) ) {
	echo 'Please complete the configuration: <a href="' . esc_url( admin_url() . 'admin.php?page=eduadmin-settings' ) . '">EduAdmin - Api Authentication</a>';
} else {
	$course_id = $wp_query->query_vars['courseId'];
	if ( ! $edo ) {
		$fetch_months = get_option( 'eduadmin-monthsToFetch', 6 );
		if ( ! is_numeric( $fetch_months ) ) {
			$fetch_months = 6;
		}

		$expands = array();

		$expands['Subjects']   = '';
		$expands['Categories'] = '';
		$expands['PriceNames'] = '$filter=PublicPriceName;';
		$expands['Events']     =
			'$filter=' .
			'HasPublicPriceName' .
			' and StatusId eq 1' .
			' and CustomerId eq null' .
			' and LastApplicationDate ge ' . date( 'c' ) .
			' and StartDate le ' . date( 'c', strtotime( 'now 23:59:59 +' . $fetch_months . ' months' ) ) .
			' and EndDate ge ' . date( 'c', strtotime( 'now' ) ) .
			';' .
			'$expand=PriceNames($filter=PublicPriceName),EventDates' .
			';' .
			'$orderby=' . ( $group_by_city ? 'City asc,' : '' ) . 'StartDate asc' .
			';';

		$expands['CustomFields'] = '$filter=ShowOnWeb';

		$expand_arr = array();
		foreach ( $expands as $key => $value ) {
			if ( empty( $value ) ) {
				$expand_arr[] = $key;
			} else {
				$expand_arr[] = $key . '(' . $value . ')';
			}
		}

		$edo = EDUAPI()->OData->CourseTemplates->GetItem(
			$course_id,
			null,
			join( ',', $expand_arr )
		);
		set_transient( 'eduadmin-object_' . $course_id, $edo, 10 );
	}

	$selected_course = false;
	$name            = '';
	if ( $edo ) {
		$name            = ( ! empty( $edo['CourseName'] ) ? $edo['CourseName'] : $edo['InternalCourseName'] );
		$selected_course = $edo;
	}

	if ( ! $selected_course ) {
		?>
		<script>history.go(-1);</script>
		<?php
		die();
	}

	EDU()->write_debug( $selected_course );

	if ( 0 === count( $selected_course['Events'] ) ) {
		?>
		<script>history.go(-1);</script>
		<?php
		die();
	}

	$events = $selected_course['Events'];
	$event  = $events[0];
	if ( isset( $_GET['eid'] ) ) {
		$eventid = intval( $_GET['eid'] );
		foreach ( $events as $ev ) {
			if ( $eventid === $ev['EventId'] ) {
				$event    = $ev;
				$events   = array();
				$events[] = $ev;
				break;
			}
		}
	}

	if ( wp_verify_nonce( $_POST['edu-valid-form'], 'edu-booking-confirm' ) && isset( $_POST['act'] ) && 'bookCourse' === sanitize_text_field( $_POST['act'] ) ) {
		include_once 'createBooking.php';
	} else {
		$contact  = new CustomerContact();
		$customer = new Customer();

		$discount_percent             = 0.0;
		$participant_discount_percent = 0.0;
		$customer_invoice_email       = '';

		$inc_vat = EDUAPI()->REST->Organisation->GetOrganisation()['PriceIncVat'];

		if ( isset( EDU()->session['eduadmin-loginUser'] ) ) {
			$user     = EDU()->session['eduadmin-loginUser'];
			$contact  = $user->Contact;
			$customer = $user->Customer;
			if ( isset( $customer->CustomerId ) ) {
				$f  = new XFiltering();
				$ft = new XFilter( 'CustomerID', '=', $customer->CustomerId );
				$f->AddItem( $ft );
				$extra_info = EDU()->api->GetCustomerExtraInfo( EDU()->get_token(), '', $f->ToString() );
				foreach ( $extra_info as $info ) {
					if ( 'DiscountPercent' === $info->Key && ! empty( $info->Value ) ) {
						$discount_percent = (double) $info->Value;
					} elseif ( 'ParticipantDiscountPercent' === $info->Key && ! empty( $info->Value ) ) {
						$participant_discount_percent = (double) $info->Value;
					} elseif ( 'CustomerInvoiceEmail' === $info->Key && ! empty( $info->Value ) ) {
						$customer_invoice_email = $info->Value;
					}
				}
			}
		}

		$occ_ids   = array();
		$occ_ids[] = -1;
		if ( empty( $_REQUEST['eid'] ) ) {
			foreach ( $events as $ev ) {
				$occ_ids[] = $ev['EventId'];
			}
		} else {
			$occ_ids[] = $event['EventId'];
		}

		$ft = new XFiltering();
		$f  = new XFilter( 'PublicPriceName', '=', 'true' );
		$ft->AddItem( $f );
		$f = new XFilter( 'OccationID', 'IN', join( ',', $occ_ids ) );
		$ft->AddItem( $f );

		$st = new XSorting();
		$s  = new XSort( 'Price', 'ASC' );
		$st->AddItem( $s );

		$prices = EDU()->api->GetPriceName( EDU()->get_token(), $st->ToString(), $ft->ToString() );

		$unique_prices = array();
		foreach ( $prices as $price ) {
			$unique_prices[ $price->Description ] = $price;
		}
		// PriceNameVat
		$first_price = current( $unique_prices );

		$st = new XSorting();
		$s  = new XSort( 'StartDate', 'ASC' );
		$st->AddItem( $s );
		$s = new XSort( 'EndDate', 'ASC' );
		$st->AddItem( $s );

		$ft = new XFiltering();
		$f  = new XFilter( 'ParentEventID', '=', $event['EventId'] );
		$ft->AddItem( $f );
		$sub_events = EDU()->api->GetSubEvent( EDU()->get_token(), $st->ToString(), $ft->ToString() );
		$occ_ids    = array();
		foreach ( $sub_events as $se ) {
			$occ_ids[] = $se->OccasionID;
		}

		$ft = new XFiltering();
		$f  = new XFilter( 'PublicPriceName', '=', 'true' );
		$ft->AddItem( $f );
		$f = new XFilter( 'OccationID', 'IN', join( ',', $occ_ids ) );
		$ft->AddItem( $f );

		$st = new XSorting();
		$s  = new XSort( 'Price', 'ASC' );
		$st->AddItem( $s );

		$sub_prices = EDU()->api->GetPriceName( EDU()->get_token(), $st->ToString(), $ft->ToString() );
		$se_price   = array();
		foreach ( $sub_prices as $sp ) {
			$se_price[ $sp->OccationID ][] = $sp;
		}

		$hide_sub_event_date_info = get_option( 'eduadmin-hideSubEventDateTime', false );
		?>

		<div class="eduadmin booking-page">
			<form action="" method="post">
				<input type="hidden" name="act" value="bookCourse"/>
				<input type="hidden" name="edu-valid-form" value="<?php esc_attr( wp_create_nonce( 'edu-booking-confirm' ) ); ?>"/>
				<a href="../" class="backLink"><?php esc_html_e( '« Go back', 'eduadmin-booking' ); ?></a>

				<div class="title">
					<?php if ( ! empty( $selected_course['ImageUrl'] ) ) : ?>
						<img class="courseImage" src="<?php echo esc_url( $selected_course['ImageUrl'] ); ?>"/>
					<?php endif; ?>
					<h1 class="courseTitle">
						<?php echo esc_html( $name ); ?>
					</h1>

					<?php if ( count( $events ) > 1 ) : ?>
						<div class="dateSelectLabel">
							<?php esc_html_e( 'Select the event you want to book', 'eduadmin-booking' ); ?>
						</div>

						<select name="eid" required class="dateInfo" onchange="eduBookingView.SelectEvent(this);">
							<option value=""><?php esc_html_e( 'Select event', 'eduadmin-booking' ); ?></option>
							<?php foreach ( $events as $ev ) : ?>
								<option value="<?php echo esc_attr( $ev['EventId'] ); ?>">
									<?php
									echo esc_html( wp_strip_all_tags( get_old_start_end_display_date( $ev['StartDate'], $ev['EndDate'] ) ) ) . ', ';
									echo esc_html( date( 'H:i', strtotime( $ev['StartDate'] ) ) );
									?>
									-
									<?php
									echo esc_html( date( 'H:i', strtotime( $ev['EndDate'] ) ) );
									echo esc_html( edu_output_event_venue( $ev['AddressName'], $ev['City'], ', ' ) );
									?>
								</option>
							<?php endforeach; ?>
						</select>
					<?php
					else :
						echo '<div class="dateInfo">';
						echo wp_kses( get_old_start_end_display_date( $event['StartDate'], $event['EndDate'] ) . ', ', wp_kses_allowed_html( 'post' ) );

						echo '<span class="eventTime">';
						echo esc_html( date( 'H:i', strtotime( $event['StartDate'] ) ) );
						?>
						-
						<?php
						echo esc_html( date( 'H:i', strtotime( $event['EndDate'] ) ) ) . '</span>';
						echo esc_html( edu_output_event_venue( $event['AddressName'], $event['City'], ', ' ) );
						echo '</div>';
					endif;
					?>
				</div>
				<?php
				if ( isset( EDU()->session['eduadmin-loginUser'] ) ) {
					$user_val = '';
					if ( isset( $contact->PersonId ) && $contact->PersonId > 0 ) {
						$user_val = trim( $contact->FirstName . ' ' . $contact->LastName );
					} else {
						$selected_login_field = get_option( 'eduadmin-loginField', 'Email' );
						switch ( $selected_login_field ) {
							case 'Email':
								$user_val = $contact->Email;
								break;
							case 'CivicRegistrationNumber':
								$user_val = $contact->CivicRegistrationNumber;
								break;
							default:
								$user_val = $contact->Email;
								break;
						}
					}
					$surl     = get_home_url();
					$cat      = get_option( 'eduadmin-rewriteBaseUrl' );
					$base_url = $surl . '/' . $cat;
					?>
					<div class="notUserCheck">
						<i>
							<?php
							/* translators: 1: User display name 2: Beginning of link 3: End of link */
							echo sprintf( __( 'Not <b>%1$s</b>? %2$sLog out%3$s', 'eduadmin-booking' ), esc_html( $user_val ), '<a href="' . esc_url( $base_url . '/profile/logout' ) . '">', '</a>' );
							?>
						</i>
					</div>
					<?php
				}
				?>
				<?php
				$no_invoice_free_events         = get_option( 'eduadmin-noInvoiceFreeEvents', false );
				$single_person_booking          = get_option( 'eduadmin-singlePersonBooking', false );
				$show_invoice_email             = isset( $attributes['hideinvoiceemailfield'] ) ? false === $attributes['hideinvoiceemailfield'] : false === get_option( 'eduadmin-hideInvoiceEmailField', false );
				$force_show_invoice_information = isset( $attributes['showinvoiceinformation'] ) ? false === $attributes['showinvoiceinformation'] : true === get_option( 'eduadmin-showInvoiceInformation', false );
				if ( $single_person_booking ) {
					include_once 'singlePersonBooking.php';
				} else {
					$field_order = get_option( 'eduadmin-fieldOrder', 'contact_customer' );
					if ( 'contact_customer' === $field_order ) {
						include_once 'contactView.php';
						include_once 'customerView.php';
					} elseif ( 'customer_contact' === $field_order ) {
						include_once 'customerView.php';
						include_once 'contactView.php';
					}
					include_once 'participantView.php';
				}
				?>
				<?php if ( 'selectWholeEvent' === get_option( 'eduadmin-selectPricename', 'firstPublic' ) ) : ?>
					<div class="priceView">
						<?php esc_html_e( 'Price name', 'eduadmin-booking' ); ?>
						<select id="edu-pricename" name="edu-pricename" required class="edudropdown edu-pricename" onchange="eduBookingView.UpdatePrice();">
							<option data-price="0" value=""><?php esc_html_e( 'Choose price', 'eduadmin-booking' ); ?></option>
							<?php foreach ( $prices as $price ) : ?>
								<option data-price="<?php echo esc_attr( $price->Price ); ?>" date-discountpercent="<?php echo esc_attr( $price->DiscountPercent ); ?>" data-pricelnkid="<?php echo esc_attr( $price->OccationPriceNameLnkID ); ?>" data-maxparticipants="<?php echo esc_attr( $price->MaxPriceNameParticipantNr ); ?>" data-currentparticipants="<?php echo esc_attr( $price->ParticipantNr ); ?>"
									<?php if ( $price->MaxPriceNameParticipantNr > 0 && $price->ParticipantNr >= $price->MaxPriceNameParticipantNr ) { ?>
										disabled
									<?php } ?>
										value="<?php echo esc_attr( $price->OccationPriceNameLnkID ); ?>">
									<?php echo esc_html( $price->Description ); ?>
									(<?php echo esc_html( convert_to_money( $price->Price, get_option( 'eduadmin-currency', 'SEK' ) ) . ' ' . ( $inc_vat ? __( 'inc vat', 'eduadmin-booking' ) : __( 'ex vat', 'eduadmin-booking' ) ) ); ?>
									)
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>

				<?php include_once 'questionView.php'; ?>

				<?php if ( get_option( 'eduadmin-allowDiscountCode', false ) ) : ?>
					<div class="discountView">
						<label>
							<div class="inputLabel">
								<?php esc_html_e( 'Discount code', 'eduadmin-booking' ); ?>
							</div>
							<div class="inputHolder">
								<input type="text" name="edu-discountCode" id="edu-discountCode" class="discount-box" placeholder="<?php esc_attr__( 'Discount code', 'eduadmin-booking' ); ?>"/>
								<button class="validateDiscount neutral-btn" data-categoryid="<?php echo esc_attr( $selected_course['CategoryId'] ); ?>" data-objectid="<?php echo esc_attr( $selected_course['CourseTemplateId'] ); ?>" onclick="eduBookingView.ValidateDiscountCode(); return false;">
									<?php esc_html_e( 'Validate', 'eduadmin-booking' ); ?>
								</button>
								<input type="hidden" name="edu-discountCodeID" id="edu-discountCodeID"/>
							</div>
						</label>
						<div class="edu-modal warning" id="edu-warning-discount">
							<?php esc_html_e( 'Invalid discount code, please check your code and try again.', 'eduadmin-booking' ); ?>
						</div>
					</div>
				<?php endif; ?>
				<?php
				$use_limited_discount = get_option( 'eduadmin-useLimitedDiscount', false );
				if ( $use_limited_discount ) {
					include_once 'limitedDiscountView.php';
				}
				?>
				<div class="submitView">
					<div class="sumTotal">
						<?php esc_html_e( 'Total sum:', 'eduadmin-booking' ); ?>
						<span id="sumValue" class="sumValue"></span>
					</div>

					<?php if ( get_option( 'eduadmin-useBookingTermsCheckbox', false ) && $link = get_option( 'eduadmin-bookingTermsLink', '' ) ): ?>
						<div class="confirmTermsHolder">
							<label>
								<input type="checkbox" id="confirmTerms" name="confirmTerms" value="agree"/>
								<?php echo sprintf( __( 'I agree to the %sTerms and Conditions%s', 'eduadmin-booking' ), '<a href="' . $link . '" target="_blank">', '</a>' ); ?>
							</label>
						</div>
					<?php endif; ?>
					<?php if ( 0 !== $event['ParticipantNumberLeft'] ) : ?>
						<input type="submit" class="bookButton cta-btn" id="edu-book-btn" onclick="var validated = eduBookingView.CheckValidation(); return validated;" value="<?php esc_attr_e( 'Book now', 'eduadmin-booking' ); ?>"/>
					<?php else : ?>
						<div class="bookButton cta-btn" disabled>
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
			</form>
		</div>

		<?php
		$original_title = get_the_title();
		$new_title      = $name . ' | ' . $original_title;

		$discount_value = 0.0;
		if ( 0 !== $participantDiscountPercent ) {
			$discount_value = ( $participantDiscountPercent / 100 ) * $first_price->Price;
		}
		?>
		<script type="text/javascript">
			var pricePerParticipant = <?php echo esc_js( round( $first_price->Price - $discount_value, 2 ) ); ?>;
			var discountPerParticipant = <?php echo esc_js( round( $participantDiscountPercent / 100, 2 ) ); ?>;
			var totalPriceDiscountPercent = <?php echo esc_js( $discountPercent ); ?>;
			var currency = '<?php echo esc_js( get_option( 'eduadmin-currency', 'SEK' ) ); ?>';
			var vatText = '<?php echo esc_js( $inc_vat ? __( 'inc vat', 'eduadmin-booking' ) : __( 'ex vat', 'eduadmin-booking' ) ); ?>';
			var ShouldValidateCivRegNo = <?php echo esc_js( get_option( 'eduadmin-validateCivicRegNo', false ) ? 'true' : 'false' ); ?>;
			(function () {
				var title = document.title;
				title = title.replace('<?php echo esc_js( $original_title ); ?>', '<?php echo esc_js( $new_title ); ?>');
				document.title = title;
				eduBookingView.MaxParticipants = <?php echo esc_js( $event['ParticipantNumberLeft'] ); ?>;
				<?php echo get_option( 'eduadmin-singlePersonBooking', false ) ? 'eduBookingView.SingleParticipant = true;' : ''; ?>
				eduBookingView.AddParticipant();
				eduBookingView.UpdatePrice();
			})();
		</script>
		<?php
	}
}
do_action( 'eduadmin-bookingform-loaded', EDU()->session['eduadmin-loginUser'] );
$out = ob_get_clean();

return $out;
