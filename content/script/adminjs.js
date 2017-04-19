var EduAdmin = {
	TestApiConnection: function () {
		var apiUserId = document.getElementById( 'eduadmin-api_user_id' ).value;
		var apiHash = document.getElementById( 'eduadmin-api_hash' ).value;
		if ( typeof jQuery != 'undefined' ) {
			jQuery.ajax( {
				data: {
					aui: apiUserId,
					ah: apiHash
				},
				url: '../wp-content/plugins/eduadmin/includes/backEnd.php',
				success: function ( d ) {
					alert( d.indexOf( 'true' ) > -1
						? 'The api connection succeded!'
						: 'The api connection failed!\nCheck credentials!' );
				},
				error: function () {
					alert( 'An error occured while checking the api connection' );
				}
			} );
		} else {
			alert( 'You must have jQuery to be able to check the Api-connection' );
		}
	},
	UnlockApiAuthentication: function () {
		var apiKey = document.getElementById( 'eduadmin-api-key' );
		apiKey.readOnly = false;

		var unlock = document.getElementById( 'edu-unlockButton' );
		unlock.style.display = 'none';
	},
	ToggleAttributeList: function ( item ) {
		var me = jQuery( item );
		me.find( '.eduadmin-attributelist' ).slideToggle( 'fast' );
	},
	SpotExampleText: function () {
		/** global: availText */
		var selVal = jQuery( '.eduadmin-spotsLeft :selected' ).val();
		jQuery( '#eduadmin-spotExampleText' ).text( availText[selVal] );
		jQuery( '#eduadmin-intervalSetting' ).hide();
		jQuery( '#eduadmin-alwaysFewSpots' ).hide();
		switch ( selVal ) {
			case 'intervals':
				jQuery( '#eduadmin-intervalSetting' ).show();
				break;
			case 'alwaysFewSpots':
			case 'onlyText':
				jQuery( '#eduadmin-alwaysFewSpots' ).show();
				break;
			default:
				break;
		}
	},
	ListSettingsSetFields: function () {

	}
};