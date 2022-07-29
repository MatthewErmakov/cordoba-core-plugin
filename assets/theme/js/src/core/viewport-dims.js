/**
 * @module
 * @exports viewportDims
 * @description Sets viewport dimensions using verge on shared state
 * and detects mobile or desktop state.
 */

import verge from 'verge';
import state from '../config/state';
import { DESKTOP_BREAKPOINT } from '../config/options';

const viewportDims = () => {
	state.v_height = verge.viewportH();
	state.v_width = verge.viewportW();

	if (state.v_width >= DESKTOP_BREAKPOINT) {
		state.is_desktop = true;
		state.is_mobile = false;

		if (!state.desktop_initialized) {
			state.desktop_initialized = true;
		}
	} else {
		state.is_desktop = false;
		state.is_mobile = true;

		if (!state.mobile_initialized) {
			state.mobile_initialized = true;
		}
	}
};

export default viewportDims;
