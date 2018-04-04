<?php
function edu_render_detail_settings_page() {
	$t = EDU()->start_timer( __METHOD__ );
	?>
	<div class="eduadmin wrap">
		<h2><?php echo esc_html( sprintf( __( 'EduAdmin settings - %s', 'eduadmin-booking' ), __( 'Detail settings', 'eduadmin-booking' ) ) ); ?></h2>

		<form method="post" action="options.php">
			<?php settings_fields( 'eduadmin-details' ); ?>
			<?php do_settings_sections( 'eduadmin-details' ); ?>
			<div class="block">
				<h3><?php esc_html_e( 'Template', 'eduadmin-booking' ); ?></h3>
				<select name="eduadmin-detailTemplate">
					<option value="template_A"<?php echo( get_option( 'eduadmin-detailTemplate' ) === 'template_A' ? ' selected="selected"' : '' ); ?>><?php _e( 'One column layout', 'eduadmin-booking' ); ?></option>
					<option value="template_B"<?php echo( get_option( 'eduadmin-detailTemplate' ) === 'template_B' ? ' selected="selected"' : '' ); ?>><?php _e( 'Two column layout', 'eduadmin-booking' ); ?></option>
				</select>
				<br />
				<br />
				<label>
					<input type="checkbox" name="eduadmin-showDetailHeaders" value="true"<?php if ( get_option( 'eduadmin-showDetailHeaders', true ) ) {
						echo ' checked="checked"';
					} ?> />
					<?php esc_html_e( 'Show headers in detail view', 'eduadmin-booking' ); ?>
				</label>
				<br />
				<i><?php esc_html_e( 'Uncheck to hide the headers in the course detail view', 'eduadmin-booking' ); ?></i>
				<br />
				<br />
				<label>
					<input type="checkbox" name="eduadmin-groupEventsByCity" value="true"<?php if ( get_option( 'eduadmin-groupEventsByCity', false ) ) {
						echo " checked=\"checked\"";
					} ?> />
					<?php esc_html_e( 'Group events by city', 'eduadmin-booking' ); ?>
				</label>
				<br />
				<i><?php esc_html_e( 'Check to group the event list by city', 'eduadmin-booking' ); ?></i>
				<br />
				<br />
				<h3><?php esc_html_e( "Page title", 'eduadmin-booking' ); ?></h3>
				<?php
				$selectedDescriptionField = get_option( 'eduadmin-pageTitleField', 'CourseName' );
				$attributes               = EDUAPI()->OData->CustomFields->Search(
					null,
					"ShowOnWeb and CustomFieldOwner eq 'Product' and CustomFieldSubOwner eq 'CourseTemplate'" .
					" and (CustomFieldType eq 'Text' or CustomFieldType eq 'Html' or CustomFieldType eq 'Textarea')"
				);
				?>
				<i><?php esc_html_e( "Select which field in EduAdmin that should be shown in the page title", 'eduadmin-booking' ); ?></i>
				<br />
				<select name="eduadmin-pageTitleField">
					<optgroup label="<?php _e( "Course fields", 'eduadmin-booking' ); ?>">
						<option value="CourseName"<?php echo( $selectedDescriptionField === 'CourseName' ? ' selected="selected"' : '' ); ?>><?php esc_html_e( 'Public name', 'eduadmin-booking' ); ?></option>
						<option value="InternalCourseName"<?php echo( $selectedDescriptionField === 'InternalCourseName' ? ' selected="selected"' : "" ); ?>><?php esc_html_e( 'Object name', 'eduadmin-booking' ); ?></option>
						<option value="CourseDescriptionShort"<?php echo( $selectedDescriptionField === 'CourseDescriptionShort' ? ' selected="selected"' : "" ); ?>><?php esc_html_e( 'Short course description', 'eduadmin-booking' ); ?></option>
						<option value="CourseDescription"<?php if ( $selectedDescriptionField === 'CourseDescription' ) {
							echo ' selected="selected"';
						} ?>><?php esc_html_e( 'Course description', 'eduadmin-booking' ); ?></option>
						<option value="CourseGoal"<?php if ( $selectedDescriptionField === "CourseGoal" ) {
							echo ' selected="selected"';
						} ?>><?php esc_html_e( "Course goal", 'eduadmin-booking' ); ?></option>
						<option value="CourseTarget"<?php if ( $selectedDescriptionField === "CourseTarget" ) {
							echo ' selected="selected"';
						} ?>><?php esc_html_e( "Target group", 'eduadmin-booking' ); ?></option>
						<option value="CoursePrerequisites"<?php if ( $selectedDescriptionField === "CoursePrerequisites" ) {
							echo ' selected="selected"';
						} ?>><?php esc_html_e( "Prerequisites", 'eduadmin-booking' ); ?></option>
						<option value="CourseAfter"<?php if ( $selectedDescriptionField === "CourseAfter" ) {
							echo ' selected="selected"';
						} ?>><?php esc_html_e( "After the course", 'eduadmin-booking' ); ?></option>
						<option value="CourseQuote"<?php if ( $selectedDescriptionField === "CourseQuote" ) {
							echo ' selected="selected"';
						} ?>><?php esc_html_e( "Quote", 'eduadmin-booking' ); ?></option>
					</optgroup>
					<?php if ( ! empty( $attributes["value"] ) ) { ?>
						<optgroup label="<?php _e( "Course attributes", 'eduadmin-booking' ); ?>">
							<?php foreach ( $attributes['value'] as $attr ) { ?>
								<option value="attr_<?php echo esc_attr( $attr['CustomFieldId'] ); ?>"<?php echo( $selectedDescriptionField === 'attr_' . $attr['CustomFieldId'] ? ' selected="selected"' : '' ); ?>><?php echo esc_html( $attr['CustomFieldName'] ); ?></option>
							<?php } ?>
						</optgroup>
					<?php } ?>
				</select>

				<p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e( 'Save settings', 'eduadmin-booking' ); ?>" />
				</p>
			</div>
		</form>
	</div>
	<?php
	EDU()->stop_timer( $t );
}
