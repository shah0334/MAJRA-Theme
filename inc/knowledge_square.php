<?php
function register_post_type_knowledge_square() {
    $labels = array(
        'name'                  => _x('Knowledge Square', 'Post type general name', 'textdomain'),
        'singular_name'         => _x('Knowledge Square', 'Post type singular name', 'textdomain'),
        'menu_name'             => _x('Knowledge Square', 'Admin Menu text', 'textdomain'),
        'name_admin_bar'        => _x('Knowledge Square', 'Add New on Toolbar', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'add_new_item'          => __('Add New Knowledge', 'textdomain'),
        'new_item'              => __('New Knowledge', 'textdomain'),
        'edit_item'             => __('Edit Knowledge', 'textdomain'),
        'view_item'             => __('View Knowledge', 'textdomain'),
        'all_items'             => __('All Knowledge', 'textdomain'),
        'search_items'          => __('Search Knowledge', 'textdomain'),
        'not_found'             => __('No Knowledge found.', 'textdomain'),
        'not_found_in_trash'    => __('No Knowledge found in Trash.', 'textdomain'),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'has_archive'           => false, // Disable archive page
        'rewrite'               => array('slug' => 'knowledge-square'),
        'supports'              => array('title', 'thumbnail', 'editor'),
        'menu_icon'             => 'dashicons-book-alt', // Menu icon
        'taxonomies'            => array('knowledge_square_category', 'post_tag'), // Add categories and tags
    );

    register_post_type('knowledge-square', $args);

    // Register custom taxonomy for categories
    register_taxonomy(
        'knowledge_square_category',
        'knowledge-square',
        array(
            'labels'            => array(
                'name'          => __('Knowledge Categories', 'textdomain'),
                'singular_name' => __('Knowledge Category', 'textdomain'),
                'menu_name'     => __('Categories'),
            ),
            'hierarchical'      => true, // Hierarchical like categories
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'meta_box_cb'       => 'knowledge_square_category_radio_meta_box',
            'rewrite'           => array('slug' => 'knowledge-square', 'with_front' => true), // Unique slug
            'has_archive'       => true,
        )
    );

    // Add predefined categories
    // if (!term_exists('Research & Studies', 'knowledge_square_category')) {
    //     wp_insert_term('Research & Studies', 'knowledge_square_category');
    // }
    // if (!term_exists('Manuals & Guidelines', 'knowledge_square_category')) {
    //     wp_insert_term('Manuals & Guidelines', 'knowledge_square_category');
    // }
}
add_action('init', 'register_post_type_knowledge_square');

function custom_knowledge_square_rewrite_rules() {
    add_rewrite_rule(
        '^(?:([a-z]{2})/)?knowledge-square/([^/]+)/?$',
        'index.php?knowledge_square_category=$matches[2]',
        'top'
    );
}
add_action('init', 'custom_knowledge_square_rewrite_rules');


// Custom radio button meta box for single category selection
function knowledge_square_category_radio_meta_box($post, $box) {
    $taxonomy = $box['args']['taxonomy'];
    $terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
    $current_term = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids'));

    ?>
    <div id="taxonomy-<?php echo esc_attr($taxonomy); ?>" class="categorydiv">
        <ul id="<?php echo esc_attr($taxonomy); ?>-tabs" class="category-tabs">
            <li class="tabs"><a href="#<?php echo esc_attr($taxonomy); ?>-all" class="taxonomy-menu"><?php _e('All Categories', 'textdomain'); ?></a></li>
        </ul>

        <div id="<?php echo esc_attr($taxonomy); ?>-all" class="tabs-panel">
            <ul id="<?php echo esc_attr($taxonomy); ?>checklist" class="list:<?php echo esc_attr($taxonomy); ?> categorychecklist form-no-clear">
                <?php foreach ($terms as $term): ?>
                    <li id="<?php echo esc_attr($taxonomy); ?>-<?php echo esc_attr($term->term_id); ?>">
                        <label>
                            <input type="radio" name="tax_input[<?php echo esc_attr($taxonomy); ?>][]" value="<?php echo esc_attr($term->term_id); ?>" <?php checked(in_array($term->term_id, $current_term)); ?> />
                            <?php echo esc_html($term->name); ?>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php
}

// Add a custom meta box for PDF upload
function add_knowledge_square_meta_boxes() {
    add_meta_box(
        'knowledge_square_pdf',
        'PDF File',
        'knowledge_square_pdf_meta_box_callback',
        'knowledge-square',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'add_knowledge_square_meta_boxes');

// Callback to display the PDF upload field
function knowledge_square_pdf_meta_box_callback($post) {
    wp_nonce_field('knowledge_square_pdf_meta_box', 'knowledge_square_pdf_meta_box_nonce');
    $pdf_file = get_post_meta($post->ID, '_knowledge_square_pdf', true);
    ?>
    <label for="knowledge_square_pdf">Upload or select a PDF file:</label><br>
    <input type="text" id="knowledge_square_pdf" name="knowledge_square_pdf" value="<?php echo esc_attr($pdf_file); ?>" style="width: 80%;" />
    <button type="button" class="button knowledge-square-pdf-upload">Upload PDF</button>
    <script>
        jQuery(document).ready(function($) {
            jQuery('.knowledge-square-pdf-upload').click(function(e) {
                e.preventDefault();
                var fileFrame = wp.media({
                    title: 'Select or upload PDF',
                    button: { text: 'Use this PDF' },
                    multiple: false,
                    library: {
                        type: 'application/pdf'  // Restrict file type to PDFs
                    },
                    mimeTypes: ['application/pdf']  
                });
                fileFrame.open();
                fileFrame.on('select', function() {
                    var attachment = fileFrame.state().get('selection').first().toJSON();
                    if (attachment.mime === 'application/pdf') {
                        jQuery('input#knowledge_square_pdf').val(attachment.url);  // Set the file URL to an input field
                    } else {
                        alert('Please select a PDF file.');
                    }
                });
            });
        });
    </script>
    <?php
}

// Save the PDF file meta data
function save_knowledge_square_pdf_meta_data($post_id) {
    if (!isset($_POST['knowledge_square_pdf_meta_box_nonce']) || !wp_verify_nonce($_POST['knowledge_square_pdf_meta_box_nonce'], 'knowledge_square_pdf_meta_box')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    if (isset($_POST['knowledge_square_pdf'])) {
        update_post_meta($post_id, '_knowledge_square_pdf', sanitize_text_field($_POST['knowledge_square_pdf']));
    }
}
add_action('save_post', 'save_knowledge_square_pdf_meta_data');



// Add Image Upload Field to "knowledge_square_category" (Edit and Add Screens)
function ks_categories_image_field($term) {
    if( $term instanceof WP_Term ){
        $image_id = get_term_meta($term->term_id, 'ks_category_image', true);
        $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
        $order = get_term_meta($term->term_id, 'ks_category_order', true);
    }else{
        $image_id  = '';
        $image_url = '';
        $order = '';
    }

    ?>
	<div class="form-field">
		<tr class="form-field">
			<th scope="row">
				<label for="ks-category-image"><?php _e('Category Image'); ?></label>
			</th>
			<td>
				<input type="hidden" id="ks-category-image" name="ks_category_image" value="<?php echo esc_attr($image_id); ?>">
				<div id="ks-category-image-preview" style="margin-top: 10px;">
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
				<label for="ks-category-order"><?php _e('Category Order'); ?></label>
			</th>
			<td>
				<input type="number" id="ks-category-order" name="ks_category_order" value="<?php echo esc_attr($order); ?>" min="0">
				<p class="description"><?php _e('Set the display order for this category.'); ?></p>
			</td>
		</tr>
	</div>
    <?php
}
add_action('knowledge_square_category_edit_form_fields', 'ks_categories_image_field');
add_action('knowledge_square_category_add_form_fields', 'ks_categories_image_field');

// Save the Image Field
function save_ks_category_data($term_id) {
    if (isset($_POST['ks_category_image']) && !empty($_POST['ks_category_image'])) {
        update_term_meta($term_id, 'ks_category_image', intval($_POST['ks_category_image']));
    } else {
        delete_term_meta($term_id, 'faq_category_image');
    }

	if (isset($_POST['ks_category_order'])) {
        update_term_meta($term_id, 'ks_category_order', intval($_POST['ks_category_order']));
    } else {
        delete_term_meta($term_id, 'ks_category_order');
    }
}
add_action('edited_knowledge_square_category', 'save_ks_category_data');
add_action('created_knowledge_square_category', 'save_ks_category_data');

function ks_categories_admin_scripts($hook) {
	if (('edit-tags.php' === $hook || 'term.php' === $hook) && isset($_GET['taxonomy']) && $_GET['taxonomy'] === 'knowledge_square_category') {
        wp_enqueue_media(); // Ensure media uploader scripts are loaded
		wp_enqueue_script(
			'knowledge_square_category-meta-box',
			get_template_directory_uri() . '/assets/js/knowledge_square_category-image.js',
			array('jquery'),
			null,
			true
		);
   }
}
add_action('admin_enqueue_scripts', 'ks_categories_admin_scripts');


// Add New Column to Taxonomy Listing
function ks_categories_add_column($columns) {
    $columns['ks_category_image'] = __('Category Image');
	$columns['ks_category_order'] = __('Category Order');
    return $columns;
}
add_filter('manage_edit-knowledge_square_category_columns', 'ks_categories_add_column');

// Populate the Column with Category Images
function ks_categories_column_content($content, $column_name, $term_id) {
    if ($column_name == 'ks_category_image') {
        $image_id = get_term_meta($term_id, 'ks_category_image', true);
        if ($image_id) {
            $image_url = wp_get_attachment_url($image_id);
            $content = '<img src="' . esc_url($image_url) . '" style="max-width:50px; height:auto;">';
        } else {
            $content = __('No Image');
        }
    }

	if ($column_name == 'ks_category_order') {
        $order = get_term_meta($term_id, 'ks_category_order', true);
        if (!empty($order)) {
            $content = $order;
        } else {
            $content = __('N/A');
        }
    }
    return $content;
}
add_filter('manage_knowledge_square_category_custom_column', 'ks_categories_column_content', 10, 3);


?>