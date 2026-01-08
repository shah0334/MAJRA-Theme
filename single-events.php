<?php get_header(); ?>
<style>
.acf-map-iframe iframe {
    width: 100% !important;
    height: 400px !important;
}
.card-content h6,
.card-content h5,
.card-content h4,
.card-content h3,
.card-content h2,
.card-content h1{
    font-family: Mackay;
    line-height: 37px;
}
.card-content p{
    font-size:16px;
    line-height: 24px;
}
ul{
    margin:0px;
    padding-left:20px;
}
ul li::marker {
  color: #3BC4BD; /* bullet color */
}
strong{ font-family: 'Graphik Arabic Bold'; }

.event-card{ position: relative;}
.event-card img{
    position: absolute;
    object-fit: contain;
}
.event-card-1 img{
    max-height: 250px;
    width: 240px;
    bottom: 0px;
    right: 30px;
    opacity: 1;
}
.rtl .event-card-1 img{
    right: unset;
    left: 30px;
}
.event-card-2 img{
    max-height: 300px;
    width: auto;
    top: 0px;
    left: 30px;
}
.event-card-3 img{
    max-height: 250px;
    width: 325px;
    bottom: 0px;
    right: 0px;
}
.rtl .event-card-3 img{
    right: unset;
    left: 0px;
}

@media (min-width: 600px) {
  .max-w-60 {
    max-width: 60%;
  }

  .min-h-400{
    min-height: 400px
  }
}

