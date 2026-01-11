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
                        <a href="<?php echo SIC_Routes::get_dashboard_home_url(); ?>">
                            <img src="<?php echo content_url('uploads/2026/01/dashboard-logo.svg'); ?>" alt="Majra Dashboard" />
                        </a>
                    </div>

                    <!-- Center: Navigation -->
                    <div class="d-none d-lg-block">
                        <nav class="dashboard-nav">
                            <?php
                            global $language;
                            $current_slug = get_post_field( 'post_name', get_post() );
                            
                            $home_active = ( $current_slug === SIC_Routes::SLUG_DASHBOARD_HOME ) ? 'active' : '';
                            
                            $org_active = '';
                            if ( in_array( $current_slug, [SIC_Routes::SLUG_MY_ORGANIZATIONS, SIC_Routes::SLUG_CREATE_ORG] ) ) {
                                $org_active = 'active';
                            }

                            $proj_active = '';
                            if ( in_array( $current_slug, [SIC_Routes::SLUG_MY_PROJECTS, SIC_Routes::SLUG_CREATE_PROJECT] ) ) {
                                $proj_active = 'active';
                            }
                            ?>
                            <a href="<?php echo SIC_Routes::get_dashboard_home_url(); ?>" class="nav-link <?php echo $home_active; ?>"><?php echo $language['DASHBOARD']['NAV']['HOME']; ?></a>
                            <a href="<?php echo SIC_Routes::get_my_organizations_url(); ?>" class="nav-link <?php echo $org_active; ?>"><?php echo $language['DASHBOARD']['NAV']['MY_ORGS']; ?></a>
                            <a href="<?php echo SIC_Routes::get_my_projects_url(); ?>" class="nav-link <?php echo $proj_active; ?>"><?php echo $language['DASHBOARD']['NAV']['MY_PROJECTS']; ?></a>
                        </nav>
                    </div>

                    <!-- Language Toggle -->
                    <div class="d-flex align-items-center me-3">
                        <?php
                        // Get current URL
                        $current_url_with_query = add_query_arg(NULL, NULL);
                        
                        // Create switcher links preserving other params
                        $ar_url = add_query_arg('d_lang', 'ar', $current_url_with_query);
                        $en_url = add_query_arg('d_lang', 'en', $current_url_with_query);
                        ?>
                        <?php if ( $current_language == 'ar' ): ?>
                            <a href="<?php echo esc_url($en_url); ?>" class="text-secondary fw-bold text-decoration-none font-graphik me-3" style="font-size: 16px;">EN</a>
                        <?php else: ?>
                            <a href="<?php echo esc_url($ar_url); ?>" class="text-secondary fw-bold text-decoration-none font-graphik me-3" style="font-size: 16px;">عربي</a>
                        <?php endif; ?>
                    </div>

                    <!-- Right: User Area -->
                    <div class="d-flex align-items-center">
                        <?php 
                        // Fetch the current user from SIC DB or session
                        $sic_user_name = 'Guest';
                        if ( is_user_logged_in() || isset($_SESSION['sic_user_id']) ) {
                            $applicant_id = isset($_SESSION['sic_user_id']) ? $_SESSION['sic_user_id'] : 0;
                            
                            if ( $applicant_id ) {
                                // Fetch real name from DB
                                $db = SIC_DB::get_instance();
                                $applicant = $db->get_applicant_by_id( $applicant_id );
                                if ( $applicant ) {
                                    $sic_user_name = trim( $applicant->first_name . ' ' . $applicant->last_name );
                                }
                            } elseif ( is_user_logged_in() ) {
                                // Fallback to WP User Display Name if no SIC session
                                $current_user = wp_get_current_user();
                                $sic_user_name = $current_user->display_name;
                            }
                        }
                        ?>
                        <div class="dashboard-user-area">
                            <span class="user-name d-none d-md-block"><?php printf( esc_html__( 'Hello, %s', 'majra' ), $sic_user_name ); ?></span>
                            
                            <div class="dashboard-actions">
                                <button class="dashboard-action-btn btn-profile" title="Profile">
                                    <img src="<?php echo content_url('uploads/2026/01/header-icon-profile.svg'); ?>" alt="Profile" />
                                </button>
                            <a href="<?php echo add_query_arg( 'sic_logout', '1', home_url() ); ?>" class="dashboard-action-btn btn-notification" title="Logout">
                                    <img src="<?php echo content_url('uploads/2026/01/header-icon-logout.svg'); ?>" alt="Logout" />
                                </a>
                         
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
                     <li><a href="<?php echo SIC_Routes::get_dashboard_home_url(); ?>"><?php echo $language['DASHBOARD']['NAV']['HOME']; ?></a></li>
                     <li><a href="<?php echo SIC_Routes::get_my_organizations_url(); ?>"><?php echo $language['DASHBOARD']['NAV']['MY_ORGS']; ?></a></li>
                     <li><a href="<?php echo SIC_Routes::get_my_projects_url(); ?>"><?php echo $language['DASHBOARD']['NAV']['MY_PROJECTS']; ?></a></li>
                </ul>
            </div>
        </div>
