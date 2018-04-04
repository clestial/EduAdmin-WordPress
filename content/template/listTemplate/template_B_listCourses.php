<?php
ob_start();
include 'list-courses.php';
?>
	<div class="course-holder tmpl_B">
		<?php

		foreach ( $courses as $object ) {
			include 'blocks/course-block.php';
			if ( $show_events_with_events_only && empty( $object['Events'] ) ) {
				continue;
			}

			if ( $show_events_without_events_only && ! empty( $object['Events'] ) ) {
				continue;
			}
			include 'blocks/course-block-b.php';
		}
		?>
	</div>
<?php
$out = ob_get_clean();

return $out;