@media (max-width: 900) {
  .pb-small-0{
    padding-bottom: 0px !important;
  }
}
</style>
<main id="primary" class="site-main">

        <?php
            while ( have_posts() ) :
                the_post();

                $banner = wp_get_attachment_image_src(get_field('event_banner'), 'full');
                $event_date_location_and_time = get_field('event_date_location_and_time');
                $subtitle = get_field('event_subtitle');
                $location = get_field('event_location');
                $registration_link = get_field('event_registration_link');
                $download_agenda_link = get_field('event_download_agenda_link');
        ?>
                <!-- Banner -->
                <section class="text-white">
                    <a href="<?php echo esc_url($registration_link); ?>" target="_blank">
                        <img src="<?php echo $banner[0]; ?>" class="w-100" style="max-width: 100vw">
                    </a>
                </section>


                <!-- ABOUT EVENT -->
                <section class="pb-0 py-10">
                    <div class="container">
                        <div class="row align-items-center g-5">
                            <div class="col-md-6 order-2 order-md-1">
                                <p class="mb-2 text-aqua-marine fs-4"><?php echo esc_html($subtitle); ?></p>
                                <h4 class="mb-3 ff-macky display-4 text-primary"><?php the_title(); ?></h4>
                                <div class="about-event mb-5">
                                    <?php the_content(); ?>
                                </div>
                                <?php
                                    if( !empty($registration_link) ){
                                ?>
                                    <a href="<?php echo esc_url($registration_link); ?>" target="_blank" class="btn fw-semibold btn-default bg-orange text-white rounded-pill px-4"><?php echo pll__('Register Now'); ?></a>
                                <?php
                                    }
                                ?>
                            </div>
                            <div class="col-md-6 order-1 order-md-2 text-center text-md-end">
                                <img class="img-fluid w-100" style="max-width: 80%" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="">
                            </div>
                        </div>
                    </div>
                </section>

                <!-- EVENT LOCATION -->
                <section class="pb-0 py-10">
                    <div class="acf-map-iframe">
                        <div class="acf-map-iframe">
                            <?php 
                                echo $location; 
                            ?>
                        </div>
                        <?php
                            if( !empty($event_date_location_and_time) ):
                        ?>
                            <div class="container">
                                <div class="p-4 d-block mx-auto" style="background-color: #FAEBDA; width: fit-content; max-width: 100%; margin-top: -10px; border-bottom-left-radius: 30px; border-bottom-right-radius: 30px">
                                    <p class="m-0 para fw-medium"><?php echo esc_html($event_date_location_and_time); ?></p>
                                </div>
                            </div>
                        <?php
                            endif;
                        ?>
                    </div>
                </section>

                <!-- EVENT CARDS -->
                 <?php
                    if( have_rows('cards') ):
                        $cards = get_field('cards');
                        if( !empty($cards) && count($cards) > 0 ):
                            $columnClass = (count($cards) == 1) ? 'col-md-12' : 'col-md-6';
                 ?>
                            <section class="pb-0 py-10">
                                <div class="container card-content">
                                    <div class="row g-2 align-items-stretch">
                                        <div class="<?php echo esc_attr($columnClass); ?> d-flex">
                                            <div class="event-card event-card-1 rounded-lg p-5 flex-fill" style="background-color: #FAEBDA;">
                                                <img src="<?php echo get_template_directory_uri();?>/assets/img/card-elem-1.png">
                                                <div class="position-relative max-w-60">
                                                    <?php echo $cards[0]['card_content']; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                            if( count($cards) > 1 ):
                                        ?>
                                            <div class="<?php echo esc_attr($columnClass); ?> d-flex">
                                                <div class="row g-2 flex-fill">
                                                    <div class="col-12">
                                                        <div class="event-card event-card-2 p-5 h-100 rounded-lg text-white bg-primary">
                                                            <img src="<?php echo get_template_directory_uri();?>/assets/img/card-elem-2.png">
                                                            <div class="position-relative">
                                                                <?php echo $cards[1]['card_content']; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                        if( count($cards) > 2 ):
                                                    ?>
                                                        <div class="col-12">
                                                            <div class="event-card event-card-3 p-5 h-100 rounded-lg text-white bg-aqua-marine">
                                                                <img src="<?php echo get_template_directory_uri();?>/assets/img/card-elem-3.png">
                                                                <div class="position-relative">
                                                                    <?php echo $cards[2]['card_content']; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                        endif;
                                                    ?>
                                                </div>
                                            </div>
                                        <?php
                                            endif;
                                        ?>
                                    </div>
                                </div>
                            </section>
                <?php
                        endif;
                    endif;

                    $sessions = get_field('sessions');
                    if( !empty($sessions) ):
                ?>

                        <!-- SESSIONS -->
                        <section class="py-10" style=";background-color:#F9F1E5; margin-top: 6rem; border-top-left-radius: 50px;border-top-right-radius: 50px;">
                            <div class="container">
                                <div class="d-flex flex-wrap gap-4 align-items-center justify-content-center text-center <?php echo $isRTL ? 'text-md-end' : 'text-md-start'; ?>" style="margin-bottom: 6rem;">
                                    <h2 class="head-h2 m-0 flex-md-grow-1 ff-macky text-primary">
                                        <?php
                                            echo pll__('Summit').'<span class="d-block"></span>'.pll__('Program');
                                        ?> 
                                    </h2>
                                    <?php
                                        if( !empty($event_date_location_and_time) ):
                                    ?>
                                            <p style="max-width: 300px" class="m-0 para fs-5 fw-medium"><?php echo esc_html($event_date_location_and_time); ?></p>
                                    <?php
                                        endif;
                                    ?>
                                </div>
                            </div>
                            <?php
                                foreach ( $sessions as $index => $session ) {
                            ?>
                                    <?php
                                        if( $index != 0 ):
                                    ?>
                                        <div class="w-100" style="height: 100px">
                                            <img class="w-100 h-100" style="object-fit:cover" src="<?php echo get_template_directory_uri();?>/assets/img/design-bar.png" alt="">
                                        </div>
                                    <?php
                                        endif;
                                    ?>
                                    <div class="container">
                                        <div class="row my-5 g-4">
                                            <div class="col-md-6">
                                                <h2 class="fs-2 ff-macky m-0 mb-1">
                                                    <?php
                                                        echo pll__('Session').' '.$index + 1;
                                                    ?> 
                                                </h2>
                                                <p class="fs-5 m-0 mb-1"><?php echo esc_html( $session['session_location'] ); ?></p>
                                                <?php
                                                    $date = $session['date_time'];
                                                    if( !empty($date) ):
                                                        echo '<p class="fs-5 m-0">' . date( 'l, d F Y h:i A', strtotime($date) ) . '</p>';
                                                    endif;
                                                ?>
                                            </div>
                                            <div class="col-md-6">
                                                <h2 class="fs-2 ff-macky m-0 mb-4"><?php echo esc_html( $session['name'] ); ?></h2>
                                                <p class="fs-6 m-0"><?php echo $session['description']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            ?>
                        </section>

                 <?php
                    endif;

                    $hasSpeakers = false;
                    $sliders_ids = array();
                    if( !empty($sessions) ){
                        foreach ( $sessions as $index => $session ) {
                            $speakers = $session['speakers'];
                            if( !empty($speakers) ){
                                $hasSpeakers = true;
                            }
                        }
                        if( $hasSpeakers ):
                 ?>

                <!-- SESSIONS -->
                <section class="py-10">
                    <div class="container">
                        <div class="d-flex gap-3 flex-wrap text-dark align-items-center">
                            <h2 class="head-h2 m-0 flex-grow-1 ff-macky"><?php echo pll__('Featured Speakers'); ?></h2>
                            <?php
                                if( !empty($download_agenda_link) ){
                            ?>
                                <a href="<?php echo esc_url($download_agenda_link); ?>" target="_blank" class="btn fw-semibold btn-default bg-orange text-white rounded-pill px-4"><?php echo pll__('Download the agenda'); ?></a>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </section>
        <?php
                
              
                    echo '<section style="padding: 0 0 6rem 0; background-color:#F9F1E5">';
                    foreach ( $sessions as $index => $session ) {
                        $speakers = $session['speakers'];

                        if( !empty($speakers) ){
                            $unique_id = uniqid();
                            array_push($sliders_ids, $unique_id);
        ?>

                                <div class="d-flex align-items-center mb-5" style="padding: 4rem 0px; background-image: url('<?php echo get_template_directory_uri();?>/assets/img/design-bar.png'); background-repeat: no-repeat; background-position: left top; background-size: cover;">
                                    <div class="container">
                                        <div class="d-flex flex-column flex-md-row gap-1 text-dark align-items-center">
                                            <h2 class="fs-1 m-0 flex-grow-1 ff-macky text-aqua-marine"><?php echo pll__('Session').' '.$index + 1; ?></h2>
                                            <div class="text-aqua-marine m-0 fs-4 fw-medium"><?php echo esc_html( $session['name'] ); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container position-relative mb-5">
                                    <div class="swiper swiper-dark event-session-swiper-slider-<?php echo esc_attr($unique_id); ?>">
                                        <div class="swiper-wrapper text-dark">
                                            <?php
                                                for ($i=0; $i < count($speakers); $i++) {
                                                    $speaker = $speakers[$i];

                                                    $speaker_image = $speaker['image'];
                                                    $speaker_name = $speaker['name'];
                                                    $speaker_details = $speaker['details'];
                                            ?>
                                                <div class="swiper-slide">
                                                    <div class="speaker-card trustee-card">
                                                        <div class="speaker-image mb-4 px-2 py-3" style="height:216px">
                                                            <?php
                                                                if( !empty( $speaker_image ) ){
                                                            ?>
                                                                <img src="<?php echo esc_url($speaker_image); ?>" alt="<?php echo esc_attr($speaker_name); ?>">
                                                            <?php
                                                                }
                                                            ?>
                                                            
                                                        </div>
                                                        <div class="speaker-info">
                                                            <h5 class="mb-2 text-primary ff-macky fs-4"><?php echo esc_html($speaker_name); ?></h5>
                                                            <p class="para m-0"><?php echo esc_html($speaker_details); ?></p>
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
                                    <div class="swiper-button-next swiper-button-<?php echo esc_attr($unique_id); ?>"></div>
                                    <div class="swiper-button-prev swiper-button-<?php echo esc_attr($unique_id); ?>"></div>
                                </div>
        <?php
                        }
                    }
                    echo '</section>';
                    endif;
                }
        
        ?>
                <section style="padding: 6rem 0 6rem 0;" class="bg-primary">
                    <div class="container">
                        <div class="row g-3 g-md-0 align-items-center text-center <?php echo $isRTL ? 'text-md-end' : 'text-md-start'; ?>">
                            <div class="col-md-6">
                                <h5 class="ff-macky fs-1 text-white m-0">
                                    <?php
                                        echo pll__('Join the Stream For Sustainable Impact!')
                                    ?>
                                </h5>
                            </div>
                            <div class="col-md-3"></div>
                            <?php
                                if( !empty($registration_link) ){
                            ?>
                                <div class="col-md-3">
                                    <a href="<?php echo esc_url($registration_link); ?>" target="_blank" class="btn fw-semibold btn-default bg-orange text-white rounded-pill px-4 mb-3"><?php echo pll__('Register Now'); ?></a>
                                    <p class="text-white fw-lighter m-0">
                                        <?php
                                            echo pll__('To claim your ticket, click to register');
                                        ?>
                                    </p>
                                </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                </section>
        <?php
            endwhile; // End of the loop.
        ?>

</main><!-- #main -->

<?php get_footer(); ?>

<script>
       document.addEventListener("DOMContentLoaded", function() {
            const slider_ids = <?php echo json_encode($sliders_ids); ?>;
            slider_ids.forEach(function(slider_id){
                new Swiper('.event-session-swiper-slider-' + slider_id, {
                    loop: true,
                    slidesPerView: 3,
                    spaceBetween: 30,
                    autoplay: {
                        delay: 10000,
                        pauseOnMouseEnter: true,
                    },
                    navigation: {
                        nextEl: `.swiper-button-next.swiper-button-${slider_id}`,
                        prevEl: `.swiper-button-prev.swiper-button-${slider_id}`,
                    },
                    breakpoints: {
                        0: {
                            slidesPerView: 1,
                        },
                        768: {
                            slidesPerView: 2,
                        },
                        992: {
                            slidesPerView: 3,
                        },
                        1100: {
                            slidesPerView: 4,
                        },
                    },
                });
            });
        });
    </script>
