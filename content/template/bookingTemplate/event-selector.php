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
				echo esc_html( edu_output_event_venue( array( $ev['AddressName'], $ev['City'] ), ', ' ) );
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
	echo esc_html( edu_output_event_venue( array( $event['AddressName'], $event['City'] ), ', ' ) );
	echo '</div>';
endif;
