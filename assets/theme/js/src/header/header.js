/**
 * @module
 * @description JavaScript specific to the site header
 */

import { on } from '../utils/events';
import * as tools from '../utils/tools';
import * as win from '../utils/dom/win-position';
import * as bodyLock from '../utils/dom/body-lock';
import setHeaderOffsets from './offsets';

let scrolledTop = true;

const el = {
	container: tools.getNodes('site-header')[0],
};

/**
 * @function scrollIn
 * @description Setup site header scroll in.
 */

const scrollIn = () => {
	el.container.classList.add('site-header--has-scrolled-in');
	scrolledTop = false;
};

/**
 * @function scrollOut
 * @description Setup site header scroll out.
 */

const scrollOut = () => {
	el.container.classList.remove('site-header--has-scrolled-in');
	scrolledTop = true;
};

/**
 * @function handleScroll
 * @description Setup site header scroll.
 */

const handleScroll = () => {
	if (bodyLock.isLocked() || document.body.classList.contains('tribe-loop-filters--is-open')) {
		return;
	}

	/* Update header offsets as scroll */
	setHeaderOffsets();

	const scrolledIn = win.top() !== 0 && win.top() > 0;

	if (scrolledIn && scrolledTop) {
		scrollIn();
	} else if (!scrolledIn && !scrolledIn) {
		scrollOut();
	}
};

/**
 * @function handleResize
 * @description handles resize event.
 */

const handleResize = () => {
	//if (!scrolledTop) {
	//	scrollOut();
	//} else {
	handleScroll();
	//}
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	//on(window, 'load', handleScroll);

	on(document, 'modern_tribe/scroll', handleScroll);

	on(document, 'modern_tribe/resize_executed', handleResize);
};

/**
 * @function init
 * @description Kick off this modules functions
 */

const header = () => {
	if (!el.container) {
		return;
	}

	bindEvents();

	handleScroll();

	console.info('Initialized site header scripts.');
};

export default header;
