=== EduAdmin Booking ===
Contributors: mnchga
Tags: booking, participants, courses, events, eduadmin, lega online
Requires at least: 4.7
Tested up to: 4.9.2
Stable tag: 1.0.25
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

= 1.0.23 =
All translations will be wiped since we're moving to full WordPress-translation management, for all phrases, not just backend

= 1.0.22 =
If you are using an older version than 1.0.21, please test this version on a development server before deploying live.
Since version 1.0.21, there are a lot of design changes in the CSS, that might require you to reset style settings,
and redo your own customization.

= 1.0.21 =
- LOTS of design changes, please update to a development server to see if anything breaks

== Changelog ==

### 1.0.25
- Bugfix: Missing styles

### 1.0.24
- Bugfix: Booking button gets disabled, and aborts the form post.. For some reason

### [1.0.23]
- Translations are wiped, so that 3rd-party plugins can translate the plugin better (and language packs for default phrases)
- Adding first version of EduAdmin PHP API Client
- Redoing how template blocks are rendered (now using a single template, instead of 3 separate to update)
- Removed lots of the changelog to a separate file found at https://github.com/MultinetInteractive/EduAdmin-WordPress/blob/master/CHANGELOG.md

### [1.0.22]
- Disabling the book-button when the form is valid and the booking is under way
- Fixes some styles to use `px` instead of `rem`
- Adding `data-price` to the fields that were missing, that (for some reason) the price-calculation wanted

[1.0.23]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.22...v1.0.23
[1.0.22]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.21...v1.0.22