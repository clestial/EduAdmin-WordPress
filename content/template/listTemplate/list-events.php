<?php
$surl    = get_home_url();
$cat     = get_option( 'eduadmin-rewriteBaseUrl' );
$baseUrl = $surl . '/' . $cat;

$fetchMonths = get_option( 'eduadmin-monthsToFetch', 6 );
if ( ! is_numeric( $fetchMonths ) ) {
	$fetchMonths = 6;
}

$filters = array();
$expands = array();
$sorting = array();

$expands['Subjects']   = "";
$expands['Categories'] = "";
$expands['PriceNames'] = '$filter=PublicPriceName';
$expands['Events']     =
	'$filter=' .
	'HasPublicPriceName' .
	' and StatusId eq 1' .
	' and CustomerId eq null' .
	' and LastApplicationDate ge ' . date( "c" ) .
	' and StartDate le ' . date( "c", strtotime( "now 23:59:59 +" . $fetchMonths . " months" ) ) .
	' and EndDate ge ' . date( "c", strtotime( "now" ) ) .
	';' .
	'$expand=PriceNames,EventDates' .
	';' .
	'$orderby=StartDate asc' .
	';';

$expands['CustomFields'] = '$filter=ShowOnWeb';

$filters[] = "ShowOnWeb";

$showEventsWithEventsOnly    = $attributes['onlyevents'];
$showEventsWithoutEventsOnly = $attributes['onlyempty'];

if ( $categoryID > 0 ) {
	$filters[]              = "CategoryId eq " . $categoryID;
	$attributes['category'] = $categoryID;
}

if ( isset( $_REQUEST['eduadmin-category'] ) && ! empty( $_REQUEST['eduadmin-category'] ) ) {
	$filters[]              = "CategoryId eq " . intval( sanitize_text_field( $_REQUEST['eduadmin-category'] ) );
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

$sortOrder = get_option( 'eduadmin-listSortOrder', 'SortIndex' );

if ( $customOrderBy != null ) {
	$orderby   = explode( ' ', $customOrderBy );
	$sortorder = explode( ' ', $customOrderByOrder );
	foreach ( $orderby as $od => $v ) {
		if ( isset( $sortorder[ $od ] ) ) {
			$or = $sortorder[ $od ];
		} else {
			$or = "asc";
		}

		$sorting[] = $v . ' ' . strtolower( $or );
	}
}

$sorting[] = $sortOrder . ' asc';

$expandArr = array();
foreach ( $expands as $key => $value ) {
	if ( empty( $value ) ) {
		$expandArr[] = $key;
	} else {
		$expandArr[] = $key . "(" . $value . ")";
	}
}

$edo     = EDUAPI()->OData->CourseTemplates->Search(
	null,
	join( " and ", $filters ),
	join( ",", $expandArr ),
	join( ",", $sorting )
);
$courses = $edo["value"];

if ( isset( $_REQUEST['searchCourses'] ) && ! empty( $_REQUEST['searchCourses'] ) ) {
	$courses = array_filter( $courses, function( $object ) {
		$name       = ( ! empty( $object["CourseName"] ) ? $object["CourseName"] : $object["InternalCourseName"] );
		$descrField = get_option( 'eduadmin-layout-descriptionfield', 'CourseDescriptionShort' );
		$descr      = '';
		if ( stripos( $descrField, "attr_" ) !== false ) {
			$attrId = intval( substr( $descrField, 5 ) );
			foreach ( $object['CustomFields'] as $custom_field ) {
				if ( $attrId === $custom_field['CustomFieldId'] ) {
					$descr = strip_tags( $custom_field['CustomFieldValue'] );
					break;
				}
			}
		} else {
			$descr = strip_tags( $object[ $descrField ] );
		}

		$nameMatch  = stripos( $name, sanitize_text_field( $_REQUEST['searchCourses'] ) ) !== false;
		$descrMatch = stripos( $descr, sanitize_text_field( $_REQUEST['searchCourses'] ) ) !== false;

		return ( $nameMatch || $descrMatch );
	} );
}

$events = array();
foreach ( $courses as $object ) {
	foreach ( $object["Events"] as $event ) {
		$event['CourseTemplate'] = $object;
		unset( $event['CourseTemplate']['Events'] );

		$pricenames = array();
		foreach ( $object['PriceNames'] as $pn ) {
			$pricenames[] = $pn['Price'];
		}
		foreach ( $event['PriceNames'] as $pn ) {
			$pricenames[] = $pn['Price'];
		}

		$minPrice       = min( $pricenames );
		$event['Price'] = $minPrice;

		$events[] = $event;
	}
}

$showCourseDays  = get_option( 'eduadmin-showCourseDays', true );
$showCourseTimes = get_option( 'eduadmin-showCourseTimes', true );
$showWeekDays    = get_option( 'eduadmin-showWeekDays', false );
$incVat          = EDUAPI()->REST->Organisation->GetOrganisation()["PriceIncVat"];

$showEventPrice = get_option( 'eduadmin-showEventPrice', false );
$currency       = get_option( 'eduadmin-currency', 'SEK' );
$showEventVenue = get_option( 'eduadmin-showEventVenueName', false );

$spotLeftOption = get_option( 'eduadmin-spotsLeft', 'exactNumbers' );
$alwaysFewSpots = get_option( 'eduadmin-alwaysFewSpots', '3' );
$spotSettings   = get_option( 'eduadmin-spotsSettings', "1-5\n5-10\n10+" );
?>
<div class="eventListTable" data-eduwidget="listview-eventlist" data-template="<?php echo esc_attr( str_replace( 'template_', '', $attributes['template'] ) ); ?>" data-subject="<?php echo esc_attr( $attributes['subject'] ); ?>" data-subjectid="<?php echo esc_attr( $attributes['subjectid'] ); ?>" data-category="<?php echo esc_attr( $attributes['category'] ); ?>" data-courselevel="<?php echo esc_attr( $attributes['courselevel'] ); ?>" data-city="<?php echo esc_attr( $attributes['city'] ); ?>" data-search="<?php echo esc_attr( sanitize_text_field( $_REQUEST['searchCourses'] ) ); ?>" data-numberofevents="<?php echo esc_attr( $attributes['numberofevents'] ); ?>" data-orderby="<?php echo esc_attr( $attributes['orderby'] ); ?>" data-order="<?php echo esc_attr( $attributes['order'] ); ?>" data-showmore="<?php echo esc_attr( $attributes['showmore'] ); ?>" data-showcity="<?php echo esc_attr( $attributes['showcity'] ); ?>" data-showbookbtn="<?php echo esc_attr( $attributes['showbookbtn'] ); ?>" data-showreadmorebtn="<?php echo esc_attr( $attributes['showreadmorebtn'] ); ?>">
