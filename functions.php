<?php
/**
 * MAJRA functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package MAJRA
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function majra_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on MAJRA, use a find and replace
		* to change 'majra' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'majra', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'majra' ),
			'footer-menu-1' => esc_html__( 'Footer Menu 1', 'majra' ),
			'footer-menu-2' => esc_html__( 'Footer Menu 2', 'majra' ),
			'footer-menu-3' => esc_html__( 'Footer Menu 3', 'majra' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'majra_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'majra_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function majra_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'majra_content_width', 640 );
}
add_action( 'after_setup_theme', 'majra_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function majra_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'majra' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'majra' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Sidebar', 'majra' ),
			'id'            => 'footer-sidebar',
			'description'   => esc_html__( 'Add widgets here.', 'majra' ),
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'majra_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function majra_scripts() {
	wp_enqueue_style( 'majra-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'majra-style', 'rtl', 'replace' );

    // Enqueue Custom Pages Styles
    wp_enqueue_style( 'majra-custom-pages', get_template_directory_uri() . '/assets/css/custom-pages.css', array(), _S_VERSION );

	wp_enqueue_script( 'majra-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
    // Enqueue Google Maps
    $env_file = get_template_directory() . '/env';
    $maps_api_key = '';
    
    if ( file_exists( $env_file ) ) {
        $lines = file( $env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
        foreach ( $lines as $line ) {
            // Skip comments
            if ( strpos( trim($line), '#' ) === 0 ) continue;
            
            // Check for valid key-value pair
            if ( strpos( $line, '=' ) !== false ) {
                list( $name, $value ) = explode( '=', $line, 2 );
                if ( trim($name) === 'GOOGLE_MAPS_API_KEY' ) {
                    $maps_api_key = trim($value);
                    break;
                }
            }
        }
    }

    if ( $maps_api_key ) {
        wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $maps_api_key . '&libraries=places&callback=initMap', array(), null, true );
    }
}
add_action( 'wp_enqueue_scripts', 'majra_scripts' );


global $current_language;
global $isRTL;
// $current_language = WPGlobus::Config()->language;
$current_language = 'en';
if (function_exists('pll_current_language')) {
	$current_language = pll_current_language();
}

if($current_language == 'ar'){
	include_once(get_template_directory() . '/languages/ar.php');
	$isRTL = true;
}else{
	include_once(get_template_directory() . '/languages/en.php');
	$isRTL = false;
}

add_shortcode('events-press-form', function () {
    $form = do_shortcode('[contact-form-7 title="Events & Press"]'); // Replace with your form ID
	global $language;
	return str_replace("majra_submit", pll__('Submit'), $form);
});

function majra_register_translation_strings() {
	$theme = 'Majra Theme';
    pll_register_string('majra_subscribe', 'Subscribe', $theme);
	pll_register_string('majra_submit', 'Submit', $theme);
    pll_register_string('majra_join_waitlist', 'Join Waitlist', $theme);
	pll_register_string('majra_copyright', 'Copyright © 2025 National CSR Fund Majra', $theme);
	pll_register_string('majra_follow_majra', 'Follow Majra', $theme);
pll_register_string('majra_events_register_now', 'Register Now', $theme);
	pll_register_string('majra_events_summit', 'Summit', $theme);
	pll_register_string('majra_events_program', 'Program', $theme);
	pll_register_string('majra_events_session', 'Session', $theme);
	pll_register_string('majra_events_claim_ticket', 'To claim your ticket, click to register', $theme);
	pll_register_string('majra_events_join_stream', 'Join the Stream For Sustainable Impact!', $theme);
	pll_register_string('majra_events_download_agenda', 'Download the agenda', $theme);
        pll_register_string('majra_events_features_speakers', 'Featured Speakers', $theme);	
}
add_action('init', 'majra_register_translation_strings');

function custom_polylang_shortcode($atts, $content = null) {
    return pll__($content);
}
add_shortcode('pll', 'custom_polylang_shortcode');

// add_action('phpmailer_init', function ($phpmailer) {
// 	var_dump($phpmailer);
// });


// require 'path-to-phpmailer/src/PHPMailer.php';
// require 'path-to-phpmailer/src/SMTP.php';
// require 'path-to-phpmailer/src/Exception.php';

// add_action('init', function () {
// 	require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
// 	require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
// 	$mail = new PHPMailer\PHPMailer\PHPMailer();

// 	try {
// 		$mail->isSMTP();
// 		$mail->Host       = 'smtp.office365.com'; // SMTP server
// 		$mail->SMTPAuth   = true;
// 		$mail->Username   = 'smtp@itmaxglobal.com'; // Outlook email
// 		$mail->Password   = 'fprqdkmckwkyqkdl'; // App password

// 		// $mail->Host       = 'smtp.office365.com'; // SMTP server
// 		// $mail->SMTPAuth   = true;
// 		// $mail->Username   = 'ImpactSeal@uaemajra.com'; // Outlook email
// 		// $mail->Password   = 'whnnhwfxflvpnqmn'; // App password

// 		$mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
// 		$mail->Port       = 587;
// 		$mail->SMTPDebug = 3; // Debug level (0 = off, 1 = client messages, 2 = client and server, 3+ = verbose)
// 		$mail->Debugoutput = 'html'; // Output debug info in HTML format
	
// 		$mail->setFrom('smtp@itmaxglobal.com', 'Impact Seal');
// 		$mail->addAddress('tahir.khursheed@itmaxglobal.com', 'Tahir Khurshid');
	
// 		$mail->isHTML(true);
// 		$mail->Subject = 'Test Email';
// 		$mail->Body    = 'This is a test email sent using PHPMailer.';
	
// 		$mail->send();
// 		echo 'Email sent successfully!';
// 	} catch (PHPMailer\PHPMailer\Exception $e) {
// 		echo 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo;
// 	}

// 	// global $phpmailer;
// 	// var_dump($phpmailer);
// 	// use PHPMailer\PHPMailer\PHPMailer;
// 	// use PHPMailer\PHPMailer\Exception;

// 	// require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
// 	// require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
// 	// require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
// 	//var_dump($mail);
// 	die();

//     $to = 'tahir.khursheed@itmaxglobal.com';
//     $subject = 'Test Email';
//     $message = 'This is a test email.';
//     $headers = ['Content-Type: text/html; charset=UTF-8'];

//     if (wp_mail($to, $subject, $message, $headers)) {
//         die('Email sent successfully via wp_mail!');
//     } else {
//         die('Failed to send email via wp_mail.');
//     }
// });

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

// Hide Admin bar
// add_filter('show_admin_bar', '__return_false');

/**
 * Board of trustees
 */
