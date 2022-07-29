/**
 * @module
 * @description Javascript for video carousel panel.
 */

import Packery from 'packery';
import state from '../config/state';
import { MOBILE_BREAKPOINT } from '../config/options';
import * as tools from '../utils/tools';
import { on } from '../utils/events';
import { panels } from './index';

const el = {
	container: tools.getNodes('panel-gallery-masonry', true, panels),
};

const instances = [];
let activePackery = false;

/**
 * @function setupGalleries
 * @description Setup galleries.
 */

const setupGalleries = () => {
	tools.getNodes('panel-gallery-masonry', true, tools.getNodes('panel-collection')[0]).forEach((panel, index) => {
		if (state.v_width >= MOBILE_BREAKPOINT && !activePackery) {
			activePackery = true;
			const pckry = new Packery(panel, { // eslint-disable-line no-unused-vars
				itemSelector: '.panel--type-gallery__item',
				percentPosition: true,
				stamp: '.panel--type-gallery__stamp',
			});
			instances.push(pckry);
		} else if (state.v_width < MOBILE_BREAKPOINT && activePackery) {
			activePackery = false;
			instances[index].destroy();
		}
	});
};

/**
 * @function handleResize
 * @description handles resize event.
 */

const handleResize = () => {
	setupGalleries();
};

/**
 * @function handlePanelPreviewUpdate
 * @description handles live panel preview event.
 */

const handlePanelPreviewUpdate = () => {
	activePackery = false;
	setupGalleries();
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	on(document, 'modern_tribe/resize_executed', handleResize);
	on(document, 'modular_content/panel_preview_updated', handlePanelPreviewUpdate);
};

/**
 * @function init
 * @description Initializes the class if the element(s) to work on are found.
 */

const init = () => {
	if (!el.container) {
		return;
	}

	bindEvents();

	setupGalleries();

	console.info('Initialized gallery panel script.');
};

export default init;
