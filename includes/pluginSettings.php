<div class="eduadmin wrap">
	<h2><?php echo sprintf( __( "EduAdmin settings - %s", "eduadmin" ), __( "Plugins", "eduadmin" ) ); ?></h2>

	<form method="post">
		<?php settings_fields( 'eduadmin-plugin-settings' ); ?>
		<?php do_settings_sections( 'eduadmin-plugin-settings' ); ?>
		<div class="block">
		<h3><?php _e( "Installed plugins", "eduadmin" ); ?></h3>
		<hr noshade="noshade" />
		<?php
		$integrations = EDU()->integrations->integrations;
		foreach ( $integrations as $integration ) {
			echo "<h3>" . esc_html( $integration->displayName ) . "</h3>\n";
			echo $integration->get_settings();
			echo "<hr />\n";
		}
		?>
		</div>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e( "Save changes", "eduadmin" ); ?>" />
		</p>
	</form>
</div>