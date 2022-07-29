/**
 * @module
 * @description Javascript that handles WP search.
 */

import * as tools from '../utils/tools';

const el = {
	container: tools.getNodes('.form-search-validate', true, document, true),
};

/**
 * @function validateSearchValue
 * @description Simple search input validation.
 */

const validateSearchValue = (e) => {
	const input = e.currentTarget.previousElementSibling;

	if (input.value.trim().length === 0) {
		e.preventDefault();
	}
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	el.container.forEach((form) => {
		form.querySelector('.form-search__submit').addEventListener('click', validateSearchValue);
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

	bindEvents();

	console.info('Initialized WP search script.');
};

export default init;
