<?php
$surl = get_home_url();
$cat  = get_option( 'eduadmin-rewriteBaseUrl' );

$base_url = $surl . '/' . $cat;

?>
<div class="tab_container tabhead">
	<a href="<?php echo $base_url; ?>/profile/myprofile" class="tab_item<?php
	if ( 'profile' === $tab ) {
		echo ' active';
	} ?>">
		<?php esc_html_e( 'Profile', 'eduadmin-booking' ); ?>
	</a>
	<a href="<?php echo $base_url; ?>/profile/certificates" class="tab_item<?php
	if ( 'certificates' === $tab ) {
		echo ' active';
	} ?>">
		<?php esc_html_e( 'Certificates', 'eduadmin-booking' ); ?>
	</a>
	<a href="<?php echo $base_url; ?>/profile/bookings" class="tab_item<?php
	if ( 'bookings' === $tab ) {
		echo ' active';
	} ?>">
		<?php esc_html_e( 'Reservations', 'eduadmin-booking' ); ?>
	</a>
	<a href="<?php echo $base_url; ?>/profile/card" class="tab_item<?php
	if ( 'limitedDiscount' === $tab ) {
		echo ' active';
	} ?>">
		<?php esc_html_e( 'Discount Cards', 'eduadmin-booking' ); ?>
	</a>
</div>
