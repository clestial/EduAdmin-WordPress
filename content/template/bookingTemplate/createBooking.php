<?php
	$ebi = $GLOBALS['edubookinginfo'];
	do_action( 'eduadmin-processbooking', $ebi );
	do_action( 'eduadmin-bookingcompleted', $ebi );
