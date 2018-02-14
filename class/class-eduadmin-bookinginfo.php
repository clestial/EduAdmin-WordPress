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

	/**
	 * EduAdminBookingInfo constructor.
	 *
	 * @param null $event_booking
	 * @param null $customer
	 * @param null $contact
	 */
	public function __construct( $event_booking = null, $customer = null, $contact = null ) {
		$this->EventBooking = $event_booking;
		$this->Customer     = $customer;
		$this->Contact      = $contact;
	}
}