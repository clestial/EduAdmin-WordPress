<?php

/**
 * Class EduAdminToken
 */
class EduAdminToken {
	/**
	 * @var string|null
	 */
	public $AccessToken = null;
	/**
	 * @var string|null
	 */
	public $TokenType = null;
	/**
	 * @var integer|null
	 */
	public $ExpiresIn = null;
	public $UserName  = null;
	public $Issued    = null;
	public $Expires   = null;

	/**
	 * EduAdminToken constructor.
	 *
	 * @param $obj
	 */
	public function __construct( $obj ) {
		if ( null === $obj ) {
			die( 'Could not deserialize the token' );
		}

		if ( ! empty( $obj['access_token'] ) ) {
			$this->AccessToken = $obj['access_token'];
		}
		if ( ! empty( $obj['token_type'] ) ) {
			$this->TokenType = $obj['token_type'];
		}
		if ( ! empty( $obj['expires_in'] ) ) {
			$this->ExpiresIn = $obj['expires_in'];
		}
		if ( ! empty( $obj['userName'] ) ) {
			$this->UserName = $obj['userName'];
		}
		if ( ! empty( $obj['.issued'] ) ) {
			$this->Issued = strtotime( $obj['.issued'] );
		}
		if ( ! empty( $obj['.expires'] ) ) {
			$this->Expires = strtotime( $obj['.expires'] );
		}
	}

	/**
	 * Checks if token is valid
	 * @return bool
	 */
	public function IsValid() {
		if ( ! empty( $this->Expires ) ) {
			return strtotime( 'now' ) < $this->Expires;
		}

		return false;
	}
}

/**
 * Class EduAdmin_ODataHolder
 */
class EduAdmin_ODataHolder {
	/** @var EduAdmin_OData_Bookings */
	public $Bookings = null;
	/** @var EduAdmin_OData_Categories */
	public $Categories = null;
	/** @var EduAdmin_OData_CourseLevels */
	public $CourseLevels = null;
	/** @var EduAdmin_OData_CourseTemplates */
	public $CourseTemplates = null;
	/** @var EduAdmin_OData_CustomerGroups */
	public $CustomerGroups = null;
	/** @var EduAdmin_OData_Customers */
	public $Customers = null;
	/** @var EduAdmin_OData_CustomFields */
	public $CustomFields = null;
	/** @var EduAdmin_OData_Events */
	public $Events = null;
	/** @var EduAdmin_OData_Grades */
	public $Grades = null;
	/** @var EduAdmin_OData_InterestRegistrations */
	public $InterestRegistrations = null;
	/** @var EduAdmin_OData_Locations */
	public $Locations = null;
	/** @var EduAdmin_OData_Personnel */
	public $Personnel = null;
	/** @var EduAdmin_OData_Persons */
	public $Persons = null;
	/** @var EduAdmin_OData_ProgrammeBookings */
	public $ProgrammeBookings = null;
	/** @var EduAdmin_OData_Programmes */
	public $Programmes = null;
	/** @var EduAdmin_OData_ProgrammeStarts */
	public $ProgrammeStarts = null;
	/** @var EduAdmin_OData_Regions */
	public $Regions = null;
	/** @var EduAdmin_OData_Reports */
	public $Reports = null;
	/** @var EduAdmin_OData_Subjects */
	public $Subjects = null;
}

/**
 * Class EduAdmin_RESTHolder
 */
class EduAdmin_RESTHolder {
	/** @var EduAdmin_REST_Booking */
	public $Booking = null;
	/** @var EduAdmin_REST_Coupon */
	public $Coupon = null;
	/** @var EduAdmin_REST_Customer */
	public $Customer = null;
	/** @var EduAdmin_REST_Event */
	public $Event = null;
	/** @var EduAdmin_REST_InterestRegistration */
	public $InterestRegistration = null;
	/** @var EduAdmin_REST_Organisation */
	public $Organisation = null;
	/** @var EduAdmin_REST_Participant */
	public $Participant = null;
	/** @var EduAdmin_REST_Person */
	public $Person = null;
	/** @var EduAdmin_REST_Personnel */
	public $Personnel = null;
	/** @var EduAdmin_REST_ProgrammeBooking */
	public $ProgrammeBooking = null;
	/** @var EduAdmin_REST_ProgrammeStart */
	public $ProgrammeStart = null;
	/** @var EduAdmin_REST_Report */
	public $Report = null;
}

