/**
 * @module
 * @description Module handles WooCommerce UI.
 */

const el = {
	container: document.getElementsByClassName('woocommerce')[0],
};

/**
 * @function customizeCheckboxLoginRemember
 * @description Customize login remember checkbox markup.
 */

const customizeCheckboxLoginRemember = () => {
	if (!$('.woocommerce-checkout').length) {
		return;
	}

	const $checkbox = $('#rememberme');
	if (!$checkbox.length) {
		return;
	}

	const label = $checkbox.parent();

	label.attr('for', 'rememberme');
	label.wrapAll('<span class="form-control-checkbox form-control-custom-style"></span>');
	$checkbox.insertBefore(label);
};

/**
 * @function customizeCheckboxMailchimp
 * @description Customize MailChimp checkbox markup.
 */

const customizeCheckboxMailchimp = () => {
	if (!$('.woocommerce-checkout').length) {
		return;
	}

	const $checkbox = $('#ss_wc_mailchimp_opt_in');
	if (!$checkbox.length) {
		return;
	}

	const label = $checkbox.parent();

	label.wrapAll('<span class="form-control-checkbox form-control-custom-style"></span>');
	$checkbox.insertBefore(label);
};

/**
 * @function updatedCheckout
 * @description Handle updated checkout event.
 */

const updatedCheckout = () => {
	customizeCheckboxMailchimp();
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	$(document.body).on('updated_checkout', updatedCheckout);
};

/**
 * @function refineUI
 * @description Kick off this modules functions
 */

const refineUI = () => {
	if (!el.container) {
		return;
	}

	bindEvents();

	customizeCheckboxLoginRemember();
	customizeCheckboxMailchimp();

	console.info('Initialized WC UI script.');
};

export default refineUI;
