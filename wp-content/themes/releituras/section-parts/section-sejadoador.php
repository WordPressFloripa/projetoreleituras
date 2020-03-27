<?php 
	$sejadoador = new WP_Query( array( 'pagename' => 'seja-um-doador' ) );
	if ( $sejadoador->have_posts() ) : 
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php while ($sejadoador->have_posts()) : $sejadoador->the_post(); ?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	<?php endwhile;?>
</div>

<?php
	wp_reset_postdata(); 
	endif;
?>