/**
 * Class EduAdmin_Data_Voucher
 */
class EduAdmin_Data_Voucher {
	/** @var integer|null $VoucherId */
	public $VoucherId = null;
	/** @var string|null $Description */
	public $Description = null;
	/** @var string|null $ValidFrom */
	public $ValidFrom = null;
	/** @var string|null $ValidTo */
	public $ValidTo = null;
	/** @var integer|null $PersonId */
	public $PersonId = null;
	/** @var integer|null $Price */
	public $Price = null;
	/** @var integer|null $DiscountPercent */
	public $DiscountPercent = null;
	/** @var integer|null $CreditsStartValue */
	public $CreditsStartValue = null;
	/** @var integer|null $CreditsLeft */
	public $CreditsLeft = null;
	/** @var string|null $Created */
	public $Created = null;
}

/**
 * Class EduAdmin_Data_MailAdvanced
 */
class EduAdmin_Data_MailAdvanced {
	/** @var integer|null $EmailTemplateId */
	public $EmailTemplateId = null;
	/** @var string|null $FromEmailAddress */
	public $FromEmailAddress = null;
	/** @var string[]|null $ToEmailAddresses */
	public $ToEmailAddresses = null;
	/** @var string[]|null $CopyToEmailAddresses */
	public $CopyToEmailAddresses = null;
}

/**
 * Class EduAdmin_Data_Mail
 */
class EduAdmin_Data_Mail {
	/** @var boolean|null $SendToParticipants */
	public $SendToParticipants = null;
	/** @var boolean|null $SendToCustomer */
	public $SendToCustomer = null;
	/** @var boolean|null $SendToCustomerContact */
	public $SendToCustomerContact = null;
	/** @var string[]|null $SendEmailCopyTo */
	public $SendEmailCopyTo = null;
}

/**
 * Class EduAdmin_Data_UnnamedParticipants
 */
class EduAdmin_Data_UnnamedParticipants {
	/** @var integer|null $PriceNameId */
	public $PriceNameId = null;
	/** @var integer|null $Quantity */
	public $Quantity = null;
}

/**
 * Class EduAdmin_Data_PatchBooking
 */
class EduAdmin_Data_PatchBooking {
	/** @var boolean|null $Preliminary */
	public $Preliminary = null;
	/** @var boolean|null $MarkedAsInvoiced */
	public $MarkedAsInvoiced = null;
	/** @var boolean|null $Paid */
	public $Paid = null;
	/** @var string|null $Notes */
	public $Notes = null;
	/** @var string|null $Reference */
	public $Reference = null;
	/** @var string|null $PurchaseOrderNumber */
	public $PurchaseOrderNumber = null;
	/** @var string|null $PostponedBillingDate */
	public $PostponedBillingDate = null;
}

/**
 * Class EduAdmin_Data_BookingData
 */
class EduAdmin_Data_BookingData {
	/** @var integer|null $EventId */
	public $EventId = null;
	/** @var string|null $Reference */
	public $Reference = null;
	/** @var integer|null $PaymentMethodId */
	public $PaymentMethodId = null;
	/** @var integer|null $PriceNameId */
	public $PriceNameId = null;
	/** @var string|null $Notes */
	public $Notes = null;
	/** @var string|null $PurchaseOrderNumber */
	public $PurchaseOrderNumber = null;
	/** @var string|null $CouponCode */
	public $CouponCode = null;
	/** @var string|null $PostponedBillingDate */
	public $PostponedBillingDate = null;
	/** @var integer|null $VoucherId */
	public $VoucherId = null;
	/** @var boolean|null $Preliminary */
	public $Preliminary = null;
	/** @var EduAdmin_Data_Options|null $Options */
	public $Options = null;
	/** @var EduAdmin_Data_Customer|null $Customer */
	public $Customer = null;
	/** @var EduAdmin_Data_ContactPerson|null $ContactPerson */
	public $ContactPerson = null;
	/** @var EduAdmin_Data_Mail|null $SendConfirmationEmail */
	public $SendConfirmationEmail = null;
	/** @var EduAdmin_Data_Participants[]|null $Participants */
	public $Participants = null;
	/** @var EduAdmin_Data_UnnamedParticipants[]|null $UnnamedParticipants */
	public $UnnamedParticipants = null;
	/** @var EduAdmin_Data_Answers[]|null $Answers */
	public $Answers = null;
	/** @var EduAdmin_Data_Accessories[]|null $Accessories */
	public $Accessories = null;
}

