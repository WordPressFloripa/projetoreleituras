<?php
/**
 * Releituras Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package releituras
 */

add_action( 'wp_enqueue_scripts', 'onepress_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function onepress_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'onepress-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'releituras-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'onepress-style' )
	);

}
