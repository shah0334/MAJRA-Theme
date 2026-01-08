<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package MAJRA
 */

get_header();
global $wp;

while ( have_posts() ) :
	the_post();
	$featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
?>
<div class="page-heading text-white gap-10 bg-deep-ocean" style="background-image: url('<?php echo $featured_image;?>')">
	<div class="container flex-grow-1">
		<a href="<?php echo home_url() ?>/media-center" class="text-white text-decoration-none d-flex align-items-center gap-1">
			<img class="w-24 <?php echo $isRTL ? 'rotate-180' : ''; ?>" loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/chevron-left-white.svg">
			<span><?php echo $language['MEDIA_CENTER']['BACK_TO_MC']; ?></span>
		</a>
	</div>
</div>



<main id="primary" class="py-10 pt-5">
	<div class="container mb-5">
		<div class="row">

			<div class="col-12 mb-5">
				<button data-url="<?php echo home_url( $wp->request ); ?>" class="copy-to-clipboard btn btn-md-lg bg-orange text-white border border-white rounded-pill px-4 px-md-5 mb-4 d-flex align-items-center gap-2">
					<img class="w-24" loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/share-white.svg">
					<span><?php echo $language['LINKS']['SHARE']; ?></span>
				</button>
				<h5 class="fs-3 ff-macky mb-3"><?php echo get_the_date('j F Y'); ?></h5>
				<h1 class="fs-1 ff-macky mb-0"><?php the_title() ?></h1>

				
			</div>
			<div class="col-12 col-lg-7 mb-4 mb-lg-0">
				<div class="fs-6"><?php the_content(); ?></div>

				<?php
					// the_post_navigation(
					// 	array(
					// 		'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'majra' ) . '</span> <span class="nav-title">%title</span>',
					// 		'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'majra' ) . '</span> <span class="nav-title">%title</span>',
					// 	)
					// );
				?>

			</div>
			<div class="col-12 col-lg-5 ps-5">
				<?php
					// Retrieve Main Article Name and URL
					$main_article_name = get_post_meta(get_the_ID(), '_main_article_name', true);
					$main_article_url = get_post_meta(get_the_ID(), '_main_article', true);

					// Retrieve Additional Links
					$additional_links = get_post_meta(get_the_ID(), '_additional_links', true);

					// Retrieve Gallery Images
					$gallery_image_ids = get_post_meta(get_the_ID(), '_gallery_images', true);

					// Display the data
					if (!empty($main_article_name) && !empty($main_article_url)) {
						?>
							<div class="mb-4 main-article">
								<h6 class="fw-bold mb-3"><?php echo $language['MEDIA_CENTER']['MAIN_ARTICLE']; ?></h6>
								<a href="<?php echo esc_url($main_article_url); ?>" target="_blank" class="btn btn-default bg-orange text-white rounded-pill w-100 text-wrap"><?php echo esc_html($main_article_name); ?></a>
							</div>
						<?php
					}

					// Display the data
					if (!empty($additional_links)) {
						?>
							<div class="additional-links">
								<h6 class="fw-bold mb-3"><?php echo $language['MEDIA_CENTER']['ADDITIONAL_LINKS']; ?></h6>
								<div class="row">
						<?php
									foreach ($additional_links as $link) {
										$link_name = isset($link['name']) ? esc_html($link['name']) : 'N/A';
										$link_url = isset($link['url']) ? esc_url($link['url']) : '#';
						?>
										<div class="col-md-6 mb-3">
											<a href="<?php echo esc_url($link_url); ?>" target="_blank" class="btn btn-default bg-white border-orange text-orange rounded-pill w-100 text-wrap"><?php echo esc_html($link_name); ?></a>
										</div>
										
						<?php
									}
						?>
								</div>
								
							</div>
						<?php
					}
				?>

				
			</div>
		</div>
	</div>



	<?php
		if (!empty($gallery_image_ids) && is_array($gallery_image_ids)) {
	?>
	<section class="bg-white py-10 position-relative mt-5 pb-0">
		<img class="w-100 position-absolute top-0 start-0" loading="lazy"  src="<?php echo get_template_directory_uri();?>/assets/img/design-bar.png" >
		<div class="container position-relative">
			<div class="row">
				<?php
					foreach ($gallery_image_ids as $image_id) {
						$image_url = wp_get_attachment_url($image_id);
						if ($image_url) {
				?>
							<div class="col-12 col-md-6 col-lg-4 mb-4">
								<div class="gallery-box ratio ratio-1x1"> 
									<img src="<?php echo esc_url($image_url); ?>" alt="Gallery Image" class="img-fluid" />
								</div>
							</div>
						
				<?php
						}
					}
				?>
			</div>
		</div>
	</section>
	<?php
		}
	?>










		<?php
			

			//get_template_part( 'template-parts/content', get_post_type() );

			// the_post_navigation(
			// 	array(
			// 		'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'majra' ) . '</span> <span class="nav-title">%title</span>',
			// 		'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'majra' ) . '</span> <span class="nav-title">%title</span>',
			// 	)
			// );

			// If comments are open or we have at least one comment, load up the comment template.
			// if ( comments_open() || get_comments_number() ) :
			// 	comments_template();
			// endif;

		
		?>

</main><!-- #main -->

<?php
endwhile; // End of the loop.

include_once get_template_directory() . "/components/media-inquiry.php";
get_footer();
?>

<script>
	$(document).ready(function(){
		$('.copy-to-clipboard').click(function(){
			let $temp = $("<input>");
			$("body").append($temp);
			$temp.val($(this).data('url')).select();
			document.execCommand("copy");
			$temp.remove();
			window.alert('Link copied to clipboard');
		});
	});
</script>
