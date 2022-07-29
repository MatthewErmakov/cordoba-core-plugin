/**
 * @module
 * @description Base content module for panels js.
 */

import * as tools from '../utils/tools';

import cardGrid from './card-grid';
import gallery from './gallery';
import social from './social';

export const panels = tools.getNodes('panel-collection')[0];

/**
 * @function init
 * @description Kick off this modules functions
 */

const init = () => {
	if (!panels) {
		return;
	}

	cardGrid();

	gallery();

	social();
};

export default init;
