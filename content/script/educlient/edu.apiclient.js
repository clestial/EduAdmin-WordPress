/** global: wp_edu */
var wp_edu = wp_edu ? wp_edu : {AjaxUrl: '', CourseFolder: '', BaseUrl: ''};
/** global: edu */
var edu = edu ? edu : {};

edu.apiclient = {
    baseUrl: null,
    courseFolder: null,
    authToken: null,
    CookieBase: 'edu_',
    AfterUpdate: null,
    parseDocument: function () {
        if (typeof wp_edu !== 'undefined') {
            edu.apiclient.baseUrl = wp_edu.AjaxUrl;
            edu.apiclient.courseFolder = wp_edu.CourseFolder;
            edu.apiclient.authJS(function () {
                edu.apiclient.replaceLoginWidget();
                edu.apiclient.replaceEventListWidget();
                edu.apiclient.replaceCourseListDates();
                edu.apiclient.replaceCourseEventList();
            });
        }
    },
    replaceLoginWidget: function () {
        var lw = document.querySelectorAll('[data-eduwidget="loginwidget"]');
        if (lw) {
            var widgets = lw.length;
            for (var i = 0; i < widgets; i++) {
                edu.apiclient.getLoginWidget(lw[i]);
            }
        }
    },
    replaceEventListWidget: function () {
        var evLists = document.querySelectorAll('[data-eduwidget="eventlist"]');
        for (var i = 0, len = evLists.length; i < len; i++) {
            edu.apiclient.getEventList(evLists[i]);
        }
    },
    replaceCourseListDates: function () {
        var courseDateObjects = document.querySelectorAll('[data-eduwidget="courseitem-date"]');
        var objectIds = [];
        for (var i = 0, len = courseDateObjects.length; i < len; i++) {
            objectIds.push(courseDateObjects[i].attributes['data-objectid'].value);
        }
        if (objectIds.length > 0) {
            edu.apiclient.getCourseListDates(objectIds);
        }
    },
    replaceCourseEventList: function () {
        var eventList = document.querySelectorAll('[data-eduwidget="listview-eventlist"]');
        var eventLength = eventList.length;
        for (var i = 0; i < eventLength; i++) {
            edu.apiclient.getCourseEventList(eventList[i]);
        }
    },
    authJS: function (next) {
        next();
    },
    getCourseListDates: function (objectIds) {
        jQuery.ajax({
            url: edu.apiclient.baseUrl + '/courselist',
            type: 'POST',
            data: {
                objectIds: objectIds,
                showcoursedays: jQuery('.eduadmin-courselistoptions').data('showcoursedays'),
                spotsleft: jQuery('.eduadmin-courselistoptions').data('spotsleft'),
                fewspots: jQuery('.eduadmin-courselistoptions').data('fewspots'),
                spotsettings: jQuery('.eduadmin-courselistoptions').data('spotsettings'),
                city: jQuery('.eduadmin-courselistoptions').data('city'),
                category: jQuery('.eduadmin-courselistoptions').data('category'),
                subject: jQuery('.eduadmin-courselistoptions').data('subject'),
                subjectid: jQuery('.eduadmin-courselistoptions').data('subjectid'),
                courselevel: jQuery('.eduadmin-courselistoptions').data('courselevel'),
                showcoursetimes: jQuery('.eduadmin-courselistoptions').data('showcoursetimes'),
                showcourseprices: jQuery('.eduadmin-courselistoptions').data('showcourseprices'),
                showweekdays: jQuery('.eduadmin.courselistoptions').data('showweekdays'),
                currency: jQuery('.eduadmin-courselistoptions').data('currency'),
                search: jQuery('.eduadmin-courselistoptions').data('search'),
                showimages: jQuery('.eduadmin-courselistoptions').data('showimages'),
                template: jQuery('.eduadmin-courselistoptions').data('template'),
                numberofevents: jQuery('.eduadmin-courselistoptions').data('numberofevents'),
                fetchmonths: jQuery('.eduadmin-courselistoptions').data('fetchmonths'),
                showvenue: jQuery('.eduadmin-courselistoptions').data('showvenue'),
                orderby: jQuery('.eduadmin-courselistoptions').data('orderby'),
                order: jQuery('.eduadmin-courselistoptions').data('order')
            },
            success: function (d) {
                var o = d;
                if (typeof d !== "object") {
                    o = JSON.parse(d);
                }

                for (var k in o) {
                    if (o.hasOwnProperty(k)) {
                        var target = document.querySelector('[data-eduwidget="courseitem-date"][data-objectid="' + k + '"]');
                        if (target) {
                            target.innerHTML = o[k];
                        }
                    }
                }
                edu.apiclient.RunAfterUpdate();
            }
        });
    },
    getCourseEventList: function (target) {
        jQuery.ajax({
            url: edu.apiclient.baseUrl + '/courselist/events',
            type: 'POST',
            data: {
                baseUrl: wp_edu.BaseUrl,
                courseFolder: wp_edu.CourseFolder,
                showcoursedays: jQuery(target).data('showcoursedays'),
                spotsleft: jQuery(target).data('spotsleft'),
                fewspots: jQuery(target).data('fewspots'),
                spotsettings: jQuery(target).data('spotsettings'),
                city: jQuery(target).data('city'),
                category: jQuery(target).data('category'),
                subject: jQuery(target).data('subject'),
                subjectid: jQuery(target).data('subjectid'),
                courselevel: jQuery(target).data('courselevel'),
                showcoursetimes: jQuery(target).data('showcoursetimes'),
                showcourseprices: jQuery(target).data('showcourseprices'),
                showweekdays: jQuery(target).data('showweekdays'),
                currency: jQuery(target).data('currency'),
                search: jQuery(target).data('search'),
                showimages: jQuery(target).data('showimages'),
                template: jQuery(target).data('template'),
                numberofevents: jQuery(target).data('numberofevents'),
                fetchmonths: jQuery(target).data('fetchmonths'),
                showvenue: jQuery(target).data('showvenue'),
                orderby: jQuery(target).data('orderby'),
                order: jQuery(target).data('order')
            },
            success: function (d) {
                jQuery(target).html(d);
                edu.apiclient.RunAfterUpdate();
            }
        });
    },
    getEventList: function (target) {
        jQuery.ajax({
            url: edu.apiclient.baseUrl + '/eventlist',
            type: 'POST',
            data: {
                objectid: jQuery(target).data('objectid'),
                city: jQuery(target).data('city'),
                groupbycity: jQuery(target).data('groupbycity'),
                baseUrl: wp_edu.BaseUrl,
                courseFolder: wp_edu.CourseFolder,
                showmore: jQuery(target).data('showmore'),
                spotsleft: jQuery(target).data('spotsleft'),
                fewspots: jQuery(target).data('fewspots'),
                spotsettings: jQuery(target).data('spotsettings'),
                eid: jQuery(target).data('eid'),
                numberofevents: jQuery(target).data('numberofevents'),
                fetchmonths: jQuery(target).data('fetchmonths'),
                showvenue: jQuery(target).data('showvenue'),
                eventinquiry: jQuery(target).data('eventinquiry')
            },
            success: function (d) {
                jQuery(target).replaceWith(d);
                edu.apiclient.RunAfterUpdate();
            }
        });
    },
    getLoginWidget: function (target) {
        var loginText = '';
        var logoutText = '';
        var guestText = '';
        if (jQuery(target).data('logintext')) {
            loginText = jQuery(target).data('logintext');
        }

        if (jQuery(target).data('logouttext')) {
            logoutText = jQuery(target).data('logouttext');
        }

        if (jQuery(target).data('guesttext')) {
            guestText = jQuery(target).data('guesttext');
        }

        jQuery.ajax({
            url: edu.apiclient.baseUrl + '/loginwidget',
            type: 'POST',
            data: {
                baseUrl: wp_edu.BaseUrl,
                courseFolder: wp_edu.CourseFolder,
                logintext: loginText,
                logouttext: logoutText,
                guesttext: guestText
            },
            success: function (d) {
                jQuery(target).replaceWith(d);
                edu.apiclient.RunAfterUpdate();
            }
        });
    },
    RunAfterUpdate: function () {
        if (edu.apiclient.AfterUpdate && typeof edu.apiclient.AfterUpdate == 'function') {
            edu.apiclient.AfterUpdate.call(null);
        }
    },
    CheckCouponCode: function (code, objectId, categoryId, onData) {
        jQuery.ajax({
            url: edu.apiclient.baseUrl + '/coupon/check',
            type: 'POST',
            data: {
                code: code,
                objectId: objectId,
                categoryId: categoryId
            },
            success: function (d) {
                if (onData && typeof onData == 'function') {
                    onData(d);
                }
            }
        });
    },
    GetCookie: function (name) {
        try {
            var cookie = document.cookie;
            name = edu.apiclient.CookieBase + name;
            var valueStart = cookie.indexOf(name + "=") + 1;
            if (valueStart === 0) {
                return null;
            }
            valueStart += name.length;
            var valueEnd = cookie.indexOf(";", valueStart);
            if (valueEnd == -1) {
                valueEnd = cookie.length;
            }

            return decodeURIComponent(cookie.substring(valueStart, valueEnd));
        } catch (e) {
            ;
        }
        return null;
    },
    SetCookie: function (name, value, expire) {
        var temp = edu.apiclient.CookieBase + name + "=" + encodeURIComponent(value) +
            (expire !== 0
                    ? "; path=/; expires=" + ((new Date((new Date()).getTime() + expire)).toUTCString()) + ";"
                    : "; path=/;"
            );
        document.cookie = temp;
    },
    CanSetCookies: function () {
        edu.apiclient.SetCookie('_eduCookieTest', 'true', 0);
        var can = edu.apiclient.GetCookie('_eduCookieTest') != null;
        edu.apiclient.DelCookie('_eduCookieTest');
        return can;
    },
    DelCookie: function (name) {
        document.cookie = edu.apiclient.CookieBase + name + '=0; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }
};

(function () {
    if (typeof jQuery != 'undefined') {
        jQuery('document').ready(function () {
            edu.apiclient.parseDocument();
        });
    } else {
        setTimeout(edu.apiclient.parseDocument, 500);
    }
})();
