/**
 * @module
 * @description JavaScript specific to the site navigation
 */

import _ from 'lodash';
import delegate from 'delegate';
import state from '../config/state';
import { on, trigger } from '../utils/events';
import * as tools from '../utils/tools';
import { navState } from './index';
import * as bodyLocker from '../utils/dom/body-lock';

const ANIMATION_DELAY = 300;

const el = {
	container: tools.getNodes('site-header')[0],
};

/**
 * @function setupAriaAttributes
 * @description Setup the ARIA attributes for mobile navigation.
 */

const setupAriaAttributes = () => {
	if (document.body.classList.contains('site-navigation--is-open')) {
		return;
	}

	if (el.trigger !== null) {
		el.trigger.setAttribute('aria-expanded', false);
		el.trigger.setAttribute('aria-haspopup', true);
		el.trigger.setAttribute('aria-controls', el.navigation.getAttribute('id'));
	}

	if (el.navigation !== null) {
		el.navigation.setAttribute('aria-hidden', true);
		el.navigation.setAttribute('aria-labelledby', el.trigger.getAttribute('id'));
	}
};

/**
 * @function removeAriaAttributes
 * @description Remove the ARIA attributes for mobile navigation.
 */

const removeAriaAttributes = () => {

	// Trigger
	if (el.trigger !== null) {
		el.trigger.removeAttribute('aria-expanded');
		el.trigger.removeAttribute('aria-haspopup');
		el.trigger.removeAttribute('aria-controls');
	}

	// Navigation
	if (el.navigation !== null) {
		el.navigation.removeAttribute('aria-hidden');
		el.navigation.removeAttribute('aria-labelledby');
	}
};

/**
 * @function setupAriaOpenAttributes
 * @description Setup mobile navigation open ARIA attributes.
 */

const setupAriaOpenAttributes = () => {
	// Trigger
	if (el.trigger !== null) {
		el.trigger.setAttribute('aria-expanded', true);
	}

	// Navigation
	if (el.navigation !== null) {
		el.navigation.setAttribute('aria-hidden', false);
	}
};

/**
 * @function setupAriaClosedAttributes
 * @description Setup mobile navigation closed ARIA attributes.
 */

const setupAriaClosedAttributes = () => {
	// Trigger
	if (el.trigger !== null) {
		el.trigger.setAttribute('aria-expanded', false);
	}

	// Navigation
	if (el.navigation !== null) {
		el.navigation.setAttribute('aria-hidden', true);
	}
};

/**
 * @function open
 * @description Opens the mobile navigation.
 */

const open = () => {
	trigger({ event: 'modern_tribe/mobile_nav_opened', native: false });
	setupAriaOpenAttributes();
	document.body.classList.add('site-navigation--is-open');
	document.body.classList.add('site-navigation--is-active');
	bodyLocker.lock();
	navState.mobileOpen = true;
};

/**
 * @function close
 * @description Closes the mobile navigation.
 */

const close = () => {
	navState.mobileOpen = false;
	setupAriaClosedAttributes();
	bodyLocker.unlock();
	document.body.classList.remove('site-navigation--is-open');
	_.delay(() => {
		document.body.classList.remove('site-navigation--is-active');
		trigger({ event: 'modern_tribe/mobile_nav_closed', native: false });
	}, ANIMATION_DELAY);
};

/**
 * @function toggleMenu
 * @description Toggles the mobile menu open and closed
 */

const toggleMenu = () => {
	if (navState.mobileOpen) {
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
		if (document.body.classList.contains('site-navigation--is-active') && !document.body.classList.contains('nav-primary__list-child--depth-0--active')) {
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
	if (document.body.classList.contains('site-navigation--is-active')) {
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
		if (document.body.classList.contains('site-navigation--is-active') && !e.currentTarget.classList.contains('nav-primary__list-child--depth-0--active')) {
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
	if (state.is_desktop) {
		if (document.body.classList.contains('site-navigation--is-open')) {
			close();
		}

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
	el.navigation = tools.getNodes('site-navigation')[0];
	el.trigger = tools.getNodes('site-navigation-trigger')[0];
	el.anchors = el.navigation.querySelectorAll('a');
	el.anchors.parent = el.navigation.querySelectorAll('.nav-primary__action--has-children');
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	document.body.addEventListener('keydown', closeOnEsc);

	on(document, 'modern_tribe/resize_executed', handleResize);

	delegate(el.container, '[data-js="site-navigation-trigger"]', 'click', toggleMenu);
	el.trigger.addEventListener('keydown', preventOutsideFocus);

	el.anchors.item(el.anchors.length - 1).addEventListener('keydown', focusNavigationTrigger);
	//el.anchors.parent.item(el.anchors.parent.length - 1).addEventListener('keydown', focusNavigationTrigger);
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

	console.info('Initialized site navigation scripts.');
};

export default navigation;
