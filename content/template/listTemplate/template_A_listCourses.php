<?php
ob_start();
include 'list-courses.php';
?>
	<div class="course-holder tmpl_A">
		<?php
		if ( ! empty( $courses ) ) {
			foreach ( $courses as $object ) {
				include 'blocks/course_block.php';
				if ( $show_events_with_events_only && empty( $object['Events'] ) ) {
					continue;
				}

				if ( $show_events_without_events_only && ! empty( $object['Events'] ) ) {
					continue;
				}
				include 'blocks/course_blockA.php';
			}
		} else {
			?>
			<div class="noResults"><?php esc_html_e( 'Your search returned zero results', 'eduadmin-booking' ); ?></div>
			<?php
		}
		?>
	</div>
<?php
$out = ob_get_clean();

return $out;
