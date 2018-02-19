<?php
$api_key = get_option( 'eduadmin-api-key' );
ob_start();

if ( ! $api_key || empty( $api_key ) ) {
	return 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
} else {
	$sorting         = array();
	$custom_order    = null;
	$custom_order_by = null;
	if ( ! empty( $attributes['order'] ) ) {
		$custom_order = $attributes['order'];
	}

	if ( ! empty( $attributes['orderby'] ) ) {
		$custom_order_by = $attributes['orderby'];
	}

	if ( null !== $custom_order_by ) {
		$orderby   = explode( ' ', $custom_order );
		$sortorder = explode( ' ', $custom_order_by );
		foreach ( $orderby as $od => $v ) {
			if ( isset( $sortorder[ $od ] ) ) {
				$or = $sortorder[ $od ];
			} else {
				$or = 'asc';
			}

			$sorting[] = $v . ' ' . strtolower( $or );
		}
	} else {
		$sorting[] = 'PriceNameId asc';
	}

	$edo = get_transient( 'eduadmin-objectpublicpricename_' . $course_id );
	if ( ! $edo ) {
		$edo = EDUAPI()->OData->CourseTemplates->GetItem(
			$course_id,
			'CourseTemplateId',
			'PriceNames($filter=PublicPriceName;$orderby=' . join( ',', $sorting ) . ')'
		)['PriceNames'];
		set_transient( 'eduadmin-objectpublicpricename_' . $course_id, $edo, 10 );
	}

	if ( ! empty( $attributes['numberofprices'] ) ) {
		$edo = array_slice( $edo, 0, $attributes['numberofprices'], true );
	}

	$currency = get_option( 'eduadmin-currency', 'SEK' );
	$inc_vat  = EDUAPI()->REST->Organisation->GetOrganisation()['PriceIncVat'];
	?>
	<div class="eventInformation">
		<h3><?php esc_html_e( 'Prices', 'eduadmin-booking' ); ?></h3>
		<?php
		foreach ( $edo as $price ) {
			echo esc_html( sprintf( '%1$s: %2$s', $price['PriceNameDescription'], convert_to_money( $price['Price'], $currency ) ) . ' ' . ( $inc_vat ? __( 'inc vat', 'eduadmin-booking' ) : __( 'ex vat', 'eduadmin-booking' ) ) );
			echo '<br />';
		}
		?>
		<hr/>
	</div>
	<?php
}

return ob_get_clean();
