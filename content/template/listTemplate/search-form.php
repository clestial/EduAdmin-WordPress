<?php if ( $searchVisible ) { ?>
    <form method="POST" class="search-form">
        <div class="search-row">
            <div class="search-dropdowns">
	            <?php if ( $allowLocationSearch && !empty( $addresses ) && $showEvents ) { ?>
                    <div class="search-item search-dropdown">
                        <select name="eduadmin-city">
                            <option value=""><?php _e( "Choose city", 'eduadmin-booking' ); ?></option>
	                        <?php
		                        $addedCities = array();
		                        foreach ( $addresses as $address ) {
			                        $city = trim( $address["City"] );
			                        if ( !in_array( $address["LocationId"], $addedCities ) && !empty( $city ) ) {
				                        echo '<option value="' . $address["LocationId"] . '"' . ( isset( $_REQUEST['eduadmin-city'] ) && intval( $_REQUEST['eduadmin-city'] ) == $address["LocationId"] ? " selected=\"selected\"" : "" ) . '>' . trim( $address["City"] ) . '</option>';
				                        $addedCities[] = $address["LocationId"];
			                        }
		                        }
	                        ?>
                        </select>
                    </div>
	            <?php } ?>
	            <?php if ( $allowSubjectSearch && !empty( $distinctSubjects ) ) { ?>
                    <div class="search-item search-dropdown">
                        <select name="eduadmin-subject">
                            <option value=""><?php _e( "Choose subject", 'eduadmin-booking' ); ?></option>
	                        <?php
		                        foreach ( $distinctSubjects as $subj => $val ) {
			                        echo '<option value="' . intval( $subj ) . '"' . ( isset( $_REQUEST['eduadmin-subject'] ) && $_REQUEST['eduadmin-subject'] == $subj ? " selected=\"selected\"" : "" ) . '>' . $val . '</option>';
		                        }
	                        ?>
                        </select>
                    </div>
	            <?php } ?>
	            <?php if ( $allowCategorySearch && !empty( $categories ) ) { ?>
                    <div class="search-item search-dropdown">
                        <select name="eduadmin-category">
                            <option value=""><?php _e( "Choose category", 'eduadmin-booking' ); ?></option>
	                        <?php
		                        foreach ( $categories as $subj ) {
			                        echo '<option value="' . intval( $subj["CategoryId"] ) . '"' . ( isset( $_REQUEST['eduadmin-category'] ) && intval( $_REQUEST['eduadmin-category'] ) == $subj["CategoryId"] ? " selected=\"selected\"" : "" ) . '>' . $subj["CategoryName"] . '</option>';
		                        }
	                        ?>
                        </select>
                    </div>
	            <?php } ?>
	            <?php if ( $allowLevelSearch && !empty( $levels ) ) { ?>
                    <div class="search-item search-dropdown">
                        <select name="eduadmin-level">
                            <option value=""><?php _e( "Choose course level", 'eduadmin-booking' ); ?></option>
	                        <?php
		                        foreach ( $levels as $level ) {
			                        echo '<option value="' . $level["CourseLevelId"] . '"' . ( isset( $_REQUEST['eduadmin-level'] ) && intval( $_REQUEST['eduadmin-level'] ) == $level["CourseLevelId"] ? " selected=\"selected\"" : "" ) . '>' . $level["Name"] . '</option>';
		                        }
	                        ?>
                        </select>
                    </div>
	            <?php } ?>
            </div>
            <div class="search-box">
                <div class="search-item search-text">
                    <input class="edu-searchTextBox" type="search" name="searchCourses" results="10"
                           autosave="edu-course-search_<?php echo session_id(); ?>"
                           placeholder="<?php _e( "Search courses", 'eduadmin-booking' ); ?>"<?php if ( isset( $_REQUEST['searchCourses'] ) ) {
	                    echo " value=\"" . sanitize_text_field( $_REQUEST['searchCourses'] ) . "\"";
                    } ?> />
                </div>
                <div class="search-item search-button">
                    <input type="submit" class="searchButton"
                           value="<?php _e( "Search", 'eduadmin-booking' ); ?>"/>
                </div>
            </div>
        </div>
		<?php
			if ( isset( $_REQUEST['searchCourses'] ) ) {
				?>
                <script type="text/javascript">
                    (function () {
                        jQuery('.edu-searchTextBox')[0].scrollIntoView(true);
                        jQuery('.edu-searchTextBox').focus();
                    })();
                </script>
				<?php
			}
		?>
    </form>
<?php }