/**
 * Class EduAdmin_Data_Options
 */
class EduAdmin_Data_Options {
	/** @var boolean|null $SkipDuplicateMatchOnCustomer */
	public $SkipDuplicateMatchOnCustomer = null;
	/** @var boolean|null $IgnoreRemainingSpots */
	public $IgnoreRemainingSpots = null;
	/** @var boolean|null $SkipDuplicateMatchOnPersons */
	public $SkipDuplicateMatchOnPersons = null;
	/** @var boolean|null $IgnoreIfPersonAlreadyBooked */
	public $IgnoreIfPersonAlreadyBooked = null;
	/** @var boolean|null $IgnoreMandatoryQuestions */
	public $IgnoreMandatoryQuestions = null;
}

/**
 * Class EduAdmin_Data_Sessions
 */
class EduAdmin_Data_Sessions {
	/** @var integer|null $SessionId */
	public $SessionId = null;
	/** @var integer|null $PriceNameId */
	public $PriceNameId = null;
}

/**
 * Class EduAdmin_Data_CustomFields
 */
class EduAdmin_Data_CustomFields {
	/** @var integer|null $CustomFieldId */
	public $CustomFieldId = null;
	/** @var object|null $CustomFieldValue */
	public $CustomFieldValue = null;
}

/**
 * Class EduAdmin_Data_Answers
 */
class EduAdmin_Data_Answers {
	/** @var integer|null $AnswerId */
	public $AnswerId = null;
	/** @var object|null $AnswerValue */
	public $AnswerValue = null;
	/** @var integer|null $AnswerNumber */
	public $AnswerNumber = null;
	/** @var string|null $AnswerTime */
	public $AnswerTime = null;
}

/**
 * Class EduAdmin_Data_Participants
 */
class EduAdmin_Data_Participants {
	/** @var integer|null $PersonId */
	public $PersonId = null;
	/** @var string|null $FirstName */
	public $FirstName = null;
	/** @var string|null $LastName */
	public $LastName = null;
	/** @var string|null $Address */
	public $Address = null;
	/** @var string|null $Address2 */
	public $Address2 = null;
	/** @var string|null $Zip */
	public $Zip = null;
	/** @var string|null $City */
	public $City = null;
	/** @var string|null $Mobile */
	public $Mobile = null;
	/** @var string|null $Phone */
	public $Phone = null;
	/** @var string|null $Email */
	public $Email = null;
	/** @var string|null $CivicRegistrationNumber */
	public $CivicRegistrationNumber = null;
	/** @var string|null $EmployeeNumber */
	public $EmployeeNumber = null;
	/** @var string|null $JobTitle */
	public $JobTitle = null;
	/** @var string|null $Country */
	public $Country = null;
	/** @var string|null $Password */
	public $Password = null;
	/** @var integer|null $PriceNameId */
	public $PriceNameId = null;
	/** @var string|null $Reference */
	public $Reference = null;
	/** @var integer|null $SeatId */
	public $SeatId = null;
	/** @var boolean|null $CanLogin */
	public $CanLogin = null;
	/** @var EduAdmin_Data_Sessions[]|null $Sessions */
	public $Sessions = null;
	/** @var EduAdmin_Data_CustomFields[]|null $CustomFields */
	public $CustomFields = null;
	/** @var EduAdmin_Data_Answers[]|null $Answers */
	public $Answers = null;
}

/**
 * Class EduAdmin_Data_ParticipantData
 */
