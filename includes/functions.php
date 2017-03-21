<?php
defined( 'ABSPATH' ) or die( 'This plugin must be run within the scope of WordPress.' );

function edu_SetupWarning()
{
	?>
	<div class="notice notice-warning is-dismissable">
		<p>Please complete the configuration: <a href="<?php echo admin_url(); ?>admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a></p>
	</div>
	<?php
}

function edu_SetupErrors()
{
	settings_errors();
}

add_action('admin_notices', 'edu_SetupErrors');

include_once("_apiFunctions.php");
include_once("_translationFunctions.php");
include_once("_questionFunctions.php");
include_once("_attributeFunctions.php");
include_once("_textFunctions.php");
include_once("_loginFunctions.php");