require get_template_directory() . '/inc/board_of_trustees.php';
require get_template_directory() . '/inc/media-center.php';
require get_template_directory() . '/inc/knowledge_square.php';
require get_template_directory() . '/inc/projects.php';
require get_template_directory() . '/inc/events.php';
require get_template_directory() . '/inc/wp-bakery-custom-widgets.php';




// Add Image Upload Field to "faq-categories" (Edit and Add Screens)
function faq_categories_image_field($term) {
	if( $term instanceof WP_Term ){
        $image_id = get_term_meta($term->term_id, 'faq_category_image', true);
        $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
        $order = get_term_meta($term->term_id, 'faq_category_order', true);
    }else{
        $image_id  = '';
        $image_url = '';
        $order = '';
    }

    ?>
	<div class="form-field">
		<tr class="form-field">
			<th scope="row">
				<label for="faq-category-image"><?php _e('Category Image'); ?></label>
			</th>
			<td>
				<input type="hidden" id="faq-category-image" name="faq_category_image" value="<?php echo esc_attr($image_id); ?>">
				<div id="faq-category-image-preview" style="margin-top: 10px;">
					<?php if ($image_url) : ?>
						<img src="<?php echo esc_url($image_url); ?>" style="max-width: 100px;">
					<?php endif; ?>
				</div>
				<button class="button upload_image_button"><?php _e('Upload/Add Image'); ?></button>
				<button class="button remove_image_button"><?php _e('Remove Image'); ?></button>
			</td>
		</tr>
	</div>
	<div class="form-field">
		<tr class="form-field">
			<th scope="row">
				<label for="faq-category-order"><?php _e('Category Order'); ?></label>
			</th>
			<td>
				<input type="number" id="faq-category-order" name="faq_category_order" value="<?php echo esc_attr($order); ?>" min="0">
				<p class="description"><?php _e('Set the display order for this category.'); ?></p>
			</td>
		</tr>
	</div>
    <?php
}
add_action('faq-categories_edit_form_fields', 'faq_categories_image_field');
add_action('faq-categories_add_form_fields', 'faq_categories_image_field');

// Save the Image Field
function save_faq_categories_image($term_id) {
    if (isset($_POST['faq_category_image']) && !empty($_POST['faq_category_image'])) {
        update_term_meta($term_id, 'faq_category_image', intval($_POST['faq_category_image']));
    } else {
        delete_term_meta($term_id, 'faq_category_image');
    }

	if (isset($_POST['faq_category_order'])) {
        update_term_meta($term_id, 'faq_category_order', intval($_POST['faq_category_order']));
    } else {
        delete_term_meta($term_id, 'faq_category_order');
    }
}
add_action('edited_faq-categories', 'save_faq_categories_image');
add_action('created_faq-categories', 'save_faq_categories_image');

