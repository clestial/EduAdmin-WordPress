<?php
	function edu_render_general_settings() {
		EDU()->timers[ __METHOD__ ] = microtime( true );
		if ( isset( $_REQUEST['act'] ) && $_REQUEST['act'] == "clearTransients" ) {
			global $wpdb;

			$prefix     = esc_sql( 'eduadmin-' );
			$options    = $wpdb->options;
			$t          = esc_sql( "%transient%$prefix%" );
			$sql        = $wpdb->prepare( "SELECT option_name FROM $options WHERE option_name LIKE '%s'", $t );
			$transients = $wpdb->get_col( $sql );
			foreach ( $transients as $transient ) {
				$key = str_replace( '_transient_timeout_', '', $transient );
				delete_transient( $key );
			}

			wp_cache_flush();
		}
		?>
        <div class="eduadmin wrap">
            <h2><?php echo sprintf( __( "EduAdmin settings - %s", "eduadmin-booking" ), __( "General", "eduadmin-booking" ) ); ?></h2>

            <form method="post" action="options.php">
				<?php settings_fields( 'eduadmin-rewrite' ); ?>
				<?php do_settings_sections( 'eduadmin-rewrite' ); ?>
                <div class="block">
                    <h3><?php _e( "General settings", "eduadmin-booking" ); ?></h3>
	                <?php _e( "Availability text", "eduadmin-booking" ); ?><br/>
					<?php
						$spotLeft = get_option( 'eduadmin-spotsLeft', 'exactNumbers' );
					?>
                    <select class="eduadmin-spotsLeft" name="eduadmin-spotsLeft" onchange="EduAdmin.SpotExampleText();">
                        <option<?php echo( $spotLeft === "exactNumbers" ? " selected=\"selected\"" : "" ); ?>
                                value="exactNumbers"><?php _e( "Exact numbers", "eduadmin-booking" ); ?></option>
                        <option<?php echo( $spotLeft === "onlyText" ? " selected=\"selected\"" : "" ); ?>
                                value="onlyText"><?php _e( "Only text (Spots left/ Few spots / No spots left)", "eduadmin-booking" ); ?></option>
                        <option<?php echo( $spotLeft === "intervals" ? " selected=\"selected\"" : "" ); ?>
                                value="intervals"><?php _e( "Interval (Please specify below)", "eduadmin-booking" ); ?></option>
                        <option<?php echo( $spotLeft === "alwaysFewSpots" ? " selected=\"selected\"" : "" ); ?>
                                value="alwaysFewSpots"><?php _e( "Always few spots", "eduadmin-booking" ); ?></option>
                    </select> <span id="eduadmin-spotExampleText"></span>
                    <br/>
                    <div class="eduadmin-spotsSettings">
                        <div id="eduadmin-intervalSetting">
                            <br/>
                            <b><?php _e( "Interval settings", "eduadmin-booking" ); ?></b><br/>
	                        <?php _e( "Insert one interval range per row (1-3, 4-10, 10+)", "eduadmin-booking" ); ?>
                            <br/>
                            <textarea name="eduadmin-spotsSettings" class="form-control" rows="5"
                                      cols="30"><?php echo get_option( 'eduadmin-spotsSettings', "1-5\n5-10\n10+" ); ?></textarea>
                        </div>
                        <div id="eduadmin-alwaysFewSpots">
                            <br/>
                            <b><?php _e( "Number of participants before showing as \"Few spots left\"", "eduadmin-booking" ); ?></b><br/>
                            <input type="number" name="eduadmin-alwaysFewSpots"
                                   value="<?php echo esc_attr( get_option( 'eduadmin-alwaysFewSpots', "3" ) ); ?>"/>
                        </div>
                    </div>
                    <br/>
	                <?php _e( "Number of months to fetch events for", "eduadmin-booking" ); ?><br/>
                    <input type="number" name="eduadmin-monthsToFetch"
                           value="<?php echo esc_attr( get_option( 'eduadmin-monthsToFetch', '6' ) ); ?>"/> <?php _e( "months", "eduadmin-booking" ); ?>
                    <br/>
                    <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary"
                               value="<?php esc_attr_e( "Save settings", "eduadmin-booking" ); ?>"/>
                    </p>
                    <h3><?php _e( "Rewrite settings", "eduadmin-booking" ); ?></h3>
                    <p>
	                    <?php echo __( "Enter the URL you want to use with the application (please check that the URL does not exists)", "eduadmin-booking" ); ?>
                    </p>
					<?php echo home_url(); ?>/<input style="width: 200px;" type="text" class="form-control folder"
                                                     name="eduadmin-rewriteBaseUrl" id="eduadmin-rewriteBaseUrl"
                                                     value="<?php echo esc_attr( get_option( 'eduadmin-rewriteBaseUrl' ) ); ?>"
                                                     placeholder="<?php echo __( "URL", "eduadmin-booking" ); ?>"/>/
					<?php
						$pages    = get_pages();
						$eduPages = array();
						foreach ( $pages as $p ) {
							if ( strstr( $p->post_content, '[eduadmin' ) ) {
								$eduPages[] = $p;
							}
						}
					?>
                    <table>
                        <tr>
                            <td><?php _e( "List view page", "eduadmin-booking" ); ?></td>
                            <td>
                                <select class="form-control" style="width: 300px;" name="eduadmin-listViewPage"
                                        id="eduadmin-listViewPage">
                                    <option value="">-- <?php _e( "No page selected", "eduadmin-booking" ); ?>--
                                    </option>
									<?php
										$listPage = get_option( 'eduadmin-listViewPage' );
										foreach ( $eduPages as $p ) {
											$suggested = false;
											if ( stristr( $p->post_content, '[eduadmin-listview' ) ) {
												$suggested = true;
											}
											echo "\t\t\t\t\t\t\t<option" . ( $listPage == $p->ID ? " selected=\"selected\"" : "" ) . " value=\"" . $p->ID . "\">" .
											     htmlentities( $p->post_title . " (ID: " . $p->ID . ")" ) .
											     ( $suggested ? " (" . __( "suggested", "eduadmin-booking" ) . ")" : "" ) .
											     "</option>\n";
										}
									?>
                                </select>
                            </td>
                            <td>
                                <i title="<?php esc_attr_e( "Shortcode to use in your page", "eduadmin-booking" ); ?>">[eduadmin-listview]</i>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo __( "Details view page", "eduadmin-booking" ); ?></td>
                            <td>
                                <select class="form-control" style="width: 300px;" name="eduadmin-detailViewPage"
                                        id="eduadmin-detailViewPage">
                                    <option value="">-- <?php _e( "No page selected", "eduadmin-booking" ); ?>--
                                    </option>
									<?php
										$detailPage = get_option( 'eduadmin-detailViewPage' );
										foreach ( $eduPages as $p ) {
											$suggested = false;
											if ( stristr( $p->post_content, '[eduadmin-detailview' ) ) {
												$suggested = true;
											}
											echo "\t\t\t\t\t\t\t<option" . ( $detailPage == $p->ID ? " selected=\"selected\"" : "" ) . " value=\"" . $p->ID . "\">" .
											     htmlentities( $p->post_title . " (ID: " . $p->ID . ")" ) .
											     ( $suggested ? " (" . __( "suggested", "eduadmin-booking" ) . ")" : "" ) .
											     "</option>\n";
										}
									?>
                                </select>
                            </td>
                            <td>
                                <i title="<?php esc_attr_e( "Shortcode to use in your page", "eduadmin-booking" ); ?>">[eduadmin-detailview]</i>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo __( "Booking view page", "eduadmin-booking" ); ?></td>
                            <td>
                                <select class="form-control" style="width: 300px;" name="eduadmin-bookingViewPage"
                                        id="eduadmin-bookingViewPage">
                                    <option value="">-- <?php _e( "No page selected", "eduadmin-booking" ); ?>--
                                    </option>
									<?php
										$bookingPage = get_option( 'eduadmin-bookingViewPage' );
										foreach ( $eduPages as $p ) {
											$suggested = false;
											if ( stristr( $p->post_content, '[eduadmin-bookingview' ) ) {
												$suggested = true;
											}
											echo "\t\t\t\t\t\t\t<option" . ( $bookingPage == $p->ID ? " selected=\"selected\"" : "" ) . " value=\"" . $p->ID . "\">" .
											     htmlentities( $p->post_title . " (ID: " . $p->ID . ")" ) .
											     ( $suggested ? " (" . __( "suggested", "eduadmin-booking" ) . ")" : "" ) .
											     "</option>\n";
										}
									?>
                                </select>
                            </td>
                            <td>
                                <i title="<?php esc_attr_e( "Shortcode to use in your page", "eduadmin-booking" ); ?>">[eduadmin-bookingview]</i>
                            </td>
                        </tr>
                        <tr>
                            <td><?php _e( "Thank you page", "eduadmin-booking" ); ?></td>
                            <td>
                                <select class="form-control" style="width: 300px;" name="eduadmin-thankYouPage"
                                        id="eduadmin-thankYouPage">
                                    <option value="">-- <?php _e( "No page selected", "eduadmin-booking" ); ?>--
                                    </option>
									<?php
										$thankPage = get_option( 'eduadmin-thankYouPage' );
										foreach ( $pages as $p ) {
											echo "\t\t\t\t\t\t\t<option" . ( $thankPage == $p->ID ? " selected=\"selected\"" : "" ) . " value=\"" . $p->ID . "\">" .
											     htmlentities( $p->post_title . " (ID: " . $p->ID . ")" ) .
											     "</option>\n";
										}
									?>
                                </select>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><?php echo __( "Login page", "eduadmin-booking" ); ?></td>
                            <td>
                                <select class="form-control" style="width: 300px;" name="eduadmin-loginViewPage"
                                        id="eduadmin-loginViewPage">
                                    <option value="">-- <?php _e( "No page selected", "eduadmin-booking" ); ?>--
                                    </option>
									<?php
										$loginPage = get_option( 'eduadmin-loginViewPage' );
										foreach ( $eduPages as $p ) {
											$suggested = false;
											if ( stristr( $p->post_content, '[eduadmin-loginview' ) ) {
												$suggested = true;
											}
											echo "\t\t\t\t\t\t\t<option" . ( $loginPage == $p->ID ? " selected=\"selected\"" : "" ) . " value=\"" . $p->ID . "\">" .
											     htmlentities( $p->post_title . " (ID: " . $p->ID . ")" ) .
											     ( $suggested ? " (" . __( "suggested", "eduadmin-booking" ) . ")" : "" ) .
											     "</option>\n";
										}
									?>
                                </select>
                            </td>
                            <td>
                                <i title="<?php esc_attr_e( "Shortcode to use in your page", "eduadmin-booking" ); ?>">[eduadmin-loginview]</i>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo __( "Course interest page", "eduadmin-booking" ); ?></td>
                            <td>
                                <select class="form-control" style="width: 300px;" name="eduadmin-interestObjectPage"
                                        id="eduadmin-interestObjectPage">
                                    <option value="">-- <?php _e( "No page selected", "eduadmin-booking" ); ?>--
                                    </option>
									<?php
										$objectInterestPage = get_option( 'eduadmin-interestObjectPage' );
										foreach ( $eduPages as $p ) {
											$suggested = false;
											if ( stristr( $p->post_content, '[eduadmin-objectinterest' ) ) {
												$suggested = true;
											}
											echo "\t\t\t\t\t\t\t<option" . ( $objectInterestPage == $p->ID ? " selected=\"selected\"" : "" ) . " value=\"" . $p->ID . "\">" .
											     htmlentities( $p->post_title . " (ID: " . $p->ID . ")" ) .
											     ( $suggested ? " (" . __( "suggested", "eduadmin-booking" ) . ")" : "" ) .
											     "</option>\n";
										}
									?>
                                </select>
                            </td>
                            <td>
                                <i title="<?php esc_attr_e( "Shortcode to use in your page", "eduadmin-booking" ); ?>">[eduadmin-objectinterest]</i>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo __( "Event interest page", "eduadmin-booking" ); ?></td>
                            <td>
                                <select class="form-control" style="width: 300px;" name="eduadmin-interestEventPage"
                                        id="eduadmin-interestEventPage">
                                    <option value="">-- <?php _e( "No page selected", "eduadmin-booking" ); ?>--
                                    </option>
									<?php
										$eventInterestPage = get_option( 'eduadmin-interestEventPage' );
										foreach ( $eduPages as $p ) {
											$suggested = false;
											if ( stristr( $p->post_content, '[eduadmin-eventinterest' ) ) {
												$suggested = true;
											}
											echo "\t\t\t\t\t\t\t<option" . ( $eventInterestPage == $p->ID ? " selected=\"selected\"" : "" ) . " value=\"" . $p->ID . "\">" .
											     htmlentities( $p->post_title . " (ID: " . $p->ID . ")" ) .
											     ( $suggested ? " (" . __( "suggested", "eduadmin-booking" ) . ")" : "" ) .
											     "</option>\n";
										}
									?>
                                </select>
                            </td>
                            <td>
                                <i title="<?php esc_attr_e( "Shortcode to use in your page", "eduadmin-booking" ); ?>">[eduadmin-eventinterest]</i>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="eduadmin-options_have_changed" value="true"/>
                    <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary"
                               value="<?php esc_attr_e( "Save settings", "eduadmin-booking" ); ?>"/>
                    </p>
                </div>
            </form>
            <form action="" method="POST">
                <input type="hidden" name="act" value="clearTransients"/>
                <input type="submit" class="button button-primary"
                       value="<?php esc_attr_e( "Clear transients/cache", "eduadmin-booking" ); ?>"/>
            </form>
        </div>
        <script type="text/javascript">

            var availText = {
                exactNumbers: "<?php esc_attr_e( 'No spots left / 5 spots left', 'eduadmin-booking' ); ?>",
                onlyText: "<?php esc_attr_e( 'No spots left / Few spots left / Spots left', 'eduadmin-booking' ); ?>",
                intervals: "<?php esc_attr_e( 'No spots left / 3-5 spots left / 6+ spots left', 'eduadmin-booking' ); ?>",
                alwaysFewSpots: "<?php esc_attr_e( 'Few spots left', 'eduadmin-booking' ); ?>"
            };

            jQuery(document).ready(function () {
                EduAdmin.SpotExampleText();
            });
        </script>
		<?php
		EDU()->timers[ __METHOD__ ] = microtime( true ) - EDU()->timers[ __METHOD__ ];
	}