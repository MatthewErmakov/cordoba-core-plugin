/**
 * @module
 * @exports resize
 * @description Kicks in any third party plugins that operate on a sitewide basis.
 */

import { trigger } from '../utils/events';
import viewportDims from './viewport-dims';
import setHeaderOffsets from '../header/offsets';

const resize = () => {
	// code for resize events can go here

	viewportDims();

	setHeaderOffsets();

	trigger({ event: 'modern_tribe/resize_executed', native: false });
};

export default resize;
