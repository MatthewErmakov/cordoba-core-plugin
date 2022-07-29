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

const el = {
	container: tools.getNodes('site-header')[0],
};

/**
 * @function open
 * @description Opens the search.
 */

const open = () => {
	document.body.classList.add('site-navigation_search--is-open');
	navState.searchActive = true;
	trigger({ event: 'modern_tribe/search_opened', native: false });

	_.delay(() => {
		el.input.focus();
	}, 600);
};

/**
 * @function close
 * @description Closes the search.
 */

const close = () => {
	navState.searchActive = false;
	document.body.classList.remove('site-navigation_search--is-open');
};

/**
 * @function maybeClose
 * @description Triggered by document clicks and closes if not in target area
 */

const maybeClose = (e) => {
	if (state.is_mobile) {
		return;
	}

	if (!navState.searchActive) {
		return;
	}

	if (!tools.closest(e.target, '.site-header')) {
		close();
	}
};

/**
 * @function toggleSearch
 * @description Toggles the search input open and closed
 */

const toggleSearch = () => {
	if (state.is_mobile) {
		return;
	}

	if (navState.searchActive) {
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
	if (!document.body.classList.contains('site-navigation_search--is-open')) {
		return;
	}

	if (e.which === 27) {
		close();
		el.trigger.focus();
	}
};

/**
 * @function closeOnFocusOfPreviousAnchor
 * @description Close search when focus & tab to previous anchor
 */

const closeOnFocusOfPreviousAnchor = (e) => {
	if (!document.body.classList.contains('site-navigation_search--is-open')) {
		return;
	}

	if (e.shiftKey && e.which === 9) {
		e.preventDefault();
		close();
		el.trigger.focus();
	}
};

/**
 * @function closeOnFocusOfNextAnchor
 * @description Close search when tab to next anchor
 */

const closeOnFocusOfNextAnchor = (e) => {
	if (!document.body.classList.contains('site-navigation_search--is-open')) {
		return;
	}

	if (e.which === 9 && !e.shiftKey) {
		e.preventDefault();
		close();
	}
};

/**
 * @function handleResize
 * @description handles resize event.
 */

const handleResize = () => {
	if (state.is_mobile && document.body.classList.contains('site-navigation_search--is-open')) {
		close();
		el.trigger.focus();
	}
};

/**
 * @function cacheElements
 * @description Caches dom nodes this module uses.
 */

const cacheElements = () => {
	el.search = tools.getNodes('.site-header__search', false, el.container, true)[0];
	el.input = el.search.querySelector('.form-search__input');
	el.trigger = el.search.querySelector('.form-search__submit');
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	document.body.addEventListener('keydown', closeOnEsc);

	on(document, 'modern_tribe/resize_executed', handleResize);

	document.addEventListener('click', maybeClose);

	delegate(el.container, '.form-search__submit', 'click', toggleSearch);

	el.trigger.addEventListener('keydown', closeOnFocusOfNextAnchor);
	el.input.addEventListener('keydown', closeOnFocusOfPreviousAnchor);
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

	bindEvents();

	console.info('Initialized site navigation search scripts.');
};

export default init;
