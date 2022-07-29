/**
 * @module
 * @description Javascript for video carousel panel.
 */

import Swiper from 'swiper';
import * as tools from '../utils/tools';
import { panels } from './index';

const el = {
	container: tools.getNodes('panel-cardgrid-slider', true, panels),
};

/**
 * @function setupCarousels
 * @description Setup carousels.
 */

const setupCarousels = () => {
	el.container.forEach((panel) => {
		const parent = tools.closest(panel, '.panel--type-cardgrid__slider-wrapper');
		const carousel = new Swiper(panel, { // eslint-disable-line no-unused-vars
			a11y: true,
			centeredSlides: false,
			grabCursor: true,
			keyboardControl: true,
			nextButton: parent.querySelector('.swiper-button-next'),
			prevButton: parent.querySelector('.swiper-button-prev'),
			slidesPerView: 'auto',
			spaceBetween: 30,
			speed: 500,
			breakpoints: {
				767: {
					spaceBetween: 20,
				},
			},
		});
	});
};

/**
 * @function init
 * @description Initializes the class if the element(s) to work on are found.
 */

const init = () => {
	if (!el.container) {
		return;
	}

	setupCarousels();

	console.info('Initialized card grid panel script.');
};

export default init;
