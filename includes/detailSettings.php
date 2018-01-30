<?php
	function edu_render_detail_settings_page() {
		$t = EDU()->StartTimer( __METHOD__ );
		global $eduapi;
		global $edutoken;
		$apiKey = get_option( 'eduadmin-api-key' );
		?>
        <div class="eduadmin wrap">
            <h2><?php echo sprintf( __( "EduAdmin settings - %s", "eduadmin-booking" ), __( "Detail settings", "eduadmin-booking" ) ); ?></h2>

            <form method="post" action="options.php">
				<?php settings_fields( 'eduadmin-details' ); ?>
				<?php do_settings_sections( 'eduadmin-details' ); ?>
                <div class="block">
                    <h3><?php _e( "Template", "eduadmin-booking" ); ?></h3>
                    <select name="eduadmin-detailTemplate">
                        <option value="template_A"<?php echo( get_option( 'eduadmin-detailTemplate' ) === "template_A" ? " selected=\"selected\"" : "" ); ?>><?php _e( "One column layout", "eduadmin-booking" ); ?></option>
                        <option value="template_B"<?php echo( get_option( 'eduadmin-detailTemplate' ) === "template_B" ? " selected=\"selected\"" : "" ); ?>><?php _e( "Two column layout", "eduadmin-booking" ); ?></option>
                    </select>
                    <br/><br/>
                    <label>
                        <input type="checkbox" name="eduadmin-showDetailHeaders"
                               value="true"<?php if ( get_option( 'eduadmin-showDetailHeaders', true ) ) {
							echo " checked=\"checked\"";
						} ?> />
	                    <?php _e( 'Show headers in detail view', 'eduadmin-booking' ); ?>
                    </label>
                    <br/>
                    <i><?php _e( 'Uncheck to hide the headers in the course detail view', 'eduadmin-booking' ); ?></i>
                    <br/><br/>
                    <label>
                        <input type="checkbox" name="eduadmin-groupEventsByCity"
                               value="true"<?php if ( get_option( 'eduadmin-groupEventsByCity', false ) ) {
							echo " checked=\"checked\"";
						} ?> />
	                    <?php _e( 'Group events by city', 'eduadmin-booking' ); ?>
                    </label>
                    <br/>
                    <i><?php _e( 'Check to group the event list by city', 'eduadmin-booking' ); ?></i>
                    <br/>
                    <br/>
                    <h3><?php _e( "Page title", "eduadmin-booking" ); ?></h3>
					<?php
						$selectedDescriptionField = get_option( 'eduadmin-pageTitleField', 'PublicName' );
						$filter                   = new XFiltering();
						$f                        = new XFilter( 'AttributeTypeID', 'IN', '2, 8, 6' );
						$filter->AddItem( $f );
						$f = new XFilter( 'AttributeOwnerTypeID', '=', '1' );
						$filter->AddItem( $f );
						$attributes = $eduapi->GetAttribute( $edutoken, '', $filter->ToString() );
					?>
                    <i><?php _e( "Select which field in EduAdmin that should be shown in the page title", "eduadmin-booking" ); ?></i><br/>
                    <select name="eduadmin-pageTitleField">
                        <optgroup label="<?php _e( "Course fields", "eduadmin-booking" ); ?>">
                            <option value="PublicName"<?php echo( $selectedDescriptionField === "PublicName" ? " selected=\"selected\"" : "" ); ?>><?php _e( "Public name", "eduadmin-booking" ); ?></option>
                            <option value="ObjectName"<?php echo( $selectedDescriptionField === "ObjectName" ? " selected=\"selected\"" : "" ); ?>><?php _e( "Object name", "eduadmin-booking" ); ?></option>
                            <option value="CourseDescriptionShort"<?php echo( $selectedDescriptionField === "CourseDescriptionShort" ? " selected=\"selected\"" : "" ); ?>><?php _e( "Short course description", "eduadmin-booking" ); ?></option>
                            <option value="CourseDescriptionShort"<?php echo( $selectedDescriptionField === "CourseDescriptionShort" ? " selected=\"selected\"" : "" ); ?>><?php _e( "Short course description", "eduadmin-booking" ); ?></option>
                            <option value="CourseDescription"<?php if ( $selectedDescriptionField === "CourseDescription" ) {
								echo " selected=\"selected\"";
                            } ?>><?php _e( "Course description", "eduadmin-booking" ); ?></option>
                            <option value="CourseGoal"<?php if ( $selectedDescriptionField === "CourseGoal" ) {
								echo " selected=\"selected\"";
                            } ?>><?php _e( "Course goal", "eduadmin-booking" ); ?></option>
                            <option value="CourseTarget"<?php if ( $selectedDescriptionField === "CourseTarget" ) {
								echo " selected=\"selected\"";
                            } ?>><?php _e( "Target group", "eduadmin-booking" ); ?></option>
                            <option value="CoursePrerequisites"<?php if ( $selectedDescriptionField === "CoursePrerequisites" ) {
								echo " selected=\"selected\"";
                            } ?>><?php _e( "Prerequisites", "eduadmin-booking" ); ?></option>
                            <option value="CourseAfter"<?php if ( $selectedDescriptionField === "CourseAfter" ) {
								echo " selected=\"selected\"";
                            } ?>><?php _e( "After the course", "eduadmin-booking" ); ?></option>
                            <option value="CourseQuote"<?php if ( $selectedDescriptionField === "CourseQuote" ) {
								echo " selected=\"selected\"";
                            } ?>><?php _e( "Quote", "eduadmin-booking" ); ?></option>
                        </optgroup>
						<?php if ( ! empty( $attributes ) ) { ?>
                            <optgroup label="<?php _e( "Course attributes", "eduadmin-booking" ); ?>">
								<?php foreach ( $attributes as $attr ) { ?>
                                    <option value="attr_<?php echo $attr->AttributeID; ?>"<?php echo( $selectedDescriptionField === "attr_" . $attr->AttributeID ? " selected=\"selected\"" : "" ); ?>><?php echo $attr->AttributeDescription; ?></option>
								<?php } ?>
                            </optgroup>
						<?php } ?>
                    </select>

                    <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary"
                               value="<?php echo __( "Save settings", "eduadmin-booking" ); ?>"/>
                    </p>
                </div>
            </form>
        </div>
		<?php
		EDU()->StopTimer( $t );
	}