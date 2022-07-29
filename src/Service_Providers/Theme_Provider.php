<?php


namespace Tribe\Project\Service_Providers;


use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tribe\Project\Theme\Body_Classes;
use Tribe\Project\Theme\Dealer_Locator;
use Tribe\Project\Theme\Excerpt;
use Tribe\Project\Theme\Image_Sizes;
use Tribe\Project\Theme\Image_Wrap;
use Tribe\Project\Theme\Inline_Scripts;
use Tribe\Project\Theme\Gravity_Forms_Filter;
use Tribe\Project\Theme\Nav\Nav_Attribute_Filters;
use Tribe\Project\Theme\Oembed_Filter;
use Tribe\Project\Theme\Resources\Editor_Styles;
use Tribe\Project\Theme\Resources\Emoji_Disabler;
use Tribe\Project\Theme\Resources\Fonts;
use Tribe\Project\Theme\Resources\Legacy_Check;
use Tribe\Project\Theme\Resources\Login_Resources;
use Tribe\Project\Theme\Resources\Scripts;
use Tribe\Project\Theme\Resources\Styles;
use Tribe\Project\Theme\Search;
use Tribe\Project\Theme\Resources\Third_Party_Tags;
use Tribe\Project\Theme\Single_Instruments;
use Tribe\Project\Theme\Supports;
use Tribe\Project\Theme\WP_Responsive_Image_Disabler;
use Tribe\Project\Theme\Sidebars;

class Theme_Provider implements ServiceProviderInterface {

	private $typekit_id   = '';
	private $google_fonts = [ 'Quando' ];

	/**
	 * Custom (self-hosted) fonts are sourced/imported in the theme: wp-content/themes/core/pcss/base/_fonts.pcss
	 * Declare them here if they require webfont event support (loading, loaded, etc).
	 */
	private $custom_fonts = [];

	public function register( Container $container ) {
		$this->body_classes( $container );
		$this->excerpts( $container );
		$this->image_sizes( $container );
		$this->image_wrap( $container );
		$this->image_links( $container );
		$this->inline_scripts( $container );
		$this->disable_responsive_images( $container );
		$this->oembed( $container );
		$this->supports( $container );
		$this->login_resources( $container );
		//$this->legacy_resources( $container );
		$this->disable_emoji( $container );
		$this->fonts( $container );
		$this->scripts( $container );
		$this->styles( $container );
		$this->editor_styles( $container );
		$this->third_party_tags( $container );
		$this->nav_attributes( $container );
		$this->gravity_forms( $container );
		$this->search( $container );
		$this->dealer_locator( $container );
		$this->sidebars( $container );
		$this->single_instruments( $container );
	}

	private function body_classes( Container $container ) {
		$container[ 'theme.body_classes' ] = function ( Container $container ) {
			return new Body_Classes();
		};
		add_filter( 'body_class', function ( $classes ) use ( $container ) {
			return $container[ 'theme.body_classes' ]->body_classes( $classes );
		}, 10, 1 );
	}

	private function excerpts( Container $container ) {
		$container[ 'theme.excerpt' ] = function ( Container $container ) {
			return new Excerpt();
		};
		add_filter( 'excerpt_length', function ( $length ) use ( $container ) {
			return $container[ 'theme.excerpt' ]->customize_excerpt_length( $length );
		}, 999 );
		add_filter( 'excerpt_more', function ( $more ) use ( $container ) {
			return $container[ 'theme.excerpt' ]->customize_excerpt_more( $more );
		} );
	}

	private function image_sizes( Container $container ) {
		$container[ 'theme.images.sizes' ] = function ( Container $container ) {
			return new Image_Sizes();
		};
		add_action( 'after_setup_theme', function () use ( $container ) {
			$container[ 'theme.images.sizes' ]->register_sizes();
		}, 10, 0 );
		add_filter( 'wpseo_opengraph_image_size', function ( $size ) use ( $container ) {
			return $container[ 'theme.images.sizes' ]->customize_wpseo_image_size( $size );
		}, 10, 1 );
	}

