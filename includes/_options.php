<?php
defined( 'ABSPATH' ) or die( 'This plugin must be run within the scope of WordPress.' );

add_action('admin_init', 'eduadmin_settings_init');
add_action('admin_menu', 'eduadmin_backend_menu');
add_action('admin_enqueue_scripts', 'eduadmin_backend_content');
add_action('wp_enqueue_scripts', 'eduadmin_frontend_content', PHP_INT_MAX);
add_action('add_meta_boxes', 'eduadmin_shortcode_metabox');
add_action('wp_footer', 'eduadmin_printJavascript');

function eduadmin_page_title($title, $sep = "|")
{
	global $eduapi;
	global $edutoken;
	global $wp;

	if($sep == null || empty($sep))
	{
		$sep = "|";
	}

	if(isset($wp) && isset($wp->query_vars) && isset($wp->query_vars["courseId"]))
	{
		$edo = get_transient('eduadmin-listCourses');
		if(!$edo)
		{
			$filtering = new XFiltering();
			$f = new XFilter('ShowOnWeb','=','true');
			$filtering->AddItem($f);

			$edo = $eduapi->GetEducationObject($edutoken, '', $filtering->ToString());
			set_transient('eduadmin-listCourses', $edo, 6 * HOUR_IN_SECONDS);
		}

		foreach($edo as $object)
		{
			$name = (!empty($object->PublicName) ? $object->PublicName : $object->ObjectName);
			$id = $object->ObjectID;
			if($id == $wp->query_vars["courseId"])
			{
				$selectedCourse = $object;
				break;
			}
		}

		if($selectedCourse != null)
		{
			$titleField = get_option('eduadmin-pageTitleField', 'PublicName');
			if(stristr($titleField, "attr_") !== false)
			{
				$attrid = substr($titleField, 5);
				$ft = new XFiltering();
				$f = new XFilter('ObjectID', '=', $selectedCourse->ObjectID);
				$ft->AddItem($f);
				$f = new XFilter('AttributeID', '=', $attrid);
				$ft->AddItem($f);
				$objAttr = $eduapi->GetObjectAttribute($edutoken, '', $ft->ToString());
				if(!empty($objAttr))
				{
					$attr = $objAttr[0];
					switch($attr->AttributeTypeID)
					{
						case 5:
							$value = $attr->AttributeAlternative;
						/*case 7:
							$value = $attr->AttributeDate;*/
						default:
							$value = $attr->AttributeValue;
						break;
					}
					if(!empty($value) && stristr($title, $value) === FALSE)
					{
						$title = $value . " " . $sep . " " . $title;
					}
					else
					{
						$title = $selectedCourse->ObjectName . " " . $sep . " " . $title;
					}
				}
				else
				{
					$title = $selectedCourse->ObjectName . " " . $sep . " " . $title;
				}
			}
			else
			{
				if(!empty($selectedCourse->{$titleField}) && stristr($title, $selectedCourse->{$titleField}) === FALSE)
				{
					$title = $selectedCourse->{$titleField} . " " . $sep . " " . $title;
				}
				else
				{
					$title = $selectedCourse->ObjectName . " " . $sep . " " . $title;
				}
			}
		}
	}

	return $title;
}

add_filter('pre_get_document_title', 'eduadmin_page_title', PHP_INT_MAX, 2);
add_filter('wp_title', 'eduadmin_page_title', PHP_INT_MAX, 2);
add_filter('aioseop_title', 'eduadmin_page_title', PHP_INT_MAX, 2);

