<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package _s
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 *
 * function _s_body_classes( $classes ) {
 * // Adds a class of hfeed to non-singular pages.
 * if ( ! is_singular() ) {
 * $classes[] = 'hfeed';
 * }
 *
 * return $classes;
 * }
 * add_filter( 'body_class', '_s_body_classes' );
 */

/**
 * Remove hentry class
 */
function _s_remove_hentry( $classes ) {
	$classes = array_diff( $classes, array( 'hentry' ) );

	return $classes;
}

add_filter( 'post_class', '_s_remove_hentry' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function _s_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}

add_action( 'wp_head', '_s_pingback_header' );

/**
 * Change the default text after an excerpt
 */
function _s_excerpt_more( $more ) {
	return '...';
}

add_filter( 'excerpt_more', '_s_excerpt_more' );

/**
 * Limit the excerpt length
 */
function _s_excerpt_length( $length ) {
	return 25;
}

add_filter( 'excerpt_length', '_s_excerpt_length' );

/**
 * Return a responsive image tag without the cropped images from a wp image array
 */
function _s_lazy_image( $img_arr, $default = null, $classes = null, $fit = null ) {
	if ( ! is_array( $img_arr ) ) {
		return;
	}
	//Get a list of available image sizes
	$sizes = get_intermediate_image_sizes();
	//Remove thumbnail and medium which are always first
	unset( $sizes[0], $sizes[1] );

	$tag = '<img ';
	if ( isset( $default ) && isset( $img_arr['sizes'][ $default . '-width' ] ) ) {
		$tag .= 'data-src="' . $img_arr['sizes'][ $default ] . '" ' . "\n";
	} elseif ( isset( $default ) && isset( $img_arr['sizes'][ $default ]['url'] ) ) {
		$tag .= 'data-src="' . $img_arr['sizes'][ $default ]['url'] . '" ' . "\n";
	} else {
		$tag .= 'data-src="' . $img_arr['url'] . '" ' . "\n";
	}
	//Add a blank image on pageload
	$tag .= 'srcset="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" ' . "\n";
	//Now loop through the available sizes and add them with their widths, default first
	if ( isset( $default ) && isset( $img_arr['sizes'][ $default . '-width' ] ) ) {
		$tag .= 'data-srcset="' . $img_arr['sizes'][ $default ] . ' ' . $img_arr['sizes'][ $default . '-width' ] . 'w ' . $img_arr['sizes'][ $default . '-height' ] . 'h,' . "\n";
	} elseif ( isset( $default ) && isset( $img_arr['sizes'][ $default ]['width'] ) ) {
		$tag .= 'data-srcset="' . $img_arr['sizes'][ $default ]['url'] . ' ' . $img_arr['sizes'][ $default ]['width'] . 'w ' . $img_arr['sizes'][ $default ]['height'] . 'h,' . "\n";
	} else {
		$tag .= 'data-srcset="' . $img_arr['url'] . ' ' . $img_arr['width'] . 'w ' . $img_arr['height'] . 'h, ' . "\n";
	}
	foreach ( $sizes as $key => $size ) {
		//We only want to add a size if it's smaller than the original image
		if ( isset( $img_arr['sizes'][ $size . '-width' ] ) && $img_arr['sizes'][ $size . '-width' ] < $img_arr['width'] ) {
			$tag .= $img_arr['sizes'][ $size ] . ' ' . $img_arr['sizes'][ $size . '-width' ] . 'w, ' . "\n";
		} elseif ( isset( $img_arr['sizes'][ $size ]['width'] ) && $img_arr['sizes'][ $size ]['width'] < $img_arr['width'] ) {
			$tag .= $img_arr['sizes'][ $size ]['url'] . ' ' . $img_arr['sizes'][ $size ]['width'] . 'w, ' . "\n";
		}
	}
	//Trim off the last comma and close the quote
	$tag = rtrim( $tag, ",\n " ) . '" ' . "\n";
	//We want the plugin in auto mode so will hardcode this bit
	$tag .= 'data-sizes="auto" ' . "\n";
	//If object-fit is set we need a data att to support ie
	if ( isset( $fit ) && ( $fit === 'cover' || $fit === 'contain' ) ) {
		$tag     .= 'data-parent-fit="' . $fit . '"' . "\n";
		$classes = $classes . ' imagecontainer-img-' . $fit;
	}
	//Add the classes
	$tag .= $classes ? 'class="lazyload ' . $classes . '"' . "\n" : 'class="lazyload"' . "\n";
	//Add the alt
	$tag .= 'alt="' . $img_arr['alt'] . '"' . "\n";
	//Close the tag
	$tag .= ' />';

	return $tag;

}

/*
 * Function to convert img tags to make them lazyload
 */
function _s_replace_image_lazy( $content ) {

	if ( ! $content ) {
		return '';
	}

	// Start the dom object
	$dom                     = new DOMDocument();
	$dom->recover            = true;
	$dom->substituteEntities = true;

	// Feed the content to the dom object
	$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );

	foreach ( $dom->getElementsByTagName( 'img' ) as $img ) {

		$src   = $img->getAttribute( 'src' );
		$class = $img->getAttribute( 'class' );
		$class = $class . ' lazyload';

		// Swap them
		$img->removeAttribute( 'src' );
		$img->setAttribute( 'data-src', $src );
		$img->setAttribute( 'class', $class );
	}

	return $dom->saveHTML();
}

/*
 * Bootstrap comment form
 */
