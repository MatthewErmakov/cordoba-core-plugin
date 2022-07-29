/**
 * @module
 * @description JavaScript specific to the instrument single dealers
 */

import _ from 'lodash';
import A11yDialog from 'a11y-dialog';
import * as tools from '../utils/tools';
import * as bodyLocker from '../utils/dom/body-lock';

const ANIMATION_DELAY = 300;

const el = {
	container: tools.getNodes('instrument-dealers')[0],
};

let dialog;
let initialized = false;

/**
 * @function open
 * @description Opens the dialog.
 */

const show = () => {
	if (!initialized) {
		$(el.dataContainer).append(el.data.innerHTML);
	}
	document.body.classList.add('widget-dialog--is-active');
	bodyLocker.lock();
	initialized = true;
};

/**
 * @function close
 * @description Closes the dialog.
 */

const hide = () => {
	bodyLocker.unlock();
	_.delay(() => {
		document.body.classList.remove('widget-dialog--is-active');
	}, ANIMATION_DELAY);
};

/**
 * @function initDialog
 * @description Setup dialog.
 */

const initDialog = () => {
	dialog = new A11yDialog(el.container, el.site_wrapper);
};

/**
 * @function cacheElements
 * @description Caches dom nodes this module uses.
 */

const cacheElements = () => {
	el.site_wrapper = tools.getNodes('site-wrap')[0];
	el.trigger = tools.getNodes('instrument-dealers-trigger')[0];
	el.data = tools.getNodes('instrument-dealers-data')[0];
	el.dataContainer = tools.getNodes('instrument-dealers-data-container')[0];
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	dialog.on('show', show);

	dialog.on('hide', hide);
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

	initDialog();

	bindEvents();

	console.info('Initialized instrument dealer scripts.');
};

export default init;