class EduAdmin_Data_ParticipantData {
	/** @var string|null $FirstName */
	public $FirstName = null;
	/** @var string|null $LastName */
	public $LastName = null;
	/** @var string|null $Address */
	public $Address = null;
	/** @var string|null $Address2 */
	public $Address2 = null;
	/** @var string|null $Zip */
	public $Zip = null;
	/** @var string|null $City */
	public $City = null;
	/** @var string|null $Mobile */
	public $Mobile = null;
	/** @var string|null $Phone */
	public $Phone = null;
	/** @var string|null $Email */
	public $Email = null;
	/** @var string|null $CivicRegistrationNumber */
	public $CivicRegistrationNumber = null;
	/** @var string|null $EmployeeNumber */
	public $EmployeeNumber = null;
	/** @var string|null $JobTitle */
	public $JobTitle = null;
	/** @var string|null $Country */
	public $Country = null;
	/** @var string|null $Reference */
	public $Reference = null;
	/** @var string|null $Password */
	public $Password = null;
	/** @var boolean|null $CanLogin */
	public $CanLogin = null;
	/** @var EduAdmin_Data_CustomFields[]|null $CustomFields */
	public $CustomFields = null;
}

/**
 * Class EduAdmin_Data_Accessories
 */
class EduAdmin_Data_Accessories {
	/** @var integer|null $AccessoryId */
	public $AccessoryId = null;
	/** @var integer|null $Quantity */
	public $Quantity = null;
}

/**
 * Class EduAdmin_Data_BookingParticipants
 */
class EduAdmin_Data_BookingParticipants {
	/** @var EADBP_Options|null $Options */
	public $Options = null;
	/** @var EduAdmin_Data_Participants[]|null $Participants */
	public $Participants = null;
}

/**
 * Class EADBP_Options
 */
class EADBP_Options {
	/** @var boolean|null $IgnoreRemainingSpots */
	public $IgnoreRemainingSpots = null;
	/** @var boolean|null $SkipDuplicateMatchOnPersons */
	public $SkipDuplicateMatchOnPersons = null;
	/** @var boolean|null $IgnoreIfPersonAlreadyBooked */
	public $IgnoreIfPersonAlreadyBooked = null;
	/** @var boolean|null $IgnoreMandatoryQuestions */
	public $IgnoreMandatoryQuestions = null;
}

/**
 * Class EduAdmin_Data_ConvertUnnamedParticipants
 */
class EduAdmin_Data_ConvertUnnamedParticipants {
	/** @var EADCUP_Options|null $Options */
	public $Options = null;
	/** @var EADCUP_NamedUnnamedParticipants[]|null $NamedUnnamedParticipants */
	public $NamedUnnamedParticipants = null;
}

/**
 * Class EADCUP_Options
 */
class EADCUP_Options {
	/** @var boolean|null $SkipDuplicateMatchOnPersons */
	public $SkipDuplicateMatchOnPersons = null;
	/** @var boolean|null $IgnoreIfPersonAlreadyBooked */
	public $IgnoreIfPersonAlreadyBooked = null;
	/** @var boolean|null $IgnoreMandatoryQuestions */
	public $IgnoreMandatoryQuestions = null;
}

/**
 * Class EADCUP_NamedUnnamedParticipants
 */
class EADCUP_NamedUnnamedParticipants {
	/** @var integer|null $PriceNameId */
	public $PriceNameId = null;
	/** @var integer|null $PersonId */
	public $PersonId = null;
	/** @var string|null $FirstName */
	public $FirstName = null;
	/** @var string|null $LastName */
	public $LastName = null;
	/** @var string|null $Address */
	public $Address = null;
	/** @var string|null $Address2 */
	public $Address2 = null;
	/** @var string|null $Zip */
	public $Zip = null;
	/** @var string|null $City */
	public $City = null;
	/** @var string|null $Mobile */
	public $Mobile = null;
	/** @var string|null $Phone */
	public $Phone = null;
	/** @var string|null $Email */
	public $Email = null;
	/** @var string|null $CivicRegistrationNumber */
	public $CivicRegistrationNumber = null;
	/** @var string|null $EmployeeNumber */
	public $EmployeeNumber = null;
	/** @var string|null $JobTitle */
	public $JobTitle = null;
	/** @var string|null $Country */
	public $Country = null;
	/** @var string|null $Password */
	public $Password = null;
	/** @var string|null $Reference */
	public $Reference = null;
	/** @var integer|null $SeatId */
	public $SeatId = null;
	/** @var boolean|null $CanLogin */
	public $CanLogin = null;
	/** @var EduAdmin_Data_Sessions[]|null $Sessions */
	public $Sessions = null;
	/** @var EduAdmin_Data_CustomFields[]|null $CustomFields */
	public $CustomFields = null;
	/** @var EduAdmin_Data_Answers[]|null $Answers */
	public $Answers = null;
}

