<?php

class EduAdminBookingHandler {
	/**
	 * @var EduAdmin
	 */
	private $edu = null;

	public function __construct( $_edu ) {
		$this->edu = $_edu;

		add_action( 'wp_loaded', array( $this, 'process_booking' ) );
	}

	public function process_booking() {
		if ( isset( $_POST['act'] ) && 'bookCourse' === $_POST['act'] ) {
			// TODO: Add code to handle the booking here
		}
	}
}