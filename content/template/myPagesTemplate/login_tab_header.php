<?php
	$surl = get_home_url();
	$cat  = get_option( 'eduadmin-rewriteBaseUrl' );

	$baseUrl = $surl . '/' . $cat;

?>
<div class="tab_container tabhead">
    <a href="<?php echo $baseUrl; ?>/profile/myprofile" class="tab_item<?php if ( $tab === "profile" ) {
		echo " active";
	} ?>"><?php _e( "Profile", 'eduadmin-booking' ); ?></a>
    <a href="<?php echo $baseUrl; ?>/profile/certificates" class="tab_item<?php if ( $tab === "certificates" ) {
		echo " active";
	} ?>"><?php _e( "Certificates", 'eduadmin-booking' ); ?></a>
    <a href="<?php echo $baseUrl; ?>/profile/bookings" class="tab_item<?php if ( $tab === "bookings" ) {
		echo " active";
	} ?>"><?php _e( "Reservations", 'eduadmin-booking' ); ?></a>
    <a href="<?php echo $baseUrl; ?>/profile/card" class="tab_item<?php if ( $tab == "limitedDiscount" ) {
		echo " active";
	} ?>"><?php _e( "Discount Cards", 'eduadmin-booking' ); ?></a>
</div>
