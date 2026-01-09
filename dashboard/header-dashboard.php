<?php
/**
 * The header for the dashboard
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
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/assets/css/custom-pages.css" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/assets/css/responsive.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':

    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],

    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=

    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);

    })(window,document,'script','dataLayer','GTM-K8S3W2NB');</script>

    <!-- End Google Tag Manager -->
</head>

<body <?php body_class($current_language . ' dashboard-page'); ?> style='direction:<?php echo $isRTL ? 'rtl' : 'ltr'; ?>;'>
<noscript><iframe src=https://www.googletagmanager.com/ns.html?id=GTM-K8S3W2NB height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php wp_body_open(); ?>
<!-- Dashboard Header -->
	<header class="dashboard-header">
            <div class="container">
                <div class="d-flex w-100 justify-content-between align-items-center">
                    
                    <!-- Left: Logo -->
                    <div class="logo">
                        <?php if ( has_custom_logo() ) : 
                            the_custom_logo();
                        else : ?>
                            <a href="<?php echo SIC_Routes::get_dashboard_home_url(); ?>"><img src="<?php bloginfo('template_directory'); ?>/assets/img/logo.svg" alt="<?php bloginfo('name'); ?>" /></a>
                        <?php endif; ?>
                    </div>

                    <!-- Center: Navigation -->
                    <div class="d-none d-lg-block">
                        <nav class="dashboard-nav">
                            <a href="<?php echo SIC_Routes::get_dashboard_home_url(); ?>" class="nav-link active"><?php pll_e('Home'); ?></a>
                            <a href="<?php echo SIC_Routes::get_my_organizations_url(); ?>" class="nav-link"><?php pll_e('My Organizations'); ?></a>
                            <a href="<?php echo SIC_Routes::get_my_projects_url(); ?>" class="nav-link"><?php pll_e('My Projects'); ?></a>
                        </nav>
                    </div>

                    <!-- Right: User Area -->
                    <div class="d-flex align-items-center">
                        <?php 
                        $current_user = wp_get_current_user();
                        $display_name = $current_user->exists() ? $current_user->display_name : 'Guest User';
                        ?>
                        <div class="dashboard-user-area">
                            <span class="user-name d-none d-md-block"><?php printf( esc_html__( 'Hello, %s', 'majra' ), $display_name ); ?></span>
                            
                            <div class="dashboard-actions">
                                <button class="dashboard-action-btn" title="Notifications">
                                    <i class="bi bi-bell"></i>
                                </button>
                                <button class="dashboard-action-btn" title="Profile">
                                    <i class="bi bi-person"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Mobile Menu Logic (Optional, kept minimal) -->
                         <div class="d-lg-none ms-3">
                            <button class="menu-open p-0 border-0 bg-transparent"><img src="<?php bloginfo('template_directory'); ?>/assets/img/menubar.svg" alt="" /></button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Mobile Nav Wrapper (Reusing existing logic but simplified) -->
        <div class="mobile-nav">
            <div class="head">
                <button class="close menu-close"><img src="<?php bloginfo('template_directory'); ?>/assets/img/close.svg" alt="" /></button>
            </div>
            <div class="menu">
                <ul class="navbar-nav">
                     <li><a href="<?php echo SIC_Routes::get_dashboard_home_url(); ?>"><?php pll_e('Home'); ?></a></li>
                     <li><a href="<?php echo SIC_Routes::get_my_organizations_url(); ?>"><?php pll_e('My Organizations'); ?></a></li>
                     <li><a href="<?php echo SIC_Routes::get_my_projects_url(); ?>"><?php pll_e('My Projects'); ?></a></li>
                </ul>
            </div>
        </div>
