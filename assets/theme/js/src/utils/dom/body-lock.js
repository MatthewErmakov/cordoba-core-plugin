import * as tests from '../tests';

const browser = tests.browserTests();
let locked = false;
let scroll = 0;
const scroller = browser.edge || browser.ie || browser.firefox ? document.documentElement : document.body;

/**
 * @function isLocked
 * @description Returns state
 */

const isLocked = () => locked;

/**
 * @function lock
 * @description Lock the body at a particular position and prevent scroll,
 * use margin to simulate original scroll position.
 */

const lock = () => {
	locked = true;
	scroll = scroller.scrollTop;
	document.body.style.position = 'fixed';
	document.body.style.width = '100%';
	document.body.style.marginTop = `-${scroll}px`;
};

/**
 * @function unlock
 * @description Unlock the body and return it to its actual scroll position.
 */

const unlock = () => {
	locked = false;
	document.body.style.marginTop = '0px';
	document.body.style.position = 'static';
	document.body.style.width = 'auto';
	scroller.scrollTop = scroll;
};

export { isLocked, lock, unlock };
