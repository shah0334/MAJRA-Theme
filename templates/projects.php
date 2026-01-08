<?php /* Template Name: Projects */ ?>
<?php 
    get_header(); 
    $category = 'all';
    $order_by = 'DESC';
    if( isset($_GET) ){
        if( isset($_GET['category']) && !empty($_GET['category']) ){
            $category = sanitize_text_field($_GET['category']);
        }
        if( isset($_GET['order']) && !empty($_GET['order']) ){
            $order_by = $_GET['order'];
        }
    }
?>

<div class="page-heading" style="background-image: url('<?php echo get_template_directory_uri();?>/assets/img/projects/heading.jpg')">
    <div class="container d-flex align-items-center h-100 text-white">
        <div>
            <h1 class="head-h1"><?php echo $language['PROJECTS']['BANNER']['TITLE']; ?></h1>
            <div class="row mt-3">
                <div class="col-md-7">
                    <h5 class="fs-6 ff-ga"><?php echo $language['PROJECTS']['BANNER']['TEXT']; ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="bg-cream py-10 text-deep-ocean">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 mb-5 mb-md-0">
                <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/projects/verify-your-project.png" class="d-block w-100 mx-auto" alt="..." style="max-width:500px">
            </div>
            <div class="col-12 col-md-6 order-2 order-md-0">
                <h5 class="text-primary mb-4 fw-semibold"><?php echo $language['PROJECTS']['VERIFY_IMPACT']['SUBTITLE']; ?></h5>
                <h2 class="head-h2 mb-4"><?php echo $language['PROJECTS']['VERIFY_IMPACT']['TITLE']; ?></h2>
                <p class="para m-0"><?php echo $language['PROJECTS']['VERIFY_IMPACT']['TEXT_1']; ?></p>
                <p class="para mb-4"><?php echo $language['PROJECTS']['VERIFY_IMPACT']['TEXT_2']; ?></p>
                
                <a href="https://entityportal.uaemajra.ae" target="_blank" class="btn btn-default rounded-pill btn-lg bg-orange px-4 text-white"><?php echo $language['PROJECTS']['VERIFY_IMPACT']['VERIFY_PROJECT']; ?></a>
            </div>
        </div>
    </div>
</section>

<section class="bg-white" id="partners">
    <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/projects/design-bar-gold-top.png" class="d-block w-100">
    <div class="container position-relative">
        <div class="swiper single-swiper-slider">
            <div class="swiper-wrapper py-5">
                <div class="swiper-slide">
                    <div class="row align-items-center min-vh-60">
                        <div class="col-12 col-md-6 order-2 order-md-0">
                            <h5 class="text-dark-green fw-semibold mb-4"><?php echo $language['PROJECTS']['PARTNERS']['TITLE']; ?></h5>
                            <h2 class="head-h2 mb-4 text-deep-ocean"><?php echo $language['PROJECTS']['PARTNERS']['JOOD']['TITLE']; ?></h2>
                            <p class="para mb-4"><?php echo $language['PROJECTS']['PARTNERS']['JOOD']['TEXT']; ?></p>

                            <a target="_blank" href="https://jood.ae/" class="btn btn-default rounded-pill btn-lg bg-dark-green px-4 text-white mb-5 d-flex align-items-center gap-2" style="width:fit-content">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M15 10.8333V15.8333C15 16.2754 14.8244 16.6993 14.5118 17.0118C14.1993 17.3244 13.7754 17.5 13.3333 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V6.66667C2.5 6.22464 2.67559 5.80072 2.98816 5.48816C3.30072 5.17559 3.72464 5 4.16667 5H9.16667" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.5 2.5H17.5V7.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.33301 11.6667L17.4997 2.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <?php echo $language['PROJECTS']['PARTNERS']['JOOD']['VISIT']; ?>
                            </a>

                            <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/projects/slide-1-icon.png" class="d-block" alt="..." style="max-width:150px">
                        </div>
                        <div class="col-12 col-md-6 mb-5 mb-md-0">
                            <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/projects/slide-1.png" class="d-block w-100 mx-auto" alt="..." style="max-width:500px">
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="row align-items-center min-vh-60">
                        <div class="col-12 col-md-6 order-2 order-md-0">
                            <h5 class="text-choco fw-semibold mb-4"><?php echo $language['PROJECTS']['PARTNERS']['TITLE']; ?></h5>
                            <h2 class="head-h2 mb-4 text-deep-ocean"><?php echo $language['PROJECTS']['PARTNERS']['MAAN']['TITLE']; ?></h2>
                            <p class="para mb-4"><?php echo $language['PROJECTS']['PARTNERS']['MAAN']['TEXT']; ?></p>

                            <a target="_blank" href="https://maan.gov.ae/en/" class="btn btn-default rounded-pill btn-lg bg-choco px-4 text-white mb-5 d-flex align-items-center gap-2" style="width:fit-content">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M15 10.8333V15.8333C15 16.2754 14.8244 16.6993 14.5118 17.0118C14.1993 17.3244 13.7754 17.5 13.3333 17.5H4.16667C3.72464 17.5 3.30072 17.3244 2.98816 17.0118C2.67559 16.6993 2.5 16.2754 2.5 15.8333V6.66667C2.5 6.22464 2.67559 5.80072 2.98816 5.48816C3.30072 5.17559 3.72464 5 4.16667 5H9.16667" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12.5 2.5H17.5V7.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.33301 11.6667L17.4997 2.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <?php echo $language['PROJECTS']['PARTNERS']['MAAN']['VISIT']; ?>
                            </a>

                            <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/projects/slide-2-icon.png" class="d-block" alt="..." style="max-width:250px">
                        </div>
                        <div class="col-12 col-md-6 mb-5 mb-md-0">
                            <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/projects/slide-2.png" class="d-block w-100 mx-auto" alt="..." style="max-width:500px">
                        </div>
                    </div>
                </div>
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
    <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/projects/design-bar-gold-bottom.png" class="d-block w-100">
