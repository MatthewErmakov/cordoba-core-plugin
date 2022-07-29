/**
 * @module
 * @description Javascript for video carousel panel.
 */

import Swiper from 'swiper';
import * as tools from '../utils/tools';
import { trigger } from '../utils/events';
import { panels } from './index';

const el = {
	container: tools.getNodes('panel-social-slider', true, panels),
};

/**
 * @function setupInstrumentImageZoom
 * @description Setup instrument slider image zoom.
 */

/*
const setupInstrumentImageZoom = (panel) => {
	const zoomTarget = $(panel).find('.item-instrument__highlights-slide-media');
	const galleryWidth = $(panel).width();

	$(zoomTarget).each((i, item) => { // eslint-disable-line consistent-return
		if (item.dataset.fullWidth > galleryWidth) {
			$(item).zoom();
		}
	});
};
*/

/**
 * @function setupInstrumentImagePopupOpen
 * @description Setup instrument slider image popup - image items.
 */

const getGalleryItems = (target) => {
	const $slides = target.closest('.panel--type-social__slider-wrapper').find('.item-instrument__highlights-slide');
	const items = [];

	$slides.each((i, image) => {
		const img = $(image).find('.item-instrument__highlights-slide-media-anchor img');
		const largeImageSrc = img.attr('data-popup-src');
		const largeImageW = img.attr('data-popup-width');
		const largeImageH = img.attr('data-popup-height');
		const item = {
			src: largeImageSrc,
			w: largeImageW,
			h: largeImageH,
			title: img.attr('data-caption') ? img.attr('data-caption') : img.attr('title'),
		};
		items.push(item);
	});

	return items;
};

/**
 * @function setupInstrumentImagePopupOpen
 * @description Setup instrument slider image popup - open.
 */

const setupInstrumentImagePopupOpen = (e) => {
	e.preventDefault();

	const pswpElement = $('.pswp')[0];
	const eventTarget = $(e.target);
	const items = getGalleryItems(eventTarget);
	let clicked;

	if (!eventTarget.is('.woocommerce-product-gallery__trigger')) {
		clicked = eventTarget.closest('.item-instrument__highlights-slide');
	} else {
		clicked = eventTarget.closest('.panel--type-social__slider-wrapper').find('.swiper-slide-active');
	}

	const options = $.extend({
		index: $(clicked).index(),
	}, {
		shareEl: false,
		closeOnScroll: false,
		history: false,
		hideAnimationDuration: 0,
		showAnimationDuration: 0,
	});

	// Initializes and opens PhotoSwipe.
	const photoswipe = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
	photoswipe.init();
};

/**
 * @function setupInstrumentImagePopup
 * @description Setup instrument slider image popup.
 */

const setupInstrumentImagePopup = (parent) => {
	const target = $(parent);

	target.prepend('<a href="#" class="woocommerce-product-gallery__trigger">🔍</a>');
	target.on('click', '.woocommerce-product-gallery__trigger', setupInstrumentImagePopupOpen);
	target.on('click', '.item-instrument__highlights-slide-media-anchor', setupInstrumentImagePopupOpen);
};

/**
 * @function setupSliders
 * @description Setup sliders.
 */

const setupSliders = () => {
	el.container.forEach((panel) => {
		const parent = tools.closest(panel, '.panel--type-social__slider-wrapper');
		const slider = new Swiper(panel, { // eslint-disable-line no-unused-vars
			a11y: true,
			//autoplay: 4000,
			//autoplayStopOnLast: true,
			grabCursor: true,
			keyboardControl: true,
			nextButton: parent.querySelector('.swiper-button-next'),
			prevButton: parent.querySelector('.swiper-button-prev'),
			pagination: panel.querySelector('.swiper-pagination'),
			paginationClickable: true,
			speed: 500,
			onInit: () => {
				if (!tools.closest(panel, '.item-instrument__section--highlights')) {
					return;
				}

				//setupInstrumentImageZoom(panel);
				setupInstrumentImagePopup(parent);
			},
			onSlideChangeStart: () => {
				if (panel.querySelector('.wp-embed-lazy--is-playing')) {
					trigger({ event: 'modern_tribe/carousel_panel_slide_changed', native: false });
				}
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

	setupSliders();

	console.info('Initialized social panel script.');
};

export default init;
