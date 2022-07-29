/**
 * @module
 * @exports ready
 * @description The core dispatcher for the dom ready event javascript.
 */

import _ from 'lodash';

// you MUST do this in every module you use lodash in.
// A custom bundle of only the lodash you use will be built by babel.

import resize from './resize';
import scroll from './scroll';
import plugins from './plugins';
import viewportDims from './viewport-dims';
import applyBrowserClasses from '../utils/dom/apply-browser-classes';

import setHeaderOffsets from '../header/offsets';

import { on, ready } from '../utils/events';

import modules from '../modules/index';
import widgets from '../widgets/index';

import header from '../header/index';
import forms from '../forms/index';
import panels from '../panel/index';
import single from '../single/index';
import woocommerce from '../woocommerce/index';

/**
 * @function bindEvents
 * @description Bind global event listeners here,
 */

const bindEvents = () => {
	on(window, 'resize', _.debounce(resize, 200, false));
	on(window, 'scroll', _.debounce(scroll, 100, { leading: true }));
};

/**
 * @function init
 * @description The core dispatcher for init across the codebase.
 */

const init = () => {
	// apply browser classes

	applyBrowserClasses();

	// init external plugins

	plugins();

	// set initial states

	viewportDims();
	setHeaderOffsets();

	// initialize global events

	bindEvents();

	// initialize the module scripts

	modules();
	widgets();

	// initialize the main scripts

	header();
	forms();
	panels();
	single();
	woocommerce();

	console.info('Initialized all javascript that targeted document ready.');
};

/**
 * @function domReady
 * @description Export our dom ready enabled init.
 */

const domReady = () => {
	ready(init);
};

export default domReady;

