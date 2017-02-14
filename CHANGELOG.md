# Change log

## [Unreleased]
### Added
- Why did I change how we check for subjects? We now check against name again
- Bugfix: Page title should not contain object multiple times
- Show an error if you are trying to login with an invalid civic reg no
- Changed serialization of new customers, so it doesn't throw warnings about incomplete classes
- Fixed SingleParticipant-booking so that there will be less duplicates (It actually checks the logged in user customer and contact person)
- Fixed MultipleParticipants-booking so that there will be less duplicates

## [0.9.17.9]
### Added
- Added `disabled`-filter in customer check (Login), just in case.
- Adding support set page title on detail pages (old wp, new wp and "All in one SEO")
- Added option to set which field you want to use as page title
- Bugfix: Search with category, subject and course level should now be working
- More validation in login-form
- Bugfix: Places-left fix when below zero. It showed "Few spots left", instead of "No spots left"

## [0.9.17.8]
### Added
- Added warning for missing civic reg.no in booking form (instead of saying they participant is missing their name)
- Bugfix in civregno formatting

## [0.9.17.7]
### Added
- Readded `flush_rewrite_rules();` when `eduadmin-options_have_changed` is set to true, so we can get rid of the stupid "Go to Settings -> Permalinks and save to fix the paths" (I hate wordpress)
- Removed a lot of `?>` from PHP-files, so we won't output any data where it's unwanted
- Removed dashes except last one in validation
- Added civic reg.no validation to login forms

## [0.9.17.6]
### Added
- Added link in booking form to log out the current user (if logged in), in format `Not person? Log out`
- Added more phrases to `defaultPhrases.json`
- If you only allow one participant, inquiries also only allow a single participant.
- Added check if `queried_object` is set before checking it.

