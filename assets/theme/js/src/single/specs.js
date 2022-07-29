/**
 * @module
 * @description JavaScript specific to the instrument single specs
 */

import delegate from 'delegate';
import * as tools from '../utils/tools';
import state from '../config/state';
import scrollTo from '../utils/dom/scroll-to';

const el = {
	container: tools.getNodes('instrument-specs')[0],
};

let active = false;

/**
 * @function setupAriaAttributes
 * @description Setup the ARIA attributes for mobile navigation.
 */

const setupAriaAttributes = () => {
	// Trigger
	el.trigger.setAttribute('aria-expanded', false);
	el.trigger.setAttribute('aria-haspopup', true);
	el.trigger.setAttribute('aria-controls', el.content.getAttribute('id'));

	// Content
	el.content.setAttribute('aria-hidden', true);
	el.content.setAttribute('aria-labelledby', el.trigger.getAttribute('id'));
};

/**
 * @function setupAriaOpenAttributes
 * @description Setup mobile navigation open ARIA attributes.
 */

const setupAriaOpenAttributes = () => {
	// Trigger
	el.trigger.setAttribute('aria-expanded', true);

	// Content
	el.content.setAttribute('aria-hidden', false);
};

/**
 * @function setupAriaClosedAttributes
 * @description Setup mobile navigation closed ARIA attributes.
 */

const setupAriaClosedAttributes = () => {
	// Trigger
	el.trigger.setAttribute('aria-expanded', false);

	// Content
	el.content.setAttribute('aria-hidden', true);
};

/**
 * @function open
 * @description Opens the mobile navigation.
 */

const open = () => {
	setupAriaOpenAttributes();
	document.body.classList.add('instrument-specs--is-open');
	active = true;

	scrollTo({
		duration: 500,
		offset: -state.header_offset,
		$target: $(el.container),
	});
};

/**
 * @function close
 * @description Closes the mobile navigation.
 */

const close = () => {
	active = false;
	setupAriaClosedAttributes();
	document.body.classList.remove('instrument-specs--is-open');
};

/**
 * @function toggleContent
 * @description Toggles the content open and closed
 */

const toggleContent = () => {
	if (active) {
		close();
	} else {
		open();
	}
};

/**
 * @function cacheElements
 * @description Caches dom nodes this module uses.
 */

const cacheElements = () => {
	el.content = tools.getNodes('instrument-specs-table')[0];
	el.trigger = tools.getNodes('instrument-specs-trigger')[0];
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	delegate(el.container, '[data-js="instrument-specs-trigger"]', 'click', toggleContent);
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

	console.info('Initialized instrument specs scripts.');
};

export default navigation;
