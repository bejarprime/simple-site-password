( function () {
	'use strict';

	function ready( callback ) {
		if ( document.readyState !== 'loading' ) {
			callback();
			return;
		}

		document.addEventListener( 'DOMContentLoaded', callback );
	}

	function setText( element, value, fallback ) {
		if ( ! element ) {
			return;
		}

		element.textContent = value.trim() || fallback;
	}

	ready( function () {
		var i18n = window.simpleSitePasswordAdmin || {};
		var changePasswordButton = document.querySelector( '[data-ssp-change-password]' );
		var newPasswordWrap = document.querySelector( '[data-ssp-new-password-wrap]' );
		var currentPassword = document.getElementById( 'ssp-current-password' );
		var togglePasswordButton = document.querySelector( '[data-ssp-toggle-password]' );
		var passwordInput = document.getElementById( 'ssp-password' );
		var strengthWrap = document.querySelector( '[data-ssp-password-strength]' );
		var strengthBar = document.querySelector( '[data-ssp-password-strength-bar]' );
		var strengthLabel = document.querySelector( '[data-ssp-password-strength-label]' );

		if ( changePasswordButton && newPasswordWrap ) {
			changePasswordButton.addEventListener( 'click', function () {
				newPasswordWrap.classList.remove( 'ssp-is-hidden' );

				if ( currentPassword ) {
					currentPassword.classList.add( 'ssp-is-hidden' );
				}

				if ( passwordInput ) {
					passwordInput.focus();
				}
			} );
		}

		if ( togglePasswordButton && passwordInput ) {
			togglePasswordButton.addEventListener( 'click', function () {
				var isPassword = passwordInput.getAttribute( 'type' ) === 'password';

				passwordInput.setAttribute( 'type', isPassword ? 'text' : 'password' );
				togglePasswordButton.textContent = isPassword ? ( i18n.hide || 'Hide' ) : ( i18n.show || 'Show' );
			} );
		}

		function getPasswordStrength( password ) {
			var score = 0;

			if ( ! password ) {
				return 'empty';
			}

			if ( password.length < 8 ) {
				return 'weak';
			}

			if ( password.length >= 8 ) {
				score++;
			}

			if ( password.length >= 12 ) {
				score++;
			}

			if ( /[a-z]/.test( password ) ) {
				score++;
			}

			if ( /[A-Z]/.test( password ) ) {
				score++;
			}

			if ( /[0-9]/.test( password ) ) {
				score++;
			}

			if ( /[^a-zA-Z0-9]/.test( password ) ) {
				score++;
			}

			if ( score >= 5 ) {
				return 'strong';
			}

			if ( score >= 3 ) {
				return 'medium';
			}

			return 'weak';
		}

		function updatePasswordStrength() {
			if ( ! passwordInput || ! strengthWrap || ! strengthBar || ! strengthLabel ) {
				return;
			}

			var strength = getPasswordStrength( passwordInput.value );
			var labels = {
				empty: i18n.passwordHelp || 'Use at least 8 characters with a mix of letters, numbers, and symbols.',
				weak: i18n.passwordWeak || 'Weak password',
				medium: i18n.passwordMedium || 'Medium password',
				strong: i18n.passwordStrong || 'Strong password'
			};

			strengthWrap.classList.remove( 'ssp-strength-empty', 'ssp-strength-weak', 'ssp-strength-medium', 'ssp-strength-strong' );
			strengthWrap.classList.add( 'ssp-strength-' + strength );
			strengthLabel.textContent = labels[ strength ];
			strengthBar.setAttribute( 'aria-hidden', 'true' );
		}

		if ( passwordInput ) {
			passwordInput.addEventListener( 'input', updatePasswordStrength );
			updatePasswordStrength();
		}

		var templateSelect = document.getElementById( 'ssp-template' );
		var titleInput = document.getElementById( 'ssp-title' );
		var descriptionInput = document.getElementById( 'ssp-description' );
		var buttonInput = document.getElementById( 'ssp-button-text' );
		var preview = document.querySelector( '[data-ssp-preview]' );
		var previewTitle = document.querySelector( '[data-ssp-preview-title]' );
		var previewDescription = document.querySelector( '[data-ssp-preview-description]' );
		var previewButton = document.querySelector( '[data-ssp-preview-button]' );

		function updateTemplatePreview() {
			if ( ! preview || ! templateSelect ) {
				return;
			}

			preview.classList.remove( 'ssp-preview-minimal', 'ssp-preview-dark', 'ssp-preview-gradient' );
			preview.classList.add( 'ssp-preview-' + templateSelect.value );
		}

		function updateTextPreview() {
			setText( previewTitle, titleInput ? titleInput.value : '', i18n.defaultTitle || 'Protected Site' );
			setText( previewDescription, descriptionInput ? descriptionInput.value : '', i18n.defaultDescription || 'Enter the password to access this site.' );
			setText( previewButton, buttonInput ? buttonInput.value : '', i18n.defaultButton || 'Access' );
		}

		if ( templateSelect ) {
			templateSelect.addEventListener( 'change', updateTemplatePreview );
			updateTemplatePreview();
		}

		[ titleInput, descriptionInput, buttonInput ].forEach( function ( input ) {
			if ( input ) {
				input.addEventListener( 'input', updateTextPreview );
			}
		} );

		updateTextPreview();
	} );
}() );
