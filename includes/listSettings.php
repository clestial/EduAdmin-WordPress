<?php
	function edu_render_list_settings_page() {
		EDU()->timers[ __METHOD__ ] = microtime( true );
		$apiKey                     = get_option( 'eduadmin-api-key' );

		if ( ! $apiKey || empty( $apiKey ) ) {
			add_action( 'admin_notices', array( 'EduAdmin', 'SetupWarning' ) );

			return;
		} else {
			?>
            <div class="eduadmin wrap">
                <h2><?php echo sprintf( __( "EduAdmin settings - %s", "eduadmin-booking" ), __( "List settings", "eduadmin-booking" ) ); ?></h2>

                <form method="post" action="options.php">
					<?php settings_fields( 'eduadmin-list' ); ?>
					<?php do_settings_sections( 'eduadmin-list' ); ?>
                    <div class="block">
                        <table>
                            <tr>
                                <td valign="top">
                                    <h3><?php _e( "Template", "eduadmin-booking" ); ?></h3>
                                    <select name="eduadmin-listTemplate">
                                        <option value="template_A"<?php echo( get_option( 'eduadmin-listTemplate' ) === "template_A" ? " selected=\"selected\"" : "" ); ?>>
                                            Layout A
                                        </option>
                                        <option value="template_B"<?php echo( get_option( 'eduadmin-listTemplate' ) === "template_B" ? " selected=\"selected\"" : "" ); ?>>
                                            Layout B
                                        </option>
                                        <option value="template_GF"<?php echo( get_option( 'eduadmin-listTemplate' ) === "template_GF" ? " selected=\"selected\"" : "" ); ?>>
                                            Layout GF
                                        </option>
                                    </select>
                                    <h3><?php _e( "List settings", "eduadmin-booking" ); ?></h3>
                                    <label>
                                        <input type="checkbox" id="eduadmin-showEventsInList"
                                               name="eduadmin-showEventsInList"<?php if ( get_option( 'eduadmin-showEventsInList', false ) ) {
											echo " checked=\"checked\"";
										} ?> />
										<?php _e( "Show events instead of courses", "eduadmin-booking" ); ?>
                                    </label>
                                    <br/>
                                    <label>
                                        <input type="checkbox" id="eduadmin-showCourseImage"
                                               name="eduadmin-showCourseImage"<?php if ( get_option( 'eduadmin-showCourseImage', true ) ) {
											echo " checked=\"checked\"";
										} ?> />
										<?php _e( "Show course images", "eduadmin-booking" ); ?>
                                    </label>
                                    <br/>
                                    <label>
                                        <input type="checkbox"
                                               name="eduadmin-showNextEventDate"<?php if ( get_option( 'eduadmin-showNextEventDate', false ) ) {
											echo " checked=\"checked\"";
										} ?> />
										<?php _e( "Show coming dates (Only course list, not events)", "eduadmin-booking" ); ?>
                                    </label>
                                    <br/>
                                    <label>
                                        <input type="checkbox"
                                               name="eduadmin-showCourseLocations"<?php if ( get_option( 'eduadmin-showCourseLocations', false ) ) {
											echo " checked=\"checked\"";
										} ?> />
										<?php _e( "Show locations (Only course list, not events)", "eduadmin-booking" ); ?>
                                    </label>
                                    <br/>
                                    <label>
                                        <input type="checkbox"
                                               name="eduadmin-showEventVenueName"<?php if ( get_option( 'eduadmin-showEventVenueName', false ) ) {
											echo " checked=\"checked\"";
										} ?> />
										<?php _e( "Show venue name", "eduadmin-booking" ); ?>
                                    </label>
                                    <br/>
                                    <label>
                                        <input type="checkbox"
                                               name="eduadmin-showEventPrice"<?php if ( get_option( 'eduadmin-showEventPrice', false ) ) {
											echo " checked=\"checked\"";
										} ?> />
										<?php _e( "Show price", "eduadmin-booking" ); ?>
                                    </label>
                                    <br/>
                                    <label>
                                        <input type="checkbox"
                                               name="eduadmin-showCourseDays"<?php if ( get_option( 'eduadmin-showCourseDays', true ) ) {
											echo " checked=\"checked\"";
										} ?> />
										<?php _e( "Show days", "eduadmin-booking" ); ?>
                                    </label>
                                    <br/>
                                    <label>
                                        <input type="checkbox"
                                               name="eduadmin-showCourseTimes"<?php if ( get_option( 'eduadmin-showCourseTimes', true ) ) {
											echo " checked=\"checked\"";
										} ?> />
										<?php _e( "Show time", "eduadmin-booking" ); ?>
                                    </label>
                                    <br/>
                                    <label>
                                        <input type="checkbox"
                                               name="eduadmin-showWeekDays"<?php if ( get_option( 'eduadmin-showWeekDays', false ) ) {
											echo " checked=\"checked\"";
										} ?> />
										<?php _e( "Show weekday names (Only event list)", "eduadmin-booking" ); ?>
                                    </label>
                                    <br/>
                                    <table cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td>
                                                <label>
                                                    <input type="checkbox" id="eduadmin-showCourseDescription"
                                                           name="eduadmin-showCourseDescription"<?php if ( get_option( 'eduadmin-showCourseDescription', true ) ) {
														echo " checked=\"checked\"";
													} ?> />
													<?php _e( "Show course description", "eduadmin-booking" ); ?>
                                                </label>
                                            </td>
                                            <td style="padding-left: 5px;">
												<?php
													$selectedDescriptionField = get_option( 'eduadmin-layout-descriptionfield', 'CourseDescriptionShort' );
													$attributes               = EDUAPI()->OData->CustomFields->Search(
														null,
														"ShowOnWeb and CustomFieldOwner eq 'Product' and CustomFieldSubOwner eq 'CourseTemplate'" .
														" and (CustomFieldType eq 'Text' or CustomFieldType eq 'Html' or CustomFieldType eq 'Textarea')"
													);
												?>
                                                <select name="eduadmin-layout-descriptionfield">
                                                    <optgroup
                                                            label="<?php _e( "Course fields", "eduadmin-booking" ); ?>">
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
													<?php if ( ! empty( $attributes[ "value" ] ) ) { ?>
                                                        <optgroup
                                                                label="<?php _e( "Course attributes", "eduadmin-booking" ); ?>">
															<?php foreach ( $attributes[ "value" ] as $attr ) { ?>
                                                                <option value="attr_<?php echo $attr[ "CustomFieldId" ]; ?>"<?php echo( $selectedDescriptionField === "attr_" . $attr[ "CustomFieldId" ] ? " selected=\"selected\"" : "" ); ?>><?php echo $attr[ "CustomFieldName" ]; ?></option>
															<?php } ?>
                                                        </optgroup>
													<?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                    <br/>
									<?php
										$sortOrder = get_option( 'eduadmin-listSortOrder', 'SortIndex' );
									?>
                                    <table>
                                        <tr>
                                            <td><?php _e( "Sort order", "eduadmin-booking" ); ?></td>
                                            <td>
                                                <select name="eduadmin-listSortOrder">
                                                    <option value="SortIndex"<?php echo( $sortOrder === "SortIndex" ? " selected=\"selected\"" : "" ); ?>><?php _e( "Sort index", "eduadmin-booking" ); ?></option>
                                                    <option value="PublicName"<?php echo( $sortOrder === "PublicName" ? " selected=\"selected\"" : "" ); ?>><?php _e( "Course name", "eduadmin-booking" ); ?></option>
                                                    <option value="CategoryName"<?php echo( $sortOrder === "CategoryName" ? " selected=\"selected\"" : "" ); ?>><?php _e( "Category name", "eduadmin-booking" ); ?></option>
                                                    <option value="ItemNr"<?php echo( $sortOrder === "ItemNr" ? " selected=\"selected\"" : "" ); ?>><?php _e( "Item number", "eduadmin-booking" ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                    </table>
                                    <br/>
                                    <h3><?php _e( "Filter options", "eduadmin-booking" ); ?></h3>
                                    <label>
                                        <input type="checkbox"
                                               name="eduadmin-allowLocationSearch"<?php if ( get_option( 'eduadmin-allowLocationSearch', true ) ) {
											echo " checked=\"checked\"";
										} ?> />
										<?php _e( "Allow filter by city", "eduadmin-booking" ); ?>
                                    </label>
                                    <br/>
                                    <label>
                                        <input type="checkbox"
                                               name="eduadmin-allowSubjectSearch"<?php if ( get_option( 'eduadmin-allowSubjectSearch', false ) ) {
											echo " checked=\"checked\"";
										} ?> />
										<?php _e( "Allow filter by subject", "eduadmin-booking" ); ?>
                                    </label>
                                    <br/>
                                    <label>
                                        <input type="checkbox"
                                               name="eduadmin-allowCategorySearch"<?php if ( get_option( 'eduadmin-allowCategorySearch', false ) ) {
											echo " checked=\"checked\"";
										} ?> />
										<?php _e( "Allow filter by category", "eduadmin-booking" ); ?>
                                    </label>
                                    <br/>
                                    <label>
                                        <input type="checkbox"
                                               name="eduadmin-allowLevelSearch"<?php if ( get_option( 'eduadmin-allowLevelSearch', false ) ) {
											echo " checked=\"checked\"";
										} ?> />
										<?php _e( "Allow filter by course level", "eduadmin-booking" ); ?>
                                    </label>
                                </td>
                                <td valign="top">
                                    <table>
                                        <tr>
                                            <td align="center">
                                                <img src="<?php echo plugins_url( '../images', __FILE__ ); ?>/layoutA_list.png"/><br/>
                                                Layout A
                                            </td>
                                            <td align="center">
                                                <img src="<?php echo plugins_url( '../images', __FILE__ ); ?>/layoutB_list.png"/><br/>
                                                Layout B
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary"
                               value="<?php esc_attr_e( "Save changes", "eduadmin-booking" ); ?>"/>
                    </p>
                </form>
            </div>
		<?php }
		EDU()->timers[ __METHOD__ ] = microtime( true ) - EDU()->timers[ __METHOD__ ];
	}