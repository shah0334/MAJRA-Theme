<?php /* Template Name: Impact Seal */ ?>
<?php get_header(); ?>

<div class="page-heading flipable" style="background-image: url('<?php echo get_template_directory_uri();?>/assets/img/impact-seal/header.jpg')">
    <div class="container d-flex align-items-center h-100 text-white">
        <div>
            <h1 class="head-h1"><?php echo $language['IMPACT_SEAL']['BANNER']['TITLE']; ?></h1>
            <div class="row mt-3">
                <div class="col-8 col-md-6">
                    <h5 class="fs-6 ff-ga"><?php echo $language['IMPACT_SEAL']['BANNER']['TEXT']; ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="bg-white py-10 position-relative">
    <img class="w-100 position-absolute top-50 start-50 translate-middle" loading="lazy"  src="<?php echo get_template_directory_uri();?>/assets/img/design-bar.png" >
    <div class="container position-relative">
        <img loading="lazy"  src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/banner.jpg" alt="Banner" class="rounded-xl img-fluid w-100">
    </div>
</section>

<section class="bg-white py-10 text-deep-ocean pt-0">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 mb-5 mb-md-0">
                <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/certify-your-impact.png" class="d-block w-100 mx-auto" alt="..." style="max-width:500px">
            </div>
            <div class="col-12 col-md-6">
                <h5 class="text-primary mb-4 fw-semibold"><?php echo $language['IMPACT_SEAL']['CERTIFY_IMPACT']['SUBTITLE']; ?></h5>
                <h2 class="head-h2 mb-4"><?php echo $language['IMPACT_SEAL']['CERTIFY_IMPACT']['TITLE']; ?></h2>
                <p class="para mb-3"><?php echo $language['IMPACT_SEAL']['CERTIFY_IMPACT']['TEXT_1']; ?></p>
                <p class="para mb-0"><?php echo $language['IMPACT_SEAL']['CERTIFY_IMPACT']['TEXT_2']; ?></p>
                <p class="para mb-3"><?php echo $language['IMPACT_SEAL']['CERTIFY_IMPACT']['TEXT_3']; ?></p>
                <p class="para m-0"><?php echo $language['IMPACT_SEAL']['CERTIFY_IMPACT']['TEXT_4']; ?></p>
            </div>
            
        </div>
    </div>
</section>

<section class="bg-white py-10 position-relative">
    <img class="w-100 position-absolute top-50 start-50 translate-middle" loading="lazy"  src="<?php echo get_template_directory_uri();?>/assets/img/design-bar.png" >
    <div class="container position-relative">
        <img loading="lazy"  src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/sme-impact-banner.jpg" alt="Banner" class="rounded-xl img-fluid w-100">
    </div>
</section>

<section class="bg-white text-deep-ocean">
    <div class="container py-10 pt-0">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 mb-5 mb-md-0">
                <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/sme-impact-seal.png" class="d-block w-100 mx-auto" alt="..." style="max-width:500px">
            </div>
            <div class="col-12 col-md-6">
                <h5 class="text-primary mb-4 fw-semibold"><?php echo $language['IMPACT_SEAL']['SME_IMPACT']['SUBTITLE']; ?></h5>
                <h2 class="head-h2 mb-4"><?php echo $language['IMPACT_SEAL']['SME_IMPACT']['TITLE']; ?></h2>
                <p class="mb-3 para"><?php echo $language['IMPACT_SEAL']['SME_IMPACT']['TEXT_1']; ?></p>
                <p class="mb-0 para"><?php echo $language['IMPACT_SEAL']['SME_IMPACT']['TEXT_2']; ?></p>
                <p class="mb-3 para"><?php echo $language['IMPACT_SEAL']['SME_IMPACT']['TEXT_3']; ?></p>
            </div>
            
        </div>
    </div>
    <!-- <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/design-bar-gold.png" class="d-block w-100"> -->
</section>

<section class="bg-cream">
    <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/design-bar-white.png" class="d-block w-100">
    <div class="container py-10">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 order-2 order-md-0">
                <!-- <h5 class="text-primary mb-4 fw-semibold"><?php echo $language['IMPACT_SEAL']['SHOW_IMPACT']['SUBTITLE']; ?></h5> -->
                <h2 class="head-h2 mb-4"><?php echo $language['IMPACT_SEAL']['SHOW_IMPACT']['TITLE']; ?></h2>
                <p class="m-0 para"><?php echo $language['IMPACT_SEAL']['SHOW_IMPACT']['TEXT_1']; ?></p>
                <p class="mb-5 para"><?php echo $language['IMPACT_SEAL']['SHOW_IMPACT']['TEXT_2']; ?></p>
                <p class="mb-4 para"><?php echo $language['IMPACT_SEAL']['SHOW_IMPACT']['TEXT_3']; ?></p>
                <?= do_shortcode('[waitlist-form]'); ?>
                <!-- <div class="bg-white p-5 rounded-lg text-black about-us-download-pdf position-relative mb-5">
                    <p class="mb-3 para"><?php echo $language['IMPACT_SEAL']['FIND_USFUL_MATERIAL']; ?></p>
                    <a style="width:fit-content" download class="btn btn-default rounded-pill btn-lg bg-aqua-marine px-4 text-white d-flex align-items-center gap-1">
                        <svg width="21" height="22" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.5283 12.4282V15.6663C17.5283 16.0957 17.3527 16.5075 17.0402 16.8112C16.7276 17.1148 16.3037 17.2854 15.8617 17.2854H4.19499C3.75296 17.2854 3.32904 17.1148 3.01648 16.8112C2.70391 16.5075 2.52832 16.0957 2.52832 15.6663V12.4282" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5.86133 8.38135L10.028 12.429L14.1947 8.38135" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10.0283 12.4277V2.71338" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <?php echo $language['LINKS']['DOWNLOAD']; ?>
                    </a>
                    <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/pdf.png" alt="Download PDF">
                </div> -->
                <p class="m-0 fw-semibold"><?php echo $language['IMPACT_SEAL']['HAVE_QUESTIONS']; ?></p>
                <p><?php echo $language['IMPACT_SEAL']['CHECK_FAQ']; ?> <a href="frequently-asked-questions" class="fw-semibold text-primary"><?php echo $language['LINKS']['FAQ']; ?></a></p>
            </div>
            <div class="col-12 col-md-6 mb-5 mb-md-0">
                <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/show-your-impact.png" class="d-block w-100 mx-auto" alt="..." style="max-width:500px">
            </div>
        </div>
    </div>
    <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/design-bar-white-bottom.png" class="d-block w-100">
