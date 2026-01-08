<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package MAJRA
 */
global $language;
global $current_language;
global $isRTL;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	
	<?php wp_head(); ?>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css" />
	<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <style>
        <?php if ($isRTL){ ?>
            @font-face {
                font-family: 'Graphik Arabic';
                src: url('<?php bloginfo('template_directory'); ?>/assets/fonts/29LT-Zarid-Display/29LTZaridDisplay-Regular.otf');
                font-weight: 400;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Graphik Arabic Bold';
                src: url('<?php bloginfo('template_directory'); ?>/assets/fonts/29LT-Zarid-Display/29LTZaridDisplay-Bold.otf');
                font-weight: 600;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Mackay';
                src: url('<?php bloginfo('template_directory'); ?>/assets/fonts/29LT-Zarid-Display/29LTZaridDisplay-Bold.otf');
            }
        <?php }else{ ?>
            @font-face {
                font-family: 'Graphik Arabic';
                src: url('<?php bloginfo('template_directory'); ?>/assets/css/GraphikArabic-Regular.eot');
                src: local('GraphikArabicRegular'), local('GraphikArabic-Regular'),
                    url('<?php bloginfo('template_directory'); ?>/assets/css/GraphikArabic-Regular.eot?#iefix') format('embedded-opentype'),
                    url('<?php bloginfo('template_directory'); ?>/assets/css/GraphikArabic-Regular.woff2') format('woff2'),
                    url('<?php bloginfo('template_directory'); ?>/assets/css/GraphikArabic-Regular.woff') format('woff'),
                    url('<?php bloginfo('template_directory'); ?>/assets/css/GraphikArabic-Regular.ttf') format('truetype');
                font-weight: 400;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Graphik Arabic Bold';
                src: url('<?php bloginfo('template_directory'); ?>/assets/css/GraphikArabic-Bold.ttf');
                font-weight: 600;
                font-style: normal;
                font-display: swap;
            }

            @font-face {
                font-family: 'Mackay';
                src: url('<?php bloginfo('template_directory'); ?>/assets/fonts/Rene-Bieder-Mackay-DEMO-Bold.otf');
            }
        <?php } ?>
    </style>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/assets/css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/assets/css/responsive.css" />
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':

    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],

    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=

    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);

    })(window,document,'script','dataLayer','GTM-K8S3W2NB');</script>

    <!-- End Google Tag Manager -->
</head>

<body <?php body_class($current_language); ?> style='direction:<?php echo $isRTL ? 'rtl' : 'ltr'; ?>;'>
<noscript><iframe src=https://www.googletagmanager.com/ns.html?id=GTM-K8S3W2NB height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php wp_body_open(); ?>
	<header>
            <div class="container">
                <div class="d-flex align-items-center gap-5 justify-content-between">
                    <div class="">
                        <div class="logo">
							<?php the_custom_logo(); ?>
                            <!--<a href="#"><img src="<?php bloginfo('template_directory'); ?>/assets/img/logo.svg" /></a>-->
                        </div>
                    </div>
                    <div class="flex-grow-1 d-none d-lg-block">
                        <div class="menu">
                            <?php
							wp_nav_menu(
								array(
									'theme_location' => 'menu-1',
									'menu_id'        => 'primary-menu',
									'menu_class'     => '', 
								)
							);
							?>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="header-meta">
                            <a href="https://entityportal.uaemajra.ae" target="_blank" class="btn btn-default bg-primary text-white rounded-pill"><?php echo $language['LINKS']['JOIN_MAJRA']; ?></a>
                            <?php
                                global $wp;
                                if (function_exists('pll_the_languages')) {
                                    $langs = pll_the_languages(array(
                                        'show_flags' => 1, // Show country flags
                                        'show_names' => 1, // Show language names
                                        'hide_current' => 1, // Show the current language
                                        'raw' => 1 // Output as an array
                                    ));
                                    foreach ($langs as $lang) {
                                        $translated_url = '';
                                        //$url = pll_home_url($lang['locale']);
                                        //$url = pll_get_permalink(get_the_ID(), $lang['slug']);
                                        if (is_singular()) {
                                            // If it's a single post or page, get the translated version of the post/page
                                            $translated_post_id = pll_get_post(get_the_ID(), $lang['slug']);
                                            if ($translated_post_id) {
                                                $translated_url = get_permalink($translated_post_id);
                                            }
                                        } elseif (is_tax() || is_category() || is_tag()) {
                                            // If it's a taxonomy term (category, tag, custom taxonomy)
                                            $term = get_queried_object();
                                            $translated_term_id = pll_get_term($term->term_id, $lang['slug']);
                                            if ($translated_term_id) {
                                                $translated_url = get_term_link($translated_term_id);
                                            }
                                        } else {
                                            // For other pages, just change the language in the current URL
                                            $translated_url = pll_home_url($lang['slug']);
                                        }

                                        if($lang['locale'] == 'ar'){
                                            echo '<a href="'.$translated_url.'" class="lang-toggler">ع</a>';
                                        }
                                        if($lang['locale'] == 'en'){
                                            echo '<a href="'.$translated_url.'" class="lang-toggler">EN</a>';
                                        }
                                    }
                                }
                            ?>
                            <button class="menu-open"><img src="<?php bloginfo('template_directory'); ?>/assets/img/menubar.svg" alt="" /></button>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="mobile-nav">
            <div class="head">
                <button class="close menu-close"><img src="<?php bloginfo('template_directory'); ?>/assets/img/close.svg" alt="" /></button>
            </div>
            <div class="menu">
                <?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'menu_class'     => '', 
					)
				);
				?>
            </div>
        </div>
			

