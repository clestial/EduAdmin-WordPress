<?php
	global $eduapi;
	global $edutoken;
	$apiKey = get_option( 'eduadmin-api-key' );
    ob_start();

	if ( ! $apiKey || empty( $apiKey ) ) {
		return 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
	} else {
		$filtering  = new XFiltering();
		$f          = new XFilter( 'ObjectID', '=', $courseId );
		$filtering->AddItem( $f );

		$f          = new XFilter( 'PublicPriceName', '=', "True" );
		$filtering->AddItem( $f );

		$sorting        = new XSorting();
		$customOrder    = null;
		$customOrderBy  = null;
		if( ! empty( $attributes['order'] ) ) {
			$customOrder = $attributes['order'];
		}

		if( ! empty( $attributes['orderby'] ) ) {
			$customOrderBy = $attributes['orderby'];
		}

		if( $customOrderBy != null ) {
			$orderby   = explode( ' ', $customOrderBy );
			$sortorder = explode( ' ', $customOrder );
			foreach ( $orderby as $od => $v ) {
				if ( isset( $sortorder[ $od ] ) ) {
					$or = $sortorder[ $od ];
				} else {
					$or = "ASC";
				}

				$s = new XSort( $v, $or );
				$sorting->AddItem( $s );
			}
		}
		else {
			$s = new XSort( 'PriceNameID', $customOrder != null ? $customOrder : 'ASC' );
			$sorting->AddItem( $s );
		}

		$edo = get_transient( 'eduadmin-objectpublicpricename_' . $courseId );
		if( ! $edo ) {
			$edo = $eduapi->GetObjectPriceName($edutoken, $sorting->ToString(), $filtering->ToString() );
			set_transient( 'eduadmin-objectpublicpricename_' . $courseId, $edo, 10 );
		}

		if( ! empty( $attributes['numberofprices'] ) ) {
			$edo = array_slice( $edo, 0, $attributes['numberofprices'], true );
		}

		$currency   = get_option( 'eduadmin-currency', 'SEK' );
		$incVat     = $eduapi->GetAccountSetting( $edutoken, 'PriceIncVat' ) == "yes";
		?>
		<div class="eventInformation">
			<h3>Priser</h3>
			<?php
			foreach ( $edo as $price ) {
				echo sprintf( '%1$s: %2$s', $price->Description, convertToMoney( $price->Price, $currency ) ) . " " . edu__( $incVat ? "inc vat" : "ex vat" );
				echo "<br>";
			}
			?>
			<hr/>
		</div>
		<?php
	}
    return ob_get_clean();