<?php
if ( get_option( 'eduadmin-allowDiscountCode', false ) ) :
	?>
	<div class="discountView">
		<label>
			<div class="inputLabel">
				<?php esc_html_e( 'Discount code', 'eduadmin-booking' ); ?>
			</div>
			<div class="inputHolder">
				<input type="text" name="edu-discountCode" id="edu-discountCode" class="discount-box" placeholder="<?php esc_attr__( 'Discount code', 'eduadmin-booking' ); ?>" />
				<button class="validateDiscount neutral-btn" data-eventid="<?php echo esc_attr( $event['EventId'] ); ?>" data-objectid="<?php echo esc_attr( $selected_course['CourseTemplateId'] ); ?>" onclick="eduBookingView.ValidateDiscountCode(); return false;">
					<?php esc_html_e( 'Validate', 'eduadmin-booking' ); ?>
				</button>
			</div>
		</label>
		<div class="edu-modal warning" id="edu-warning-discount">
			<?php esc_html_e( 'Invalid discount code, please check your code and try again.', 'eduadmin-booking' ); ?>
		</div>
	</div>
<?php
endif;
