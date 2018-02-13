<?php
	$apiKey = get_option( 'eduadmin-api-key' );
	ob_start();

	if ( ! $apiKey || empty( $apiKey ) ) {
		return 'Please complete the configuration: <a href="' . admin_url() . 'admin.php?page=eduadmin-settings">EduAdmin - Api Authentication</a>';
	} else {
		$sorting       = array();
		$customOrder   = null;
		$customOrderBy = null;
		if ( ! empty( $attributes['order'] ) ) {
			$customOrder = $attributes['order'];
		}

		if ( ! empty( $attributes['orderby'] ) ) {
			$customOrderBy = $attributes['orderby'];
		}

		if ( $customOrderBy != null ) {
			$orderby   = explode( ' ', $customOrderBy );
			$sortorder = explode( ' ', $customOrderByOrder );
			foreach ( $orderby as $od => $v ) {
				if ( isset( $sortorder[ $od ] ) ) {
					$or = $sortorder[ $od ];
				} else {
					$or = "asc";
				}

				$sorting[] = $v . ' ' . strtolower( $or );
			}
		} else {
			$sorting[] = 'PriceNameId asc';
		}

		$edo = get_transient( 'eduadmin-objectpublicpricename_' . $courseId );
		if ( ! $edo ) {
			$edo = EDUAPI()->OData->CourseTemplates->GetItem(
				$courseId,
				"CourseTemplateId",
				'PriceNames($filter=PublicPriceName;$orderby=' . join( ',', $sorting ) . ')'
			)["PriceNames"];
			set_transient( 'eduadmin-objectpublicpricename_' . $courseId, $edo, 10 );
		}

		if ( ! empty( $attributes['numberofprices'] ) ) {
			$edo = array_slice( $edo, 0, $attributes['numberofprices'], true );
		}

		$currency = get_option( 'eduadmin-currency', 'SEK' );
		$incVat   = EDUAPI()->REST->Organisation->GetOrganisation()["PriceIncVat"];
		?>
        <div class="eventInformation">
            <h3><?php _e( "Prices", 'eduadmin-booking' ); ?></h3>
			<?php
				foreach ( $edo as $price ) {
					echo sprintf( '%1$s: %2$s', $price["PriceNameDescription"], convertToMoney( $price["Price"], $currency ) ) . " " . ( $incVat ? __( "inc vat", 'eduadmin-booking' ) : __( "ex vat", 'eduadmin-booking' ) );
					echo "<br>";
				}
			?>
            <hr/>
        </div>
		<?php
	}

	return ob_get_clean();