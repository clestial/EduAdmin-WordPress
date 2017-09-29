/** global: edu */

var eduBookingView = {
	Customer: null,
	ContactPerson: null,
	Participants: [],
	SingleParticipant: false,
	MaxParticipants: 0,
	CurrentParticipants: 0,
	DiscountPercent: 0,
	AddParticipant: function () {
		if ( !eduBookingView.SingleParticipant ) {
			if ( eduBookingView.MaxParticipants == -1 || eduBookingView.CurrentParticipants < eduBookingView.MaxParticipants ) {
				var holder = document.getElementById( 'edu-participantHolder' );
				var tmpl = document.querySelector( '.eduadmin .participantItem.template' );
				var cloned = tmpl.cloneNode( true );
				cloned.style.display = 'block';
				cloned.className = cloned.className.replace( ' template', '' );
				holder.appendChild( cloned );
			}
			else {
				var partWarning = document.getElementById( 'edu-warning-participants' );
				if ( partWarning ) {
					partWarning.style.display = 'block';
					setTimeout( function () {
						var partWarning = document.getElementById( 'edu-warning-participants' );
						partWarning.style.display = '';
					}, 5000 );
				}
			}
		}
		eduBookingView.UpdatePrice();
	},
	RemoveParticipant: function ( obj ) {
		var participantHolder = document.getElementById( 'edu-participantHolder' );
		participantHolder.removeChild( obj.parentNode.parentNode );
		eduBookingView.UpdatePrice();
	},
	SelectEvent: function ( obj ) {
		var eventid = obj.value;
		if ( eventid !== "-1" ) {
			location.href = '?eid=' + eventid;
		}
	},
	CheckParticipantCount: function () {
		var participants = (eduBookingView.SingleParticipant
			? 1
			: document.querySelectorAll( '.eduadmin .participantItem:not(.template):not(.contactPerson)' ).length - 1);
		return !(participants >= eduBookingView.MaxParticipants && eduBookingView.MaxParticipants >= 0);

	},
	UpdatePrice: function () {
		var contactParticipant = document.getElementById( 'contactIsAlsoParticipant' );
		var contact = 0;
		if ( contactParticipant ) {
			if ( contactParticipant.checked ) {
				contact = 1;
			} else {
				contact = 0;
			}
		}
		eduBookingView.ContactAsParticipant();
		eduBookingView.CurrentParticipants = (eduBookingView.SingleParticipant
			? 1
			: document.querySelectorAll( '.eduadmin .participantItem:not(.template):not(.contactPerson)' ).length + contact);

		var questions = document.querySelectorAll( '.questionPanel [data-price]' );

		var questionPrice = 0.0;
		for ( var qi = 0; qi < questions.length; qi++ ) {
			var question = questions[qi];
			var price = parseFloat( question.dataset.price );
			var qtype = question.dataset.type;
			if ( !isNaN( price ) ) {
				switch ( qtype ) {
					case "number":
						if ( question.value != '' && !isNaN( question.value ) && parseInt( question.value ) > 0 ) {
							questionPrice += (price * parseInt( question.value ));
						} else {
							question.value = '';
						}
						break;
					case "text":
						if ( question.value != '' ) {
							questionPrice += price;
						}
						break;
					case "note":
						if ( question.value != '' ) {
							questionPrice += price;
						}
						break;
					case "radio":
						if ( question.checked ) {
							questionPrice += price;
						}
						break;
					case "check":
						if ( question.checked ) {
							questionPrice += price;
						}
						break;
					case "dropdown":
						if ( question.selected ) {
							questionPrice += price;
						}
						break;
					case "infotext":
						questionPrice += price;
						break;
					case "date":
						if ( question.value != '' ) {
							questionPrice += price;
						}
						break;
					default:
						break;
				}
			}
		}

		var priceObject = document.getElementById( 'sumValue' );

		var priceDdl = document.getElementById( 'edu-pricename' );
		if ( priceDdl !== null ) {
			var selected = priceDdl.selectedOptions[0];
			var ppp = 0.0;
			if ( selected !== null && undefined !== selected.attributes["data-price"] ) {
				ppp = parseFloat( selected.attributes["data-price"].value );
			}
			if ( typeof window.discountPerParticipant !== 'undefined' && window.discountPerParticipant > 0 ) {
				var disc = window.discountPerParticipant * ppp;
				window.pricePerParticipant = ppp - disc;
			} else {
				window.pricePerParticipant = ppp;
			}
		}

		if ( priceObject && typeof window.pricePerParticipant !== 'undefined' && window.currency != '' ) {
			var newPrice = 0.0;
			var participantPriceNames = document.querySelectorAll( '.participantItem:not(.template) .participantPriceName' );
			if ( participantPriceNames.length > 0 ) {
				var participants = eduBookingView.CurrentParticipants;
				for ( var i = 0; i < participants; i++ ) {
					if ( window.discountPerParticipant !== undefined && window.discountPerParticipant > 0 ) {
						var lpr = parseFloat( participantPriceNames[i].selectedOptions[0].attributes['data-price'].value );
						var disc = window.discountPerParticipant * lpr;
						newPrice += lpr - disc;
					} else {
						newPrice += parseFloat( participantPriceNames[i].selectedOptions[0].attributes['data-price'].value );
					}
				}
			} else {
				newPrice = (eduBookingView.CurrentParticipants * window.pricePerParticipant);
			}
			if ( !isNaN( questionPrice ) ) {
				newPrice += questionPrice;
			}

			var subEventPrices = document.querySelectorAll( '.eduadmin .participantItem:not(.template):not(.contactPerson) input.subEventCheckBox:checked' );
			if ( subEventPrices.length > 0 ) {
				for ( var i = 0; i < subEventPrices.length; i++ ) {
					newPrice += parseFloat( subEventPrices[i].attributes['data-price'].value );
				}
			}

			if ( eduBookingView.SingleParticipant || (contactParticipant && contactParticipant.checked) ) {
				subEventPrices = document.querySelectorAll( '.eduadmin .participantItem.contactPerson:not(.template) input.subEventCheckBox:checked' );
				if ( subEventPrices.length > 0 ) {
					for ( var i = 0; i < subEventPrices.length; i++ ) {
						newPrice += parseFloat( subEventPrices[i].attributes['data-price'].value );
					}
				}
			}

			if ( window.totalPriceDiscountPercent != 0 || eduBookingView.DiscountPercent != 0 ) {
				var disc = ((window.totalPriceDiscountPercent + eduBookingView.DiscountPercent) / 100) * newPrice;
				newPrice = newPrice - disc;
			}

			priceObject.innerHTML = numberWithSeparator( newPrice, ' ' ) + ' ' + window.currency + ' ' + window.vatText;
		}

	},
	UpdateInvoiceCustomer: function () {
		var invoiceView = document.getElementById( 'invoiceView' );
		if ( invoiceView ) {
			invoiceView.style.display = invoiceView.style.display == 'block' ? 'none' : 'block';
		}
	},
	ContactAsParticipant: function () {
		var contactParticipant = document.getElementById( 'contactIsAlsoParticipant' );
		var contact = 0;
		if ( contactParticipant ) {
			if ( contactParticipant.checked ) {
				contact = 1;
			} else {
				contact = 0;
			}
		}
		var contactParticipantItem = document.getElementById( 'contactPersonParticipant' );
		if ( contactParticipantItem ) {
			contactParticipantItem.style.display = contact == 1 ? 'block' : 'none';

			var cFirstName = document.getElementById( 'edu-contactFirstName' ).value;
			var cLastName = document.getElementById( 'edu-contactLastName' ).value;
			var cEmail = document.getElementById( 'edu-contactEmail' ).value;
			var cPhone = document.getElementById( 'edu-contactPhone' ).value;
			var cMobile = document.getElementById( 'edu-contactMobile' ).value;

			document.querySelector( '.contactFirstName' ).value = cFirstName;
			document.querySelector( '.contactLastName' ).value = cLastName;
			document.querySelector( '.contactEmail' ).value = cEmail;
			document.querySelector( '.contactPhone' ).value = cPhone;
			document.querySelector( '.contactMobile' ).value = cMobile;
			var tCivReg = document.querySelector( '.contactCivReg' );
			if ( tCivReg ) {
				tCivReg.value = document.getElementById( 'edu-contactCivReg' ).value;
			}

			if ( contact == 1 && !this.AddedContactPerson ) {
				var freeParticipant = document.querySelector( '.eduadmin .participantItem:not(.template):not(.contactPerson)' );
				if ( freeParticipant ) {
					var freeFirstName = freeParticipant.querySelector( '.participantFirstName' );
					if ( freeFirstName ) {
						if ( freeFirstName.value === '' ) {
							var removeButton = freeParticipant.querySelector( '.removeParticipant' );
							var participantHolder = document.getElementById( 'edu-participantHolder' );
							participantHolder.removeChild( removeButton.parentNode.parentNode );
						}
					}
				}
				this.AddedContactPerson = true;
			}
		}
	},
	AddedContactPerson: false,
	ValidateDiscountCode: function () {
		edu.apiclient.CheckCouponCode(
			jQuery( '#edu-discountCode' ).val(),
			jQuery( '.validateDiscount' ).data( 'objectid' ),
			jQuery( '.validateDiscount' ).data( 'categoryid' ),
			function ( data ) {
				if ( data ) {
					jQuery( '#edu-discountCodeID' ).val( data.CouponID );
					eduBookingView.DiscountPercent = data.DiscountPercent;
					eduBookingView.UpdatePrice();
				} else {
					// Invalid code
					var codeWarning = document.getElementById( 'edu-warning-discount' );
					if ( codeWarning ) {
						codeWarning.style.display = 'block';
						setTimeout( function () {
							var codeWarning = document.getElementById( 'edu-warning-discount' );
							codeWarning.style.display = '';
						}, 5000 );
					}
				}
			}
		);
	},
	CheckValidation: function () {
		var terms = document.getElementById( 'confirmTerms' );
		if ( terms ) {
			if ( !terms.checked ) {
				var termWarning = document.getElementById( 'edu-warning-terms' );
				if ( termWarning ) {
					termWarning.style.display = 'block';
					setTimeout( function () {
						var termWarning = document.getElementById( 'edu-warning-terms' );
						termWarning.style.display = '';
					}, 5000 );
				}
				return false;
			}
		}

		var participants = document.querySelectorAll( '.eduadmin .participantItem:not(.template):not(.contactPerson)' );
		var requiredFieldsToCreateParticipants = [
			'participantFirstName[]',
			'participantCivReg[]'
		];

		if ( ShouldValidateCivRegNo && !eduBookingView.ValidateCivicRegNo() ) {
			return false
		}


		var contactParticipant = document.getElementById( 'contactIsAlsoParticipant' );
		var contact = 0;
		if ( contactParticipant ) {
			if ( contactParticipant.checked ) {
				contact = 1;
			} else {
				contact = 0;
			}
		}

		if ( eduBookingView.SingleParticipant ) {
			contact = 1;
		}

		if ( participants.length + contact == 0 ) {
			var noPartWarning = document.getElementById( 'edu-warning-no-participants' );
			if ( noPartWarning ) {
				noPartWarning.style.display = 'block';
				setTimeout( function () {
					var noPartWarning = document.getElementById( 'edu-warning-no-participants' );
					noPartWarning.style.display = '';
				}, 5000 );
			}
			return false;
		}

		for ( var i = 0; i < participants.length; i++ ) {
			var participant = participants[i];
			var fields = participant.querySelectorAll( 'input' );
			for ( var f = 0; f < fields.length; f++ ) {
				if ( requiredFieldsToCreateParticipants.indexOf( fields[f].name ) >= 0 ) {

					if ( fields[f].value.replace( / /i, '' ) == '' ) {
						/* Show missing participant-name warning */
						if ( fields[f].name == 'participantFirstName[]' ) {
							var partWarning = document.getElementById( 'edu-warning-missing-participants' );
							if ( partWarning ) {
								partWarning.style.display = 'block';
								setTimeout( function () {
									var partWarning = document.getElementById( 'edu-warning-missing-participants' );
									partWarning.style.display = '';
								}, 5000 );
							}
						}
						else if ( fields[f].name == 'participantCivReg[]' ) {
							var civicWarning = document.getElementById( 'edu-warning-missing-civicregno' );
							if ( civicWarning ) {
								civicWarning.style.display = 'block';
								setTimeout( function () {
									var civicWarning = document.getElementById( 'edu-warning-missing-civicregno' );
									civicWarning.style.display = '';
								}, 5000 );
							}
						}
						return false;
					}
				}
			}
		}

		return true;
	},
	ValidateCivicRegNo: function () {

		function __isValid( civRegField ) {
			var civReg = civRegField.value;
			if ( !civReg || civReg.length == 0 ) {
				return false;
			}

			if ( !civReg.match( /^(\d{2,4})-?(\d{2})-?(\d{2})-?(\d{4})$/i ) ) {
				return false;
			}

			var date = new Date();
			var year = RegExp.$1, month = RegExp.$2, day = RegExp.$3, unique = RegExp.$4;
			if ( year.toString().length <= 2 ) {
				year = date.getFullYear().toString().substring( 0, 2 ) + '' + year;
				while ( year > date.getFullYear() ) {
					year -= 100;
				}
			}

			var checkDate = new Date( year, month - 1, day );
			if ( Object.prototype.toString.call( checkDate ) !== '[object Date]' || isNaN( checkDate.getTime() ) ) {
				return false;
			}

			if ( month.toString().length == 1 ) {
				month = '0' + month;
			}

			if ( day.toString().length == 1 ) {
				day = '0' + day;
			}

			var formattedCivReg =
				year + '' +
				month + '' +
				day + '-' +
				unique;

			civRegField.value = formattedCivReg;
			var cleanCivReg = formattedCivReg.replace( /-/gi, '' ).substr( 2 ), parity = cleanCivReg.length % 2,
				sum = 0;
			for ( var i = 0; i < cleanCivReg.length; i++ ) {
				var d = parseInt( cleanCivReg.charAt( i ), 10 );
				if ( i % 2 == parity ) {
					d *= 2;
				}
				if ( d > 9 ) {
					d -= 9;
				}
				sum += d;
			}
			return (sum % 10) === 0;
		}

		var civicRegNoFields = jQuery( '.participantItem:not(.template) .eduadmin-civicRegNo' );
		for ( var i = 0; i < civicRegNoFields.length; i++ ) {
			var field = civicRegNoFields[i];
			if ( !__isValid( field ) ) {
				field.focus();
				return false;
			}
		}
		return true;
	}
};

function edu_openDatePopup( obj ) {
	jQuery( '.edu-DayPopup.cloned' ).remove();

	var pos = jQuery( obj.parentElement ).offset();
	var width = jQuery( obj ).outerWidth();

	var pop = jQuery( obj.nextSibling ).clone().appendTo( 'body' );
	pop.addClass( 'cloned' );
	pop.css( {
		display: 'block',
		opacity: 1,
		top: (pos.top) + 'px',
		left: (pos.left + width) + 10 + 'px'
	} );
}

function edu_closeDatePopup( e, obj ) {
	var pop = jQuery( obj.parentElement );
	pop.remove();

	e.cancelBubble = true;
	e.preventDefault();
}

var eduDetailView = {
	ShowAllEvents: function ( filter, me ) {
		me.parentNode.parentNode.removeChild( me.parentNode );
		jQuery( '.showMoreHidden[data-groupid="' + filter + '"]' ).slideDown();
	}
};

function numberWithSeparator( x, sep ) {
	return x.toString().replace( /\B(?=(\d{3})+(?!\d))/g, sep );
}

var oldonload = window.onload;
window.onload = function () {
	if ( oldonload ) {
		oldonload();
	}
};