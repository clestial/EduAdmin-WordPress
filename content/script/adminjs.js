var EduAdmin = {
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