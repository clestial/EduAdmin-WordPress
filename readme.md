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

Plugin that you connect to [EduAdmin](http://www.eduadmin.se) to enable booking on your website.
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

### 1.0.28
- If no events are available, load public pricenames from course template

### 1.0.27
- Adding more fields to output when a booking is completed

### 1.0.26
- Some more styles to some buttons
- Making it easier to edit some templates
- If there are no dates provided to the date-function, render an empty string instead of 01 January, 1970
- Adding support for `showmore` in `[eduadmin-detailinfo]` as attribute
- Bugfix: Don't load already loaded classes