function _s_comment_form( $args ) {
	$args['comment_field'] = '<div class="form-group comment-form-comment">
  <label for="comment">' . _x( 'Comment', 'noun' ) . '</label>
  <textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
  </div>';
	$args['class_submit']  = 'btn btn-primary'; // since WP 4.1

	return $args;
}

add_filter( 'comment_form_defaults', '_s_comment_form' );

function _s_comment_form_fields( $fields ) {

	$fields['author'] = '<div class="form-group comment-form-author">' . '<label for="author">' . __( 'Name' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
	                    '<input class="form-control" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>';
	$fields['email']  = '<div class="form-group comment-form-email"><label for="email">' . __( 'Email' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
	                    '<input class="form-control" id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>';
	$fields['url']    = '<div class="form-group comment-form-url"><label for="url">' . __( 'Website' ) . '</label> ' .
	                    '<input class="form-control" id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></div>';

	$fields['cookies'] = '<div class=" form-group comment-form-cookies-consent form-check"><input id="wp-comment-cookies-consent" class="form-check-input" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
	                     '<label class="form-check-label" for="wp-comment-cookies-consent">' . __( 'Save my name, email, and website in this browser for the next time I comment.' ) . '</label></div>';

	return $fields;
}

add_filter( 'comment_form_default_fields', '_s_comment_form_fields' );

/**
 * Stuff to remove default code
 */

//Remove amp fonts
add_action( 'amp_post_template_head', function () {
	remove_action( 'amp_post_template_head', 'amp_post_template_add_fonts' );
}, 9 );

//Remove the generator tag
remove_action( 'wp_head', 'wp_generator' );

//Remove the frontend admin bar while in development
//add_filter('show_admin_bar', '__return_false');

//Remove shortlinks from head
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

//remove manifest link
//http://wpsmackdown.com/wordpress-cleanup-wp-head/
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

//Remove emoji frontend files added in 4.2
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
//Remove s.w.org prefetch link
add_filter( 'emoji_svg_url', '__return_false' );

//Disable the json api and remove the head link
//add_filter('rest_enabled', '__return_false');
add_filter( 'rest_jsonp_enabled', '__return_false' );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );

//oEmbed stuff
//Remove the REST API endpoint.
remove_action( 'rest_api_init', 'wp_oembed_register_route' );
//Turn off oEmbed auto discovery.
//Don't filter oEmbed results.
remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
//Remove oEmbed discovery links.
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
//Remove oEmbed-specific JavaScript from the front-end and back-end.
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

/**
 * Third party extensions that do annoying things
 */

//Remove All Yoast HTML Comments
//https://gist.github.com/paulcollett/4c81c4f6eb85334ba076
function go_yoast() {
	if ( defined( 'WPSEO_VERSION' ) ) {
		add_action( 'get_header', function () {
			ob_start( function ( $o ) {
				return preg_replace( '/\n?<.*?yoast.*?>/mi', '', $o );
			} );
		} );
		add_action( 'wp_head', function () {
			ob_end_flush();
		}, 999 );
	}
}

add_action( 'plugins_loaded', 'go_yoast' );

//Move the yoast seo stuff to the bottom of the admin pages
function yoasttobottom() {
	return 'low';
}

add_filter( 'wpseo_metabox_prio', 'yoasttobottom' );

//cf7
//add_filter( 'wpcf7_load_js', '__return_false' );
//add_filter( 'wpcf7_load_css', '__return_false' );


//Jetpack
// First, make sure Jetpack doesn't concatenate all its CSS
/*
add_filter( 'jetpack_implode_frontend_css', '__return_false' );
*/
// Then, remove each CSS file, one at a time
/*
function jeherve_remove_all_jp_css() {
	wp_deregister_style( 'AtD_style' ); // After the Deadline
	wp_deregister_style( 'jetpack_likes' ); // Likes
	wp_deregister_style( 'jetpack_related-posts' ); //Related Posts
	wp_deregister_style( 'jetpack-carousel' ); // Carousel
	wp_deregister_style( 'grunion.css' ); // Grunion contact form
	wp_deregister_style( 'the-neverending-homepage' ); // Infinite Scroll
	wp_deregister_style( 'infinity-twentyten' ); // Infinite Scroll - Twentyten Theme
	wp_deregister_style( 'infinity-twentyeleven' ); // Infinite Scroll - Twentyeleven Theme
	wp_deregister_style( 'infinity-twentytwelve' ); // Infinite Scroll - Twentytwelve Theme
	wp_deregister_style( 'noticons' ); // Notes
	wp_deregister_style( 'post-by-email' ); // Post by Email
	wp_deregister_style( 'publicize' ); // Publicize
	wp_deregister_style( 'sharedaddy' ); // Sharedaddy
	wp_deregister_style( 'sharing' ); // Sharedaddy Sharing
	wp_deregister_style( 'stats_reports_css' ); // Stats
	wp_deregister_style( 'jetpack-widgets' ); // Widgets
	wp_deregister_style( 'jetpack-slideshow' ); // Slideshows
	wp_deregister_style( 'presentations' ); // Presentation shortcode
	wp_deregister_style( 'jetpack-subscriptions' ); // Subscriptions
	wp_deregister_style( 'tiled-gallery' ); // Tiled Galleries
	wp_deregister_style( 'widget-conditions' ); // Widget Visibility
	wp_deregister_style( 'jetpack_display_posts_widget' ); // Display Posts Widget
	wp_deregister_style( 'gravatar-profile-widget' ); // Gravatar Widget
	wp_deregister_style( 'widget-grid-and-list' ); // Top Posts widget
	wp_deregister_style( 'jetpack-widgets' ); // Widgets
}
add_action('wp_print_styles', 'jeherve_remove_all_jp_css' );
*/