	private function image_wrap( Container $container ) {
		$container[ 'theme.images.wrap' ] = function ( Container $container ) {
			return new Image_Wrap();
		};

		add_filter( 'the_content', function ( $html ) use ( $container ) {
			return $container[ 'theme.images.wrap' ]->customize_wp_image_non_captioned_output( $html );
        }, 12, 1 );

		remove_shortcode('caption' );
		add_shortcode( 'caption', [ $container['theme.images.wrap'], 'caption' ] );
	}

	private function image_links( Container $container ) {
		add_filter( 'pre_option_image_default_link_type', function () {
			return 'none';
		}, 10, 1 );
	}

	private function disable_responsive_images( Container $container ) {
		$container[ 'theme.images.responsive_disabler' ] = function ( Container $container ) {
			return new WP_Responsive_Image_Disabler();
		};
		add_action( 'init', function () use ( $container ) {
			$container[ 'theme.images.responsive_disabler' ]->hook();
		} );
	}

	private function inline_scripts( Container $container ) {
		$container[ 'theme.inline_scripts' ] = function ( Container $container ) {
			return new Inline_Scripts( $container[ 'plugin_file' ] );
		};
		add_action( 'core_theme_nav_render_scripts', function () use ( $container ) {
			$container[ 'theme.inline_scripts' ]->header_navigation_render_scripts();
		}, 10, 0 );
	}

	private function oembed( Container $container ) {
		$container[ 'theme.oembed' ] = function ( Container $container ) {
			return new Oembed_Filter( [
				Oembed_Filter::PROVIDER_VIMEO,
				Oembed_Filter::PROVIDER_YOUTUBE,
			] );
		};

		add_filter( 'oembed_dataparse', function ( $html, $data, $url ) use ( $container ) {
			return $container[ 'theme.oembed' ]->setup_lazyload_html( $html, $data, $url );
		}, 1000, 3 );
		add_filter( 'embed_oembed_html', function ( $html, $url, $attr, $post_id ) use ( $container ) {
			return $container[ 'theme.oembed' ]->filter_frontend_html_from_cache( $html, $url, $attr, $post_id );
		}, 1, 4 );
		add_filter( 'embed_oembed_html', function ( $html, $url, $attr, $post_id ) use ( $container ) {
			return $container[ 'theme.oembed' ]->wrap_oembed_shortcode_output( $html, $url, $attr, $post_id );
		}, 99, 4 );
	}

	private function supports( Container $container ) {
		$container[ 'theme.supports' ] = function ( Container $container ) {
			return new Supports();
		};

		add_action( 'after_setup_theme', function () use ( $container ) {
			$container[ 'theme.supports' ]->add_theme_supports();
		}, 10, 0 );
	}

	private function login_resources( Container $container ) {
		$container[ 'theme.resources.login' ] = function ( Container $container ) {
			return new Login_Resources( $container[ 'plugin_file' ] );
		};
		add_action( 'login_enqueue_scripts', function () use ( $container ) {
			$container[ 'theme.resources.login' ]->login_styles();
		}, 10, 0 );
	}

	private function legacy_resources( Container $container ) {
		$container[ 'theme.resources.legacy' ] = function ( Container $container ) {
			return new Legacy_Check();
		};

		add_action( 'wp_head', function () use ( $container ) {
			$container[ 'theme.resources.legacy' ]->old_browsers();
		}, 0, 0 );
	}

	private function disable_emoji( Container $container ) {
		$container[ 'theme.resources.emoji_disabler' ] = function ( Container $container ) {
			return new Emoji_Disabler();
		};
		add_action( 'after_setup_theme', function () use ( $container ) {
			$container[ 'theme.resources.emoji_disabler' ]->remove_hooks();
		} );
	}

