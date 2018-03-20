=== EduAdmin Booking ===
Contributors: mnchga
Tags: booking, participants, courses, events, eduadmin, lega online
Requires at least: 4.7
Tested up to: 4.9
Stable tag: 1.0.28
Requires PHP: 5.2
License: GPL3
License-URI: https://www.gnu.org/licenses/gpl-3.0.en.html
EduAdmin plugin to allow visitors to book courses at your website. Requires EduAdmin-account.

== Description ==

Plugin that you connect to [EduAdmin](https://www.eduadmin.se) to enable booking on your website.
Requires at least PHP 5.2 (with [SoapClient](http://php.net/manual/en/book.soap.php) installed and configured)

[<img src="https://img.shields.io/wordpress/plugin/v/eduadmin-booking.svg" alt="Plugin version" />](https://wordpress.org/plugins/eduadmin-booking/)
[<img src="https://img.shields.io/wordpress/plugin/dt/eduadmin-booking.svg" alt="Downloads" />](https://wordpress.org/plugins/eduadmin-booking/)
[<img src="https://img.shields.io/wordpress/v/eduadmin-booking.svg" alt="Tested up to" />](https://wordpress.org/plugins/eduadmin-booking/)

[<img src="https://badges.gitter.im/MultinetInteractive/EduAdmin-WordPress.png" alt="Gitter" />](https://gitter.im/MultinetInteractive/EduAdmin-WordPress)
[<img src="https://travis-ci.org/MultinetInteractive/EduAdmin-WordPress.svg?branch=master" alt="Build status" />](https://travis-ci.org/MultinetInteractive/EduAdmin-WordPress)
[<img src="https://scrutinizer-ci.com/g/MultinetInteractive/EduAdmin-WordPress/badges/quality-score.png?b=master" alt="Code quality" />](https://scrutinizer-ci.com/g/MultinetInteractive/EduAdmin-WordPress/?branch=master)

== Installation ==

- Upload the zip-file (or install from WordPress) and activate the plugin
- Provide the API key from EduAdmin.
- Create pages for the different views and give them their shortcodes

== Upgrade Notice ==

= 2.0 =
We have replaced everything with a new API-client, so some things may be broken. If you experience any bugs (not new feature-requests), please contact the MultiNet Support.

= 1.0.23 =
All translations will be wiped since we're moving to full WordPress-translation management, for all phrases, not just backend

= 1.0.22 =
If you are using an older version than 1.0.21, please test this version on a development server before deploying live.
Since version 1.0.21, there are a lot of design changes in the CSS, that might require you to reset style settings,
and redo your own customization.

= 1.0.21 =
- LOTS of design changes, please update to a development server to see if anything breaks

== Changelog ==

### 2.0 ###
- Adding page for certificates
- Bumping major version, since we're using a brand new API
- Removing default styles, it will now be emptied when you reset it. (To make sure that you don't have double CSS)
- Making "Forgot password" into a "neutral-btn"
- Making event separators a little bit bigger and bolder
- `showmore` upgrade, available in `[eduadmin-detailinfo]` as attribute
- Two column-template fixed to load templated event list.
- Adding nonces to actions/forms
- Customer, person and participant CustomFields on booking page
- Fixed event inquiries to use the new API
- Adding attribute `eventprice` to `[eduadmin-detailinfo]`
- Adding cache-break to the new API (OData endpoints)
- Adding listview-shortcode for Programmes `[eduadmin-programmelist]`


### 1.0.28
- If no events are available, load public pricenames from course template

### 1.0.27
- Adding more fields to output when a booking is completed