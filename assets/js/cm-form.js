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

class FormHandlerClass extends elementorModules.frontend.handlers.Base {

	constructor( ...$props ) {
		super( ...$props );
	}

	getDefaultSettings() {
		return {
			selectors: {
				form: '.cm-form',
				formCountry: '.cm-form-country',
				formSubmit: '.cm-form-submit',
				formMessage: '.cm-form-message',
				formSubmitContainer: '.cm-form-submit-container',
				inputField: 'input',
				selectField: 'select',
				phoneCountry: '.cm-form-phone-prefix',
				checkboxField: 'input[type="checkbox"]',
				dateField: 'input[type="date"]',
				vlCidInput: "input[name='vl-cid']",
				referralInput: "input[name='referral']",
				thankYouPage: '.cm-form-thank-you-page',
				fileDownloadLink: '#file-download-link',
				formInputContainer: '.cm-form-input-container',
				loaderClass: 'cm-form-submit_loader'
			},
		};
	}

	getDefaultElements() {
		const selectors = this.getSettings('selectors');
		return {
			$form: this.$element.find(selectors.form),
			$formCountry: this.$element.find(selectors.formCountry),
			$phoneCountry: this.$element.find(selectors.phoneCountry),
			$formSubmit: this.$element.find(selectors.formSubmit),
			$formMessage: this.$element.find(selectors.formMessage),
			$formSubmitContainer: this.$element.find(selectors.formSubmitContainer),
			$inputField: this.$element.find(selectors.inputField),
			$selectField: this.$element.find(selectors.selectField),
			$checkboxField: this.$element.find(selectors.checkboxField),
			$dateField: this.$element.find(selectors.dateField),
			$vlCidInput: this.$element.find(selectors.vlCidInput),
			$referralInput: this.$element.find(selectors.referralInput),
			$thankYouPage: this.$element.find(selectors.thankYouPage),
			$fileDownloadLink: this.$element.find(selectors.fileDownloadLink),
			$formInputContainer: this.$element.find(selectors.formInputContainer)
		};
	}

	bindEvents() {
		new EmailSuggestions( this.elements.$form[0] );
		this.elements.$formCountry.on('change', this.onFormCountryChange.bind(this));
		this.elements.$inputField.on('input', this.onInputChange.bind(this));
		this.elements.$selectField.on('change', this.onInputChange.bind(this));
		this.elements.$checkboxField.on('change', this.onInputChange.bind(this));
		this.elements.$dateField.on('change', this.onInputChange.bind(this));
		this.elements.$formSubmit.on('click', this.onSubmitClick.bind(this));
	}

	onFormCountryChange(event) {
		this.copyPhoneCountryCode(event.target);
	}

	onInputChange(event) {
		const fields = [event.target.name];
		this.validateFields(fields, event.target.closest(this.getSettings('selectors').form));
	}

	onSubmitClick(event) {
		event.preventDefault();
		const selectors = this.getSettings('selectors');
		const form = this.elements.$form[0];
		const inputs = form.querySelectorAll(`${selectors.formInputContainer}:not(${selectors.formSubmitContainer}) input, ${selectors.formInputContainer}:not(${selectors.formSubmitContainer}) select`);
		const vlCidInput = this.elements.$vlCidInput[0];
		const vlCidValue = this.getCookie('vl-cid');
		const referralInput = this.elements.$referralInput[0];

		vlCidInput.value = vlCidValue;
		if (!referralInput.value) {
			const referralFromCookie = this.getCookie('referral_params');
			referralInput.value = referralFromCookie.substr(1).replaceAll('&', '|');
		}

		const fields = Array.from(inputs).map(input => input.name);
		const isValid = this.validateFields(fields, form);
		if (isValid) {
			document.dispatchEvent(new CustomEvent('frm-lp-submit', {detail: {form: form}}));

			if (form.classList.contains("file-download")) {
				const fileLink = this.elements.$fileDownloadLink.get(0);
				if (fileLink) fileLink.click();
			}

			event.target.classList.add(selectors.loaderClass);

			fields.forEach((field) => {
				const fieldElement = form.querySelector(`[name=${field}]`);
				let value = fieldElement.value;
				if (field==="email") value = value.toLowerCase();
				fieldElement.value = value.trim();
			});

			const data = Array.from(new FormData(form)).reduce((obj, [key, value]) => {
				obj[key] = value;
				return obj;
			}, {});

			data.cxd = this.getParamsFromUrl('cxd') || false;

			form.nextElementSibling.style.display = 'block';

			this.postData(`/wp-json/cmform/v1/${form.dataset.route}`, data)
				.then(data => this.handleResponse(data, form, event.target))
				.catch(error => console.error('Error:', error));
		}
	}

