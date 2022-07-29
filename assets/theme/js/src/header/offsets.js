/**
 * @module
 * @description JavaScript specific to the site header offsets
 */

import state from '../config/state';
import { WP_ADMIN_BAR_BREAKPOINT, DESKTOP_BREAKPOINT } from '../config/options';
import * as tools from '../utils/tools';

const setHeaderOffsets = () => {
	// Update admin offset
	if (tools.hasClass(document.body, 'admin-bar')) {
		state.wpadmin_offset = (state.v_width >= WP_ADMIN_BAR_BREAKPOINT) ? 32 : 46;
	}

	// Update header height
	state.header_height = (state.v_width >= DESKTOP_BREAKPOINT) ? tools.getNodes('site-header')[0].offsetHeight : tools.getNodes('site-header-brand')[0].offsetHeight;

	// Update header offset
	state.header_offset = (tools.hasClass(document.body, 'admin-bar')) ? (state.header_height + state.wpadmin_offset) : state.header_height;
};

export default setHeaderOffsets;
