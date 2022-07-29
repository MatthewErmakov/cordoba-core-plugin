<?php

namespace Tribe\Project\Theme;

class Image_Wrap {

	/**
	* Customize WP non-captioned image output
	* TODO: @backend code review this
	*
	* @param $html
	*
	* @return mixed
	* @filter the_content
	*/
	public function customize_wp_image_non_captioned_output( $html ) {
		if ( ! is_singular() && ! in_the_loop() && ! is_main_query() ) {
			return $html;
        }

        return preg_replace_callback( '/<p>((?:.(?!p>))*?)(<a[^>]*>)?\s*(<img[^>]+>)(<\/a>)?(.*?)<\/p>/is', function( $matches ) {
        	/*
 			Groups 	Regex 			 Description
 					<p>|<figure>     starting <p> or <figure> tag
 			1	    ((?:.(?!p>))*?)	 match 0 or more of anything not followed by p>
 					.(?!p>) 		 anything that's not followed by p>
 					?: 			     non-capturing group.
 					*?		         match the ". modified by p> condition" expression non-greedily
 			2	    (<a[^>]*>)?		 starting <a> tag (optional)
 					\s*			     white space (optional)
 			3	    (<img[^>]+>)	 <img> tag
 					\s*			     white space (optional)
 			4	    (<\/a>)? 		 ending </a> tag (optional)
 			5	    (.*?)<\/p>		 everything up to the final </p>
 					i modifier 		 case insensitive
					s modifier		 allows . to match multiple lines (important for 1st and 5th group)
 			*/

        	// hande and setup alignment class
	        $alignment = 'alignnone';
	        $dom = new \DOMDocument();
        	$dom->loadHTML( $matches[3], LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
        	$image = $dom->getElementsByTagName( 'img' )->item( 0 );
        	$classes = explode( ' ', $image->getAttribute( 'class' ) );
        	foreach( $classes as $class ) {
        		if( in_array( $class, [ 'alignnone', 'alignleft', 'aligncenter', 'alignright' ] ) ) {
        			$alignment = $class;
        			unset( $classes[ array_search( $class, $classes ) ] );
		        }
	        }
	        $image->setAttribute( 'class', implode( ' ', $classes ) );
	        $html = $dom->saveHTML();

        	// image and (optional) link: <a ...><img ...></a>
			$image = $matches[2] . '<div class="wp-image--decor">' . $html . '</div>' . $matches[4];

			// content before and after image. wrap in <p> unless it's empty
			$content = trim( $matches[1] . $matches[5] );
			if ( $content ) {
				$content = '<p>' . $content . '</p>';
			}

	        // move alignment classes to our non-caption image wrapper & remove from image
            // mimicks markup for captioned images
            //preg_match( '#class\s*=\s*"[^"]*(alignnone|alignleft|aligncenter|alignright)[^"]*"#', $image, $alignment_match );
            //$alignment = empty( $alignment_match[1] ) ? 'alignnone' : $alignment_match[1];

            //$image = empty( $alignment_match[1] ) ? $image : str_replace( $alignment_match[1] . ' ', '', $image );

	        return sprintf( '<figure class="wp-image wp-image--no-caption %s">%s</figure>%s', esc_attr( $alignment ), $image, $content );
        }, $html );

 	}

	/**
	 * Modified core Shortcode handler from core. Builds the Caption shortcode output.
	 *
	 * Allows a plugin to replace the content that would otherwise be returned. The
	 * filter is {@see 'img_caption_shortcode'} and passes an empty string, the attr
	 * parameter and the content parameter values.
	 *
	 * The supported attributes for the shortcode are 'id', 'align', 'width', and
	 * 'caption'.
	 *
	 * @since 2.6.0
	 *
	 * @param array  $attr {
	 *     Attributes of the caption shortcode.
	 *
	 *     @type string $id      ID of the div element for the caption.
	 *     @type string $align   Class name that aligns the caption. Default 'alignnone'. Accepts 'alignleft',
	 *                           'aligncenter', alignright', 'alignnone'.
	 *     @type int    $width   The width of the caption, in pixels.
	 *     @type string $caption The caption text.
	 *     @type string $class   Additional class name(s) added to the caption container.
	 * }
	 * @param string $content Shortcode content.
	 * @return string HTML content to display the caption.
	 */
	public function caption( $attr, $content = null ) {
		// New-style shortcode with the caption inside the shortcode with the link and image tags.
		if ( ! isset( $attr['caption'] ) ) {
			if ( preg_match( '#((?:<a [^>]+>\s*)?<img [^>]+>(?:\s*</a>)?)(.*)#is', $content, $matches ) ) {
				//$content = $matches[1];
				$attr['caption'] = trim( $matches[2] );
			}
		} elseif ( strpos( $attr['caption'], '<' ) !== false ) {
			$attr['caption'] = wp_kses( $attr['caption'], 'post' );
		}

		// Customize and setup handling of breaking up content into parts
		$dom = new \DOMDocument();
		$dom->loadHTML( $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		$image = $dom->getElementsByTagName( 'img' )->item( 0 );
		$image_alt    = '';
		$image_class  = '';
		$image_width  = '';
		$image_height = '';
		if ( null !== $image ) {
			$image_url    = $image->getAttribute( 'src' );
			$image_alt    = $image->getAttribute( 'alt' );
			$image_class  = $image->getAttribute( 'class' );
			$image_width  = $image->getAttribute( 'width' );
			$image_height = $image->getAttribute( 'height' );
		}

		$anchor = $dom->getElementsByTagName( 'a' )->item( 0 );
		if ( null !== $anchor ) {
			$url = $anchor->getAttribute( 'href' );
		}

		if ( empty( $image ) && empty( $image_url ) ) {
			return $content;
		}

		/**
		 * Filters the default caption shortcode output.
		 *
		 * If the filtered output isn't empty, it will be used instead of generating
		 * the default caption template.
		 *
		 * @since 2.6.0
		 *
		 * @see img_caption_shortcode()
		 *
		 * @param string $output  The caption output. Default empty.
		 * @param array  $attr    Attributes of the caption shortcode.
		 * @param string $content The image element, possibly wrapped in a hyperlink.
		 */
		$output = apply_filters( 'img_caption_shortcode', '', $attr, $content );
		if ( $output != '' )
			return $output;

		$atts = shortcode_atts( array(
			'id'	  => '',
			'align'	  => 'alignnone',
			'width'	  => '',
			'caption' => '',
			'class'   => '',
		), $attr, 'caption' );

		$atts['width'] = (int) $atts['width'];
		if ( $atts['width'] < 1 || empty( $atts['caption'] ) )
			return $content;

		if ( ! empty( $atts['id'] ) )
			$atts['id'] = 'id="' . esc_attr( sanitize_html_class( $atts['id'] ) ) . '" ';

		$class = trim( 'wp-image wp-image--caption ' . $atts['align'] . ' ' . $atts['class'] );

		$html5 = current_theme_supports( 'html5', 'caption' );
		// HTML5 captions never added the extra 10px to the image width
		$width = $html5 ? $atts['width'] : ( 10 + $atts['width'] );

		/**
		 * Filters the width of an image's caption.
		 *
		 * By default, the caption is 10 pixels greater than the width of the image,
		 * to prevent post content from running up against a floated image.
		 *
		 * @since 3.7.0
		 *
		 * @see img_caption_shortcode()
		 *
		 * @param int    $width    Width of the caption in pixels. To remove this inline style,
		 *                         return zero.
		 * @param array  $atts     Attributes of the caption shortcode.
		 * @param string $content  The image element, possibly wrapped in a hyperlink.
		 */
		$caption_width = apply_filters( 'img_caption_shortcode_width', $width, $atts, $content );

		$style = '';
		if ( $caption_width ) {
			$style = 'style="width: ' . (int) $caption_width . 'px" ';
		}

		/*
		if ( $html5 ) {
			$html = '<figure ' . $atts['id'] . $style . 'class="' . esc_attr( $class ) . '">'
			. do_shortcode( $content ) . '<figcaption class="wp-caption-text">' . $atts['caption'] . '</figcaption></figure>';
		} else {
			$html = '<div ' . $atts['id'] . $style . 'class="' . esc_attr( $class ) . '">'
			. do_shortcode( $content ) . '<p class="wp-caption-text">' . $atts['caption'] . '</p></div>';
		}
		*/

		$html = '<figure ' . $atts['id'] . $style . 'class="' . esc_attr( $class ) . '">';

		$div = '<div class="wp-image--decor">';
		$div .= '<img src="' . esc_url( $image_url ) . '" class="'. $image_class .'" alt="'. $image_alt .'" width="'. $image_width .'" height="'. $image_height .'">';
		$div .= '</div>';

		if ( ! empty( $atts['caption'] ) ) {
			$div .= '<figcaption class="wp-caption-text">' . $atts['caption'] . '</figcaption>';
		}

		if ( ! empty( $url ) ) {
			$div = sprintf( '<a href="%1$s">' . $div . '</a>', esc_url( $url ) );
		}

		$html .= $div;
		$html .= '</figure>';

		return $html;
	}
}
