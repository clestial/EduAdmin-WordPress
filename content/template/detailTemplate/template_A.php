<?php
ob_start();
global $wp_query;
$api_key = get_option( 'eduadmin-api-key' );

if ( ! $api_key || empty( $api_key ) ) {
	echo 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
} else {
	include( 'course-info.php' );
	if ( ! $selected_course ) {
		?>
		<script type="text/javascript">location.href = '<?php echo esc_url( $base_url ); ?>';</script>
		<?php
		exit( 200 );
	}
	?>
	<div class="eduadmin">
		<a href="../" class="backLink"><?php esc_html_e( '« Go back', 'eduadmin-booking' ); ?></a>
		<div class="title">
			<?php if ( ! empty( $selected_course['ImageUrl'] ) ) : ?>
				<img src="<?php echo esc_url( $selected_course['ImageUrl'] ); ?>" class="courseImage"/>
			<?php endif; ?>
			<h1 class="courseTitle"><?php echo esc_html( $name ); ?>
				<small class="courseLevel"><?php echo esc_html( null !== $course_level ? $course_level['Name'] : '' ); ?></small>
			</h1>
		</div>
		<hr/>
		<div class="textblock">
			<?php if ( ! in_array( 'description', $hide_sections, true ) && ! empty( $selected_course['CourseDescription'] ) ) { ?>
				<?php if ( $show_headers ) { ?>
					<h3><?php esc_html_e( 'Course description', 'eduadmin-booking' ); ?></h3>
				<?php } ?>
				<div>
					<?php
					echo wp_kses( $selected_course['CourseDescription'], wp_kses_allowed_html( 'post' ) );
					?>
				</div>
			<?php } ?>
			<?php if ( ! in_array( 'goal', $hide_sections, true ) && ! empty( $selected_course['CourseGoal'] ) ) { ?>
				<?php if ( $show_headers ) { ?>
					<h3><?php esc_html_e( 'Course goal', 'eduadmin-booking' ); ?></h3>
				<?php } ?>
				<div>
					<?php
					echo wp_kses( $selected_course['CourseGoal'], wp_kses_allowed_html( 'post' ) );
					?>
				</div>
			<?php } ?>
			<?php if ( ! in_array( 'target', $hide_sections, true ) && ! empty( $selected_course['TargetGroup'] ) ) { ?>
				<?php if ( $show_headers ) { ?>
					<h3><?php esc_html_e( 'Target group', 'eduadmin-booking' ); ?></h3>
				<?php } ?>
				<div>
					<?php
					echo wp_kses( $selected_course['TargetGroup'], wp_kses_allowed_html( 'post' ) );
					?>
				</div>
			<?php } ?>
			<?php if ( ! in_array( 'prerequisites', $hide_sections, true ) && ! empty( $selected_course['Prerequisites'] ) ) { ?>
				<?php if ( $show_headers ) { ?>
					<h3><?php esc_html_e( 'Prerequisites', 'eduadmin-booking' ); ?></h3>
				<?php } ?>
				<div>
					<?php
					echo wp_kses( $selected_course['Prerequisites'], wp_kses_allowed_html( 'post' ) );
					?>
				</div>
			<?php } ?>
			<?php if ( ! in_array( 'after', $hide_sections, true ) && ! empty( $selected_course['CourseAfter'] ) ) { ?>
				<?php if ( $show_headers ) { ?>
					<h3><?php esc_html_e( 'After the course', 'eduadmin-booking' ); ?></h3>
				<?php } ?>
				<div>
					<?php
					echo wp_kses( $selected_course['CourseAfter'], wp_kses_allowed_html( 'post' ) );
					?>
				</div>
			<?php } ?>
			<?php if ( ! in_array( 'quote', $hide_sections, true ) && ! empty( $selected_course['Quote'] ) ) { ?>
				<?php if ( $show_headers ) { ?>
					<h3><?php esc_html_e( 'Quotes', 'eduadmin-booking' ); ?></h3>
				<?php } ?>
				<div>
					<?php
					echo wp_kses( $selected_course['Quote'], wp_kses_allowed_html( 'post' ) );
					?>
				</div>
			<?php } ?>
		</div>
		<div class="eventInformation">
			<?php if ( ! in_array( 'time', $hide_sections, true ) && ! empty( $selected_course['StartTime'] ) && ! empty( $selected_course['EndTime'] ) ) { ?>
				<h3><?php esc_html_e( 'Time', 'eduadmin-booking' ); ?></h3>
				<?php
				/* translators: 1: Number of days */
				echo esc_html( ( $selected_course['Days'] > 0 ? sprintf( _n( '%1$d day', '%1$d days', $selected_course['Days'], 'eduadmin-booking' ), $selected_course['Days'] ) . ', ' : '' ) . date( 'H:i', strtotime( $selected_course['StartTime'] ) ) . ' - ' . date( 'H:i', strtotime( $selected_course['EndTime'] ) ) );
				?>
			<?php } ?>
			<?php

			if ( ! in_array( 'price', $hide_sections, true ) && ! empty( $prices ) ) {
				?>
				<h3><?php esc_html_e( 'Price', 'eduadmin-booking' ); ?></h3>
				<?php
				$currency = get_option( 'eduadmin-currency', 'SEK' );

				if ( 1 === count( $prices ) ) {
					?>
					<?php echo esc_html( sprintf( '%1$s %2$s', current( $prices )['PriceNameDescription'], convert_to_money( current( $prices )['Price'], $currency ) ) . ' ' . ( $inc_vat ? __( 'inc vat', 'eduadmin-booking' ) : __( 'ex vat', 'eduadmin-booking' ) ) ); ?>
					<?php
				} else {
					foreach ( $prices as $up ) {
						?>
						<?php echo esc_html( sprintf( '%1$s %2$s', $up['PriceNameDescription'], convert_to_money( $up['Price'], $currency ) ) . ' ' . ( $inc_vat ? __( 'inc vat', 'eduadmin-booking' ) : __( 'ex vat', 'eduadmin-booking' ) ) ); ?>
						<br/>
						<?php
					}
				}
			}
			?>
		</div>

		<?php
		include( 'blocks/event-list.php' );
		if ( $allow_interest_reg_object && false !== $object_interest_page ) {
			?>
			<br/>
			<div class="inquiry">
				<a class="inquiry-link"
				   href="<?php echo $base_url; ?>/<?php echo make_slugs( $name ); ?>__<?php echo $selected_course['CourseTemplateId']; ?>/interest/<?php echo edu_get_query_string( '?' ); ?>"><?php _e( 'Send inquiry about this course', 'eduadmin-booking' ); ?></a>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}

$out = ob_get_clean();

return $out;
