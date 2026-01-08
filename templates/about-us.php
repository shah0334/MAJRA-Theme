<?php /* Template Name: About Us */ ?>
<?php get_header(); ?>

<div class="page-heading" style="background-image: url('<?php echo get_template_directory_uri();?>/assets/img/about-us-banner.jpg')">
    <div class="container d-flex align-items-center h-100">
        <h1 class="head-h1"><?php echo $language['ABOUTUS']['BANNER']['TITLE']; ?></h1>
    </div>
</div>

<section class="bg-deep-ocean py-10 text-white">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 order-2 order-md-0">
                <h5 class="text-aqua-marine mb-4 fw-semibold"><?php echo $language['ABOUTUS']['WHO_WE_ARE']['SUBTITLE']; ?></h5>
                <h2 class="head-h2 text-white mb-4"><?php echo $language['ABOUTUS']['WHO_WE_ARE']['HEADING']; ?></h2>
                <p class="mb-5 para text-white"><?php echo $language['ABOUTUS']['WHO_WE_ARE']['DETAIL']; ?></p>
                <div class="bg-white p-5 rounded-lg text-black about-us-download-pdf position-relative">
                    <p class="mb-3 para"><?php echo $language['ABOUTUS']['WHO_WE_ARE']['FORMATION']; ?></p>
                    <!-- <a style="width:fit-content" download href="<?php echo get_template_directory_uri();?>/assets/UAE_CD_No_2_of_2018.pdf" class="btn btn-default rounded-pill btn-lg bg-aqua-marine px-4 text-white d-flex align-items-center gap-1">
                        <svg width="21" height="22" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.5283 12.4282V15.6663C17.5283 16.0957 17.3527 16.5075 17.0402 16.8112C16.7276 17.1148 16.3037 17.2854 15.8617 17.2854H4.19499C3.75296 17.2854 3.32904 17.1148 3.01648 16.8112C2.70391 16.5075 2.52832 16.0957 2.52832 15.6663V12.4282" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5.86133 8.38135L10.028 12.429L14.1947 8.38135" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10.0283 12.4277V2.71338" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Download
                    </a> -->
                    <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/pdf.png" alt="Download PDF">
                </div>
            </div>
            <div class="col-12 col-md-6 mb-5 mb-md-0">
                <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/about-us.png" class="d-block w-100 mx-auto" alt="..." style="max-width:500px">
            </div>
        </div>
    </div>
</section>

<!-- CHAIRMAN'S MESSAGE -->
<section class="bg-cream py-10">
    <div class="container mt-5">
        <h2 class="head-h2 mb-5"><?php echo $language['ABOUTUS']['CHAIRMAN_MSG']['TITLE']; ?></h2>
    </div>
    <div class="position-relative">
        <img class="w-100 position-absolute top-50 start-50 translate-middle" loading="lazy"  src="<?php echo get_template_directory_uri();?>/assets/img/design-bar.png" >
        <div class="container position-relative">
            <div class="bg-white rounded-lg p-5">
                <div class="row align-items-center">
                    <div class="col-12 col-md-5">
                        <img loading="lazy" class="img-fluid mb-4 m-md-0" src="<?php echo get_template_directory_uri();?>/assets/img/chairman.png" alt="Chairman">
                    </div>
                    <div class="col-12 col-md-7">
                        <img loading="lazy" class="img-fluid mb-4" src="<?php echo get_template_directory_uri();?>/assets/img/quote-<?php echo $isRTL ? 'end' : 'start'; ?>.png" alt="Quote Icon">
                        <p class="m-0 fs-4 fw-semibold mb-2">
                            <?php echo $language['ABOUTUS']['CHAIRMAN_MSG']['MESSAGE']; ?>
                        </p>
                        <div class="<?php echo $isRTL ? 'text-start' : 'text-end'; ?> mb-3">
                            <img loading="lazy" class="img-fluid" src="<?php echo get_template_directory_uri();?>/assets/img/quote-<?php echo $isRTL ? 'start' : 'end'; ?>.png" alt="Quote Icon">
                        </div>
                        <p class="text-aqua-marine fs-5 ff-macky m-0"><?php echo $language['ABOUTUS']['CHAIRMAN_MSG']['NAME']; ?></p>
                        <p class="text-deep-ocean m-0"><?php echo $language['ABOUTUS']['CHAIRMAN_MSG']['DESIGNATION']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5 text-deep-ocean">
        <p>
            <?php echo $language['ABOUTUS']['CHAIRMAN_MSG']['LINE_1']; ?>
            <span class="d-inline-block show-about-us-text">
                <a class="text-orange text-decoration-none d-flex align-items-center gap-2 cursor-pointer">
                    <?php echo $language['LINKS']['READ_MORE']; ?>  
                    <img class="<?php echo $isRTL ? 'rotate-180' : ''; ?>" loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/chevron-right-orange.svg">
                </a>
            </span>
        </p>
        <div class="about-us-text" style="display:none">
            <p><?php echo $language['ABOUTUS']['CHAIRMAN_MSG']['LINE_2']; ?></p>
            <p><?php echo $language['ABOUTUS']['CHAIRMAN_MSG']['LINE_3']; ?></p>
            <p><?php echo $language['ABOUTUS']['CHAIRMAN_MSG']['LINE_4']; ?></p>
            <p>
                <?php echo $language['ABOUTUS']['CHAIRMAN_MSG']['LINE_5']; ?>
                <span class="d-inline-block hide-about-us-text">
                    <a class="text-orange text-decoration-none d-flex align-items-center gap-2 cursor-pointer">
                        <?php echo $language['LINKS']['READ_LESS']; ?>  
                        <img class="<?php echo $isRTL ? 'rotate-180' : ''; ?>" loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/chevron-right-orange.svg">
                    </a>
                </span>
            </p>
        </div>
    </div>
