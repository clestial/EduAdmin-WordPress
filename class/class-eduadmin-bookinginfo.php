<?php

class EduAdminBookingInfo {
	/**
	 * @var EventBookingV2
	 */
	public $EventBooking;

	/**
	 * @var Customer
	 */
	public $Customer;

	/**
	 * @var CustomerContact
	 */
	public $Contact;

	public function __construct( $eventBooking = null, $customer = null, $contact = null ) {
		$this->EventBooking = $eventBooking;
		$this->Customer     = $customer;
		$this->Contact      = $contact;
	}
}