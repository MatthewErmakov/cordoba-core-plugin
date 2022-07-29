/**
 * @module
 * @description Base content module for shared content js and props.
 */

import comments from './comments';
import dealers from './dealers';
import specs from './specs';

/**
 * @function init
 * @description Kick off this modules functions
 */

const init = () => {
	comments();

	dealers();

	specs();
};

export default init;
