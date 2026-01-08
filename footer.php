<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MAJRA
 */
global $language;
?>

        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="ft-logo"><img class="img-fluid" style="max-width:277px" src="<?php echo get_template_directory_uri();?>/assets/img/footer-logo.png" /></div>
                        <div class="ft-subs-div text-deep-ocean">
                            <h3 class="mb-3"><?php echo $language['SUBSCRIBE']['DETAIL_1']; ?></h3>
                            <?= do_shortcode('[mc4wp_form id=272]'); ?>
                        </div>
                    </div>
                    <div class="col-12 col-lg-7 offset-lg-1">
                        <div class="row">
                            <div class="col-6 col-md-4 ft-links">
                                <?php
                                    wp_nav_menu(
                                        array(
                                            'theme_location' => 'footer-menu-1',
                                            'menu_class'     => '', 
                                        )
                                    );
                                    ?>
                            </div>
                            <div class="col-6 col-md-4 ft-links">
                                <?php
                                    wp_nav_menu(
                                        array(
                                            'theme_location' => 'footer-menu-2',
                                            'menu_class'     => '', 
                                        )
                                    );
                                ?>
                                <div class="d-flex flex-column gap-3 mt-3">
                                    <a href="tel:800277823" class="d-flex align-items-center gap-2 fs-6 text-decoration-none text-primary fw-semibold">
                                        <img src="<?php bloginfo('template_directory'); ?>/assets/img/phone.png" />
                                        <p class="m-0" dir="ltr">800 277823</p>
                                    </a> 
                                    <a href="mailto:info@uaemajra.ae" class="d-flex align-items-center gap-2 fs-6 text-decoration-none text-primary fw-semibold">
                                        <img src="<?php bloginfo('template_directory'); ?>/assets/img/mail.png" />
                                        <p class="m-0">info@uaemajra.ae</p>
                                    </a>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 ft-links">
                                <?php
                                        wp_nav_menu(
                                            array(
                                                'theme_location' => 'footer-menu-3',
                                                'menu_class'     => '', 
                                            )
                                        );
                                        ?>
                                        <div class="ft-socials">
                                            <h3><?php pll_e('Follow Majra'); ?></h3>
                                            <div class="ft-soc-row">
                                                <a target="_blank" href="https://www.instagram.com/uaemajra/"><img src="<?php bloginfo('template_directory'); ?>/assets/img/ft-insta.svg" /></a> 
                                                <a target="_blank" href="https://www.linkedin.com/company/uaemajra/"><img src="<?php bloginfo('template_directory'); ?>/assets/img/ft-linkedin.svg" /></a>
                                                <a target="_blank" href="https://twitter.com/uaemajra"><img src="<?php bloginfo('template_directory'); ?>/assets/img/ft-x.svg" /></a>
                                            </div>
                                        </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row copyright-row">
                    <div class="col-md-12"><p class="copyright"><?php pll_e('Copyright © 2025 National CSR Fund Majra'); ?></p></div>
                </div>
            </div>
        </footer>
		<?php wp_footer(); ?>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"></script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script src="<?php bloginfo('template_directory'); ?>/assets/js/script.js"></script>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-T954F0HQ77"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            
            gtag('config', 'G-T954F0HQ77');
        </script>
    </body>
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
</html>

