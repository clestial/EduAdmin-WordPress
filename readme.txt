=== EduAdmin Booking ===

Contributors: mnchga
Tags: booking, participants, courses, events, eduadmin, lega online
Requires at least: 4.7
Tested up to: 4.9.1
Stable tag: 1.0.14
Requires PHP: 5.0.1 (with SoapClient)
License: GPL3
License-URI: https://www.gnu.org/licenses/gpl-3.0.en.html

EduAdmin plugin to allow visitors to book courses at your website. Requires EduAdmin-account.

== Description ==

Plugin that you connect to [EduAdmin](http://www.eduadmin.se) to enable booking on your website.
Requires at least PHP 5.0.1 (with [SoapClient](http://php.net/manual/en/book.soap.php) installed and configured)

| Repository | Latest version | Downloads |
| ---------- | --------------: | ---------: |
| WordPress.org | [![WordPress plugin](https://img.shields.io/wordpress/plugin/v/eduadmin-booking.svg)](https://wordpress.org/plugins/eduadmin-booking/) | [![WordPress plugin](https://img.shields.io/wordpress/plugin/dt/eduadmin-booking.svg)](https://wordpress.org/plugins/eduadmin-booking/) |

[![WordPress](https://img.shields.io/wordpress/v/eduadmin-booking.svg)](https://wordpress.org/plugins/eduadmin-booking/)
[![Gitter chat](https://badges.gitter.im/MultinetInteractive/EduAdmin-WordPress.png)](https://gitter.im/MultinetInteractive/EduAdmin-WordPress)
[![Build Status](https://travis-ci.org/MultinetInteractive/EduAdmin-WordPress.svg?branch=master)](https://travis-ci.org/MultinetInteractive/EduAdmin-WordPress)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/MultinetInteractive/EduAdmin-WordPress/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/MultinetInteractive/EduAdmin-WordPress/?branch=master)

== Installation ==

- Upload the zip-file and activate the plugin
- Provide the API key from EduAdmin.
- Create pages for the different views

== Upgrade Notice ==

### [1.0.12]
- JS-bugfix, the selector for the civic reg numbers should work on any field that has the correct class (and is not a template)

### [1.0.11]
- Bugfix with REST API, we don't need to pass the token now, so we can always get a valid token when needed.

### [1.0.10]
- Changing to REST API instead, hopefully this will increase the speed again.

### [1.0.9]
- We changed to admin-ajax here (It will be slow)

== Changelog ==

### [1.0.14]
- Bugfix: Search not checking if value was empty, which converted some values to `0`, which is bad.

### [1.0.13]
- Hide the course image, if there is no URL available.

### [1.0.12]
- JS-bugfix, the selector for the civic reg numbers should work on any field that has the correct class (and is not a template)

### [1.0.11]
- Bugfix with REST API, we don't need to pass the token now, so we can always get a valid token when needed.

### [1.0.10]
- Rewriting the AJAX-API a third time. This time we use the REST API.
- Increased performance overall by rethinking the localization-functions.
- Added more debugging timers to see if we can catch performance thieves.

### [1.0.9]
- Implementing current ajax-methods as WordPress-ajax-methods instead.
- Removed the whole backend-directory that contained the old AJAX-api.
- Added ability to hide `price,time` from the detail view. (Also fixed the bug that didn't hide the fields)

### [1.0.8]
- Code fixes to remove notices (if warnings are shown)
- Login field is now correctly typed if email is selected.
- Making it possible to hide fields on the default detail templates by using the attribute `hide`
  - Fields that can be hidden: `description,goal,target,prerequisites,after,quote`

### [1.0.7]
- Fixed text domain on three phrases I missed earlier.
- Fixing validation error for civic reg numbers.

### [1.0.6]
- Adding setting to force customers to be registered before being able to book

### [1.0.5]
- Defining `WP_SESSION_COOKIE` so that we won't get warnings/notices.
- Adding script to autodeploy to WordPress when we make new releases (Commits to production-branch)

### [1.0.4]
- Adding action `eduadmin-bookingform-loaded`, so that plugins can fire when the booking form is loaded.

### [1.0.3]
- Removing `.official.plugin` and `auto_update`, since we are running as a normal plugin now.

### [1.0.2]
- Removing internal language files
- Removing `README.md` and `CHANGELOG.md`, these live inside `readme.txt` (this file)
- Bugfix for questions and attributes with only one option (SOAP API gave us an object instead of an array)

### [1.0.1]
- Modified when languages should load (`plugins_loaded` instead of `init`)
- Changing text domain everywhere to `eduadmin-booking` (new WordPress-slug)
- Adding `autocomplete="new-password"` to password-field when you register a new account while booking

### [1.0.0]
#### WordPress-plugin compatibility/requirements
- Removing unnecessary paths (for functions that are never used)
- Fixing the correct way to include files (by path, not function..)
- Sanitizing everything I can think of/find.
- Modifying how pages are outputted.
- Implementing `WP_Session` to get rid of `$_SESSION`-usage
  - Finally found how to get rid of `$_SESSION` in the custom ajax-handlers.

#### Added
- Shortcode attributes on `[eduadmin-listview]`
  - `showsearch`: Overrides settings to show the search
  - `showcity`: Sets if you want to show/hide city from the list
  - `showbookbtn`: Sets if you want to show/hide the book button
  - `showreadmorebtn`: Sets if you want to show/hide the read more button
- Shortcode to show all public pricenames on Course `[eduadmin-coursepublicpricename]`
- Setting to hide invoice email
- Attribute to hide invoice email `[eduadmin-detailview hideinvoiceemailfield="1"]`

### [0.10.24]
#### Added
- Fixed validation-bug in javascript if you only had the contact person as a participant.

### [0.10.23]
#### Added
- Added pluralized text to the shortcode that shows course days `[eduadmin-detailinfo coursedays]`
  Now outputs `1 day` or `2 days`
- Reformatted the HTML for contact/participant names, so we can use 50% width. Works in *most* cases.

### [0.10.22]
#### Added
- Add sorting to pricenames in Eventlists
- Fixed faulty tooltips for `orderby` and `order`

### [0.10.21]
#### Added
- Check if number of free spots is equals or less than 0, instead of only 0.

### [0.10.20]
#### Added
- Added setting for List-views, to show week days (only event lists)

### [0.10.19]
#### Added
- Added support for attribute `courseid` in `[eduadmin-objectinterest]`-shortcode

### [0.10.18]
#### Added
- Added discount card-support, so end customers can use their discount cards when they book a course

### [0.10.17]
#### Added
- Added extra code to prevent bookings to go through when there are no free spots left

### [0.10.16]
#### Added
- Fixing code styles
- Fix sorting in `template_GF_listCourses`, again

### [0.10.15]
#### Added
- Bugfix: Logging in on non-existing contacts activated some kind of warning

### [0.10.14]
#### Added
- Fix in `template_GF_listCourses` to fix sorting

### [0.10.13]
#### Added
- Added new class `EduAdminBookingHandler`, to process bookings from the plugin
- Added span element around event time in booking form, so you can hide it.
- Moved booking handling to `EduAdminBookingHandler`.
- Added custom actions `eduadmin-checkpaymentplugins`, `eduadmin-processbooking` and `eduadmin-bookingcompleted`


### [0.10.12]
#### Added
- Bugfix: Fixed a bug where you were unable to select a single event.

### [0.10.11]
#### Added
- Fixing issues stated by scrutinizer
- Added class `EduAdminBookingInfo` that is passed to action `eduadmin-processbooking`
- Moved the redirect from when you've completed a booking to `createBooking.php`
- Added `NoRedirect`-property to `EduAdminBookingInfo` to skip the redirects after a booking is completed.
- Redid the `EduAdminClient` to conform to coding standards
- Added filter `edu-booking-error` so we can show dynamic errors in booking process.
- Fix: Show start/end-time on events with only one course day

### [0.10.10]
#### Added
- Fixing issues stated by scrutinizer
- Added `exit()` after `wp_redirect()`
- Bugfix: Correctly matching logout with `stristr`

### [0.10.9]
#### Added
- Bugfix: Sessions are now set before the class is loaded
- Changed how we handle redirects (Login/Logout)
- Plugin-support: We can now save plugin-settings
- Added .scrutinizer.yml
- Fixing issues stated by scrutinizer

### [0.10.8]
#### Added
- Trying to build everything as classes instead, just like WooCommerce
- Bugfix: While fetching prices, we should use the same date span as everything else.
- Started coding support for plugins

### [0.10.7]
#### Added
- Default translation is now in Swedish.

### [0.10.6]
#### Added
- Fixes mobile-layout on detail-page (Template-B)

### [0.10.5]
#### Added
- Added better version-check (support-wise)
- Bugfix: civic validation (Do not validate the invisible template)

### [0.10.4]
#### Added
- Added lots of `shield.io`-badges
- Added support to use [GitHub Updater](https://github.com/afragen/github-updater)
- Adding Travis-CI to begin experimenting with tests
- Adding check to `edu.api.authenticate.php` so we don't get warnings in travis
- Adding phpunit-tests to travis
- Added fix to session_start
- Redoing date limits for shown events. (Soon I'll have to make a setting for this)
- Updated readme.txt

### [0.10.3]
#### Added
- Adding span around time in eventlist, so it can be hidden with css `.eduadmin .eventTime` and `.edu-DayPopup .eventTime`

### [0.10.2]
#### Added
- Added option to block editing user fields of they are logged in

### [0.10.1]
#### Added
- Admin notices instead of just blurting the error text into the page.
- Pulled #64 and #63 from @ekandreas to fix composer compatibility and proper way to set access levels in menues.

### [0.10.0]
#### Added
- New date-handling, if there are more than 3 date groups, we show a popup instead
- Bugfix: Added CustomerID-filter to more lists (it flashed some events that were customer related)
- Bugfix: Removed debug info from "Spots left"-text

### [0.9.19]
#### Added
- Added classes to participant-lists, so that the headers can "set" the style easier than using strange CSS-selectors
- Bugfix: Places left didn't account for max spots.

### [0.9.18]
#### Added
- Switched version-numbering to `semver` to make it easier to use with composer
- Added participant-list under "My bookings" as requested by issue #62

### [0.9.17.16]
#### Added
- Bugfix: Pricenames with zero max participants should be selectable

### [0.9.17.15]
#### Added
- Rudimentary support to block people from booking with certain price names (Only when it's selectable)
- Bugfix: Javascript, dates, string. Woe is me.

### [0.9.17.14]
#### Added
- Bugfix: Validation in Javascript is a pain in the rear.

### [0.9.17.13]
#### Added
- Bugfix: Added code to save invoice reference on single participant bookings
- Bugfix: Fixed an JS-error on login pages.

### [0.9.17.12]
#### Added
- Added an extra option in customer groups, and a required flag, so you HAVE to choose a group before saving.
- Added invoice reference field to single person booking

### [0.9.17.11]
#### Added
- Bugfix: Page title must set separator as default parameter, or else things break

### [0.9.17.10]
#### Added
- Why did I change how we check for subjects? We now check against name again
- Bugfix: Page title should not contain object multiple times
- Show an error if you are trying to login with an invalid civic reg no
- Changed serialization of new customers, so it doesn't throw warnings about incomplete classes
- Fixed SingleParticipant-booking so that there will be less duplicates (It actually checks the logged in user customer and contact person)
- Fixed MultipleParticipants-booking so that there will be less duplicates

### [0.9.17.9]
#### Added
- Added `disabled`-filter in customer check (Login), just in case.
- Adding support set page title on detail pages (old wp, new wp and "All in one SEO")
- Added option to set which field you want to use as page title
- Bugfix: Search with category, subject and course level should now be working
- More validation in login-form
- Bugfix: Places-left fix when below zero. It showed "Few spots left", instead of "No spots left"

### [0.9.17.8]
#### Added
- Added warning for missing civic reg.no in booking form (instead of saying they participant is missing their name)
- Bugfix in civregno formatting

### [0.9.17.7]
#### Added
- Readded `flush_rewrite_rules();` when `eduadmin-options_have_changed` is set to true, so we can get rid of the stupid "Go to Settings -> Permalinks and save to fix the paths" (I hate wordpress)
- Removed a lot of `?>` from PHP-files, so we won't output any data where it's unwanted
- Removed dashes except last one in validation
- Added civic reg.no validation to login forms

### [0.9.17.6]
#### Added
- Added link in booking form to log out the current user (if logged in), in format `Not person? Log out`
- Added more phrases to `defaultPhrases.json`
- If you only allow one participant, inquiries also only allow a single participant.
- Added check if `queried_object` is set before checking it.

### [0.9.17.5]
#### Added
- Added LICENSE.md
- Added limitedDiscountView in bookingTemplate to handle limited discount cards
- Added some phrases to defaultPhrases.json (I've got to find a way to do this automagically)
- Bugfix: Fixed date format function on profile -> discount cards. (used an old function)
- Bugfix: Suppressing warnings if `HTTP_REFERER` is missing
- Bugfix: We should use `edu__` in string concatenations instead of `edu_e`
- Bugfix: Event inquiry used the old date function
- Bugfix: We should pass along the settings to use event inquiries all the way..

### [0.9.17.4]
#### Added
- Option to use civic reg.no validation (Swedish) in Booking settings
- Validation support in `frontendjs.js` to validate swedish civic reg.nos
- Added css-style to required input fields (`.eduadmin .inputHolder input[required]`)
- Added `<meta name="robots" content="noindex" />` to detail pages if no `courseid` is present, to prevent broken detail page to be indexed by search engines.
- Bugfix: Booking-form-login now checks the correct field when we try to login

#### Removed
- Removed validation of existing password to enforce password retrieval on contacts with `NULL` passwords.

### [0.9.17.3]
#### Added
- Added option to set how many "few" spots is when you use "Text only"

### [0.9.17.2]
#### Added
- Bugfix: Invoice info should be shown if you don't use the setting from [0.9.17]

### [0.9.17.1]
#### Added
- Bugfix: Search applies to events now as well..

### [0.9.17]
#### Added
- Option to hide invoice information when events are free
- If the above option is used, hides invoice information from free events

### [0.9.16]
#### Added
- `edu.apiclient.AfterUpdate` can be used as a function in javascript to run after the page has loaded all EduAdmin-related things.
- Added automatic focus on searchbox after searching
- Bugfix: It's called `debugTimers` not `debug` :)
- Missed a couple `LastApplicationDate` fix in the code
- Bugfix: It's also commonly known that you should check if all variables are declare
- Fixed date listing in event list templates
- Bugfix: Since you can change login field, we should populate the field used instead of only email.

### [0.9.15]
#### Added
- Added `singlePersonBooking.php` to handle when the participant is customer, contact and participant.
- Added `__bookSingleParticipant.php` and `__bookMultipleParticipants.php` to handle different settings.
- Fixing `frontend.js` to work with single participant-settings.
- Switched to openssl_encrypt/decrypt since mcrypt is deprecated
- Added class name to dates, so you can style them yourself
- Added span around venue name, so you can style it, if you want to
- Adding support to load existing attribute data to customer and contact, when loading the booking form. (Would be bad if we emptied it..)

#### Removed
- `getallheaders` is now gone, forever.

### [0.9.14]
#### Added
- Attributes can now be saved on customers, contacts and participants (person) (Only multiple participants currently)

### [0.9.13]
#### Added
- Added support for attributes (customer, contact and person) in booking form.
- Added functions to render the different attribute types, attributes not supported is multi value attributes, dates, checkbox lists and HTML
- Added: Saving customer attributes
- Bugfix: Phrases
- Booking login form didn't care about what field you chose, it does now.
- Pre-booking form also didn't care about what field you chose, it also does now.

### [0.9.12]
#### Added
- Added option `eduadmin-allowDiscountCode`, to enable the customers to enter a discount code when they book participants for a event.
- Bugfix: When copying contact to participant, correct field is now copied, instead of surname.
- Added backend-api file to handle checking coupon codes
- Added support to validate coupon codes against the api
- Added support to recalculate the price and post the coupon with the Booking

### [0.9.11]
#### Added
- Removed `margin-top: 1.0em; vertical-align: top;` from `.inputLabel` and replaced with `vertical-align: middle;`.
- Bugfix: Search was not respected by ajax-reloads. (Bad, bad JS..)
- Added extra option to show city **AND** venue name.
- Fixed all views and endpoints that show city to include venue name if the setting is on.
- Only show date period in the listview event list, instead of all course days.

### [0.9.10]
#### Added
- If you selected civic registration number as login field, you must now fill it in on your customer contact. It's hard to login otherwise.
- Fixed an error with translations in Booking settings
- Fixing [#48](https://github.com/MultinetInteractive/EduAdmin-WordPress/issues/48), to allow users to choose "username" field
- Login-code checks the given login field instead of email.
- It is now possible to add your own translation directly in the plugin. (Again)
- Added extra filter to course list (ajax) to skip "next" event if there isn't a public price name set.
- Changed the filter for LastApplicationDate on events to satisfy the needs (and proper implementation) of being able to book the same day

### [0.9.9.2.40]
#### Added
- Set `date_default_timezone_set` to `UTC` to get rid of warnings instead.

#### Removed
- Removed all error suppression regarding dates.

### [0.9.9.2.39]
#### Added
- More "fixes" for the broken host, only error suppression for `date` and `strtotime`

### [0.9.9.2.38]
#### Added
- Lots, and lots of warning suppression (all `strtotime`)

#### Updated
- `CONTRIBUTING.md` is updated (ripped from [jmaynard](https://medium.com/@jmaynard/a-contribution-policy-for-open-source-that-works-bfc4600c9d83#.c42dikaxi))

### [0.9.9.2.37]
#### Added
- This changelog
- Bugfix: if phrase doesn't exist in our dictionary, it threw an error. It shouldn't do that.
- Bugfix: Some users have a faulty php-config and gives warnings about that we need to set a timezone before we run `strtotime`

### [0.9.9.2.36] - 2017-01-05
#### Removed
- Removing our translation, making it possible for third party plugins to translate the plugin by using standard WordPress-translation

### [0.9.9.2.25] - 2016-12-05
#### Added
- Added GF-course view (Hard coded with cities)
- Added attributes `order`, `orderby` on listview and detail info shortcodes
- Added attribute `mode` to listview shortcode, so you can select mode

### [0.9.9.2.5] - 2016-10-04
#### Added
- Added support for sub events
- Changed links to be absolute
- Added support for event dates

### [0.9.7.5] - 2016-09-13
#### Added
- Added attribute `numberofevents` to shortcode `[eduadmin-listview]`
- Fix in rewrite-script
- Added missing translations
- Also adds event inquiries for fullbooked events

### 0.9.7 - 2016-09-06
#### Added
- Added inquiry support in course

[Unreleased]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.13...HEAD
[1.0.13]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.12...v1.0.13
[1.0.12]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.11...v1.0.12
[1.0.11]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.10...v1.0.11
[1.0.10]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.9...v1.0.10
[1.0.9]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.8...v1.0.9
[1.0.8]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.7...v1.0.8
[1.0.7]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.6...v1.0.7
[1.0.6]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.5...v1.0.6
[1.0.5]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.4...v1.0.5
[1.0.4]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.3...v1.0.4
[1.0.3]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.2...v1.0.3
[1.0.2]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.24...v1.0.0
[0.10.24]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.23...v0.10.24
[0.10.23]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.22...v0.10.23
[0.10.22]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.21...v0.10.22
[0.10.21]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.20...v0.10.21
[0.10.20]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.19...v0.10.20
[0.10.19]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.18...v0.10.19
[0.10.18]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.17...v0.10.18
[0.10.17]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.16...v0.10.17
[0.10.16]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.15...v0.10.16
[0.10.15]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.14...v0.10.15
[0.10.14]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.13...v0.10.14
[0.10.13]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.12...v0.10.13
[0.10.12]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.11...v0.10.12
[0.10.11]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.10...v0.10.11
[0.10.10]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.9...v0.10.10
[0.10.9]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.8...v0.10.9
[0.10.8]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.7...v0.10.8
[0.10.7]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.6...v0.10.7
[0.10.6]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.5...v0.10.6
[0.10.5]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.4...v0.10.5
[0.10.4]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.3...v0.10.4
[0.10.3]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.2...v0.10.3
[0.10.2]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.1...v0.10.2
[0.10.1]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.10.0...v0.10.1
[0.10.0]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.18...v0.10.0
[0.9.19]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.18...v0.9.19
[0.9.18]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.16...v0.9.18
[0.9.17.16]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.15...v0.9.17.16
[0.9.17.15]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.14...v0.9.17.15
[0.9.17.14]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.13...v0.9.17.14
[0.9.17.13]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.12...v0.9.17.13
[0.9.17.12]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.11...v0.9.17.12
[0.9.17.11]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.10...v0.9.17.11
[0.9.17.10]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.9...v0.9.17.10
[0.9.17.9]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.8...v0.9.17.9
[0.9.17.8]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.7...v0.9.17.8
[0.9.17.7]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.6...v0.9.17.7
[0.9.17.6]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.5...v0.9.17.6
[0.9.17.5]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.4...v0.9.17.5
[0.9.17.4]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.3...v0.9.17.4
[0.9.17.3]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.2...v0.9.17.3
[0.9.17.2]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.1...v0.9.17.2
[0.9.17.1]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17...v0.9.17.1
[0.9.17]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.16...v0.9.17
[0.9.16]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.15...v0.9.16
[0.9.15]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.14...v0.9.15
[0.9.14]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.13...v0.9.14
[0.9.13]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.12...v0.9.13
[0.9.12]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.11...v0.9.12
[0.9.11]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.10...v0.9.11
[0.9.10]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.9.2.40...v0.9.10
[0.9.9.2.40]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.9.2.39...v0.9.9.2.40
[0.9.9.2.39]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.9.2.38...v0.9.9.2.39
[0.9.9.2.38]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.9.2.37...v0.9.9.2.38
[0.9.9.2.37]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.9.2.36...v0.9.9.2.37
[0.9.9.2.36]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.9.2.25...v0.9.9.2.36
[0.9.9.2.25]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.9.2.5...v0.9.9.2.25
[0.9.9.2.5]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.7.5...v0.9.9.2.5
[0.9.7.5]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.7...v0.9.7.5