<?php 
function register_post_type_board_of_trustees() {
    $labels = array(
        'name'                  => _x('Board of Trustees', 'Post type general name', 'textdomain'),
        'singular_name'         => _x('Board of Trustee', 'Post type singular name', 'textdomain'),
        'menu_name'             => _x('Board of Trustees', 'Admin Menu text', 'textdomain'),
        'name_admin_bar'        => _x('Board of Trustees', 'Add New on Toolbar', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'add_new_item'          => __('Add New Board of Trustee', 'textdomain'),
        'new_item'              => __('New Board of Trustee', 'textdomain'),
        'edit_item'             => __('Edit Board of Trustee', 'textdomain'),
        'view_item'             => __('View Board of Trustee', 'textdomain'),
        'all_items'             => __('All Board of Trustees', 'textdomain'),
        'search_items'          => __('Search Board of Trustee', 'textdomain'),
        'parent_item_colon'     => __('Parent Board of Trustees:', 'textdomain'),
        'not_found'             => __('No board of trustee found.', 'textdomain'),
        'not_found_in_trash'    => __('No board of trustees found in Trash.', 'textdomain'),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'has_archive'           => true,
        'rewrite'               => array('slug' => 'board-of-trustees'), // URL slug
        'supports'              => array('title', 'editor', 'thumbnail'),
        'menu_icon'             => 'dashicons-admin-users', // Dashicon for the menu
        //'show_in_rest'          => true, // Enable Gutenberg/Block Editor support
    );

    register_post_type('board-of-trustees', $args);
}
add_action('init', 'register_post_type_board_of_trustees');

// function add_board_of_trustees_meta_boxes() {
//     add_meta_box(
//         'board_of_trustees_details',
//         __('Board of Trustee Details', 'textdomain'),
//         'render_board_of_trustees_meta_box',
//         'board-of-trustees',
//         'normal',
//         'high'
//     );
// }
// add_action('add_meta_boxes', 'add_board_of_trustees_meta_boxes');

// function render_board_of_trustees_meta_box($post) {
//     // Retrieve existing values
//     $order  = get_post_meta($post->ID, '_board_trustee_order', true);
//     $designation = get_post_meta($post->ID, '_board_trustee_designation', true);

//     // Nonce for security
//     wp_nonce_field('save_board_of_trustees_details', 'board_of_trustees_nonce');

//     echo '<p><label for="_board_trustee_order">' . __('Order', 'textdomain') . '</label></p>';
//     echo '<input type="number" id="_board_trustee_order" name="_board_trustee_order" value="' . esc_attr($order ) . '" style="width: 100%;" required />';

//     echo '<p><label for="board_trustee_designation">' . __('Designation', 'textdomain') . '</label></p>';
//     echo '<input type="text" id="board_trustee_designation" name="board_trustee_designation" value="' . esc_attr($designation) . '" style="width: 100%;" required />';
// }

// function save_board_of_trustees_meta_boxes($post_id) {
//     // Verify nonce
//     if (!isset($_POST['board_of_trustees_nonce']) || !wp_verify_nonce($_POST['board_of_trustees_nonce'], 'save_board_of_trustees_details')) {
//         return;
//     }

//     // Prevent autosave
//     if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
//         return;
//     }

//     // Check user permissions
//     if (!current_user_can('edit_post', $post_id)) {
//         return;
//     }
//     // Validate and save Order field
//     if (isset($_POST['board_trustee_order'])) {
//         $order = intval($_POST['board_trustee_order']);
//         if ($order <= 0) { // Ensure order is positive
//             $order = 1;
//         }
//         update_post_meta($post_id, '_board_trustee_order', $order);
//     }

//     // Validate and save Designation field
//     if (isset($_POST['board_trustee_designation']) && !empty(trim($_POST['board_trustee_designation']))) {
//         $designation = sanitize_text_field($_POST['board_trustee_designation']);
//         update_post_meta($post_id, '_board_trustee_designation', $designation);
//     } else {
//         delete_post_meta($post_id, '_board_trustee_designation'); // Remove if empty
//     }
// }
// add_action('save_post', 'save_board_of_trustees_meta_boxes');

/**
 * Add custom columns to the admin list view for Board of Trustees.
 */
function set_custom_board_of_trustees_columns($columns) {
    // Reorder columns to place 'Thumbnail' at index 0 and 'Content' at index 2
    $reordered_columns = array(
        'thumbnail' => __('Thumbnail', 'textdomain'),
    );
    
	foreach ($columns as $key => $value) {
		if ($key === 'title') {
			$reordered_columns[$key] = $value; // Add the Title column
            //$reordered_columns['order'] = __('Order', 'textdomain');
			$reordered_columns['editor_content'] = __('Content', 'textdomain'); // Add Content column after Title
		} else {
			$reordered_columns[$key] = $value; // Add remaining columns
		}
	}
		
	return $reordered_columns;
}
add_filter('manage_board-of-trustees_posts_columns', 'set_custom_board_of_trustees_columns');

/**
 * Populate custom columns in the admin list view for Board of Trustees.
 */
function custom_board_of_trustees_column($column, $post_id) {
    switch ($column) {
		case 'thumbnail':
            // Display the featured image or a default image
            $thumbnail = get_the_post_thumbnail($post_id, [50, 50]);
            if ($thumbnail) {
                echo $thumbnail;
            } else {
                echo '<img src="https://via.placeholder.com/50" alt="Default Image" width="50" height="50">';
            }
            break;

        // case 'order':
        //     // Display the board trustee order
        //     $order = get_post_meta($post_id, '_board_trustee_order', true);
        //     echo $order ? intval($order) : '-'; // Default to '-' if no order is set
        //     break;
			
        case 'editor_content':
            // Get the content and trim it for display
            $content = get_the_content(null, false, $post_id);
            echo wp_trim_words($content, 20); // Limit to 20 words
            break;
    }
}
add_action('manage_board-of-trustees_posts_custom_column', 'custom_board_of_trustees_column', 10, 2);

/**
 * Make the Content column sortable.
 */
function board_of_trustees_sortable_columns($columns) {
    //$columns['order'] = 'order';
    $columns['editor_content'] = 'editor_content';
    return $columns;
}
add_filter('manage_edit-board-of-trustees_sortable_columns', 'board_of_trustees_sortable_columns');

// function board_of_trustees_orderby($query) {
//     if (!is_admin()) {
//         return;
//     }

//     $orderby = $query->get('orderby');
//     if ('order' === $orderby) {
//         $query->set('meta_key', '_board_trustee_order');
//         $query->set('orderby', 'meta_value_num');
//     }
// }
// add_action('pre_get_posts', 'board_of_trustees_orderby');
?>