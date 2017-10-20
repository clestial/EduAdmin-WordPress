<?php

	class EduAdminBookingInfo {
		/**
		 * @var EventBookingV2
		 */
		public $EventBooking;
		/**
		 * @var CustomerV3
		 */
		public $Customer;
		/**
		 * @var CustomerContactV2
		 */
		public $Contact;
		/**
		 * @var bool
		 */
		public $NoRedirect = false;

		public function __construct( $eventBooking = null, $customer = null, $contact = null ) {
			$this->EventBooking = $eventBooking;
			$this->Customer     = $customer;
			$this->Contact      = $contact;
		}
	}