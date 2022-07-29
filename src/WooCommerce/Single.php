<?php

namespace Tribe\Project\WooCommerce;

class Single {

	public function override_template() {
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	}

	public function woocommerce_template_single_sharing() {
		$product = wc_get_product( get_the_ID() );

		// CASE: if product is type variable don't output social
		// sharing for this particular action
		if ( $product->is_type( 'variable' ) ) {
			return;
		}

        return get_template_part( 'components/social/share' );
	}

	public function customize_woocommerce_price_format( $format, $currency_pos ) {
		$currency = get_woocommerce_currency();

		switch ( $currency_pos ) {
			case 'left' :
				$format = '%1$s%2$s' . '<span class="woocommerce-Price-suffix-currency">&nbsp;' . $currency . '</span>';
				break;
			case 'right' :
				$format = '<span class="woocommerce-Price-suffix-currency">' . $currency . '&nbsp;</span>' . '%2$s%1$s';
			break;
			case 'left_space' :
				$format = '%1$s&nbsp;%2$s' . '<span class="woocommerce-Price-suffix-currency">&nbsp;' . $currency . '</span>';
			break;
			case 'right_space' :
				$format = '<span class="woocommerce-Price-suffix-currency">' . $currency . '&nbsp;</span>' . '%2$s&nbsp;%1$s';
			break;
		}

		return $format;
	}

}
