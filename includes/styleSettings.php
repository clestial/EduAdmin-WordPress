<?php
	function edu_render_style_settings_page() {
		if ( isset( $_POST['resetStyle'] ) ) {
			delete_option( 'eduadmin-style' );
		}
		?>
        <div class="eduadmin wrap">
            <h2><?php echo sprintf( __( "EduAdmin settings - %s", "eduadmin-booking" ), __( "Style", "eduadmin-booking" ) ); ?></h2>

            <form method="post" action="options.php">
				<?php settings_fields( 'eduadmin-style' ); ?>
				<?php do_settings_sections( 'eduadmin-style' ); ?>
                <div class="block">
                    <div id="eduadmin-style-editor"
                         style="position: relative; min-width: 1000px; width: 100%; min-height: 600px; border: 1px solid #c3c3c3;"></div>
                    <textarea name="eduadmin-style" id="eduadmin-style" style="width: 100%;" cols="250" rows="40"
                              spellcheck="false"><?php
							$defaultCss = file_get_contents( EDUADMIN_PLUGIN_PATH . '/content/style/frontendstyle.css' );
							$css        = get_option( 'eduadmin-style', $defaultCss );
							echo $css;
						?></textarea>

                    <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary"
                               value="<?php echo __( "Save settings", "eduadmin-booking" ); ?>"/>
                        <input type="button"
                               onclick="var c = confirm('<?php _e( "Are you sure you want to reset the style settings?", "eduadmin-booking" ); ?>'); if (c) { var f = document.getElementById('resetForm').submit(); } else { return false; }"
                               class="button button-secondary"
                               value="<?php echo esc_attr__( "Reset styles", "eduadmin-booking" ); ?>"/>
                    </p>
                </div>
            </form>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.3/ace.js" type="text/javascript"
                    charset="utf-8"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.3/ext-language_tools.js" type="text/javascript"
                    charset="utf-8"></script>
            <script>
                ace.require("ace/ext/language_tools");
                var editor = ace.edit("eduadmin-style-editor");
                var textarea = jQuery('#eduadmin-style').hide();
                editor.getSession().setValue(textarea.val());
                editor.getSession().setUseWorker(false);
                editor.setTheme("ace/theme/dawn");
                editor.getSession().setMode("ace/mode/css");
                editor.getSession().on('change', function () {
                    textarea.val(editor.getSession().getValue());
                });

                editor.setOptions({
                    enableBasicAutocompletion: true,
                    enableSnippets: false,
                    enableLiveAutocompletion: true
                });
            </script>
            <form method="post" action="" id="resetForm">
                <input type="hidden" name="resetStyle" value="1"/>
            </form>
        </div>
		<?php
	}