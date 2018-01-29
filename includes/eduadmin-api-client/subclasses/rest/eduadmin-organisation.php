<?php

	/**
	 * Class EduAdmin_REST_Organisation
	 */
	class EduAdmin_REST_Organisation extends EduAdminRESTClient {
		protected $api_url = "/v1/Organisation";

		/**
		 * @return mixed
		 */
		public function GetOrganisation() {
			return parent::GET( "", array(), get_called_class() . "|" . __FUNCTION__ );
		}
	}