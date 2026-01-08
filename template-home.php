<?php /* Template Name: Home Template */ ?>
<?php get_header(); ?>
<style>
.close-box{ transform:rotate(180deg); cursor:pointer;}
.logo-car-img img {
	height: 80px;
}
.wpcf7-response-output {
   background-color: #FFF;
}	
</style>
<main id="primary" class="site-main">

		<?php
		if ( have_posts() ) :

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_type() );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->
	
	<?php
?>
<?php get_footer(); ?>