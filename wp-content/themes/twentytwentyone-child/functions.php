<?php
/**
 * Twenty Twenty-One Child Theme functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue parent and child styles.
 */
function twentytwentyone_child_enqueue_styles() {
	wp_enqueue_style(
		'twentytwentyone-parent-style',
		get_template_directory_uri() . '/style.css',
		array(),
		wp_get_theme( 'twentytwentyone' )->get( 'Version' )
	);

	wp_enqueue_style(
		'twentytwentyone-child-style',
		get_stylesheet_uri(),
		array( 'twentytwentyone-parent-style' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'twentytwentyone_child_enqueue_styles' );