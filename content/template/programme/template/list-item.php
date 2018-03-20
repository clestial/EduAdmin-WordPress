<div class="programme-list-item">
	<div class="programme-name">
		<?php
		echo esc_html( $programme['ProgrammeName'] );
		?>
	</div>
	<div class="programme-length">
		<?php
		if ( ! empty( $programme['LengthUnit'] ) && ! empty( $programme['Length'] ) ) {
			switch ( $programme['LengthUnit'] ) {
				case 'Days':
					/* translators: 1. Amount */
					echo esc_html( sprintf( _nx( '%d day', '%d days', intval( $programme['Length'] ), 'Length of programme', 'eduadmin-booking' ), intval( $programme['Length'] ) ) );
					break;
				case 'Weeks':
					/* translators: 1. Amount */
					echo esc_html( sprintf( _nx( '%d week', '%d weeks', intval( $programme['Length'] ), 'Length of programme', 'eduadmin-booking' ), intval( $programme['Length'] ) ) );
					break;
				case 'Months':
					/* translators: 1. Amount */
					echo esc_html( sprintf( _nx( '%d month', '%d months', intval( $programme['Length'] ), 'Length of programme', 'eduadmin-booking' ), intval( $programme['Length'] ) ) );
					break;
			}
		}
		?>
	</div>
	<div class="programme-nextstart">
		<?php
		if ( ! empty( $programme['ProgrammeStarts'] ) ) {
			$next_start = current( $programme['ProgrammeStarts'] );
			/* translators: 1. The date for the next programme start */
			echo wp_kses_post( sprintf( _x( 'Next start: %s', 'Next programme start', 'eduadmin-booking' ), get_display_date( $next_start['StartDate'] ) ) );
		}
		?>
	</div>
	<div class="programme-buttons">
		<a href="#" class="cta-btn">Placeholder</a>
	</div>
	<?php
	if ( ! empty( $_GET['debug'] ) ) {
		EDU()->write_debug( $programme );
	}
	?>
</div>