	handleResponse(data, form, submitButton) {
		if (data.success) {
			location.href = data.link
			submitButton.disabled = true;
		} else {
			form.nextElementSibling.style.display = 'none';
			const messageText = data.message || data.data;
			const message = this.elements.$formMessage.get(0);

			if (!message) {
				this.elements.$formSubmitContainer.append(`<p class="cm-form-message">${messageText}</p>`);
			} else {
				message.textContent = messageText;
			}

			submitButton.classList.remove(this.getSettings('selectors').loaderClass);
		}
	}

	validateFields(fields, form) {
		let SendOK = true;

		fields.forEach((field) => {
			const fieldElement = form.querySelector(`[name=${field}]`);
			if (!fieldElement) return;

			fieldElement.classList.remove("error", "valid");
			let value = fieldElement.value;

			const errorBlock = form.querySelector(`#${field}-error`);
			if (errorBlock) {
				errorBlock.style.display = 'none';
			}
			form.querySelector("#phone-digits-error").style.display = 'none';

			switch (field) {
				case 'firstname':
				case 'lastname':
					if (!/^[\w\u0621-\u064A\u0660-\u0669]+(\s?[\w\u0621-\u064A\u0660-\u0669]+)*$/.test(value) || value.length < 3 || value.length > 40 || /\d/.test(value)) {
						form.querySelector(`#${field}-error`).style.display = 'block';
						fieldElement.classList.add("error");
						SendOK = false;
					} else {
						fieldElement.classList.add("valid");
					}
					break;
				case 'email':
					if (!/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/.test(value.toLowerCase())) {
						form.querySelector("#email-error").style.display = 'block';
						fieldElement.classList.add("error");
						SendOK = false;
					} else {
						fieldElement.classList.add("valid");
					}
					break;
				case 'phone':
					if (!/^\d{6,12}$/.test(value)) {
						form.querySelector("#phone-digits-error").style.display = 'block';
						fieldElement.classList.add("error");
						SendOK = false;
					} else {
						fieldElement.classList.add("valid");
					}
					break;
				case 'countryiso2':
					if (value==="") {
						form.querySelector("#countryiso2-error").style.display = 'block';
						fieldElement.classList.add("error");
						SendOK = false;
					} else {
						fieldElement.classList.add("valid");
					}
					break;
				case 'password':
					if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,12}$/.test(value)) {
						form.querySelector("#password-error").style.display = 'block';
						fieldElement.classList.add("error");
						SendOK = false;
					} else {
						fieldElement.classList.add("valid");
					}
					break;
				case 'birthday':
					const maxValue = form.querySelector("[name=birthday]").getAttribute('max');
					const minValue = form.querySelector("[name=birthday]").getAttribute('min');
					if (value!=="" && !this.isMinorUser(maxValue, minValue, value)) {
						form.querySelector("#birthday-error").style.display = 'block';
						fieldElement.classList.add("error");
						SendOK = false;
					} else {
						fieldElement.classList.add("valid");
					}
					break;
				case 'promocode':
					if (value.length > 0 && value.length > 32) {
						form.querySelector("#promocode-error").style.display = 'block';
						fieldElement.classList.add("error");
						SendOK = false;
					}
					break;
				default:
					break;
			}
		});

		return SendOK;
	}

	copyPhoneCountryCode(element) {
		const phoneCountry = this.elements.$phoneCountry[0];
		if (element && phoneCountry) {
			phoneCountry.value = element.options[element.selectedIndex].dataset.telephonecode;
		}
	}

	isMinorUser(maxDateString, minDateString, value) {
		const minDate = new Date(minDateString).getTime();
		const maxDate = new Date(maxDateString).getTime();
		const selectedDate = new Date(value).getTime();

		return selectedDate <= maxDate && selectedDate > minDate;
	}

	getCookie(name) {
		let matches = document.cookie.match(new RegExp(
			"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
		));

		return matches ? decodeURIComponent(matches[1]) : '';
	}

	getParamsFromUrl(param) {
		// Create a URL object from the current location
		const url = new URL(window.location.href);

		// Create a URLSearchParams object from the URL's search parameters
		const params = new URLSearchParams(url.search);

		// Retrieve and return the value of the specified parameter
		return params.get(param);
	}
}

jQuery( window ).on( 'elementor/frontend/init', () => {
	const addHandler = ( $element ) => {
		elementorFrontend.elementsHandler.addHandler( FormHandlerClass, {
			$element,
		} );
	};

	elementorFrontend.hooks.addAction(
		'frontend/element_ready/cm-form.default',
		addHandler
	);
} );