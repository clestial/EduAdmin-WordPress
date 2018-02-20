<?php
ob_start();
global $wp_query;
$api_key = get_option( 'eduadmin-api-key' );

if ( ! $api_key || empty( $api_key ) ) {
	echo 'Please complete the configuration: <a href="' . esc_url( admin_url() . 'admin.php?page=eduadmin-settings' ) . '">EduAdmin - Api Authentication</a>';
} else {
	include_once 'course-info.php';
	include_once '__loginHandler.php';
	?>
	<div class="eduadmin loginForm">
		<form action="<?php echo( isset( $_REQUEST['eid'] ) ? '?eid=' . esc_attr( sanitize_text_field( $_REQUEST['eid'] ) ) : '' ); ?>" method="post">
			<a href="../" class="backLink"><?php esc_html_e( '« Go back', 'eduadmin-booking' ); ?></a>
			<div class="title">
				<?php if ( ! empty( $selected_course['ImageUrl'] ) ) : ?>
					<img class="courseImage" src="<?php echo esc_url( $selected_course['ImageUrl'] ); ?>"/>
				<?php endif; ?>
				<h1 class="courseTitle"><?php echo esc_html( $name ); ?></h1>
				<?php require_once 'event-selector.php'; ?>
				<?php
				if ( ! isset( EDU()->session['checkEmail'] ) ) {
					include_once '__checkEmail.php';
				} elseif ( isset( EDU()->session['checkEmail'] ) ) {
					if ( isset( EDU()->session['needsLogin'] ) && true === EDU()->session['needsLogin'] ) {
						include_once '__loginForm.php';
					} else {
						unset( EDU()->session['checkEmail'] );
						unset( EDU()->session['needsLogin'] );
						?>
						<script type="text/javascript">(function () {
								location.reload(true);
							})();</script>
						<?php
					}
				}
				?>
			</div>
		</form>
	</div>
	<?php
}
$out = ob_get_clean();

return $out;
