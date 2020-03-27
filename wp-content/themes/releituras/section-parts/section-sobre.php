<?php 
	$sobre = new WP_Query( array( 'pagename' => 'sobre' ) );
	if ( $sobre->have_posts() ) : 
?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php while ($sobre->have_posts()) : $sobre->the_post(); ?>
	<div class="bg-cinza pt-5 pb-2 d-flex align-items-center">
		<div class="container">
			<?php the_content(); ?>
		</div>
	</div>
		
	<?php endwhile;?>
</div>

<?php
	wp_reset_postdata(); 
	endif;
?>