/**
 * Class EduAdmin_Data_Customer
 */
class EduAdmin_Data_Customer {
	/** @var string|null $CustomerName */
	public $CustomerName = null;
	/** @var string|null $CustomerNumber */
	public $CustomerNumber = null;
	/** @var string|null $Address */
	public $Address = null;
	/** @var string|null $Address2 */
	public $Address2 = null;
	/** @var string|null $Zip */
	public $Zip = null;
	/** @var string|null $City */
	public $City = null;
	/** @var string|null $Country */
	public $Country = null;
	/** @var string|null $OrganisationNumber */
	public $OrganisationNumber = null;
	/** @var string|null $Email */
	public $Email = null;
	/** @var string|null $Phone */
	public $Phone = null;
	/** @var string|null $Mobile */
	public $Mobile = null;
	/** @var string|null $Notes */
	public $Notes = null;
	/** @var string|null $Web */
	public $Web = null;
	/** @var integer|null $CustomerGroupId */
	public $CustomerGroupId = null;
	/** @var string|null $CustomerGroupName */
	public $CustomerGroupName = null;
	/** @var EduAdmin_Data_BillingInfo|null $BillingInfo */
	public $BillingInfo = null;
	/** @var EduAdmin_Data_CustomFields[]|null $CustomFields */
	public $CustomFields = null;
}

/**
 * Class EduAdmin_Data_BillingInfo
 */
class EduAdmin_Data_BillingInfo {
	/** @var integer|null $OptionalBillingCustomerId */
	public $OptionalBillingCustomerId = null;
	/** @var string|null $CustomerName */
	public $CustomerName = null;
	/** @var string|null $Address */
	public $Address = null;
	/** @var string|null $Address2 */
	public $Address2 = null;
	/** @var string|null $Zip */
	public $Zip = null;
	/** @var string|null $City */
	public $City = null;
	/** @var string|null $Country */
	public $Country = null;
	/** @var string|null $OrganisationNumber */
	public $OrganisationNumber = null;
	/** @var string|null $VatNumber */
	public $VatNumber = null;
	/** @var string|null $Reference */
	public $Reference = null;
	/** @var string|null $OurReference */
	public $OurReference = null;
	/** @var string|null $EdiReference */
	public $EdiReference = null;
	/** @var string|null $Email */
	public $Email = null;
	/** @var boolean|null $NoVat */
	public $NoVat = null;
	/** @var integer|null $InvoiceDeliveryMethodId */
	public $InvoiceDeliveryMethodId = null;
}

/**
 * Class EduAdmin_Data_InterestRegistrationBasic
 */
class EduAdmin_Data_InterestRegistrationBasic {
	/** @var integer|null $CourseTemplateId */
	public $CourseTemplateId = null;
	/** @var integer|null $EventId */
	public $EventId = null;
	/** @var integer|null $NumberOfParticipants */
	public $NumberOfParticipants = null;
	/** @var string|null $CompanyName */
	public $CompanyName = null;
	/** @var string|null $FirstName */
	public $FirstName = null;
	/** @var string|null $LastName */
	public $LastName = null;
	/** @var string|null $Email */
	public $Email = null;
	/** @var string|null $Phone */
	public $Phone = null;
	/** @var string|null $Mobile */
	public $Mobile = null;
	/** @var string|null $Notes */
	public $Notes = null;
}

/**
 * Class EduAdmin_Data_InterestRegistrationComplete
 */
