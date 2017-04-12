<b><?php _e( "Page shortcodes", "eduadmin" ); ?></b><br />
<div class="eduadmin-shortcode" onclick="EduAdmin.ToggleAttributeList(this);">
	<span title="<?php esc_attr_e( "Shortcode to display the course list.\n(Click to view attributes)", "eduadmin" ); ?>">
		[eduadmin-listview]
	</span>
	<div class="eduadmin-attributelist">
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Sets which template to use in the listview (template_A, template_B, template_GF)", "eduadmin" ); ?>">template</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Filters the course list by category (Insert category ID)", "eduadmin" ); ?>">category</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Filters the course list by subject (Text)", "eduadmin" ); ?>">subject</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Hides the search box from the list", "eduadmin" ); ?>">hidesearch</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Only shows courses that have events", "eduadmin" ); ?>">onlyevents</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Only shows courses that do not have events", "eduadmin" ); ?>">onlyempty</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Tells the list how many items to show at max", "eduadmin" ); ?>">numberofevents</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Sets which mode you want to use in the list view (event, course)", "eduadmin" ); ?>">mode</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Only shows courses that do not have events", "eduadmin" ); ?>">orderby</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Only shows courses that do not have events", "eduadmin" ); ?>">order</span>
		</div>
	</div>
</div>
<div class="eduadmin-shortcode" onclick="EduAdmin.ToggleAttributeList(this);">
	<span title="<?php esc_attr_e( "Shortcode to display the course detail view.\n(Click to view attributes)", "eduadmin" ); ?>">
		[eduadmin-detailview]
	</span>
	<div class="eduadmin-attributelist">
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "For custom pages, you must provide courseid in all detail-info attributes.", "eduadmin" ); ?>">courseid</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "By using this attribute, you will tell the plugin not to load any templates.", "eduadmin" ); ?>">customtemplate</span>
		</div>
	</div>
</div>
<div class="eduadmin-shortcode" onclick="EduAdmin.ToggleAttributeList(this);">
	<span title="<?php esc_attr_e( "Shortcode to display the booking form view.", "eduadmin" ); ?>">
		[eduadmin-bookingview]
	</span>
	<div class="eduadmin-attributelist">
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "To build custom booking view pages, you can provide a course id", "eduadmin" ); ?>">courseid</span>
		</div>
	</div>
</div>
<div class="eduadmin-shortcode" onclick="EduAdmin.ToggleAttributeList(this);">
	<span title="<?php esc_attr_e( "Shortcode to display the login view\n(My Pages, Profile, Bookings, etc.)", "eduadmin" ); ?>">
		[eduadmin-loginview]
	</span>
</div>
<hr noshade="noshade" />
<b><?php _e( "Widgets", "eduadmin" ); ?></b><br />
<div class="eduadmin-shortcode" onclick="EduAdmin.ToggleAttributeList(this);">
	<span title="<?php esc_attr_e( "Shortcode to inject the login widget.", "eduadmin" ); ?>">
		[eduadmin-loginwidget]
	</span>
	<div class="eduadmin-attributelist">
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Text to show instead of standard", "eduadmin" ); ?>">logintext</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Text to show instead of standard", "eduadmin" ); ?>">logouttext</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Text to show instead of standard", "eduadmin" ); ?>">guesttext</span>
		</div>
	</div>
</div>
<hr noshade="noshade" />
<b><?php _e( "Detail shortcodes", "eduadmin" ); ?></b><br />
<div class="eduadmin-shortcode" onclick="EduAdmin.ToggleAttributeList(this);">
	<span title="<?php esc_attr_e( "Shortcode to display detailed information from provided attributes.\n(Click to view attributes)", "eduadmin" ); ?>">
		[eduadmin-detailinfo]
	</span>
	<div class="eduadmin-attributelist">
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "This will include a Javascript-snippet that replaces the page title with the current course name", "eduadmin" ); ?>">pagetitlejs</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "This attribute is only required if you do full custom pages for your courses.", "eduadmin" ); ?>">courseid</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches the name of the course", "eduadmin" ); ?>">coursename</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches the public name of the course", "eduadmin" ); ?>">coursepublicname</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches what level this course is", "eduadmin" ); ?>">courselevel</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches the URL of the course image", "eduadmin" ); ?>">courseimage</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches the image text of the course image", "eduadmin" ); ?>">courseimagetext</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches the number of days the course usually has", "eduadmin" ); ?>">coursedays</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches the start time of the course", "eduadmin" ); ?>">coursestarttime</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches the end time of the course", "eduadmin" ); ?>">courseendtime</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches the price of the course", "eduadmin" ); ?>">courseprice</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches the short description of the course", "eduadmin" ); ?>">coursedescriptionshort</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches the description of the course", "eduadmin" ); ?>">coursedescription</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches the goal of the course", "eduadmin" ); ?>">coursegoal</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches the target group of the course", "eduadmin" ); ?>">coursetarget</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches the prerequisites of the course", "eduadmin" ); ?>">courseprerequisites</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches what to do after the course", "eduadmin" ); ?>">courseafter</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches all the quotes from the course", "eduadmin" ); ?>">coursequote</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches a list of events for the course", "eduadmin" ); ?>">courseeventlist</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Filters the courseeventlist to show the specified amount of courses (Number)", "eduadmin" ); ?>">showmore</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Filters the courseeventlist to show only the specified city (Text)", "eduadmin" ); ?>">courseeventlistfiltercity</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Fetches value from a course attribute (Insert attribute ID)", "eduadmin" ); ?>">courseattributeid</span>
		</div>
		<div class="eduadmin-attribute">
			<span title="<?php esc_attr_e( "Gets the URL that is used to send the inquiry form for a course", "eduadmin" ); ?>">courseinquiryurl</span>
		</div>
	</div>
	<hr />
	<?php _e( "For more information about our shortcodes and attributes, check our GitHub-page" ); ?><br />
	<a href="https://github.com/MultinetInteractive/EduAdmin-WordPress/wiki" target="_blank">GitHub EduAdmin</a>
</div>