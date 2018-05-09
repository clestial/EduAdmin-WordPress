<?php
$surl = get_home_url();
$cat  = get_option( 'eduadmin-rewriteBaseUrl' );

$base_url = $surl . '/' . $cat;

function edu_profile_menu_item( $url, $text, $active ) {
	$surl = get_home_url();
	$cat  = get_option( 'eduadmin-rewriteBaseUrl' );

	$base_url = $surl . '/' . $cat;

	echo '<a href="' . esc_url( $base_url . $url ) . '" class="tab_item' . ( $active ? ' active' : '' ) . '">' . esc_html( $text ) . '</a>';
}

?>
<div class="tab_container tabhead">
	<?php
	edu_profile_menu_item( '/profile/myprofile', __( 'Profile', 'eduadmin-booking' ), 'profile' === $tab );
	edu_profile_menu_item( '/profile/certificates', __( 'Certificates', 'eduadmin-booking' ), 'certificates' === $tab );
	edu_profile_menu_item( '/profile/bookings', __( 'Reservations', 'eduadmin-booking' ), 'bookings' === $tab );
	edu_profile_menu_item( '/profile/card', __( 'Discount Cards', 'eduadmin-booking' ), 'limitedDiscount' === $tab );
	?>
</div>
