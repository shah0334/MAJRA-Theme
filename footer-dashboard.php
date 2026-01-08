<?php
/**
 * The template for displaying the dashboard footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MAJRA
 */
global $language;
?>

        <!-- Dashboard Footer (Hidden/Empty as requested) -->
        <!-- 
        <footer class="dashboard-footer">
            ...
        </footer>
        -->
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
