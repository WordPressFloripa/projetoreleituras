<?php 
	$depoimentos = new WP_Query( array( 'pagename' => 'depoimentos' ) );
	if ( $depoimentos->have_posts() ) : 
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php while ($depoimentos->have_posts()) : $depoimentos->the_post(); ?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	<?php endwhile;?>
</div>

<?php
	wp_reset_postdata(); 
	endif;
?>