function faq_categories_admin_scripts($hook) {
	if (('edit-tags.php' === $hook || 'term.php' === $hook) && isset($_GET['taxonomy']) && $_GET['taxonomy'] === 'faq-categories') {
        wp_enqueue_media(); // Ensure media uploader scripts are loaded
		wp_enqueue_script(
			'faq-category-meta-box',
			get_template_directory_uri() . '/assets/js/faq-category-image.js',
			array('jquery'),
			null,
			true
		);
   }
}
add_action('admin_enqueue_scripts', 'faq_categories_admin_scripts');


// Add New Column to Taxonomy Listing
function faq_categories_add_column($columns) {
    $columns['faq_category_image'] = __('Category Image');
	$columns['faq_category_order'] = __('Category Order');
    return $columns;
}
add_filter('manage_edit-faq-categories_columns', 'faq_categories_add_column');

// Populate the Column with Category Images
function faq_categories_column_content($content, $column_name, $term_id) {
    if ($column_name == 'faq_category_image') {
        $image_id = get_term_meta($term_id, 'faq_category_image', true);
        if ($image_id) {
            $image_url = wp_get_attachment_url($image_id);
            $content = '<img src="' . esc_url($image_url) . '" style="max-width:50px; height:auto;">';
        } else {
            $content = __('No Image');
        }
    }

	if ($column_name == 'faq_category_order') {
        $order = get_term_meta($term_id, 'faq_category_order', true);
        if (!empty($order)) {
            $content = $order;
        } else {
            $content = __('N/A');
        }
    }
    return $content;
}
add_filter('manage_faq-categories_custom_column', 'faq_categories_column_content', 10, 3);



/*
 * Function for post duplication. Dups appear as drafts. User is redirected to the edit screen
 */
function rd_duplicate_post_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
	  wp_die('No post to duplicate has been supplied!');
	}
   
	/*
	 * Nonce verification
	 */
	if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
	  return;
   
	/*
	 * get the original post id
	 */
	$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
	/*
	 * and all the original post data then
	 */
	$post = get_post( $post_id );
   
	/*
	 * if you don't want current user to be the new post author,
	 * then change next couple of lines to this: $new_post_author = $post->post_author;
	 */
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;
   
	/*
	 * if post data exists, create the post duplicate
	 */
	if (isset( $post ) && $post != null) {
   
	  /*
	   * new post data array
	   */
	  $args = array(
		'comment_status' => $post->comment_status,
		'ping_status'    => $post->ping_status,
		'post_author'    => $new_post_author,
		'post_content'   => $post->post_content,
		'post_excerpt'   => $post->post_excerpt,
		'post_name'      => $post->post_name,
		'post_parent'    => $post->post_parent,
		'post_password'  => $post->post_password,
		'post_status'    => 'draft',
		'post_title'     => $post->post_title,
		'post_type'      => $post->post_type,
		'to_ping'        => $post->to_ping,
		'menu_order'     => $post->menu_order
	  );
   
	  /*
	   * insert the post by wp_insert_post() function
	   */
	  $new_post_id = wp_insert_post( $args );
   
	  /*
	   * get all current post terms ad set them to the new post draft
	   */
	  $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
	  foreach ($taxonomies as $taxonomy) {
		$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
		wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
	  }
   
	  /*
	   * duplicate all post meta just in two SQL queries
	   */
	  $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
	  if (count($post_meta_infos)!=0) {
		$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
		foreach ($post_meta_infos as $meta_info) {
		  $meta_key = $meta_info->meta_key;
		  if( $meta_key == '_wp_old_slug' ) continue;
		  $meta_value = addslashes($meta_info->meta_value);
		  $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
		}
		$sql_query.= implode(" UNION ALL ", $sql_query_sel);
		$wpdb->query($sql_query);
	  }
   
   
	  /*
	   * finally, redirect to the edit post screen for the new draft
	   */
	  wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
	  exit;
	} else {
	  wp_die('Post creation failed, could not find original post: ' . $post_id);
	}
  }
  add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );
   
  /*
   * Add the duplicate link to action list for post_row_actions
   */
  function rd_duplicate_post_link( $actions, $post ) {
	if (current_user_can('edit_posts')) {
	  $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=rd_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
	}
	return $actions;
  }
   
  add_filter( 'post_row_actions', 'rd_duplicate_post_link', 10, 2 );
  add_filter('page_row_actions', 'rd_duplicate_post_link', 10, 2);
  
  function use_gd_editor($editors) {
    return array('WP_Image_Editor_GD', 'WP_Image_Editor_Imagick');
  }
  add_filter('wp_image_editors', 'use_gd_editor');
  add_filter('http_request_timeout', function() { return 30; });

/**
 * Load SIC Dashboard Module
 * Comment out the line below to disable the entire dashboard
 */
require_once get_template_directory() . '/dashboard/dashboard_functions.php';
