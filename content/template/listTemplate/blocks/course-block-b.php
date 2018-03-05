<div class="objectBlock brick">
	<?php if ( $show_images && ! empty( $object['ImageUrl'] ) ) { ?>
		<div class="objectImage" onclick="location.href = '<?php echo esc_url( $base_url . '/' . make_slugs( $name ) . '__' . $object['CourseTemplateId'] . '/' . edu_get_query_string() ); ?>';" style="background-image: url('<?php echo esc_url( $object['ImageUrl'] ); ?>');"></div>
	<?php } ?>
	<div class="objectName">
		<a href="<?php echo esc_url( $base_url . '/' . make_slugs( $name ) . '__' . $object['CourseTemplateId'] . '/' . edu_get_query_string() ); ?>"><?php echo esc_html( get_utf8( $name ) ); ?></a>
	</div>
	<div class="objectDescription">
		<?php
		if ( stripos( $descr_field, 'attr_' ) !== false ) {
			$attr_id = intval( substr( $descr_field, 5 ) );
			foreach ( $object['CustomFields'] as $custom_field ) {
				if ( $attr_id === $custom_field['CustomFieldId'] ) {
					$descr = strip_tags( $custom_field['CustomFieldValue'] );
					break;
				}
			}
		} else {
			$descr = strip_tags( $object[ $descr_field ] );
		}

		if ( $show_descr ) {
			echo '<div class"courseDescription">' . esc_html( $descr ) . '</div>';
		}

		if ( $show_course_locations && ! empty( $event_cities ) && $show_city ) {
			$cities = join( ', ', array_keys( $event_cities ) );
			echo '<div class="locationInfo">' . esc_html( $cities ) . '</div> ';
		}

		if ( $show_next_event_date ) {
			echo '<div class="nextEventDate" data-eduwidget="courseitem-date" data-objectid="' . esc_attr( $object['CourseTemplateId'] ) . '">';
			if ( ! empty( $sorted_events ) ) {
				echo sprintf( __( 'Next event %1$s', 'eduadmin-booking' ), date( 'Y-m-d', strtotime( current( $sorted_events )['StartDate'] ) ) ) . " " . current( $sorted_events )["City"];
				if ( $show_event_venue ) {
					echo '<span class="venueInfo">, ' . current( $sorted_events )['AddressName'] . '</span>';
				}
			} else {
				echo '<i>' . __( 'No coming events', 'eduadmin-booking' ) . '</i>';
			}
			echo '</div> ';
		}

		if ( $show_event_price && ! empty( $prices ) ) {
			ksort( $prices );
			$cheapest = current( $prices );
			echo '<div class="priceInfo">' . sprintf( __( 'From %1$s', 'eduadmin-booking' ), convert_to_money( $cheapest['Price'], get_option( 'eduadmin-currency', 'SEK' ) ) ) . " " . ( $inc_vat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) ) . "</div> ";
		}

		if ( $object['Days'] > 0 ) {
			echo
				'<div class="dayInfo">' .
				( $show_course_days ? sprintf( _n( '%1$d day', '%1$d days', $object['Days'], 'eduadmin-booking' ), $object['Days'] ) . ( $show_course_times ? ', ' : '' ) : '' ) .
				( $show_course_times ? date( 'H:i', strtotime( $object['StartTime'] ) ) .
				                       ' - ' .
				                       date( 'H:i', strtotime( $object['EndTime'] ) ) : '' ) .
				'</div>';
		}
		?>
	</div>
	<div class="objectBook">
		<?php if ( $show_read_more_btn ) : ?>
			<a class="readMoreButton cta-btn" href="<?php echo $base_url; ?>/<?php echo make_slugs( $name ); ?>__<?php echo $object["CourseTemplateId"]; ?>/<?php echo edu_get_query_string(); ?>"><?php _e( "Read more", 'eduadmin-booking' ); ?></a>
		<?php endif; ?>
	</div>
</div>
