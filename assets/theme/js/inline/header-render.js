/* eslint-disable */
var modernTribe = window.modernTribe || {};
(function(mt) {
	var header = mt.headerRender = mt.headerRender || {};

	header.util = {
		debounce: function(func, wait, immediate) {
			var timeout;
			return function() {
				var context = this, args = arguments;
				var later = function() {
					timeout = null;
					if (!immediate) func.apply(context, args);
				};
				var callNow = immediate && !timeout;
				clearTimeout(timeout);
				timeout = setTimeout(later, wait);
				if (callNow) func.apply(context, args);
			};
		}
	};

	header.notice = {
		mobileBreakpoint: 960,
		wpBreakpoint: 783,
		el: document.getElementsByClassName('site-header')[0],
		wrapper: document.getElementsByClassName('site-header__brand')[0],
		notice: document.getElementsByClassName('woocommerce-notice-global--store')[0],
		viewport: document.documentElement.clientWidth,

		state: {
			firstRun: true,
		},

		isMobile: function() {
			return document.documentElement.clientWidth < this.mobileBreakpoint;
		},

		isWPAdminBump: function() {
			return document.documentElement.clientWidth >= this.wpBreakpoint;
		},

		wpAdminOffset: function() {
			if (document.body.classList.contains('admin-bar')) {
				return this.isWPAdminBump() ? 32 : 46;
			} else {
				return 0;
			}
		},

		bindEvents: function() {
			window.addEventListener('resize', header.util.debounce(this.handleResize, 200));
		},

		handleSpaceForWCStoreNotice: function() {
			// Reset top
			this.el.removeAttribute('style');
			this.wrapper.removeAttribute('style');

			// Set header context
			var header = this.isMobile() ? this.wrapper : this.el;

			// Set header top position
			header.style.top = this.notice.offsetHeight + this.wpAdminOffset() + 'px';
		},

		handleResize: function() {
			if (document.documentElement.clientWidth === header.notice.viewport) {
				return;
			}

			header.notice.handleSpaceForWCStoreNotice();

			header.notice.viewport = document.documentElement.clientWidth;
		},

		init: function () {
			if (!(this.el && this.notice)) {
				return;
			}

			this.bindEvents();
			this.handleSpaceForWCStoreNotice();
		}
	};

	header.notice.init();
})(modernTribe);
