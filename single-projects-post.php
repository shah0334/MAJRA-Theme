<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package MAJRA
 */

get_header();

while ( have_posts() ) :
	the_post();

	$featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    $partners_logo = get_post_meta($post->ID, '_partners_logo', true);
?>
<div class="page-heading text-white gap-10 bg-deep-ocean" style="background-image: url('<?php echo $featured_image;?>')">
	<div class="container flex-grow-1">
		<a href="<?php echo home_url() ?>/projects" class="text-white text-decoration-none d-flex align-items-center gap-1">
			<img class="w-24 <?php echo $isRTL ? 'rotate-180' : ''; ?>" loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/chevron-left-white.svg">
			<span><?php echo $language['PROJECTS']['BACK']; ?></span>
		</a>
	</div>
	<div class="container">
		<div class="row">
            <div class="col-12">
                <h1 class="head-h2 text-white text-wrap"><?php the_title() ?></h1>
            </div>
        </div>
    </div>
</div>


<section class="py-10 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <h5 class="text-primary mb-4 fw-semibold"><?php echo $language['PROJECTS']['MAGNIFY_IMPACT']['SUBTITLE']; ?></h5>
                <h2 class="head-h2 mb-4"><?php echo $language['PROJECTS']['MAGNIFY_IMPACT']['TITLE']; ?></h2>
                <p class="para mb-4"><?php echo $language['PROJECTS']['MAGNIFY_IMPACT']['TEXT']; ?></p>
                
                <a href="mailto:projects@uaemajra.ae" class="btn btn-default rounded-pill btn-lg bg-orange px-4 text-white"><?php echo $language['PROJECTS']['MAGNIFY_IMPACT']['BUTTON']; ?></a>
            </div>
            <div class="w-100 mb-5"></div>
            <?php
                if( !empty( $partners_logo ) ){
                    echo '<div class="col-12"><h5 class="text-deep-ocean mb-4 fw-semibold">'.$language["PROJECTS"]["PROJECT_PARTNERS"].'</h5><div class="swiper winner-logos"><div class="swiper-wrapper">';
                    foreach ($partners_logo as $logo) {
                        $image_url = wp_get_attachment_url($logo);
                        echo '<div class="swiper-slide"><img loading="lazy" src="'.esc_url($image_url).'" alt="Gallery Image" class="img-fluid" /></div>';
                    }
                    echo '</div></div></div>';
                }
            ?>
        </div>
    </div>
</section>

