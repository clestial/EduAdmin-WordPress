<b><?php esc_html_e( 'Page shortcodes', 'eduadmin-booking' ); ?></b><br/>
<div class="eduadmin-shortcode" onclick="EduAdmin.ToggleAttributeList(this);">
	<span title="<?php esc_attr_e( "Shortcode to display the course list.\n(Click to view attributes)", 'eduadmin-booking' ); ?>">
		[eduadmin-listview]
	</span>
	<div class="eduadmin-attributelist">
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Sets which template to use in the listview (template_A, template_B, template_GF)', 'eduadmin-booking' ); ?>">template</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Filters the course list by category (Insert category ID)', 'eduadmin-booking' ); ?>">category</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Filters the course list by subject (Text)', 'eduadmin-booking' ); ?>">subject</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Hides the search box from the list', 'eduadmin-booking' ); ?>">hidesearch</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Only shows courses that have events', 'eduadmin-booking' ); ?>">onlyevents</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Only shows courses that do not have events', 'eduadmin-booking' ); ?>">onlyempty</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Tells the list how many items to show at max', 'eduadmin-booking' ); ?>">numberofevents</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Sets which mode you want to use in the list view (event, course)', 'eduadmin-booking' ); ?>">mode</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Sets the field to sort by', 'eduadmin-booking' ); ?>">orderby</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Set which order to sort the data (ASC / DESC)', 'eduadmin-booking' ); ?>">order</span>
		</div>
	</div>
</div>
<div class="eduadmin-shortcode" onclick="EduAdmin.ToggleAttributeList(this);">
	<span title="<?php esc_attr_e( "Shortcode to display the course detail view.\n(Click to view attributes)", 'eduadmin-booking' ); ?>">
		[eduadmin-detailview]
	</span>
	<div class="eduadmin-attributelist">
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'For custom pages, you must provide courseid in all detail-info attributes.', 'eduadmin-booking' ); ?>">courseid</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'By using this attribute, you will tell the plugin not to load any templates.', 'eduadmin-booking' ); ?>">customtemplate</span>
		</div>
	</div>
</div>
<div class="eduadmin-shortcode" onclick="EduAdmin.ToggleAttributeList(this);">
	<span title="<?php esc_attr_e( 'Shortcode to display the booking form view.', 'eduadmin-booking' ); ?>">
		[eduadmin-bookingview]
	</span>
	<div class="eduadmin-attributelist">
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'To build custom booking view pages, you can provide a course id', 'eduadmin-booking' ); ?>">courseid</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Hides the invoice e-mail field from the form', 'eduadmin-booking' ); ?>">hideinvoiceemailfield</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Force show invoice information fields', 'eduadmin-booking' ); ?>">showinvoiceinformation</span>
		</div>
	</div>
</div>
<div class="eduadmin-shortcode" onclick="EduAdmin.ToggleAttributeList(this);">
	<span title="<?php esc_attr_e( "Shortcode to display the login view\n(My Pages, Profile, Bookings, etc.)", 'eduadmin-booking' ); ?>">
		[eduadmin-loginview]
	</span>
</div>
<div class="eduadmin-shortcode" onclick="EduAdmin.ToggleAttributeList(this);">
	<span title="<?php esc_attr_e( "Shortcode to display pricenames of specific course.\n(Click to view attributes)", 'eduadmin-booking' ); ?>">
		[eduadmin-coursepublicpricename]
	</span>
	<div class="eduadmin-attributelist">
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'To get pricenames of a course, you provide a course id', 'eduadmin-booking' ); ?>">courseid</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Tells the list how many items to show at max', 'eduadmin-booking' ); ?>">numberofprices</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Sets the field to sort by', 'eduadmin-booking' ); ?>">orderby</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Set which order to sort the data (ASC / DESC)', 'eduadmin-booking' ); ?>">order</span>
		</div>
	</div>
</div>
<hr noshade="noshade"/>
<b><?php esc_html_e( 'Widgets', 'eduadmin-booking' ); ?></b><br/>
<div class="eduadmin-shortcode" onclick="EduAdmin.ToggleAttributeList(this);">
	<span title="<?php esc_attr_e( 'Shortcode to inject the login widget.', 'eduadmin-booking' ); ?>">
		[eduadmin-loginwidget]
	</span>
	<div class="eduadmin-attributelist">
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Text to show instead of standard', 'eduadmin-booking' ); ?>">logintext</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Text to show instead of standard', 'eduadmin-booking' ); ?>">logouttext</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Text to show instead of standard', 'eduadmin-booking' ); ?>">guesttext</span>
		</div>
	</div>
</div>
<hr noshade="noshade"/>
<b><?php esc_html_e( 'Detail shortcodes', 'eduadmin-booking' ); ?></b><br/>
<div class="eduadmin-shortcode" onclick="EduAdmin.ToggleAttributeList(this);">
	<span title="<?php esc_attr_e( "Shortcode to display detailed information from provided attributes.\n(Click to view attributes)", 'eduadmin-booking' ); ?>">
		[eduadmin-detailinfo]
	</span>
	<div class="eduadmin-attributelist">
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'This will include a Javascript-snippet that replaces the page title with the current course name', 'eduadmin-booking' ); ?>">pagetitlejs</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'This attribute is only required if you do full custom pages for your courses.', 'eduadmin-booking' ); ?>">courseid</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches the name of the course', 'eduadmin-booking' ); ?>">coursename</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches the public name of the course', 'eduadmin-booking' ); ?>">coursepublicname</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches what level this course is', 'eduadmin-booking' ); ?>">courselevel</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches the URL of the course image', 'eduadmin-booking' ); ?>">courseimage</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches the image text of the course image', 'eduadmin-booking' ); ?>">courseimagetext</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches the number of days the course usually has', 'eduadmin-booking' ); ?>">coursedays</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches the start time of the course', 'eduadmin-booking' ); ?>">coursestarttime</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches the end time of the course', 'eduadmin-booking' ); ?>">courseendtime</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches the price of the course', 'eduadmin-booking' ); ?>">courseprice</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches the short description of the course', 'eduadmin-booking' ); ?>">coursedescriptionshort</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches the description of the course', 'eduadmin-booking' ); ?>">coursedescription</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches the goal of the course', 'eduadmin-booking' ); ?>">coursegoal</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches the target group of the course', 'eduadmin-booking' ); ?>">coursetarget</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches the prerequisites of the course', 'eduadmin-booking' ); ?>">courseprerequisites</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches what to do after the course', 'eduadmin-booking' ); ?>">courseafter</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches all the quotes from the course', 'eduadmin-booking' ); ?>">coursequote</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches a list of events for the course', 'eduadmin-booking' ); ?>">courseeventlist</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Filters the courseeventlist to show the specified amount of courses (Number)', 'eduadmin-booking' ); ?>">showmore</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Filters the courseeventlist to show only the specified city (Text)', 'eduadmin-booking' ); ?>">courseeventlistfiltercity</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Fetches value from a course attribute (Insert attribute ID)', 'eduadmin-booking' ); ?>">courseattributeid</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( 'Gets the URL that is used to send the inquiry form for a course', 'eduadmin-booking' ); ?>">courseinquiryurl</span>
		</div>
	</div>
	<hr/>
	<?php esc_html_e( 'For more information about our shortcodes and attributes, check our GitHub-page', 'eduadmin-booking' ); ?>
	<br/>
	<a href="https://github.com/MultinetInteractive/EduAdmin-WordPress/wiki" target="_blank">GitHub EduAdmin</a>
</div>