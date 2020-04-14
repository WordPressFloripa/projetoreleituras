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
	require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';
}

/*criando menu social*/

register_nav_menus(
	array(
		'menu-social'  => esc_html__( 'Menu Social', 'releituras' ),
	)
);

/*Add imagem para lista de audiolivros*/
add_image_size( 'img-lista-audiolivros', 200, 200, true );

