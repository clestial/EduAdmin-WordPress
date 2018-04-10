<?php

function edu_validate_column( $column_type, $column ) {
	$valid_columns   = edu_get_valid_columns( $column_type );
	$replace_columns = edu_get_replacement_columns( $column_type );
	if ( key_exists( strtolower( $column ), $replace_columns ) ) {
		return $replace_columns[ strtolower( $column ) ];
	}

	if ( ! in_array( $column, $valid_columns, true ) ) {
		return false;
	}

	return $column;
}

function edu_get_valid_columns( $column_type ) {
	switch ( $column_type ) {
		case 'event':
			return array(
				'EventId',
				'EventName',
				'CourseTemplateId',
				'CourseName',
				'InternalCourseName',
				'CategoryId',
				'CategoryName',
				'ShowOnWeb',
				'ShowOnWebInternal',
				'Notes',
				'LocationId',
				'LocationAddressId',
				'City',
				'StartDate',
				'EndDate',
				'MinParticipantNumber',
				'MaxParticipantNumber',
				'NumberOfBookedParticipants',
				'ParticipantNumberLeft',
				'StatusId',
				'StatusText',
				'AddressName',
				'ConfirmedAddress',
				'CustomerId',
				'UsePriceNameMaxParticipantNumber',
				'LastApplicationDate',
				'CompanySpecific',
				'AllowOverlappingSessions',
				'HasPublicPriceName',
				'PersonnelMessage',
				'ProjectNumber',
				'ParticipantVat',
			);
		case 'course':
			return array(
				'CourseTemplateId',
				'EducationNumber',
				'Shortening',
				'CourseName',
				'InternalCourseName',
				'CourseDescription',
				'CourseDescriptionShort',
				'CourseGoal',
				'TargetGroup',
				'CourseAfter',
				'Prerequisites',
				'Quote',
				'Notes',
				'ShowOnWeb',
				'ShowOnWebInternal',
				'CategoryId',
				'CategoryName',
				'ImageUrl',
				'Days',
				'StartTime',
				'EndTime',
				'RequireCivicRegistrationNumber',
				'Department',
				'MaxParticipantNumber',
				'MinParticipantNumber',
				'CourseLevelId',
				'ParticipantVat',
				'SortIndex',
			);
	}
}

function edu_get_replacement_columns( $column_type ) {
	switch ( $column_type ) {
		case 'event':
			return array(
				'periodstart' => 'StartDate',
				'periodend'   => 'EndDate,',
			);
		case 'course':
			return array(
				'objectname' => 'CourseName',
			);
	}
}
