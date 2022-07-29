<?php
namespace Tribe\Project\Rest_Api;

class Initial_Data {

	/**
	 * Gets global posts data from the JSON API server
	 *
	 * @param null $posts
	 *
	 * @return array
	 */
	public function get_post_data( $posts = null ){
		if( $posts === null && !is_404() ){
			global $wp_query;
			$posts = $wp_query->posts;
		}
		global $wp_rest_server;
		if( empty( $wp_rest_server ) ){
			$wp_rest_server_class = apply_filters( 'wp_rest_server_class', 'WP_REST_Server' );
			$wp_rest_server       = new $wp_rest_server_class;
			do_action( 'rest_api_init' );
		}
		$data                 = [];
		$request              = new \WP_REST_Request();
		$request[ 'context' ] = 'view';

		foreach( (array) $posts as $key => $post ){
			$controller = new \WP_REST_Posts_Controller( $post->post_type );
			$data[]     = $wp_rest_server->response_to_data( $controller->prepare_item_for_response( $post, $request ), true );
		}

		return $data;
	}

	/**
	 *
	 * @static
	 *
	 * @return Initial_Data
	 */
	public static function instance(){
		return tribe_project()->container()[ 'rest-api.initial_data' ];
	}
}