	private function fonts( Container $container ) {
		$container[ 'theme.resources.typekit_id' ] = $this->typekit_id;
		$container[ 'theme.resources.google_fonts' ] = $this->google_fonts;
		$container[ 'theme.resources.custom_fonts' ] = $this->custom_fonts;
		$container[ 'theme.resources.fonts' ] = function ( Container $container ) {
			return new Fonts(
				$container[ 'plugin_file' ],
				[
					'typekit' => $container[ 'theme.resources.typekit_id' ],
					'google'  => $container[ 'theme.resources.google_fonts' ],
					'custom'  => $container[ 'theme.resources.custom_fonts' ],
				]
			);
		};

		add_action( 'wp_head', function () use ( $container ) {
			$container[ 'theme.resources.fonts' ]->load_fonts();
		}, 0, 0 );
		/* add_action( 'login_head', function() use ( $container ) {
			$container[ 'theme.resources.fonts' ]->load_fonts();
		}, 0, 0); */
	}

	private function scripts( Container $container ) {
		$container[ 'theme.resources.scripts' ] = function ( Container $container ) {
			return new Scripts( $container[ 'plugin_file' ] );
		};
		add_action( 'wp_enqueue_scripts', function () use ( $container ) {
			$container[ 'theme.resources.scripts' ]->enqueue_scripts();
		}, 10, 0 );
		add_action( 'wp_footer', function () use ( $container ) {
			$container[ 'theme.resources.scripts' ]->add_mailchimp_popup_js();
		}, 9999, 0 );
	}

	private function styles( Container $container ) {
		$container[ 'theme.resources.styles' ] = function ( Container $container ) {
			return new Styles( $container[ 'plugin_file' ] );
		};
		add_action( 'wp_enqueue_scripts', function () use ( $container ) {
			$container[ 'theme.resources.styles' ]->enqueue_styles();
		}, 10, 0 );
		add_action( 'wp_head', function () use ( $container ) {
			$container[ 'theme.resources.styles' ]->add_no_js_polite_styles();
		} );
	}

	private function editor_styles( Container &$container ) {
		$container[ 'theme.resources.editor_styles' ] = function ( Container $container ) {
			return new Editor_Styles( $container[ 'plugin_file' ] );
		};
		add_action( 'after_setup_theme', function () use ( $container ) {
			$container[ 'theme.resources.editor_styles' ]->visual_editor_styles();
		}, 10, 0 );
		add_filter( 'tiny_mce_before_init', function ( $settings ) use ( $container ) {
			return $container[ 'theme.resources.editor_styles' ]->visual_editor_body_class( $settings );
		}, 10, 1 );
		add_filter( 'mce_buttons_2', function ( $buttons ) use ( $container ) {
			return $container[ 'theme.resources.editor_styles' ]->add_tinymce_format_button( $buttons );
		}, 10, 1 );
		add_filter( 'tiny_mce_before_init', function ( $init_array ) use ( $container ) {
			return $container[ 'theme.resources.editor_styles' ]->add_tinymce_formats( $init_array );
		}, 10, 1 );
	}

	private function third_party_tags( Container $container ) {
		$container[ 'theme.resources.third_party_tags' ] = function ( Container $container ) {
			return new Third_Party_Tags();
		};
		add_action( 'tribe_head_body', function () use ( $container ) {
			$container[ 'theme.resources.third_party_tags' ]->inject_google_tag_manager();
		} );
	}

