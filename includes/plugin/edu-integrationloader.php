<?php

class EDU_IntegrationLoader {
	public $integrations = array();

	public function __construct() {
		$t = EDU()->StartTimer( __METHOD__ );
		do_action( 'edu_integrations_init' );

		$integrationList = apply_filters( 'edu_integrations', array() );

		foreach ( $integrationList as $int ) {
			$load_int                            = new $int();
			$this->integrations[ $load_int->id ] = $load_int;
		}
		EDU()->StopTimer( $t );
	}

	public function get_integrations() {
		return $this->integrations;
	}
}