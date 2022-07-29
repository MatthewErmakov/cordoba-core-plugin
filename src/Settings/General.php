<?php
namespace Tribe\Project\Settings;

use Tribe\Libs\ACF;
use Tribe\Project\Post_Types;

class General extends Contracts\ACF_Settings {
	const NAME = 'general_settings';

	const TAB_SOCIAL             = 'tab_social';
	const ORGANIZATION_FACEBOOK  = 'organization_facebook';
	const ORGANIZATION_TWITTER   = 'organization_twitter';
	const ORGANIZATION_INSTAGRAM = 'organization_instagram';
	const ORGANIZATION_YOUTUBE   = 'organization_youtube';
	const ORGANIZATION_VIMEO     = 'organization_vimeo';

	const TAB_HEADER                                 = 'tab_header';
	const HEADER_NAV_GUITAR                          = 'header_nav_item_guitar';
	const HEADER_NAV_UKULELE                         = 'header_nav_item_ukulele';
	const HEADER_NAV_CUSTOM                          = 'header_nav_item_custom';
	const HEADER_NAV_CUSTOM_LINK                     = 'header_nav_item_custom_link';
	const HEADER_NAV_INSTRUMENT_ITEM_IMAGE_NON_HOVER = 'image_non_hover';
	const HEADER_NAV_INSTRUMENT_ITEM_IMAGE_ON_HOVER  = 'image_on_hover';

	const TAB_FOOTER                = 'tab_footer';
	const FOOTER_ITEM_WHERE_TO_BUY  = 'footer_where_to_buy';
	const FOOTER_ITEM_CONTACT_US    = 'footer_contact_us';
	const FOOTER_ITEM_STAY_IN_TOUCH = 'footer_stay_in_touch';

	const TAB_SITE_TAGS = 'tab_site_tags';
	const ID_GTM        = 'id_google_tag_manager';

	const TAB_HERO    = 'tab_hero';
	const HERO_IMAGES = 'hero_images';

	const TAB_BLOG      = 'tab_blog';
	const BLOG_FEATURED = 'blog_featured';

	const TAB_ARCHIVES                 = 'tab_archives';
	const ARCHIVES_GUITAR              = 'archives_guitar';
	const ARCHIVES_GUITAR_DESCRIPTION  = 'archives_guitar_description';
	const ARCHIVES_GUITAR_IMAGE        = 'archives_guitar_image';
	const ARCHIVES_UKULELE             = 'archives_ukulele';
	const ARCHIVES_UKULELE_DESCRIPTION = 'archives_ukulele_description';
	const ARCHIVES_UKULELE_IMAGE       = 'archives_ukulele_image';

	public function get_title() {
		return __( 'General Settings', 'tribe' );
	}

	public function get_capability() {
		return 'manage_options';
	}

	public function get_parent_slug() {
		return 'options-general.php';
	}

	/**
	 * Adds the settings group
	 */
	public function register_fields() {
		acf_add_local_field_group( $this->get_settings_group() );
	}

