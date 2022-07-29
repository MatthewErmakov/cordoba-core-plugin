
/**
 * @function init
 * @description Kick off this modules functions
 */

import ui from './ui';
import cartActions from './cart-actions';
import checkoutActions from './checkout-actions';
import loopFilters from './loop-filters';

const init = () => {
	ui();

	cartActions();

	checkoutActions();

	loopFilters();
};

export default init;
