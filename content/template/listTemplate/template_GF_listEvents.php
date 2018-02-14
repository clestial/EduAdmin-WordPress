<?php
ob_start();
?>
	This template does not support a list of events
<?php
$out = ob_get_clean();

return $out;