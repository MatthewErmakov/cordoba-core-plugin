/**
 * @module
 * @description Javascript that handles post loop filters.
 */

import delegate from 'delegate';
import * as tools from '../utils/tools';
import queryToJson from '../utils/data/query-to-json';

const el = {
	container: tools.getNodes('posts-loop-filters')[0],
};

/**
 * @function categoryFilterHandling
 * @description Handle form submittal based on category updates.
 */

const categoryFilterHandling = (e) => {
	// Set form action to category archive url
	el.container.action = e.delegateTarget.options[e.delegateTarget.selectedIndex].getAttribute('data-url');

	// Submit the form
	tools.getNodes('posts-loop-filters-submit')[0].click();
};

/**
 * @function validateSearchValue
 * @description Simple search input validation.
 */

const validateSearchValue = (e) => {
	const input = e.delegateTarget.previousElementSibling;
	const query = queryToJson();
	const searchQueryExists = Object.prototype.hasOwnProperty.call(query, 's') && query.s.length;

	if ((input.value.trim().length === 0 && !searchQueryExists) && !el.container.querySelector('#filterByCategory').value) {
		e.preventDefault();
	}
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */

const bindEvents = () => {
	delegate(el.container, '#filterByCategory', 'change', categoryFilterHandling);

	delegate(el.container, '.form-search__submit', 'click', validateSearchValue);
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

	console.info('Initialized post loop filter script.');
};

export default init;