function eduadmin_settings_init()
{
	/* Credential settings */
	register_setting('eduadmin-credentials', 'eduadmin-api-key');
	register_setting('eduadmin-credentials', 'eduadmin-credentials_have_changed');

	/* Rewrite settings */
	register_setting('eduadmin-rewrite', 'eduadmin-options_have_changed');
	register_setting('eduadmin-rewrite', 'eduadmin-rewriteBaseUrl');
	register_setting('eduadmin-rewrite', 'eduadmin-listViewPage');
	register_setting('eduadmin-rewrite', 'eduadmin-loginViewPage');
	register_setting('eduadmin-rewrite', 'eduadmin-detailViewPage');
	register_setting('eduadmin-rewrite', 'eduadmin-bookingViewPage');
	register_setting('eduadmin-rewrite', 'eduadmin-thankYouPage');
	register_setting('eduadmin-rewrite', 'eduadmin-interestObjectPage');
	register_setting('eduadmin-rewrite', 'eduadmin-interestEventPage');


	/* Booking settings */
	register_setting('eduadmin-booking', 'eduadmin-useLogin');
	register_setting('eduadmin-booking', 'eduadmin-loginField');
	register_setting('eduadmin-booking', 'eduadmin-singlePersonBooking');
	register_setting('eduadmin-booking', 'eduadmin-customerGroupId');
	register_setting('eduadmin-booking', 'eduadmin-currency');
	register_setting('eduadmin-booking', 'eduadmin-bookingTermsLink');
	register_setting('eduadmin-booking', 'eduadmin-useBookingTermsCheckbox');
	register_setting('eduadmin-booking', 'eduadmin-javascript');
	register_setting('eduadmin-booking', 'eduadmin-customerMatching');
	register_setting('eduadmin-booking', 'eduadmin-selectPricename');
	register_setting('eduadmin-booking', 'eduadmin-fieldOrder');
	register_setting('eduadmin-booking', 'eduadmin-allowInterestRegObject');
	register_setting('eduadmin-booking', 'eduadmin-allowInterestRegEvent');
	register_setting('eduadmin-booking', 'eduadmin-hideSubEventDateTime');
	register_setting('eduadmin-booking', 'eduadmin-allowDiscountCode');
	register_setting('eduadmin-booking', 'eduadmin-noInvoiceFreeEvents');
	register_setting('eduadmin-booking', 'eduadmin-validateCivicRegNo');
	register_setting('eduadmin-booking', 'eduadmin-useLimitedDiscount');

	/* Phrase settings */
	register_setting('eduadmin-phrases', 'eduadmin-phrases');

	/* Style settings */
	register_setting('eduadmin-style', 'eduadmin-style');

	/* Detail settings */
	register_setting('eduadmin-details', 'eduadmin-showDetailHeaders');
	register_setting('eduadmin-details', 'eduadmin-detailTemplate');
	register_setting('eduadmin-details', 'eduadmin-groupEventsByCity');
	register_setting('eduadmin-details', 'eduadmin-pageTitleField');

	/* List settings */
	register_setting('eduadmin-list', 'eduadmin-showEventsInList');
	register_setting('eduadmin-list', 'eduadmin-listTemplate');

	register_setting('eduadmin-list', 'eduadmin-allowLocationSearch');
	register_setting('eduadmin-list', 'eduadmin-allowSubjectSearch');
	register_setting('eduadmin-list', 'eduadmin-allowCategorySearch');
	register_setting('eduadmin-list', 'eduadmin-allowLevelSearch');

	register_setting('eduadmin-list', 'eduadmin-listSortOrder');

	register_setting('eduadmin-list', 'eduadmin-layout-descriptionfield');

	register_setting('eduadmin-list', 'eduadmin-showCourseImage');
	register_setting('eduadmin-list', 'eduadmin-showCourseDescription');
	register_setting('eduadmin-list', 'eduadmin-showNextEventDate');
	register_setting('eduadmin-list', 'eduadmin-showCourseLocations');
	register_setting('eduadmin-list', 'eduadmin-showEventPrice');
	register_setting('eduadmin-list', 'eduadmin-showCourseDays');
	register_setting('eduadmin-list', 'eduadmin-showCourseTimes');
	register_setting('eduadmin-list', 'eduadmin-showEventVenueName');


	/* Global settings */
	register_setting('eduadmin-rewrite', 'eduadmin-spotsLeft');
	register_setting('eduadmin-rewrite', 'eduadmin-spotsSettings');
	register_setting('eduadmin-rewrite', 'eduadmin-alwaysFewSpots');
	register_setting('eduadmin-rewrite', 'eduadmin-monthsToFetch');
}

function eduadmin_frontend_content()
{
	$styleVersion = filemtime(dirname(__DIR__) . '/content/style/frontendstyle.css');
	wp_register_style('eduadmin_frontend_style', plugins_url('content/style/frontendstyle.css', dirname(__FILE__)), false, dateVersion($styleVersion));
	$customcss = get_option('eduadmin-style', '');
	wp_enqueue_style('eduadmin_frontend_style');
	wp_add_inline_style('eduadmin_frontend_style', $customcss);

	$scriptVersion = filemtime(dirname(__DIR__) . '/content/script/educlient/edu.apiclient.js');
	wp_register_script('eduadmin_apiclient_script', plugins_url('content/script/educlient/edu.apiclient.js', dirname(__FILE__)), false, dateVersion($scriptVersion));
	wp_localize_script('eduadmin_apiclient_script', 'wp_edu',
	array(
		'BaseUrl' => home_url(),
		'BaseUrlBackend' => plugins_url('backend', dirname(__FILE__)),
		'BaseUrlScripts' => plugins_url('content/script', dirname(__FILE__)),
		'CourseFolder' => get_option('eduadmin-rewriteBaseUrl'),
		'Phrases' => edu_LoadPhrases(),
		'ApiKey' => get_option('eduadmin-api-key')
	));
	wp_enqueue_script('eduadmin_apiclient_script', false, array('jquery'));

	$scriptVersion = filemtime(dirname(__DIR__) . '/content/script/frontendjs.js');
	wp_register_script('eduadmin_frontend_script', plugins_url('content/script/frontendjs.js', dirname(__FILE__)), false, dateVersion($scriptVersion));
	wp_enqueue_script('eduadmin_frontend_script', false, array('jquery'));

}

