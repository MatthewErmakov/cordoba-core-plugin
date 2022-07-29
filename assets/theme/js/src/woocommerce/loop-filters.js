/**
 * @module
 * @description JavaScript specific to the site navigation
 */

import _ from 'lodash';
import delegate from 'delegate';
import state from '../config/state';
import scrollTo from '../utils/dom/scroll-to';
import { on } from '../utils/events';
import * as tools from '../utils/tools';
import * as bodyLocker from '../utils/dom/body-lock';
import * as tests from '../utils/tests';

const browser = tests.browserTests();

const el = {
	container: tools.getNodes('shop-filters-bar')[0],
};

let navActive = false;

/**
 * @function setupAriaAttributes
 * @description Setup the ARIA attributes for mobile navigation.
 */

const setupAriaAttributes = () => {
	if (document.body.classList.contains('shop-filters--is-open')) {
		return;
	}

	// Trigger
	el.trigger.setAttribute('aria-expanded', false);
	el.trigger.setAttribute('aria-haspopup', true);
	el.trigger.setAttribute('aria-controls', el.navigation.getAttribute('id'));

	// Navigation
	el.navigation.setAttribute('aria-hidden', true);
	el.navigation.setAttribute('aria-labelledby', el.trigger.getAttribute('id'));
};

/**
 * @function removeAriaAttributes
 * @description Remove the ARIA attributes for mobile navigation.
 */

const removeAriaAttributes = () => {
	// Trigger
	el.trigger.removeAttribute('aria-expanded');
	el.trigger.removeAttribute('aria-haspopup');
	el.trigger.removeAttribute('aria-controls');

	// Navigation
	el.navigation.removeAttribute('aria-hidden');
	el.navigation.removeAttribute('aria-labelledby');
};

/**
 * @function setupAriaOpenAttributes
 * @description Setup mobile navigation open ARIA attributes.
 */

const setupAriaOpenAttributes = () => {
	// Trigger
	el.trigger.setAttribute('aria-expanded', true);

	// Navigation
	el.navigation.setAttribute('aria-hidden', false);
};

/**
 * @function setupAriaClosedAttributes
 * @description Setup mobile navigation closed ARIA attributes.
 */

const setupAriaClosedAttributes = () => {
	// Trigger
	el.trigger.setAttribute('aria-expanded', false);

	// Navigation
	el.navigation.setAttribute('aria-hidden', true);
};

/**
 * @function openiOSHack
 * @description Open iOS hack for scrollto + bodylock bug.
 */

const openiOSHack = () => {
	_.delay(() => {
		document.body.classList.add('shop-filters--is-open--ios');

		_.delay(() => {
			document.body.classList.add('shop-filters--is-active--ios');
		}, 25);
	}, 25);
};

/**
 * @function open
 * @description Opens the mobile navigation.
 */

const open = () => {
	setupAriaOpenAttributes();

	scrollTo({
		duration: 200,
		offset: -state.header_offset,
		$target: $(el.trigger),
	});

	_.delay(() => {
		document.body.classList.add('shop-filters--is-open');
		document.body.classList.add('shop-filters--is-active');
		bodyLocker.lock();

		if (browser.ios) {
			openiOSHack();
		}
	}, 250);

	navActive = true;
};

/**
 * @function closeiOSHack
 * @description Close iOS hack for scrollto + bodylock bug.
 */

const closeiOSHack = () => {
	document.body.classList.remove('shop-filters--is-open--ios');
	document.body.classList.remove('shop-filters--is-active--ios');
};

/**
 * @function close
 * @description Closes the mobile navigation.
 */

const close = () => {
	navActive = false;
	setupAriaClosedAttributes();
	bodyLocker.unlock();
	document.body.classList.remove('shop-filters--is-open');

	if (browser.ios) {
		closeiOSHack();
	}

	_.delay(() => {
		document.body.classList.remove('shop-filters--is-active');
	}, 300);
};

/**
 * @function closeConditionally
 * @description Closes the mobile navigation conditionally.
 */

const closeConditionally = () => {
	if (document.body.classList.contains('shop-filters--is-open')) {
		close();
	}
};

/**
 * @function toggleMenu
 * @description Toggles the mobile menu open and closed
 */

const toggleMenu = () => {
	if (navActive) {
		close();
	} else {
		open();
	}
};

/**
 * @function closeOnEsc
 * @description Close the mobile navigation when pressing esc
 */

const closeOnEsc = (e) => {
	if (e.which === 27) {
		if (document.body.classList.contains('shop-filters--is-active')) {
			close();
			el.trigger.focus();
		}
	}
};

/**
 * @function preventOutsideFocus
 * @description Prevents focus outside of open mobile navigation
 */

const preventOutsideFocus = (e) => {
	if (document.body.classList.contains('shop-filters--is-active')) {
		if (e.shiftKey && e.which === 9) {
			e.preventDefault();
		}
	}
};

/**
 * @function focusNavigationTrigger
 * @description Add focus back on mobile navigation trigger
 */

const focusNavigationTrigger = (e) => {
	if (e.which === 9 && !e.shiftKey) {
		if (document.body.classList.contains('shop-filters--is-active')) {
			e.preventDefault();
			el.trigger.focus();
		}
	}
};

/**
 * @function handleResize
 * @description handles resize event.
 */

const handleResize = () => {
	closeConditionally();

	if (state.is_desktop) {
		removeAriaAttributes();
	} else {
		setupAriaAttributes();
	}
};

/**
 * @function cacheElements
 * @description Caches dom nodes this module uses.
 */

const cacheElements = () => {
	el.navigation = tools.getNodes('shop-filters')[0];
	el.trigger = tools.getNodes('shop-filters-trigger')[0];
	el.anchors = el.container.querySelectorAll('a');
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	document.body.addEventListener('keydown', closeOnEsc);

	on(document, 'modern_tribe/resize_executed', handleResize);

	delegate(el.container, '[data-js="shop-filters-trigger"]', 'click', toggleMenu);
	el.trigger.addEventListener('keydown', preventOutsideFocus);

	on(document, 'modern_tribe/mobile_nav_opened', closeConditionally);

	el.anchors.item(el.anchors.length - 1).addEventListener('keydown', focusNavigationTrigger);
};

/**
 * @function init
 * @description Kick off this modules functions
 */

const navigation = () => {
	if (!el.container) {
		return;
	}

	cacheElements();

	bindEvents();

	setupAriaAttributes();

	console.info('Initialized WC loop filter scripts.');
};

export default navigation;