</section>

<section class="bg-cream py-10" id="list">
    <div class="container">
        <form class="filter-form" action="#list">
            <div class="d-flex mb-5 align-items-center justify-content-between">
                <h2 class="head-h2 m-0"><?php echo $language['PROJECTS']['IMPACTFUL_PROJECTS']; ?></h2>
                <div>
                    <select name="order" id="" class="form-select form-control-lg rounded-pill px-4 filter-input" style="width:120px">
                        <option value="DESC" <?php echo $order_by == 'DESC' ? 'selected' : '' ?>><?php echo $language['LINKS']['LATEST']; ?></option>
                        <option value="ASC" <?php echo $order_by == 'ASC' ? 'selected' : '' ?>><?php echo $language['LINKS']['OLDEST']; ?></option>
                    </select>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12">
                    <input type="hidden" name="category" value="<?php echo $category; ?>">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a href="#" class="nav-link <?php echo ($category=='all') ? 'active' : ''; ?>" data-category='all'><?php echo $language['LINKS']['ALL']; ?></a>
                        </li>
                        <?php
                            $categories = get_terms(array(
                                'taxonomy'   => 'projects_category',
                                'hide_empty' => false, // Set to true if you only want terms with posts.
                            ));
                            if (!is_wp_error($categories) && !empty($categories)) {
                                foreach ($categories as $cat) {
                                    echo '<li class="nav-item">
                                            <a href="#" class="nav-link '.(($category==$cat->slug) ? "active" : "").'" data-category="'.$cat->slug.'">'.$cat->name.'</a>
                                        </li>';
                                }
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </form>

        <div class="row">
            <?php
                $args = array(
                    'posts_per_page' => 20,
                    'post_type' => 'projects-post',
                    'orderby' => 'date',
                    'order' =>  $order_by,
                    'post_status' => 'publish',
                    // category
                    // 'tax_query' => array(
                    //     array(
                    //         'taxonomy' => 'projects_category', // Replace with your taxonomy if custom
                    //         'field'    => 'slug',     // Can be 'slug', 'id', or 'name'
                    //         'terms'    => 'dubai', // Replace with the desired category slug or ID
                    //     ),
                    // ),
                );

                if( $category != 'all' && !empty($category) ){
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'projects_category', // Replace with your taxonomy if custom
                            'field'    => 'slug',     // Can be 'slug', 'id', or 'name'
                            'terms'    => $category, // Replace with the desired category slug or ID
                        )
                    );
                }

                $query = new WP_Query($args);
                if ( $query->have_posts() ) :
                    while ($query->have_posts()) : 
                        $query->the_post();
                        $post_slug = get_post_field('post_name', get_post());
                        $categories = get_the_terms(get_the_ID(), 'projects_category');
                        $sdgs = get_post_meta(get_the_ID(), '_sdgs', true);
                        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
            ?>
                        <div class="col-12 col-md-6 col-lg-4 col-xxl-3 mb-4">
                            <a href="<?php echo home_url();?>/projects/<?php echo $post_slug; ?>" class="project-card shadow-sm text-decoration-none">
                                <!-- <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/projects/dummy.png" class="img-fluid preview"> -->
                                <?php
                                    if ($featured_image) {
                                        echo '<img loading="lazy" src="'.$featured_image.'" class="img-fluid preview block">';
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
                                    <h4 class="ff-macky fs-4 m-0 flex-grow-1 h-100"><?php echo the_title(); ?></h4>
                                    <?php
                                        if( !empty($sdgs) ){
                                            $spliced_array = count($sdgs) > 4 ? array_slice($sdgs, 0, 4) : $sdgs;
                                            $remaining = count($sdgs) > 4 ? count($sdgs)-4 : 0;
                                            echo '<div class="d-flex icons mt-4">';
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
                else :
                    echo '<p class="text-center">'.$language["LINKS"]["NOT_FOUND"].'</p>';
                endif;
            ?>
        </div>
    </div>
</section>

<?php include_once get_template_directory() . "/components/stay-upto-date.php"; ?>
<?php get_footer();?>


<script>
    jQuery(document).ready(function(){
        jQuery('.filter-form select[name="order"]').change((e) => {
            e.preventDefault();
            jQuery('.filter-form').submit();
        });

        jQuery('.filter-form .nav-link').click(function(e){
            e.preventDefault();
            jQuery('.filter-form input[name="category"]').val($(this).data('category'));
            jQuery('.filter-form').submit();
        });
    });
</script>