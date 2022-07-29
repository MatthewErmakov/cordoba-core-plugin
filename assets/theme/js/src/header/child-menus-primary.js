/**
 * @module
 * @description JavaScript specific to the site header primary child menus
 */

import delegate from 'delegate';
import * as tools from '../utils/tools';
import { on } from '../utils/events';
import state from '../config/state';
import { navState } from './index';

const el = {
	container: tools.getNodes('site-navigation')[0],
};

/**
 * @function setupAriaAttributes
 * @description Setup the ARIA attributes for child menus.
 */

const setupAriaAttributes = () => {
	el.childMenuTriggers.forEach((item) => {
		const itemChildMenu = item.nextElementSibling;

		if (itemChildMenu === null) {
			return;
		}

		const childMenuId = itemChildMenu.getAttribute('id');

		// Parent Menu Items
		item.setAttribute('aria-expanded', false);
		item.setAttribute('aria-haspopup', true);
		item.setAttribute('aria-controls', childMenuId);
		item.setAttribute('aria-owns', childMenuId);

		// Child Menus
		itemChildMenu.setAttribute('aria-hidden', true);
		itemChildMenu.setAttribute('role', 'group');
		itemChildMenu.setAttribute('aria-labelledby', item.getAttribute('id'));
	});
};

/**
 * @function setupAriaOpenAttributes
 * @description Setup navigation child menu open ARIA attributes.
 */

const setupAriaOpenAttributes = (target) => {
	const itemChildMenu = target.nextElementSibling;

	// Parent Menu Items
	target.setAttribute('aria-expanded', true);

	// Child Menus
	itemChildMenu.setAttribute('aria-hidden', false);
};

/**
 * @function setupAriaClosedAttributes
 * @description Setup navigation child menu closed ARIA attributes.
 */

const setupAriaClosedAttributes = () => {
	el.childMenuTriggers.forEach((item) => {
		const itemChildMenu = item.nextElementSibling;

		// Parent Menu Items
		item.setAttribute('aria-expanded', false);

		if (itemChildMenu === null) {
			return;
		}

		// Child Menus
		itemChildMenu.setAttribute('aria-hidden', true);
	});
};

/**
 * @function close
 * @description Closes any child menus open except for passed target if set
 */

const closeMenu = (current = null) => {
	if (current) {
		current.classList.add('current-item');
	}

	tools
		.getNodes('[data-js="trigger-child-menu"]:not(.current-item)', true, el.container, true)
		.forEach(toggle => toggle.parentNode.classList.remove('nav-primary__list-item--child-active'));

	if (current) {
		current.classList.remove('current-item');
	}
};

/**
 * @function close
 * @description Closes all child menus open
 */

const closeAll = () => {
	setupAriaClosedAttributes();
	closeMenu();
	document.body.classList.remove('nav-primary__list-child--depth-0--active');
	navState.subPrimaryActive = false;
};

/**
 * @function closeAllIfOpen
 * @description Closes all child menus if open
 */

const closeAllIfOpen = () => {
	if (document.body.classList.contains('nav-primary__list-child--depth-0--active')) {
		closeAll();
	}
};

/**
 * @function maybeClose
 * @description Triggered by document clicks and closes if not in target area
 */

const maybeClose = (e) => {
	if (!navState.subPrimaryActive) {
		return;
	}

	if (!tools.closest(e.target, '.site-header')) {
		closeAll();
	}
};

/**
 * @function toggle
 * @description Handles toggling of the primary navigation child menus.
 */

const toggle = (e) => {
	const group = e.delegateTarget.parentNode;

	e.preventDefault();

	group.classList.toggle('nav-primary__list-item--child-active');

	setupAriaClosedAttributes();

	if (group.classList.contains('nav-primary__list-item--child-active')) {
		setupAriaOpenAttributes(e.delegateTarget);
		document.body.classList.add('nav-primary__list-child--depth-0--active');
		closeMenu(e.delegateTarget);

		navState.subPrimaryActive = true;
	} else {
		document.body.classList.remove('nav-primary__list-child--depth-0--active');
		navState.subPrimaryActive = false;
	}
};

