<?php
$surl     = get_home_url();
$cat      = get_option( 'eduadmin-rewriteBaseUrl' );
$base_url = $surl . '/' . $cat;

$fetch_months = get_option( 'eduadmin-monthsToFetch', 6 );
if ( ! is_numeric( $fetch_months ) ) {
	$fetch_months = 6;
}

$filters = array();
$expands = array();
$sorting = array();

$expands['Subjects']   = '';
$expands['Categories'] = '';
$expands['PriceNames'] = '$filter=PublicPriceName';
$expands['Events']     =
	'$filter=' .
	'HasPublicPriceName' .
	' and StatusId eq 1' .
	' and CustomerId eq null' .
	' and LastApplicationDate ge ' . date( 'c' ) .
	' and StartDate le ' . date( 'c', strtotime( 'now 23:59:59 +' . $fetch_months . ' months' ) ) .
	' and EndDate ge ' . date( 'c', strtotime( 'now' ) ) .
	';' .
	'$expand=PriceNames,EventDates' .
	';' .
	'$orderby=StartDate asc' .
	';';

$expands['CustomFields'] = '$filter=ShowOnWeb';

$filters[] = 'ShowOnWeb';

$showEventsWithEventsOnly    = $attributes['onlyevents'];
$showEventsWithoutEventsOnly = $attributes['onlyempty'];

if ( $category_id > 0 ) {
	$filters[]              = 'CategoryId eq ' . $category_id;
	$attributes['category'] = $category_id;
}

if ( ! empty( $_REQUEST['eduadmin-category'] ) ) {
	$filters[]              = "CategoryId eq " . intval( sanitize_text_field( $_REQUEST['eduadmin-category'] ) );
	$attributes['category'] = intval( $_REQUEST['eduadmin-category'] );
}

if ( ! empty( $_REQUEST['eduadmin-city'] ) ) {
	$filters[]          = 'Events/any(e:e/LocationId eq ' . intval( $_REQUEST['eduadmin-city'] ) . ')';
	$attributes['city'] = intval( $_REQUEST['eduadmin-city'] );
}

if ( ! empty( $attributes['subject'] ) ) {
	$filters[] = 'Subjects/any(s:s/SubjectName eq \'' . sanitize_text_field( $attributes['subject'] ) . '\')';
}

if ( ! empty( $attributes['subjectid'] ) ) {
	$filters[] = 'Subjects/any(s:s/SubjectId eq ' . intval( $attributes['subjectid'] ) . ')';
}

if ( ! empty( $_REQUEST['eduadmin-subject'] ) ) {
	$filters[]               = 'Subjects/any(s:s/SubjectId eq ' . intval( $_REQUEST['eduadmin-subject'] ) . ')';
	$attributes['subjectid'] = intval( $_REQUEST['eduadmin-subject'] );
}

if ( ! empty( $_REQUEST['eduadmin-level'] ) ) {
	$filters[]                 = 'CourseLevelId eq ' . intval( sanitize_text_field( $_REQUEST['eduadmin-level'] ) );
	$attributes['courselevel'] = intval( sanitize_text_field( $_REQUEST['eduadmin-level'] ) );
}

$sort_order = get_option( 'eduadmin-listSortOrder', 'SortIndex' );

if ( null !== $custom_order_by ) {
	$orderby   = explode( ' ', $custom_order_by );
	$sortorder = explode( ' ', $custom_order_by_order );
	foreach ( $orderby as $od => $v ) {
		if ( isset( $sortorder[ $od ] ) ) {
			$or = $sortorder[ $od ];
		} else {
			$or = 'asc';
		}
		if ( edu_validate_column( 'course', $v ) !== false ) {
			$sorting[] = $v . ' ' . strtolower( $or );
		}
	}
}

if ( edu_validate_column( 'course', $sort_order ) !== false ) {
	$sorting[] = $sort_order . ' asc';
}

$expand_arr = array();
foreach ( $expands as $key => $value ) {
	if ( empty( $value ) ) {
		$expand_arr[] = $key;
	} else {
		$expand_arr[] = $key . '(' . $value . ')';
	}
}