	private function nav_attributes( Container &$container ) {
		$container[ 'theme.nav.attribute_filters' ] = function ( Container $container ) {
			return new Nav_Attribute_Filters();
		};

		add_filter( 'nav_menu_item_id', function ( $menu_id, $item, $args, $depth ) use ( $container ) {
			return $container[ 'theme.nav.attribute_filters' ]->customize_nav_item_id( $menu_id, $item, $args, $depth );
		}, 10, 4 );

		add_filter( 'nav_menu_css_class', function ( $classes, $item, $args, $depth ) use ( $container ) {
			return $container[ 'theme.nav.attribute_filters' ]->customize_nav_item_classes( $classes, $item, $args, $depth );
		}, 10, 4 );

		add_filter( 'nav_menu_link_attributes', function ( $atts, $item, $args, $depth ) use ( $container ) {
			return $container[ 'theme.nav.attribute_filters' ]->customize_nav_item_anchor_atts( $atts, $item, $args, $depth );
		}, 10, 4 );

		add_filter( 'walker_nav_menu_start_el', function ( $item_output, $item, $depth, $args ) use ( $container ) {
			return $container[ 'theme.nav.attribute_filters' ]->customize_nav_item_start_el( $item_output, $item, $depth, $args );
		}, 10, 4 );
	}

	private function gravity_forms( Container $container ) {
		$container[ 'theme.gravity_forms_filter' ] = function ( Container $container ) {
			return new Gravity_Forms_Filter();
		};
		add_action( 'init', function () use ( $container ) {
			$container[ 'theme.gravity_forms_filter' ]->hook();
		}, 10, 0 );
	}

	private function search( Container $container ) {
		$container[ 'theme.search' ] = function ( Container $container ) {
			return new Search();
		};

		add_filter( 'template_include', function ( $template ) use ( $container ) {
			return $container[ 'theme.search' ]->route_to_proper_search( $template );
		}, 10, 1 );

		add_action( 'pre_get_posts', function ( $query ) use ( $container ) {
			$container[ 'theme.search' ]->customize_search_query( $query );
		}, 10, 1 );
	}

	private function dealer_locator( Container $container ) {
		$container[ 'theme.dealer_locator' ] = function ( Container $container ) {
			return new Dealer_Locator();
		};

		add_filter( 'bh_sl_featured_img', function() use ( $container ) {
			return $container[ 'theme.dealer_locator' ]->customize_image_size();
		}, 10 );

		add_action( 'wp_footer', function() use ( $container ) {
			$container[ 'theme.dealer_locator' ]->add_locator_category_filter_script();
		} );

		add_filter( 'bh_sl_page_check', function ( $check ) use ( $container ) {
			return $container[ 'theme.dealer_locator' ]->remove_locator_scripts_styles( $check );
		}, 10, 1 );

		add_filter( 'option_bh_storelocator_style_options', function( $value, $option ) use ( $container ) {
			return $container[ 'theme.dealer_locator' ]->set_locator_map_styles( $value );
		}, 10, 2 );

		add_action( 'wp_enqueue_scripts', function() use ( $container ) {
			$container[ 'theme.dealer_locator' ]->remove_locator_styles();
		}, 20 );

		add_action( 'template_redirect', function() use ( $container ) {
			$container[ 'theme.dealer_locator' ]->kill_locator_templates();
		}, 10 );

		add_filter( 'bh_sl_location_data', function( $location_data ) use ( $container ) {
			return $container[ 'theme.dealer_locator' ]->filter_location_map_data( $location_data );
		}, 10, 2 );

		add_action( 'init', function () use ( $container ) {
			remove_shortcode('cardinal-storelocator' );
			add_shortcode( 'cardinal-storelocator', [ $container['theme.dealer_locator'], 'bh_storelocator_shortcode' ] );
		}, 20 );
	}

	private function sidebars( Container $container ) {
		$container[ 'theme.sidebars' ] = function ( Container $container ) {
			return new Sidebars();
		};

		add_filter( 'widgets_init', function () use ( $container ) {
			$container[ 'theme.sidebars' ]->register_sidebars();
		}, 10 );
	}

	private function single_instruments( Container $container ) {
		$container[ 'theme.single-instruments' ] = function( Container $container ) {
			return new Single_Instruments();
		};
	}
}