function eduadmin_backend_content()
{
	$styleVersion = filemtime(dirname(__DIR__) . '/content/style/adminstyle.css');
	wp_register_style('eduadmin_admin_style', plugins_url('content/style/adminstyle.css', dirname(__FILE__)), false, dateVersion($styleVersion));
	wp_enqueue_style('eduadmin_admin_style');

	$scriptVersion = filemtime(dirname(__DIR__) . '/content/script/adminjs.js');
	wp_register_script('eduadmin_admin_script', plugins_url('content/script/adminjs.js', dirname(__FILE__)), false, dateVersion($scriptVersion));
	wp_enqueue_script('eduadmin_admin_script', false, array('jquery'));
}

function eduadmin_backend_menu()
{
    $level = 'administrator';
	add_menu_page('EduAdmin', 'EduAdmin', $level, 'eduadmin-settings', 'eduadmin_settings_general', 'dashicons-welcome-learn-more');
	add_submenu_page('eduadmin-settings', __('EduAdmin - General', 'eduadmin'), __('General settings', 'eduadmin'), $level, 'eduadmin-settings', 'eduadmin_settings_general');
	add_submenu_page('eduadmin-settings', __('EduAdmin - List view', 'eduadmin'), __('List settings', 'eduadmin'), $level, 'eduadmin-settings-view', 'eduadmin_settings_list');
	add_submenu_page('eduadmin-settings', __('EduAdmin - Detail view', 'eduadmin'), __('Detail settings', 'eduadmin'), $level, 'eduadmin-settings-detail', 'eduadmin_settings_detail');
	add_submenu_page('eduadmin-settings', __('EduAdmin - Booking view', 'eduadmin'), __('Booking settings', 'eduadmin'), $level, 'eduadmin-settings-booking', 'eduadmin_settings_booking');
	add_submenu_page('eduadmin-settings', __('EduAdmin - Translation', 'eduadmin'), __('Translation', 'eduadmin'), $level, 'eduadmin-settings-text', 'eduadmin_settings_text');
	add_submenu_page('eduadmin-settings', __('EduAdmin - Style', 'eduadmin'), __('Style settings', 'eduadmin'), $level, 'eduadmin-settings-style', 'eduadmin_settings_style');
	add_submenu_page('eduadmin-settings', __('EduAdmin - Api Authentication', 'eduadmin'), __('Api Authentication', 'eduadmin'), $level, 'eduadmin-settings-api', 'eduadmin_settings_page');
}

function eduadmin_settings_page()
{
	include_once("settingsPage.php");
}

function eduadmin_settings_general()
{
	include_once("generalSettings.php");
}

function eduadmin_settings_list()
{
	include_once("listSettings.php");
}

function eduadmin_settings_detail()
{
	include_once("detailSettings.php");
}

function eduadmin_settings_booking()
{
	include_once("bookingSettings.php");
}

function eduadmin_settings_text()
{
	include_once("textSettings.php");
}

function eduadmin_settings_style()
{
	include_once("styleSettings.php");
}

function eduadmin_shortcode_metabox()
{
	add_meta_box('eduadmin-metabox', __('EduAdmin - Shortcodes', 'eduadmin'), 'eduadmin_create_metabox', null, 'side', 'high');
}

function eduadmin_create_metabox()
{
	include_once("_metaBox.php");
}

function eduadmin_RewriteJavaScript($script)
{
	global $eduapi;
	global $edutoken;

	if(isset($_REQUEST['edu-thankyou']))
	{
		if(stripos($script, "$") !== FALSE)
		{
			$ft = new XFiltering();
			$f = new XFilter('EventCustomerLnkID', '=', $_REQUEST['edu-thankyou']);
			$ft->AddItem($f);
			$bookingInfo = $eduapi->GetEventBooking($edutoken, '', $ft->ToString());

			$script = str_replace(
				array(
					'$bookingno$',
					'$productname$',
					'$totalsum$',
					'$participants$'
				),
				array(
					esc_js($bookingInfo[0]->EventCustomerLnkID), // $bookingno$
					esc_js($bookingInfo[0]->ObjectName), // $productname$
					esc_js($bookingInfo[0]->TotalPrice), // $totalsum$
					esc_js($bookingInfo[0]->ParticipantNr) // $participants$
				),
				$script
			);
		}

		return $script;
	}
	return '';
}

function eduadmin_printJavascript()
{
	if(trim(get_option('eduadmin-javascript', '')) != '' && isset($_SESSION['eduadmin-printJS']))
	{
		$str = "<script type=\"text/javascript\">\n";
		$script = get_option('eduadmin-javascript');
		$str .= eduadmin_RewriteJavaScript($script);
		$str .= "\n</script>";
		unset($_SESSION['eduadmin-printJS']);
		echo $str;
	}
}