class EduAdmin_Data_InterestRegistrationComplete {
	/** @var integer|null $EventId */
	public $EventId = null;
	/** @var string|null $Reference */
	public $Reference = null;
	/** @var integer|null $PaymentMethodId */
	public $PaymentMethodId = null;
	/** @var integer|null $PriceNameId */
	public $PriceNameId = null;
	/** @var string|null $Notes */
	public $Notes = null;
	/** @var string|null $PurchaseOrderNumber */
	public $PurchaseOrderNumber = null;
	/** @var string|null $CouponCode */
	public $CouponCode = null;
	/** @var string|null $PostponedBillingDate */
	public $PostponedBillingDate = null;
	/** @var EADIRC_Options|null $Options */
	public $Options = null;
	/** @var EduAdmin_Data_Customer|null $Customer */
	public $Customer = null;
	/** @var EduAdmin_Data_ContactPerson|null $ContactPerson */
	public $ContactPerson = null;
	/** @var EduAdmin_Data_Mail|null $SendConfirmationEmail */
	public $SendConfirmationEmail = null;
	/** @var EduAdmin_Data_Participants[]|null $Participants */
	public $Participants = null;
	/** @var EduAdmin_Data_UnnamedParticipants[]|null $UnnamedParticipants */
	public $UnnamedParticipants = null;
	/** @var EduAdmin_Data_Answers[]|null $Answers */
	public $Answers = null;
	/** @var EduAdmin_Data_Accessories[]|null $Accessories */
	public $Accessories = null;
}

/**
 * Class EADIRC_Options
 */
class EADIRC_Options {
	/** @var boolean|null $SkipDuplicateMatchOnCustomer */
	public $SkipDuplicateMatchOnCustomer = null;
	/** @var boolean|null $SkipDuplicateMatchOnPersons */
	public $SkipDuplicateMatchOnPersons = null;
	/** @var boolean|null $IgnoreIfPersonAlreadyBooked */
	public $IgnoreIfPersonAlreadyBooked = null;
	/** @var boolean|null $IgnoreMandatoryQuestions */
	public $IgnoreMandatoryQuestions = null;
}

/**
 * Class EduAdmin_Data_ContactPerson
 */
class EduAdmin_Data_ContactPerson {
	/** @var boolean|null $AddAsParticipant */
	public $AddAsParticipant = null;
	/** @var integer|null $PersonId */
	public $PersonId = null;
	/** @var string|null $FirstName */
	public $FirstName = null;
	/** @var string|null $LastName */
	public $LastName = null;
	/** @var string|null $Address */
	public $Address = null;
	/** @var string|null $Address2 */
	public $Address2 = null;
	/** @var string|null $Zip */
	public $Zip = null;
	/** @var string|null $City */
	public $City = null;
	/** @var string|null $Mobile */
	public $Mobile = null;
	/** @var string|null $Phone */
	public $Phone = null;
	/** @var string|null $Email */
	public $Email = null;
	/** @var string|null $CivicRegistrationNumber */
	public $CivicRegistrationNumber = null;
	/** @var string|null $EmployeeNumber */
	public $EmployeeNumber = null;
	/** @var string|null $JobTitle */
	public $JobTitle = null;
	/** @var string|null $Country */
	public $Country = null;
	/** @var string|null $Password */
	public $Password = null;
	/** @var integer|null $PriceNameId */
	public $PriceNameId = null;
	/** @var string|null $Reference */
	public $Reference = null;
	/** @var integer|null $SeatId */
	public $SeatId = null;
	/** @var boolean|null $CanLogin */
	public $CanLogin = null;
	/** @var EduAdmin_Data_Sessions[]|null $Sessions */
	public $Sessions = null;
	/** @var EduAdmin_Data_CustomFields[]|null $CustomFields */
	public $CustomFields = null;
	/** @var EduAdmin_Data_Answers[]|null $Answers */
	public $Answers = null;
}

/**
 * Class EduAdmin_Data_ArrivalStatus
 */
class EduAdmin_Data_ArrivalStatus {
	/** @var integer|null $EventDateId */
	public $EventDateId = null;
	/** @var integer|null $ParticipantId */
	public $ParticipantId = null;
	/** @var string|null $Comment */
	public $Comment = null;
}

/**
 * Class EduAdmin_Data_GradeData
 */
class EduAdmin_Data_GradeData {
	/** @var integer[]|null $ParticipantIds */
	public $ParticipantIds = null;
	/** @var integer|null $GradeId */
	public $GradeId = null;
}

