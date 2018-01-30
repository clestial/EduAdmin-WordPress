<?php
	const EduAdminClient_ServiceUrl = 'https://api.legaonline.se/api.asmx?WSDL'; // WSDL

	/**
	 * EduAdminClient class, generated from 'https://api.legaonline.se/api.asmx?WSDL'
	 */
	class EduAdminClient {
		/**
		 * @var bool
		 */
		public $debugTimers = false;
		/**
		 * @var bool
		 */
		public $debug = false;
		/**
		 * @var array
		 */
		public $timers;
		/**
		 * @var SoapClient
		 */
		protected $__server;

		/**
		 * EduAdminClient constructor
		 *
		 * @param $version
		 */
		public function __construct( $version ) {
			$this->timers   = array();
			$t              = $this->StartTimer( 'InitSoapClient' );
			$this->__server = new SoapClient(
				EduAdminClient_ServiceUrl,
				array(
					'trace'      => 0,
					'cache_wsdl' => WSDL_CACHE_BOTH,
					'user_agent' => 'EduAdmin WordPress Plugin (' . $version . ')',
				)
			);
			$this->StopTimer( $t );
		}

		/**
		 * @param string $name Name of the timer
		 *
		 * @return string Returns the unique name for the created timer
		 */
		public function StartTimer( $name ) {
			$timer_id                                = count( $this->timers ) + 1;
			$this->timers[ $name . "_" . $timer_id ] = microtime( true );

			return $name . "_" . $timer_id;
		}

		/**
		 * @param string $name The unique name of the timer (Returned from StartTimer)
		 */
		public function StopTimer( $name ) {
			$this->timers[ $name ] = microtime( true ) - $this->timers[ $name ];
		}

		/**
		 * @param string $authToken
		 * @param int    $eventID
		 * @param int    $customerID
		 * @param int    $customerContactID
		 * @param int[]  $personIDs
		 *
		 * @return int
		 */
		public function Book( $authToken, $eventID, $customerID, $customerContactID, array $personIDs ) {
			$param = array(
				'authToken'         => $authToken,
				'eventID'           => $eventID,
				'customerID'        => $customerID,
				'customerContactID' => $customerContactID,
				'personIDs'         => $personIDs,
			);

			return $this->__callServer( $param, 'Book' );
		}

		/**
		 * @param array  $params     All variables to send to the webservice
		 * @param string $methodName The method to call on the webservice
		 *
		 * @return mixed|null
		 */
		private function __callServer( $params, $methodName ) {
			$t      = $this->StartTimer( $methodName . '__callServer' );
			$result = null;
			try {
				$result = $this->__server->__soapCall(
					$methodName,
					array( $params )
				);
			}
			catch ( SoapFault $fault ) {
				if ( $this->debug ) {
					echo '<pre>' . print_r( $fault, true ) . '</pre>';
				}
			}
			if ( $this->debug ) {
				$this->__debug();
			}
			$this->StopTimer( $t );
			if ( isset( $result->{$methodName . 'Result'} ) ) {
				return $result->{$methodName . 'Result'};
			}

			return null;
		}

		private function __debug() {
			$requestHeaders  = $this->__server->__getLastRequestHeaders();
			$request         = $this->__server->__getLastRequest();
			$responseHeaders = $this->__server->__getLastResponseHeaders();
			$response        = $this->__server->__getLastResponse();

			if ( ! empty( $requestHeaders ) ) {
				echo '<code>' . nl2br( htmlspecialchars( $requestHeaders, true ) ) . '</code>' . '<br/>';
			}
			if ( ! empty( $request ) ) {
				echo highlight_string( $request, true ) . '<br/>';
			}

			if ( ! empty( $responseHeaders ) ) {
				echo '<code>' . nl2br( htmlspecialchars( $responseHeaders, true ) ) . '</code>' . '<br/>';
			}
			if ( ! empty( $response ) ) {
				echo highlight_string( $response, true ) . '<br/>';
			}
		}

		/**
		 * @param string $authToken
		 * @param int    $eventID
		 * @param int    $customerID
		 * @param int    $customerContactID
		 * @param string $customerReference
		 * @param int[]  $personIDs
		 *
		 * @return int
		 */
		public function BookIncCustomerReference( $authToken, $eventID, $customerID, $customerContactID, $customerReference, array $personIDs ) {
			$param = array(
				'authToken'         => $authToken,
				'eventID'           => $eventID,
				'customerID'        => $customerID,
				'customerContactID' => $customerContactID,
				'customerReference' => $customerReference,
				'personIDs'         => $personIDs,
			);

			return $this->__callServer( $param, 'BookIncCustomerReference' );
		}

		/**
		 * @param string $authToken
		 * @param int    $eventID
		 * @param int    $customerID
		 * @param int    $customerContactID
		 * @param int    $paymentMethodID
		 * @param int[]  $personIDs
		 *
		 * @return int
		 */
		public function BookIncPaymentMethod( $authToken, $eventID, $customerID, $customerContactID, $paymentMethodID, array $personIDs ) {
			$param = array(
				'authToken'         => $authToken,
				'eventID'           => $eventID,
				'customerID'        => $customerID,
				'customerContactID' => $customerContactID,
				'paymentMethodID'   => $paymentMethodID,
				'personIDs'         => $personIDs,
			);

			return $this->__callServer( $param, 'BookIncPaymentMethod' );
		}

		/**
		 * @param string $authToken
		 * @param int    $eventID
		 * @param int    $customerID
		 * @param int    $customerContactID
		 * @param string $customerReference
		 * @param int    $paymentMethodID
		 * @param int    $occasionPriceNameLnkID
		 * @param int[]  $personIDs
		 *
		 * @return int
		 */
		public function BookIncPriceName( $authToken, $eventID, $customerID, $customerContactID, $customerReference, $paymentMethodID, $occasionPriceNameLnkID, array $personIDs ) {
			$param = array(
				'authToken'              => $authToken,
				'eventID'                => $eventID,
				'customerID'             => $customerID,
				'customerContactID'      => $customerContactID,
				'customerReference'      => $customerReference,
				'paymentMethodID'        => $paymentMethodID,
				'occasionPriceNameLnkID' => $occasionPriceNameLnkID,
				'personIDs'              => $personIDs,
			);

			return $this->__callServer( $param, 'BookIncPriceName' );
		}

		/**
		 * @param string     $authToken
		 * @param int        $eventID
		 * @param int        $customerID
		 * @param int        $customerContactID
		 * @param int        $paymentMethodID
		 * @param stdClass[] $priceName
		 *
		 * @return int
		 */
		public function BookPriceName( $authToken, $eventID, $customerID, $customerContactID, $paymentMethodID, array $priceName ) {
			$param = array(
				'authToken'         => $authToken,
				'eventID'           => $eventID,
				'customerID'        => $customerID,
				'customerContactID' => $customerContactID,
				'paymentMethodID'   => $paymentMethodID,
				'priceName'         => $priceName,
			);

			return $this->__callServer( $param, 'BookPriceName' );
		}

		/**
		 * @param string     $authToken
		 * @param int        $eclID
		 * @param stdClass[] $salesObjectBookingInfo
		 *
		 * @return void
		 */
		public function BookSalesObject( $authToken, $eclID, array $salesObjectBookingInfo ) {
			$param = array(
				'authToken'              => $authToken,
				'eclID'                  => $eclID,
				'salesObjectBookingInfo' => $salesObjectBookingInfo,
			);

			$this->__callServer( $param, 'BookSalesObject' );
		}

		/**
		 * @param string $authToken
		 * @param int    $eclID
		 * @param string $salesObjectBookingInfo
		 *
		 * @return void
		 */
		public function BookSalesObjectXml( $authToken, $eclID, $salesObjectBookingInfo ) {
			$param = array(
				'authToken'              => $authToken,
				'eclID'                  => $eclID,
				'salesObjectBookingInfo' => $salesObjectBookingInfo,
			);

			$this->__callServer( $param, 'BookSalesObjectXml' );
		}

		/**
		 * @param string $authToken
		 * @param int    $objectID
		 * @param int    $categoryID
		 * @param string $code
		 *
		 * @return Coupon
		 */
		public function CheckCouponCode( $authToken, $objectID, $categoryID, $code ) {
			$param = array(
				'authToken'  => $authToken,
				'objectID'   => $objectID,
				'categoryID' => $categoryID,
				'code'       => $code,
			);

			return $this->__callServer( $param, 'CheckCouponCode' );
		}

		/**
		 * @param string   $authToken
		 * @param stdClass $bookingInfo
		 *
		 * @return int
		 */
		public function CreateBooking( $authToken, $bookingInfo ) {
			$param = array(
				'authToken'   => $authToken,
				'bookingInfo' => $bookingInfo,
			);

			return $this->__callServer( $param, 'CreateBooking' );
		}

		/**
		 * @param string   $authToken
		 * @param stdClass $bookingInfo
		 *
		 * @return int
		 */
		public function CreateBookingPriceName( $authToken, $bookingInfo ) {
			$param = array(
				'authToken'   => $authToken,
				'bookingInfo' => $bookingInfo,
			);

			return $this->__callServer( $param, 'CreateBookingPriceName' );
		}

		/**
		 * @param string $authToken
		 * @param string $bookingInfo
		 *
		 * @return int
		 */
		public function CreateBookingXml( $authToken, $bookingInfo ) {
			$param = array(
				'authToken'   => $authToken,
				'bookingInfo' => $bookingInfo,
			);

			return $this->__callServer( $param, 'CreateBookingXml' );
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $namedParticipants
		 *
		 * @return int[]
		 */
		public function CreateParticipantFromUnnamed( $authToken, array $namedParticipants ) {
			$param = array(
				'authToken'         => $authToken,
				'namedParticipants' => $namedParticipants,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'CreateParticipantFromUnnamed' ) )->int;
		}

		/**
		 * Returns an array from the webservice
		 *
		 * @param string   $objName Name of the parameter we're reading
		 * @param stdClass $res     The object we're reading data from
		 *
		 * @return mixed
		 */
		private function __getArray( $objName, $res ) {
			$t = $this->StartTimer( $objName . '__getArray' );
			if ( ! empty( $res->{$objName} ) ) {
				if ( is_array( $res->{$objName} ) ) {
					$this->StopTimer( $t );

					return $res;
				} else {
					$nRes                                    = new stdClass;
					$nRes->{$objName}                        = array();
					$nRes->{$objName}[]                      = $res->{$objName};
					$this->StopTimer( $t );

					return $nRes;
				}
			} else {
				if ( ! empty( $res->{"ArrayOf" . $objName} ) ) {
					if ( is_array( $res->{"ArrayOf" . $objName} ) ) {
						$this->StopTimer( $t );
						if ( $this->debugTimers ) {
							echo "<!-- " . $objName . '__getArray' . ": " . round( $this->timers[ $objName . '__getArray' ] * 1000, 2 ) . "ms -->\n";
						}
						if ( isset( $res->{"ArrayOf" . $objName}[0]->{$objName} ) ) {
							$arRes             = new stdClass;
							$arRes->{$objName} = array();
							foreach ( $res->{"ArrayOf" . $objName} as $item ) {
								$arRes->{$objName}[] = $item->{$objName};
							}

							return $arRes;
						}

						return $res;
					} else {
						$nRes                                    = new stdClass;
						$nRes->{$objName}                        = $res->{"ArrayOf" . $objName}->{$objName};
						$this->StopTimer( $t );
						if ( $this->debugTimers ) {
							echo "<!-- " . $objName . '__getArray' . ": " . round( $this->timers[ $objName . '__getArray' ] * 1000, 2 ) . "ms -->\n";
						}

						return $nRes;
					}
				}
				$nRes                                    = new stdClass;
				$nRes->{$objName}                        = array();
				$this->StopTimer( $t );

				return $nRes;
			}
		}

		/**
		 * @param string   $authToken
		 * @param stdClass $bookingInfo
		 *
		 * @return int
		 */
		public function CreateSeatBooking( $authToken, $bookingInfo ) {
			$param = array(
				'authToken'   => $authToken,
				'bookingInfo' => $bookingInfo,
			);

			return $this->__callServer( $param, 'CreateSeatBooking' );
		}

		/**
		 * @param string   $authToken
		 * @param stdClass $bookingInfo
		 *
		 * @return int
		 */
		public function CreateSubEventBooking( $authToken, $bookingInfo ) {
			$param = array(
				'authToken'   => $authToken,
				'bookingInfo' => $bookingInfo,
			);

			return $this->__callServer( $param, 'CreateSubEventBooking' );
		}

		/**
		 * @param string $authToken
		 * @param string $bookingInfoSubEvent
		 *
		 * @return int
		 */
		public function CreateSubEventBookingXml( $authToken, $bookingInfoSubEvent ) {
			$param = array(
				'authToken'           => $authToken,
				'bookingInfoSubEvent' => $bookingInfoSubEvent,
			);

			return $this->__callServer( $param, 'CreateSubEventBookingXml' );
		}

		/**
		 * @param string $authToken
		 * @param int[]  $customerContactIDs
		 *
		 * @return void
		 */
		public function DeleteCustomerContact( $authToken, array $customerContactIDs ) {
			$param = array(
				'authToken'          => $authToken,
				'customerContactIDs' => $customerContactIDs,
			);

			$this->__callServer( $param, 'DeleteCustomerContact' );
		}

		/**
		 * @param string $authToken
		 * @param int[]  $customerContactAttributeIDs
		 *
		 * @return void
		 */
		public function DeleteCustomerContactAttribute( $authToken, array $customerContactAttributeIDs ) {
			$param = array(
				'authToken'                   => $authToken,
				'customerContactAttributeIDs' => $customerContactAttributeIDs,
			);

			$this->__callServer( $param, 'DeleteCustomerContactAttribute' );
		}

		/**
		 * @param string $authToken
		 * @param int    $eventCustomerLnkID
		 *
		 * @return void
		 */
		public function DeleteEventBooking( $authToken, $eventCustomerLnkID ) {
			$param = array(
				'authToken'          => $authToken,
				'eventCustomerLnkID' => $eventCustomerLnkID,
			);

			$this->__callServer( $param, 'DeleteEventBooking' );
		}

		/**
		 * @param string $authToken
		 * @param int    $eventParticipantID
		 *
		 * @return void
		 */
		public function DeleteEventParticipant( $authToken, $eventParticipantID ) {
			$param = array(
				'authToken'          => $authToken,
				'eventParticipantID' => $eventParticipantID,
			);

			$this->__callServer( $param, 'DeleteEventParticipant' );
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $subEventList
		 *
		 * @return bool
		 */
		public function DeleteEventParticipantSubEvent( $authToken, array $subEventList ) {
			$param = array(
				'authToken'    => $authToken,
				'subEventList' => $subEventList,
			);

			return $this->__callServer( $param, 'DeleteEventParticipantSubEvent' );
		}

		/**
		 * @param string $authToken
		 * @param string $subEventList
		 *
		 * @return bool
		 */
		public function DeleteEventParticipantSubEventXml( $authToken, $subEventList ) {
			$param = array(
				'authToken'    => $authToken,
				'subEventList' => $subEventList,
			);

			return $this->__callServer( $param, 'DeleteEventParticipantSubEventXml' );
		}

		/**
		 * @param string $authToken
		 * @param int[]  $personIDs
		 *
		 * @return void
		 */
		public function DeletePerson( $authToken, array $personIDs ) {
			$param = array(
				'authToken' => $authToken,
				'personIDs' => $personIDs,
			);

			$this->__callServer( $param, 'DeletePerson' );
		}

		/**
		 * @param string $authToken
		 * @param int    $unavailableDateID
		 *
		 * @return void
		 */
		public function DeleteUnavailablePersonnelDate( $authToken, $unavailableDateID ) {
			$param = array(
				'authToken'         => $authToken,
				'unavailableDateID' => $unavailableDateID,
			);

			$this->__callServer( $param, 'DeleteUnavailablePersonnelDate' );
		}

		/**
		 * @param string $authToken
		 *
		 * @return AccountInfo[]
		 */
		public function GetAccountInfo( $authToken ) {
			$param = array(
				'authToken' => $authToken,
			);

			return $this->__getArray( 'AccountInfo', $this->__callServer( $param, 'GetAccountInfo' ) )->AccountInfo;
		}

		/**
		 * @param string $authToken
		 *
		 * @return string
		 */
		public function GetAccountInfoXml( $authToken ) {
			$param = array(
				'authToken' => $authToken,
			);

			return $this->__callServer( $param, 'GetAccountInfoXml' );
		}

		/**
		 * @param string   $authToken
		 * @param stdClass $setting
		 *
		 * @return string
		 */
		public function GetAccountSetting( $authToken, $setting ) {
			$param = array(
				'authToken' => $authToken,
				'setting'   => $setting,
			);

			return $this->__callServer( $param, 'GetAccountSetting' );
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $settings
		 *
		 * @return AccountSettingsInfo[]
		 */
		public function GetAccountSettings( $authToken, array $settings ) {
			$param = array(
				'authToken' => $authToken,
				'settings'  => $settings,
			);

			return $this->__getArray( 'AccountSettingsInfo', $this->__callServer( $param, 'GetAccountSettings' ) )->AccountSettingsInfo;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return Attribute[]
		 */
		public function GetAttribute( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'Attribute', $this->__callServer( $param, 'GetAttribute' ) )->Attribute;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetAttributeXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetAttributeXml' );
		}

		/**
		 * @param int    $userID
		 * @param string $hash
		 *
		 * @return string
		 */
		public function GetAuthToken( $userID, $hash ) {
			$param = array(
				'userID' => $userID,
				'hash'   => $hash,
			);

			return $this->__callServer( $param, 'GetAuthToken' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return BookedEventAccessory[]
		 */
		public function GetBookedEventAccessory( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'BookedEventAccessory', $this->__callServer( $param, 'GetBookedEventAccessory' ) )->BookedEventAccessory;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return Category[]
		 */
		public function GetCategory( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'Category', $this->__callServer( $param, 'GetCategory' ) )->Category;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return Category[]
		 */
		public function GetCategorySpecial( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'Category', $this->__callServer( $param, 'GetCategorySpecial' ) )->Category;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetCategorySpecialXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetCategorySpecialXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return CategoryV3[]
		 */
		public function GetCategoryV3( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'CategoryV3', $this->__callServer( $param, 'GetCategoryV3' ) )->CategoryV3;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetCategoryXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetCategoryXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return Certificate[]
		 */
		public function GetCertificate( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'Certificate', $this->__callServer( $param, 'GetCertificate' ) )->Certificate;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return CertificatePerson[]
		 */
		public function GetCertificatePerson( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'CertificatePerson', $this->__callServer( $param, 'GetCertificatePerson' ) )->CertificatePerson;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeSubjects
		 *
		 * @return CertificatePersonV2[]
		 */
		public function GetCertificatePersonV2( $authToken, $sort, $filter, $includeSubjects ) {
			$param = array(
				'authToken'       => $authToken,
				'sort'            => $sort,
				'filter'          => $filter,
				'includeSubjects' => $includeSubjects,
			);

			return $this->__getArray( 'CertificatePersonV2', $this->__callServer( $param, 'GetCertificatePersonV2' ) )->CertificatePersonV2;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeSubjects
		 *
		 * @return string
		 */
		public function GetCertificatePersonV2Xml( $authToken, $sort, $filter, $includeSubjects ) {
			$param = array(
				'authToken'       => $authToken,
				'sort'            => $sort,
				'filter'          => $filter,
				'includeSubjects' => $includeSubjects,
			);

			return $this->__callServer( $param, 'GetCertificatePersonV2Xml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetCertificatePersonXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetCertificatePersonXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetCertificateXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetCertificateXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return CompanyAttribute[]
		 */
		public function GetCompanyAttribute( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'CompanyAttribute', $this->__callServer( $param, 'GetCompanyAttribute' ) )->CompanyAttribute;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetCompanyAttributeXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetCompanyAttributeXml' );
		}

		/**
		 * @param string $authToken
		 *
		 * @return string
		 */
		public function GetCompanyLogoUrl( $authToken ) {
			$param = array(
				'authToken' => $authToken,
			);

			return $this->__callServer( $param, 'GetCompanyLogoUrl' );
		}

		/**
		 * @param string   $authToken
		 * @param int      $eclID
		 * @param int|null $documentID
		 *
		 * @return ConfirmationEmailMessage
		 */
		public function GetConfirmationEmailMessage( $authToken, $eclID, $documentID ) {
			$param = array(
				'authToken'  => $authToken,
				'eclID'      => $eclID,
				'documentID' => $documentID,
			);

			return $this->__callServer( $param, 'GetConfirmationEmailMessage' );
		}

		/**
		 * @param string   $authToken
		 * @param int      $eclID
		 * @param int|null $documentID
		 *
		 * @return string
		 */
		public function GetConfirmationEmailMessageXml( $authToken, $eclID, $documentID ) {
			$param = array(
				'authToken'  => $authToken,
				'eclID'      => $eclID,
				'documentID' => $documentID,
			);

			return $this->__callServer( $param, 'GetConfirmationEmailMessageXml' );
		}

		/**
		 * @param string   $authToken
		 * @param stdClass $language
		 *
		 * @return Country[]
		 */
		public function GetCountries( $authToken, $language ) {
			$param = array(
				'authToken' => $authToken,
				'language'  => $language,
			);

			return $this->__getArray( 'Country', $this->__callServer( $param, 'GetCountries' ) )->Country;
		}

		/**
		 * @param string   $authToken
		 * @param stdClass $language
		 *
		 * @return string
		 */
		public function GetCountriesXml( $authToken, $language ) {
			$param = array(
				'authToken' => $authToken,
				'language'  => $language,
			);

			return $this->__callServer( $param, 'GetCountriesXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeAttributes
		 *
		 * @return Customer[]
		 */
		public function GetCustomer( $authToken, $sort, $filter, $includeAttributes ) {
			$param = array(
				'authToken'         => $authToken,
				'sort'              => $sort,
				'filter'            => $filter,
				'includeAttributes' => $includeAttributes,
			);

			return $this->__getArray( 'Customer', $this->__callServer( $param, 'GetCustomer' ) )->Customer;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return CustomerAttribute[]
		 */
		public function GetCustomerAttribute( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'CustomerAttribute', $this->__callServer( $param, 'GetCustomerAttribute' ) )->CustomerAttribute;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetCustomerAttributeXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetCustomerAttributeXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeAttributes
		 *
		 * @return CustomerContact[]
		 */
		public function GetCustomerContact( $authToken, $sort, $filter, $includeAttributes ) {
			$param = array(
				'authToken'         => $authToken,
				'sort'              => $sort,
				'filter'            => $filter,
				'includeAttributes' => $includeAttributes,
			);

			return $this->__getArray( 'CustomerContact', $this->__callServer( $param, 'GetCustomerContact' ) )->CustomerContact;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return CustomerContactAttribute[]
		 */
		public function GetCustomerContactAttribute( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'CustomerContactAttribute', $this->__callServer( $param, 'GetCustomerContactAttribute' ) )->CustomerContactAttribute;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetCustomerContactAttributeXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetCustomerContactAttributeXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeAttributes
		 *
		 * @return CustomerContactV2[]
		 */
		public function GetCustomerContactV2( $authToken, $sort, $filter, $includeAttributes ) {
			$param = array(
				'authToken'         => $authToken,
				'sort'              => $sort,
				'filter'            => $filter,
				'includeAttributes' => $includeAttributes,
			);

			return $this->__getArray( 'CustomerContactV2', $this->__callServer( $param, 'GetCustomerContactV2' ) )->CustomerContactV2;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeAttributes
		 *
		 * @return string
		 */
		public function GetCustomerContactV2Xml( $authToken, $sort, $filter, $includeAttributes ) {
			$param = array(
				'authToken'         => $authToken,
				'sort'              => $sort,
				'filter'            => $filter,
				'includeAttributes' => $includeAttributes,
			);

			return $this->__callServer( $param, 'GetCustomerContactV2Xml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeAttributes
		 *
		 * @return string
		 */
		public function GetCustomerContactXml( $authToken, $sort, $filter, $includeAttributes ) {
			$param = array(
				'authToken'         => $authToken,
				'sort'              => $sort,
				'filter'            => $filter,
				'includeAttributes' => $includeAttributes,
			);

			return $this->__callServer( $param, 'GetCustomerContactXml' );
		}

		/**
		 * @param string $authToken
		 * @param int    $customerID
		 * @param int    $eventID
		 *
		 * @return CustomerEventPrice
		 */
		public function GetCustomerEventPrice( $authToken, $customerID, $eventID ) {
			$param = array(
				'authToken'  => $authToken,
				'customerID' => $customerID,
				'eventID'    => $eventID,
			);

			return $this->__callServer( $param, 'GetCustomerEventPrice' );
		}

		/**
		 * @param string $authToken
		 * @param int    $customerID
		 * @param int    $eventID
		 *
		 * @return string
		 */
		public function GetCustomerEventPriceXml( $authToken, $customerID, $eventID ) {
			$param = array(
				'authToken'  => $authToken,
				'customerID' => $customerID,
				'eventID'    => $eventID,
			);

			return $this->__callServer( $param, 'GetCustomerEventPriceXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return ExtraInfo[][]
		 */
		public function GetCustomerExtraInfo( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'ExtraInfo', $this->__callServer( $param, 'GetCustomerExtraInfo' ) )->ExtraInfo;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return CustomerGroup[]
		 */
		public function GetCustomerGroup( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'CustomerGroup', $this->__callServer( $param, 'GetCustomerGroup' ) )->CustomerGroup;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetCustomerGroupXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetCustomerGroupXml' );
		}

		/**
		 * @param string $authToken
		 * @param int    $customerID
		 * @param int[]  $objectIds
		 *
		 * @return CustomerPrice[]
		 */
		public function GetCustomerObjectPrices( $authToken, $customerID, array $objectIds ) {
			$param = array(
				'authToken'  => $authToken,
				'customerID' => $customerID,
				'objectIds'  => $objectIds,
			);

			return $this->__getArray( 'CustomerPrice', $this->__callServer( $param, 'GetCustomerObjectPrices' ) )->CustomerPrice;
		}

		/**
		 * @param string $authToken
		 * @param int    $customerID
		 * @param int[]  $objectIds
		 *
		 * @return string
		 */
		public function GetCustomerObjectPricesXml( $authToken, $customerID, array $objectIds ) {
			$param = array(
				'authToken'  => $authToken,
				'customerID' => $customerID,
				'objectIds'  => $objectIds,
			);

			return $this->__callServer( $param, 'GetCustomerObjectPricesXml' );
		}

		/**
		 * @param string $authToken
		 * @param int    $customerID
		 * @param int    $objectID
		 *
		 * @return CustomerPrice
		 */
		public function GetCustomerPrice( $authToken, $customerID, $objectID ) {
			$param = array(
				'authToken'  => $authToken,
				'customerID' => $customerID,
				'objectID'   => $objectID,
			);

			return $this->__callServer( $param, 'GetCustomerPrice' );
		}

		/**
		 * @param string $authToken
		 * @param int    $customerID
		 * @param int    $objectID
		 *
		 * @return string
		 */
		public function GetCustomerPriceXml( $authToken, $customerID, $objectID ) {
			$param = array(
				'authToken'  => $authToken,
				'customerID' => $customerID,
				'objectID'   => $objectID,
			);

			return $this->__callServer( $param, 'GetCustomerPriceXml' );
		}

		/**
		 * @param string   $authToken
		 * @param stdClass $statisticsFilter
		 * @param int      $top
		 *
		 * @return CustomerStatistics[]
		 */
		public function GetCustomerStatistics( $authToken, $statisticsFilter, $top ) {
			$param = array(
				'authToken'        => $authToken,
				'statisticsFilter' => $statisticsFilter,
				'top'              => $top,
			);

			return $this->__getArray( 'CustomerStatistics', $this->__callServer( $param, 'GetCustomerStatistics' ) )->CustomerStatistics;
		}

		/**
		 * @param string $authToken
		 * @param string $statisticsFilter
		 * @param int    $top
		 *
		 * @return string
		 */
		public function GetCustomerStatisticsXml( $authToken, $statisticsFilter, $top ) {
			$param = array(
				'authToken'        => $authToken,
				'statisticsFilter' => $statisticsFilter,
				'top'              => $top,
			);

			return $this->__callServer( $param, 'GetCustomerStatisticsXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeAttributes
		 *
		 * @return CustomerV2[]
		 */
		public function GetCustomerV2( $authToken, $sort, $filter, $includeAttributes ) {
			$param = array(
				'authToken'         => $authToken,
				'sort'              => $sort,
				'filter'            => $filter,
				'includeAttributes' => $includeAttributes,
			);

			return $this->__getArray( 'CustomerV2', $this->__callServer( $param, 'GetCustomerV2' ) )->CustomerV2;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeAttributes
		 *
		 * @return string
		 */
		public function GetCustomerV2Xml( $authToken, $sort, $filter, $includeAttributes ) {
			$param = array(
				'authToken'         => $authToken,
				'sort'              => $sort,
				'filter'            => $filter,
				'includeAttributes' => $includeAttributes,
			);

			return $this->__callServer( $param, 'GetCustomerV2Xml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeAttributes
		 *
		 * @return CustomerV3[]
		 */
		public function GetCustomerV3( $authToken, $sort, $filter, $includeAttributes ) {
			$param = array(
				'authToken'         => $authToken,
				'sort'              => $sort,
				'filter'            => $filter,
				'includeAttributes' => $includeAttributes,
			);

			return $this->__getArray( 'CustomerV3', $this->__callServer( $param, 'GetCustomerV3' ) )->CustomerV3;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeAttributes
		 *
		 * @return string
		 */
		public function GetCustomerV3Xml( $authToken, $sort, $filter, $includeAttributes ) {
			$param = array(
				'authToken'         => $authToken,
				'sort'              => $sort,
				'filter'            => $filter,
				'includeAttributes' => $includeAttributes,
			);

			return $this->__callServer( $param, 'GetCustomerV3Xml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeAttributes
		 *
		 * @return string
		 */
		public function GetCustomerXml( $authToken, $sort, $filter, $includeAttributes ) {
			$param = array(
				'authToken'         => $authToken,
				'sort'              => $sort,
				'filter'            => $filter,
				'includeAttributes' => $includeAttributes,
			);

			return $this->__callServer( $param, 'GetCustomerXml' );
		}

		/**
		 * @param string $authToken
		 * @param int    $eventID
		 *
		 * @return int
		 */
		public function GetDefaultParticipantDocumentID( $authToken, $eventID ) {
			$param = array(
				'authToken' => $authToken,
				'eventID'   => $eventID,
			);

			return $this->__callServer( $param, 'GetDefaultParticipantDocumentID' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return DocumentSentListEvent[]
		 */
		public function GetDocumentSentList( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'DocumentSentListEvent', $this->__callServer( $param, 'GetDocumentSentList' ) )->DocumentSentListEvent;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetDocumentSentListXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetDocumentSentListXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EducationLevel[]
		 */
		public function GetEducationLevel( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EducationLevel', $this->__callServer( $param, 'GetEducationLevel' ) )->EducationLevel;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EducationLevelObject[]
		 */
		public function GetEducationLevelObject( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EducationLevelObject', $this->__callServer( $param, 'GetEducationLevelObject' ) )->EducationLevelObject;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEducationLevelObjectXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEducationLevelObjectXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEducationLevelXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEducationLevelXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EducationObject[]
		 */
		public function GetEducationObject( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EducationObject', $this->__callServer( $param, 'GetEducationObject' ) )->EducationObject;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeSubjects
		 *
		 * @return EducationObjectV2[]
		 */
		public function GetEducationObjectV2( $authToken, $sort, $filter, $includeSubjects ) {
			$param = array(
				'authToken'       => $authToken,
				'sort'            => $sort,
				'filter'          => $filter,
				'includeSubjects' => $includeSubjects,
			);

			return $this->__getArray( 'EducationObjectV2', $this->__callServer( $param, 'GetEducationObjectV2' ) )->EducationObjectV2;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeSubjects
		 *
		 * @return string
		 */
		public function GetEducationObjectV2Xml( $authToken, $sort, $filter, $includeSubjects ) {
			$param = array(
				'authToken'       => $authToken,
				'sort'            => $sort,
				'filter'          => $filter,
				'includeSubjects' => $includeSubjects,
			);

			return $this->__callServer( $param, 'GetEducationObjectV2Xml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEducationObjectXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEducationObjectXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EducationSubject[]
		 */
		public function GetEducationSubject( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EducationSubject', $this->__callServer( $param, 'GetEducationSubject' ) )->EducationSubject;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEducationSubjectXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEducationSubjectXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return Event[]
		 */
		public function GetEvent( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'Event', $this->__callServer( $param, 'GetEvent' ) )->Event;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventAccessory[]
		 */
		public function GetEventAccessory( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventAccessory', $this->__callServer( $param, 'GetEventAccessory' ) )->EventAccessory;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventAccessoryXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventAccessoryXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventBooking[]
		 */
		public function GetEventBooking( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventBooking', $this->__callServer( $param, 'GetEventBooking' ) )->EventBooking;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return ExtraInfo[][]
		 */
		public function GetEventBookingExtraInfo( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'ExtraInfo', $this->__callServer( $param, 'GetEventBookingExtraInfo' ) )->ExtraInfo;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventBookingPriceName[]
		 */
		public function GetEventBookingPriceName( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventBookingPriceName', $this->__callServer( $param, 'GetEventBookingPriceName' ) )->EventBookingPriceName;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventBookingPriceNameXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventBookingPriceNameXml' );
		}

		/**
		 * @param string $authToken
		 * @param int    $eventID
		 *
		 * @return EventBookingQuestion[]
		 */
		public function GetEventBookingQuestion( $authToken, $eventID ) {
			$param = array(
				'authToken' => $authToken,
				'eventID'   => $eventID,
			);

			return $this->__getArray( 'EventBookingQuestion', $this->__callServer( $param, 'GetEventBookingQuestion' ) )->EventBookingQuestion;
		}

		/**
		 * @param string $authToken
		 * @param int    $eventID
		 *
		 * @return string
		 */
		public function GetEventBookingQuestionXml( $authToken, $eventID ) {
			$param = array(
				'authToken' => $authToken,
				'eventID'   => $eventID,
			);

			return $this->__callServer( $param, 'GetEventBookingQuestionXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventBookingV2[]
		 */
		public function GetEventBookingV2( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventBookingV2', $this->__callServer( $param, 'GetEventBookingV2' ) )->EventBookingV2;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventBookingV2Xml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventBookingV2Xml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventBookingXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventBookingXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventCustomerAnswer[]
		 */
		public function GetEventCustomerAnswer( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventCustomerAnswer', $this->__callServer( $param, 'GetEventCustomerAnswer' ) )->EventCustomerAnswer;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventCustomerAnswerV2[]
		 */
		public function GetEventCustomerAnswerV2( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventCustomerAnswerV2', $this->__callServer( $param, 'GetEventCustomerAnswerV2' ) )->EventCustomerAnswerV2;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventCustomerAnswerV2Xml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventCustomerAnswerV2Xml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventCustomerAnswerXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventCustomerAnswerXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventDate[]
		 */
		public function GetEventDate( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventDate', $this->__callServer( $param, 'GetEventDate' ) )->EventDate;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventDateXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventDateXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return ExtraInfo[][]
		 */
		public function GetEventExtraInfo( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'ExtraInfo', $this->__callServer( $param, 'GetEventExtraInfo' ) )->ExtraInfo;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventParticipant[]
		 */
		public function GetEventParticipant( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventParticipant', $this->__callServer( $param, 'GetEventParticipant' ) )->EventParticipant;
		}

		/**
		 * @param string $authToken
		 * @param int[]  $eventParticipantIDs
		 *
		 * @return EventParticipantSubEvent[]
		 */
		public function GetEventParticipantSubEvent( $authToken, array $eventParticipantIDs ) {
			$param = array(
				'authToken'           => $authToken,
				'eventParticipantIDs' => $eventParticipantIDs,
			);

			return $this->__getArray( 'EventParticipantSubEvent', $this->__callServer( $param, 'GetEventParticipantSubEvent' ) )->EventParticipantSubEvent;
		}

		/**
		 * @param string $authToken
		 * @param int[]  $eventParticipantIDs
		 *
		 * @return string
		 */
		public function GetEventParticipantSubEventXml( $authToken, array $eventParticipantIDs ) {
			$param = array(
				'authToken'           => $authToken,
				'eventParticipantIDs' => $eventParticipantIDs,
			);

			return $this->__callServer( $param, 'GetEventParticipantSubEventXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventParticipantV2[]
		 */
		public function GetEventParticipantV2( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventParticipantV2', $this->__callServer( $param, 'GetEventParticipantV2' ) )->EventParticipantV2;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventParticipantV2Xml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventParticipantV2Xml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventParticipantXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventParticipantXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventPaymentMethod[]
		 */
		public function GetEventPaymentMethod( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventPaymentMethod', $this->__callServer( $param, 'GetEventPaymentMethod' ) )->EventPaymentMethod;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventPaymentMethodXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventPaymentMethodXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventPersonnelMessage[]
		 */
		public function GetEventPersonnelMessage( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventPersonnelMessage', $this->__callServer( $param, 'GetEventPersonnelMessage' ) )->EventPersonnelMessage;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventPersonnelMessageXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventPersonnelMessageXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventPersonnelObject[]
		 */
		public function GetEventPersonnelObject( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventPersonnelObject', $this->__callServer( $param, 'GetEventPersonnelObject' ) )->EventPersonnelObject;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventPersonnelObjectV2[]
		 */
		public function GetEventPersonnelObjectV2( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventPersonnelObjectV2', $this->__callServer( $param, 'GetEventPersonnelObjectV2' ) )->EventPersonnelObjectV2;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventPersonnelObjectV2Xml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventPersonnelObjectV2Xml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventPersonnelObjectXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventPersonnelObjectXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventProjectNumber[]
		 */
		public function GetEventProjectNumber( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventProjectNumber', $this->__callServer( $param, 'GetEventProjectNumber' ) )->EventProjectNumber;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventProjectNumberXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventProjectNumberXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return EventQuestion[]
		 */
		public function GetEventQuestion( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'EventQuestion', $this->__callServer( $param, 'GetEventQuestion' ) )->EventQuestion;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventQuestionXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventQuestionXml' );
		}

		/**
		 * @param string   $authToken
		 * @param int      $objectID
		 * @param int|null $eventID
		 *
		 * @return EventSeat[]
		 */
		public function GetEventSeat( $authToken, $objectID, $eventID ) {
			$param = array(
				'authToken' => $authToken,
				'objectID'  => $objectID,
				'eventID'   => $eventID,
			);

			return $this->__getArray( 'EventSeat', $this->__callServer( $param, 'GetEventSeat' ) )->EventSeat;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetEventXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetEventXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetGetUserLocationXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetGetUserLocationXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return Grade[]
		 */
		public function GetGrade( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'Grade', $this->__callServer( $param, 'GetGrade' ) )->Grade;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetGradeXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetGradeXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return InterestReg[]
		 */
		public function GetInterestReg( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'InterestReg', $this->__callServer( $param, 'GetInterestReg' ) )->InterestReg;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetInterestRegXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetInterestRegXml' );
		}

		/**
		 * @param string $authToken
		 *
		 * @return string
		 */
		public function GetInternalIPAddressString( $authToken ) {
			$param = array(
				'authToken' => $authToken,
			);

			return $this->__callServer( $param, 'GetInternalIPAddressString' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return LimitedDiscount[]
		 */
		public function GetLimitedDiscount( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'LimitedDiscount', $this->__callServer( $param, 'GetLimitedDiscount' ) )->LimitedDiscount;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return LimitedDiscountObjectStatus[]
		 */
		public function GetLimitedDiscountObjectStatus( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'LimitedDiscountObjectStatus', $this->__callServer( $param, 'GetLimitedDiscountObjectStatus' ) )->LimitedDiscountObjectStatus;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetLimitedDiscountObjectStatusXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetLimitedDiscountObjectStatusXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return LimitedDiscountType[]
		 */
		public function GetLimitedDiscountType( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'LimitedDiscountType', $this->__callServer( $param, 'GetLimitedDiscountType' ) )->LimitedDiscountType;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetLimitedDiscountTypeXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetLimitedDiscountTypeXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetLimitedDiscountXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetLimitedDiscountXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return LMSObject[]
		 */
		public function GetLMSObject( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'LMSObject', $this->__callServer( $param, 'GetLMSObject' ) )->LMSObject;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetLMSObjectXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetLMSObjectXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return Location[]
		 */
		public function GetLocation( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'Location', $this->__callServer( $param, 'GetLocation' ) )->Location;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return LocationAddress[]
		 */
		public function GetLocationAddress( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'LocationAddress', $this->__callServer( $param, 'GetLocationAddress' ) )->LocationAddress;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetLocationAddressXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetLocationAddressXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetLocationXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetLocationXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return ObjectAttribute[]
		 */
		public function GetObjectAttribute( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'ObjectAttribute', $this->__callServer( $param, 'GetObjectAttribute' ) )->ObjectAttribute;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetObjectAttributeXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetObjectAttributeXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return ObjectCategoryQuestion[]
		 */
		public function GetObjectCategoryQuestion( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'ObjectCategoryQuestion', $this->__callServer( $param, 'GetObjectCategoryQuestion' ) )->ObjectCategoryQuestion;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetObjectCategoryQuestionXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetObjectCategoryQuestionXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return ObjectFile[]
		 */
		public function GetObjectFile( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'ObjectFile', $this->__callServer( $param, 'GetObjectFile' ) )->ObjectFile;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetObjectFileXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetObjectFileXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return ObjectPriceName[]
		 */
		public function GetObjectPriceName( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'ObjectPriceName', $this->__callServer( $param, 'GetObjectPriceName' ) )->ObjectPriceName;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetObjectPriceNameXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetObjectPriceNameXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeAttributes
		 *
		 * @return Person[]
		 */
		public function GetPerson( $authToken, $sort, $filter, $includeAttributes ) {
			$param = array(
				'authToken'         => $authToken,
				'sort'              => $sort,
				'filter'            => $filter,
				'includeAttributes' => $includeAttributes,
			);

			return $this->__getArray( 'Person', $this->__callServer( $param, 'GetPerson' ) )->Person;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return PersonAttribute[]
		 */
		public function GetPersonAttribute( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'PersonAttribute', $this->__callServer( $param, 'GetPersonAttribute' ) )->PersonAttribute;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetPersonAttributeXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetPersonAttributeXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return PersonnelObject[]
		 */
		public function GetPersonnelObject( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'PersonnelObject', $this->__callServer( $param, 'GetPersonnelObject' ) )->PersonnelObject;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return PersonnelObjectTitle[]
		 */
		public function GetPersonnelObjectTitle( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'PersonnelObjectTitle', $this->__callServer( $param, 'GetPersonnelObjectTitle' ) )->PersonnelObjectTitle;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetPersonnelObjectTitleXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetPersonnelObjectTitleXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetPersonnelObjectXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetPersonnelObjectXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 * @param bool   $includeAttributes
		 *
		 * @return string
		 */
		public function GetPersonXml( $authToken, $sort, $filter, $includeAttributes ) {
			$param = array(
				'authToken'         => $authToken,
				'sort'              => $sort,
				'filter'            => $filter,
				'includeAttributes' => $includeAttributes,
			);

			return $this->__callServer( $param, 'GetPersonXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return PriceName[]
		 */
		public function GetPriceName( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'PriceName', $this->__callServer( $param, 'GetPriceName' ) )->PriceName;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetPriceNameXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetPriceNameXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return Question[]
		 */
		public function GetQuestion( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'Question', $this->__callServer( $param, 'GetQuestion' ) )->Question;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetQuestionXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetQuestionXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return Region[]
		 */
		public function GetRegion( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'Region', $this->__callServer( $param, 'GetRegion' ) )->Region;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetRegionXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetRegionXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return RentObject[]
		 */
		public function GetRentObject( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'RentObject', $this->__callServer( $param, 'GetRentObject' ) )->RentObject;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetRentObjectXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetRentObjectXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return ReportDoc[]
		 */
		public function GetReportDoc( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'ReportDoc', $this->__callServer( $param, 'GetReportDoc' ) )->ReportDoc;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetReportDocXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetReportDocXml' );
		}

		/**
		 * @param string     $authToken
		 * @param int        $reportID
		 * @param string     $reportName
		 * @param bool       $showAsHtml
		 * @param stdClass[] $parameters
		 *
		 * @return string
		 */
		public function GetReportUrl( $authToken, $reportID, $reportName, $showAsHtml, array $parameters ) {
			$param = array(
				'authToken'  => $authToken,
				'reportID'   => $reportID,
				'reportName' => $reportName,
				'showAsHtml' => $showAsHtml,
				'parameters' => $parameters,
			);

			return $this->__callServer( $param, 'GetReportUrl' );
		}

		/**
		 * @param string $authToken
		 * @param int    $reportID
		 * @param string $reportName
		 * @param bool   $showAsHtml
		 * @param string $parameters
		 *
		 * @return string
		 */
		public function GetReportUrlXml( $authToken, $reportID, $reportName, $showAsHtml, $parameters ) {
			$param = array(
				'authToken'  => $authToken,
				'reportID'   => $reportID,
				'reportName' => $reportName,
				'showAsHtml' => $showAsHtml,
				'parameters' => $parameters,
			);

			return $this->__callServer( $param, 'GetReportUrlXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $customerName
		 *
		 * @return Customer[]
		 */
		public function GetSimilarCustomer( $authToken, $customerName ) {
			$param = array(
				'authToken'    => $authToken,
				'customerName' => $customerName,
			);

			return $this->__getArray( 'Customer', $this->__callServer( $param, 'GetSimilarCustomer' ) )->Customer;
		}

		/**
		 * @param string $authToken
		 * @param string $customerName
		 *
		 * @return string
		 */
		public function GetSimilarCustomerXml( $authToken, $customerName ) {
			$param = array(
				'authToken'    => $authToken,
				'customerName' => $customerName,
			);

			return $this->__callServer( $param, 'GetSimilarCustomerXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return SubEvent[]
		 */
		public function GetSubEvent( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'SubEvent', $this->__callServer( $param, 'GetSubEvent' ) )->SubEvent;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetSubEventXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetSubEventXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return UnavailablePersonnelDate[]
		 */
		public function GetUnavailablePersonnelDate( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'UnavailablePersonnelDate', $this->__callServer( $param, 'GetUnavailablePersonnelDate' ) )->UnavailablePersonnelDate;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetUnavailablePersonnelDateXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetUnavailablePersonnelDateXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return UnnamedParticipant[]
		 */
		public function GetUnnamedParticipant( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'UnnamedParticipant', $this->__callServer( $param, 'GetUnnamedParticipant' ) )->UnnamedParticipant;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return UserAttribute[]
		 */
		public function GetUserAttribute( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'UserAttribute', $this->__callServer( $param, 'GetUserAttribute' ) )->UserAttribute;
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return string
		 */
		public function GetUserAttributeXml( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__callServer( $param, 'GetUserAttributeXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $sort
		 * @param string $filter
		 *
		 * @return UserLocation[]
		 */
		public function GetUserLocation( $authToken, $sort, $filter ) {
			$param = array(
				'authToken' => $authToken,
				'sort'      => $sort,
				'filter'    => $filter,
			);

			return $this->__getArray( 'UserLocation', $this->__callServer( $param, 'GetUserLocation' ) )->UserLocation;
		}

		/**
		 * @param string $authToken
		 * @param int    $objectID
		 * @param int    $categoryID
		 *
		 * @return Coupon[]
		 */
		public function GetValidCoupons( $authToken, $objectID, $categoryID ) {
			$param = array(
				'authToken'  => $authToken,
				'objectID'   => $objectID,
				'categoryID' => $categoryID,
			);

			return $this->__getArray( 'Coupon', $this->__callServer( $param, 'GetValidCoupons' ) )->Coupon;
		}

		/**
		 * @param string   $authToken
		 * @param int[]    $customerIDs
		 * @param stdClass $fromEventStart
		 * @param bool     $updateReference
		 *
		 * @return int[]
		 */
		public function RefreshEventBookingCustomerInfo( $authToken, array $customerIDs, $fromEventStart, $updateReference ) {
			$param = array(
				'authToken'       => $authToken,
				'customerIDs'     => $customerIDs,
				'fromEventStart'  => $fromEventStart,
				'updateReference' => $updateReference,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'RefreshEventBookingCustomerInfo' ) )->int;
		}

		/**
		 * @param string $authToken
		 * @param int    $eventCustomerLnkID
		 *
		 * @return int
		 */
		public function RefreshSingleEventBookingCustomerInfo( $authToken, $eventCustomerLnkID ) {
			$param = array(
				'authToken'          => $authToken,
				'eventCustomerLnkID' => $eventCustomerLnkID,
			);

			return $this->__callServer( $param, 'RefreshSingleEventBookingCustomerInfo' );
		}

		/**
		 * @param string   $authToken
		 * @param int      $eclID
		 * @param string   $from
		 * @param string[] $toAddresses
		 *
		 * @return string[]
		 */
		public function SendConfirmationEmail( $authToken, $eclID, $from, array $toAddresses ) {
			$param = array(
				'authToken' => $authToken,

				'eclID'       => $eclID,
				'from'        => $from,
				'toAddresses' => $toAddresses,
			);

			return $this->__getArray( 'string', $this->__callServer( $param, 'SendConfirmationEmail' ) )->string;
		}

		/**
		 * @param string   $authToken
		 * @param int      $eclID
		 * @param string   $from
		 * @param string[] $toAddresses
		 * @param string[] $toAddressesCopy
		 *
		 * @return ConfirmationEmailInfo
		 */
		public function SendConfirmationEmailAndCopy( $authToken, $eclID, $from, array $toAddresses, array $toAddressesCopy ) {
			$param = array(
				'authToken'       => $authToken,
				'eclID'           => $eclID,
				'from'            => $from,
				'toAddresses'     => $toAddresses,
				'toAddressesCopy' => $toAddressesCopy,
			);

			return $this->__callServer( $param, 'SendConfirmationEmailAndCopy' );
		}

		/**
		 * @param string   $authToken
		 * @param int      $eclID
		 * @param string   $from
		 * @param string[] $toAddresses
		 *
		 * @return string[]
		 */
		public function SendConfirmationEmailCompanyTailored( $authToken, $eclID, $from, array $toAddresses ) {
			$param = array(
				'authToken'   => $authToken,
				'eclID'       => $eclID,
				'from'        => $from,
				'toAddresses' => $toAddresses,
			);

			return $this->__getArray( 'string', $this->__callServer( $param, 'SendConfirmationEmailCompanyTailored' ) )->string;
		}

		/**
		 * @param string   $authToken
		 * @param int      $eclID
		 * @param int      $participantDocumentID
		 * @param string   $from
		 * @param string[] $toAddresses
		 *
		 * @return string[]
		 */
		public function SendConfirmationEmailDoc( $authToken, $eclID, $participantDocumentID, $from, array $toAddresses ) {
			$param = array(
				'authToken'             => $authToken,
				'eclID'                 => $eclID,
				'participantDocumentID' => $participantDocumentID,
				'from'                  => $from,
				'toAddresses'           => $toAddresses,
			);

			return $this->__getArray( 'string', $this->__callServer( $param, 'SendConfirmationEmailDoc' ) )->string;
		}

		/**
		 * @param string $authToken
		 * @param int    $customerContactID
		 * @param string $strSenderDescription
		 *
		 * @return bool
		 */
		public function SendCustomerContactPassword( $authToken, $customerContactID, $strSenderDescription ) {
			$param = array(
				'authToken'            => $authToken,
				'customerContactID'    => $customerContactID,
				'strSenderDescription' => $strSenderDescription,
			);

			return $this->__callServer( $param, 'SendCustomerContactPassword' );
		}

		/**
		 * @param string $authToken
		 * @param int    $customerContactID
		 * @param string $strSenderDescription
		 *
		 * @return bool
		 */
		public function SendCustomerContactPasswordEnglish( $authToken, $customerContactID, $strSenderDescription ) {
			$param = array(
				'authToken'            => $authToken,
				'customerContactID'    => $customerContactID,
				'strSenderDescription' => $strSenderDescription,
			);

			return $this->__callServer( $param, 'SendCustomerContactPasswordEnglish' );
		}

		/**
		 * @param string $authToken
		 * @param int    $customerID
		 * @param string $strSenderDescription
		 *
		 * @return bool
		 */
		public function SendCustomerPassword( $authToken, $customerID, $strSenderDescription ) {
			$param = array(
				'authToken'            => $authToken,
				'customerID'           => $customerID,
				'strSenderDescription' => $strSenderDescription,
			);

			return $this->__callServer( $param, 'SendCustomerPassword' );
		}

		/**
		 * @param string   $authToken
		 * @param int      $limitedDiscountID
		 * @param int      $documentID
		 * @param string   $from
		 * @param string[] $toAddresses
		 *
		 * @return string[]
		 */
		public function SendLimitedDiscountConfirmation( $authToken, $limitedDiscountID, $documentID, $from, array $toAddresses ) {
			$param = array(
				'authToken'         => $authToken,
				'limitedDiscountID' => $limitedDiscountID,
				'documentID'        => $documentID,
				'from'              => $from,
				'toAddresses'       => $toAddresses,
			);

			return $this->__getArray( 'string', $this->__callServer( $param, 'SendLimitedDiscountConfirmation' ) )->string;
		}

		/**
		 * @param string $authToken
		 * @param int    $eclID
		 * @param bool   $paid
		 *
		 * @return void
		 */
		public function SetBookPaidStatus( $authToken, $eclID, $paid ) {
			$param = array(
				'authToken' => $authToken,
				'eclID'     => $eclID,
				'paid'      => $paid,
			);

			$this->__callServer( $param, 'SetBookPaidStatus' );
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $customer
		 *
		 * @return int[]
		 */
		public function SetCustomer( $authToken, array $customer ) {
			$param = array(
				'authToken' => $authToken,
				'customer'  => $customer,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetCustomer' ) )->int;
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $customerAttribute
		 *
		 * @return int[]
		 */
		public function SetCustomerAttribute( $authToken, array $customerAttribute ) {
			$param = array(
				'authToken'         => $authToken,
				'customerAttribute' => $customerAttribute,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetCustomerAttribute' ) )->int;
		}

		/**
		 * @param string $authToken
		 * @param string $customerAttribute
		 *
		 * @return int[]
		 */
		public function SetCustomerAttributeXml( $authToken, $customerAttribute ) {
			$param = array(
				'authToken'         => $authToken,
				'customerAttribute' => $customerAttribute,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetCustomerAttributeXml' ) )->int;
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $customerContact
		 *
		 * @return int[]
		 */
		public function SetCustomerContact( $authToken, array $customerContact ) {
			$param = array(
				'authToken'       => $authToken,
				'customerContact' => $customerContact,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetCustomerContact' ) )->int;
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $customerContactAttribute
		 *
		 * @return int[]
		 */
		public function SetCustomerContactAttributes( $authToken, array $customerContactAttribute ) {
			$param = array(
				'authToken'                => $authToken,
				'customerContactAttribute' => $customerContactAttribute,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetCustomerContactAttributes' ) )->int;
		}

		/**
		 * @param string $authToken
		 * @param string $customerContactAttribute
		 *
		 * @return int[]
		 */
		public function SetCustomerContactAttributesXml( $authToken, $customerContactAttribute ) {
			$param = array(
				'authToken'                => $authToken,
				'customerContactAttribute' => $customerContactAttribute,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetCustomerContactAttributesXml' ) )->int;
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $customerContact
		 *
		 * @return int[]
		 */
		public function SetCustomerContactV2( $authToken, array $customerContact ) {
			$param = array(
				'authToken'       => $authToken,
				'customerContact' => $customerContact,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetCustomerContactV2' ) )->int;
		}

		/**
		 * @param string $authToken
		 * @param string $customerContact
		 *
		 * @return int[]
		 */
		public function SetCustomerContactV2Xml( $authToken, $customerContact ) {
			$param = array(
				'authToken'       => $authToken,
				'customerContact' => $customerContact,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetCustomerContactV2Xml' ) )->int;
		}

		/**
		 * @param string $authToken
		 * @param string $customerContact
		 *
		 * @return int[]
		 */
		public function SetCustomerContactXml( $authToken, $customerContact ) {
			$param = array(
				'authToken'       => $authToken,
				'customerContact' => $customerContact,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetCustomerContactXml' ) )->int;
		}

		/**
		 * @param string     $authToken
		 * @param int        $customerId
		 * @param stdClass[] $extraInfo
		 *
		 * @return void
		 */
		public function SetCustomerExtraInfo( $authToken, $customerId, array $extraInfo ) {
			$param = array(
				'authToken'  => $authToken,
				'customerId' => $customerId,
				'extraInfo'  => $extraInfo,
			);

			$this->__callServer( $param, 'SetCustomerExtraInfo' );
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $customer
		 *
		 * @return int[]
		 */
		public function SetCustomerV2( $authToken, array $customer ) {
			$param = array(
				'authToken' => $authToken,
				'customer'  => $customer,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetCustomerV2' ) )->int;
		}

		/**
		 * @param string $authToken
		 * @param string $customer
		 *
		 * @return int[]
		 */
		public function SetCustomerV2Xml( $authToken, $customer ) {
			$param = array(
				'authToken' => $authToken,
				'customer'  => $customer,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetCustomerV2Xml' ) )->int;
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $customer
		 *
		 * @return int[]
		 */
		public function SetCustomerV3( $authToken, array $customer ) {
			$param = array(
				'authToken' => $authToken,
				'customer'  => $customer,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetCustomerV3' ) )->int;
		}

		/**
		 * @param string $authToken
		 * @param string $customer
		 *
		 * @return int[]
		 */
		public function SetCustomerXml( $authToken, $customer ) {
			$param = array(
				'authToken' => $authToken,
				'customer'  => $customer,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetCustomerXml' ) )->int;
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $eventBookingPostponedDates
		 *
		 * @return void
		 */
		public function SetEventBookingPostponedBillingDate( $authToken, array $eventBookingPostponedDates ) {
			$param = array(
				'authToken'                  => $authToken,
				'eventBookingPostponedDates' => $eventBookingPostponedDates,
			);

			$this->__callServer( $param, 'SetEventBookingPostponedBillingDate' );
		}

		/**
		 * @param string $authToken
		 * @param string $eventBookingPostponedDates
		 *
		 * @return void
		 */
		public function SetEventBookingPostponedBillingDateXml( $authToken, $eventBookingPostponedDates ) {
			$param = array(
				'authToken'                  => $authToken,
				'eventBookingPostponedDates' => $eventBookingPostponedDates,
			);

			$this->__callServer( $param, 'SetEventBookingPostponedBillingDateXml' );
		}

		/**
		 * @param string $authToken
		 * @param int    $eventCustomerLnkID
		 * @param bool   $preliminary
		 *
		 * @return void
		 */
		public function SetEventBookingPreliminaryStatus( $authToken, $eventCustomerLnkID, $preliminary ) {
			$param = array(
				'authToken'          => $authToken,
				'eventCustomerLnkID' => $eventCustomerLnkID,
				'preliminary'        => $preliminary,
			);

			$this->__callServer( $param, 'SetEventBookingPreliminaryStatus' );
		}

		/**
		 * @param string     $authToken
		 * @param int        $eventCustomerLnkID
		 * @param stdClass[] $lstEditPriceNames
		 *
		 * @return void
		 */
		public function SetEventBookingPricenameParticipantNr( $authToken, $eventCustomerLnkID, array $lstEditPriceNames ) {
			$param = array(
				'authToken'          => $authToken,
				'eventCustomerLnkID' => $eventCustomerLnkID,
				'lstEditPriceNames'  => $lstEditPriceNames,
			);

			$this->__callServer( $param, 'SetEventBookingPricenameParticipantNr' );
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $eventCustomerAnswer
		 *
		 * @return void
		 */
		public function SetEventCustomerAnswer( $authToken, array $eventCustomerAnswer ) {
			$param = array(
				'authToken'           => $authToken,
				'eventCustomerAnswer' => $eventCustomerAnswer,
			);

			$this->__callServer( $param, 'SetEventCustomerAnswer' );
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $eventCustomerAnswer
		 *
		 * @return void
		 */
		public function SetEventCustomerAnswerV2( $authToken, array $eventCustomerAnswer ) {
			$param = array(
				'authToken'           => $authToken,
				'eventCustomerAnswer' => $eventCustomerAnswer,
			);

			$this->__callServer( $param, 'SetEventCustomerAnswerV2' );
		}

		/**
		 * @param string $authToken
		 * @param string $eventCustomerAnswer
		 *
		 * @return void
		 */
		public function SetEventCustomerAnswerV2Xml( $authToken, $eventCustomerAnswer ) {
			$param = array(
				'authToken'           => $authToken,
				'eventCustomerAnswer' => $eventCustomerAnswer,
			);

			$this->__callServer( $param, 'SetEventCustomerAnswerV2Xml' );
		}

		/**
		 * @param string $authToken
		 * @param string $eventCustomerAnswer
		 *
		 * @return void
		 */
		public function SetEventCustomerAnswerXml( $authToken, $eventCustomerAnswer ) {
			$param = array(
				'authToken'           => $authToken,
				'eventCustomerAnswer' => $eventCustomerAnswer,
			);

			$this->__callServer( $param, 'SetEventCustomerAnswerXml' );
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $eventParticipant
		 *
		 * @return int[]
		 */
		public function SetEventParticipant( $authToken, array $eventParticipant ) {
			$param = array(
				'authToken'        => $authToken,
				'eventParticipant' => $eventParticipant,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetEventParticipant' ) )->int;
		}

		/**
		 * @param string $authToken
		 * @param int    $eventParticipantID
		 * @param bool   $arrived
		 *
		 * @return void
		 */
		public function SetEventParticipantArrivedStatus( $authToken, $eventParticipantID, $arrived ) {
			$param = array(
				'authToken'          => $authToken,
				'eventParticipantID' => $eventParticipantID,
				'arrived'            => $arrived,
			);

			$this->__callServer( $param, 'SetEventParticipantArrivedStatus' );
		}

		/**
		 * @param string $authToken
		 * @param int    $eventParticipantID
		 * @param int    $gradeID
		 *
		 * @return void
		 */
		public function SetEventParticipantGrade( $authToken, $eventParticipantID, $gradeID ) {
			$param = array(
				'authToken'          => $authToken,
				'eventParticipantID' => $eventParticipantID,
				'gradeID'            => $gradeID,
			);

			$this->__callServer( $param, 'SetEventParticipantGrade' );
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $subEventList
		 *
		 * @return bool
		 */
		public function SetEventParticipantSubEvent( $authToken, array $subEventList ) {
			$param = array(
				'authToken'    => $authToken,
				'subEventList' => $subEventList,
			);

			return $this->__callServer( $param, 'SetEventParticipantSubEvent' );
		}

		/**
		 * @param string $authToken
		 * @param string $subEventList
		 *
		 * @return bool
		 */
		public function SetEventParticipantSubEventXml( $authToken, $subEventList ) {
			$param = array(
				'authToken'    => $authToken,
				'subEventList' => $subEventList,
			);

			return $this->__callServer( $param, 'SetEventParticipantSubEventXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $eventParticipant
		 *
		 * @return int[]
		 */
		public function SetEventParticipantXml( $authToken, $eventParticipant ) {
			$param = array(
				'authToken'        => $authToken,
				'eventParticipant' => $eventParticipant,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetEventParticipantXml' ) )->int;
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $interestRegEventList
		 *
		 * @return int[]
		 */
		public function SetInterestRegEvent( $authToken, array $interestRegEventList ) {
			$param = array(
				'authToken'            => $authToken,
				'interestRegEventList' => $interestRegEventList,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetInterestRegEvent' ) )->int;
		}

		/**
		 * @param string   $authToken
		 * @param stdClass $bookingInfo
		 *
		 * @return int
		 */
		public function SetInterestRegEventBooking( $authToken, $bookingInfo ) {
			$param = array(
				'authToken'   => $authToken,
				'bookingInfo' => $bookingInfo,
			);

			return $this->__callServer( $param, 'SetInterestRegEventBooking' );
		}

		/**
		 * @param string $authToken
		 * @param string $bookingInfoXml
		 *
		 * @return int
		 */
		public function SetInterestRegEventBookingXml( $authToken, $bookingInfoXml ) {
			$param = array(
				'authToken'      => $authToken,
				'bookingInfoXml' => $bookingInfoXml,
			);

			return $this->__callServer( $param, 'SetInterestRegEventBookingXml' );
		}

		/**
		 * @param string $authToken
		 * @param string $interestRegEventXml
		 *
		 * @return int[]
		 */
		public function SetInterestRegEventXml( $authToken, $interestRegEventXml ) {
			$param = array(
				'authToken'           => $authToken,
				'interestRegEventXml' => $interestRegEventXml,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetInterestRegEventXml' ) )->int;
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $interestRegObjectList
		 *
		 * @return int[]
		 */
		public function SetInterestRegObject( $authToken, array $interestRegObjectList ) {
			$param = array(
				'authToken'             => $authToken,
				'interestRegObjectList' => $interestRegObjectList,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetInterestRegObject' ) )->int;
		}

		/**
		 * @param string $authToken
		 * @param string $interestRegObjectXml
		 *
		 * @return int[]
		 */
		public function SetInterestRegObjectXml( $authToken, $interestRegObjectXml ) {
			$param = array(
				'authToken'            => $authToken,
				'interestRegObjectXml' => $interestRegObjectXml,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetInterestRegObjectXml' ) )->int;
		}

		/**
		 * @param string   $authToken
		 * @param stdClass $bookingInfo
		 *
		 * @return InterestRegReturnObject
		 */
		public function SetInterestRegSubEventBooking( $authToken, $bookingInfo ) {
			$param = array(
				'authToken'   => $authToken,
				'bookingInfo' => $bookingInfo,
			);

			return $this->__callServer( $param, 'SetInterestRegSubEventBooking' );
		}

		/**
		 * @param string $authToken
		 * @param string $bookingInfo
		 *
		 * @return string
		 */
		public function SetInterestRegSubEventBookingXml( $authToken, $bookingInfo ) {
			$param = array(
				'authToken'   => $authToken,
				'bookingInfo' => $bookingInfo,
			);

			return $this->__callServer( $param, 'SetInterestRegSubEventBookingXml' );
		}

		/**
		 * @param string $authToken
		 * @param int    $eclID
		 *
		 * @return void
		 */
		public function SetInvalidPayment( $authToken, $eclID ) {
			$param = array(
				'authToken' => $authToken,
				'eclID'     => $eclID,
			);

			$this->__callServer( $param, 'SetInvalidPayment' );
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $limitedDiscount
		 *
		 * @return int[]
		 */
		public function SetLimitedDiscount( $authToken, array $limitedDiscount ) {
			$param = array(
				'authToken'       => $authToken,
				'limitedDiscount' => $limitedDiscount,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetLimitedDiscount' ) )->int;
		}

		/**
		 * @param string $authToken
		 * @param string $limitedDiscount
		 *
		 * @return int[]
		 */
		public function SetLimitedDiscountXml( $authToken, $limitedDiscount ) {
			$param = array(
				'authToken'       => $authToken,
				'limitedDiscount' => $limitedDiscount,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetLimitedDiscountXml' ) )->int;
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $person
		 *
		 * @return int[]
		 */
		public function SetPerson( $authToken, array $person ) {
			$param = array(
				'authToken' => $authToken,
				'person'    => $person,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetPerson' ) )->int;
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $personAttribute
		 *
		 * @return int[]
		 */
		public function SetPersonAttribute( $authToken, array $personAttribute ) {
			$param = array(
				'authToken'       => $authToken,
				'personAttribute' => $personAttribute,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetPersonAttribute' ) )->int;
		}

		/**
		 * @param string $authToken
		 * @param string $personAttribute
		 *
		 * @return int[]
		 */
		public function SetPersonAttributeXml( $authToken, $personAttribute ) {
			$param = array(
				'authToken'       => $authToken,
				'personAttribute' => $personAttribute,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetPersonAttributeXml' ) )->int;
		}

		/**
		 * @param string $authToken
		 * @param string $person
		 *
		 * @return int[]
		 */
		public function SetPersonXml( $authToken, $person ) {
			$param = array(
				'authToken' => $authToken,
				'person'    => $person,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'SetPersonXml' ) )->int;
		}

		/**
		 * @param string     $authToken
		 * @param int        $personnelID
		 * @param stdClass[] $unavailableDates
		 *
		 * @return UnavailableDateResponse
		 */
		public function SetUnavailablePersonnelDate( $authToken, $personnelID, array $unavailableDates ) {
			$param = array(
				'authToken'        => $authToken,
				'personnelID'      => $personnelID,
				'unavailableDates' => $unavailableDates,
			);

			return $this->__callServer( $param, 'SetUnavailablePersonnelDate' );
		}

		/**
		 * @param string $authToken
		 * @param int    $personnelID
		 * @param string $unavailableDates
		 *
		 * @return string
		 */
		public function SetUnavailablePersonnelDateXml( $authToken, $personnelID, $unavailableDates ) {
			$param = array(
				'authToken'        => $authToken,
				'personnelID'      => $personnelID,
				'unavailableDates' => $unavailableDates,
			);

			return $this->__callServer( $param, 'SetUnavailablePersonnelDateXml' );
		}

		/**
		 * @param string $authToken
		 * @param int    $eclID
		 *
		 * @return void
		 */
		public function SetValidPayment( $authToken, $eclID ) {
			$param = array(
				'authToken' => $authToken,
				'eclID'     => $eclID,
			);

			$this->__callServer( $param, 'SetValidPayment' );
		}

		/**
		 * @param string     $authToken
		 * @param stdClass[] $updateInfo
		 *
		 * @return int[]
		 */
		public function UpdateSalesBookingInfo( $authToken, array $updateInfo ) {
			$param = array(
				'authToken'  => $authToken,
				'updateInfo' => $updateInfo,
			);

			return $this->__getArray( 'int', $this->__callServer( $param, 'UpdateSalesBookingInfo' ) )->int;
		}

		/**
		 * @param string $authToken
		 * @param string $addressString
		 * @param string $compareAddress
		 *
		 * @return bool
		 */
		public function ValidateAddressString( $authToken, $addressString, $compareAddress ) {
			$param = array(
				'authToken'      => $authToken,
				'addressString'  => $addressString,
				'compareAddress' => $compareAddress,
			);

			return $this->__callServer( $param, 'ValidateAddressString' );
		}

		/**
		 * @param string $authToken
		 *
		 * @return bool
		 */
		public function ValidateAuthToken( $authToken ) {
			$param = array(
				'authToken' => $authToken,
			);

			return $this->__callServer( $param, 'ValidateAuthToken' );
		}
	}

	/**
	 * AccountInfo
	 */
	class AccountInfo {
		/**
		 * @var string
		 */
		public $Name;
		/**
		 * @var string
		 */
		public $Email;
		/**
		 * @var string
		 */
		public $Address1;
		/**
		 * @var string
		 */
		public $Zip;
		/**
		 * @var string
		 */
		public $City;
		/**
		 * @var string
		 */
		public $Phone;
		/**
		 * @var string
		 */
		public $Fax;
		/**
		 * @var string
		 */
		public $OrgNr;
		/**
		 * @var string
		 */
		public $Homepage;

		/**
		 * AccountInfo constructor
		 */
		public function __construct() {
		}
	}

	/**
	 * AccountSettingsInfo
	 */
	class AccountSettingsInfo {
		/**
		 * @var mixed
		 */
		var $Setting;
		/**
		 * @var string
		 */
		public $Value;

		/**
		 * AccountSettingsInfo constructor
		 */
		public function __construct() {
			$this->Setting = null;
		}
	}

	/**
	 * Attribute
	 */
	class Attribute {
		/**
		 * @var int
		 */
		public $AttributeID;
		/**
		 * @var int
		 */
		public $AttributeTypeID;
		/**
		 * @var string
		 */
		public $AttributeTypeDescription;
		/**
		 * @var int
		 */
		public $AttributeOwnerTypeID;
		/**
		 * @var string
		 */
		public $AttributeOwnerTypeDescription;
		/**
		 * @var string
		 */
		public $AttributeDescription;
		/**
		 * @var string
		 */
		public $AttributeValue;
		/**
		 * @var mixed
		 */
		var $AttributeAlternative;

		/**
		 * Attribute constructor
		 */
		public function __construct() {
			$this->AttributeID          = 0;
			$this->AttributeTypeID      = 0;
			$this->AttributeOwnerTypeID = 0;
			$this->AttributeAlternative = array();
		}
	}

	/**
	 * AttributeAlternative
	 */
	class AttributeAlternative {
		/**
		 * @var int
		 */
		public $AttributeAlternativeID;
		/**
		 * @var string
		 */
		public $AttributeAlternativeDescription;

		/**
		 * AttributeAlternative constructor
		 */
		public function __construct() {
			$this->AttributeAlternativeID = 0;
		}
	}

	/**
	 * BookedEventAccessory
	 */
	class BookedEventAccessory {
		/**
		 * @var int
		 */
		public $EventCustomerLnkID;
		/**
		 * @var int
		 */
		public $BookedQuantity;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var mixed
		 */
		var $StartDate;
		/**
		 * @var mixed
		 */
		var $EndDate;
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var mixed
		 */
		var $Cost;
		/**
		 * @var int
		 */
		public $Quantity;
		/**
		 * @var mixed
		 */
		var $ObjectPrice;
		/**
		 * @var string
		 */
		public $PublicName;
		/**
		 * @var mixed
		 */
		var $VatPercent;

		/**
		 * BookedEventAccessory constructor
		 */
		public function __construct() {
			$this->EventCustomerLnkID = 0;
			$this->BookedQuantity     = 0;
			$this->ObjectID           = 0;
			$this->StartDate          = date( 'c' );
			$this->EndDate            = date( 'c' );
			$this->EventID            = 0;
			$this->Cost               = null;
			$this->Quantity           = 0;
			$this->ObjectPrice        = null;
			$this->VatPercent         = null;
		}
	}

	/**
	 * BookingInfo
	 */
	class BookingInfo {
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int
		 */
		public $CustomerContactID;
		/**
		 * @var string
		 */
		public $CustomerReference;
		/**
		 * @var int|null
		 */
		public $PaymentMethodID;
		/**
		 * @var int|null
		 */
		public $OccasionPriceNameLnkID;
		/**
		 * @var int[]
		 */
		public $PersonIDs;
		/**
		 * @var string
		 */
		public $Notes;
		/**
		 * @var int|null
		 */
		public $LimitedDiscountID;

		/**
		 * BookingInfo constructor
		 */
		public function __construct() {
			$this->EventID                = 0;
			$this->CustomerID             = 0;
			$this->CustomerContactID      = 0;
			$this->PaymentMethodID        = null;
			$this->OccasionPriceNameLnkID = null;
			$this->PersonIDs              = array();
			$this->LimitedDiscountID      = null;
		}
	}

	/**
	 * BookingInfoPriceName
	 */
	class BookingInfoPriceName {
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int|null
		 */
		public $CustomerContactID;
		/**
		 * @var string
		 */
		public $CustomerReference;
		/**
		 * @var int|null
		 */
		public $PaymentMethodID;
		/**
		 * @var string
		 */
		public $Notes;
		/**
		 * @var int|null
		 */
		public $CouponID;
		/**
		 * @var mixed
		 */
		var $PriceNames;
		/**
		 * @var mixed
		 */
		var $Preliminary;

		/**
		 * BookingInfoPriceName constructor
		 */
		public function __construct() {
			$this->EventID           = 0;
			$this->CustomerID        = 0;
			$this->CustomerContactID = null;
			$this->PaymentMethodID   = null;
			$this->CouponID          = null;
			$this->PriceNames        = array();
			$this->Preliminary       = null;
		}
	}

	/**
	 * BookingInfoSubEvent
	 */
	class BookingInfoSubEvent {
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int
		 */
		public $CustomerContactID;
		/**
		 * @var string
		 */
		public $CustomerReference;
		/**
		 * @var int|null
		 */
		public $PaymentMethodID;
		/**
		 * @var int|null
		 */
		public $OccasionPriceNameLnkID;
		/**
		 * @var string
		 */
		public $Notes;
		/**
		 * @var int|null
		 */
		public $LimitedDiscountID;
		/**
		 * @var mixed
		 */
		var $Preliminary;
		/**
		 * @var mixed
		 */
		var $SubEventPersons;
		/**
		 * @var string
		 */
		public $PurchaseOrderNumber;
		/**
		 * @var int|null
		 */
		public $CouponID;

		/**
		 * BookingInfoSubEvent constructor
		 */
		public function __construct() {
			$this->EventID                = 0;
			$this->CustomerID             = 0;
			$this->CustomerContactID      = 0;
			$this->PaymentMethodID        = null;
			$this->OccasionPriceNameLnkID = null;
			$this->LimitedDiscountID      = null;
			$this->Preliminary            = null;
			$this->SubEventPersons        = array();
			$this->CouponID               = null;
		}
	}

	/**
	 * BookingSeatInfo
	 */
	class BookingSeatInfo {
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int
		 */
		public $CustomerContactID;
		/**
		 * @var string
		 */
		public $CustomerReference;
		/**
		 * @var int|null
		 */
		public $PaymentMethodID;
		/**
		 * @var int|null
		 */
		public $OccasionPriceNameLnkID;
		/**
		 * @var string
		 */
		public $Notes;
		/**
		 * @var int|null
		 */
		public $LimitedDiscountID;
		/**
		 * @var mixed
		 */
		var $Preliminary;
		/**
		 * @var mixed
		 */
		var $SubEventPersons;
		/**
		 * @var string
		 */
		public $PurchaseOrderNumber;
		/**
		 * @var int|null
		 */
		public $CouponID;

		/**
		 * BookingSeatInfo constructor
		 */
		public function __construct() {
			$this->EventID                = 0;
			$this->CustomerID             = 0;
			$this->CustomerContactID      = 0;
			$this->PaymentMethodID        = null;
			$this->OccasionPriceNameLnkID = null;
			$this->LimitedDiscountID      = null;
			$this->Preliminary            = null;
			$this->SubEventPersons        = array();
			$this->CouponID               = null;
		}
	}

	/**
	 * Category
	 */
	class Category {
		/**
		 * @var int
		 */
		public $CategoryID;
		/**
		 * @var string
		 */
		public $CategoryName;
		/**
		 * @var bool
		 */
		public $ShowOnWeb;
		/**
		 * @var string
		 */
		public $ImageUrl;
		/**
		 * @var string
		 */
		public $CategoryNotes;
		/**
		 * @var int
		 */
		public $ParentID;
		/**
		 * @var bool
		 */
		public $ShowOnWebInternal;

		/**
		 * Category constructor
		 */
		public function __construct() {
			$this->CategoryID        = 0;
			$this->ShowOnWeb         = false;
			$this->ParentID          = 0;
			$this->ShowOnWebInternal = false;
		}
	}

	/**
	 * CategoryV2
	 */
	class CategoryV2 {
		/**
		 * @var string
		 */
		public $MetaType;
		/**
		 * @var int
		 */
		public $CategoryID;
		/**
		 * @var string
		 */
		public $CategoryName;
		/**
		 * @var bool
		 */
		public $ShowOnWeb;
		/**
		 * @var string
		 */
		public $ImageUrl;
		/**
		 * @var string
		 */
		public $CategoryNotes;
		/**
		 * @var int
		 */
		public $ParentID;
		/**
		 * @var bool
		 */
		public $ShowOnWebInternal;

		/**
		 * CategoryV2 constructor
		 */
		public function __construct() {
			$this->CategoryID        = 0;
			$this->ShowOnWeb         = false;
			$this->ParentID          = 0;
			$this->ShowOnWebInternal = false;
		}
	}

	/**
	 * CategoryV3
	 */
	class CategoryV3 {
		/**
		 * @var string
		 */
		public $Color;
		/**
		 * @var string
		 */
		public $MetaType;
		/**
		 * @var int
		 */
		public $CategoryID;
		/**
		 * @var string
		 */
		public $CategoryName;
		/**
		 * @var bool
		 */
		public $ShowOnWeb;
		/**
		 * @var string
		 */
		public $ImageUrl;
		/**
		 * @var string
		 */
		public $CategoryNotes;
		/**
		 * @var int
		 */
		public $ParentID;
		/**
		 * @var bool
		 */
		public $ShowOnWebInternal;

		/**
		 * CategoryV3 constructor
		 */
		public function __construct() {
			$this->CategoryID        = 0;
			$this->ShowOnWeb         = false;
			$this->ParentID          = 0;
			$this->ShowOnWebInternal = false;
		}
	}

	/**
	 * Certificate
	 */
	class Certificate {
		/**
		 * @var int
		 */
		public $CertificateID;
		/**
		 * @var string
		 */
		public $CertificateNumber;
		/**
		 * @var string
		 */
		public $CertificateName;
		/**
		 * @var mixed
		 */
		var $Created;
		/**
		 * @var int|null
		 */
		public $ValidMonthCount;
		/**
		 * @var int|null
		 */
		public $ValidDayCount;
		/**
		 * @var int|null
		 */
		public $CompleteObjectsMonthCount;
		/**
		 * @var mixed
		 */
		var $ObjectRules;
		/**
		 * @var mixed
		 */
		var $CertificateRules;

		/**
		 * Certificate constructor
		 */
		public function __construct() {
			$this->CertificateID             = 0;
			$this->Created                   = date( 'c' );
			$this->ValidMonthCount           = null;
			$this->ValidDayCount             = null;
			$this->CompleteObjectsMonthCount = null;
			$this->ObjectRules               = array();
			$this->CertificateRules          = array();
		}
	}

	/**
	 * CertificatePerson
	 */
	class CertificatePerson {
		/**
		 * @var int
		 */
		public $PersonID;
		/**
		 * @var string
		 */
		public $PersonFirstName;
		/**
		 * @var string
		 */
		public $PersonLastName;
		/**
		 * @var string
		 */
		public $PersonCivicRegistrationNumber;
		/**
		 * @var string
		 */
		public $PersonEmail;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int
		 */
		public $CertificateID;
		/**
		 * @var string
		 */
		public $CertificateNumber;
		/**
		 * @var string
		 */
		public $CertificateName;
		/**
		 * @var mixed
		 */
		var $CertificateDate;
		/**
		 * @var mixed
		 */
		var $ValidFrom;
		/**
		 * @var mixed
		 */
		var $ValidTo;
		/**
		 * @var int|null
		 */
		public $CertificateFromEventID;

		/**
		 * CertificatePerson constructor
		 */
		public function __construct() {
			$this->PersonID               = 0;
			$this->CustomerID             = 0;
			$this->CertificateID          = 0;
			$this->CertificateDate        = date( 'c' );
			$this->ValidFrom              = null;
			$this->ValidTo                = null;
			$this->CertificateFromEventID = null;
		}
	}

	/**
	 * CertificatePersonV2
	 */
	class CertificatePersonV2 {
		/**
		 * @var int
		 */
		public $CertificatePersonID;
		/**
		 * @var bool
		 */
		public $RequiresHealthCertificate;
		/**
		 * @var int[]
		 */
		public $CertificateFromEventIDs;
		/**
		 * @var int[]
		 */
		public $CertificateFromPersonCertificateIDs;
		/**
		 * @var mixed
		 */
		var $Subjects;
		/**
		 * @var bool
		 */
		public $CertificateAfterRetest;
		/**
		 * @var string
		 */
		public $CertificatePersonComment;
		/**
		 * @var int
		 */
		public $PersonID;
		/**
		 * @var string
		 */
		public $PersonFirstName;
		/**
		 * @var string
		 */
		public $PersonLastName;
		/**
		 * @var string
		 */
		public $PersonCivicRegistrationNumber;
		/**
		 * @var string
		 */
		public $PersonEmail;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int
		 */
		public $CertificateID;
		/**
		 * @var string
		 */
		public $CertificateNumber;
		/**
		 * @var string
		 */
		public $CertificateName;
		/**
		 * @var mixed
		 */
		var $CertificateDate;
		/**
		 * @var mixed
		 */
		var $ValidFrom;
		/**
		 * @var mixed
		 */
		var $ValidTo;
		/**
		 * @var int|null
		 */
		public $CertificateFromEventID;

		/**
		 * CertificatePersonV2 constructor
		 */
		public function __construct() {
			$this->CertificatePersonID                 = 0;
			$this->RequiresHealthCertificate           = false;
			$this->CertificateFromEventIDs             = array();
			$this->CertificateFromPersonCertificateIDs = array();
			$this->Subjects                            = array();
			$this->CertificateAfterRetest              = false;
			$this->PersonID                            = 0;
			$this->CustomerID                          = 0;
			$this->CertificateID                       = 0;
			$this->CertificateDate                     = date( 'c' );
			$this->ValidFrom                           = null;
			$this->ValidTo                             = null;
			$this->CertificateFromEventID              = null;
		}
	}

	/**
	 * CertificateRule
	 */
	class CertificateRule {
		/**
		 * @var int
		 */
		public $CertificateID;
		/**
		 * @var string
		 */
		public $CertificateName;

		/**
		 * CertificateRule constructor
		 */
		public function __construct() {
			$this->CertificateID = 0;
		}
	}

	/**
	 * CompanyAttribute
	 */
	class CompanyAttribute {
		/**
		 * @var bool
		 */
		public $AttributeChecked;
		/**
		 * @var mixed
		 */
		var $AttributeDate;
		/**
		 * @var int
		 */
		public $AttributeID;
		/**
		 * @var int
		 */
		public $AttributeTypeID;
		/**
		 * @var string
		 */
		public $AttributeTypeDescription;
		/**
		 * @var int
		 */
		public $AttributeOwnerTypeID;
		/**
		 * @var string
		 */
		public $AttributeOwnerTypeDescription;
		/**
		 * @var string
		 */
		public $AttributeDescription;
		/**
		 * @var string
		 */
		public $AttributeValue;
		/**
		 * @var mixed
		 */
		var $AttributeAlternative;

		/**
		 * CompanyAttribute constructor
		 */
		public function __construct() {
			$this->AttributeChecked     = false;
			$this->AttributeDate        = null;
			$this->AttributeID          = 0;
			$this->AttributeTypeID      = 0;
			$this->AttributeOwnerTypeID = 0;
			$this->AttributeAlternative = array();
		}
	}

	/**
	 * ConfirmationEmailInfo
	 */
	class ConfirmationEmailInfo {
		/**
		 * @var string[]
		 */
		public $ConfirmationSentTo;
		/**
		 * @var string[]
		 */
		public $ConfirmationCopySentTo;

		/**
		 * ConfirmationEmailInfo constructor
		 */
		public function __construct() {
			$this->ConfirmationSentTo     = array();
			$this->ConfirmationCopySentTo = array();
		}
	}

	/**
	 * ConfirmationEmailMessage
	 */
	class ConfirmationEmailMessage {
		/**
		 * @var int
		 */
		public $DocumentID;
		/**
		 * @var string
		 */
		public $Subject;
		/**
		 * @var string
		 */
		public $Body;

		/**
		 * ConfirmationEmailMessage constructor
		 */
		public function __construct() {
			$this->DocumentID = 0;
		}
	}

	/**
	 * Country
	 */
	class Country {
		/**
		 * @var string
		 */
		public $CountryCode;
		/**
		 * @var string
		 */
		public $Abbr;
		/**
		 * @var int|null
		 */
		public $CountryNumber;
		/**
		 * @var string
		 */
		public $CountryName;
		/**
		 * @var string
		 */
		public $CultureName;

		/**
		 * Country constructor
		 */
		public function __construct() {
			$this->CountryNumber = null;
		}
	}

	/**
	 * Coupon
	 */
	class Coupon {
		/**
		 * @var int
		 */
		public $CouponID;
		/**
		 * @var string
		 */
		public $Code;
		/**
		 * @var mixed
		 */
		var $DiscountPercent;
		/**
		 * @var string
		 */
		public $CouponDescription;
		/**
		 * @var mixed
		 */
		var $ValidFrom;
		/**
		 * @var mixed
		 */
		var $ValidTo;

		/**
		 * Coupon constructor
		 */
		public function __construct() {
			$this->CouponID        = 0;
			$this->DiscountPercent = null;
			$this->ValidFrom       = date( 'c' );
			$this->ValidTo         = date( 'c' );
		}
	}

	/**
	 * Customer
	 */
	class Customer {
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var string
		 */
		public $CustomerNumber;
		/**
		 * @var string
		 */
		public $CustomerName;
		/**
		 * @var string
		 */
		public $Address1;
		/**
		 * @var string
		 */
		public $Address2;
		/**
		 * @var string
		 */
		public $Zip;
		/**
		 * @var string
		 */
		public $City;
		/**
		 * @var string
		 */
		public $Country;
		/**
		 * @var string
		 */
		public $Phone;
		/**
		 * @var string
		 */
		public $Mobile;
		/**
		 * @var string
		 */
		public $Fax;
		/**
		 * @var string
		 */
		public $Email;
		/**
		 * @var string
		 */
		public $Homepage;
		/**
		 * @var string
		 */
		public $InvoiceName;
		/**
		 * @var string
		 */
		public $InvoiceAddress1;
		/**
		 * @var string
		 */
		public $InvoiceAddress2;
		/**
		 * @var string
		 */
		public $InvoiceZip;
		/**
		 * @var string
		 */
		public $InvoiceCity;
		/**
		 * @var string
		 */
		public $InvoiceCountry;
		/**
		 * @var string
		 */
		public $InvoiceOrgnr;
		/**
		 * @var string
		 */
		public $CustomerGroupName;
		/**
		 * @var int|null
		 */
		public $CustomerGroupID;
		/**
		 * @var string
		 */
		public $Password;
		/**
		 * @var string
		 */
		public $InvoiceVatnr;
		/**
		 * @var string
		 */
		public $CustomerReference;
		/**
		 * @var mixed
		 */
		var $VatFree;
		/**
		 * @var mixed
		 */
		var $Attribute;

		/**
		 * Customer constructor
		 */
		public function __construct() {
			$this->CustomerID      = 0;
			$this->CustomerGroupID = null;
			$this->VatFree         = null;
			$this->Attribute       = array();
		}
	}

	/**
	 * CustomerAttribute
	 */
	class CustomerAttribute {
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int|null
		 */
		public $CustomerAttributeID;
		/**
		 * @var bool
		 */
		public $AttributeChecked;
		/**
		 * @var mixed
		 */
		var $AttributeDate;
		/**
		 * @var int
		 */
		public $AttributeID;
		/**
		 * @var int
		 */
		public $AttributeTypeID;
		/**
		 * @var string
		 */
		public $AttributeTypeDescription;
		/**
		 * @var int
		 */
		public $AttributeOwnerTypeID;
		/**
		 * @var string
		 */
		public $AttributeOwnerTypeDescription;
		/**
		 * @var string
		 */
		public $AttributeDescription;
		/**
		 * @var string
		 */
		public $AttributeValue;
		/**
		 * @var mixed
		 */
		var $AttributeAlternative;

		/**
		 * CustomerAttribute constructor
		 */
		public function __construct() {
			$this->CustomerID           = 0;
			$this->CustomerAttributeID  = null;
			$this->AttributeChecked     = false;
			$this->AttributeDate        = null;
			$this->AttributeID          = 0;
			$this->AttributeTypeID      = 0;
			$this->AttributeOwnerTypeID = 0;
			$this->AttributeAlternative = array();
		}
	}

	/**
	 * CustomerContact
	 */
	class CustomerContact {
		/**
		 * @var int
		 */
		public $CustomerContactID;
		/**
		 * @var string
		 */
		public $ContactNumber;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var string
		 */
		public $ContactName;
		/**
		 * @var string
		 */
		public $Address1;
		/**
		 * @var string
		 */
		public $Address2;
		/**
		 * @var string
		 */
		public $Zip;
		/**
		 * @var string
		 */
		public $City;
		/**
		 * @var string
		 */
		public $Phone;
		/**
		 * @var string
		 */
		public $Mobile;
		/**
		 * @var string
		 */
		public $Fax;
		/**
		 * @var string
		 */
		public $Email;
		/**
		 * @var string
		 */
		public $Position;
		/**
		 * @var string
		 */
		public $Loginpass;
		/**
		 * @var string
		 */
		public $Notes;
		/**
		 * @var string
		 */
		public $CustomerGroupName;
		/**
		 * @var bool
		 */
		public $PublicGroup;
		/**
		 * @var string
		 */
		public $CivicRegistrationNumber;
		/**
		 * @var mixed
		 */
		var $CanLogin;
		/**
		 * @var mixed
		 */
		var $Attribute;

		/**
		 * CustomerContact constructor
		 */
		public function __construct() {
			$this->CustomerContactID = 0;
			$this->CustomerID        = 0;
			$this->PublicGroup       = false;
			$this->CanLogin          = null;
			$this->Attribute         = array();
		}
	}

	/**
	 * CustomerContactAttribute
	 */
	class CustomerContactAttribute {
		/**
		 * @var int
		 */
		public $CustomerContactID;
		/**
		 * @var int|null
		 */
		public $CustomerContactAttributeID;
		/**
		 * @var bool
		 */
		public $AttributeChecked;
		/**
		 * @var mixed
		 */
		var $AttributeDate;
		/**
		 * @var int
		 */
		public $AttributeID;
		/**
		 * @var int
		 */
		public $AttributeTypeID;
		/**
		 * @var string
		 */
		public $AttributeTypeDescription;
		/**
		 * @var int
		 */
		public $AttributeOwnerTypeID;
		/**
		 * @var string
		 */
		public $AttributeOwnerTypeDescription;
		/**
		 * @var string
		 */
		public $AttributeDescription;
		/**
		 * @var string
		 */
		public $AttributeValue;
		/**
		 * @var mixed
		 */
		var $AttributeAlternative;

		/**
		 * CustomerContactAttribute constructor
		 */
		public function __construct() {
			$this->CustomerContactID          = 0;
			$this->CustomerContactAttributeID = null;
			$this->AttributeChecked           = false;
			$this->AttributeDate              = null;
			$this->AttributeID                = 0;
			$this->AttributeTypeID            = 0;
			$this->AttributeOwnerTypeID       = 0;
			$this->AttributeAlternative       = array();
		}
	}

	/**
	 * CustomerContactV2
	 */
	class CustomerContactV2 {
		/**
		 * @var string
		 */
		public $PurchaseOrderNumber;
		/**
		 * @var string
		 */
		public $ContactReference;
		/**
		 * @var string
		 */
		public $Country;
		/**
		 * @var int
		 */
		public $CustomerContactID;
		/**
		 * @var string
		 */
		public $ContactNumber;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var string
		 */
		public $ContactName;
		/**
		 * @var string
		 */
		public $Address1;
		/**
		 * @var string
		 */
		public $Address2;
		/**
		 * @var string
		 */
		public $Zip;
		/**
		 * @var string
		 */
		public $City;
		/**
		 * @var string
		 */
		public $Phone;
		/**
		 * @var string
		 */
		public $Mobile;
		/**
		 * @var string
		 */
		public $Fax;
		/**
		 * @var string
		 */
		public $Email;
		/**
		 * @var string
		 */
		public $Position;
		/**
		 * @var string
		 */
		public $Loginpass;
		/**
		 * @var string
		 */
		public $Notes;
		/**
		 * @var string
		 */
		public $CustomerGroupName;
		/**
		 * @var bool
		 */
		public $PublicGroup;
		/**
		 * @var string
		 */
		public $CivicRegistrationNumber;
		/**
		 * @var mixed
		 */
		var $CanLogin;
		/**
		 * @var mixed
		 */
		var $Attribute;

		/**
		 * CustomerContactV2 constructor
		 */
		public function __construct() {
			$this->CustomerContactID = 0;
			$this->CustomerID        = 0;
			$this->PublicGroup       = false;
			$this->CanLogin          = null;
			$this->Attribute         = array();
		}
	}

	/**
	 * CustomerEventPrice
	 */
	class CustomerEventPrice {
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var mixed
		 */
		var $CustomerPrice;
		/**
		 * @var mixed
		 */
		var $CustomerSubEventPrices;

		/**
		 * CustomerEventPrice constructor
		 */
		public function __construct() {
			$this->EventID                = 0;
			$this->CustomerPrice          = null;
			$this->CustomerSubEventPrices = array();
		}
	}

	/**
	 * CustomerGroup
	 */
	class CustomerGroup {
		/**
		 * @var int
		 */
		public $CustomerGroupID;
		/**
		 * @var string
		 */
		public $CustomerGroupName;
		/**
		 * @var string
		 */
		public $CustomerGroupCode;
		/**
		 * @var int
		 */
		public $ParentCustomerGroupID;
		/**
		 * @var mixed
		 */
		var $DiscountPercent;
		/**
		 * @var bool
		 */
		public $PublicGroup;
		/**
		 * @var string
		 */
		public $PriceNameCode;

		/**
		 * CustomerGroup constructor
		 */
		public function __construct() {
			$this->CustomerGroupID       = 0;
			$this->ParentCustomerGroupID = 0;
			$this->DiscountPercent       = null;
			$this->PublicGroup           = false;
		}
	}

	/**
	 * CustomerPrice
	 */
	class CustomerPrice {
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var mixed
		 */
		var $Price;
		/**
		 * @var mixed
		 */
		var $CancelationFee;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var int
		 */
		public $ObjectCategoryID;

		/**
		 * CustomerPrice constructor
		 */
		public function __construct() {
			$this->CustomerID       = 0;
			$this->Price            = null;
			$this->CancelationFee   = null;
			$this->ObjectID         = 0;
			$this->ObjectCategoryID = 0;
		}
	}

	/**
	 * CustomerStatistics
	 */
	class CustomerStatistics {
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var mixed
		 */
		var $Value;

		/**
		 * CustomerStatistics constructor
		 */
		public function __construct() {
			$this->CustomerID = 0;
			$this->Value      = null;
		}
	}

	/**
	 * CustomerSubEventPrice
	 */
	class CustomerSubEventPrice {
		/**
		 * @var int
		 */
		public $SubEventID;
		/**
		 * @var mixed
		 */
		var $CustomerPrice;

		/**
		 * CustomerSubEventPrice constructor
		 */
		public function __construct() {
			$this->SubEventID    = 0;
			$this->CustomerPrice = null;
		}
	}

	/**
	 * CustomerV2
	 */
	class CustomerV2 {
		/**
		 * @var string
		 */
		public $InvoiceEmail;
		/**
		 * @var string
		 */
		public $OurReference;
		/**
		 * @var string
		 */
		public $EdiReference;
		/**
		 * @var string
		 */
		public $Notes;
		/**
		 * @var mixed
		 */
		var $CanLogin;
		/**
		 * @var mixed
		 */
		var $DiscountPercent;
		/**
		 * @var mixed
		 */
		var $ParticipantDiscountPercent;
		/**
		 * @var mixed
		 */
		var $NotCreditworthy;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var string
		 */
		public $CustomerNumber;
		/**
		 * @var string
		 */
		public $CustomerName;
		/**
		 * @var string
		 */
		public $Address1;
		/**
		 * @var string
		 */
		public $Address2;
		/**
		 * @var string
		 */
		public $Zip;
		/**
		 * @var string
		 */
		public $City;
		/**
		 * @var string
		 */
		public $Country;
		/**
		 * @var string
		 */
		public $Phone;
		/**
		 * @var string
		 */
		public $Mobile;
		/**
		 * @var string
		 */
		public $Fax;
		/**
		 * @var string
		 */
		public $Email;
		/**
		 * @var string
		 */
		public $Homepage;
		/**
		 * @var string
		 */
		public $InvoiceName;
		/**
		 * @var string
		 */
		public $InvoiceAddress1;
		/**
		 * @var string
		 */
		public $InvoiceAddress2;
		/**
		 * @var string
		 */
		public $InvoiceZip;
		/**
		 * @var string
		 */
		public $InvoiceCity;
		/**
		 * @var string
		 */
		public $InvoiceCountry;
		/**
		 * @var string
		 */
		public $InvoiceOrgnr;
		/**
		 * @var string
		 */
		public $CustomerGroupName;
		/**
		 * @var int|null
		 */
		public $CustomerGroupID;
		/**
		 * @var string
		 */
		public $Password;
		/**
		 * @var string
		 */
		public $InvoiceVatnr;
		/**
		 * @var string
		 */
		public $CustomerReference;
		/**
		 * @var mixed
		 */
		var $VatFree;
		/**
		 * @var mixed
		 */
		var $Attribute;

		/**
		 * CustomerV2 constructor
		 */
		public function __construct() {
			$this->CanLogin                   = null;
			$this->DiscountPercent            = null;
			$this->ParticipantDiscountPercent = null;
			$this->NotCreditworthy            = null;
			$this->CustomerID                 = 0;
			$this->CustomerGroupID            = null;
			$this->VatFree                    = null;
			$this->Attribute                  = array();
		}
	}

	/**
	 * CustomerV3
	 */
	class CustomerV3 {
		/**
		 * @var int|null
		 */
		public $InvoiceDeliveryMethodID;
		/**
		 * @var string
		 */
		public $InvoiceEmail;
		/**
		 * @var string
		 */
		public $OurReference;
		/**
		 * @var string
		 */
		public $EdiReference;
		/**
		 * @var string
		 */
		public $Notes;
		/**
		 * @var mixed
		 */
		var $CanLogin;
		/**
		 * @var mixed
		 */
		var $DiscountPercent;
		/**
		 * @var mixed
		 */
		var $ParticipantDiscountPercent;
		/**
		 * @var mixed
		 */
		var $NotCreditworthy;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var string
		 */
		public $CustomerNumber;
		/**
		 * @var string
		 */
		public $CustomerName;
		/**
		 * @var string
		 */
		public $Address1;
		/**
		 * @var string
		 */
		public $Address2;
		/**
		 * @var string
		 */
		public $Zip;
		/**
		 * @var string
		 */
		public $City;
		/**
		 * @var string
		 */
		public $Country;
		/**
		 * @var string
		 */
		public $Phone;
		/**
		 * @var string
		 */
		public $Mobile;
		/**
		 * @var string
		 */
		public $Fax;
		/**
		 * @var string
		 */
		public $Email;
		/**
		 * @var string
		 */
		public $Homepage;
		/**
		 * @var string
		 */
		public $InvoiceName;
		/**
		 * @var string
		 */
		public $InvoiceAddress1;
		/**
		 * @var string
		 */
		public $InvoiceAddress2;
		/**
		 * @var string
		 */
		public $InvoiceZip;
		/**
		 * @var string
		 */
		public $InvoiceCity;
		/**
		 * @var string
		 */
		public $InvoiceCountry;
		/**
		 * @var string
		 */
		public $InvoiceOrgnr;
		/**
		 * @var string
		 */
		public $CustomerGroupName;
		/**
		 * @var int|null
		 */
		public $CustomerGroupID;
		/**
		 * @var string
		 */
		public $Password;
		/**
		 * @var string
		 */
		public $InvoiceVatnr;
		/**
		 * @var string
		 */
		public $CustomerReference;
		/**
		 * @var mixed
		 */
		var $VatFree;
		/**
		 * @var mixed
		 */
		var $Attribute;

		/**
		 * CustomerV3 constructor
		 */
		public function __construct() {
			$this->InvoiceDeliveryMethodID    = null;
			$this->CanLogin                   = null;
			$this->DiscountPercent            = null;
			$this->ParticipantDiscountPercent = null;
			$this->NotCreditworthy            = null;
			$this->CustomerID                 = 0;
			$this->CustomerGroupID            = null;
			$this->VatFree                    = null;
			$this->Attribute                  = array();
		}
	}

	/**
	 * DocumentSentListEvent
	 */
	class DocumentSentListEvent {
		/**
		 * @var int
		 */
		public $DocumentID;
		/**
		 * @var string
		 */
		public $Email;
		/**
		 * @var mixed
		 */
		var $SendDate;
		/**
		 * @var bool
		 */
		public $Error;
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var int
		 */
		public $EventCustomerLnkID;

		/**
		 * DocumentSentListEvent constructor
		 */
		public function __construct() {
			$this->DocumentID         = 0;
			$this->SendDate           = date( 'c' );
			$this->Error              = false;
			$this->EventID            = 0;
			$this->EventCustomerLnkID = 0;
		}
	}

	/**
	 * EducationLevel
	 */
	class EducationLevel {
		/**
		 * @var int
		 */
		public $EducationLevelID;
		/**
		 * @var string
		 */
		public $Name;
		/**
		 * @var int|null
		 */
		public $Index;

		/**
		 * EducationLevel constructor
		 */
		public function __construct() {
			$this->EducationLevelID = 0;
			$this->Index            = null;
		}
	}

	/**
	 * EducationLevelObject
	 */
	class EducationLevelObject {
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var int
		 */
		public $EducationLevelID;
		/**
		 * @var string
		 */
		public $Name;
		/**
		 * @var int|null
		 */
		public $Index;

		/**
		 * EducationLevelObject constructor
		 */
		public function __construct() {
			$this->ObjectID         = 0;
			$this->EducationLevelID = 0;
			$this->Index            = null;
		}
	}

	/**
	 * EducationObject
	 */
	class EducationObject {
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var string
		 */
		public $CourseDescription;
		/**
		 * @var string
		 */
		public $CourseDescriptionShort;
		/**
		 * @var string
		 */
		public $CourseGoal;
		/**
		 * @var bool
		 */
		public $ShowOnWeb;
		/**
		 * @var string
		 */
		public $TargetGroup;
		/**
		 * @var string
		 */
		public $CourseAfter;
		/**
		 * @var string
		 */
		public $Prerequisites;
		/**
		 * @var string
		 */
		public $CategoryName;
		/**
		 * @var int
		 */
		public $CategoryID;
		/**
		 * @var string
		 */
		public $ImageUrl;
		/**
		 * @var int
		 */
		public $Days;
		/**
		 * @var string
		 */
		public $StartTime;
		/**
		 * @var string
		 */
		public $EndTime;
		/**
		 * @var string
		 */
		public $ItemNr;
		/**
		 * @var bool
		 */
		public $RequireCivicRegistrationNumber;
		/**
		 * @var int
		 */
		public $ParticipantDocumentID;
		/**
		 * @var string
		 */
		public $Quote;
		/**
		 * @var string
		 */
		public $Notes;
		/**
		 * @var string
		 */
		public $PublicName;
		/**
		 * @var string
		 */
		public $Department;
		/**
		 * @var int
		 */
		public $MaxParticipantNr;
		/**
		 * @var int
		 */
		public $MinParticipantNr;

		/**
		 * EducationObject constructor
		 */
		public function __construct() {
			$this->ObjectID                       = 0;
			$this->ShowOnWeb                      = false;
			$this->CategoryID                     = 0;
			$this->Days                           = 0;
			$this->RequireCivicRegistrationNumber = false;
			$this->ParticipantDocumentID          = 0;
			$this->MaxParticipantNr               = 0;
			$this->MinParticipantNr               = 0;
		}
	}

	/**
	 * EducationObjectV2
	 */
	class EducationObjectV2 {
		/**
		 * @var string
		 */
		public $Shortening;
		/**
		 * @var int|null
		 */
		public $SortIndex;
		/**
		 * @var int|null
		 */
		public $EducationLevelID;
		/**
		 * @var mixed
		 */
		var $Vat;
		/**
		 * @var mixed
		 */
		var $Subjects;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var string
		 */
		public $CourseDescription;
		/**
		 * @var string
		 */
		public $CourseDescriptionShort;
		/**
		 * @var string
		 */
		public $CourseGoal;
		/**
		 * @var bool
		 */
		public $ShowOnWeb;
		/**
		 * @var string
		 */
		public $TargetGroup;
		/**
		 * @var string
		 */
		public $CourseAfter;
		/**
		 * @var string
		 */
		public $Prerequisites;
		/**
		 * @var string
		 */
		public $CategoryName;
		/**
		 * @var int
		 */
		public $CategoryID;
		/**
		 * @var string
		 */
		public $ImageUrl;
		/**
		 * @var int
		 */
		public $Days;
		/**
		 * @var string
		 */
		public $StartTime;
		/**
		 * @var string
		 */
		public $EndTime;
		/**
		 * @var string
		 */
		public $ItemNr;
		/**
		 * @var bool
		 */
		public $RequireCivicRegistrationNumber;
		/**
		 * @var int
		 */
		public $ParticipantDocumentID;
		/**
		 * @var string
		 */
		public $Quote;
		/**
		 * @var string
		 */
		public $Notes;
		/**
		 * @var string
		 */
		public $PublicName;
		/**
		 * @var string
		 */
		public $Department;
		/**
		 * @var int
		 */
		public $MaxParticipantNr;
		/**
		 * @var int
		 */
		public $MinParticipantNr;

		/**
		 * EducationObjectV2 constructor
		 */
		public function __construct() {
			$this->SortIndex                      = null;
			$this->EducationLevelID               = null;
			$this->Vat                            = null;
			$this->Subjects                       = array();
			$this->ObjectID                       = 0;
			$this->ShowOnWeb                      = false;
			$this->CategoryID                     = 0;
			$this->Days                           = 0;
			$this->RequireCivicRegistrationNumber = false;
			$this->ParticipantDocumentID          = 0;
			$this->MaxParticipantNr               = 0;
			$this->MinParticipantNr               = 0;
		}
	}

	/**
	 * EducationSubject
	 */
	class EducationSubject {
		/**
		 * @var int
		 */
		public $SubjectID;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var string
		 */
		public $SubjectName;

		/**
		 * EducationSubject constructor
		 */
		public function __construct() {
			$this->SubjectID = 0;
			$this->ObjectID  = 0;
		}
	}

	/**
	 * Event
	 */
	class Event {
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var string
		 */
		public $CategoryName;
		/**
		 * @var string
		 */
		public $Description;
		/**
		 * @var int
		 */
		public $LocationID;
		/**
		 * @var int|null
		 */
		public $LocationAddressID;
		/**
		 * @var string
		 */
		public $City;
		/**
		 * @var string
		 */
		public $Notes;
		/**
		 * @var mixed
		 */
		var $PeriodStart;
		/**
		 * @var mixed
		 */
		var $PeriodEnd;
		/**
		 * @var string
		 */
		public $ImageUrl;
		/**
		 * @var int
		 */
		public $OccationID;
		/**
		 * @var int
		 */
		public $MaxParticipantNr;
		/**
		 * @var int
		 */
		public $TotalParticipantNr;
		/**
		 * @var bool
		 */
		public $ShowOnWeb;
		/**
		 * @var bool
		 */
		public $ShowOnWebInternal;
		/**
		 * @var int
		 */
		public $StatusID;
		/**
		 * @var string
		 */
		public $StatusText;
		/**
		 * @var string
		 */
		public $AddressName;
		/**
		 * @var bool
		 */
		public $ConfirmedAddress;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var bool
		 */
		public $UsePriceNameMaxParticipantNr;
		/**
		 * @var mixed
		 */
		var $LastApplicationDate;
		/**
		 * @var bool
		 */
		public $Seats;
		/**
		 * @var int[]
		 */
		public $PersonnelIDs;

		/**
		 * Event constructor
		 */
		public function __construct() {
			$this->EventID                      = 0;
			$this->ObjectID                     = 0;
			$this->LocationID                   = 0;
			$this->LocationAddressID            = null;
			$this->PeriodStart                  = date( 'c' );
			$this->PeriodEnd                    = date( 'c' );
			$this->OccationID                   = 0;
			$this->MaxParticipantNr             = 0;
			$this->TotalParticipantNr           = 0;
			$this->ShowOnWeb                    = false;
			$this->ShowOnWebInternal            = false;
			$this->StatusID                     = 0;
			$this->ConfirmedAddress             = false;
			$this->CustomerID                   = 0;
			$this->UsePriceNameMaxParticipantNr = false;
			$this->LastApplicationDate          = null;
			$this->Seats                        = false;
			$this->PersonnelIDs                 = array();
		}
	}

	/**
	 * EventAccessory
	 */
	class EventAccessory {
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var mixed
		 */
		var $StartDate;
		/**
		 * @var mixed
		 */
		var $EndDate;
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var mixed
		 */
		var $Cost;
		/**
		 * @var int
		 */
		public $Quantity;
		/**
		 * @var mixed
		 */
		var $ObjectPrice;
		/**
		 * @var string
		 */
		public $PublicName;
		/**
		 * @var mixed
		 */
		var $VatPercent;

		/**
		 * EventAccessory constructor
		 */
		public function __construct() {
			$this->ObjectID    = 0;
			$this->StartDate   = date( 'c' );
			$this->EndDate     = date( 'c' );
			$this->EventID     = 0;
			$this->Cost        = null;
			$this->Quantity    = 0;
			$this->ObjectPrice = null;
			$this->VatPercent  = null;
		}
	}

	/**
	 * EventBooking
	 */
	class EventBooking {
		/**
		 * @var int
		 */
		public $EventCustomerLnkID;
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var string
		 */
		public $EventDescription;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int
		 */
		public $CustomerContactID;
		/**
		 * @var mixed
		 */
		var $TotalPrice;
		/**
		 * @var int
		 */
		public $ParticipantNr;
		/**
		 * @var mixed
		 */
		var $Created;
		/**
		 * @var bool
		 */
		public $Paid;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var mixed
		 */
		var $PeriodStart;
		/**
		 * @var mixed
		 */
		var $PeriodEnd;
		/**
		 * @var bool
		 */
		public $Preliminary;
		/**
		 * @var string
		 */
		public $Notes;

		/**
		 * EventBooking constructor
		 */
		public function __construct() {
			$this->EventCustomerLnkID = 0;
			$this->EventID            = 0;
			$this->CustomerID         = 0;
			$this->CustomerContactID  = 0;
			$this->TotalPrice         = null;
			$this->ParticipantNr      = 0;
			$this->Created            = date( 'c' );
			$this->Paid               = false;
			$this->ObjectID           = 0;
			$this->PeriodStart        = date( 'c' );
			$this->PeriodEnd          = date( 'c' );
			$this->Preliminary        = false;
		}
	}

	/**
	 * EventBookingAnswer
	 */
	class EventBookingAnswer {
		/**
		 * @var int
		 */
		public $AnswerID;
		/**
		 * @var string
		 */
		public $AnswerText;
		/**
		 * @var int
		 */
		public $SortIndex;
		/**
		 * @var mixed
		 */
		var $Price;
		/**
		 * @var mixed
		 */
		var $VatPercent;
		/**
		 * @var string
		 */
		public $DefaultAnswerText;
		/**
		 * @var string
		 */
		public $DefaultAnswerTime;
		/**
		 * @var bool
		 */
		public $DefaultAlternative;

		/**
		 * EventBookingAnswer constructor
		 */
		public function __construct() {
			$this->AnswerID           = 0;
			$this->SortIndex          = 0;
			$this->Price              = null;
			$this->VatPercent         = null;
			$this->DefaultAlternative = false;
		}
	}

	/**
	 * EventBookingPostponedBillingDate
	 */
	class EventBookingPostponedBillingDate {
		/**
		 * @var int
		 */
		public $EventCustomerLnkID;
		/**
		 * @var mixed
		 */
		var $BillingDate;

		/**
		 * EventBookingPostponedBillingDate constructor
		 */
		public function __construct() {
			$this->EventCustomerLnkID = 0;
			$this->BillingDate        = null;
		}
	}

	/**
	 * EventBookingPriceName
	 */
	class EventBookingPriceName {
		/**
		 * @var int
		 */
		public $PriceNameID;
		/**
		 * @var string
		 */
		public $Description;
		/**
		 * @var mixed
		 */
		var $Price;
		/**
		 * @var mixed
		 */
		var $TotalPrice;
		/**
		 * @var int
		 */
		public $ParticipantNr;
		/**
		 * @var int
		 */
		public $EventCustomerLnkID;
		/**
		 * @var int
		 */
		public $OccationPriceNameLnkID;

		/**
		 * EventBookingPriceName constructor
		 */
		public function __construct() {
			$this->PriceNameID            = 0;
			$this->Price                  = null;
			$this->TotalPrice             = null;
			$this->ParticipantNr          = 0;
			$this->EventCustomerLnkID     = 0;
			$this->OccationPriceNameLnkID = 0;
		}
	}

	/**
	 * EventBookingPriceNameInfo
	 */
	class EventBookingPriceNameInfo {
		/**
		 * @var int
		 */
		public $occationPriceNameLnkID;
		/**
		 * @var int
		 */
		public $participantNr;

		/**
		 * EventBookingPriceNameInfo constructor
		 */
		public function __construct() {
			$this->occationPriceNameLnkID = 0;
			$this->participantNr          = 0;
		}
	}

	/**
	 * EventBookingQuestion
	 */
	class EventBookingQuestion {
		/**
		 * @var int
		 */
		public $QuestionID;
		/**
		 * @var string
		 */
		public $QuestionText;
		/**
		 * @var int
		 */
		public $QuestionTypeID;
		/**
		 * @var string
		 */
		public $QuestionTypeText;
		/**
		 * @var bool
		 */
		public $ShowExternal;
		/**
		 * @var string
		 */
		public $MetaType;
		/**
		 * @var bool
		 */
		public $AddNumberField;
		/**
		 * @var bool
		 */
		public $Time;
		/**
		 * @var int
		 */
		public $SortIndex;
		/**
		 * @var int|null
		 */
		public $CategoryID;
		/**
		 * @var string
		 */
		public $CategoryName;
		/**
		 * @var bool
		 */
		public $Mandatory;
		/**
		 * @var bool
		 */
		public $KeyQuestion;
		/**
		 * @var string
		 */
		public $ProductNumber;
		/**
		 * @var mixed
		 */
		var $Answers;

		/**
		 * EventBookingQuestion constructor
		 */
		public function __construct() {
			$this->QuestionID     = 0;
			$this->QuestionTypeID = 0;
			$this->ShowExternal   = false;
			$this->AddNumberField = false;
			$this->Time           = false;
			$this->SortIndex      = 0;
			$this->CategoryID     = null;
			$this->Mandatory      = false;
			$this->KeyQuestion    = false;
			$this->Answers        = array();
		}
	}

	/**
	 * EventBookingV2
	 */
	class EventBookingV2 {
		/**
		 * @var mixed
		 */
		var $TotalPriceIncVat;
		/**
		 * @var mixed
		 */
		var $TotalPriceExVat;
		/**
		 * @var mixed
		 */
		var $VatSum;
		/**
		 * @var mixed
		 */
		var $TotalDiscount;
		/**
		 * @var string
		 */
		public $CustomerReference;
		/**
		 * @var bool
		 */
		public $BookedFromWeb;
		/**
		 * @var int
		 */
		public $EventCustomerLnkID;
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var string
		 */
		public $EventDescription;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int
		 */
		public $CustomerContactID;
		/**
		 * @var mixed
		 */
		var $TotalPrice;
		/**
		 * @var int
		 */
		public $ParticipantNr;
		/**
		 * @var mixed
		 */
		var $Created;
		/**
		 * @var bool
		 */
		public $Paid;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var mixed
		 */
		var $PeriodStart;
		/**
		 * @var mixed
		 */
		var $PeriodEnd;
		/**
		 * @var bool
		 */
		public $Preliminary;
		/**
		 * @var string
		 */
		public $Notes;

		/**
		 * EventBookingV2 constructor
		 */
		public function __construct() {
			$this->TotalPriceIncVat   = null;
			$this->TotalPriceExVat    = null;
			$this->VatSum             = null;
			$this->TotalDiscount      = null;
			$this->BookedFromWeb      = false;
			$this->EventCustomerLnkID = 0;
			$this->EventID            = 0;
			$this->CustomerID         = 0;
			$this->CustomerContactID  = 0;
			$this->TotalPrice         = null;
			$this->ParticipantNr      = 0;
			$this->Created            = date( 'c' );
			$this->Paid               = false;
			$this->ObjectID           = 0;
			$this->PeriodStart        = date( 'c' );
			$this->PeriodEnd          = date( 'c' );
			$this->Preliminary        = false;
		}
	}

	/**
	 * EventCustomerAnswer
	 */
	class EventCustomerAnswer {
		/**
		 * @var int
		 */
		public $AnswerID;
		/**
		 * @var string
		 */
		public $AnswerText;
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var int
		 */
		public $EventCustomerLnkID;

		/**
		 * EventCustomerAnswer constructor
		 */
		public function __construct() {
			$this->AnswerID           = 0;
			$this->EventID            = 0;
			$this->EventCustomerLnkID = 0;
		}
	}

	/**
	 * EventCustomerAnswerV2
	 */
	class EventCustomerAnswerV2 {
		/**
		 * @var int|null
		 */
		public $AnswerNumber;
		/**
		 * @var string
		 */
		public $AnswerTime;
		/**
		 * @var int
		 */
		public $AnswerID;
		/**
		 * @var string
		 */
		public $AnswerText;
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var int
		 */
		public $EventCustomerLnkID;

		/**
		 * EventCustomerAnswerV2 constructor
		 */
		public function __construct() {
			$this->AnswerNumber       = null;
			$this->AnswerID           = 0;
			$this->EventID            = 0;
			$this->EventCustomerLnkID = 0;
		}
	}

	/**
	 * EventDate
	 */
	class EventDate {
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var mixed
		 */
		var $StartDate;
		/**
		 * @var mixed
		 */
		var $EndDate;

		/**
		 * EventDate constructor
		 */
		public function __construct() {
			$this->EventID   = 0;
			$this->StartDate = date( 'c' );
			$this->EndDate   = date( 'c' );
		}
	}

	/**
	 * EventParticipant
	 */
	class EventParticipant {
		/**
		 * @var int
		 */
		public $EventParticipantID;
		/**
		 * @var int
		 */
		public $PersonID;
		/**
		 * @var string
		 */
		public $PersonName;
		/**
		 * @var string
		 */
		public $PersonEmail;
		/**
		 * @var string
		 */
		public $PersonCivicRegistrationNumber;
		/**
		 * @var string
		 */
		public $PersonPhone;
		/**
		 * @var string
		 */
		public $PersonMobile;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int
		 */
		public $CustomerContactID;
		/**
		 * @var int
		 */
		public $EventCustomerLnkID;
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var mixed
		 */
		var $TotalPrice;
		/**
		 * @var int
		 */
		public $ParticipantNr;
		/**
		 * @var mixed
		 */
		var $Created;
		/**
		 * @var mixed
		 */
		var $Price;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var mixed
		 */
		var $Arrived;
		/**
		 * @var int
		 */
		public $GradeID;
		/**
		 * @var string
		 */
		public $GradeName;
		/**
		 * @var bool
		 */
		public $Paid;
		/**
		 * @var string
		 */
		public $Reference;
		/**
		 * @var int|null
		 */
		public $PaymentMethodID;
		/**
		 * @var string
		 */
		public $PaymentMethodName;
		/**
		 * @var mixed
		 */
		var $Canceled;
		/**
		 * @var mixed
		 */
		var $PeriodStart;
		/**
		 * @var mixed
		 */
		var $PeriodEnd;

		/**
		 * EventParticipant constructor
		 */
		public function __construct() {
			$this->EventParticipantID = 0;
			$this->PersonID           = 0;
			$this->CustomerID         = 0;
			$this->CustomerContactID  = 0;
			$this->EventCustomerLnkID = 0;
			$this->EventID            = 0;
			$this->TotalPrice         = null;
			$this->ParticipantNr      = 0;
			$this->Created            = date( 'c' );
			$this->Price              = null;
			$this->ObjectID           = 0;
			$this->Arrived            = null;
			$this->GradeID            = 0;
			$this->Paid               = false;
			$this->PaymentMethodID    = null;
			$this->Canceled           = null;
			$this->PeriodStart        = date( 'c' );
			$this->PeriodEnd          = date( 'c' );
		}
	}

	/**
	 * EventParticipantSubEvent
	 */
	class EventParticipantSubEvent {
		/**
		 * @var int
		 */
		public $EventParticipantID;
		/**
		 * @var mixed
		 */
		var $SubEvents;

		/**
		 * EventParticipantSubEvent constructor
		 */
		public function __construct() {
			$this->EventParticipantID = 0;
			$this->SubEvents          = array();
		}
	}

	/**
	 * EventParticipantV2
	 */
	class EventParticipantV2 {
		/**
		 * @var mixed
		 */
		var $CanceledDate;
		/**
		 * @var mixed
		 */
		var $GradeDate;
		/**
		 * @var bool
		 */
		public $GradeAfterRetest;
		/**
		 * @var string
		 */
		public $GradeComment;
		/**
		 * @var int
		 */
		public $EventParticipantID;
		/**
		 * @var int
		 */
		public $PersonID;
		/**
		 * @var string
		 */
		public $PersonName;
		/**
		 * @var string
		 */
		public $PersonEmail;
		/**
		 * @var string
		 */
		public $PersonCivicRegistrationNumber;
		/**
		 * @var string
		 */
		public $PersonPhone;
		/**
		 * @var string
		 */
		public $PersonMobile;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int
		 */
		public $CustomerContactID;
		/**
		 * @var int
		 */
		public $EventCustomerLnkID;
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var mixed
		 */
		var $TotalPrice;
		/**
		 * @var int
		 */
		public $ParticipantNr;
		/**
		 * @var mixed
		 */
		var $Created;
		/**
		 * @var mixed
		 */
		var $Price;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var mixed
		 */
		var $Arrived;
		/**
		 * @var int
		 */
		public $GradeID;
		/**
		 * @var string
		 */
		public $GradeName;
		/**
		 * @var bool
		 */
		public $Paid;
		/**
		 * @var string
		 */
		public $Reference;
		/**
		 * @var int|null
		 */
		public $PaymentMethodID;
		/**
		 * @var string
		 */
		public $PaymentMethodName;
		/**
		 * @var mixed
		 */
		var $Canceled;
		/**
		 * @var mixed
		 */
		var $PeriodStart;
		/**
		 * @var mixed
		 */
		var $PeriodEnd;

		/**
		 * EventParticipantV2 constructor
		 */
		public function __construct() {
			$this->CanceledDate       = null;
			$this->GradeDate          = null;
			$this->GradeAfterRetest   = false;
			$this->EventParticipantID = 0;
			$this->PersonID           = 0;
			$this->CustomerID         = 0;
			$this->CustomerContactID  = 0;
			$this->EventCustomerLnkID = 0;
			$this->EventID            = 0;
			$this->TotalPrice         = null;
			$this->ParticipantNr      = 0;
			$this->Created            = date( 'c' );
			$this->Price              = null;
			$this->ObjectID           = 0;
			$this->Arrived            = null;
			$this->GradeID            = 0;
			$this->Paid               = false;
			$this->PaymentMethodID    = null;
			$this->Canceled           = null;
			$this->PeriodStart        = date( 'c' );
			$this->PeriodEnd          = date( 'c' );
		}
	}

	/**
	 * EventPaymentMethod
	 */
	class EventPaymentMethod {
		/**
		 * @var int
		 */
		public $PaymentMethodID;
		/**
		 * @var string
		 */
		public $MethodName;
		/**
		 * @var int
		 */
		public $EventID;

		/**
		 * EventPaymentMethod constructor
		 */
		public function __construct() {
			$this->PaymentMethodID = 0;
			$this->EventID         = 0;
		}
	}

	/**
	 * EventPersonnelMessage
	 */
	class EventPersonnelMessage {
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var string
		 */
		public $PersonnelMessage;

		/**
		 * EventPersonnelMessage constructor
		 */
		public function __construct() {
			$this->EventID = 0;
		}
	}

	/**
	 * EventPersonnelObject
	 */
	class EventPersonnelObject {
		/**
		 * @var int
		 */
		public $PersonnelID;
		/**
		 * @var bool
		 */
		public $Confirmed;
		/**
		 * @var int
		 */
		public $OccationID;
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var string
		 */
		public $Description;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var int
		 */
		public $LocationID;
		/**
		 * @var int|null
		 */
		public $LocationAddressID;
		/**
		 * @var string
		 */
		public $AddressName;
		/**
		 * @var string
		 */
		public $City;
		/**
		 * @var mixed
		 */
		var $StartDate;
		/**
		 * @var mixed
		 */
		var $EndDate;
		/**
		 * @var int
		 */
		public $EventMaxParticipantNr;

		/**
		 * EventPersonnelObject constructor
		 */
		public function __construct() {
			$this->PersonnelID           = 0;
			$this->Confirmed             = false;
			$this->OccationID            = 0;
			$this->EventID               = 0;
			$this->LocationID            = 0;
			$this->LocationAddressID     = null;
			$this->StartDate             = date( 'c' );
			$this->EndDate               = date( 'c' );
			$this->EventMaxParticipantNr = 0;
		}
	}

	/**
	 * EventPersonnelObjectV2
	 */
	class EventPersonnelObjectV2 {
		/**
		 * @var string
		 */
		public $ConfirmMessage;
		/**
		 * @var int
		 */
		public $PersonnelID;
		/**
		 * @var bool
		 */
		public $Confirmed;
		/**
		 * @var int
		 */
		public $OccationID;
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var string
		 */
		public $Description;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var int
		 */
		public $LocationID;
		/**
		 * @var int|null
		 */
		public $LocationAddressID;
		/**
		 * @var string
		 */
		public $AddressName;
		/**
		 * @var string
		 */
		public $City;
		/**
		 * @var mixed
		 */
		var $StartDate;
		/**
		 * @var mixed
		 */
		var $EndDate;
		/**
		 * @var int
		 */
		public $EventMaxParticipantNr;

		/**
		 * EventPersonnelObjectV2 constructor
		 */
		public function __construct() {
			$this->PersonnelID           = 0;
			$this->Confirmed             = false;
			$this->OccationID            = 0;
			$this->EventID               = 0;
			$this->LocationID            = 0;
			$this->LocationAddressID     = null;
			$this->StartDate             = date( 'c' );
			$this->EndDate               = date( 'c' );
			$this->EventMaxParticipantNr = 0;
		}
	}

	/**
	 * EventProjectNumber
	 */
	class EventProjectNumber {
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var string
		 */
		public $ProjectNumber;

		/**
		 * EventProjectNumber constructor
		 */
		public function __construct() {
			$this->EventID = 0;
		}
	}

	/**
	 * EventQuestion
	 */
	class EventQuestion {
		/**
		 * @var int
		 */
		public $QuestionID;
		/**
		 * @var int
		 */
		public $AnswerID;
		/**
		 * @var string
		 */
		public $AnswerText;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var int
		 */
		public $OccationID;
		/**
		 * @var int
		 */
		public $EventID;

		/**
		 * EventQuestion constructor
		 */
		public function __construct() {
			$this->QuestionID = 0;
			$this->AnswerID   = 0;
			$this->ObjectID   = 0;
			$this->OccationID = 0;
			$this->EventID    = 0;
		}
	}

	/**
	 * EventSeat
	 */
	class EventSeat {
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var int
		 */
		public $SeatID;
		/**
		 * @var int
		 */
		public $RowID;
		/**
		 * @var int
		 */
		public $SeatSortIndex;
		/**
		 * @var int
		 */
		public $RowSortIndex;
		/**
		 * @var int
		 */
		public $Nr;
		/**
		 * @var bool
		 */
		public $Booked;
		/**
		 * @var bool
		 */
		public $Locked;
		/**
		 * @var bool
		 */
		public $Dead;
		/**
		 * @var int|null
		 */
		public $TicketID;

		/**
		 * EventSeat constructor
		 */
		public function __construct() {
			$this->EventID       = 0;
			$this->SeatID        = 0;
			$this->RowID         = 0;
			$this->SeatSortIndex = 0;
			$this->RowSortIndex  = 0;
			$this->Nr            = 0;
			$this->Booked        = false;
			$this->Locked        = false;
			$this->Dead          = false;
			$this->TicketID      = null;
		}
	}

	/**
	 * ExtraInfo
	 */
	class ExtraInfo {
		/**
		 * @var string
		 */
		public $Key;
		/**
		 * @var mixed
		 */
		var $Value;

		/**
		 * ExtraInfo constructor
		 */
		public function __construct() {
			$this->Value = null;
		}
	}

	/**
	 * Filter
	 */
	class Filter {
		/**
		 * @var mixed
		 */
		var $StatisticsType;
		/**
		 * @var mixed
		 */
		var $FromDate;
		/**
		 * @var mixed
		 */
		var $ToDate;

		/**
		 * Filter constructor
		 */
		public function __construct() {
			$this->StatisticsType = null;
			$this->FromDate       = date( 'c' );
			$this->ToDate         = date( 'c' );
		}
	}

	/**
	 * Grade
	 */
	class Grade {
		/**
		 * @var int
		 */
		public $GradeID;
		/**
		 * @var string
		 */
		public $GradeName;
		/**
		 * @var string
		 */
		public $GradeText;
		/**
		 * @var mixed
		 */
		var $GradeValue;

		/**
		 * Grade constructor
		 */
		public function __construct() {
			$this->GradeID    = 0;
			$this->GradeValue = null;
		}
	}

	/**
	 * InterestReg
	 */
	class InterestReg {
		/**
		 * @var int
		 */
		public $InterestRegID;
		/**
		 * @var int|null
		 */
		public $ObjectID;
		/**
		 * @var int|null
		 */
		public $EventID;
		/**
		 * @var string
		 */
		public $CompanyName;
		/**
		 * @var string
		 */
		public $ContactName;
		/**
		 * @var string
		 */
		public $Email;
		/**
		 * @var string
		 */
		public $Phone;
		/**
		 * @var string
		 */
		public $Mobile;
		/**
		 * @var string
		 */
		public $Notes;
		/**
		 * @var int|null
		 */
		public $ParticipantNr;
		/**
		 * @var bool
		 */
		public $Done;
		/**
		 * @var mixed
		 */
		var $Created;
		/**
		 * @var mixed
		 */
		var $EventBooking;

		/**
		 * InterestReg constructor
		 */
		public function __construct() {
			$this->InterestRegID = 0;
			$this->ObjectID      = null;
			$this->EventID       = null;
			$this->ParticipantNr = null;
			$this->Done          = false;
			$this->Created       = date( 'c' );
			$this->EventBooking  = null;
		}
	}

	/**
	 * InterestRegEvent
	 */
	class InterestRegEvent {
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var int|null
		 */
		public $ParticipantNr;
		/**
		 * @var string
		 */
		public $CompanyName;
		/**
		 * @var string
		 */
		public $ContactName;
		/**
		 * @var string
		 */
		public $Email;
		/**
		 * @var string
		 */
		public $Phone;
		/**
		 * @var string
		 */
		public $Mobile;
		/**
		 * @var string
		 */
		public $Notes;

		/**
		 * InterestRegEvent constructor
		 */
		public function __construct() {
			$this->ObjectID      = 0;
			$this->EventID       = 0;
			$this->ParticipantNr = null;
		}
	}

	/**
	 * InterestRegEventBooking
	 */
	class InterestRegEventBooking {
		/**
		 * @var mixed
		 */
		var $Participants;
		/**
		 * @var int
		 */
		public $NrOfUnnamedParticipants;
		/**
		 * @var mixed
		 */
		var $TotalPriceIncVat;
		/**
		 * @var mixed
		 */
		var $TotalPriceExVat;
		/**
		 * @var mixed
		 */
		var $VatSum;
		/**
		 * @var mixed
		 */
		var $TotalDiscount;
		/**
		 * @var string
		 */
		public $CustomerReference;
		/**
		 * @var bool
		 */
		public $BookedFromWeb;
		/**
		 * @var int
		 */
		public $EventCustomerLnkID;
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var string
		 */
		public $EventDescription;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int
		 */
		public $CustomerContactID;
		/**
		 * @var mixed
		 */
		var $TotalPrice;
		/**
		 * @var int
		 */
		public $ParticipantNr;
		/**
		 * @var mixed
		 */
		var $Created;
		/**
		 * @var bool
		 */
		public $Paid;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var mixed
		 */
		var $PeriodStart;
		/**
		 * @var mixed
		 */
		var $PeriodEnd;
		/**
		 * @var bool
		 */
		public $Preliminary;
		/**
		 * @var string
		 */
		public $Notes;

		/**
		 * InterestRegEventBooking constructor
		 */
		public function __construct() {
			$this->Participants            = array();
			$this->NrOfUnnamedParticipants = 0;
			$this->TotalPriceIncVat        = null;
			$this->TotalPriceExVat         = null;
			$this->VatSum                  = null;
			$this->TotalDiscount           = null;
			$this->BookedFromWeb           = false;
			$this->EventCustomerLnkID      = 0;
			$this->EventID                 = 0;
			$this->CustomerID              = 0;
			$this->CustomerContactID       = 0;
			$this->TotalPrice              = null;
			$this->ParticipantNr           = 0;
			$this->Created                 = date( 'c' );
			$this->Paid                    = false;
			$this->ObjectID                = 0;
			$this->PeriodStart             = date( 'c' );
			$this->PeriodEnd               = date( 'c' );
			$this->Preliminary             = false;
		}
	}

	/**
	 * InterestRegObject
	 */
	class InterestRegObject {
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var int|null
		 */
		public $ParticipantNr;
		/**
		 * @var string
		 */
		public $CompanyName;
		/**
		 * @var string
		 */
		public $ContactName;
		/**
		 * @var string
		 */
		public $Email;
		/**
		 * @var string
		 */
		public $Phone;
		/**
		 * @var string
		 */
		public $Mobile;
		/**
		 * @var string
		 */
		public $Notes;

		/**
		 * InterestRegObject constructor
		 */
		public function __construct() {
			$this->ObjectID      = 0;
			$this->ParticipantNr = null;
		}
	}

	/**
	 * InterestRegParticipant
	 */
	class InterestRegParticipant {
		/**
		 * @var int
		 */
		public $EventParticipantID;
		/**
		 * @var int
		 */
		public $PersonID;
		/**
		 * @var string
		 */
		public $PersonName;
		/**
		 * @var string
		 */
		public $PersonEmail;
		/**
		 * @var string
		 */
		public $PersonCivicRegistrationNumber;
		/**
		 * @var string
		 */
		public $PersonPhone;
		/**
		 * @var string
		 */
		public $PersonMobile;
		/**
		 * @var string
		 */
		public $PersonAddress1;
		/**
		 * @var string
		 */
		public $PersonAddress2;
		/**
		 * @var string
		 */
		public $PersonZip;
		/**
		 * @var string
		 */
		public $PersonCity;

		/**
		 * InterestRegParticipant constructor
		 */
		public function __construct() {
			$this->EventParticipantID = 0;
			$this->PersonID           = 0;
		}
	}

	/**
	 * InterestRegReturnObject
	 */
	class InterestRegReturnObject {
		/**
		 * @var int
		 */
		public $InterestRegID;
		/**
		 * @var int
		 */
		public $EventCustomerLnkID;

		/**
		 * InterestRegReturnObject constructor
		 */
		public function __construct() {
			$this->InterestRegID      = 0;
			$this->EventCustomerLnkID = 0;
		}
	}

	/**
	 * LimitedDiscount
	 */
	class LimitedDiscount {
		/**
		 * @var int
		 */
		public $LimitedDiscountID;
		/**
		 * @var int
		 */
		public $LimitedDiscountTypeID;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int|null
		 */
		public $CustomerContactID;
		/**
		 * @var int|null
		 */
		public $DiscountPercent;
		/**
		 * @var int|null
		 */
		public $CategoryID;
		/**
		 * @var mixed
		 */
		var $Price;
		/**
		 * @var int|null
		 */
		public $CreditStartValue;
		/**
		 * @var int|null
		 */
		public $CreditLeft;
		/**
		 * @var mixed
		 */
		var $Paid;
		/**
		 * @var string
		 */
		public $PublicName;
		/**
		 * @var int|null
		 */
		public $PaymentMethodID;
		/**
		 * @var mixed
		 */
		var $ValidFrom;
		/**
		 * @var mixed
		 */
		var $ValidTo;

		/**
		 * LimitedDiscount constructor
		 */
		public function __construct() {
			$this->LimitedDiscountID     = 0;
			$this->LimitedDiscountTypeID = 0;
			$this->CustomerID            = 0;
			$this->CustomerContactID     = null;
			$this->DiscountPercent       = null;
			$this->CategoryID            = null;
			$this->Price                 = null;
			$this->CreditStartValue      = null;
			$this->CreditLeft            = null;
			$this->Paid                  = null;
			$this->PaymentMethodID       = null;
			$this->ValidFrom             = null;
			$this->ValidTo               = null;
		}
	}

	/**
	 * LimitedDiscountObjectStatus
	 */
	class LimitedDiscountObjectStatus {
		/**
		 * @var int
		 */
		public $CreditCount;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var int
		 */
		public $LimitedDiscountID;

		/**
		 * LimitedDiscountObjectStatus constructor
		 */
		public function __construct() {
			$this->CreditCount       = 0;
			$this->ObjectID          = 0;
			$this->LimitedDiscountID = 0;
		}
	}

	/**
	 * LimitedDiscountType
	 */
	class LimitedDiscountType {
		/**
		 * @var int
		 */
		public $LimitedDiscountTypeID;
		/**
		 * @var string
		 */
		public $LimitedDiscountDescription;
		/**
		 * @var bool
		 */
		public $IndividualBooking;
		/**
		 * @var mixed
		 */
		var $Price;
		/**
		 * @var int
		 */
		public $DiscountPercent;
		/**
		 * @var bool
		 */
		public $ShowPublic;
		/**
		 * @var string
		 */
		public $PublicName;
		/**
		 * @var int
		 */
		public $CreditCount;
		/**
		 * @var mixed
		 */
		var $ValidFrom;
		/**
		 * @var mixed
		 */
		var $ValidTo;
		/**
		 * @var int|null
		 */
		public $DocumentID;

		/**
		 * LimitedDiscountType constructor
		 */
		public function __construct() {
			$this->LimitedDiscountTypeID = 0;
			$this->IndividualBooking     = false;
			$this->Price                 = null;
			$this->DiscountPercent       = 0;
			$this->ShowPublic            = false;
			$this->CreditCount           = 0;
			$this->ValidFrom             = null;
			$this->ValidTo               = null;
			$this->DocumentID            = null;
		}
	}

	/**
	 * LMSObject
	 */
	class LMSObject {
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var string
		 */
		public $CourseDescription;
		/**
		 * @var string
		 */
		public $CourseDescriptionShort;
		/**
		 * @var string
		 */
		public $CourseGoal;
		/**
		 * @var bool
		 */
		public $ShowOnWeb;
		/**
		 * @var string
		 */
		public $TargetGroup;
		/**
		 * @var string
		 */
		public $CourseAfter;
		/**
		 * @var string
		 */
		public $Prerequisites;
		/**
		 * @var string
		 */
		public $CategoryName;
		/**
		 * @var int
		 */
		public $CategoryID;
		/**
		 * @var string
		 */
		public $ImageUrl;
		/**
		 * @var int
		 */
		public $Days;
		/**
		 * @var string
		 */
		public $StartTime;
		/**
		 * @var string
		 */
		public $EndTime;
		/**
		 * @var string
		 */
		public $ItemNr;
		/**
		 * @var bool
		 */
		public $RequireCivicRegistrationNumber;
		/**
		 * @var int
		 */
		public $ParticipantDocumentID;
		/**
		 * @var string
		 */
		public $Quote;
		/**
		 * @var string
		 */
		public $Notes;
		/**
		 * @var string
		 */
		public $PublicName;
		/**
		 * @var string
		 */
		public $Department;
		/**
		 * @var int
		 */
		public $MaxParticipantNr;
		/**
		 * @var int
		 */
		public $MinParticipantNr;

		/**
		 * LMSObject constructor
		 */
		public function __construct() {
			$this->ObjectID                       = 0;
			$this->ShowOnWeb                      = false;
			$this->CategoryID                     = 0;
			$this->Days                           = 0;
			$this->RequireCivicRegistrationNumber = false;
			$this->ParticipantDocumentID          = 0;
			$this->MaxParticipantNr               = 0;
			$this->MinParticipantNr               = 0;
		}
	}

	/**
	 * Location
	 */
	class Location {
		/**
		 * @var int
		 */
		public $LocationID;
		/**
		 * @var string
		 */
		public $City;
		/**
		 * @var string
		 */
		public $XPos;
		/**
		 * @var string
		 */
		public $YPos;
		/**
		 * @var bool
		 */
		public $PublicLocation;
		/**
		 * @var string
		 */
		public $CostCenter;
		/**
		 * @var string
		 */
		public $LocationNotes;
		/**
		 * @var int|null
		 */
		public $RegionID;

		/**
		 * Location constructor
		 */
		public function __construct() {
			$this->LocationID     = 0;
			$this->PublicLocation = false;
			$this->RegionID       = null;
		}
	}

	/**
	 * LocationAddress
	 */
	class LocationAddress {
		/**
		 * @var int
		 */
		public $LocationAddressID;
		/**
		 * @var int
		 */
		public $LocationID;
		/**
		 * @var string
		 */
		public $Name;
		/**
		 * @var string
		 */
		public $Address;
		/**
		 * @var string
		 */
		public $Zip;
		/**
		 * @var string
		 */
		public $City;
		/**
		 * @var string
		 */
		public $Phone;
		/**
		 * @var string
		 */
		public $Fax;
		/**
		 * @var string
		 */
		public $Email;
		/**
		 * @var string
		 */
		public $Notes;
		/**
		 * @var string
		 */
		public $InterestRegEmail;
		/**
		 * @var mixed
		 */
		var $Cost;
		/**
		 * @var string
		 */
		public $Homepage;

		/**
		 * LocationAddress constructor
		 */
		public function __construct() {
			$this->LocationAddressID = 0;
			$this->LocationID        = 0;
			$this->Cost              = null;
		}
	}

	/**
	 * NamedParticipant
	 */
	class NamedParticipant {
		/**
		 * @var int
		 */
		public $EventParticipantID;
		/**
		 * @var int|null
		 */
		public $PersonID;
		/**
		 * @var string
		 */
		public $PersonName;
		/**
		 * @var string
		 */
		public $PersonEmail;
		/**
		 * @var string
		 */
		public $PersonPhone;
		/**
		 * @var string
		 */
		public $PersonMobile;
		/**
		 * @var string
		 */
		public $PersonCivicRegistrationNumber;
		/**
		 * @var string
		 */
		public $PersonAddress1;
		/**
		 * @var string
		 */
		public $PersonAddress2;
		/**
		 * @var string
		 */
		public $PersonZip;
		/**
		 * @var string
		 */
		public $PersonCity;
		/**
		 * @var string
		 */
		public $PersonPosition;
		/**
		 * @var string
		 */
		public $PersonEmployeeNumber;
		/**
		 * @var string
		 */
		public $Reference;
		/**
		 * @var mixed
		 */
		var $SubEvents;
		/**
		 * @var mixed
		 */
		var $Attribute;

		/**
		 * NamedParticipant constructor
		 */
		public function __construct() {
			$this->EventParticipantID = 0;
			$this->PersonID           = null;
			$this->SubEvents          = array();
			$this->Attribute          = array();
		}
	}

	/**
	 * ObjectAttribute
	 */
	class ObjectAttribute {
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var bool
		 */
		public $AttributeChecked;
		/**
		 * @var int
		 */
		public $AttributeID;
		/**
		 * @var int
		 */
		public $AttributeTypeID;
		/**
		 * @var string
		 */
		public $AttributeTypeDescription;
		/**
		 * @var int
		 */
		public $AttributeOwnerTypeID;
		/**
		 * @var string
		 */
		public $AttributeOwnerTypeDescription;
		/**
		 * @var string
		 */
		public $AttributeDescription;
		/**
		 * @var string
		 */
		public $AttributeValue;
		/**
		 * @var mixed
		 */
		var $AttributeAlternative;

		/**
		 * ObjectAttribute constructor
		 */
		public function __construct() {
			$this->ObjectID             = 0;
			$this->AttributeChecked     = false;
			$this->AttributeID          = 0;
			$this->AttributeTypeID      = 0;
			$this->AttributeOwnerTypeID = 0;
			$this->AttributeAlternative = array();
		}
	}

	/**
	 * ObjectCategoryQuestion
	 */
	class ObjectCategoryQuestion {
		/**
		 * @var mixed
		 */
		var $VatPercent;
		/**
		 * @var int|null
		 */
		public $CategoryID;
		/**
		 * @var int|null
		 */
		public $ObjectID;
		/**
		 * @var bool
		 */
		public $Time;
		/**
		 * @var string
		 */
		public $MetaType;
		/**
		 * @var int
		 */
		public $QuestionID;
		/**
		 * @var string
		 */
		public $QuestionText;
		/**
		 * @var int
		 */
		public $QuestionTypeID;
		/**
		 * @var string
		 */
		public $QuestionTypeText;
		/**
		 * @var bool
		 */
		public $ShowExternal;
		/**
		 * @var int
		 */
		public $AnswerID;
		/**
		 * @var mixed
		 */
		var $Price;
		/**
		 * @var bool
		 */
		public $DefaultAlternative;
		/**
		 * @var string
		 */
		public $AnswerText;
		/**
		 * @var int
		 */
		public $SortIndex;

		/**
		 * ObjectCategoryQuestion constructor
		 */
		public function __construct() {
			$this->VatPercent         = null;
			$this->CategoryID         = null;
			$this->ObjectID           = null;
			$this->Time               = false;
			$this->QuestionID         = 0;
			$this->QuestionTypeID     = 0;
			$this->ShowExternal       = false;
			$this->AnswerID           = 0;
			$this->Price              = null;
			$this->DefaultAlternative = false;
			$this->SortIndex          = 0;
		}
	}

	/**
	 * ObjectFile
	 */
	class ObjectFile {
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var string
		 */
		public $FileName;
		/**
		 * @var mixed
		 */
		var $Created;
		/**
		 * @var string
		 */
		public $Comment;
		/**
		 * @var string
		 */
		public $FileUrl;

		/**
		 * ObjectFile constructor
		 */
		public function __construct() {
			$this->ObjectID = 0;
			$this->Created  = date( 'c' );
		}
	}

	/**
	 * ObjectPriceName
	 */
	class ObjectPriceName {
		/**
		 * @var int
		 */
		public $PriceNameID;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var mixed
		 */
		var $Price;
		/**
		 * @var bool
		 */
		public $PublicPriceName;
		/**
		 * @var string
		 */
		public $Description;

		/**
		 * ObjectPriceName constructor
		 */
		public function __construct() {
			$this->PriceNameID     = 0;
			$this->ObjectID        = 0;
			$this->Price           = null;
			$this->PublicPriceName = false;
		}
	}

	/**
	 * ObjectRule
	 */
	class ObjectRule {
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var string
		 */
		public $ObjectName;

		/**
		 * ObjectRule constructor
		 */
		public function __construct() {
			$this->ObjectID = 0;
		}
	}

	/**
	 * Person
	 */
	class Person {
		/**
		 * @var int
		 */
		public $PersonID;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int|null
		 */
		public $CustomerContactID;
		/**
		 * @var string
		 */
		public $PersonName;
		/**
		 * @var string
		 */
		public $PersonEmail;
		/**
		 * @var string
		 */
		public $PersonPhone;
		/**
		 * @var string
		 */
		public $PersonMobile;
		/**
		 * @var string
		 */
		public $PersonCivicRegistrationNumber;
		/**
		 * @var string
		 */
		public $PersonAddress1;
		/**
		 * @var string
		 */
		public $PersonAddress2;
		/**
		 * @var string
		 */
		public $PersonZip;
		/**
		 * @var string
		 */
		public $PersonCity;
		/**
		 * @var string
		 */
		public $PersonPosition;
		/**
		 * @var string
		 */
		public $PersonEmployeeNumber;
		/**
		 * @var mixed
		 */
		var $Attribute;

		/**
		 * Person constructor
		 */
		public function __construct() {
			$this->PersonID          = 0;
			$this->CustomerID        = 0;
			$this->CustomerContactID = null;
			$this->Attribute         = array();
		}
	}

	/**
	 * PersonAttribute
	 */
	class PersonAttribute {
		/**
		 * @var int
		 */
		public $PersonID;
		/**
		 * @var int|null
		 */
		public $PersonAttributeID;
		/**
		 * @var bool
		 */
		public $AttributeChecked;
		/**
		 * @var mixed
		 */
		var $AttributeDate;
		/**
		 * @var int
		 */
		public $AttributeID;
		/**
		 * @var int
		 */
		public $AttributeTypeID;
		/**
		 * @var string
		 */
		public $AttributeTypeDescription;
		/**
		 * @var int
		 */
		public $AttributeOwnerTypeID;
		/**
		 * @var string
		 */
		public $AttributeOwnerTypeDescription;
		/**
		 * @var string
		 */
		public $AttributeDescription;
		/**
		 * @var string
		 */
		public $AttributeValue;
		/**
		 * @var mixed
		 */
		var $AttributeAlternative;

		/**
		 * PersonAttribute constructor
		 */
		public function __construct() {
			$this->PersonID             = 0;
			$this->PersonAttributeID    = null;
			$this->AttributeChecked     = false;
			$this->AttributeDate        = null;
			$this->AttributeID          = 0;
			$this->AttributeTypeID      = 0;
			$this->AttributeOwnerTypeID = 0;
			$this->AttributeAlternative = array();
		}
	}

	/**
	 * PersonnelObject
	 */
	class PersonnelObject {
		/**
		 * @var int
		 */
		public $PersonnelID;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var string
		 */
		public $Address;
		/**
		 * @var string
		 */
		public $Zip;
		/**
		 * @var string
		 */
		public $City;
		/**
		 * @var string
		 */
		public $Country;
		/**
		 * @var string
		 */
		public $Phone;
		/**
		 * @var string
		 */
		public $Mobile;
		/**
		 * @var string
		 */
		public $Fax;
		/**
		 * @var string
		 */
		public $Email;
		/**
		 * @var string
		 */
		public $Password;
		/**
		 * @var string
		 */
		public $ImageUrl;
		/**
		 * @var string
		 */
		public $Notes;

		/**
		 * PersonnelObject constructor
		 */
		public function __construct() {
			$this->PersonnelID = 0;
			$this->ObjectID    = 0;
		}
	}

	/**
	 * PersonnelObjectTitle
	 */
	class PersonnelObjectTitle {
		/**
		 * @var int
		 */
		public $PersonnelID;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var string
		 */
		public $Title;

		/**
		 * PersonnelObjectTitle constructor
		 */
		public function __construct() {
			$this->PersonnelID = 0;
			$this->ObjectID    = 0;
		}
	}

	/**
	 * PriceName
	 */
	class PriceName {
		/**
		 * @var int
		 */
		public $OccationPriceNameLnkID;
		/**
		 * @var int
		 */
		public $PriceNameID;
		/**
		 * @var int
		 */
		public $OccationID;
		/**
		 * @var mixed
		 */
		var $Price;
		/**
		 * @var bool
		 */
		public $PublicPriceName;
		/**
		 * @var mixed
		 */
		var $DiscountPercent;
		/**
		 * @var int
		 */
		public $MaxPriceNameParticipantNr;
		/**
		 * @var int
		 */
		public $ParticipantNr;
		/**
		 * @var string
		 */
		public $Description;
		/**
		 * @var int
		 */
		public $PriceNameVat;
		/**
		 * @var string
		 */
		public $PriceNameCode;

		/**
		 * PriceName constructor
		 */
		public function __construct() {
			$this->OccationPriceNameLnkID    = 0;
			$this->PriceNameID               = 0;
			$this->OccationID                = 0;
			$this->Price                     = null;
			$this->PublicPriceName           = false;
			$this->DiscountPercent           = null;
			$this->MaxPriceNameParticipantNr = 0;
			$this->ParticipantNr             = 0;
			$this->PriceNameVat              = 0;
		}
	}

	/**
	 * PriceNameBookingInfo
	 */
	class PriceNameBookingInfo {
		/**
		 * @var int
		 */
		public $OccationPriceNameLnkID;
		/**
		 * @var int
		 */
		public $ParticipantNr;

		/**
		 * PriceNameBookingInfo constructor
		 */
		public function __construct() {
			$this->OccationPriceNameLnkID = 0;
			$this->ParticipantNr          = 0;
		}
	}

	/**
	 * Question
	 */
	class Question {
		/**
		 * @var int
		 */
		public $QuestionID;
		/**
		 * @var string
		 */
		public $QuestionText;
		/**
		 * @var int
		 */
		public $QuestionTypeID;
		/**
		 * @var string
		 */
		public $QuestionTypeText;
		/**
		 * @var bool
		 */
		public $ShowExternal;
		/**
		 * @var int
		 */
		public $AnswerID;
		/**
		 * @var mixed
		 */
		var $Price;
		/**
		 * @var bool
		 */
		public $DefaultAlternative;
		/**
		 * @var string
		 */
		public $AnswerText;
		/**
		 * @var int
		 */
		public $SortIndex;

		/**
		 * Question constructor
		 */
		public function __construct() {
			$this->QuestionID         = 0;
			$this->QuestionTypeID     = 0;
			$this->ShowExternal       = false;
			$this->AnswerID           = 0;
			$this->Price              = null;
			$this->DefaultAlternative = false;
			$this->SortIndex          = 0;
		}
	}

	/**
	 * Region
	 */
	class Region {
		/**
		 * @var int
		 */
		public $RegionID;
		/**
		 * @var string
		 */
		public $RegionName;

		/**
		 * Region constructor
		 */
		public function __construct() {
			$this->RegionID = 0;
		}
	}

	/**
	 * RentObject
	 */
	class RentObject {
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var string
		 */
		public $ItemNr;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var string
		 */
		public $PublicName;
		/**
		 * @var int
		 */
		public $CategoryID;
		/**
		 * @var int
		 */
		public $DepotID;
		/**
		 * @var string
		 */
		public $CategoryName;
		/**
		 * @var bool
		 */
		public $GroupObject;
		/**
		 * @var bool
		 */
		public $SalesObject;
		/**
		 * @var string
		 */
		public $BarcodreNr;
		/**
		 * @var string
		 */
		public $Notes;

		/**
		 * RentObject constructor
		 */
		public function __construct() {
			$this->ObjectID    = 0;
			$this->CategoryID  = 0;
			$this->DepotID     = 0;
			$this->GroupObject = false;
			$this->SalesObject = false;
		}
	}

	/**
	 * ReportDoc
	 */
	class ReportDoc {
		/**
		 * @var int
		 */
		public $ReportDocID;
		/**
		 * @var string
		 */
		public $ReportName;
		/**
		 * @var string
		 */
		public $PublicName;
		/**
		 * @var int
		 */
		public $ReportDocTypeID;

		/**
		 * ReportDoc constructor
		 */
		public function __construct() {
			$this->ReportDocID     = 0;
			$this->ReportDocTypeID = 0;
		}
	}

	/**
	 * ReportParameter
	 */
	class ReportParameter {
		/**
		 * @var string
		 */
		public $Name;
		/**
		 * @var string
		 */
		public $Value;

		/**
		 * ReportParameter constructor
		 */
		public function __construct() {
		}
	}

	/**
	 * SalesObjectBookingInfo
	 */
	class SalesObjectBookingInfo {
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var int
		 */
		public $Quantity;

		/**
		 * SalesObjectBookingInfo constructor
		 */
		public function __construct() {
			$this->ObjectID = 0;
			$this->Quantity = 0;
		}
	}

	/**
	 * SubEvent
	 */
	class SubEvent {
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var int
		 */
		public $ParentEventID;
		/**
		 * @var int
		 */
		public $OccasionID;
		/**
		 * @var int
		 */
		public $ObjectID;
		/**
		 * @var string
		 */
		public $ObjectName;
		/**
		 * @var string
		 */
		public $Description;
		/**
		 * @var mixed
		 */
		var $StartDate;
		/**
		 * @var mixed
		 */
		var $EndDate;
		/**
		 * @var int
		 */
		public $MaxParticipantNr;
		/**
		 * @var int
		 */
		public $TotalParticipantNr;
		/**
		 * @var bool
		 */
		public $SelectedByDefault;
		/**
		 * @var bool
		 */
		public $MandatoryParticipation;

		/**
		 * SubEvent constructor
		 */
		public function __construct() {
			$this->EventID                = 0;
			$this->ParentEventID          = 0;
			$this->OccasionID             = 0;
			$this->ObjectID               = 0;
			$this->StartDate              = date( 'c' );
			$this->EndDate                = date( 'c' );
			$this->MaxParticipantNr       = 0;
			$this->TotalParticipantNr     = 0;
			$this->SelectedByDefault      = false;
			$this->MandatoryParticipation = false;
		}
	}

	/**
	 * SubEventInfo
	 */
	class SubEventInfo {
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var int|null
		 */
		public $OccasionPriceNameLnkID;

		/**
		 * SubEventInfo constructor
		 */
		public function __construct() {
			$this->EventID                = 0;
			$this->OccasionPriceNameLnkID = null;
		}
	}

	/**
	 * SubEventPerson
	 */
	class SubEventPerson {
		/**
		 * @var mixed
		 */
		var $SubEvents;
		/**
		 * @var string
		 */
		public $Reference;
		/**
		 * @var int|null
		 */
		public $OccasionPriceNameLnkID;
		/**
		 * @var int
		 */
		public $PersonID;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int|null
		 */
		public $CustomerContactID;
		/**
		 * @var string
		 */
		public $PersonName;
		/**
		 * @var string
		 */
		public $PersonEmail;
		/**
		 * @var string
		 */
		public $PersonPhone;
		/**
		 * @var string
		 */
		public $PersonMobile;
		/**
		 * @var string
		 */
		public $PersonCivicRegistrationNumber;
		/**
		 * @var string
		 */
		public $PersonAddress1;
		/**
		 * @var string
		 */
		public $PersonAddress2;
		/**
		 * @var string
		 */
		public $PersonZip;
		/**
		 * @var string
		 */
		public $PersonCity;
		/**
		 * @var string
		 */
		public $PersonPosition;
		/**
		 * @var string
		 */
		public $PersonEmployeeNumber;
		/**
		 * @var mixed
		 */
		var $Attribute;

		/**
		 * SubEventPerson constructor
		 */
		public function __construct() {
			$this->SubEvents              = array();
			$this->OccasionPriceNameLnkID = null;
			$this->PersonID               = 0;
			$this->CustomerID             = 0;
			$this->CustomerContactID      = null;
			$this->Attribute              = array();
		}
	}

	/**
	 * SubEventSeatPerson
	 */
	class SubEventSeatPerson {
		/**
		 * @var mixed
		 */
		var $SubEvents;
		/**
		 * @var string
		 */
		public $Reference;
		/**
		 * @var int|null
		 */
		public $OccasionPriceNameLnkID;
		/**
		 * @var int|null
		 */
		public $SeatID;
		/**
		 * @var int
		 */
		public $PersonID;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var int|null
		 */
		public $CustomerContactID;
		/**
		 * @var string
		 */
		public $PersonName;
		/**
		 * @var string
		 */
		public $PersonEmail;
		/**
		 * @var string
		 */
		public $PersonPhone;
		/**
		 * @var string
		 */
		public $PersonMobile;
		/**
		 * @var string
		 */
		public $PersonCivicRegistrationNumber;
		/**
		 * @var string
		 */
		public $PersonAddress1;
		/**
		 * @var string
		 */
		public $PersonAddress2;
		/**
		 * @var string
		 */
		public $PersonZip;
		/**
		 * @var string
		 */
		public $PersonCity;
		/**
		 * @var string
		 */
		public $PersonPosition;
		/**
		 * @var string
		 */
		public $PersonEmployeeNumber;
		/**
		 * @var mixed
		 */
		var $Attribute;

		/**
		 * SubEventSeatPerson constructor
		 */
		public function __construct() {
			$this->SubEvents              = array();
			$this->OccasionPriceNameLnkID = null;
			$this->SeatID                 = null;
			$this->PersonID               = 0;
			$this->CustomerID             = 0;
			$this->CustomerContactID      = null;
			$this->Attribute              = array();
		}
	}

	/**
	 * Subject
	 */
	class Subject {
		/**
		 * @var int
		 */
		public $SubjectID;
		/**
		 * @var string
		 */
		public $SubjectName;
		/**
		 * @var string
		 */
		public $SubjectDescription;
		/**
		 * @var string
		 */
		public $SubjectTitle;
		/**
		 * @var string
		 */
		public $MetaDescription;

		/**
		 * Subject constructor
		 */
		public function __construct() {
			$this->SubjectID = 0;
		}
	}

	/**
	 * UnavailableDate
	 */
	class UnavailableDate {
		/**
		 * @var string
		 */
		public $Description;
		/**
		 * @var mixed
		 */
		var $StartDate;
		/**
		 * @var mixed
		 */
		var $EndDate;

		/**
		 * UnavailableDate constructor
		 */
		public function __construct() {
			$this->StartDate = date( 'c' );
			$this->EndDate   = date( 'c' );
		}
	}

	/**
	 * UnavailableDateResponse
	 */
	class UnavailableDateResponse {
		/**
		 * @var bool
		 */
		public $Success;
		/**
		 * @var string
		 */
		public $ErrorMessage;

		/**
		 * UnavailableDateResponse constructor
		 */
		public function __construct() {
			$this->Success = false;
		}
	}

	/**
	 * UnavailablePersonnelDate
	 */
	class UnavailablePersonnelDate {
		/**
		 * @var int
		 */
		public $PersonnelID;
		/**
		 * @var int
		 */
		public $UnavailableDateID;
		/**
		 * @var string
		 */
		public $Description;
		/**
		 * @var mixed
		 */
		var $StartDate;
		/**
		 * @var mixed
		 */
		var $EndDate;

		/**
		 * UnavailablePersonnelDate constructor
		 */
		public function __construct() {
			$this->PersonnelID       = 0;
			$this->UnavailableDateID = 0;
			$this->StartDate         = date( 'c' );
			$this->EndDate           = date( 'c' );
		}
	}

	/**
	 * UnnamedParticipant
	 */
	class UnnamedParticipant {
		/**
		 * @var int
		 */
		public $EventParticipantID;
		/**
		 * @var int
		 */
		public $EventCustomerLnkID;
		/**
		 * @var int
		 */
		public $EventID;
		/**
		 * @var int
		 */
		public $OccasionPriceNameLnkID;
		/**
		 * @var int
		 */
		public $Quantity;
		/**
		 * @var bool
		 */
		public $Canceled;
		/**
		 * @var int
		 */
		public $CustomerID;
		/**
		 * @var mixed
		 */
		var $Created;

		/**
		 * UnnamedParticipant constructor
		 */
		public function __construct() {
			$this->EventParticipantID     = 0;
			$this->EventCustomerLnkID     = 0;
			$this->EventID                = 0;
			$this->OccasionPriceNameLnkID = 0;
			$this->Quantity               = 0;
			$this->Canceled               = false;
			$this->CustomerID             = 0;
			$this->Created                = date( 'c' );
		}
	}

	/**
	 * UpdateSalesBookingInfo
	 */
	class UpdateSalesBookingInfo {
		/**
		 * @var int
		 */
		public $ObjectId;
		/**
		 * @var int
		 */
		public $Quantity;
		/**
		 * @var int
		 */
		public $EventCustomerLnkId;

		/**
		 * UpdateSalesBookingInfo constructor
		 */
		public function __construct() {
			$this->ObjectId           = 0;
			$this->Quantity           = 0;
			$this->EventCustomerLnkId = 0;
		}
	}

	/**
	 * UserAttribute
	 */
	class UserAttribute {
		/**
		 * @var int|null
		 */
		public $UserAttributeID;
		/**
		 * @var int
		 */
		public $UserID;
		/**
		 * @var bool
		 */
		public $AttributeChecked;
		/**
		 * @var mixed
		 */
		var $AttributeDate;
		/**
		 * @var int
		 */
		public $AttributeID;
		/**
		 * @var int
		 */
		public $AttributeTypeID;
		/**
		 * @var string
		 */
		public $AttributeTypeDescription;
		/**
		 * @var int
		 */
		public $AttributeOwnerTypeID;
		/**
		 * @var string
		 */
		public $AttributeOwnerTypeDescription;
		/**
		 * @var string
		 */
		public $AttributeDescription;
		/**
		 * @var string
		 */
		public $AttributeValue;
		/**
		 * @var mixed
		 */
		var $AttributeAlternative;

		/**
		 * UserAttribute constructor
		 */
		public function __construct() {
			$this->UserAttributeID      = null;
			$this->UserID               = 0;
			$this->AttributeChecked     = false;
			$this->AttributeDate        = null;
			$this->AttributeID          = 0;
			$this->AttributeTypeID      = 0;
			$this->AttributeOwnerTypeID = 0;
			$this->AttributeAlternative = array();
		}
	}

	/**
	 * UserLocation
	 */
	class UserLocation {
		/**
		 * @var int
		 */
		public $UserID;
		/**
		 * @var int
		 */
		public $LocationID;

		/**
		 * UserLocation constructor
		 */
		public function __construct() {
			$this->UserID     = 0;
			$this->LocationID = 0;
		}
	}