<section class="bg-cream py-10">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4 mb-md-0">
                <?php 
                    $kpi_title = get_post_meta($post->ID, '_kpi_title', true);
                    if( !empty($kpi_title) ){
                        echo '<h2 class="head-h2 mb-4">'.$kpi_title.'</h2>';
                    }

                    $kpi_desc = get_post_meta($post->ID, '_kpi_desc', true);
                    if( !empty($kpi_desc) ){
                        echo '<p class="m-0">'.$kpi_desc.'</p>';
                    }
                ?>
            </div>
            <div class="col-md-6">
                <div class="project-card kpi-card bg-orange text-white">
                    <?php
                        $kpi_subtitle = get_post_meta($post->ID, '_kpi_subtitle', true);
                        $kpis = get_post_meta($post->ID, '_kpis', true);
                        $kpi_image = get_post_meta($post->ID, '_kpi_image', true);
                        $kpi_image = (!empty($kpi_image)) ? wp_get_attachment_url($kpi_image) : null;

                        if( !empty($kpi_subtitle) || !empty($kpis) || !empty($kpi_image) ){
                            if ($kpi_image) {
                                echo '<img loading="lazy" src="'.$kpi_image.'" class="img-fluid preview">';
                            } else {
                                echo '<div class="img-fluid preview bg-deep-ocean position-relative text-white">
                                    <div class="position-absolute top-50 start-50 translate-middle fs-5">'.$language["LINKS"]["NO_IMAGE"].'</div>
                                </div>';
                            }
                        }
                        

                        
                        if( !empty($kpi_subtitle) || !empty($kpis) ){
                            echo '<div class="p-4">';
                            if (!empty($kpi_subtitle)) {
                                echo '<h5 class="m-0 fw-medium">'.$kpi_subtitle.'</h5>';
                            }

                            if( !empty($kpis) ){
                                echo '<div class="row mt-4">';

                                $count = count($kpis);
                                $col_class = $count === 1 ? 'col-12' : ($count === 2 ? 'col-6' : 'col-4');
                                foreach ($kpis as $kpi) {
                                    echo '<div class="'.$col_class.'">
                                            <h2 class="ff-macky">'.$kpi["value"].'</h2>
                                            <p class="m-0">'.$kpi["name"].'</p>
                                        </div>';
                                }
                                echo '</div>';
                            }
                            echo'</div>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
     $sdgs = get_post_meta($post->ID, '_sdgs', true);
     $sdgs_description = $language['PROJECTS']['SDGS']['DESCRIPTION'];
     if( !empty($sdgs) ){
?>
<section class="bg-deep-ocean py-10 pt-0">
    <div class="container pb-5 position-relative swiper-dark">
        <div class="swiper single-swiper-slider">
            <div class="swiper-wrapper py-5 text-white">
                <?php
                    $sdg_lang = $current_language == 'ar' ? '-ar' : '';
                    foreach ($sdgs as $sdg) {
                ?>
                        <div class="swiper-slide">
                            <div class="row align-items-center min-vh-60">
                                <div class="col-12 col-md-6 order-2 order-md-0">
                                    <h5 class="text-aqua-marine mb-4"><?php echo $language['PROJECTS']['SDGS']['ACHIEVED']; ?></h5>
                                    <h2 class="head-h2 text-light-orange mb-4"><?php echo $language['PROJECTS']['SDGS']['SDG']; ?> <br><?php echo $language['PROJECTS']['SDGS']['NUMBER']; ?> <?php echo $sdg; ?></h2>
                                    <p class="ff-ga m-0 fs-5"><?php echo $sdgs_description[$sdg]; ?></p>
                                </div>
                                <div class="col-12 col-md-6 mb-5 mb-md-0">
                                    <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/sdgs/slides/<?php echo $sdg.$sdg_lang; ?>.png" class="d-block w-100 mx-auto" alt="..." style="max-width:500px">
                                </div>
                            </div>
                        </div>
                <?php
                    }
                ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="autoplay-progress">
                <svg viewBox="0 0 48 48">
                    <circle cx="24" cy="24" r="20"></circle>
                </svg>
                <span></span>
            </div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>
<?php
    }
    $categories = get_the_terms(get_the_ID(), 'projects_category');
endwhile; // End of the loop.
wp_reset_postdata();

    // Get related projects
    if ($categories && !is_wp_error($categories)) {
        $category_ids = wp_list_pluck($categories, 'term_id');

        $args = array(
            'post_type'      => 'projects-post',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
            'posts_per_page' => 4, // Limit to 5 related projects
            'post__not_in'   => array(get_the_ID()), // Exclude the current post
            'tax_query'      => array(
                array(
                    'taxonomy' => 'projects_category', // Custom taxonomy
                    'field'    => 'term_id',          // Match based on term ID
                    'terms'    => $category_ids,      // Categories of the current project
                    'operator' => 'IN',               // Include posts in these categories
                ),
            ),
        );
        $related_projects_query = new WP_Query($args);
        if ($related_projects_query->have_posts()) :
?>

<section class="bg-cream py-10">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="head-h2 mb-4"><?php echo $language['PROJECTS']['RELATED_PROJECTS']; ?></h2>
            </div>
            <?php
                 while ($related_projects_query->have_posts()) : 
                    $related_projects_query->the_post();
                    $post_slug = get_post_field('post_name', get_post());
                    $categories = get_the_terms(get_the_ID(), 'projects_category');
                    $sdgs = get_post_meta(get_the_ID(), '_sdgs', true);
                    $featured_image = get_the_post_thumbnail(get_the_ID(), 'full', array('class' => 'img-fluid preview'));
            ?>
                        <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-4 mb-lg-0">
                            <a href="<?php echo get_permalink(); ?>" class="project-card shadow-sm text-decoration-none">
                                <!-- <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/projects/dummy.png" class="img-fluid preview"> -->
                                <?php
                                    if ($featured_image) {
                                        echo $featured_image;
                                    } else {
                                        echo '<div class="img-fluid preview bg-deep-ocean position-relative text-white">
                                                <div class="position-absolute top-50 start-50 translate-middle fs-5">'.$language["LINKS"]["NO_IMAGE"].'</div>
                                            </div>';
                                    }
                                ?>
                                <div class="d-flex flex-column bg-white p-4 text-deep-ocean flex-grow-1">
                                    <?php
                                        if( !empty($categories) ){
                                            if( count($categories) > 1 ){
                                    ?>
                                                <div class="d-flex gap-2 align-items-center mb-3">
                                                    <div><h5 class="fw-bold m-0"><?php echo count($categories); ?> <?php echo $language['PROJECTS']['EMIRATES']; ?></h5></div>
                                                    <div class="border-top border-3 rounded-pill border-orange" style="width:54px; height:0px"></div>
                                                </div>
                                    <?php
                                            }else{
                                                foreach  ($categories as $category) { 
                                    ?>
                                                    <div class="d-flex gap-2 align-items-center mb-3">
                                                        <div><h5 class="fw-bold m-0"><?php echo $category->name; ?></h5></div>
                                                        <div class="border-top border-3 rounded-pill border-orange" style="width:54px; height:0px"></div>
                                                    </div>
                                    <?php
                                                }
                                            }
                                        }
                                    ?>
                                    <h4 class="ff-macky fs-4 m-0 flex-grow-1"><?php echo the_title(); ?></h4>
                                    <?php
                                        if( !empty($sdgs) ){
                                            $spliced_array = count($sdgs) > 4 ? array_slice($sdgs, 0, 4) : $sdgs;
                                            $remaining = count($sdgs) > 4 ? count($sdgs)-4 : 0;
                                            echo '<div class="d-flex icons mt-4 align-items-center gap-2">';
                                            foreach  ($spliced_array as $sdg) { 
                                    ?>
                                                <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/sdgs/SDG-<?php echo  $sdg; ?>.png" class="img-fluid">
                                    <?php
                                            }
                                            echo $remaining > 0 ? '<b>+'.$remaining.'</b>' : '';
                                            echo '</div>';
                                        }
                                    ?>
                                </div>
                            </a>
                        </div>
            <?php
                endwhile;
                wp_reset_postdata();
            ?>
        </div>
    </div>
</section>



<?php
        endif;
    }
get_footer();
