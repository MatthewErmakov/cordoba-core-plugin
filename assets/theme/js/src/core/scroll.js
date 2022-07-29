/**
 * @module
 * @exports scroll
 * @description Emits a scroll event that is debounced for other modules,
 * can also hookup global debounced scroll functions.
 */

import { trigger } from '../utils/events';

const scroll = () => {
	trigger({ event: 'modern_tribe/scroll', native: false });
};

export default scroll;
