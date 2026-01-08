<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package MAJRA
 */

get_header();
?>
	<section class="error-404 not-found">
		<div class="container">
			<div class="row">
				<div class="col-md-12 text-center">
					<header class="page-header">
						<h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'majra' ); ?></h1>
					</header><!-- .page-header -->
					<div class="page-content">
						<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'majra' ); ?></p>
					</div><!-- .page-content -->
				</div>	
			</div>
		</div>	
	</section><!-- .error-404 -->


<?php
get_footer();
