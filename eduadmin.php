<?php
defined( 'ABSPATH' ) or die( 'This plugin must be run within the scope of WordPress.' );

/*
 * Plugin Name:	EduAdmin Booking
 * Plugin URI:	http://www.eduadmin.se
 * Description:	EduAdmin plugin to allow visitors to book courses at your website
 * Tags:	booking, participants, courses, events, eduadmin, lega online
 * Version:	0.9.17.10
 * Requires at least: 3.0
 * Tested up to: 4.7.1
 * Author:	Chris Gårdenberg, MultiNet Interactive AB
 * Author URI:	http://www.multinet.se
 * License:	GPL3
 * Text Domain:	eduadmin
 * Domain Path: /languages/
 */
/*
    EduAdmin Booking plugin
    Copyright (C) 2015-2017 Chris Gårdenberg, MultiNet Interactive AB

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

include_once("includes/loApiClient.php");
if(!session_id())
	session_start();

include_once("includes/_options.php");
include_once("includes/functions.php");
include_once("includes/_rewrites.php");
include_once("includes/_shortcodes.php");

date_default_timezone_set(wp_get_timezone_string());
@ini_set('date.timezone', wp_get_timezone_string());
$_SESSION['__defaultTimeZone'] = wp_get_timezone_string();

if(file_exists(dirname(__FILE__) . "/.official.plugin.php"))
{
	include_once(".official.plugin.php");
}

function edu_load_language()
{
	$domain = 'eduadmin';
	$locale = apply_filters('plugin_locale', get_locale(), $domain);
	load_textdomain($domain, WP_LANG_DIR.'/eduadmin/'.$domain.'-'.$locale.'.mo');
	load_plugin_textdomain($domain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages');

    if(!wp_next_scheduled( 'eduadmin_call_home' )) {
        wp_schedule_event( time(), 'hourly', 'eduadmin_call_home' );
    }
}

function edu_new_theme()
{
	update_option('eduadmin-options_have_changed', true);
}

function edu_call_home()
{
    global $wp_version;
    $usageData = array(
        'siteUrl' => get_site_url(),
        'siteName' => get_option('blogname'),
        'wpVersion' => $wp_version,
        'token' => get_option('eduadmin-api-key'),
        'officialVersion' => file_exists(dirname(__FILE__) . "/.official.plugin.php"),
        'pluginVersion' => '0.9.17.10'
    );

    $callHomeUrl = 'http://ws10.multinet.se/edu-plugin/wp_phone_home.php';
    wp_remote_post($callHomeUrl, array('body' => $usageData));
}

add_action('eduadmin_call_home', 'edu_call_home');

add_action('init', 'edu_load_language');
add_action('after_switch_theme', 'edu_new_theme');

register_activation_hook( __FILE__, 'eduadmin_activate_rewrite' );
register_deactivation_hook(__FILE__, 'eduadmin_deactivate');

function eduadmin_deactivate() {
    eduadmin_deactivate_rewrite();
    wp_clear_scheduled_hook( 'eduadmin_call_home' );
}