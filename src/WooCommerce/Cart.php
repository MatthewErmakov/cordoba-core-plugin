<?php

namespace Tribe\Project\WooCommerce;

class Cart {

    public function override_template() {
        remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
    }

}
