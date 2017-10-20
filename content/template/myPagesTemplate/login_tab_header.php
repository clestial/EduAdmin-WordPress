<?php
	$surl = get_home_url();
	$cat  = get_option( 'eduadmin-rewriteBaseUrl' );

	$baseUrl = $surl . '/' . $cat;

?>
<div class="tab_container tabhead">
    <a href="<?php echo $baseUrl; ?>/profile/myprofile" class="tab_item<?php if ( $tab === "profile" ) {
		echo " active";
	} ?>"><?php edu_e( "Profile" ); ?></a>
    <a href="<?php echo $baseUrl; ?>/profile/bookings" class="tab_item<?php if ( $tab === "bookings" ) {
		echo " active";
	} ?>"><?php edu_e( "Reservations" ); ?></a>
    <a href="<?php echo $baseUrl; ?>/profile/card" class="tab_item<?php if ( $tab == "limitedDiscount" ) {
		echo " active";
	} ?>"><?php edu_e( "Discount Cards" ); ?></a>
</div>
