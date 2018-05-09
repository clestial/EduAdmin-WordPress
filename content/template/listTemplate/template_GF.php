<?php
ob_start();
global $wp_query;
$api_key = get_option( 'eduadmin-api-key' );

if ( ! $api_key || empty( $api_key ) ) {
	echo 'Please complete the configuration: <a href="' . esc_url( admin_url() . 'admin.php?page=eduadmin-settings' ) . '">EduAdmin - Api Authentication</a>';
} else {
	include 'list-options.php';
	?>
	<div class="eduadmin">
		<div class="courseContainer">
			<?php include 'template-loader.php'; ?>
		</div>
	</div>
	<?php
}
$out = ob_get_clean();

return $out;
