<?php 
	$audiolivro = new WP_Query( array( 'pagename' => 'audio-livros' ) );
	if ( $audiolivro->have_posts() ) : 
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php while ($audiolivro->have_posts()) : $audiolivro->the_post(); ?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	<?php endwhile;?>
</div>

<?php
	wp_reset_postdata(); 
	endif;
?>
