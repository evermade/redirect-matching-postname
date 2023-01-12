<?php
/**
 * Plugin Name:       Redirect Matching Postname
 * Plugin URI:        https://evermade.fi
 * Description:       Redirects 404s to the post with the same name as the requested URL.
 * Version:           1.0.0
 * Author:            Evermade
 * Author URI:        https://evermade.fi
 * License:           GPL-2.0-or-later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/evermade
 * Requires PHP:      7.4
 * Requires WP:       5.6
 */

function em_redirect_matching_postname() {

	if ( ! is_404() ) {
		return;
	}

	// validate request uri
	if ( empty( $_SERVER['REQUEST_URI'] ) ) {
		return;
	}

	// extract postname from request uri
	$request_uri = explode( '/', trailingslashit( strtok($_SERVER["REQUEST_URI"], '?') ) );

	// remove empty parts
	$request_uri = array_filter( $request_uri );

	// get last part
	$post_name   = end( $request_uri );

	// validate postname
	if ( empty( $post_name ) ) {
		return;
	}

	// get post by postname
	$query = new WP_Query(
		[
			'name'           => $post_name,
			'post_type'      => apply_filters( 'em_redirect_matching_postname/post_types', 'any' ),
			'post_status'    => 'publish',
			'posts_per_page' => 1,
		]
	);

	// validate post
	if ( ! $query->have_posts() ) {
		return;
	}

	// redirect to post
	$post      = $query->posts[ array_key_first( $query->posts ) ];
	$permalink = get_permalink( $post );

	// validating urls
	$og_urls = [
		(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]", // absolute url
		$_SERVER['REQUEST_URI'], // relative url
	];

	// re-add query parameters from request uri
	if ( ! empty( $_GET ) ) {
		foreach ( $_GET as $key => $value ) {
			$permalink = add_query_arg( $key, $value, $permalink );
		}
	}

	// avoid infinite loop
	if ( ! in_array( $permalink, $og_urls ) ) {
		wp_safe_redirect( $permalink, 301 );
		exit;
	}

}
add_action( 'template_redirect', 'em_redirect_matching_postname', 100 );