</section>

<section class="bg-cream-light py-10 text-deep-ocean">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-12 col-md-6 mb-5 mb-md-0">
                <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/winner.png" class="d-block w-100 mx-auto" alt="..." style="max-width:500px">
            </div>
            <div class="col-12 col-md-6">
                <h5 class="text-primary mb-4 fw-semibold"><?php echo $language['IMPACT_SEAL']['SEAL_WINNERS']['SUBTITLE']; ?></h5>
                <h2 class="head-h2 mb-4"><?php echo $language['IMPACT_SEAL']['SEAL_WINNERS']['TITLE']; ?></h2>
                <p class="mb-3 para"><?php echo $language['IMPACT_SEAL']['SEAL_WINNERS']['TEXT']; ?></p>
            </div>
            
        </div>
    </div>

    <div class="container py-3 position-relative">
        <div class="swiper winner-logos">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/w-1.png" class="img-fluid">
                </div>
                <div class="swiper-slide">
                    <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/w-2.png" class="img-fluid">
                </div>
                <div class="swiper-slide">
                    <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/w-3.png" class="img-fluid">
                </div>
                <div class="swiper-slide">
                    <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/w-4.png" class="img-fluid">
                </div>
                <div class="swiper-slide">
                    <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/w-5.png" class="img-fluid">
                </div>
                <div class="swiper-slide">
                    <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/w-6.png" class="img-fluid">
                </div>
                <div class="swiper-slide">
                    <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/impact-seal/w-7.png" class="img-fluid">
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <div class="container mt-5">
        <h2 class="head-h2 mb-5"><?php echo $language['IMPACT_SEAL']['WINNER_STORIES']['TITLE']; ?></h2>

        <div class="row">
            <?php
                $stories = $language['IMPACT_SEAL']['WINNER_STORIES']['STORIES'];
                for ($i=0; $i < count($stories); $i++) { 
            ?>
                    <div class="col-md-6 mb-4">   
                        <div class="winner-story p-3" style="background-image: url('<?php echo get_template_directory_uri().$stories[$i]["IMAGE"]; ?>')">
                            <div class="d-flex flex-column justify-content-end h-100 text-white">
                                <div>
                                    <img loading="lazy"  src="<?php echo get_template_directory_uri().$stories[$i]['LOGO']; ?>" alt="Chairman" class="img-fluid mb-3" style="max-width:100px">
                                    <h5 class="fw-semibold fs-4"><?php echo $stories[$i]['TITLE']; ?></h5>
                                    <!-- <h6 class="fw-light fs-6"><?php echo $stories[$i]['TITLE']; ?></h6> -->
                                </div>
                            </div>
                            <a class="play play-yt-video" data-title="<?php echo $stories[$i]['TITLE']; ?>" data-src="<?php echo $stories[$i]['VIDEO']; ?>" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" viewBox="0 0 70 70" fill="none">
                                    <circle cx="35" cy="35" r="35" fill="white"/>
                                    <path d="M27.1289 25.37C27.1289 22.9965 29.7546 21.563 31.7512 22.8465L45.9534 31.9765C47.7904 33.1574 47.7904 35.8426 45.9534 37.0235L31.7512 46.1535C29.7546 47.437 27.1289 46.0035 27.1289 43.63V25.37Z" fill="#FC9C63"/>
                                </svg>
                            </a>
                        </div>
                    </div>
            <?php
                }
            ?>
        </div>
    </div>
</section>



<!-- Modal -->
<div class="modal fade" id="ytVideoMModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ytVideoMModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-header bg-white">
        <h1 class="modal-title fs-5" id="ytVideoMModalLabel">Video</h1>
        <button type="button" class="btn-close close-yt-video" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <iframe 
            class="w-100" 
            height="500" 
            src="https://www.youtube.com/embed/MOXdfqUED3c" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
        </iframe>
      </div>
    </div>
  </div>
</div>


<?php include_once get_template_directory() . "/components/stay-upto-date.php"; ?>
<?php get_footer();?>

<script>
    $(document).ready(() => {
        $('.play-yt-video').click(function() {
            const iframe = `<iframe 
                class="w-100" 
                height="500" 
                src="${$(this).data('src')}" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen>
            </iframe>`;
            $('#ytVideoMModal .modal-title').html($(this).data('title'));
            $('#ytVideoMModal .modal-body').html(iframe);
            $('#ytVideoMModal').modal('show');
        });

        $('.close-yt-video').click(function() {
            $('#ytVideoMModal .modal-body').html('');
        });
    });
</script>