	private function get_settings_group() {
		$key   = self::NAME;
		$group = new ACF\Group( $key );
		$group->set_attributes( [
			'title'      => __( 'General Settings', 'tribe' ),
			'location'   => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => $this->slug,
					],
				],
			],
		] );

		$group->add_field( $this->get_header_group_tab() );
		$group->add_field( $this->get_header_nav_guitar_field() );
		$group->add_field( $this->get_header_nav_ukulele_field() );
		$group->add_field( $this->get_header_nav_custom_field() );

		$group->add_field( $this->get_hero_group_tab() );
		$group->add_field( $this->get_hero_images_field() );

		$group->add_field( $this->get_blog_group_tab() );
		$group->add_field( $this->get_blog_featured_field() );

		$group->add_field( $this->get_archives_group_tab() );
		$group->add_field( $this->get_archives_guitar_field() );
		$group->add_field( $this->get_archives_ukulele_field() );

		$group->add_field( $this->get_footer_group_tab() );
		$group->add_field( $this->get_footer_where_to_buy_field() );
		$group->add_field( $this->get_footer_contact_us_field() );
		$group->add_field( $this->get_footer_stay_in_touch_field() );

		$group->add_field( $this->get_social_group_tab() );
		$group->add_field( $this->get_social_facebook_field() );
		$group->add_field( $this->get_social_twitter_field() );
		$group->add_field( $this->get_social_instagram_field() );
		$group->add_field( $this->get_social_youtube_field() );
		$group->add_field( $this->get_social_vimeo_field() );

		$group->add_field( $this->get_site_tags_group_tab() );
		$group->add_field( $this->get_site_tags_gtm_field() );

		return $group->get_attributes();
	}

	private function get_header_group_tab() {
		$field = new ACF\Field( self::NAME . '_' . self::TAB_HEADER );
		$field->set_attributes( [
			'label'     => __( 'Header & Navigation', 'tribe' ),
			'name'      => self::TAB_HEADER,
			'type'      => 'tab',
			'placement' => 'left',
		] );
		return $field;
	}

	private function get_header_nav_guitar_field() {
		$repeater = new ACF\Repeater( self::NAME . '_' . self::HEADER_NAV_GUITAR );
		$repeater->set_attributes( [
			'label'    => __( 'Guitars Navigation Item Data', 'tribe' ),
			'name'     => static::HEADER_NAV_GUITAR,
			'required' => 1,
			'min'      => 1,
			'max'      => 1,
			'layout'   => 'row',
		] );
		$image_non_hover = new ACF\Field( self::NAME . '_' . self::HEADER_NAV_GUITAR . '_' . self::HEADER_NAV_INSTRUMENT_ITEM_IMAGE_NON_HOVER );
		$image_non_hover->set_attributes( [
			'label'         => __( 'Not Hovered Image', 'tribe' ),
			'name'          => static::HEADER_NAV_INSTRUMENT_ITEM_IMAGE_NON_HOVER,
			'type'          => 'image',
			'instructions'  => __( 'Optimal image size: 600 x 364. Recommend centering the instrument horizontally and then also filling vertical space, all on a transparent background.', 'tribe' ),
			'required'      => 1,
			'return_format' => 'id',
			'mime_types'    => 'png, jpeg, jpg',
		] );
		$repeater->add_field( $image_non_hover );
		$image_on_hover = new ACF\Field( self::NAME . '_' . self::HEADER_NAV_GUITAR . '_' . self::HEADER_NAV_INSTRUMENT_ITEM_IMAGE_ON_HOVER );
		$image_on_hover->set_attributes( [
			'label'         => __( 'Hovered Image', 'tribe' ),
			'name'          => static::HEADER_NAV_INSTRUMENT_ITEM_IMAGE_ON_HOVER,
			'type'          => 'image',
			'instructions'  => __( 'Optimal image size: 490 x 540.', 'tribe' ),
			'required'      => 1,
			'return_format' => 'id',
			'mime_types'    => 'png, jpeg, jpg',
		] );
		$repeater->add_field( $image_on_hover );
		return $repeater;
	}

	private function get_header_nav_ukulele_field() {
		$repeater = new ACF\Repeater( self::NAME . '_' . self::HEADER_NAV_UKULELE );
		$repeater->set_attributes( [
			'label'    => __( 'Ukuleles Navigation Item Data', 'tribe' ),
			'name'     => static::HEADER_NAV_UKULELE,
			'required' => 1,
			'min'      => 1,
			'max'      => 1,
			'layout'   => 'row',
		] );
		$image_non_hover = new ACF\Field( self::NAME . '_' . self::HEADER_NAV_UKULELE . '_' . self::HEADER_NAV_INSTRUMENT_ITEM_IMAGE_NON_HOVER );
		$image_non_hover->set_attributes( [
			'label'         => __( 'Not Hovered Image', 'tribe' ),
			'name'          => static::HEADER_NAV_INSTRUMENT_ITEM_IMAGE_NON_HOVER,
			'type'          => 'image',
			'instructions'  => __( 'Optimal image size: 600 x 364. Recommend centering the instrument horizontally and then also filling vertical space, all on a transparent background.', 'tribe' ),
			'required'      => 1,
			'return_format' => 'id',
			'mime_types'    => 'png, jpeg, jpg',
		] );
		$repeater->add_field( $image_non_hover );
		$image_on_hover = new ACF\Field( self::NAME . '_' . self::HEADER_NAV_UKULELE . '_' . self::HEADER_NAV_INSTRUMENT_ITEM_IMAGE_ON_HOVER );
		$image_on_hover->set_attributes( [
			'label'         => __( 'Hovered Image', 'tribe' ),
			'name'          => static::HEADER_NAV_INSTRUMENT_ITEM_IMAGE_ON_HOVER,
			'type'          => 'image',
			'instructions'  => __( 'Optimal image size: 490 x 540.', 'tribe' ),
			'required'      => 1,
			'return_format' => 'id',
			'mime_types'    => 'png, jpeg, jpg',
		] );
		$repeater->add_field( $image_on_hover );
		return $repeater;
	}

	private function get_header_nav_custom_field() {
		$repeater = new ACF\Repeater( self::NAME . '_' . self::HEADER_NAV_CUSTOM );
		$repeater->set_attributes( [
			'label'    => __( 'Custom Navigation Item Data', 'tribe' ),
			'name'     => static::HEADER_NAV_CUSTOM,
			'required' => 1,
			'min'      => 1,
			'max'      => 1,
			'layout'   => 'row',
		] );
		$link = new ACF\Field( self::NAME . '_' . self::HEADER_NAV_CUSTOM . '_' . self::HEADER_NAV_CUSTOM_LINK );
		$link->set_attributes( [
			'label'         => __( 'Link', 'tribe' ),
			'name'          => static::HEADER_NAV_CUSTOM_LINK,
			'type'          => 'post_object',
			'required'      => 1,
			'post_type'     => [
				0 => Post_Types\Page\Page::NAME,
			],
			'taxonomy'      => [],
			'allow_null'    => 0,
			'multiple'      => 0,
			'return_format' => 'id',
			'ui'            => 1,
		] );
		$repeater->add_field( $link );
		$image_non_hover = new ACF\Field( self::NAME . '_' . self::HEADER_NAV_CUSTOM . '_' . self::HEADER_NAV_INSTRUMENT_ITEM_IMAGE_NON_HOVER );
		$image_non_hover->set_attributes( [
			'label'         => __( 'Not Hovered Image', 'tribe' ),
			'name'          => static::HEADER_NAV_INSTRUMENT_ITEM_IMAGE_NON_HOVER,
			'type'          => 'image',
			'instructions'  => __( 'Optimal image size: 600 x 364. Recommend centering the instrument horizontally and then also filling vertical space, all on a transparent background.', 'tribe' ),
			'required'      => 1,
			'return_format' => 'id',
			'mime_types'    => 'png, jpeg, jpg',
		] );
		$repeater->add_field( $image_non_hover );
		$image_on_hover = new ACF\Field( self::NAME . '_' . self::HEADER_NAV_CUSTOM . '_' . self::HEADER_NAV_INSTRUMENT_ITEM_IMAGE_ON_HOVER );
		$image_on_hover->set_attributes( [
			'label'         => __( 'Hovered Image', 'tribe' ),
			'name'          => static::HEADER_NAV_INSTRUMENT_ITEM_IMAGE_ON_HOVER,
			'type'          => 'image',
			'instructions'  => __( 'Optimal image size: 490 x 540.', 'tribe' ),
			'required'      => 1,
			'return_format' => 'id',
			'mime_types'    => 'png, jpeg, jpg',
		] );
		$repeater->add_field( $image_on_hover );
		return $repeater;
	}

	private function get_hero_group_tab() {
		$field = new ACF\Field( self::NAME . '_' . self::TAB_HERO );
		$field->set_attributes( [
			'label'     => __( 'Hero', 'tribe' ),
			'name'      => self::TAB_HERO,
			'type'      => 'tab',
			'placement' => 'left',
		] );
		return $field;
	}

	private function get_hero_images_field() {
		$field = new ACF\Field( self::NAME . '_' . self::HERO_IMAGES );
		$field->set_attributes( [
			'label'        => __( 'Hero Images', 'tribe' ),
			'name'         => self::HERO_IMAGES,
			'type'         => 'gallery',
			'instructions' => __( 'Used at random to power the hero background image. Optimal image size: 2000 x 485; recommend featuring the focus of the image towards the center.' ),
			'required'     => 1,
			'min'          => 1,
			'max'          => 6,
			'mime_types'   => 'png, jpeg, jpg',
		] );
		return $field;
	}

	private function get_blog_group_tab() {
		$field = new ACF\Field( self::NAME . '_' . self::TAB_BLOG );
		$field->set_attributes( [
			'label'     => __( 'Blog', 'tribe' ),
			'name'      => self::TAB_SOCIAL,
			'type'      => 'tab',
			'placement' => 'left',
		] );
		return $field;
	}

	private function get_blog_featured_field() {
		$field = new ACF\Field( self::NAME . '_' . self::BLOG_FEATURED );
		$field->set_attributes( [
			'label'         => __( 'Featured Post', 'tribe' ),
			'name'          => self::BLOG_FEATURED,
			'type'          => 'post_object',
			'post_type'     => [
				0 => Post_Types\Post\Post::NAME,
			],
			'taxonomy'      => [],
			'allow_null'    => 1,
			'multiple'      => 0,
			'return_format' => 'object',
			'ui'            => 1,
		] );
		return $field;
	}

	private function get_archives_group_tab() {
		$field = new ACF\Field( self::NAME . '_' . self::TAB_ARCHIVES );
		$field->set_attributes( [
			'label'     => __( 'Archives', 'tribe' ),
			'name'      => self::TAB_ARCHIVES,
			'type'      => 'tab',
			'placement' => 'left',
		] );
		return $field;
	}

	private function get_archives_guitar_field() {
		$repeater = new ACF\Repeater( self::NAME . '_' . self::ARCHIVES_GUITAR );
		$repeater->set_attributes( [
			'label'    => __( 'Guitars Archive Data', 'tribe' ),
			'name'     => static::ARCHIVES_GUITAR,
			'required' => 1,
			'min'      => 1,
			'max'      => 1,
			'layout'   => 'row',
		] );
		$description = new ACF\Field( self::NAME . '_' . self::ARCHIVES_GUITAR . '_' . self::ARCHIVES_GUITAR_DESCRIPTION );
		$description->set_attributes( [
			'label'     => __( 'Description', 'tribe' ),
			'name'      => static::ARCHIVES_GUITAR_DESCRIPTION,
			'type'      => 'textarea',
			'maxlength' => 200,
			'rows'      => 3,
			'new_lines' => '',
			'required'  => 1,
		] );
		$repeater->add_field( $description );
		$image = new ACF\Field( self::NAME . '_' . self::ARCHIVES_GUITAR . '_' . self::ARCHIVES_GUITAR_IMAGE );
		$image->set_attributes( [
			'label'         => __( 'Featured Image', 'tribe' ),
			'name'          => static::ARCHIVES_GUITAR_IMAGE,
			'type'          => 'image',
			'instructions'  => __( 'Optimal image size: minimum of 2000 wide; add 120 of height to handle site header overlap. Recommend featuring the focus of the image towards the bottom.', 'tribe' ),
			'return_format' => 'id',
			'mime_types'    => 'png, jpeg, jpg',
		] );
		$repeater->add_field( $image );
		return $repeater;
	}

	private function get_archives_ukulele_field() {
		$repeater = new ACF\Repeater( self::NAME . '_' . self::ARCHIVES_UKULELE );
		$repeater->set_attributes( [
			'label'    => __( 'Ukuleles Archive Data', 'tribe' ),
			'name'     => static::ARCHIVES_UKULELE,
			'required' => 1,
			'min'      => 1,
			'max'      => 1,
			'layout'   => 'row',
		] );
		$description = new ACF\Field( self::NAME . '_' . self::ARCHIVES_UKULELE . '_' . self::ARCHIVES_UKULELE_DESCRIPTION );
		$description->set_attributes( [
			'label'     => __( 'Description', 'tribe' ),
			'name'      => static::ARCHIVES_UKULELE_DESCRIPTION,
			'type'      => 'textarea',
			'maxlength' => 200,
			'rows'      => 3,
			'new_lines' => '',
			'required'  => 1,
		] );
		$repeater->add_field( $description );
		$image = new ACF\Field( self::NAME . '_' . self::ARCHIVES_UKULELE . '_' . self::ARCHIVES_UKULELE_IMAGE );
		$image->set_attributes( [
			'label'         => __( 'Featured Image', 'tribe' ),
			'name'          => static::ARCHIVES_UKULELE_IMAGE,
			'type'          => 'image',
			'instructions'  => __( 'Optimal image size: minimum of 2000 wide; add 120 of height to handle site header overlap. Recommend featuring the focus of the image towards the bottom.', 'tribe' ),
			'return_format' => 'id',
			'mime_types'    => 'png, jpeg, jpg',
		] );
		$repeater->add_field( $image );
		return $repeater;
	}

	private function get_footer_group_tab() {
		$field = new ACF\Field( self::NAME . '_' . self::TAB_FOOTER );
		$field->set_attributes( [
			'label'     => __( 'Footer', 'tribe' ),
			'name'      => self::TAB_FOOTER,
			'type'      => 'tab',
			'placement' => 'left',
		] );
		return $field;
	}

	private function get_footer_where_to_buy_field() {
		$field = new ACF\Field( self::NAME . '_' . self::FOOTER_ITEM_WHERE_TO_BUY );
		$field->set_attributes( [
			'label'          => __( 'Where to Buy Page', 'tribe' ),
			'name'           => self::FOOTER_ITEM_WHERE_TO_BUY,
			'type'           => 'page_link',
			'post_type'      => [
				0 => Post_Types\Page\Page::NAME,
			],
		] );
		return $field;
	}

	private function get_footer_contact_us_field() {
		$field = new ACF\Field( self::NAME . '_' . self::FOOTER_ITEM_CONTACT_US );
		$field->set_attributes( [
			'label'          => __( 'Contact Us Page', 'tribe' ),
			'name'           => self::FOOTER_ITEM_CONTACT_US,
			'type'           => 'page_link',
			'post_type'      => [
				0 => Post_Types\Page\Page::NAME,
			],
		] );
		return $field;
	}

	private function get_footer_stay_in_touch_field() {
		$field = new ACF\Field( self::NAME . '_' . self::FOOTER_ITEM_STAY_IN_TOUCH );
		$field->set_attributes( [
			'label'          => __( 'Stay in Touch Page', 'tribe' ),
			'name'           => self::FOOTER_ITEM_STAY_IN_TOUCH,
			'type'           => 'page_link',
			'post_type'      => [
				0 => Post_Types\Page\Page::NAME,
			],
		] );
		return $field;
	}

	private function get_social_group_tab() {
		$field = new ACF\Field( self::NAME . '_' . self::TAB_SOCIAL );
		$field->set_attributes( [
			'label'     => __( 'Social', 'tribe' ),
			'name'      => self::TAB_SOCIAL,
			'type'      => 'tab',
			'placement' => 'left',
		] );
		return $field;
	}

	private function get_social_facebook_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_FACEBOOK );
		$field->set_attributes( [
			'label'     => __( 'Facebook URL', 'tribe' ),
			'name'      => self::ORGANIZATION_FACEBOOK,
			'type'      => 'url',
		] );
		return $field;
	}

	private function get_social_twitter_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_TWITTER );
		$field->set_attributes( [
			'label'     => __( 'Twitter URL', 'tribe' ),
			'name'      => self::ORGANIZATION_TWITTER,
			'type'      => 'url',
		] );
		return $field;
	}

	private function get_social_instagram_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_INSTAGRAM );
		$field->set_attributes( [
			'label'     => __( 'Instagram URL', 'tribe' ),
			'name'      => self::ORGANIZATION_INSTAGRAM,
			'type'      => 'url',
		] );
		return $field;
	}

	private function get_social_youtube_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_YOUTUBE );
		$field->set_attributes( [
			'label'     => __( 'YouTube URL', 'tribe' ),
			'name'      => self::ORGANIZATION_YOUTUBE,
			'type'      => 'url',
		] );
		return $field;
	}

	private function get_social_vimeo_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ORGANIZATION_VIMEO );
		$field->set_attributes( [
			'label'     => __( 'Vimeo URL', 'tribe' ),
			'name'      => self::ORGANIZATION_VIMEO,
			'type'      => 'url',
		] );
		return $field;
	}

	private function get_site_tags_group_tab() {
		$field = new ACF\Field( self::NAME . '_' . self::TAB_SITE_TAGS );
		$field->set_attributes( [
			'label'     => __( 'Site Tags', 'tribe' ),
			'name'      => self::TAB_SITE_TAGS,
			'type'      => 'tab',
			'placement' => 'left',
		] );
		return $field;
	}

	private function get_site_tags_gtm_field() {
		$field = new ACF\Field( self::NAME . '_' . self::ID_GTM );
		$field->set_attributes( [
			'label'       => __( 'Google Tag Manager ID', 'tribe' ),
			'name'        => self::ID_GTM,
			'type'        => 'text',
			'placeholder' => __( 'Enter Google Tag Manager ID', 'tribe' ),
		] );
		return $field;
	}

	/**
	 * @return General
	 */
	public static function instance() {
		return tribe_project()->container()['settings.general'];
	}

}
