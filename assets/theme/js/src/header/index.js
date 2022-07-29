/**
 * @module
 * @description Base vendor module for the modern tribe header & navigation js.
 */

import { on } from '../utils/events';

import headerOffsets from './offsets';

import header from './header';
import navigation from './navigation';
import childMenusPrimary from './child-menus-primary';
import search from './search';

export const navState = {
	mobileOpen: false,
	subPrimaryActive: false,
	searchActive: false,
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	on(document, 'modern_tribe/scroll', headerOffsets);
};

/**
 * @function init
 * @description Kick off this modules functions
 */

const init = () => {
	bindEvents();

	header();

	navigation();

	childMenusPrimary();

	search();
};

export default init;
