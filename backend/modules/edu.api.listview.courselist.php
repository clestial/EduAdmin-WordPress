<?php
	date_default_timezone_set( 'UTC' );
	if ( ! function_exists( 'edu_api_listview_courselist' ) ) {
		function edu_api_listview_courselist( $request ) {
			header( "Content-type: application/json; charset=UTF-8" );
			global $eduapi;
			global $phrases;

			$edutoken = edu_decrypt( "edu_js_token_crypto", $request["token"] );
			$phrases  = $request['phrases'];

			$objectIds = $request['objectIds'];

			$fetchMonths = $request['fetchmonths'];
			if ( ! is_numeric( $fetchMonths ) ) {
				$fetchMonths = 6;
			}

			$filtering = new XFiltering();
			$f         = new XFilter( 'ShowOnWeb', '=', 'true' );
			$filtering->AddItem( $f );

			$f = new XFilter( 'PeriodStart', '<=', date( "Y-m-d 23:59:59", strtotime( "now +" . $fetchMonths . " months" ) ) );
			$filtering->AddItem( $f );
			$f = new XFilter( 'PeriodEnd', '>=', date( "Y-m-d", strtotime( "now" ) ) );
			$filtering->AddItem( $f );
			$f = new XFilter( 'StatusID', '=', '1' );
			$filtering->AddItem( $f );

			$f = new XFilter( 'LastApplicationDate', '>=', date( "Y-m-d H:i:s" ) );
			$filtering->AddItem( $f );

			if ( ! empty( $objectIds ) ) {
				$f = new XFilter( 'ObjectID', 'IN', join( ',', $objectIds ) );
				$filtering->AddItem( $f );
			}

			$f = new XFilter( 'CustomerID', '=', '0' );
			$filtering->AddItem( $f );

			$sorting = new XSorting();
			$s       = new XSort( 'PeriodStart', 'ASC' );
			$sorting->AddItem( $s );

			$ede = $eduapi->GetEvent( $edutoken, $sorting->ToString(), $filtering->ToString() );

			$occIds = array();
			$evIds  = array();

			foreach ( $ede as $e ) {
				$occIds[] = $e->OccationID;
				$evIds[]  = $e->EventID;
			}

			$ft = new XFiltering();
			$f  = new XFilter( 'EventID', 'IN', join( ",", $evIds ) );
			$ft->AddItem( $f );

			$eventDays = $eduapi->GetEventDate( $edutoken, '', $ft->ToString() );

			$eventDates = array();
			foreach ( $eventDays as $ed ) {
				$eventDates[ $ed->EventID ][] = $ed;
			}

			$ft = new XFiltering();
			$f  = new XFilter( 'PublicPriceName', '=', 'true' );
			$ft->AddItem( $f );
			$f = new XFilter( 'OccationID', 'IN', join( ",", $occIds ) );
			$ft->AddItem( $f );
			$pricenames = $eduapi->GetPriceName( $edutoken, '', $ft->ToString() );

			if ( ! empty( $pricenames ) ) {
				$ede = array_filter( $ede, function( $object ) use ( &$pricenames ) {
					$pn = $pricenames;
					foreach ( $pn as $subj ) {
						if ( $object->OccationID == $subj->OccationID ) {
							return true;
						}
					}

					return false;
				} );
			}

			$returnValue = array();
			foreach ( $ede as $event ) {
				if ( ! isset( $returnValue[ $event->ObjectID ] ) ) {
					$returnValue[ $event->ObjectID ] = sprintf( edu__( 'Next event %1$s' ), date( "Y-m-d", strtotime( $event->PeriodStart ) ) ) . " " . $event->City;
				}
			}

			return json_encode( $returnValue );
		}
	}

	if ( isset( $_REQUEST['module'] ) && $_REQUEST['module'] == "listview_courselist" ) {
		echo edu_api_listview_courselist( $_REQUEST );
	}