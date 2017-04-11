<?php
if (isset($_POST['resetTranslation'])) {
	delete_option('eduadmin-phrases');
}
?>
<div class="eduadmin wrap">
	<h2><?php echo sprintf(__("EduAdmin settings - %s", "eduadmin"), __("Translation", "eduadmin")); ?></h2>

	<form method="post" action="options.php">
		<?php settings_fields('eduadmin-phrases'); ?>
		<?php do_settings_sections('eduadmin-phrases'); ?>
		<div class="block">
		<b><i><span style="color: red;"><?php _e("Translation is now handled in third party plugins to handle multiple languages.", "eduadmin"); ?></span></i></b>
		<br />
			<table>
				<tr>
					<td><h3><?php _e("Key text", "eduadmin"); ?></h3></td>
					<td><h3><?php _e("Translation", "eduadmin"); ?></h3></td>
				</tr>
<?php
	delete_transient('eduadmin-phrases');
	$phrasesstr = get_option('eduadmin-phrases');
	$phrases = edu_LoadPhrases();

	ksort($phrases, SORT_NATURAL | SORT_FLAG_CASE);

	foreach ($phrases as $phrase => $translation) {
		__($phrase, "eduadmin");
		?>
		<tr>
			<td><?php echo $phrase; ?></td>
			<td><input type="text" class="form-control" style="width: 300px;" onblur="update_phrase(event);" data-key="<?php echo esc_attr($phrase); ?>" placeholder="<?php echo esc_attr($phrase); ?>" value="<?php echo esc_attr($translation["OldPhrase"]); ?>" /></td>
		</tr>
		<?php
	}
?>
			</table>
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo esc_attr__("Save settings", "eduadmin"); ?>" />
				<input type="button" onclick="var c = confirm('<?php _e("Are you sure you want to reset the translation?", "eduadmin"); ?>'); if (c) { var f = document.getElementById('resetForm').submit(); } else { return false; }" class="button button-secondary" value="<?php echo esc_attr__("Reset translations", "eduadmin"); ?>" />
			</p>
		</div>
		<input type="hidden" id="eduadmin-phrases" name="eduadmin-phrases" value='' />

	</form>
	<script type="text/javascript">
	var loadedPhrases;
	(function() {
		loadedPhrases = <?php echo $phrasesstr; ?>;
		document.getElementById('eduadmin-phrases').value = JSON.stringify(loadedPhrases);
	})();

	function update_phrase(item)
	{
		var t = item.target;
		var key = t.attributes['data-key'].value;
		var translation = t.value;
		if(translation == '') {
			delete loadedPhrases[key];
		} else {
			loadedPhrases[key] = translation;
		}
		document.getElementById('eduadmin-phrases').value = JSON.stringify(loadedPhrases);
	}
	</script>
	<form method="post" action="" id="resetForm">
		<input type="hidden" name="resetTranslation" value="1" />
	</form>
</div>