$edo = EDUAPI()->OData->CourseTemplates->Search(
	null,
	join( ' and ', $filters ),
	join( ',', $expand_arr ),
	join( ',', $sorting )
);

$courses = $edo['value'];

if ( ! empty( $_REQUEST['searchCourses'] ) ) {
	$courses = array_filter( $courses, function( $object ) {
		$name        = ( ! empty( $object['CourseName'] ) ? $object['CourseName'] : $object['InternalCourseName'] );
		$descr_field = get_option( 'eduadmin-layout-descriptionfield', 'CourseDescriptionShort' );
		$descr       = '';
		if ( stripos( $descr_field, 'attr_' ) !== false ) {
			$attrId = intval( substr( $descr_field, 5 ) );
			foreach ( $object['CustomFields'] as $custom_field ) {
				if ( $attrId === $custom_field['CustomFieldId'] ) {
					$descr = strip_tags( $custom_field['CustomFieldValue'] );
					break;
				}
			}
		} else {
			$descr = strip_tags( $object[ $descr_field ] );
		}

		$name_match  = stripos( $name, sanitize_text_field( $_REQUEST['searchCourses'] ) ) !== false;
		$descr_match = stripos( $descr, sanitize_text_field( $_REQUEST['searchCourses'] ) ) !== false;

		return ( $name_match || $descr_match );
	} );
}

$events = array();
foreach ( $courses as $object ) {
	foreach ( $object['Events'] as $event ) {
		$event['CourseTemplate'] = $object;
		unset( $event['CourseTemplate']['Events'] );

		$pricenames = array();
		foreach ( $object['PriceNames'] as $pn ) {
			$pricenames[] = $pn['Price'];
		}
		foreach ( $event['PriceNames'] as $pn ) {
			$pricenames[] = $pn['Price'];
		}

		$min_price      = min( $pricenames );
		$event['Price'] = $min_price;

		$events[] = $event;
	}
}

$show_course_days  = get_option( 'eduadmin-showCourseDays', true );
$show_course_times = get_option( 'eduadmin-showCourseTimes', true );
$show_week_days    = get_option( 'eduadmin-showWeekDays', false );
$inc_vat           = EDUAPI()->REST->Organisation->GetOrganisation()['PriceIncVat'];

$show_event_price = get_option( 'eduadmin-showEventPrice', false );
$currency         = get_option( 'eduadmin-currency', 'SEK' );
$show_event_venue = get_option( 'eduadmin-showEventVenueName', false );

$spot_left_option = get_option( 'eduadmin-spotsLeft', 'exactNumbers' );
$always_few_spots = get_option( 'eduadmin-alwaysFewSpots', '3' );
$spot_settings    = get_option( 'eduadmin-spotsSettings', "1-5\n5-10\n10+" );
?>
<div class="eventListTable" data-eduwidget="listview-eventlist" data-template="<?php echo esc_attr( str_replace( 'template_', '', $attributes['template'] ) ); ?>" data-subject="<?php echo esc_attr( $attributes['subject'] ); ?>" data-subjectid="<?php echo esc_attr( $attributes['subjectid'] ); ?>" data-category="<?php echo esc_attr( $attributes['category'] ); ?>" data-courselevel="<?php echo esc_attr( $attributes['courselevel'] ); ?>" data-city="<?php echo esc_attr( $attributes['city'] ); ?>" data-search="<?php echo esc_attr( ( ! empty( $_REQUEST['searchCourses'] ) ? sanitize_text_field( $_REQUEST['searchCourses'] ) : '' ) ); ?>" data-numberofevents="<?php echo esc_attr( $attributes['numberofevents'] ); ?>" data-orderby="<?php echo esc_attr( $attributes['orderby'] ); ?>" data-order="<?php echo esc_attr( $attributes['order'] ); ?>" data-showmore="<?php echo esc_attr( $attributes['showmore'] ); ?>" data-showcity="<?php echo esc_attr( $attributes['showcity'] ); ?>" data-showbookbtn="<?php echo esc_attr( $attributes['showbookbtn'] ); ?>" data-showreadmorebtn="<?php echo esc_attr( $attributes['showreadmorebtn'] ); ?>">
