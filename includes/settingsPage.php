<?php
function edu_render_settings_page() {
	EDU()->timers[ __METHOD__ ] = microtime( true );
	if ( get_option( 'eduadmin-credentials_have_changed' ) ) {
		delete_transient( 'eduadmin-token' );
		delete_transient( 'eduadmin-listCourses' );
		delete_transient( 'eduadmin-events-object' );
		delete_transient( 'eduadmin-courseSubject' );
		delete_transient( 'eduadmin-listEvents' );
		delete_transient( 'eduadmin-courseLevels' );
		delete_transient( 'eduadmin-levels' );
		delete_transient( 'eduadmin-categories' );
		delete_transient( 'eduadmin-locations' );
		delete_transient( 'eduadmin-subjects' );

		update_option( 'eduadmin-credentials_have_changed', false );
	}
	?>
	<div class="eduadmin wrap">
		<h2><?php echo sprintf( __( "EduAdmin settings - %s", 'eduadmin-booking' ), __( "Api authentication", 'eduadmin-booking' ) ); ?></h2>

		<form method="post" action="options.php">
			<?php settings_fields( 'eduadmin-credentials' ); ?>
			<?php do_settings_sections( 'eduadmin-credentials' ); ?>
			<input type="hidden" name="eduadmin-credentials_have_changed" value="true"/>
			<div class="block">
				<p>
					<?php echo __( "Enter the provided Api Key to connect to EduAdmin", 'eduadmin-booking' ); ?>
				</p>
				<p>
					<?php echo sprintf( __( "You can get these details by contacting %s", 'eduadmin-booking' ), sprintf( "<a href=\"http://support.multinet.se\" target=\"_blank\">%s</a>", __( "our support", 'eduadmin-booking' ) ) ); ?>
				</p>
				<input type="text" readonly class="form-control api_hash" name="eduadmin-api-key" id="eduadmin-api-key" value="<?php echo get_option( 'eduadmin-api-key' ); ?>" placeholder="<?php esc_attr_e( "Api key for WordPress plugin", 'eduadmin-booking' ); ?>"/>
				<span id="edu-unlockButton" title="<?php esc_attr_e( "Click here to unlock the Api Authentication-fields", 'eduadmin-booking' ); ?>" class="dashicons dashicons-lock" onclick="EduAdmin.UnlockApiAuthentication();"></span>
				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_attr__( "Save settings", 'eduadmin-booking' ); ?>"/>
				</p>
			</div>
		</form>
	</div>
	<?php
	EDU()->timers[ __METHOD__ ] = microtime( true ) - EDU()->timers[ __METHOD__ ];
}
