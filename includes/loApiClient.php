<?php
  const ServiceUrl = 'https://api.legaonline.se/api.asmx?WSDL'; // WSDL

  class EduAdminClient
  {
    protected $__server;
    public $debugTimers = false;
    public $debug = false;
    public $timers;

      public function __construct()
      {
      $this->timers = array();
      $this->timers['InitSoapClient'] = microtime(true);
      $this->__server = new SoapClient(
        ServiceUrl,
        array(
          'trace' => 0,
          'cache_wsdl' => WSDL_CACHE_BOTH
        )
      );
      $this->timers['InitSoapClient'] = microtime(true) - $this->timers['InitSoapClient'];
      }

    public function Book($authToken, $eventID, $customerID, $customerContactID, array $personIDs) {
      $param = array(
        'authToken' => $authToken,
        'eventID' => $eventID,
        'customerID' => $customerID,
        'customerContactID' => $customerContactID,
        'personIDs' => $personIDs
      );

      return $this->__callServer($param, 'Book');
    }

    public function BookIncCustomerReference($authToken, $eventID, $customerID, $customerContactID, $customerReference, array $personIDs) {
      $param = array(
        'authToken' => $authToken,
        'eventID' => $eventID,
        'customerID' => $customerID,
        'customerContactID' => $customerContactID,
        'customerReference' => $customerReference,
        'personIDs' => $personIDs
      );

      return $this->__callServer($param, 'BookIncCustomerReference');
    }

    public function BookIncPaymentMethod($authToken, $eventID, $customerID, $customerContactID, $paymentMethodID, array $personIDs) {
      $param = array(
        'authToken' => $authToken,
        'eventID' => $eventID,
        'customerID' => $customerID,
        'customerContactID' => $customerContactID,
        'paymentMethodID' => $paymentMethodID,
        'personIDs' => $personIDs
      );

      return $this->__callServer($param, 'BookIncPaymentMethod');
    }

    public function BookIncPriceName($authToken, $eventID, $customerID, $customerContactID, $customerReference, $paymentMethodID, $occasionPriceNameLnkID, array $personIDs) {
      $param = array(
        'authToken' => $authToken,
        'eventID' => $eventID,
        'customerID' => $customerID,
        'customerContactID' => $customerContactID,
        'customerReference' => $customerReference,
        'paymentMethodID' => $paymentMethodID,
        'occasionPriceNameLnkID' => $occasionPriceNameLnkID,
        'personIDs' => $personIDs
      );

      return $this->__callServer($param, 'BookIncPriceName');
    }

    public function BookPriceName($authToken, $eventID, $customerID, $customerContactID, $paymentMethodID, array $priceName) {
      $param = array(
        'authToken' => $authToken,
        'eventID' => $eventID,
        'customerID' => $customerID,
        'customerContactID' => $customerContactID,
        'paymentMethodID' => $paymentMethodID,
        'priceName' => $priceName
      );

      return $this->__callServer($param, 'BookPriceName');
    }

    public function BookSalesObject($authToken, $eclID, array $salesObjectBookingInfo) {
      $param = array(
        'authToken' => $authToken,
        'eclID' => $eclID,
        'salesObjectBookingInfo' => $salesObjectBookingInfo
      );

      return $this->__callServer($param, 'BookSalesObject');
    }

    public function BookSalesObjectXml($authToken, $eclID, $salesObjectBookingInfo) {
      $param = array(
        'authToken' => $authToken,
        'eclID' => $eclID,
        'salesObjectBookingInfo' => $salesObjectBookingInfo
      );

      return $this->__callServer($param, 'BookSalesObjectXml');
    }

    public function CheckCouponCode($authToken, $objectID, $categoryID, $code) {
      $param = array(
        'authToken' => $authToken,
        'objectID' => $objectID,
        'categoryID' => $categoryID,
        'code' => $code
      );

      return $this->__callServer($param, 'CheckCouponCode');
    }

    public function CreateBooking($authToken, $bookingInfo) {
      $param = array(
        'authToken' => $authToken,
        'bookingInfo' => $bookingInfo
      );

      return $this->__callServer($param, 'CreateBooking');
    }

    public function CreateBookingPriceName($authToken, $bookingInfo) {
      $param = array(
        'authToken' => $authToken,
        'bookingInfo' => $bookingInfo
      );

      return $this->__callServer($param, 'CreateBookingPriceName');
    }

    public function CreateBookingXml($authToken, $bookingInfo) {
      $param = array(
        'authToken' => $authToken,
        'bookingInfo' => $bookingInfo
      );

      return $this->__callServer($param, 'CreateBookingXml');
    }

    public function CreateParticipantFromUnnamed($authToken, array $namedParticipants) {
      $param = array(
        'authToken' => $authToken,
        'namedParticipants' => $namedParticipants
      );

      return $this->__getArray('int', $this->__callServer($param, 'CreateParticipantFromUnnamed'))->int;
    }

    public function CreateSeatBooking($authToken, $bookingInfo) {
      $param = array(
        'authToken' => $authToken,
        'bookingInfo' => $bookingInfo
      );

      return $this->__callServer($param, 'CreateSeatBooking');
    }

    public function CreateSubEventBooking($authToken, $bookingInfo) {
      $param = array(
        'authToken' => $authToken,
        'bookingInfo' => $bookingInfo
      );

      return $this->__callServer($param, 'CreateSubEventBooking');
    }

    public function CreateSubEventBookingXml($authToken, $bookingInfoSubEvent) {
      $param = array(
        'authToken' => $authToken,
        'bookingInfoSubEvent' => $bookingInfoSubEvent
      );

      return $this->__callServer($param, 'CreateSubEventBookingXml');
    }

    public function DeleteCustomerContact($authToken, array $customerContactIDs) {
      $param = array(
        'authToken' => $authToken,
        'customerContactIDs' => $customerContactIDs
      );

      return $this->__callServer($param, 'DeleteCustomerContact');
    }

    public function DeleteCustomerContactAttribute($authToken, array $customerContactAttributeIDs) {
      $param = array(
        'authToken' => $authToken,
        'customerContactAttributeIDs' => $customerContactAttributeIDs
      );

      return $this->__callServer($param, 'DeleteCustomerContactAttribute');
    }

    public function DeleteEventBooking($authToken, $eventCustomerLnkID) {
      $param = array(
        'authToken' => $authToken,
        'eventCustomerLnkID' => $eventCustomerLnkID
      );

      return $this->__callServer($param, 'DeleteEventBooking');
    }

    public function DeleteEventParticipant($authToken, $eventParticipantID) {
      $param = array(
        'authToken' => $authToken,
        'eventParticipantID' => $eventParticipantID
      );

      return $this->__callServer($param, 'DeleteEventParticipant');
    }

    public function DeleteEventParticipantSubEvent($authToken, array $subEventList) {
      $param = array(
        'authToken' => $authToken,
        'subEventList' => $subEventList
      );

      return $this->__callServer($param, 'DeleteEventParticipantSubEvent');
    }

    public function DeleteEventParticipantSubEventXml($authToken, $subEventList) {
      $param = array(
        'authToken' => $authToken,
        'subEventList' => $subEventList
      );

      return $this->__callServer($param, 'DeleteEventParticipantSubEventXml');
    }

    public function DeletePerson($authToken, array $personIDs) {
      $param = array(
        'authToken' => $authToken,
        'personIDs' => $personIDs
      );

      return $this->__callServer($param, 'DeletePerson');
    }

    public function DeleteUnavailablePersonnelDate($authToken, $unavailableDateID) {
      $param = array(
        'authToken' => $authToken,
        'unavailableDateID' => $unavailableDateID
      );

      return $this->__callServer($param, 'DeleteUnavailablePersonnelDate');
    }

    public function GetAccountInfo($authToken) {
      $param = array(
        'authToken' => $authToken
      );

      return $this->__getArray('AccountInfo', $this->__callServer($param, 'GetAccountInfo'))->AccountInfo;
    }

    public function GetAccountInfoXml($authToken) {
      $param = array(
        'authToken' => $authToken
      );

      return $this->__callServer($param, 'GetAccountInfoXml');
    }

    public function GetAccountSetting($authToken, $setting) {
      $param = array(
        'authToken' => $authToken,
        'setting' => $setting
      );

      return $this->__callServer($param, 'GetAccountSetting');
    }

    public function GetAccountSettings($authToken, array $settings) {
      $param = array(
        'authToken' => $authToken,
        'settings' => $settings
      );

      return $this->__getArray('AccountSettingsInfo', $this->__callServer($param, 'GetAccountSettings'))->AccountSettingsInfo;
    }

    public function GetAttribute($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('Attribute', $this->__callServer($param, 'GetAttribute'))->Attribute;
    }

    public function GetAttributeXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetAttributeXml');
    }

    public function GetAuthToken($userID, $hash) {
      $param = array(
        'userID' => $userID,
        'hash' => $hash
      );

      return $this->__callServer($param, 'GetAuthToken');
    }

    public function GetBookedEventAccessory($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('BookedEventAccessory', $this->__callServer($param, 'GetBookedEventAccessory'))->BookedEventAccessory;
    }

    public function GetCategory($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('Category', $this->__callServer($param, 'GetCategory'))->Category;
    }

    public function GetCategorySpecial($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('Category', $this->__callServer($param, 'GetCategorySpecial'))->Category;
    }

    public function GetCategorySpecialXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetCategorySpecialXml');
    }

    public function GetCategoryV3($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('CategoryV3', $this->__callServer($param, 'GetCategoryV3'))->CategoryV3;
    }

    public function GetCategoryXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetCategoryXml');
    }

    public function GetCertificate($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('Certificate', $this->__callServer($param, 'GetCertificate'))->Certificate;
    }

    public function GetCertificatePerson($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('CertificatePerson', $this->__callServer($param, 'GetCertificatePerson'))->CertificatePerson;
    }

    public function GetCertificatePersonV2($authToken, $sort, $filter, $includeSubjects) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter,
        'includeSubjects' => $includeSubjects
      );

      return $this->__getArray('CertificatePersonV2', $this->__callServer($param, 'GetCertificatePersonV2'))->CertificatePersonV2;
    }

    public function GetCertificatePersonV2Xml($authToken, $sort, $filter, $includeSubjects) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter,
        'includeSubjects' => $includeSubjects
      );

      return $this->__callServer($param, 'GetCertificatePersonV2Xml');
    }

    public function GetCertificatePersonXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetCertificatePersonXml');
    }

    public function GetCertificateXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetCertificateXml');
    }

    public function GetCompanyAttribute($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('CompanyAttribute', $this->__callServer($param, 'GetCompanyAttribute'))->CompanyAttribute;
    }

    public function GetCompanyAttributeXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetCompanyAttributeXml');
    }

    public function GetCompanyLogoUrl($authToken) {
      $param = array(
        'authToken' => $authToken
      );

      return $this->__callServer($param, 'GetCompanyLogoUrl');
    }

    public function GetConfirmationEmailMessage($authToken, $eclID, $documentID) {
      $param = array(
        'authToken' => $authToken,
        'eclID' => $eclID,
        'documentID' => $documentID
      );

      return $this->__callServer($param, 'GetConfirmationEmailMessage');
    }

    public function GetConfirmationEmailMessageXml($authToken, $eclID, $documentID) {
      $param = array(
        'authToken' => $authToken,
        'eclID' => $eclID,
        'documentID' => $documentID
      );

      return $this->__callServer($param, 'GetConfirmationEmailMessageXml');
    }

    public function GetCountries($authToken, $language) {
      $param = array(
        'authToken' => $authToken,
        'language' => $language
      );

      return $this->__getArray('Country', $this->__callServer($param, 'GetCountries'))->Country;
    }

    public function GetCountriesXml($authToken, $language) {
      $param = array(
        'authToken' => $authToken,
        'language' => $language
      );

      return $this->__callServer($param, 'GetCountriesXml');
    }

    public function GetCustomer($authToken, $sort, $filter, $includeAttributes) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter,
        'includeAttributes' => $includeAttributes
      );

      return $this->__getArray('Customer', $this->__callServer($param, 'GetCustomer'))->Customer;
    }

    public function GetCustomerAttribute($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('CustomerAttribute', $this->__callServer($param, 'GetCustomerAttribute'))->CustomerAttribute;
    }

    public function GetCustomerAttributeXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetCustomerAttributeXml');
    }

    public function GetCustomerContact($authToken, $sort, $filter, $includeAttributes) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter,
        'includeAttributes' => $includeAttributes
      );

      return $this->__getArray('CustomerContact', $this->__callServer($param, 'GetCustomerContact'))->CustomerContact;
    }

    public function GetCustomerContactAttribute($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('CustomerContactAttribute', $this->__callServer($param, 'GetCustomerContactAttribute'))->CustomerContactAttribute;
    }

    public function GetCustomerContactAttributeXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetCustomerContactAttributeXml');
    }

    public function GetCustomerContactV2($authToken, $sort, $filter, $includeAttributes) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter,
        'includeAttributes' => $includeAttributes
      );

      return $this->__getArray('CustomerContactV2', $this->__callServer($param, 'GetCustomerContactV2'))->CustomerContactV2;
    }

    public function GetCustomerContactV2Xml($authToken, $sort, $filter, $includeAttributes) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter,
        'includeAttributes' => $includeAttributes
      );

      return $this->__callServer($param, 'GetCustomerContactV2Xml');
    }

    public function GetCustomerContactXml($authToken, $sort, $filter, $includeAttributes) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter,
        'includeAttributes' => $includeAttributes
      );

      return $this->__callServer($param, 'GetCustomerContactXml');
    }

    public function GetCustomerEventPrice($authToken, $customerID, $eventID) {
      $param = array(
        'authToken' => $authToken,
        'customerID' => $customerID,
        'eventID' => $eventID
      );

      return $this->__callServer($param, 'GetCustomerEventPrice');
    }

    public function GetCustomerEventPriceXml($authToken, $customerID, $eventID) {
      $param = array(
        'authToken' => $authToken,
        'customerID' => $customerID,
        'eventID' => $eventID
      );

      return $this->__callServer($param, 'GetCustomerEventPriceXml');
    }

    public function GetCustomerExtraInfo($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('ExtraInfo', $this->__callServer($param, 'GetCustomerExtraInfo'))->ExtraInfo;
    }

    public function GetCustomerGroup($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('CustomerGroup', $this->__callServer($param, 'GetCustomerGroup'))->CustomerGroup;
    }

    public function GetCustomerGroupXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetCustomerGroupXml');
    }

    public function GetCustomerObjectPrices($authToken, $customerID, array $objectIds) {
      $param = array(
        'authToken' => $authToken,
        'customerID' => $customerID,
        'objectIds' => $objectIds
      );

      return $this->__getArray('CustomerPrice', $this->__callServer($param, 'GetCustomerObjectPrices'))->CustomerPrice;
    }

    public function GetCustomerObjectPricesXml($authToken, $customerID, array $objectIds) {
      $param = array(
        'authToken' => $authToken,
        'customerID' => $customerID,
        'objectIds' => $objectIds
      );

      return $this->__callServer($param, 'GetCustomerObjectPricesXml');
    }

    public function GetCustomerPrice($authToken, $customerID, $objectID) {
      $param = array(
        'authToken' => $authToken,
        'customerID' => $customerID,
        'objectID' => $objectID
      );

      return $this->__callServer($param, 'GetCustomerPrice');
    }

    public function GetCustomerPriceXml($authToken, $customerID, $objectID) {
      $param = array(
        'authToken' => $authToken,
        'customerID' => $customerID,
        'objectID' => $objectID
      );

      return $this->__callServer($param, 'GetCustomerPriceXml');
    }

    public function GetCustomerStatistics($authToken, $statisticsFilter, $top) {
      $param = array(
        'authToken' => $authToken,
        'statisticsFilter' => $statisticsFilter,
        'top' => $top
      );

      return $this->__getArray('CustomerStatistics', $this->__callServer($param, 'GetCustomerStatistics'))->CustomerStatistics;
    }

    public function GetCustomerStatisticsXml($authToken, $statisticsFilter, $top) {
      $param = array(
        'authToken' => $authToken,
        'statisticsFilter' => $statisticsFilter,
        'top' => $top
      );

      return $this->__callServer($param, 'GetCustomerStatisticsXml');
    }

    public function GetCustomerV2($authToken, $sort, $filter, $includeAttributes) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter,
        'includeAttributes' => $includeAttributes
      );

      return $this->__getArray('CustomerV2', $this->__callServer($param, 'GetCustomerV2'))->CustomerV2;
    }

    public function GetCustomerV2Xml($authToken, $sort, $filter, $includeAttributes) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter,
        'includeAttributes' => $includeAttributes
      );

      return $this->__callServer($param, 'GetCustomerV2Xml');
    }

    public function GetCustomerXml($authToken, $sort, $filter, $includeAttributes) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter,
        'includeAttributes' => $includeAttributes
      );

      return $this->__callServer($param, 'GetCustomerXml');
    }

    public function GetDefaultParticipantDocumentID($authToken, $eventID) {
      $param = array(
        'authToken' => $authToken,
        'eventID' => $eventID
      );

      return $this->__callServer($param, 'GetDefaultParticipantDocumentID');
    }

    public function GetDocumentSentList($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('DocumentSentListEvent', $this->__callServer($param, 'GetDocumentSentList'))->DocumentSentListEvent;
    }

    public function GetDocumentSentListXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetDocumentSentListXml');
    }

    public function GetEducationLevel($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EducationLevel', $this->__callServer($param, 'GetEducationLevel'))->EducationLevel;
    }

    public function GetEducationLevelObject($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EducationLevelObject', $this->__callServer($param, 'GetEducationLevelObject'))->EducationLevelObject;
    }

    public function GetEducationLevelObjectXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEducationLevelObjectXml');
    }

    public function GetEducationLevelXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEducationLevelXml');
    }

    public function GetEducationObject($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EducationObject', $this->__callServer($param, 'GetEducationObject'))->EducationObject;
    }

    public function GetEducationObjectV2($authToken, $sort, $filter, $includeSubjects) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter,
        'includeSubjects' => $includeSubjects
      );

      return $this->__getArray('EducationObjectV2', $this->__callServer($param, 'GetEducationObjectV2'))->EducationObjectV2;
    }

    public function GetEducationObjectV2Xml($authToken, $sort, $filter, $includeSubjects) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter,
        'includeSubjects' => $includeSubjects
      );

      return $this->__callServer($param, 'GetEducationObjectV2Xml');
    }

    public function GetEducationObjectXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEducationObjectXml');
    }

    public function GetEducationSubject($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EducationSubject', $this->__callServer($param, 'GetEducationSubject'))->EducationSubject;
    }

    public function GetEducationSubjectXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEducationSubjectXml');
    }

    public function GetEvent($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('Event', $this->__callServer($param, 'GetEvent'))->Event;
    }

    public function GetEventAccessory($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EventAccessory', $this->__callServer($param, 'GetEventAccessory'))->EventAccessory;
    }

    public function GetEventAccessoryXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventAccessoryXml');
    }

    public function GetEventBooking($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EventBooking', $this->__callServer($param, 'GetEventBooking'))->EventBooking;
    }

    public function GetEventBookingExtraInfo($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('ExtraInfo', $this->__callServer($param, 'GetEventBookingExtraInfo'))->ExtraInfo;
    }

    public function GetEventBookingPriceName($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EventBookingPriceName', $this->__callServer($param, 'GetEventBookingPriceName'))->EventBookingPriceName;
    }

    public function GetEventBookingPriceNameXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventBookingPriceNameXml');
    }

    public function GetEventBookingQuestion($authToken, $eventID) {
      $param = array(
        'authToken' => $authToken,
        'eventID' => $eventID
      );

      return $this->__getArray('EventBookingQuestion', $this->__callServer($param, 'GetEventBookingQuestion'))->EventBookingQuestion;
    }

    public function GetEventBookingQuestionXml($authToken, $eventID) {
      $param = array(
        'authToken' => $authToken,
        'eventID' => $eventID
      );

      return $this->__callServer($param, 'GetEventBookingQuestionXml');
    }

    public function GetEventBookingXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventBookingXml');
    }

    public function GetEventCustomerAnswer($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EventCustomerAnswer', $this->__callServer($param, 'GetEventCustomerAnswer'))->EventCustomerAnswer;
    }

    public function GetEventCustomerAnswerV2($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EventCustomerAnswerV2', $this->__callServer($param, 'GetEventCustomerAnswerV2'))->EventCustomerAnswerV2;
    }

    public function GetEventCustomerAnswerV2Xml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventCustomerAnswerV2Xml');
    }

    public function GetEventCustomerAnswerXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventCustomerAnswerXml');
    }

    public function GetEventDate($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EventDate', $this->__callServer($param, 'GetEventDate'))->EventDate;
    }

    public function GetEventDateXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventDateXml');
    }

    public function GetEventExtraInfo($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('ExtraInfo', $this->__callServer($param, 'GetEventExtraInfo'))->ExtraInfo;
    }

    public function GetEventParticipant($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EventParticipant', $this->__callServer($param, 'GetEventParticipant'))->EventParticipant;
    }

    public function GetEventParticipantSubEvent($authToken, array $eventParticipantIDs) {
      $param = array(
        'authToken' => $authToken,
        'eventParticipantIDs' => $eventParticipantIDs
      );

      return $this->__getArray('EventParticipantSubEvent', $this->__callServer($param, 'GetEventParticipantSubEvent'))->EventParticipantSubEvent;
    }

    public function GetEventParticipantSubEventXml($authToken, array $eventParticipantIDs) {
      $param = array(
        'authToken' => $authToken,
        'eventParticipantIDs' => $eventParticipantIDs
      );

      return $this->__callServer($param, 'GetEventParticipantSubEventXml');
    }

    public function GetEventParticipantV2($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EventParticipantV2', $this->__callServer($param, 'GetEventParticipantV2'))->EventParticipantV2;
    }

    public function GetEventParticipantV2Xml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventParticipantV2Xml');
    }

    public function GetEventParticipantXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventParticipantXml');
    }

    public function GetEventPaymentMethod($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EventPaymentMethod', $this->__callServer($param, 'GetEventPaymentMethod'))->EventPaymentMethod;
    }

    public function GetEventPaymentMethodXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventPaymentMethodXml');
    }

    public function GetEventPersonnelMessage($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EventPersonnelMessage', $this->__callServer($param, 'GetEventPersonnelMessage'))->EventPersonnelMessage;
    }

    public function GetEventPersonnelMessageXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventPersonnelMessageXml');
    }

    public function GetEventPersonnelObject($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EventPersonnelObject', $this->__callServer($param, 'GetEventPersonnelObject'))->EventPersonnelObject;
    }

    public function GetEventPersonnelObjectV2($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EventPersonnelObjectV2', $this->__callServer($param, 'GetEventPersonnelObjectV2'))->EventPersonnelObjectV2;
    }

    public function GetEventPersonnelObjectV2Xml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventPersonnelObjectV2Xml');
    }

    public function GetEventPersonnelObjectXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventPersonnelObjectXml');
    }

    public function GetEventProjectNumber($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EventProjectNumber', $this->__callServer($param, 'GetEventProjectNumber'))->EventProjectNumber;
    }

    public function GetEventProjectNumberXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventProjectNumberXml');
    }

    public function GetEventQuestion($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('EventQuestion', $this->__callServer($param, 'GetEventQuestion'))->EventQuestion;
    }

    public function GetEventQuestionXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventQuestionXml');
    }

    public function GetEventSeat($authToken, $objectID, $eventID) {
      $param = array(
        'authToken' => $authToken,
        'objectID' => $objectID,
        'eventID' => $eventID
      );

      return $this->__getArray('EventSeat', $this->__callServer($param, 'GetEventSeat'))->EventSeat;
    }

    public function GetEventXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetEventXml');
    }

    public function GetGetUserLocationXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetGetUserLocationXml');
    }

    public function GetGrade($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('Grade', $this->__callServer($param, 'GetGrade'))->Grade;
    }

    public function GetGradeXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetGradeXml');
    }

    public function GetInternalIPAddressString($authToken) {
      $param = array(
        'authToken' => $authToken
      );

      return $this->__callServer($param, 'GetInternalIPAddressString');
    }

    public function GetLimitedDiscount($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('LimitedDiscount', $this->__callServer($param, 'GetLimitedDiscount'))->LimitedDiscount;
    }

    public function GetLimitedDiscountObjectStatus($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('LimitedDiscountObjectStatus', $this->__callServer($param, 'GetLimitedDiscountObjectStatus'))->LimitedDiscountObjectStatus;
    }

    public function GetLimitedDiscountObjectStatusXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetLimitedDiscountObjectStatusXml');
    }

    public function GetLimitedDiscountType($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('LimitedDiscountType', $this->__callServer($param, 'GetLimitedDiscountType'))->LimitedDiscountType;
    }

    public function GetLimitedDiscountTypeXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetLimitedDiscountTypeXml');
    }

    public function GetLimitedDiscountXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetLimitedDiscountXml');
    }

    public function GetLMSObject($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('LMSObject', $this->__callServer($param, 'GetLMSObject'))->LMSObject;
    }

    public function GetLMSObjectXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetLMSObjectXml');
    }

    public function GetLocation($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('Location', $this->__callServer($param, 'GetLocation'))->Location;
    }

    public function GetLocationAddress($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('LocationAddress', $this->__callServer($param, 'GetLocationAddress'))->LocationAddress;
    }

    public function GetLocationAddressXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetLocationAddressXml');
    }

    public function GetLocationXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetLocationXml');
    }

    public function GetObjectAttribute($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('ObjectAttribute', $this->__callServer($param, 'GetObjectAttribute'))->ObjectAttribute;
    }

    public function GetObjectAttributeXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetObjectAttributeXml');
    }

    public function GetObjectCategoryQuestion($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('ObjectCategoryQuestion', $this->__callServer($param, 'GetObjectCategoryQuestion'))->ObjectCategoryQuestion;
    }

    public function GetObjectCategoryQuestionXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetObjectCategoryQuestionXml');
    }

    public function GetObjectFile($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('ObjectFile', $this->__callServer($param, 'GetObjectFile'))->ObjectFile;
    }

    public function GetObjectFileXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetObjectFileXml');
    }

    public function GetObjectPriceName($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('ObjectPriceName', $this->__callServer($param, 'GetObjectPriceName'))->ObjectPriceName;
    }

    public function GetObjectPriceNameXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetObjectPriceNameXml');
    }

    public function GetPerson($authToken, $sort, $filter, $includeAttributes) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter,
        'includeAttributes' => $includeAttributes
      );

      return $this->__getArray('Person', $this->__callServer($param, 'GetPerson'))->Person;
    }

    public function GetPersonAttribute($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('PersonAttribute', $this->__callServer($param, 'GetPersonAttribute'))->PersonAttribute;
    }

    public function GetPersonAttributeXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetPersonAttributeXml');
    }

    public function GetPersonnelObject($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('PersonnelObject', $this->__callServer($param, 'GetPersonnelObject'))->PersonnelObject;
    }

    public function GetPersonnelObjectTitle($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('PersonnelObjectTitle', $this->__callServer($param, 'GetPersonnelObjectTitle'))->PersonnelObjectTitle;
    }

    public function GetPersonnelObjectTitleXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetPersonnelObjectTitleXml');
    }

    public function GetPersonnelObjectXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetPersonnelObjectXml');
    }

    public function GetPersonXml($authToken, $sort, $filter, $includeAttributes) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter,
        'includeAttributes' => $includeAttributes
      );

      return $this->__callServer($param, 'GetPersonXml');
    }

    public function GetPriceName($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('PriceName', $this->__callServer($param, 'GetPriceName'))->PriceName;
    }

    public function GetPriceNameXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetPriceNameXml');
    }

    public function GetQuestion($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('Question', $this->__callServer($param, 'GetQuestion'))->Question;
    }

    public function GetQuestionXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetQuestionXml');
    }

    public function GetRegion($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('Region', $this->__callServer($param, 'GetRegion'))->Region;
    }

    public function GetRegionXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetRegionXml');
    }

    public function GetRentObject($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('RentObject', $this->__callServer($param, 'GetRentObject'))->RentObject;
    }

    public function GetRentObjectXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetRentObjectXml');
    }

    public function GetReportDoc($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('ReportDoc', $this->__callServer($param, 'GetReportDoc'))->ReportDoc;
    }

    public function GetReportDocXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetReportDocXml');
    }

    public function GetReportUrl($authToken, $reportID, $reportName, $showAsHtml, array $parameters) {
      $param = array(
        'authToken' => $authToken,
        'reportID' => $reportID,
        'reportName' => $reportName,
        'showAsHtml' => $showAsHtml,
        'parameters' => $parameters
      );

      return $this->__callServer($param, 'GetReportUrl');
    }

    public function GetReportUrlXml($authToken, $reportID, $reportName, $showAsHtml, $parameters) {
      $param = array(
        'authToken' => $authToken,
        'reportID' => $reportID,
        'reportName' => $reportName,
        'showAsHtml' => $showAsHtml,
        'parameters' => $parameters
      );

      return $this->__callServer($param, 'GetReportUrlXml');
    }

    public function GetSimilarCustomer($authToken, $customerName) {
      $param = array(
        'authToken' => $authToken,
        'customerName' => $customerName
      );

      return $this->__getArray('Customer', $this->__callServer($param, 'GetSimilarCustomer'))->Customer;
    }

    public function GetSimilarCustomerXml($authToken, $customerName) {
      $param = array(
        'authToken' => $authToken,
        'customerName' => $customerName
      );

      return $this->__callServer($param, 'GetSimilarCustomerXml');
    }

    public function GetSubEvent($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('SubEvent', $this->__callServer($param, 'GetSubEvent'))->SubEvent;
    }

    public function GetSubEventXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetSubEventXml');
    }

    public function GetUnavailablePersonnelDate($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('UnavailablePersonnelDate', $this->__callServer($param, 'GetUnavailablePersonnelDate'))->UnavailablePersonnelDate;
    }

    public function GetUnavailablePersonnelDateXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetUnavailablePersonnelDateXml');
    }

    public function GetUnnamedParticipant($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('UnnamedParticipant', $this->__callServer($param, 'GetUnnamedParticipant'))->UnnamedParticipant;
    }

    public function GetUserAttribute($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('UserAttribute', $this->__callServer($param, 'GetUserAttribute'))->UserAttribute;
    }

    public function GetUserAttributeXml($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__callServer($param, 'GetUserAttributeXml');
    }

    public function GetUserLocation($authToken, $sort, $filter) {
      $param = array(
        'authToken' => $authToken,
        'sort' => $sort,
        'filter' => $filter
      );

      return $this->__getArray('UserLocation', $this->__callServer($param, 'GetUserLocation'))->UserLocation;
    }

    public function GetValidCoupons($authToken, $objectID, $categoryID) {
      $param = array(
        'authToken' => $authToken,
        'objectID' => $objectID,
        'categoryID' => $categoryID
      );

      return $this->__getArray('Coupon', $this->__callServer($param, 'GetValidCoupons'))->Coupon;
    }

    public function RefreshEventBookingCustomerInfo($authToken, array $customerIDs, $fromEventStart, $updateReference) {
      $param = array(
        'authToken' => $authToken,
        'customerIDs' => $customerIDs,
        'fromEventStart' => $fromEventStart,
        'updateReference' => $updateReference
      );

      return $this->__getArray('int', $this->__callServer($param, 'RefreshEventBookingCustomerInfo'))->int;
    }

    public function RefreshSingleEventBookingCustomerInfo($authToken, $eventCustomerLnkID) {
      $param = array(
        'authToken' => $authToken,
        'eventCustomerLnkID' => $eventCustomerLnkID
      );

      return $this->__callServer($param, 'RefreshSingleEventBookingCustomerInfo');
    }

    public function SendConfirmationEmail($authToken, $eclID, $from, array $toAddresses) {
      $param = array(
        'authToken' => $authToken,
        'eclID' => $eclID,
        'from' => $from,
        'toAddresses' => $toAddresses
      );

      return $this->__getArray('string', $this->__callServer($param, 'SendConfirmationEmail'))->string;
    }

    public function SendConfirmationEmailAndCopy($authToken, $eclID, $from, array $toAddresses, array $toAddressesCopy) {
      $param = array(
        'authToken' => $authToken,
        'eclID' => $eclID,
        'from' => $from,
        'toAddresses' => $toAddresses,
        'toAddressesCopy' => $toAddressesCopy
      );

      return $this->__callServer($param, 'SendConfirmationEmailAndCopy');
    }

    public function SendConfirmationEmailCompanyTailored($authToken, $eclID, $from, array $toAddresses) {
      $param = array(
        'authToken' => $authToken,
        'eclID' => $eclID,
        'from' => $from,
        'toAddresses' => $toAddresses
      );

      return $this->__getArray('string', $this->__callServer($param, 'SendConfirmationEmailCompanyTailored'))->string;
    }

    public function SendConfirmationEmailDoc($authToken, $eclID, $participantDocumentID, $from, array $toAddresses) {
      $param = array(
        'authToken' => $authToken,
        'eclID' => $eclID,
        'participantDocumentID' => $participantDocumentID,
        'from' => $from,
        'toAddresses' => $toAddresses
      );

      return $this->__getArray('string', $this->__callServer($param, 'SendConfirmationEmailDoc'))->string;
    }

    public function SendCustomerContactPassword($authToken, $customerContactID, $strSenderDescription) {
      $param = array(
        'authToken' => $authToken,
        'customerContactID' => $customerContactID,
        'strSenderDescription' => $strSenderDescription
      );

      return $this->__callServer($param, 'SendCustomerContactPassword');
    }

    public function SendCustomerContactPasswordEnglish($authToken, $customerContactID, $strSenderDescription) {
      $param = array(
        'authToken' => $authToken,
        'customerContactID' => $customerContactID,
        'strSenderDescription' => $strSenderDescription
      );

      return $this->__callServer($param, 'SendCustomerContactPasswordEnglish');
    }

    public function SendCustomerPassword($authToken, $customerID, $strSenderDescription) {
      $param = array(
        'authToken' => $authToken,
        'customerID' => $customerID,
        'strSenderDescription' => $strSenderDescription
      );

      return $this->__callServer($param, 'SendCustomerPassword');
    }

    public function SendLimitedDiscountConfirmation($authToken, $limitedDiscountID, $documentID, $from, array $toAddresses) {
      $param = array(
        'authToken' => $authToken,
        'limitedDiscountID' => $limitedDiscountID,
        'documentID' => $documentID,
        'from' => $from,
        'toAddresses' => $toAddresses
      );

      return $this->__getArray('string', $this->__callServer($param, 'SendLimitedDiscountConfirmation'))->string;
    }

    public function SetBookPaidStatus($authToken, $eclID, $paid) {
      $param = array(
        'authToken' => $authToken,
        'eclID' => $eclID,
        'paid' => $paid
      );

      return $this->__callServer($param, 'SetBookPaidStatus');
    }

    public function SetCustomer($authToken, array $customer) {
      $param = array(
        'authToken' => $authToken,
        'customer' => $customer
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetCustomer'))->int;
    }

    public function SetCustomerAttribute($authToken, array $customerAttribute) {
      $param = array(
        'authToken' => $authToken,
        'customerAttribute' => $customerAttribute
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetCustomerAttribute'))->int;
    }

    public function SetCustomerAttributeXml($authToken, $customerAttribute) {
      $param = array(
        'authToken' => $authToken,
        'customerAttribute' => $customerAttribute
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetCustomerAttributeXml'))->int;
    }

    public function SetCustomerContact($authToken, array $customerContact) {
      $param = array(
        'authToken' => $authToken,
        'customerContact' => $customerContact
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetCustomerContact'))->int;
    }

    public function SetCustomerContactAttributes($authToken, array $customerContactAttribute) {
      $param = array(
        'authToken' => $authToken,
        'customerContactAttribute' => $customerContactAttribute
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetCustomerContactAttributes'))->int;
    }

    public function SetCustomerContactAttributesXml($authToken, $customerContactAttribute) {
      $param = array(
        'authToken' => $authToken,
        'customerContactAttribute' => $customerContactAttribute
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetCustomerContactAttributesXml'))->int;
    }

    public function SetCustomerContactV2($authToken, array $customerContact) {
      $param = array(
        'authToken' => $authToken,
        'customerContact' => $customerContact
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetCustomerContactV2'))->int;
    }

    public function SetCustomerContactV2Xml($authToken, $customerContact) {
      $param = array(
        'authToken' => $authToken,
        'customerContact' => $customerContact
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetCustomerContactV2Xml'))->int;
    }

    public function SetCustomerContactXml($authToken, $customerContact) {
      $param = array(
        'authToken' => $authToken,
        'customerContact' => $customerContact
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetCustomerContactXml'))->int;
    }

    public function SetCustomerExtraInfo($authToken, $customerId, array $extraInfo) {
      $param = array(
        'authToken' => $authToken,
        'customerId' => $customerId,
        'extraInfo' => $extraInfo
      );

      return $this->__callServer($param, 'SetCustomerExtraInfo');
    }

    public function SetCustomerV2($authToken, array $customer) {
      $param = array(
        'authToken' => $authToken,
        'customer' => $customer
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetCustomerV2'))->int;
    }

    public function SetCustomerV2Xml($authToken, $customer) {
      $param = array(
        'authToken' => $authToken,
        'customer' => $customer
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetCustomerV2Xml'))->int;
    }

    public function SetCustomerXml($authToken, $customer) {
      $param = array(
        'authToken' => $authToken,
        'customer' => $customer
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetCustomerXml'))->int;
    }

    public function SetEventBookingPostponedBillingDate($authToken, array $eventBookingPostponedDates) {
      $param = array(
        'authToken' => $authToken,
        'eventBookingPostponedDates' => $eventBookingPostponedDates
      );

      return $this->__callServer($param, 'SetEventBookingPostponedBillingDate');
    }

    public function SetEventBookingPostponedBillingDateXml($authToken, $eventBookingPostponedDates) {
      $param = array(
        'authToken' => $authToken,
        'eventBookingPostponedDates' => $eventBookingPostponedDates
      );

      return $this->__callServer($param, 'SetEventBookingPostponedBillingDateXml');
    }

    public function SetEventBookingPreliminaryStatus($authToken, $eventCustomerLnkID, $preliminary) {
      $param = array(
        'authToken' => $authToken,
        'eventCustomerLnkID' => $eventCustomerLnkID,
        'preliminary' => $preliminary
      );

      return $this->__callServer($param, 'SetEventBookingPreliminaryStatus');
    }

    public function SetEventBookingPricenameParticipantNr($authToken, $eventCustomerLnkID, array $lstEditPriceNames) {
      $param = array(
        'authToken' => $authToken,
        'eventCustomerLnkID' => $eventCustomerLnkID,
        'lstEditPriceNames' => $lstEditPriceNames
      );

      return $this->__callServer($param, 'SetEventBookingPricenameParticipantNr');
    }

    public function SetEventCustomerAnswer($authToken, array $eventCustomerAnswer) {
      $param = array(
        'authToken' => $authToken,
        'eventCustomerAnswer' => $eventCustomerAnswer
      );

      return $this->__callServer($param, 'SetEventCustomerAnswer');
    }

    public function SetEventCustomerAnswerV2($authToken, array $eventCustomerAnswer) {
      $param = array(
        'authToken' => $authToken,
        'eventCustomerAnswer' => $eventCustomerAnswer
      );

      return $this->__callServer($param, 'SetEventCustomerAnswerV2');
    }

    public function SetEventCustomerAnswerV2Xml($authToken, $eventCustomerAnswer) {
      $param = array(
        'authToken' => $authToken,
        'eventCustomerAnswer' => $eventCustomerAnswer
      );

      return $this->__callServer($param, 'SetEventCustomerAnswerV2Xml');
    }

    public function SetEventCustomerAnswerXml($authToken, $eventCustomerAnswer) {
      $param = array(
        'authToken' => $authToken,
        'eventCustomerAnswer' => $eventCustomerAnswer
      );

      return $this->__callServer($param, 'SetEventCustomerAnswerXml');
    }

    public function SetEventParticipant($authToken, array $eventParticipant) {
      $param = array(
        'authToken' => $authToken,
        'eventParticipant' => $eventParticipant
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetEventParticipant'))->int;
    }

    public function SetEventParticipantArrivedStatus($authToken, $eventParticipantID, $arrived) {
      $param = array(
        'authToken' => $authToken,
        'eventParticipantID' => $eventParticipantID,
        'arrived' => $arrived
      );

      return $this->__callServer($param, 'SetEventParticipantArrivedStatus');
    }

    public function SetEventParticipantGrade($authToken, $eventParticipantID, $gradeID) {
      $param = array(
        'authToken' => $authToken,
        'eventParticipantID' => $eventParticipantID,
        'gradeID' => $gradeID
      );

      return $this->__callServer($param, 'SetEventParticipantGrade');
    }

    public function SetEventParticipantSubEvent($authToken, array $subEventList) {
      $param = array(
        'authToken' => $authToken,
        'subEventList' => $subEventList
      );

      return $this->__callServer($param, 'SetEventParticipantSubEvent');
    }

    public function SetEventParticipantSubEventXml($authToken, $subEventList) {
      $param = array(
        'authToken' => $authToken,
        'subEventList' => $subEventList
      );

      return $this->__callServer($param, 'SetEventParticipantSubEventXml');
    }

    public function SetEventParticipantXml($authToken, $eventParticipant) {
      $param = array(
        'authToken' => $authToken,
        'eventParticipant' => $eventParticipant
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetEventParticipantXml'))->int;
    }

    public function SetInterestRegEvent($authToken, array $interestRegEventList) {
      $param = array(
        'authToken' => $authToken,
        'interestRegEventList' => $interestRegEventList
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetInterestRegEvent'))->int;
    }

    public function SetInterestRegEventBooking($authToken, $bookingInfo) {
      $param = array(
        'authToken' => $authToken,
        'bookingInfo' => $bookingInfo
      );

      return $this->__callServer($param, 'SetInterestRegEventBooking');
    }

    public function SetInterestRegEventBookingXml($authToken, $bookingInfoXml) {
      $param = array(
        'authToken' => $authToken,
        'bookingInfoXml' => $bookingInfoXml
      );

      return $this->__callServer($param, 'SetInterestRegEventBookingXml');
    }

    public function SetInterestRegEventXml($authToken, $interestRegEventXml) {
      $param = array(
        'authToken' => $authToken,
        'interestRegEventXml' => $interestRegEventXml
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetInterestRegEventXml'))->int;
    }

    public function SetInterestRegObject($authToken, array $interestRegObjectList) {
      $param = array(
        'authToken' => $authToken,
        'interestRegObjectList' => $interestRegObjectList
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetInterestRegObject'))->int;
    }

    public function SetInterestRegObjectXml($authToken, $interestRegObjectXml) {
      $param = array(
        'authToken' => $authToken,
        'interestRegObjectXml' => $interestRegObjectXml
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetInterestRegObjectXml'))->int;
    }

    public function SetInterestRegSubEventBooking($authToken, $bookingInfo) {
      $param = array(
        'authToken' => $authToken,
        'bookingInfo' => $bookingInfo
      );

      return $this->__callServer($param, 'SetInterestRegSubEventBooking');
    }

    public function SetInterestRegSubEventBookingXml($authToken, $bookingInfo) {
      $param = array(
        'authToken' => $authToken,
        'bookingInfo' => $bookingInfo
      );

      return $this->__callServer($param, 'SetInterestRegSubEventBookingXml');
    }

    public function SetInvalidPayment($authToken, $eclID) {
      $param = array(
        'authToken' => $authToken,
        'eclID' => $eclID
      );

      return $this->__callServer($param, 'SetInvalidPayment');
    }

    public function SetLimitedDiscount($authToken, array $limitedDiscount) {
      $param = array(
        'authToken' => $authToken,
        'limitedDiscount' => $limitedDiscount
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetLimitedDiscount'))->int;
    }

    public function SetLimitedDiscountXml($authToken, $limitedDiscount) {
      $param = array(
        'authToken' => $authToken,
        'limitedDiscount' => $limitedDiscount
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetLimitedDiscountXml'))->int;
    }

    public function SetPerson($authToken, array $person) {
      $param = array(
        'authToken' => $authToken,
        'person' => $person
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetPerson'))->int;
    }

    public function SetPersonAttribute($authToken, array $personAttribute) {
      $param = array(
        'authToken' => $authToken,
        'personAttribute' => $personAttribute
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetPersonAttribute'))->int;
    }

    public function SetPersonAttributeXml($authToken, $personAttribute) {
      $param = array(
        'authToken' => $authToken,
        'personAttribute' => $personAttribute
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetPersonAttributeXml'))->int;
    }

    public function SetPersonXml($authToken, $person) {
      $param = array(
        'authToken' => $authToken,
        'person' => $person
      );

      return $this->__getArray('int', $this->__callServer($param, 'SetPersonXml'))->int;
    }

    public function SetUnavailablePersonnelDate($authToken, $personnelID, array $unavailableDates) {
      $param = array(
        'authToken' => $authToken,
        'personnelID' => $personnelID,
        'unavailableDates' => $unavailableDates
      );

      return $this->__callServer($param, 'SetUnavailablePersonnelDate');
    }

    public function SetUnavailablePersonnelDateXml($authToken, $personnelID, $unavailableDates) {
      $param = array(
        'authToken' => $authToken,
        'personnelID' => $personnelID,
        'unavailableDates' => $unavailableDates
      );

      return $this->__callServer($param, 'SetUnavailablePersonnelDateXml');
    }

    public function SetValidPayment($authToken, $eclID) {
      $param = array(
        'authToken' => $authToken,
        'eclID' => $eclID
      );

      return $this->__callServer($param, 'SetValidPayment');
    }

    public function UpdateSalesBookingInfo($authToken, array $updateInfo) {
      $param = array(
        'authToken' => $authToken,
        'updateInfo' => $updateInfo
      );

      return $this->__getArray('int', $this->__callServer($param, 'UpdateSalesBookingInfo'))->int;
    }

    public function ValidateAddressString($authToken, $addressString, $compareAddress) {
      $param = array(
        'authToken' => $authToken,
        'addressString' => $addressString,
        'compareAddress' => $compareAddress
      );

      return $this->__callServer($param, 'ValidateAddressString');
    }

    public function ValidateAuthToken($authToken) {
      $param = array(
        'authToken' => $authToken
      );

      return $this->__callServer($param, 'ValidateAuthToken');
    }

    private function __getArray($objName, $res)
    {
      $this->timers[$objName . '__getArray'] = microtime(true);
      if (!empty($res->{$objName}))
      {
        if (is_array($res->{$objName}))
        {
          $this->timers[$objName . '__getArray'] = microtime(true) - $this->timers[$objName . '__getArray'];
          return $res;
        }
        else
        {
          $nRes = new stdClass;
          $nRes->{$objName} = array();
          $nRes->{$objName}[] = $res->{$objName};
          $this->timers[$objName . '__getArray'] = microtime(true) - $this->timers[$objName . '__getArray'];
          return $nRes;
        }
      }
      else
      {
        if (!empty($res->{"ArrayOf" . $objName})) {
                  if (is_array($res->{ "ArrayOf" . $objName})) {
                      $this->timers[$objName . '__getArray'] = microtime(true) - $this->timers[$objName . '__getArray'];
                      if ($this->debugTimers) {
                          echo "<!-- " . $objName . '__getArray' . ": " . round($this->timers[$objName . '__getArray'] * 1000, 2) . "ms -->\n";
                      }
            if (isset($res->{"ArrayOf" . $objName}[0]->{$objName}))
            {
              $arRes = new stdClass;
              $arRes->{$objName} = array();
              foreach ($res->{ "ArrayOf" . $objName} as $item)
              {
                $arRes->{$objName}[] = $item->{$objName};
              }
              return $arRes;
            }
            return $res;
          } else {
            $nRes = new stdClass;
            $nRes->{$objName} = $res->{ "ArrayOf" . $objName}->{$objName};
            $this->timers[$objName . '__getArray'] = microtime(true) - $this->timers[$objName . '__getArray'];
            if ($this->debugTimers) {
              echo "<!-- " . $objName . '__getArray' . ": " . round($this->timers[$objName . '__getArray'] * 1000, 2) . "ms -->\n";
            }
            return $nRes;
          }
              }
        $nRes = new stdClass;
        $nRes->{$objName} = array();
        $this->timers[$objName . '__getArray'] = microtime(true) - $this->timers[$objName . '__getArray'];
        return $nRes;
      }
    }

    private function __callServer($params, $methodName)
    {
      $this->timers[$methodName . '__callServer'] = microtime(true);
      $result = null;
      try {
        $result = $this->__server->__soapCall(
          $methodName,
          array($params)
        );
      }
      catch (SoapFault $fault)
      {
        if ($this->debug)
        {
          echo '<pre>' . print_r($fault, true) . '</pre>';
        }
      }
      if ($this->debug)
        $this->__debug();
      $this->timers[$methodName . '__callServer'] = microtime(true) - $this->timers[$methodName . '__callServer'];
      if (isset($result->{$methodName . 'Result'}))
        return $result->{$methodName . 'Result'};
      return null;
    }

    private function __debug($result = null)
    {
      $requestHeaders = $this->__server->__getLastRequestHeaders();
        $request = $this->__server->__getLastRequest();
        $responseHeaders = $this->__server->__getLastResponseHeaders();
        $response = $this->__server->__getLastResponse();

      if (!empty($requestHeaders))
          echo '<code>' . nl2br(htmlspecialchars($requestHeaders, true)) . '</code>' . '<br/>';
      if (!empty($request))
          echo highlight_string($request, true) . '<br/>';

      if (!empty($responseHeaders))
          echo '<code>' . nl2br(htmlspecialchars($responseHeaders, true)) . '</code>' . '<br/>';
      if (!empty($response))
          echo highlight_string($response, true) . '<br/>';

    }
  }

class AccountInfo {
    var $Name;
    var $Email;
    var $Address1;
    var $Zip;
    var $City;
    var $Phone;
    var $Fax;
    var $OrgNr;
    var $Homepage;

  function __construct() {

  }
}



class AccountSettingsInfo {
    var $Setting;
    var $Value;

  function __construct() {
      $this->Setting = null;
  }
}

class Attribute {
    var $AttributeID;
    var $AttributeTypeID;
    var $AttributeTypeDescription;
    var $AttributeOwnerTypeID;
    var $AttributeOwnerTypeDescription;
    var $AttributeDescription;
    var $AttributeValue;
    var $AttributeAlternative;

  function __construct() {
      $this->AttributeID = 0;
      $this->AttributeTypeID = 0;
      $this->AttributeOwnerTypeID = 0;
      $this->AttributeAlternative = array();
  }
}

class AttributeAlternative {
    var $AttributeAlternativeID;
    var $AttributeAlternativeDescription;

  function __construct() {
      $this->AttributeAlternativeID = 0;
  }
}

class BookedEventAccessory {
    var $EventCustomerLnkID;
    var $BookedQuantity;
    var $ObjectName;
    var $ObjectID;
    var $StartDate;
    var $EndDate;
    var $EventID;
    var $Cost;
    var $Quantity;
    var $ObjectPrice;
    var $PublicName;
    var $VatPercent;

  function __construct() {
      $this->EventCustomerLnkID = 0;
      $this->BookedQuantity = 0;
      $this->ObjectID = 0;
      $this->StartDate = date('c');
      $this->EndDate = date('c');
      $this->EventID = 0;
      $this->Cost = null;
      $this->Quantity = 0;
      $this->ObjectPrice = null;
      $this->VatPercent = null;
  }
}

class BookingInfo {
    var $EventID;
    var $CustomerID;
    var $CustomerContactID;
    var $CustomerReference;
    var $PaymentMethodID;
    var $OccasionPriceNameLnkID;
    var $PersonIDs;
    var $Notes;
    var $LimitedDiscountID;

  function __construct() {
      $this->EventID = 0;
      $this->CustomerID = 0;
      $this->CustomerContactID = 0;
      $this->PaymentMethodID = null;
      $this->OccasionPriceNameLnkID = null;
      $this->PersonIDs = array();
      $this->LimitedDiscountID = null;
  }
}

class BookingInfoPriceName {
    var $EventID;
    var $CustomerID;
    var $CustomerContactID;
    var $CustomerReference;
    var $PaymentMethodID;
    var $Notes;
    var $CouponID;
    var $PriceNames;
    var $Preliminary;

  function __construct() {
      $this->EventID = 0;
      $this->CustomerID = 0;
      $this->CustomerContactID = null;
      $this->PaymentMethodID = null;
      $this->CouponID = null;
      $this->PriceNames = array();
      $this->Preliminary = null;
  }
}

class BookingInfoSubEvent {
    var $EventID;
    var $CustomerID;
    var $CustomerContactID;
    var $CustomerReference;
    var $PaymentMethodID;
    var $OccasionPriceNameLnkID;
    var $Notes;
    var $LimitedDiscountID;
    var $Preliminary;
    var $SubEventPersons;
    var $PurchaseOrderNumber;
    var $CouponID;

  function __construct() {
      $this->EventID = 0;
      $this->CustomerID = 0;
      $this->CustomerContactID = 0;
      $this->PaymentMethodID = null;
      $this->OccasionPriceNameLnkID = null;
      $this->LimitedDiscountID = null;
      $this->Preliminary = null;
      $this->SubEventPersons = array();
      $this->CouponID = null;
  }
}

class BookingSeatInfo {
    var $EventID;
    var $CustomerID;
    var $CustomerContactID;
    var $CustomerReference;
    var $PaymentMethodID;
    var $OccasionPriceNameLnkID;
    var $Notes;
    var $LimitedDiscountID;
    var $Preliminary;
    var $SubEventPersons;
    var $PurchaseOrderNumber;
    var $CouponID;

  function __construct() {
      $this->EventID = 0;
      $this->CustomerID = 0;
      $this->CustomerContactID = 0;
      $this->PaymentMethodID = null;
      $this->OccasionPriceNameLnkID = null;
      $this->LimitedDiscountID = null;
      $this->Preliminary = null;
      $this->SubEventPersons = array();
      $this->CouponID = null;
  }
}

class Category {
    var $CategoryID;
    var $CategoryName;
    var $ShowOnWeb;
    var $ImageUrl;
    var $CategoryNotes;
    var $ParentID;
    var $ShowOnWebInternal;

  function __construct() {
      $this->CategoryID = 0;
      $this->ShowOnWeb = false;
      $this->ParentID = 0;
      $this->ShowOnWebInternal = false;
  }
}

class CategoryV2 {
    var $MetaType;
    var $CategoryID;
    var $CategoryName;
    var $ShowOnWeb;
    var $ImageUrl;
    var $CategoryNotes;
    var $ParentID;
    var $ShowOnWebInternal;

  function __construct() {
      $this->CategoryID = 0;
      $this->ShowOnWeb = false;
      $this->ParentID = 0;
      $this->ShowOnWebInternal = false;
  }
}

class CategoryV3 {
    var $Color;
    var $MetaType;
    var $CategoryID;
    var $CategoryName;
    var $ShowOnWeb;
    var $ImageUrl;
    var $CategoryNotes;
    var $ParentID;
    var $ShowOnWebInternal;

  function __construct() {
      $this->CategoryID = 0;
      $this->ShowOnWeb = false;
      $this->ParentID = 0;
      $this->ShowOnWebInternal = false;
  }
}

class Certificate {
    var $CertificateID;
    var $CertificateNumber;
    var $CertificateName;
    var $Created;
    var $ValidMonthCount;
    var $ValidDayCount;
    var $CompleteObjectsMonthCount;
    var $ObjectRules;
    var $CertificateRules;

  function __construct() {
      $this->CertificateID = 0;
      $this->Created = date('c');
      $this->ValidMonthCount = null;
      $this->ValidDayCount = null;
      $this->CompleteObjectsMonthCount = null;
      $this->ObjectRules = array();
      $this->CertificateRules = array();
  }
}

class CertificatePerson {
    var $PersonID;
    var $PersonFirstName;
    var $PersonLastName;
    var $PersonCivicRegistrationNumber;
    var $PersonEmail;
    var $CustomerID;
    var $CertificateID;
    var $CertificateNumber;
    var $CertificateName;
    var $CertificateDate;
    var $ValidFrom;
    var $ValidTo;
    var $CertificateFromEventID;

  function __construct() {
      $this->PersonID = 0;
      $this->CustomerID = 0;
      $this->CertificateID = 0;
      $this->CertificateDate = date('c');
      $this->ValidFrom = null;
      $this->ValidTo = null;
      $this->CertificateFromEventID = null;
  }
}

class CertificatePersonV2 {
    var $CertificatePersonID;
    var $RequiresHealthCertificate;
    var $CertificateFromEventIDs;
    var $CertificateFromPersonCertificateIDs;
    var $Subjects;
    var $CertificateAfterRetest;
    var $CertificatePersonComment;
    var $PersonID;
    var $PersonFirstName;
    var $PersonLastName;
    var $PersonCivicRegistrationNumber;
    var $PersonEmail;
    var $CustomerID;
    var $CertificateID;
    var $CertificateNumber;
    var $CertificateName;
    var $CertificateDate;
    var $ValidFrom;
    var $ValidTo;
    var $CertificateFromEventID;

  function __construct() {
      $this->CertificatePersonID = 0;
      $this->RequiresHealthCertificate = false;
      $this->CertificateFromEventIDs = array();
      $this->CertificateFromPersonCertificateIDs = array();
      $this->Subjects = array();
      $this->CertificateAfterRetest = false;
      $this->PersonID = 0;
      $this->CustomerID = 0;
      $this->CertificateID = 0;
      $this->CertificateDate = date('c');
      $this->ValidFrom = null;
      $this->ValidTo = null;
      $this->CertificateFromEventID = null;
  }
}

class CertificateRule {
    var $CertificateID;
    var $CertificateName;

  function __construct() {
      $this->CertificateID = 0;
  }
}

class CompanyAttribute {
    var $AttributeChecked;
    var $AttributeDate;
    var $AttributeID;
    var $AttributeTypeID;
    var $AttributeTypeDescription;
    var $AttributeOwnerTypeID;
    var $AttributeOwnerTypeDescription;
    var $AttributeDescription;
    var $AttributeValue;
    var $AttributeAlternative;

  function __construct() {
      $this->AttributeChecked = false;
      $this->AttributeDate = null;
      $this->AttributeID = 0;
      $this->AttributeTypeID = 0;
      $this->AttributeOwnerTypeID = 0;
      $this->AttributeAlternative = array();
  }
}

class ConfirmationEmailInfo {
    var $ConfirmationSentTo;
    var $ConfirmationCopySentTo;

  function __construct() {
      $this->ConfirmationSentTo = array();
      $this->ConfirmationCopySentTo = array();
  }
}

class ConfirmationEmailMessage {
    var $DocumentID;
    var $Subject;
    var $Body;

  function __construct() {
      $this->DocumentID = 0;
  }
}

class Country {
    var $CountryCode;
    var $Abbr;
    var $CountryNumber;
    var $CountryName;
    var $CultureName;

  function __construct() {
      $this->CountryNumber = null;
  }
}

class Coupon {
    var $CouponID;
    var $Code;
    var $DiscountPercent;
    var $CouponDescription;
    var $ValidFrom;

    var $ValidTo;

  function __construct() {
      $this->CouponID = 0;
      $this->DiscountPercent = null;
      $this->ValidFrom = date('c');
      $this->ValidTo = date('c');
  }
}

class Customer {
    var $CustomerID;
    var $CustomerNumber;
    var $CustomerName;
    var $Address1;
    var $Address2;
    var $Zip;
    var $City;
    var $Country;
    var $Phone;
    var $Mobile;
    var $Fax;
    var $Email;
    var $Homepage;
    var $InvoiceName;
    var $InvoiceAddress1;
    var $InvoiceAddress2;
    var $InvoiceZip;
    var $InvoiceCity;
    var $InvoiceCountry;
    var $InvoiceOrgnr;
    var $CustomerGroupName;
    var $CustomerGroupID;
    var $Password;
    var $InvoiceVatnr;
    var $CustomerReference;
    var $VatFree;
    var $Attribute;

  function __construct() {
      $this->CustomerID = 0;
      $this->CustomerGroupID = null;
      $this->VatFree = null;
      $this->Attribute = array();
  }
}

class CustomerAttribute {
    var $CustomerID;
    var $CustomerAttributeID;
    var $AttributeChecked;
    var $AttributeDate;
    var $AttributeID;
    var $AttributeTypeID;
    var $AttributeTypeDescription;
    var $AttributeOwnerTypeID;
    var $AttributeOwnerTypeDescription;
    var $AttributeDescription;
    var $AttributeValue;
    var $AttributeAlternative;

  function __construct() {
      $this->CustomerID = 0;
      $this->CustomerAttributeID = null;
      $this->AttributeChecked = false;
      $this->AttributeDate = null;
      $this->AttributeID = 0;
      $this->AttributeTypeID = 0;
      $this->AttributeOwnerTypeID = 0;
      $this->AttributeAlternative = array();
  }
}

class CustomerContact {
    var $CustomerContactID;
    var $ContactNumber;
    var $CustomerID;
    var $ContactName;
    var $Address1;
    var $Address2;
    var $Zip;
    var $City;
    var $Phone;
    var $Mobile;
    var $Fax;
    var $Email;
    var $Position;
    var $Loginpass;
    var $Notes;
    var $CustomerGroupName;
    var $PublicGroup;
    var $CivicRegistrationNumber;
    var $CanLogin;
    var $Attribute;

  function __construct() {
      $this->CustomerContactID = 0;
      $this->CustomerID = 0;
      $this->PublicGroup = false;
      $this->CanLogin = null;
      $this->Attribute = array();
  }
}

class CustomerContactAttribute {
    var $CustomerContactID;
    var $CustomerContactAttributeID;
    var $AttributeChecked;
    var $AttributeDate;
    var $AttributeID;
    var $AttributeTypeID;
    var $AttributeTypeDescription;
    var $AttributeOwnerTypeID;
    var $AttributeOwnerTypeDescription;
    var $AttributeDescription;
    var $AttributeValue;
    var $AttributeAlternative;

  function __construct() {
      $this->CustomerContactID = 0;
      $this->CustomerContactAttributeID = null;
      $this->AttributeChecked = false;
      $this->AttributeDate = null;
      $this->AttributeID = 0;
      $this->AttributeTypeID = 0;
      $this->AttributeOwnerTypeID = 0;
      $this->AttributeAlternative = array();
  }
}

class CustomerContactV2 {
    var $PurchaseOrderNumber;
    var $ContactReference;
    var $Country;
    var $CustomerContactID;
    var $ContactNumber;
    var $CustomerID;
    var $ContactName;
    var $Address1;
    var $Address2;
    var $Zip;
    var $City;
    var $Phone;
    var $Mobile;
    var $Fax;
    var $Email;
    var $Position;
    var $Loginpass;
    var $Notes;
    var $CustomerGroupName;
    var $PublicGroup;
    var $CivicRegistrationNumber;
    var $CanLogin;
    var $Attribute;

  function __construct() {
      $this->CustomerContactID = 0;
      $this->CustomerID = 0;
      $this->PublicGroup = false;
      $this->CanLogin = null;
      $this->Attribute = array();
  }
}

class CustomerEventPrice {
    var $EventID;
    var $CustomerPrice;
    var $CustomerSubEventPrices;

  function __construct() {
      $this->EventID = 0;
      $this->CustomerPrice = null;
      $this->CustomerSubEventPrices = array();
  }
}

class CustomerGroup {
    var $CustomerGroupID;
    var $CustomerGroupName;
    var $CustomerGroupCode;
    var $ParentCustomerGroupID;
    var $DiscountPercent;
    var $PublicGroup;
    var $PriceNameCode;

  function __construct() {
      $this->CustomerGroupID = 0;
      $this->ParentCustomerGroupID = 0;
      $this->DiscountPercent = null;
      $this->PublicGroup = false;
  }
}

class CustomerPrice {
    var $CustomerID;
    var $Price;
    var $CancelationFee;
    var $ObjectID;
    var $ObjectCategoryID;

  function __construct() {
      $this->CustomerID = 0;
      $this->Price = null;
      $this->CancelationFee = null;
      $this->ObjectID = 0;
      $this->ObjectCategoryID = 0;
  }
}

class CustomerStatistics {
    var $CustomerID;
    var $Value;

  function __construct() {
      $this->CustomerID = 0;
      $this->Value = null;
  }
}

class CustomerSubEventPrice {
    var $SubEventID;
    var $CustomerPrice;

  function __construct() {
      $this->SubEventID = 0;
      $this->CustomerPrice = null;
  }
}

class CustomerV2 {
    var $InvoiceEmail;
    var $OurReference;
    var $EdiReference;
    var $Notes;
    var $CanLogin;
    var $DiscountPercent;
    var $ParticipantDiscountPercent;
    var $NotCreditworthy;
    var $CustomerID;
    var $CustomerNumber;
    var $CustomerName;
    var $Address1;
    var $Address2;
    var $Zip;
    var $City;
    var $Country;
    var $Phone;
    var $Mobile;
    var $Fax;
    var $Email;
    var $Homepage;
    var $InvoiceName;
    var $InvoiceAddress1;
    var $InvoiceAddress2;
    var $InvoiceZip;
    var $InvoiceCity;
    var $InvoiceCountry;
    var $InvoiceOrgnr;
    var $CustomerGroupName;
    var $CustomerGroupID;
    var $Password;
    var $InvoiceVatnr;
    var $CustomerReference;
    var $VatFree;
    var $Attribute;

  function __construct() {
      $this->CanLogin = null;
      $this->DiscountPercent = null;
      $this->ParticipantDiscountPercent = null;
      $this->NotCreditworthy = null;
      $this->CustomerID = 0;
      $this->CustomerGroupID = null;
      $this->VatFree = null;
      $this->Attribute = array();
  }
}

class DocumentSentListEvent {
    var $DocumentID;
    var $Email;
    var $SendDate;
    var $Error;
    var $EventID;
    var $EventCustomerLnkID;

  function __construct() {
      $this->DocumentID = 0;
      $this->SendDate = date('c');
      $this->Error = false;
      $this->EventID = 0;
      $this->EventCustomerLnkID = 0;
  }
}

class EducationLevel {
    var $EducationLevelID;
    var $Name;
    var $Index;

  function __construct() {
      $this->EducationLevelID = 0;
      $this->Index = null;
  }
}

class EducationLevelObject {
    var $ObjectID;
    var $EducationLevelID;
    var $Name;
    var $Index;

  function __construct() {
      $this->ObjectID = 0;
      $this->EducationLevelID = 0;
      $this->Index = null;
  }
}

class EducationObject {
    var $ObjectID;
    var $ObjectName;
    var $CourseDescription;
    var $CourseDescriptionShort;
    var $CourseGoal;
    var $ShowOnWeb;
    var $TargetGroup;
    var $CourseAfter;
    var $Prerequisites;
    var $CategoryName;
    var $CategoryID;
    var $ImageUrl;
    var $Days;
    var $StartTime;
    var $EndTime;
    var $ItemNr;
    var $RequireCivicRegistrationNumber;
    var $ParticipantDocumentID;
    var $Quote;
    var $Notes;
    var $PublicName;
    var $Department;
    var $MaxParticipantNr;
    var $MinParticipantNr;

  function __construct() {
      $this->ObjectID = 0;
      $this->ShowOnWeb = false;
      $this->CategoryID = 0;
      $this->Days = 0;
      $this->RequireCivicRegistrationNumber = false;
      $this->ParticipantDocumentID = 0;
      $this->MaxParticipantNr = 0;
      $this->MinParticipantNr = 0;
  }
}

class EducationObjectV2 {
    var $Shortening;
    var $SortIndex;
    var $EducationLevelID;
    var $Vat;
    var $Subjects;
    var $ObjectID;
    var $ObjectName;
    var $CourseDescription;
    var $CourseDescriptionShort;
    var $CourseGoal;
    var $ShowOnWeb;
    var $TargetGroup;
    var $CourseAfter;
    var $Prerequisites;
    var $CategoryName;
    var $CategoryID;
    var $ImageUrl;
    var $Days;
    var $StartTime;
    var $EndTime;
    var $ItemNr;
    var $RequireCivicRegistrationNumber;
    var $ParticipantDocumentID;
    var $Quote;
    var $Notes;
    var $PublicName;
    var $Department;
    var $MaxParticipantNr;
    var $MinParticipantNr;

  function __construct() {
      $this->SortIndex = null;
      $this->EducationLevelID = null;
      $this->Vat = null;
      $this->Subjects = array();
      $this->ObjectID = 0;
      $this->ShowOnWeb = false;
      $this->CategoryID = 0;
      $this->Days = 0;
      $this->RequireCivicRegistrationNumber = false;
      $this->ParticipantDocumentID = 0;
      $this->MaxParticipantNr = 0;
      $this->MinParticipantNr = 0;
  }
}

class EducationSubject {
    var $SubjectID;
    var $ObjectID;
    var $SubjectName;

  function __construct() {
      $this->SubjectID = 0;
      $this->ObjectID = 0;
  }
}

class Event {
    var $EventID;
    var $ObjectID;
    var $ObjectName;
    var $CategoryName;
    var $Description;
    var $LocationID;
    var $LocationAddressID;
    var $City;
    var $Notes;
    var $PeriodStart;
    var $PeriodEnd;
    var $ImageUrl;
    var $OccationID;
    var $MaxParticipantNr;
    var $TotalParticipantNr;
    var $ShowOnWeb;
    var $ShowOnWebInternal;
    var $StatusID;
    var $StatusText;
    var $AddressName;
    var $ConfirmedAddress;
    var $CustomerID;
    var $UsePriceNameMaxParticipantNr;
    var $LastApplicationDate;
    var $Seats;
    var $PersonnelIDs;

  function __construct() {
      $this->EventID = 0;
      $this->ObjectID = 0;
      $this->LocationID = 0;
      $this->LocationAddressID = null;
      $this->PeriodStart = date('c');
      $this->PeriodEnd = date('c');
      $this->OccationID = 0;
      $this->MaxParticipantNr = 0;
      $this->TotalParticipantNr = 0;
      $this->ShowOnWeb = false;
      $this->ShowOnWebInternal = false;
      $this->StatusID = 0;
      $this->ConfirmedAddress = false;
      $this->CustomerID = 0;
      $this->UsePriceNameMaxParticipantNr = false;
      $this->LastApplicationDate = null;
      $this->Seats = false;
      $this->PersonnelIDs = array();
  }
}

class EventAccessory {
    var $ObjectName;
    var $ObjectID;
    var $StartDate;
    var $EndDate;
    var $EventID;
    var $Cost;
    var $Quantity;
    var $ObjectPrice;
    var $PublicName;
    var $VatPercent;

  function __construct() {
      $this->ObjectID = 0;
      $this->StartDate = date('c');
      $this->EndDate = date('c');
      $this->EventID = 0;
      $this->Cost = null;
      $this->Quantity = 0;
      $this->ObjectPrice = null;
      $this->VatPercent = null;
  }
}

class EventBooking {
    var $EventCustomerLnkID;
    var $EventID;
    var $ObjectName;
    var $EventDescription;
    var $CustomerID;
    var $CustomerContactID;
    var $TotalPrice;
    var $ParticipantNr;
    var $Created;
    var $Paid;
    var $ObjectID;
    var $PeriodStart;
    var $PeriodEnd;
    var $Preliminary;
    var $Notes;

  function __construct() {
      $this->EventCustomerLnkID = 0;
      $this->EventID = 0;
      $this->CustomerID = 0;
      $this->CustomerContactID = 0;
      $this->TotalPrice = null;
      $this->ParticipantNr = 0;
      $this->Created = date('c');
      $this->Paid = false;
      $this->ObjectID = 0;
      $this->PeriodStart = date('c');
      $this->PeriodEnd = date('c');
      $this->Preliminary = false;
  }
}

class EventBookingAnswer {
    var $AnswerID;
    var $AnswerText;
    var $SortIndex;
    var $Price;
    var $VatPercent;
    var $DefaultAnswerText;
    var $DefaultAnswerTime;
    var $DefaultAlternative;

  function __construct() {
      $this->AnswerID = 0;
      $this->SortIndex = 0;
      $this->Price = null;
      $this->VatPercent = null;
      $this->DefaultAlternative = false;
  }
}

class EventBookingPostponedBillingDate {
    var $EventCustomerLnkID;
    var $BillingDate;

  function __construct() {
      $this->EventCustomerLnkID = 0;
      $this->BillingDate = null;
  }
}

class EventBookingPriceName {
    var $PriceNameID;
    var $Description;
    var $Price;
    var $TotalPrice;
    var $ParticipantNr;
    var $EventCustomerLnkID;
    var $OccationPriceNameLnkID;

  function __construct() {
      $this->PriceNameID = 0;
      $this->Price = null;
      $this->TotalPrice = null;
      $this->ParticipantNr = 0;
      $this->EventCustomerLnkID = 0;
      $this->OccationPriceNameLnkID = 0;
  }
}

class EventBookingPriceNameInfo {
    var $occationPriceNameLnkID;
    var $participantNr;

  function __construct() {
      $this->occationPriceNameLnkID = 0;
      $this->participantNr = 0;
  }
}

class EventBookingQuestion {
    var $QuestionID;
    var $QuestionText;
    var $QuestionTypeID;
    var $QuestionTypeText;
    var $ShowExternal;
    var $MetaType;
    var $AddNumberField;
    var $Time;
    var $SortIndex;
    var $CategoryID;
    var $CategoryName;
    var $Mandatory;
    var $KeyQuestion;
    var $ProductNumber;
    var $Answers;

  function __construct() {
      $this->QuestionID = 0;
      $this->QuestionTypeID = 0;
      $this->ShowExternal = false;
      $this->AddNumberField = false;
      $this->Time = false;
      $this->SortIndex = 0;
      $this->CategoryID = null;
      $this->Mandatory = false;
      $this->KeyQuestion = false;
      $this->Answers = array();
  }
}

class EventCustomerAnswer {
    var $AnswerID;
    var $AnswerText;
    var $EventID;
    var $EventCustomerLnkID;

  function __construct() {
      $this->AnswerID = 0;
      $this->EventID = 0;
      $this->EventCustomerLnkID = 0;
  }
}

class EventCustomerAnswerV2 {
    var $AnswerNumber;
    var $AnswerTime;
    var $AnswerID;
    var $AnswerText;
    var $EventID;
    var $EventCustomerLnkID;

  function __construct() {
      $this->AnswerNumber = null;
      $this->AnswerID = 0;
      $this->EventID = 0;
      $this->EventCustomerLnkID = 0;
  }
}

class EventDate {
    var $EventID;
    var $StartDate;
    var $EndDate;

  function __construct() {
      $this->EventID = 0;
      $this->StartDate = date('c');
      $this->EndDate = date('c');
  }
}

class EventParticipant {
    var $EventParticipantID;
    var $PersonID;
    var $PersonName;
    var $PersonEmail;
    var $PersonCivicRegistrationNumber;
    var $PersonPhone;
    var $PersonMobile;
    var $CustomerID;
    var $CustomerContactID;
    var $EventCustomerLnkID;
    var $EventID;
    var $TotalPrice;
    var $ParticipantNr;
    var $Created;
    var $Price;
    var $ObjectID;
    var $ObjectName;
    var $Arrived;
    var $GradeID;
    var $GradeName;
    var $Paid;
    var $Reference;
    var $PaymentMethodID;
    var $PaymentMethodName;
    var $Canceled;
    var $PeriodStart;
    var $PeriodEnd;

  function __construct() {
      $this->EventParticipantID = 0;
      $this->PersonID = 0;
      $this->CustomerID = 0;
      $this->CustomerContactID = 0;
      $this->EventCustomerLnkID = 0;
      $this->EventID = 0;
      $this->TotalPrice = null;
      $this->ParticipantNr = 0;
      $this->Created = date('c');
      $this->Price = null;
      $this->ObjectID = 0;
      $this->Arrived = null;
      $this->GradeID = 0;
      $this->Paid = false;
      $this->PaymentMethodID = null;
      $this->Canceled = null;
      $this->PeriodStart = date('c');
      $this->PeriodEnd = date('c');
  }
}

class EventParticipantSubEvent {
    var $EventParticipantID;
    var $SubEvents;

  function __construct() {
      $this->EventParticipantID = 0;
      $this->SubEvents = array();
  }
}

class EventParticipantV2 {
    var $CanceledDate;
    var $GradeDate;
    var $GradeAfterRetest;
    var $GradeComment;
    var $EventParticipantID;
    var $PersonID;
    var $PersonName;
    var $PersonEmail;
    var $PersonCivicRegistrationNumber;
    var $PersonPhone;
    var $PersonMobile;
    var $CustomerID;
    var $CustomerContactID;
    var $EventCustomerLnkID;
    var $EventID;
    var $TotalPrice;
    var $ParticipantNr;
    var $Created;
    var $Price;
    var $ObjectID;
    var $ObjectName;
    var $Arrived;
    var $GradeID;
    var $GradeName;
    var $Paid;
    var $Reference;
    var $PaymentMethodID;
    var $PaymentMethodName;
    var $Canceled;
    var $PeriodStart;
    var $PeriodEnd;

  function __construct() {
      $this->CanceledDate = null;
      $this->GradeDate = null;
      $this->GradeAfterRetest = false;
      $this->EventParticipantID = 0;
      $this->PersonID = 0;
      $this->CustomerID = 0;
      $this->CustomerContactID = 0;
      $this->EventCustomerLnkID = 0;
      $this->EventID = 0;
      $this->TotalPrice = null;
      $this->ParticipantNr = 0;
      $this->Created = date('c');
      $this->Price = null;
      $this->ObjectID = 0;
      $this->Arrived = null;
      $this->GradeID = 0;
      $this->Paid = false;
      $this->PaymentMethodID = null;
      $this->Canceled = null;
      $this->PeriodStart = date('c');
      $this->PeriodEnd = date('c');
  }
}

class EventPaymentMethod {
    var $PaymentMethodID;
    var $MethodName;
    var $EventID;

  function __construct() {
      $this->PaymentMethodID = 0;
      $this->EventID = 0;
  }
}

class EventPersonnelMessage {
    var $EventID;
    var $PersonnelMessage;

  function __construct() {
      $this->EventID = 0;
  }
}

class EventPersonnelObject {
    var $PersonnelID;
    var $Confirmed;
    var $OccationID;
    var $EventID;
    var $Description;
    var $ObjectName;
    var $LocationID;
    var $LocationAddressID;
    var $AddressName;
    var $City;
    var $StartDate;
    var $EndDate;
    var $EventMaxParticipantNr;

  function __construct() {
      $this->PersonnelID = 0;
      $this->Confirmed = false;
      $this->OccationID = 0;
      $this->EventID = 0;
      $this->LocationID = 0;
      $this->LocationAddressID = null;
      $this->StartDate = date('c');
      $this->EndDate = date('c');
      $this->EventMaxParticipantNr = 0;
  }
}

class EventPersonnelObjectV2 {
    var $ConfirmMessage;
    var $PersonnelID;
    var $Confirmed;
    var $OccationID;
    var $EventID;
    var $Description;
    var $ObjectName;
    var $LocationID;
    var $LocationAddressID;
    var $AddressName;
    var $City;
    var $StartDate;
    var $EndDate;
    var $EventMaxParticipantNr;

  function __construct() {
      $this->PersonnelID = 0;
      $this->Confirmed = false;
      $this->OccationID = 0;
      $this->EventID = 0;
      $this->LocationID = 0;
      $this->LocationAddressID = null;
      $this->StartDate = date('c');
      $this->EndDate = date('c');
      $this->EventMaxParticipantNr = 0;
  }
}

class EventProjectNumber {
    var $EventID;
    var $ProjectNumber;

  function __construct() {
      $this->EventID = 0;
  }
}

class EventQuestion {
    var $QuestionID;
    var $AnswerID;
    var $AnswerText;
    var $ObjectID;
    var $OccationID;
    var $EventID;

  function __construct() {
      $this->QuestionID = 0;
      $this->AnswerID = 0;
      $this->ObjectID = 0;
      $this->OccationID = 0;
      $this->EventID = 0;
  }
}

class EventSeat {
    var $EventID;
    var $SeatID;
    var $RowID;
    var $SeatSortIndex;
    var $RowSortIndex;
    var $Nr;
    var $Booked;
    var $Locked;
    var $Dead;
    var $TicketID;

  function __construct() {
      $this->EventID = 0;
      $this->SeatID = 0;
      $this->RowID = 0;
      $this->SeatSortIndex = 0;
      $this->RowSortIndex = 0;
      $this->Nr = 0;
      $this->Booked = false;
      $this->Locked = false;
      $this->Dead = false;
      $this->TicketID = null;
  }
}

class ExtraInfo {
    var $Key;
    var $Value;

  function __construct() {
      $this->Value = null;
  }
}

class Filter {
    var $StatisticsType;
    var $FromDate;
    var $ToDate;

  function __construct() {
      $this->StatisticsType = null;
      $this->FromDate = date('c');
      $this->ToDate = date('c');
  }
}

class Grade {
    var $GradeID;
    var $GradeName;
    var $GradeText;
    var $GradeValue;

  function __construct() {
      $this->GradeID = 0;
      $this->GradeValue = null;
  }
}

class InterestRegEvent {
    var $ObjectID;
    var $EventID;
    var $ParticipantNr;
    var $CompanyName;
    var $ContactName;
    var $Email;
    var $Phone;
    var $Mobile;
    var $Notes;

  function __construct() {
      $this->ObjectID = 0;
      $this->EventID = 0;
      $this->ParticipantNr = null;
  }
}

class InterestRegObject {
    var $ObjectID;
    var $ParticipantNr;
    var $CompanyName;
    var $ContactName;
    var $Email;
    var $Phone;
    var $Mobile;
    var $Notes;

  function __construct() {
      $this->ObjectID = 0;
      $this->ParticipantNr = null;
  }
}

class InterestRegReturnObject {
    var $InterestRegID;
    var $EventCustomerLnkID;

  function __construct() {
      $this->InterestRegID = 0;
      $this->EventCustomerLnkID = 0;
  }
}



class LimitedDiscount {
    var $LimitedDiscountID;
    var $LimitedDiscountTypeID;
    var $CustomerID;
    var $CustomerContactID;
    var $DiscountPercent;
    var $CategoryID;
    var $Price;
    var $CreditStartValue;
    var $CreditLeft;
    var $Paid;
    var $PublicName;
    var $PaymentMethodID;
    var $ValidFrom;
    var $ValidTo;

  function __construct() {
      $this->LimitedDiscountID = 0;
      $this->LimitedDiscountTypeID = 0;
      $this->CustomerID = 0;
      $this->CustomerContactID = null;
      $this->DiscountPercent = null;
      $this->CategoryID = null;
      $this->Price = null;
      $this->CreditStartValue = null;
      $this->CreditLeft = null;
      $this->Paid = null;
      $this->PaymentMethodID = null;
      $this->ValidFrom = null;
      $this->ValidTo = null;
  }
}

class LimitedDiscountObjectStatus {
    var $CreditCount;
    var $ObjectID;
    var $LimitedDiscountID;

  function __construct() {
      $this->CreditCount = 0;
      $this->ObjectID = 0;
      $this->LimitedDiscountID = 0;
  }
}

class LimitedDiscountType {
    var $LimitedDiscountTypeID;
    var $LimitedDiscountDescription;
    var $IndividualBooking;
    var $Price;
    var $DiscountPercent;
    var $ShowPublic;
    var $PublicName;
    var $CreditCount;
    var $ValidFrom;
    var $ValidTo;
    var $DocumentID;

  function __construct() {
      $this->LimitedDiscountTypeID = 0;
      $this->IndividualBooking = false;
      $this->Price = null;
      $this->DiscountPercent = 0;
      $this->ShowPublic = false;
      $this->CreditCount = 0;
      $this->ValidFrom = null;
      $this->ValidTo = null;
      $this->DocumentID = null;
  }
}

class LMSObject {
    var $ObjectID;
    var $ObjectName;
    var $CourseDescription;
    var $CourseDescriptionShort;
    var $CourseGoal;
    var $ShowOnWeb;
    var $TargetGroup;
    var $CourseAfter;
    var $Prerequisites;
    var $CategoryName;
    var $CategoryID;
    var $ImageUrl;
    var $Days;
    var $StartTime;
    var $EndTime;
    var $ItemNr;
    var $RequireCivicRegistrationNumber;
    var $ParticipantDocumentID;
    var $Quote;
    var $Notes;
    var $PublicName;
    var $Department;
    var $MaxParticipantNr;
    var $MinParticipantNr;

  function __construct() {
      $this->ObjectID = 0;
      $this->ShowOnWeb = false;
      $this->CategoryID = 0;
      $this->Days = 0;
      $this->RequireCivicRegistrationNumber = false;
      $this->ParticipantDocumentID = 0;
      $this->MaxParticipantNr = 0;
      $this->MinParticipantNr = 0;
  }
}

class Location {
    var $LocationID;
    var $City;
    var $XPos;
    var $YPos;
    var $PublicLocation;
    var $CostCenter;
    var $LocationNotes;
    var $RegionID;

  function __construct() {
      $this->LocationID = 0;
      $this->PublicLocation = false;
      $this->RegionID = null;
  }
}

class LocationAddress {
    var $LocationAddressID;
    var $LocationID;
    var $Name;
    var $Address;
    var $Zip;
    var $City;
    var $Phone;
    var $Fax;
    var $Email;
    var $Notes;
    var $InterestRegEmail;
    var $Cost;
    var $Homepage;

  function __construct() {
      $this->LocationAddressID = 0;
      $this->LocationID = 0;
      $this->Cost = null;
  }
}

class NamedParticipant {
    var $EventParticipantID;
    var $PersonID;
    var $PersonName;
    var $PersonEmail;
    var $PersonPhone;
    var $PersonMobile;
    var $PersonCivicRegistrationNumber;
    var $PersonAddress1;
    var $PersonAddress2;
    var $PersonZip;
    var $PersonCity;
    var $PersonPosition;
    var $PersonEmployeeNumber;
    var $Reference;
    var $SubEvents;
    var $Attribute;

  function __construct() {
      $this->EventParticipantID = 0;
      $this->PersonID = null;
      $this->SubEvents = array();
      $this->Attribute = array();
  }
}

class ObjectAttribute {
    var $ObjectID;
    var $AttributeChecked;
    var $AttributeID;
    var $AttributeTypeID;
    var $AttributeTypeDescription;
    var $AttributeOwnerTypeID;
    var $AttributeOwnerTypeDescription;
    var $AttributeDescription;
    var $AttributeValue;
    var $AttributeAlternative;

  function __construct() {
      $this->ObjectID = 0;
      $this->AttributeChecked = false;
      $this->AttributeID = 0;
      $this->AttributeTypeID = 0;
      $this->AttributeOwnerTypeID = 0;
      $this->AttributeAlternative = array();
  }
}

class ObjectCategoryQuestion {
    var $VatPercent;
    var $CategoryID;
    var $ObjectID;
    var $Time;
    var $MetaType;
    var $QuestionID;
    var $QuestionText;
    var $QuestionTypeID;
    var $QuestionTypeText;
    var $ShowExternal;
    var $AnswerID;
    var $Price;
    var $DefaultAlternative;
    var $AnswerText;
    var $SortIndex;

  function __construct() {
      $this->VatPercent = null;
      $this->CategoryID = null;
      $this->ObjectID = null;
      $this->Time = false;
      $this->QuestionID = 0;
      $this->QuestionTypeID = 0;
      $this->ShowExternal = false;
      $this->AnswerID = 0;
      $this->Price = null;
      $this->DefaultAlternative = false;
      $this->SortIndex = 0;
  }
}

class ObjectFile {
    var $ObjectID;
    var $FileName;
    var $Created;
    var $Comment;
    var $FileUrl;

  function __construct() {
      $this->ObjectID = 0;
      $this->Created = date('c');
  }
}

class ObjectPriceName {
    var $PriceNameID;
    var $ObjectID;
    var $Price;
    var $PublicPriceName;
    var $Description;

  function __construct() {
      $this->PriceNameID = 0;
      $this->ObjectID = 0;
      $this->Price = null;
      $this->PublicPriceName = false;
  }
}

class ObjectRule {
    var $ObjectID;
    var $ObjectName;

  function __construct() {
      $this->ObjectID = 0;
  }
}

class Person {
    var $PersonID;
    var $CustomerID;
    var $CustomerContactID;
    var $PersonName;
    var $PersonEmail;
    var $PersonPhone;
    var $PersonMobile;
    var $PersonCivicRegistrationNumber;
    var $PersonAddress1;
    var $PersonAddress2;
    var $PersonZip;
    var $PersonCity;
    var $PersonPosition;
    var $PersonEmployeeNumber;
    var $Attribute;

  function __construct() {
      $this->PersonID = 0;
      $this->CustomerID = 0;
      $this->CustomerContactID = null;
      $this->Attribute = array();
  }
}

class PersonAttribute {
    var $PersonID;
    var $PersonAttributeID;
    var $AttributeChecked;
    var $AttributeDate;
    var $AttributeID;
    var $AttributeTypeID;
    var $AttributeTypeDescription;
    var $AttributeOwnerTypeID;
    var $AttributeOwnerTypeDescription;
    var $AttributeDescription;
    var $AttributeValue;
    var $AttributeAlternative;

  function __construct() {
      $this->PersonID = 0;
      $this->PersonAttributeID = null;
      $this->AttributeChecked = false;
      $this->AttributeDate = null;
      $this->AttributeID = 0;
      $this->AttributeTypeID = 0;
      $this->AttributeOwnerTypeID = 0;
      $this->AttributeAlternative = array();
  }
}

class PersonnelObject {
    var $PersonnelID;
    var $ObjectID;
    var $ObjectName;
    var $Address;
    var $Zip;
    var $City;
    var $Country;
    var $Phone;
    var $Mobile;
    var $Fax;
    var $Email;
    var $Password;
    var $ImageUrl;
    var $Notes;

  function __construct() {
      $this->PersonnelID = 0;
      $this->ObjectID = 0;
  }
}

class PersonnelObjectTitle {
    var $PersonnelID;
    var $ObjectID;
    var $Title;

  function __construct() {
      $this->PersonnelID = 0;
      $this->ObjectID = 0;
  }
}

class PriceName {
    var $OccationPriceNameLnkID;
    var $PriceNameID;
    var $OccationID;
    var $Price;
    var $PublicPriceName;
    var $DiscountPercent;
    var $MaxPriceNameParticipantNr;
    var $ParticipantNr;
    var $Description;
    var $PriceNameVat;
    var $PriceNameCode;

  function __construct() {
      $this->OccationPriceNameLnkID = 0;
      $this->PriceNameID = 0;
      $this->OccationID = 0;
      $this->Price = null;
      $this->PublicPriceName = false;
      $this->DiscountPercent = null;
      $this->MaxPriceNameParticipantNr = 0;
      $this->ParticipantNr = 0;
      $this->PriceNameVat = 0;
  }
}

class PriceNameBookingInfo {
    var $OccationPriceNameLnkID;
    var $ParticipantNr;

  function __construct() {
      $this->OccationPriceNameLnkID = 0;
      $this->ParticipantNr = 0;
  }
}

class Question {
    var $QuestionID;
    var $QuestionText;
    var $QuestionTypeID;
    var $QuestionTypeText;
    var $ShowExternal;
    var $AnswerID;
    var $Price;
    var $DefaultAlternative;
    var $AnswerText;
    var $SortIndex;

  function __construct() {
      $this->QuestionID = 0;
      $this->QuestionTypeID = 0;
      $this->ShowExternal = false;
      $this->AnswerID = 0;
      $this->Price = null;
      $this->DefaultAlternative = false;
      $this->SortIndex = 0;
  }
}

class Region {
    var $RegionID;
    var $RegionName;

  function __construct() {
      $this->RegionID = 0;
  }
}

class RentObject {
    var $ObjectID;
    var $ItemNr;
    var $ObjectName;
    var $PublicName;
    var $CategoryID;
    var $DepotID;
    var $CategoryName;
    var $GroupObject;
    var $SalesObject;
    var $BarcodreNr;
    var $Notes;

  function __construct() {
      $this->ObjectID = 0;
      $this->CategoryID = 0;
      $this->DepotID = 0;
      $this->GroupObject = false;
      $this->SalesObject = false;
  }
}

class ReportDoc {
    var $ReportDocID;
    var $ReportName;
    var $PublicName;
    var $ReportDocTypeID;

  function __construct() {
      $this->ReportDocID = 0;
      $this->ReportDocTypeID = 0;
  }
}

class ReportParameter {
    var $Name;
    var $Value;

  function __construct() {

  }
}

class SalesObjectBookingInfo {
    var $ObjectID;
    var $Quantity;

  function __construct() {
      $this->ObjectID = 0;
      $this->Quantity = 0;
  }
}



class SubEvent {
    var $EventID;
    var $ParentEventID;
    var $OccasionID;
    var $ObjectID;
    var $ObjectName;
    var $Description;
    var $StartDate;
    var $EndDate;
    var $MaxParticipantNr;
    var $TotalParticipantNr;
    var $SelectedByDefault;
    var $MandatoryParticipation;

  function __construct() {
      $this->EventID = 0;
      $this->ParentEventID = 0;
      $this->OccasionID = 0;
      $this->ObjectID = 0;
      $this->StartDate = date('c');
      $this->EndDate = date('c');
      $this->MaxParticipantNr = 0;
      $this->TotalParticipantNr = 0;
      $this->SelectedByDefault = false;
      $this->MandatoryParticipation = false;
  }
}

class SubEventInfo {
    var $EventID;
    var $OccasionPriceNameLnkID;

  function __construct() {
      $this->EventID = 0;
      $this->OccasionPriceNameLnkID = null;
  }
}

class SubEventPerson {
    var $SubEvents;
    var $Reference;
    var $OccasionPriceNameLnkID;
    var $PersonID;
    var $CustomerID;
    var $CustomerContactID;
    var $PersonName;
    var $PersonEmail;
    var $PersonPhone;
    var $PersonMobile;
    var $PersonCivicRegistrationNumber;
    var $PersonAddress1;
    var $PersonAddress2;
    var $PersonZip;
    var $PersonCity;
    var $PersonPosition;
    var $PersonEmployeeNumber;
    var $Attribute;

  function __construct() {
      $this->SubEvents = array();
      $this->OccasionPriceNameLnkID = null;
      $this->PersonID = 0;
      $this->CustomerID = 0;
      $this->CustomerContactID = null;
      $this->Attribute = array();
  }
}

class SubEventSeatPerson {
    var $SubEvents;
    var $Reference;
    var $OccasionPriceNameLnkID;
    var $SeatID;
    var $PersonID;
    var $CustomerID;
    var $CustomerContactID;
    var $PersonName;
    var $PersonEmail;
    var $PersonPhone;
    var $PersonMobile;
    var $PersonCivicRegistrationNumber;
    var $PersonAddress1;
    var $PersonAddress2;
    var $PersonZip;
    var $PersonCity;
    var $PersonPosition;
    var $PersonEmployeeNumber;
    var $Attribute;

  function __construct() {
      $this->SubEvents = array();
      $this->OccasionPriceNameLnkID = null;
      $this->SeatID = null;
      $this->PersonID = 0;
      $this->CustomerID = 0;
      $this->CustomerContactID = null;
      $this->Attribute = array();
  }
}

class Subject {
    var $SubjectID;
    var $SubjectName;
    var $SubjectDescription;
    var $SubjectTitle;
    var $MetaDescription;

  function __construct() {
      $this->SubjectID = 0;
  }
}

class UnavailableDate {
    var $Description;
    var $StartDate;
    var $EndDate;

  function __construct() {
      $this->StartDate = date('c');
      $this->EndDate = date('c');
  }
}

class UnavailableDateResponse {
    var $Success;
    var $ErrorMessage;

  function __construct() {
      $this->Success = false;
  }
}

class UnavailablePersonnelDate {
    var $PersonnelID;
    var $UnavailableDateID;
    var $Description;
    var $StartDate;
    var $EndDate;

  function __construct() {
      $this->PersonnelID = 0;
      $this->UnavailableDateID = 0;
      $this->StartDate = date('c');
      $this->EndDate = date('c');
  }
}

class UnnamedParticipant {
    var $EventParticipantID;
    var $EventCustomerLnkID;
    var $EventID;
    var $OccasionPriceNameLnkID;
    var $Quantity;
    var $Canceled;
    var $CustomerID;
    var $Created;

  function __construct() {
      $this->EventParticipantID = 0;
      $this->EventCustomerLnkID = 0;
      $this->EventID = 0;
      $this->OccasionPriceNameLnkID = 0;
      $this->Quantity = 0;
      $this->Canceled = false;
      $this->CustomerID = 0;
      $this->Created = date('c');
  }
}

class UpdateSalesBookingInfo {
    var $ObjectId;
    var $Quantity;
    var $EventCustomerLnkId;

  function __construct() {
      $this->ObjectId = 0;
      $this->Quantity = 0;
      $this->EventCustomerLnkId = 0;
  }
}

class UserAttribute {
    var $UserAttributeID;
    var $UserID;
    var $AttributeChecked;
    var $AttributeDate;
    var $AttributeID;
    var $AttributeTypeID;
    var $AttributeTypeDescription;
    var $AttributeOwnerTypeID;
    var $AttributeOwnerTypeDescription;
    var $AttributeDescription;
    var $AttributeValue;
    var $AttributeAlternative;

  function __construct() {
      $this->UserAttributeID = null;
      $this->UserID = 0;
      $this->AttributeChecked = false;
      $this->AttributeDate = null;
      $this->AttributeID = 0;
      $this->AttributeTypeID = 0;
      $this->AttributeOwnerTypeID = 0;
      $this->AttributeAlternative = array();
  }
}

class UserLocation {
    var $UserID;
    var $LocationID;

  function __construct() {
      $this->UserID = 0;
      $this->LocationID = 0;
  }
}

class XFilter
{
    var $FilterName = '';
    var $FilterCondition = '';
    var $FilterValue = '';

    public function __construct($name, $condition, $value)
    {
        $this->FilterName      = $name;
        $this->FilterCondition = $condition;
        $this->FilterValue     = $value;
    }
}

class XFiltering
{
    var $pre = '<Filtering>';
    var $post = '</Filtering>';

    var $filterItems = array();

    public function __construct()
    {
        $this->filterItems = array();
    }

    public function AddItem(XFilter $filter)
    {
        $this->filterItems[] = $filter;
    }

    public function ToString()
    {
        $xml = $this->pre;
        foreach ($this->filterItems as $filter) {
            $xml .= '<Filter>';
            $xml .= '<FilterName>' . $filter->FilterName . '</FilterName>';
            $xml .= '<FilterCondition>' . str_replace(Array(
                "<",
                ">"
            ), Array(
                "&lt;",
                "&gt;"
            ), $filter->FilterCondition) . '</FilterCondition>';
            $xml .= '<FilterValue>' . $filter->FilterValue . '</FilterValue>';
            $xml .= '</Filter>';
        }
        $xml .= $this->post;

        return $xml;
    }
}

class XSort
{
    var $SortName = '';
    var $SortDirection = 'ASC';

    function __construct($name, $direction = 'ASC')
    {
        $this->SortName      = $name;
        $this->SortDirection = $direction;
    }
}

class XSorting
{
    var $pre = '<Sorting>';
    var $post = '</Sorting>';

    var $sortItems = array();

    public function __construct()
    {
        $this->sortItems = array();
    }

    public function AddItem(XSort $sort)
    {
        $this->sortItems[] = $sort;
    }

    public function ToString()
    {
        $xml = $this->pre;
        foreach ($this->sortItems as $sort) {
            $xml .= '<Sort>';
            $xml .= '<SortName>' . $sort->SortName . '</SortName>';
            $xml .= '<SortDirection>' . $sort->SortDirection . '</SortDirection>';
            $xml .= '</Sort>';
        }
        $xml .= $this->post;

        return $xml;
    }
}