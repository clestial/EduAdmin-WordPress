<?php

	/**
	 * Class EduAdmin_REST_InterestRegistration
	 */
	class EduAdmin_REST_InterestRegistration extends EduAdminRESTClient {
		protected $api_url = "/v1/InterestRegistration";

		/**
		 * @param EduAdmin_Data_InterestRegistrationBasic $basic
		 *
		 * @return mixed
		 */
		public function CreateBasic( EduAdmin_Data_InterestRegistrationBasic $basic ) {
			return parent::POST( "CreateBasic",
			                     $basic,
			                     get_called_class() . "|" . __FUNCTION__
			);
		}

		/**
		 * @param EduAdmin_Data_InterestRegistrationComplete $complete
		 *
		 * @return mixed
		 */
		public function CreateComplete( EduAdmin_Data_InterestRegistrationComplete $complete ) {
			return parent::POST( "CreateComplete",
			                     $complete,
			                     get_called_class() . "|" . __FUNCTION__
			);
		}
	}