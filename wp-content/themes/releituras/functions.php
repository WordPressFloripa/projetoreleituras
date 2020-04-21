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

	//require_once get_template_directory() . '/inc/template-tags.php';
}

/*criando menu social*/

register_nav_menus(
	array(
		'menu-social'  => esc_html__( 'Menu Social', 'releituras' ),
		'menu-social-footer'  => esc_html__( 'Menu Social Rodapé', 'releituras' ),
	)
);

/*Add imagem para lista de audiolivros*/
add_image_size( 'img-lista-audiolivros', 200, 200, true );

/*Editando rodapé*/ 

function onepress_footer_site_info() {
	?>
	<?php printf( esc_html__( '%1$s %2$s %3$s', 'onepress' ), '&copy;', esc_attr( date( 'Y' ) ), esc_attr( get_bloginfo() ) ); ?>
	<span class="sep"> &ndash; </span>
	<?php printf( esc_html__( 'LIVRO ACESSÍVEL PARA TODOS', 'onepress' ) ); ?>
	<?php
}
