<?php
	ob_start();
	global $wp_query;
	$apiKey = get_option( 'eduadmin-api-key' );

	if ( ! $apiKey || empty( $apiKey ) ) {
		echo 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
	} else {
		include( "list-options.php" );
		?>
        <div class="eduadmin">
            <div class="courseContainer">
	            <?php include( "template_loader.php" ); ?>
            </div>
        </div>
		<?php
	}
	$out = ob_get_clean();
	return $out;