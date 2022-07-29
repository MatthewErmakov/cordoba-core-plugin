<?php


namespace Tribe\Project\Theme;


class Image_Sizes {

	const CORE_FULL                           = 'core-full';
	const CORE_MOBILE                         = 'core-mobile';
	const THUMB_5_8                           = '5x8';
	const THUMB_5_8_RETINA                    = '5x8-retina';
	const THUMB_16_9                          = '16x9';
	const THUMB_16_9_MOBILE                   = '16x9-mobile';
	const THUMB_16_9_RETINA                   = '16x9-retina';
	const THUMB_27_40                         = '27x40';
	const THUMB_27_40_RETINA                  = '27x40-retina';
	const THUMB_47_54                         = '47x54';
	const THUMB_47_54_MOBILE                  = '47x54-mobile';
	const THUMB_47_54_RETINA                  = '47x54-retina';
	const THUMB_335_216                       = '335x216';
	const THUMB_335_216_MOBILE                = '335x216-mobile';
	const THUMB_335_216_RETINA                = '335x216-retina';
	const THUMB_163_131                       = '163x131';
	const THUMB_163_131_MOBILE                = '163x131-mobile';
	const THUMB_163_131_RETINA                = '163x131-retina';
	const THUMB_1755_998                      = '1755x998';
	const THUMB_1755_998_RETINA               = '1755x998-retina';
	const THUMB_NAV_ITEM_INSTRUMENT_NON_HOVER = 'nav-item-instrument-non-hover';
	const THUMB_NAV_ITEM_INSTRUMENT_ON_HOVER  = 'nav-item-instrument-on-hover';
	const THUMB_INTERSTITIAL                  = 'interstitial';
	const THUMB_INTERSTITIAL_MOBILE           = 'interstitial-mobile';
	const THUMB_INTERSTITIAL_RETINA           = 'interstitial-retina';
	const THUMB_GALLERY                       = 'gallery';
	const THUMB_GALLERY_RETINA                = 'gallery-retina';
	const LOGO                                = 'logo';
	const SWATCH                              = 'swatch';
	const SOCIAL_SHARE                        = 'social-share';

	private $sizes = [
		self::CORE_FULL                           => [
			'width'  => 2000,
			'height' => 0,
			'crop'   => true,
		],
		self::CORE_MOBILE                         => [
			'width'  => 1200,
			'height' => 0,
			'crop'   => true,
		],
		self::SOCIAL_SHARE                        => [
			'width'  => 1200,
			'height' => 630,
			'crop'   => true,
		],
		self::THUMB_5_8                           => [
			'width'  => 400,
			'height' => 350,
			'crop'   => true,
		],
		self::THUMB_5_8_RETINA                    => [
			'width'  => 600,
			'height' => 525,
			'crop'   => true,
		],
		self::THUMB_16_9                          => [
			'width'  => 750,
			'height' => 422,
			'crop'   => true,
		],
		self::THUMB_16_9_MOBILE                   => [
			'width'  => 1152,
			'height' => 648,
			'crop'   => true,
		],
		self::THUMB_27_40                         => [
			'width'  => 300,
			'height' => 444,
			'crop'   => true,
		],
		self::THUMB_27_40_RETINA                  => [
			'width'  => 600,
			'height' => 889,
			'crop'   => true,
		],
		self::THUMB_47_54                         => [
			'width'  => 470,
			'height' => 540,
			'crop'   => true,
		],
		self::THUMB_47_54_MOBILE                  => [
			'width'  => 1152,
			'height' => 1324,
			'crop'   => true,
		],
		self::THUMB_47_54_RETINA                  => [
			'width'  => 705,
			'height' => 810,
			'crop'   => true,
		],
		self::THUMB_335_216                       => [
			'width'  => 670,
			'height' => 432,
			'crop'   => true,
		],
		self::THUMB_335_216_MOBILE                => [
			'width'  => 1152,
			'height' => 743,
			'crop'   => true,
		],
		self::THUMB_335_216_RETINA                => [
			'width'  => 1005,
			'height' => 648,
			'crop'   => true,
		],
		self::THUMB_163_131                       => [
			'width'  => 815,
			'height' => 655,
			'crop'   => true,
		],
		self::THUMB_163_131_MOBILE                => [
			'width'  => 1152,
			'height' => 926,
			'crop'   => true,
		],
		self::THUMB_163_131_RETINA                => [
			'width'  => 1222,
			'height' => 982,
			'crop'   => true,
		],
		self::THUMB_NAV_ITEM_INSTRUMENT_NON_HOVER => [
			'width'  => 600,
			'height' => 364,
			'crop'   => true,
		],
		self::THUMB_NAV_ITEM_INSTRUMENT_ON_HOVER  => [
			'width'  => 490,
			'height' => 540,
			'crop'   => true,
		],
		self::THUMB_INTERSTITIAL                  => [
			'width'  => 1440,
			'height' => 630,
			'crop'   => false,
		],
		self::THUMB_INTERSTITIAL_MOBILE           => [
			'width'  => 1152,
			'height' => 504,
			'crop'   => false,
		],
		self::THUMB_INTERSTITIAL_RETINA           => [
			'width'  => 2000,
			'height' => 875,
			'crop'   => false,
		],
		self::THUMB_GALLERY                       => [
			'width'  => 800,
			'height' => 2000,
			'crop'   => false,
		],
		self::THUMB_GALLERY_RETINA                => [
			'width'  => 1200,
			'height' => 3000,
			'crop'   => false,
		],
		self::LOGO                                => [
			'width'  => 300,
			'height' => 0,
			'crop'   => false,
		],
		self::THUMB_1755_998                         => [
			'width'  => 1170,
			'height' => 665,
			'crop'   => true,
		],
		self::THUMB_1755_998_RETINA                  => [
			'width'  => 1755,
			'height' => 998,
			'crop'   => true,
		],
		self::SWATCH => [
			'width'  => 50,
			'height' => 50,
			'crop'   => true,
		],
	];

	private $opengraph_image_size = self::SOCIAL_SHARE;

	/**
	 * @return void
	 * @action after_setup_theme
	 */
	public function register_sizes() {
		foreach ( $this->sizes as $key => $attributes ) {
			add_image_size( $key, $attributes[ 'width' ], $attributes[ 'height' ], $attributes[ 'crop' ] );
		}
	}

	/**
	 * @param $size
	 * @return string
	 * @filter wpseo_opengraph_image_size
	 */
	public function customize_wpseo_image_size( $size ) {
		return $this->opengraph_image_size;
	}
}
