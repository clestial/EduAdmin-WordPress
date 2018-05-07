=== EduAdmin Booking ===
Contributors: mnchga
Tags: booking, participants, courses, events, eduadmin, lega online
Requires at least: 4.7
Tested up to: 4.9
Stable tag: 2.0.3
Requires PHP: 5.2
License: GPL3
License-URI: https://www.gnu.org/licenses/gpl-3.0.en.html
EduAdmin plugin to allow visitors to book courses at your website. Requires EduAdmin-account.

== Description ==

Plugin that you connect to [EduAdmin](https://www.eduadmin.se) to enable booking on your website.

[<img src="https://img.shields.io/wordpress/plugin/v/eduadmin-booking.svg" alt="Plugin version" />](https://wordpress.org/plugins/eduadmin-booking/)
[<img src="https://img.shields.io/wordpress/plugin/dt/eduadmin-booking.svg" alt="Downloads" />](https://wordpress.org/plugins/eduadmin-booking/)
[<img src="https://img.shields.io/wordpress/v/eduadmin-booking.svg" alt="Tested up to" />](https://wordpress.org/plugins/eduadmin-booking/)

[<img src="https://badges.gitter.im/MultinetInteractive/EduAdmin-WordPress.png" alt="Gitter" />](https://gitter.im/MultinetInteractive/EduAdmin-WordPress)
[<img src="https://travis-ci.org/MultinetInteractive/EduAdmin-WordPress.svg?branch=master" alt="Build status" />](https://travis-ci.org/MultinetInteractive/EduAdmin-WordPress)
[<img src="https://scrutinizer-ci.com/g/MultinetInteractive/EduAdmin-WordPress/badges/quality-score.png?b=master" alt="Code quality" />](https://scrutinizer-ci.com/g/MultinetInteractive/EduAdmin-WordPress/?branch=master)

[<img src="https://img.shields.io/github/commits-since/MultinetInteractive/EduAdmin-WordPress/latest.svg" alt="Plugin version" />](https://wordpress.org/plugins/eduadmin-booking/)



== Installation ==

- Upload the zip-file (or install from WordPress) and activate the plugin
- Provide the API key from EduAdmin.
- Create pages for the different views and give them their shortcodes

== Upgrade Notice ==

= 2.0 =
We have replaced everything with a new API-client, so some things may be broken. If you experience any bugs (not new feature-requests), please contact the MultiNet Support.
If you notice that your API key doesn't work any more, you have to contact us.

== Changelog ==

### 2.0.4 ###
- fix: Fixed so that strings from the EduAdmin-API also gets captured into `data`
- add: Show price on programme booking-page
- add: Support for programme bookings to be booked (with support for payment plugins)
- chg: Added CTA-class to book-button on detail view for programme starts
- chg: Updating EduAdmin PHP API Client
- chg: Adding escaping of output
- add: Making it easier to add profile-menu items
- add: Support for REST endpoint ProgrammeStart (Get questions)
- add: Support for price check on ProgrammeBooking
- chg: Codestyling to match other pages.

### 2.0.3 ###
- add: Ability to view schedule of a programme
- chg: Bugfix where confirmation emails weren't sent for multiple participant bookings
- chg: Bugfix for 2 column detail template
- add: Better error handling when booking a course (At least some handling..)

### 2.0.2 ###
- fix: Adding check for nonces in interest-registration pages
- fix: Checking count in password reset in a different way
- add: When you activate/deactivate the plugin, all transients are now cleaned
- add: Programme start list in detail view
- add: Save `customerId` and `personId` in hidden variables on booking page, so we won't lose logged in users if the session times out.
- add: If we cannot find anything related to `[eduadmin` in the pages, show all pages.

### 2.0.1 ###
- chg: Better check against `customtemplate`
- add: Backend-function to fix old search/sort/display values to the new ones
- fix: Stop setting cookies for while logging in (except the ones from WP_Session), should stop nginx from breaking.
- chg: Validating all fields when you post a booking
- chg: Removed `setcookie( 'eduadmin_loginUser' ...`, since it's not needed by the plugin.
- chg: Fixed line breaks in interest registration in a textarea
- add: Validate what fields are being sorted on (if it's even possible) in course and event lists
