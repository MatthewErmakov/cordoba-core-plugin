<?php
namespace Tribe\Project\Post_Meta;

use Tribe\Libs\ACF\ACF_Meta_Group;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Group;

class Post_Meta extends ACF_Meta_Group {
	const NAME = 'post-meta';

	const FEATURED_IMAGE_OVERRIDE = 'feature_image_override';

	/**
	 * @return array
	 */
	public function get_keys() {
		return [
			self::FEATURED_IMAGE_OVERRIDE,
		];
	}

	/**
	 * @param int $post_id
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function get_value( $post_id, $key ) {
		return get_field( $key, $post_id );
	}

	/**
	 * Set group attributes
	 *
	 * @return array
	 */
	public function get_group_config() {
		$group = $this->create_group( self::NAME, esc_html__( 'Featured Image Override', 'tribe' ) );
		$group->add_field( $this->get_featured_image_override_field() );

		return $group->get_attributes();
	}

	/**
	 * @param string $name
	 * @param string $label
	 *
	 * @return Group
	 */
	public function create_group( string $name, string $label ) {
		$group_key = md5( $name );
		$group = new Group( $group_key );
		$group->set( 'title', $label );
		$group->set_post_types( $this->post_types );
		$group->set( 'position', 'side' );

		return $group;
	}

	/**
	 * @return Field
	 */
	public function get_featured_image_override_field() {
		$field = new Field( static::NAME . '_' . self::FEATURED_IMAGE_OVERRIDE );
		$field->set_attributes( [
			'label'         => '',
			'name'          => self::FEATURED_IMAGE_OVERRIDE,
			'type'          => 'image',
			'return_format' => 'id',
			'instructions'  => __( 'Optional image override to be used in the card grid panel and loops. Optimal image size: 1005 x 889.', 'tribe' ),
		] );

		return $field;
	}

}