## [0.9.17.5]
### Added
- Added LICENSE.md
- Added limitedDiscountView in bookingTemplate to handle limited discount cards
- Added some phrases to defaultPhrases.json (I've got to find a way to do this automagically)
- Bugfix: Fixed date format function on profile -> discount cards. (used an old function)
- Bugfix: Suppressing warnings if `HTTP_REFERER` is missing
- Bugfix: We should use `edu__` in string concatenations instead of `edu_e`
- Bugfix: Event inquiry used the old date function
- Bugfix: We should pass along the settings to use event inquiries all the way..

## [0.9.17.4]
### Added
- Option to use civic reg.no validation (Swedish) in Booking settings
- Validation support in `frontendjs.js` to validate swedish civic reg.nos
- Added css-style to required input fields (`.eduadmin .inputHolder input[required]`)
- Added `<meta name="robots" content="noindex" />` to detail pages if no `courseid` is present, to prevent broken detail page to be indexed by search engines.
- Bugfix: Booking-form-login now checks the correct field when we try to login

### Removed
- Removed validation of existing password to enforce password retrieval on contacts with `NULL` passwords.

## [0.9.17.3]
### Added
- Added option to set how many "few" spots is when you use "Text only"

## [0.9.17.2]
### Added
- Bugfix: Invoice info should be shown if you don't use the setting from [0.9.17]

## [0.9.17.1]
### Added
- Bugfix: Search applies to events now as well..

## [0.9.17]
### Added
- Option to hide invoice information when events are free
- If the above option is used, hides invoice information from free events

## [0.9.16]
### Added
- `edu.apiclient.AfterUpdate` can be used as a function in javascript to run after the page has loaded all EduAdmin-related things.
- Added automatic focus on searchbox after searching
- Bugfix: It's called `debugTimers` not `debug` :)
- Missed a couple `LastApplicationDate` fix in the code
- Bugfix: It's also commonly known that you should check if all variables are declare
- Fixed date listing in event list templates
- Bugfix: Since you can change login field, we should populate the field used instead of only email.

## [0.9.15]
### Added
- Added `singlePersonBooking.php` to handle when the participant is customer, contact and participant.
- Added `__bookSingleParticipant.php` and `__bookMultipleParticipants.php` to handle different settings.
- Fixing `frontend.js` to work with single participant-settings.
- Switched to openssl_encrypt/decrypt since mcrypt is deprecated
- Added class name to dates, so you can style them yourself
- Added span around venue name, so you can style it, if you want to
- Adding support to load existing attribute data to customer and contact, when loading the booking form. (Would be bad if we emptied it..)

### Removed
- `getallheaders` is now gone, forever.

## [0.9.14]
### Added
- Attributes can now be saved on customers, contacts and participants (person) (Only multiple participants currently)

## [0.9.13]
### Added
- Added support for attributes (customer, contact and person) in booking form.
- Added functions to render the different attribute types, attributes not supported is multi value attributes, dates, checkbox lists and HTML
- Added: Saving customer attributes
- Bugfix: Phrases
- Booking login form didn't care about what field you chose, it does now.
- Pre-booking form also didn't care about what field you chose, it also does now.

## [0.9.12]
### Added
- Added option `eduadmin-allowDiscountCode`, to enable the customers to enter a discount code when they book participants for a event.
- Bugfix: When copying contact to participant, correct field is now copied, instead of surname.
- Added backend-api file to handle checking coupon codes
- Added support to validate coupon codes against the api
- Added support to recalculate the price and post the coupon with the Booking

## [0.9.11]
### Added
- Removed `margin-top: 1.0em; vertical-align: top;` from `.inputLabel` and replaced with `vertical-align: middle;`.
- Bugfix: Search was not respected by ajax-reloads. (Bad, bad JS..)
- Added extra option to show city **AND** venue name.
- Fixed all views and endpoints that show city to include venue name if the setting is on.
- Only show date period in the listview event list, instead of all course days.

## [0.9.10]
### Added
- If you selected civic registration number as login field, you must now fill it in on your customer contact. It's hard to login otherwise.
- Fixed an error with translations in Booking settings
- Fixing [#48](https://github.com/MultinetInteractive/EduAdmin-WordPress/issues/48), to allow users to choose "username" field
- Login-code checks the given login field instead of email.
- It is now possible to add your own translation directly in the plugin. (Again)
- Added extra filter to course list (ajax) to skip "next" event if there isn't a public price name set.
- Changed the filter for LastApplicationDate on events to satisfy the needs (and proper implementation) of being able to book the same day

## [0.9.9.2.40]
### Added
- Set `date_default_timezone_set` to `UTC` to get rid of warnings instead.

### Removed
- Removed all error suppression regarding dates.

## [0.9.9.2.39]
### Added
- More "fixes" for the broken host, only error suppression for `date` and `strtotime`

## [0.9.9.2.38]
### Added
- Lots, and lots of warning suppression (all `strtotime`)

### Updated
- `CONTRIBUTING.md` is updated (ripped from [jmaynard](https://medium.com/@jmaynard/a-contribution-policy-for-open-source-that-works-bfc4600c9d83#.c42dikaxi))

## [0.9.9.2.37]
### Added
- This changelog
- Bugfix: if phrase doesn't exist in our dictionary, it threw an error. It shouldn't do that.
- Bugfix: Some users have a faulty php-config and gives warnings about that we need to set a timezone before we run `strtotime`

## [0.9.9.2.36] - 2017-01-05
### Removed
- Removing our translation, making it possible for third party plugins to translate the plugin by using standard WordPress-translation

## [0.9.9.2.25] - 2016-12-05
### Added
- Added GF-course view (Hard coded with cities)
- Added attributes `order`, `orderby` on listview and detail info shortcodes
- Added attribute `mode` to listview shortcode, so you can select mode

## [0.9.9.2.5] - 2016-10-04
### Added
- Added support for sub events
- Changed links to be absolute
- Added support for event dates

## [0.9.7.5] - 2016-09-13
### Added
- Added attribute `numberofevents` to shortcode `[eduadmin-listview]`
- Fix in rewrite-script
- Added missing translations
- Also adds event inquiries for fullbooked events

## 0.9.7 - 2016-09-06
### Added
- Added inquiry support in course

[Unreleased]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.9...HEAD
[0.9.17.8]: https://github.com/MultinetInteractive/EduAdmin-WordPress/compare/v0.9.17.8...v0.9.17.9
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