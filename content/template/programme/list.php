<?php

if ( empty( $programmes['Errors'] ) ) {
	?>
	<div class="eduadmin programme-list">
		<div class="programme-header">
			<div><b><?php esc_html_e( 'Programme', 'eduadmin-booking' ); ?></b></div>
			<div><b><?php esc_html_e( 'Length', 'eduadmin-booking' ); ?></b></div>
			<div></div>
			<div></div>
		</div>
		<?php
		foreach ( $programmes['value'] as $programme ) {
			include 'template/list-item.php';
		}
		?>
	</div>
	<?php
}
