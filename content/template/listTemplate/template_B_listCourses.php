<?php
ob_start();
include 'list-courses.php';
?>
	<div class="course-holder tmpl_B">
		<?php

		foreach ( $courses as $object ) {
			include 'blocks/course-block.php';
			if ( $showEventsWithEventsOnly && empty( $object["Events"] ) ) {
				continue;
			}

			if ( $showEventsWithoutEventsOnly && ! empty( $object["Events"] ) ) {
				continue;
			}
			include 'blocks/course-block-b.php';
		}
		?>
	</div>
<?php
$out = ob_get_clean();

return $out;