</section>

<section class="bg-cream py-10 pt-0 trustee-wrapper">
    <div class="container">
        <h2 class="head-h2 mb-5"><?php echo $language['ABOUTUS']['BOARD_OF_TRUSTEES']; ?></h2>

        <div class="row">
            <?php
                $args = array(
                    'post_type'      => 'board-of-trustees',
                    'posts_per_page' => -1, // Fetch all posts
                    'post_status'    => 'publish', // Only published posts
                );
                $query = new WP_Query($args);
                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
            ?>
                        <div class="col-12 col-md-4 col-lg-3 mb-5">
                            <div class="trustee-card">
                                <div class="mb-4 px-2 py-3" style="background-image: url('<?php echo get_template_directory_uri();?>/assets/img/design-bar-single.png')">
                                    <?php
                                        if (has_post_thumbnail()) {
                                            the_post_thumbnail('full', ['class' => 'img-fluid']);
                                        } else {
                                            echo '<img src="https://via.placeholder.com/100" alt="Default Image" class="img-fluid">';
                                        }
                                    ?>
                                </div>
                                <div class="content">
                                    <!-- <img class="img-fluid mb-4" src="<?php echo get_template_directory_uri();?>/assets/img/chairman.png" alt="Chairman"> -->
                                    <h3 class="mb-2 text-primary ff-macky fs-4"><?php the_title(); ?></h3>
                                    <p class="para m-0">
                                        <?php
                                            echo WPGlobus_Core::text_filter(get_the_content(), $current_language);
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
            <?php
                    endwhile;
                else : 
            ?>
                    <p>No Board of Trustees found.</p>
            <?php 
                endif; 
                wp_reset_postdata();
            ?>
        </div>
    </div>
</section>

<section class="bg-deep-ocean py-10 pt-0">
    <div class="container pb-5 position-relative">
        <div class="swiper swiper-dark single-swiper-slider">
            <div class="swiper-wrapper py-5 text-white">
                <?php 
                    foreach ($language['ABOUTUS']['COMMITTEE'] as $x => $committee) {
                        $isEven  = $x % 2 === 0;
                        $orderClasses = $isEven ? 'order-1 order-md-2' : 'order-2 order-md-0';
                ?>
                        <div class="swiper-slide">
                            <div class="row align-items-center min-vh-60">
                                <div class="col-12 col-md-6 <?php echo $orderClasses; ?>">
                                    <h5 class="text-aqua-marine mb-4"><?php echo $committee['SUBTITLE']; ?></h5>
                                    <h2 class="head-h2 text-light-orange mb-4"><?php echo $committee['TITLE']; ?></h2>
                                    <p class="para m-0 text-white"><?php echo $committee['TEXT']; ?></p>
                                </div>
                                <div class="col-12 col-md-6 mb-5 mb-md-0">
                                    <img src="<?php echo get_template_directory_uri().$committee['IMAGE']; ?>" class="d-block w-100 mx-auto" alt="..." style="max-width:500px">
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


<section class="bg-cream py-10">
    <div class="container">
        <h2 class="head-h2 mb-5"><?php echo $language['ABOUTUS']['STRATEGIC_GOALS']['TITLE']; ?></h2>

        <div class="row">
            <?php
                $StrategicGoals = $language['ABOUTUS']['STRATEGIC_GOALS']['GOAL'];
                for ($i=0; $i < count($StrategicGoals); $i++) { 
            ?>
                    <div class="col-12 col-md-6">
                        <div class="strategic-goal-card mb-4 text-white bg-black p-4">
                            <video playsinline autoplay preload="auto" loop muted>
                                <source class="pupose-box-video-src" src="<?php echo  get_template_directory_uri().$StrategicGoals[$i]['VIDEO']; ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            <div class="content d-flex flex-column">
                                <div class="flex-grow-1 text-center">
                                    <h5 class="mb-2 fs-1 ff-macky"><?php echo $StrategicGoals[$i]['TITLE'] ?></h5>
                                </div>
                                <h5 class="mb-2 fs-3 ff-macky">0<?php echo $i+1; ?></h5>
                                <p class="m-0 fw-semibold fs-5"><?php echo $StrategicGoals[$i]['TEXT'] ?></p>
                            </div>
                        </div>
                    </div>
            <?php
                }
            ?>
        </div>
    </div>
</section>

<?php get_footer();?>

<script>
    $(document).ready(function(){
        $('.show-about-us-text').click(function(){
            const $this = $(this);
            $('.about-us-text').slideToggle();
            $('.show-about-us-text').addClass('d-none');
        });
        $('.hide-about-us-text').click(function(){
            $('.about-us-text').slideToggle();
            $('.show-about-us-text').removeClass('d-none');
        });
    });
</script>