/**
 * Class EduAdmin_Data_Person
 */
class EduAdmin_Data_Person {
	/** @var string|null $FirstName */
	public $FirstName = null;
	/** @var string|null $LastName */
	public $LastName = null;
	/** @var string|null $Address */
	public $Address = null;
	/** @var string|null $Address2 */
	public $Address2 = null;
	/** @var string|null $Zip */
	public $Zip = null;
	/** @var string|null $City */
	public $City = null;
	/** @var string|null $Mobile */
	public $Mobile = null;
	/** @var string|null $Phone */
	public $Phone = null;
	/** @var string|null $Email */
	public $Email = null;
	/** @var string|null $CivicRegistrationNumber */
	public $CivicRegistrationNumber = null;
	/** @var string|null $EmployeeNumber */
	public $EmployeeNumber = null;
	/** @var string|null $JobTitle */
	public $JobTitle = null;
	/** @var string|null $Country */
	public $Country = null;
	/** @var string|null $Password */
	public $Password = null;
	/** @var integer|null $CustomerId */
	public $CustomerId = null;
	/** @var boolean|null $CanLogin */
	public $CanLogin = null;
	/** @var EduAdmin_Data_CustomFields[]|null $CustomFields */
	public $CustomFields = null;
}

/**
 * Class EduAdmin_Data_Login
 */
class EduAdmin_Data_Login {
	/** @var string|null $Email */
	public $Email = null;
	/** @var string|null $Password */
	public $Password = null;
}

/**
 * Class EduAdmin_Data_ProgrammeBooking
 */
class EduAdmin_Data_ProgrammeBooking {
	/** @var integer|null $ProgrammeStartId */
	public $ProgrammeStartId = null;
	/** @var boolean|null $Preliminary */
	public $Preliminary = null;
	/** @var integer|null $PaymentMethodId */
	public $PaymentMethodId = null;
	/** @var integer|null $PriceNameId */
	public $PriceNameId = null;
	/** @var string|null $Notes */
	public $Notes = null;
	/** @var EduAdmin_Data_Customer|null $Customer */
	public $Customer = null;
	/** @var EduAdmin_Data_ContactPerson|null $ContactPerson */
	public $ContactPerson = null;
	/** @var EduAdmin_Data_Options|null $Options */
	public $Options = null;
	/** @var EduAdmin_Data_Mail|null $SendConfirmationEmail */
	public $SendConfirmationEmail = null;
	/** @var EduAdmin_Data_Participants[]|null $Participants */
	public $Participants = null;
	/** @var EduAdmin_Data_PriceRows[]|null $PriceRows */
	public $PriceRows = null;
	/** @var EduAdmin_Data_Answers[]|null $Answers */
	public $Answers = null;
}

/**
 * Class EduAdmin_Data_PriceRows
 */
class EduAdmin_Data_PriceRows {
	/** @var integer|null $PricePerUnit */
	public $PricePerUnit = null;
	/** @var integer|null $Quantity */
	public $Quantity = null;
	/** @var string|null $InvoiceDate */
	public $InvoiceDate = null;
	/** @var string|null $Description */
	public $Description = null;
	/** @var integer|null $VatPercent */
	public $VatPercent = null;
	/** @var string|null $ItemNr */
	public $ItemNr = null;
}

/**
 * Class EduAdmin_Data_ReportOptions
 */
class EduAdmin_Data_ReportOptions {
	/** @var string */
	public $ReportName = null;
	/** @var EduAdmin_Data_ReportOptionParameter[] */
	public $Parameters = null;
}

/**
 * Class EduAdmin_Data_ReportOptionParameter
 */
class EduAdmin_Data_ReportOptionParameter {
	/** @var string */
	public $Name = null;
	/** @var string */
	public $Value = null;
}

/**
 * Class EduAdmin_Data_ProgrammeBooking_Patch
 */
class EduAdmin_Data_ProgrammeBooking_Patch {
	/** @var boolean|null $Preliminary */
	public $Preliminary = null;
	/** @var boolean|null $Paid */
	public $Paid = null;
	/** @var string|null $Notes */
	public $Notes = null;
}