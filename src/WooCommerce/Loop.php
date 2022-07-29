<?php

namespace Tribe\Project\WooCommerce;

class Loop {

	public function customize_posts_per_page( $cols ) {
		if( is_admin() )  {
			return $cols;
		}

		return 12;
	}

	public function override_templates() {
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

		// Add product schema to results
		add_action( 'woocommerce_after_shop_loop_item', function () {
			the_schema_as_json_ld();
		}, 20 );
	}

	public function customizing_the_loop( $query ) {
		if ( ! class_exists( 'woocommerce' ) ) {
			return;
		}

		// TODO: this is causing a notice on home page, not sure why
		//if ( ! is_shop() && ! is_product_taxonomy() ) {
		//	return;
		//}

		if ( $query->is_admin || ! $query->is_main_query() ) {
			return;
		}

		// Handle on sale query for shop loops
		if ( ! empty( $_GET['view_all_on_sale'] ) && $_GET['view_all_on_sale'] === 'yes' ) {
			$query->set( 'meta_query', [
				'relation' => 'OR',
				// Simple products type
				[
					'key'     => '_sale_price',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'numeric'
				],
				// Variable products type
				[
					'key'     => '_min_variation_sale_price',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'numeric'
				],
			] );
			
			print_r($query);
		}

	}

}
