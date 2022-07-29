<?php

namespace Tribe\Project\Theme\Resources;

use Tribe\Project\Settings\General;

class Third_Party_Tags {

	/**
	 *  Google Tag Manager
	 */
	public function inject_google_tag_manager() {

		$id = General::instance()->get_setting( General::ID_GTM );

		if ( empty( $id ) ) {
			return;
		}

		?>

		<!-- Google Tag Manager -->
		<script>
			(function (w, d, s, l, i) {
				w[l] = w[l] || [];
				w[l].push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
				var f = d.getElementsByTagName(s)[0],
					j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
				j.async = true;
				j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
				f.parentNode.insertBefore(j, f);
			})(window, document, 'script', 'dataLayer', '<?php echo $id; ?>');
		</script>
		<noscript>
			<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $id; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<!-- End Google Tag Manager -->

		<?php
	}
}
