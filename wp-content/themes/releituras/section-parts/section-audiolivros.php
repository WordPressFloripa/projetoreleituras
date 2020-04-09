<?php 
	$audiolivro = new WP_Query( array( 'pagename' => 'audio-livros' ) );
	if ( $audiolivro->have_posts() ) : 
?>

<section id="audio-livros">
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php while ($audiolivro->have_posts()) : $audiolivro->the_post(); ?>
			<div class="pt-5 pb-2 d-flex align-items-center">	
			<div class="container">
				<?php the_content(); ?>
			</div>
		<?php endwhile;?>
	</div>

	<?php
		wp_reset_postdata(); 
		endif;
	?>
</section>