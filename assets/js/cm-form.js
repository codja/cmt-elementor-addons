(function($) {
    var isAlreadyClicked = false;
	updateNonce();
    prepareForm();

	$(".cm-form-country").each(function() {
		CopyPhoneCountryCode( $(this) );
	});

    $("body").on("input", ".cm-form input:not([id='phonecountry'])", function() {
        var fields = [$(this).attr('name')];
        validateFields(fields, $(this).closest(".cm-form"));
    });

    $("body").on("change", ".cm-form select, .cm-form input[type='checkbox'], .cm-form input[type='date']", function() {
        var fields = [$(this).attr('name')];
        validateFields(fields, $(this).closest(".cm-form"));
    });

    $("body").on("click", ".cm-form-submit", function(evt) {
    	evt.preventDefault();
    	if ( window.countryIso === "US" ) {
		    return;
	    }
		const fields = [];
		const form = $(this).closest(".cm-form");
		const message = $(this).closest(".cm-form-message");
		const messageContainer = $(this).closest(".cm-form-submit-container");
		const inputs = form.find(".cm-form-input-container:not(.cm-form-submit-container) input, .cm-form-input-container:not(.cm-form-submit-container) select");
		const vlCidInput = form.find( "input[name='vl-cid']" );
		const vlCidValue = getCookie( 'vl-cid' );

		vlCidInput.val( vlCidValue );
		const referralInput = form.find("input[name='referral']");
		if ( ! referralInput.val() ) {
			const referralFromCookie = getCookie('referral_params');
			referralInput.val( referralFromCookie.substr(1).replaceAll('&', '|') );
		}

        for( let i = 0; i < inputs.length; i++ ) {
            fields.push(inputs[i].attributes.name.nodeValue);
        }

		const isValid = validateFields(fields, form);
		isAlreadyClicked = true;

        if (isValid) {
			document.dispatchEvent( new CustomEvent('frm-lp-submit', {
				detail: {
					form: evt.target.closest('.cm-form')
				}
			}) );

            if(form.hasClass("file-download")) {
                var fileLink = form.find("#file-download-link");
                if(fileLink.length > 0) fileLink[0].click();
            }

            $(this).addClass('cm-form-submit_loader');

            fields.forEach(function(field) {
                var fieldElement = form.find("[name=" + field + "]");
                value = fieldElement.val();
                if(field == "email") value = value.toLocaleLowerCase();
                fieldElement.val(value.trim());
            });

	        var data = form.serializeArray().reduce(function(obj, item) {
		        obj[item.name] = item.value;
		        return obj;
	        }, {});

	        data.cxd = getParamsFromUrl('cxd') || false;

			//var iframe = form.siblings('.cm-form-thank-you-page'); // temp
			form.next().show();

	        postData( '/wp-json/cmform/v1/' + form[0].dataset.route, data )
		        .then( data => {

		            if ( data.success ) {

						if( false && iframe.length > 0 ) { // temp

							setTimeout(function () {
								form.next().hide();
								$('.cm-form-thank-you-page').css('visibility', 'visible');
							}, 1000);

							setTimeout(function () {
								form[0].action.indexOf("mixpanel") < 0
									? location.href = data.link
									: handleMixPanelRequest( form );

								$(this)[0].disabled = true;
								$(this).removeClass('cm-form-submit_loader');
							}, 3000);

						} else {
							form[0].action.indexOf("mixpanel") < 0
								? location.href = data.link
								: handleMixPanelRequest( form );

							$(this)[0].disabled = true;
						}

		            } else {
						form.next().hide();
						const messageText = data.message ? data.message : data.data;
						! message.length
							? messageContainer.append('<p class="cm-form-message">' + messageText + '</p>')
							: message.text( messageText );

						$(this).removeClass('cm-form-submit_loader');
		            }
	            } )
		        .catch( ( error ) => {
	                console.error('Error:', error);
	            } );
        }
    })

    $("body").on("change", ".cm-form-country", function() {
        CopyPhoneCountryCode($(this));
    });

    function validateFields(fields, form) {
        var SendOK = true;

	    function isMinorUser( maxDateString, minDateString, value ) {
	    	var minDate = new Date( minDateString ).getTime();
	    	var maxDate = new Date( maxDateString ).getTime();
	    	var selectedDate = new Date( value ).getTime();
		    return selectedDate <= maxDate && selectedDate > minDate;
	    }

	    for (var i = 0; i < fields.length; i++) {
            var field = fields[i];
            form.find("#" + field + "-error").hide();
            form.find("#phone-digits-error").hide();
            form.find("[name=" + field + "]").removeClass("error").removeClass("valid");
            var value = form.find("[name=" + field + "]") ? form.find("[name=" + field + "]").val() : "";

            switch (field) {
                case 'firstname':
                    //if (value == "" || value.match(/^[a-zA-Z|\u0600-\u06FF][a-zA-Z|\u0600-\u06FF\s?]{2,40}$/) == null)  { //'' or null  \u0600-\u06FF
                    if (value == "" || value.match(/^([\w\u0621-\u064A\u0660-\u0669]+\s?)*\s*$/) == null || value.length > 40 || value.length < 3 || value.match(/\d/) !== null)  { //'' or null  \u0600-\u06FF
                        form.find("#firstname-error").show();
                        form.find("#firstname").addClass("error");
                        SendOK = false;
                    }
                    else {
                        form.find("#firstname").addClass("valid");
                    }
                    break;
                case 'lastname':
                    if (value == "" || value.match(/^([\w\u0621-\u064A\u0660-\u0669]+\s?)*\s*$/) == null || value.length > 40 || value.length < 3 || value.match(/\d/) !== null) {
                        form.find("#lastname-error").show();
                        form.find("#lastname").addClass("error");
                        SendOK = false;
                    }
                    else {
                        form.find("#lastname").addClass("valid");
                    }
                    break;
                case 'email':
                    value = value.toLocaleLowerCase();
                    //if (value == "" || value.match(/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,}(?:\.[a-z]{2})?)$/i) == null) {
                    if (value == "" || value.match(/^([a-z0-9\-\_]+(?:\.[a-z0-9\-\_]+)*)@((?:[a-z0-9\-\_]+\.)*[a-z0-9\-\_]{0,66})\.([a-z]{2,}(?:\.[a-z]{2})?)$/) == null) {
                        form.find("#email-error").show();
                        form.find("#email").addClass("error");
                        SendOK = false;
                    }
                    else {
                        form.find("#email").addClass("valid");
                    }
                    break;
                case 'phone':
                    if (value == "") {
                        SendOK = false;
                        form.find("#phone-error").show();
                        form.find("#phone").addClass("error");
                    }
                    else if(value.match(form.hasClass("arabic") ? '^[0-9]{6,12}$' : '^[0-9]{6,10}$') == null) {
                        SendOK = false;
                        form.find("#phone-digits-error").show();
                        form.find("#phone").addClass("error");
                    }
                    else form.find("#phone").addClass("valid");
                    break;
                case 'phonecountry':
                    if (value.match('^[0-9]{1,4}$') == null || value == "") {
                        SendOK = false;
                        form.find("#phonecountry-error").show();
                    }
                    if (value == '+') {
                        SendOK = false;
                        form.find("#countryiso2-error").show();
                        form.find("#countryiso2").addClass("error");
                    }
                    break;
                case 'countryiso2':
                    if(value == "" || !value) {
                        SendOK = false;
                        form.find("#countryiso2-error").show();
                        form.find("#phonecountry-error").show();
                        form.find("#countryiso2").addClass("error");
                    }
                    else form.find("#countryiso2").addClass("valid");
                    break;
	            case 'password':
					if (value == "" || value.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,12}$/) == null ) {
			            form.find("#password-error").show();
			            form.find("#password").addClass("error");
			            SendOK = false;
		            }
		            else {
			            form.find("#password").addClass("valid");
		            }
		            break;
	            case 'birthday':
	            	var maxValue = form.find( "[name=birthday]" ).attr( 'max' );
	            	var minValue = form.find( "[name=birthday]" ).attr( 'min' );
					if ( value !== "" && ! isMinorUser( maxValue, minValue, value ) ) {
			            SendOK = false;
			            form.find ("#birthday-error" ).show();
			            form.find( "[name=birthday]" ).addClass( "error" );
		            }
		            else form.find( "[name=birthday]" ).addClass( "valid" );
		            break;
				case 'promocode':
					if ( value.length > 0 && value.length > 32 ) {
						form.find("#promocode-error").show();
						form.find("#promocode").addClass("error");
						SendOK = false;
					}
					break;
                case 'agree':
                    if( form.find( "#agree" ).is( ":checked" ) === false ) {
                        SendOK = false;
                        form.find( "#agree-error" ).show();
                        form.find( "#agree" ).addClass( "error" );
                    }
                    break;

                default:
            }
        }

        return SendOK;
    }

    function CopyPhoneCountryCode(selectCountry) {
        var phoneCountryInput = selectCountry.closest("form")[0].querySelector(".cm-form-phone-container input[name='phonecountry']");
        selectCountry = selectCountry[0];
        if (phoneCountryInput) {
            phoneCountryInput.value = selectCountry.options[selectCountry.selectedIndex].dataset.telephonecode;
        }
    }

    function prepareForm() {
	    $(".cm-form input[name='landingPageUrl']").each(function() {
		    $(this).val( window.location.hostname + window.location.pathname );
	    });

        writeCookies();
    }

    function handleMixPanelRequest(form) {
        var data = {
            "token": "74d816453fe3cbb99afb2fe67927af30",
            "$distinct_id": generateUUID(),
            "$set": serializeFormData(form.serializeArray(), form[0].classList.contains("popup-form")),
        };

        httpGetData("https://api.mixpanel.com/engage/?data=" + encodeURI(JSON.stringify(data)), function(response) {
            if(response == 1) {
                var redirectInput = form.find("input[name='redirectToPage']");
                window.location.href = redirectInput.length > 0 && redirectInput.val() != "" ? (redirectInput.val() + "?firstname=" + data["$set"]["$first_name"]) : ("https://cm-ib.ibcommissions.com/register/?firstname=" + data["$set"]["$first_name"]) ;
            }
        });
    }

    function generateUUID() {
        var d = new Date().getTime();
        var d2 = (performance && performance.now && (performance.now()*1000)) || 0;
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            var r = Math.random() * 16;
            if(d > 0){
                r = (d + r)%16 | 0;
                d = Math.floor(d/16);
            } else {
                r = (d2 + r)%16 | 0;
                d2 = Math.floor(d2/16);
            }
            return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
        });
    }

    function serializeFormData(formDataObjectArray, is_popup) {
        var acceptedObjectKeysArray = ["firstname", "lastname", "email", "phone", "country"];
        var acceptedObjectValuesArray = ["$first_name", "$last_name", "$email", "$phone", "$country_code"];
        var formData = {}

        for(var i = 0; i < formDataObjectArray.length; i++) {
            var index = acceptedObjectKeysArray.indexOf(formDataObjectArray[i]["name"])
            if(index > -1) formData[acceptedObjectValuesArray[index]] = formDataObjectArray[i]["value"];
        }

        formData["$lp_link"] = window.location.href + (is_popup ? "?popup" : "");

        return formData;
    }

    function writeCookies() {
		const nUrlParams = new URLSearchParams(location.search);

		let gclid = "";

		const possibleClientIdParams = ['gclid', 'tblci', 'fbclid', 'vmcid'];
		for (let i = 0; i < possibleClientIdParams.length; i++) {
			const paramValue = nUrlParams.get(possibleClientIdParams[i]);
			if (paramValue !== null) {
				gclid = paramValue;
				// nUrlParams.delete(possibleClientIdParams[i]);
				break;
			}
		}
		nUrlParams.set('gclid', gclid);

		function setParam(utmName, paramName) {
			if (nUrlParams.has(utmName)) {
				nUrlParams.set(paramName, nUrlParams.get(utmName));
			}

		}

		function setCookies(utmName, cookiesFieldName, cookiesObject ) {
			if (nUrlParams.has(utmName)) {
				cookiesObject[cookiesFieldName] = nUrlParams.get(utmName);
			}
			return cookiesObject;
		}

		const cookiesObject = {};
		setCookies('utm_campaignid', 'A', cookiesObject);
		setCookies('utm_campaign', 'SubAffiliate', cookiesObject);
		setCookies('utm_campaignid', 'Vvar1', cookiesObject);
		setCookies('utm_content', 'Vvar2', cookiesObject);
		setCookies('utm_device','Vvar4', cookiesObject);
		setCookies('utm_term','Vvar6' , cookiesObject);
		setCookies('utm_placement','Vvar7', cookiesObject );
		setCookies('utm_adgroupid', 'Vvar8', cookiesObject);
		cookiesObject['gclid'] = gclid;

		document.cookie = 'MARKETING_CONTACT_TRACKING=' + JSON.stringify(cookiesObject);

		setParam('utm_campaignid', 'A');
		setParam('utm_campaign', 'subaffiliate');
		setParam('utm_campaignid', 'vvar1');
		setParam('utm_content', 'vvar2');
		setParam('utm_device','vvar4');
		setParam('utm_term','vvar6' );
		setParam('utm_placement','vvar7' );
		setParam('utm_adgroupid', 'vvar8');

    }

    function getParamsFromUrl( param ) {
	    var url = new URL(location.href);
	    var params = new URLSearchParams(url.search);

	    return params.get( param );
	}

	function getCookie(name) {
		let matches = document.cookie.match(new RegExp(
			"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
		));
		return matches ? decodeURIComponent(matches[1]) : '';
	}

    function httpGetData(url, callback) {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function() {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
                callback(xmlHttp.responseText);
        }
        xmlHttp.open("GET", url, true); // true for asynchronous
        xmlHttp.send(null);
    }

	async function postData( url = '', data = {} ) {
		const response = await fetch( url, {
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json',
				'X-WP-Nonce': cmform._nonce,
			},
			cache: 'no-store',
			credentials: 'include',
			method: 'POST',
			body: JSON.stringify( data )
		} );
		return response.json();
	}

	// Popup form
	if ( document.querySelector( '.popup-form' ) ) {

		const html = document.querySelector( 'html' ),
			btnsCallModals = document.querySelectorAll( '.js-cmtrading-popup' ),
			modal = document.querySelector( '.cm-form-container_popup' ),
			closeModal = modal.querySelector( '.js-cmtrading-close-popup' );

		const showModal = ( evt ) => {
			evt.preventDefault();
			html.style.overflow = 'hidden';
			modal.classList.add( 'cm-popup-active' );
		};

		const hideModal = () => {
			html.style.overflow = '';
			modal.classList.remove( 'cm-popup-active' );
		};

		btnsCallModals.forEach( ( elem ) => {
			elem.addEventListener( 'click', showModal );
		} );

		closeModal.addEventListener( 'click', hideModal );

	}

	class Hideable {

		constructor( element ) {
			this.elem = element;
		}

		show() {
			this.elem.classList.remove( 'cm-form-hidden' );
		}

		hide() {
			this.elem.classList.add( 'cm-form-hidden' );
		}

	}

	class EmailSuggestions {

		constructor( elem ) {
			this.form = elem;
			this.email = this.form.querySelector( '.cm-form-email-container' );

			if ( ! this.email ) {
				return;
			}

			this.emailInput = this.email.querySelector( 'input[type=email]' );
			this.suggestions = this.email.querySelector( '.cm-form-suggestions' );

			if ( ! this.emailInput || ! this.suggestions ) {
				return;
			}

			this.tooltipSuggestions = new Hideable( this.suggestions )

			this.bindEvents();
		}

		bindEvents() {
			this.tabHandler();
			this.suggestionsHandler();
			this.emailInputHandler();
		}

		tabHandler() {
			this.form.addEventListener( 'keyup', ( evt ) => {
				if ( evt.keyCode === 9 ) {
					const activeElement = document.activeElement;
					if ( ! activeElement.parentElement.parentElement.classList.contains( 'cm-form-email' ) ) {
						this.tooltipSuggestions.hide();
					}
				}
			} );
		}

		suggestionsHandler() {
			[...this.suggestions.children].forEach( ( elem ) => {
				elem.addEventListener( 'click', ( evt ) => {
					this.emailInput.value = elem.textContent;
					$( '.cm-form-email' ).trigger( 'input' );
					this.emailInput.dispatchEvent( new Event( 'input' ) );
					this.tooltipSuggestions.hide();
				} );
			} );

			document.addEventListener('click', ( evt ) => {
				const outsideClick = ! this.emailInput.contains( evt.target ) && ! this.suggestions.contains( evt.target );
				if ( outsideClick ) {
					this.tooltipSuggestions.hide();
				}
			} );
		}

		emailInputHandler() {
			this.emailInput.addEventListener( 'focus', () => {
				if ( this.emailInput.value.length > 0 ) {
					this.tooltipSuggestions.show();
				}
			} );

			this.emailInput.addEventListener( 'input', ( evt ) => {
				if ( this.emailInput.value.length === 0 ) {
					this.tooltipSuggestions.hide();
					return;
				} else {
					this.tooltipSuggestions.show();
				}

				[...this.suggestions.children].forEach( ( elem ) => {
					const placeholder = elem.querySelector( '.cm-form-suggestions__placeholder' );
					const value = evt.target.value;
					! value.includes( '@' )
						? placeholder.textContent = value
						: this.handlerEmailDomain( elem, placeholder, value )
				} );
			} );
		}

		handlerEmailDomain( elem, placeholder, value ) {
			if ( ! elem || ! placeholder || ! value ) {
				return;
			}

			const splitString = value.split( '@' );
			const firstPart = splitString[0];
			const emailDomain = '@' + splitString[ splitString.length - 1 ];

			placeholder.textContent = firstPart;

			! elem.textContent.includes( emailDomain )
				? elem.classList.add( 'cm-form-hidden' )
				: elem.classList.remove( 'cm-form-hidden' );
		}
	}

	const signupForms = document.querySelectorAll( '.cm-form' );
	signupForms.forEach( form => {
		new EmailSuggestions( form );
	} );

	async function getRefreshedNonce() {
		try {
			let response = await fetch( cmform.url, {
				method: 'POST',
				headers: new Headers( {
					'Content-Type': 'application/x-www-form-urlencoded',
				} ),
				body: new URLSearchParams({
					action: 'cm_get_refreshed_nonce',
				} ).toString(),
				credentials: 'same-origin',
			} );

			response = await response.json();

			return response?.data?._nonce ?? '';
		} catch( e ) {
			return '';
		}
	}

	async function updateNonce() {
		const updatedNonce = await getRefreshedNonce();
		if ( updatedNonce ) {
			cmform._nonce = updatedNonce;
		}

	}
})(jQuery);