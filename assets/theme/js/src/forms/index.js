/**
 * @module
 * @description Base content module for shared content js and props.
 */

import postLoopFilters from './post-loop-filters';
import search from './search';

/**
 * @function init
 * @description Kick off this modules functions
 */

const init = () => {
	postLoopFilters();
	search();
};

export default init;
