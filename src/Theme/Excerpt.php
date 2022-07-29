<?php

namespace Tribe\Project\Theme;

class Excerpt {

	/**
	 * Customize the excerpt length
	 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/excerpt_length
	 */
	public function customize_excerpt_length( $length ) {
		return 80;
	}

	/**
	 * Customize the excerpt more string
	 * @link http://codex.wordpress.org/Function_Reference/the_excerpt
	 */
	public function customize_excerpt_more( $more ) {
		return '&hellip;';
	}

}



