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
