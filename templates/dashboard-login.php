<?php
/**
 * Template Name: Dashboard Login
 */

// If logged in, redirect to dashboard
if ( isset($_SESSION['sic_user_id']) ) {
    wp_redirect( SIC_Routes::get_dashboard_home_url() );
    exit;
}

$error = '';
if ( isset($_GET['message']) ) {
    $error = $_GET['message'];
}

// Handle Login Action
// if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'sic_mock_login' ) {
//     $user_index = isset($_POST['user_index']) ? intval($_POST['user_index']) : 1;
//     $db = SIC_DB::get_instance();
//     $applicant = $db->get_or_create_dummy_applicant($user_index);
    
//     if ( $applicant ) {
//         $_SESSION['sic_user_id'] = $applicant->applicant_id;
//         $_SESSION['sic_user_name'] = $applicant->first_name . ' ' . $applicant->last_name;
//         wp_redirect( SIC_Routes::get_dashboard_home_url() );
//         exit;
//     }
// }

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
    <style>
        .login-section{
            background-image: url('<?php echo get_template_directory_uri();?>/assets/img/login-bg.png');
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>

<body <?php body_class($current_language); ?> style='direction:<?php echo $isRTL ? 'rtl' : 'ltr'; ?>;'>
    <?php wp_body_open(); ?>

    <div class="vh-100 w-100 overflow-hidden" style="background-color: #F7FAFB">
        <div class="row h-100">
            <div class="d-none d-lg-flex align-items-end col-12 col-lg-7 login-section text-white">
                <div class="p-5">
                    <p class="text-uppercase m-0 fs-4">Participate in the</p>
                    <p class="font-mackay display-2 fw-bold m-0">Sustainable</p>
                    <p class="font-mackay display-2 fw-bold m-0 text-nowrap">Impact Challenge</p>
                </div>
            </div>
            <div class="col-12 col-lg-5 d-flex flex-column align-items-center py-5">
                <img src="<?php echo get_template_directory_uri();?>/assets/img/sic-logo-2026.png" style="max-width: 300px; width: 90%;" class="mb-5">
                <div class="flex-grow-1 d-flex align-items-center">
                    <div class=" d-flex flex-column bg-white p-5 rounded-4 shadow-sm text-center" style="min-height: 450px; max-width: 440px; width: 90%;">
                        <div class="mb-4 flex-grow-1">
                            <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-3">Log in</h2>
                            <p class="font-graphik text-secondary fs-6 mb-3">Log in securely using your UAE Pass.</p>
                            <p class="font-graphik text-deep-ocean fs-5 m-0">Register your company and submit your projects to join the journey toward national recognition.</p>
                        </div>

                        <a href="<?php echo $uaepass_auth->get_login_url(); ?>">
                            <img src="<?php echo get_template_directory_uri();?>/assets/img/UAEPASS_Login_Btn_Outline_Pill_Active@2x.png" class="img-fluid">
                        </a>

                        <?php
                            if(!empty($error)){
                                echo '<p class="text-danger mt-3 m-0">ERROR: ';
                                echo $error;
                                echo '</p>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php wp_footer(); ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
