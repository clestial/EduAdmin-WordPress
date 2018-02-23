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
$expands['PriceNames'] = '';
$expands['Events']     =
	'$filter=' .
	'HasPublicPriceName' .
	' and StatusId eq 1' .
	' and CustomerId eq null' .
	' and LastApplicationDate ge ' . date( 'c' ) .
	' and StartDate le ' . date( 'c', strtotime( 'now 23:59:59 +' . $fetch_months . ' months' ) ) .
	' and EndDate ge ' . date( 'c', strtotime( 'now' ) ) .
	';' .
	'$expand=PriceNames' .
	';' .
	'$orderby=StartDate asc' .
	';';

$expands['CustomFields'] = '$filter=ShowOnWeb';

$filters[] = 'ShowOnWeb';

$show_events_with_events_only    = $attributes['onlyevents'];
$show_events_without_events_only = $attributes['onlyempty'];

if ( $category_id > 0 ) {
	$filters[]              = 'CategoryId eq ' . $category_id;
	$attributes['category'] = $category_id;
}

if ( isset( $_REQUEST['eduadmin-category'] ) && ! empty( $_REQUEST['eduadmin-category'] ) ) {
	$filters[]              = 'CategoryId eq ' . intval( sanitize_text_field( $_REQUEST['eduadmin-category'] ) );
	$attributes['category'] = intval( $_REQUEST['eduadmin-category'] );
}

if ( isset( $_REQUEST['eduadmin-city'] ) && ! empty( $_REQUEST['eduadmin-city'] ) ) {
	$filters[]          = 'Events/any(e:e/LocationId eq ' . intval( $_REQUEST['eduadmin-city'] ) . ')';
	$attributes['city'] = intval( $_REQUEST['eduadmin-city'] );
}

if ( isset( $attributes['subject'] ) && ! empty( $attributes['subject'] ) ) {
	$filters[] = 'Subjects/any(s:s/SubjectName eq \'' . sanitize_text_field( $attributes['subject'] ) . '\')';
}

if ( isset( $_REQUEST['eduadmin-subject'] ) && ! empty( $_REQUEST['eduadmin-subject'] ) ) {
	$filters[]               = 'Subjects/any(s:s/SubjectId eq ' . intval( $_REQUEST['eduadmin-subject'] ) . ')';
	$attributes['subjectid'] = intval( $_REQUEST['eduadmin-subject'] );
}

if ( isset( $_REQUEST['eduadmin-level'] ) && ! empty( $_REQUEST['eduadmin-level'] ) ) {
	$filters[] = 'EducationLevelId eq ' . intval( sanitize_text_field( $_REQUEST['eduadmin-level'] ) );
}

$sort_order = get_option( 'eduadmin-listSortOrder', 'SortIndex' );

if ( $custom_order_by != null ) {
	$orderby   = explode( ' ', $custom_order_by );
	$sortorder = explode( ' ', $custom_order_by_order );
	foreach ( $orderby as $od => $v ) {
		if ( isset( $sortorder[ $od ] ) ) {
			$or = $sortorder[ $od ];
		} else {
			$or = 'asc';
		}

		$sorting[] = $v . ' ' . strtolower( $or );
	}
}

$sorting[] = $sort_order . ' asc';

$expand_arr = array();
foreach ( $expands as $key => $value ) {
	if ( empty( $value ) ) {
		$expand_arr[] = $key;
	} else {
		$expand_arr[] = $key . "(" . $value . ")";
	}
}

$edo     = EDUAPI()->OData->CourseTemplates->Search(
	null,
	join( ' and ', $filters ),
	join( ',', $expand_arr ),
	join( ',', $sorting )
);
$courses = $edo['value'];

if ( isset( $_REQUEST['searchCourses'] ) && ! empty( $_REQUEST['searchCourses'] ) ) {
	$courses = array_filter( $courses, function( $object ) {
		$name        = ( ! empty( $object["CourseName"] ) ? $object["CourseName"] : $object["InternalCourseName"] );
		$descr_field = get_option( 'eduadmin-layout-descriptionfield', 'CourseDescriptionShort' );
		$descr       = '';
		if ( stripos( $descr_field, "attr_" ) !== false ) {
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

$show_next_event_date  = get_option( 'eduadmin-showNextEventDate', false );
$show_course_locations = get_option( 'eduadmin-showCourseLocations', false );
$show_event_price      = get_option( 'eduadmin-showEventPrice', false );
$inc_vat               = EDUAPI()->REST->Organisation->GetOrganisation()['PriceIncVat'];

$show_course_days  = get_option( 'eduadmin-showCourseDays', true );
$show_course_times = get_option( 'eduadmin-showCourseTimes', true );
$show_week_days    = get_option( 'eduadmin-showWeekDays', false );

$show_descr       = get_option( 'eduadmin-showCourseDescription', true );
$show_event_venue = get_option( 'eduadmin-showEventVenueName', false );
$currency         = get_option( 'eduadmin-currency', 'SEK' );
?>
<div class="eduadmin-courselistoptions" data-subject="<?php echo esc_attr( $attributes['subject'] ); ?>" data-subjectid="<?php echo esc_attr( $attributes['subjectid'] ); ?>" data-category="<?php echo esc_attr( $attributes['category'] ); ?>" data-city="<?php echo esc_attr( $attributes['city'] ); ?>" data-courselevel="<?php echo esc_attr( $attributes['courselevel'] ); ?>" data-search="<?php echo esc_attr( $_REQUEST['searchCourses'] ); ?>" data-numberofevents="<?php echo esc_attr( $attributes['numberofevents'] ); ?>"></div>