/**
 * @function closeOnFocusOfParentNext
 * @description Close child menu when focus/tab to a new next parent menu anchor
 */

const closeOnFocusOfParentNext = (e) => {
	if (state.is_mobile) {
		return;
	}

	if (!document.body.classList.contains('nav-primary__list-child--depth-0--active')) {
		return;
	}

	if (e.which === 9 && !e.shiftKey) {
		closeAll();
	}
};

/**
 * @function closeOnFocusOfPrevEl
 * @description Close child menu when focus/tab to a previous focusable element
 */

const closeOnFocusOfPrevEl = (e) => {
	if (state.is_mobile) {
		return;
	}

	if (!document.body.classList.contains('nav-primary__list-child--depth-0--active')) {
		return;
	}

	if (e.shiftKey && e.which === 9) {
		closeAll();
	}
};

/**
 * @function closeOnEsc
 * @description Close the child menus when pressing esc
 */

const closeOnEsc = (e) => {
	if (e.which === 27) {
		if (document.body.classList.contains('nav-primary__list-child--depth-0--active')) {
			$('.nav-primary__list-item--child-active').find('.nav-primary__action--has-children').focus();
			closeAll();
		}
	}
};

/**
 * @function setupTopLevelActionKeyboardNavigation
 * @description Handle shift + tab & tab keyboard navigation
 */

const setupTopLevelActionKeyboardNavigation = () => {
	if (!state.desktop_initialized) {
		return;
	}

	const parentActions = tools.getNodes('.nav-primary__action--depth-0', true, el.container, true);

	if (!parentActions.length) {
		return;
	}

	// Handle closing of child menu when shift + tab from parent
	// level anchor to previous parent anchor
	parentActions.forEach((item) => {
		item.addEventListener('keydown', closeOnFocusOfPrevEl);
	});

	// Handle closing of child menu when tab
	// from last parent level anchor to next focusable element
	parentActions[parentActions.length - 1].addEventListener('keydown', closeOnFocusOfParentNext);

	// Handle closing of child menu when shift + tab
	// from first parent level anchor to previous focusable element
	parentActions[0].addEventListener('keydown', closeOnFocusOfPrevEl);
};

/**
 * @function setupMenuKeyboardNavigation
 * @description Handle shift + tab & tab keyboard navigation for child menus
 */

const setupMenuKeyboardNavigation = () => {
	if (!state.desktop_initialized) {
		return;
	}

	if (!el.childMenus.length) {
		return;
	}

	// Handle closing of child menu when focus on last child menu anchor
	// and tab to next parent level anchor
	el.childMenus.forEach((menu) => {
		const anchors = menu.querySelectorAll('.nav-primary__action');
		anchors.item(anchors.length - 1).addEventListener('keydown', closeOnFocusOfParentNext);
	});
};

/**
 * @function handleResize
 * @description handles resize event.
 */

const handleResize = () => {
	//closeAllIfOpen();
	setupMenuKeyboardNavigation();
	setupTopLevelActionKeyboardNavigation();
};

/**
 * @function cacheElements
 * @description Caches dom nodes this module uses.
 */

const cacheElements = () => {
	el.childMenus = tools.getNodes('[data-js="child-menu"]', true, el.container, true);
	el.childMenuTriggers = tools.getNodes('trigger-child-menu', true, el.container);
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	on(document, 'modern_tribe/resize_executed', handleResize);

	on(document, 'modern_tribe/mobile_nav_closed', closeAllIfOpen);

	on(document, 'modern_tribe/search_opened', closeAllIfOpen);

	delegate(el.container, '[data-js="trigger-child-menu"]', 'click', toggle);

	document.addEventListener('click', maybeClose);

	document.body.addEventListener('keydown', closeOnEsc);
};

/**
 * @function init
 * @description Kick off this modules functions
 */

const init = () => {
	if (!el.container) {
		return;
	}

	cacheElements();

	setupAriaAttributes();

	setupMenuKeyboardNavigation();
	setupTopLevelActionKeyboardNavigation();

	bindEvents();

	console.info('Initialized site navigation primary child menu scripts.');
};

export default init;
