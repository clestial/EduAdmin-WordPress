<?php

	class EDU_IntegrationLoader {
		public $integrations = array();

		public function __construct() {
			EDU()->timers[ __METHOD__ ] = microtime( true );
			do_action( 'edu_integrations_init' );

			$integrationList = apply_filters( 'edu_integrations', array() );

			foreach ( $integrationList as $int ) {
				$load_int                            = new $int();
				$this->integrations[ $load_int->id ] = $load_int;
			}
			EDU()->timers[ __METHOD__ ] = microtime( true ) - EDU()->timers[ __METHOD__ ];
		}

		public function get_integrations() {
			return $this->integrations;
		}
	}