<?php
function register_post_type_media_center() {
    $labels = array(
        'name'                  => _x('Media Center', 'Post type general name', 'textdomain'),
        'singular_name'         => _x('Media Center', 'Post type singular name', 'textdomain'),
        'menu_name'             => _x('Media Center', 'Admin Menu text', 'textdomain'),
        'name_admin_bar'        => _x('Media Center', 'Add New on Toolbar', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'add_new_item'          => __('Add Item', 'textdomain'),
        'new_item'              => __('New item', 'textdomain'),
        'edit_item'             => __('Edit item', 'textdomain'),
        'view_item'             => __('View item', 'textdomain'),
        'all_items'             => __('All items', 'textdomain'),
        'search_items'          => __('Search', 'textdomain'),
        'parent_item_colon'     => __('Parent item:', 'textdomain'),
        'not_found'             => __('No item found.', 'textdomain'),
        'not_found_in_trash'    => __('No item found in Trash.', 'textdomain'),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'has_archive'           => false,
        'rewrite'               => array('slug' => 'media-center'), // URL slug
        'supports'              => array('title', 'editor', 'thumbnail'),
        'menu_icon'             => 'dashicons-admin-users', // Dashicon for the menu
        //'show_in_rest'          => true, // Enable Gutenberg/Block Editor support
    );

    register_post_type('media-center-post', $args);
}
add_action('init', 'register_post_type_media_center');

// Add Meta Box
function media_center_add_meta_boxes() {
    add_meta_box(
        'media_center_meta_box',
        __('Media Center Details', 'textdomain'),
        'media_center_meta_box_callback',
        'media-center-post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'media_center_add_meta_boxes');

// Enqueue Media Library Scripts
function enqueue_media_library_script($hook) {
    if ('post.php' === $hook || 'post-new.php' === $hook) {
        wp_enqueue_media();
        wp_enqueue_script(
            'media-center-meta-box',
            get_template_directory_uri() . '/assets/js/media-center-gallery.js',
            array('jquery'),
            null,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'enqueue_media_library_script');

// Meta Box Callback
function media_center_meta_box_callback($post) {
    wp_nonce_field('media_center_save_meta_box_data', 'media_center_meta_box_nonce');

    // Get saved meta data
    $main_article = get_post_meta($post->ID, '_main_article', true);
    $main_article_name = get_post_meta($post->ID, '_main_article_name', true);
    $additional_links = get_post_meta($post->ID, '_additional_links', true) ?: array();
    $gallery_images = get_post_meta($post->ID, '_gallery_images', true) ?: array();

    // Main Article
    ?>
    <strong><label for="main_article_name"><?php _e('Main Article Name:', 'textdomain'); ?></label></strong>
    <div class="link-row" style="margin-bottom: 10px;margin-top: 10px; display: flex; gap: 10px; align-items: center;">
        <input type="text" id="main_article_name" placeholder="Enter Name" name="main_article_name" value="<?php echo esc_attr($main_article_name); ?>" style="width: 33%;" />
        <input type="url" id="main_article" placeholder="Enter Url" name="main_article" value="<?php echo esc_url($main_article); ?>" style="width: 33%;" />
    </div>
    <!-- Additional Links -->
    <p>
        <strong><label for="additional_links"><?php _e('Additional Links (Add Name and URL):', 'textdomain'); ?></label></strong>
        <div id="additional_links_container">
            <?php
            if (!empty($additional_links)) {
                foreach ($additional_links as $link) {
                    $link_name = isset($link['name']) ? esc_attr($link['name']) : '';
                    $link_url = isset($link['url']) ? esc_url($link['url']) : '';
                    echo '<div class="link-row" style="margin-bottom: 10px; display: flex; gap: 10px; align-items: center;">
                        <input type="text" name="additional_links[name][]" value="' . $link_name . '" placeholder="Link Name" style="width: 30%;" />
                        <input type="url" name="additional_links[url][]" value="' . $link_url . '" placeholder="Link URL" style="width: 30%;" />
                        <button type="button" class="remove-link-button" style="padding: 5px 10px; background-color: #f44336; color: white; border: none; cursor: pointer;">Remove</button>
                    </div>';
                }
            }
            ?>
        </div>
        <button type="button" id="add_link_button" style="margin-top: 10px;"><?php _e('Add Link', 'textdomain'); ?></button>
    </p>

    <!-- Gallery Images -->
    <p>
        <label for="gallery_images"><?php _e('Gallery Images:', 'textdomain'); ?></label>
        <button type="button" id="select-images" class="button">Select Images</button>
        <ul id="gallery-images-list">
            <?php foreach ($gallery_images as $image) : ?>
                <li>
                    <img src="<?php echo esc_url(wp_get_attachment_url($image)); ?>" width="100" />
                    <input type="hidden" name="gallery_images[]" value="<?php echo esc_attr($image); ?>" />
                    <button type="button" class="remove-image button">Remove</button>
                </li>
            <?php endforeach; ?>
        </ul>
    </p>

    <script>
        document.getElementById('add_link_button').addEventListener('click', function () {
            const container = document.getElementById('additional_links_container');
            const newRow = document.createElement('div');
            newRow.className = 'link-row';
            newRow.style = 'margin-bottom: 10px; display: flex; gap: 10px; align-items: center;';
            newRow.innerHTML = `
                <input type="text" name="additional_links[name][]" placeholder="Link Name" style="width: 30%;" />
                <input type="url" name="additional_links[url][]" placeholder="Link URL" style="width: 30%;" />
                <button type="button" class="remove-link-button" style="padding: 5px 10px; background-color: #f44336; color: white; border: none; cursor: pointer;">Remove</button>
            `;
            container.appendChild(newRow);

            // Attach event listener to the Remove button
            attachRemoveListeners(newRow.querySelector('.remove-link-button'));
        });

        function attachRemoveListeners(button) {
            button.addEventListener('click', function () {
                const row = this.parentNode;
                row.parentNode.removeChild(row);
            });
        }

        // Attach Remove listeners to existing buttons
        document.querySelectorAll('.remove-link-button').forEach(attachRemoveListeners);
    </script>
    <?php
}

// Save Meta Box Data
function media_center_save_meta_box_data($post_id) {
    if (!isset($_POST['media_center_meta_box_nonce']) || !wp_verify_nonce($_POST['media_center_meta_box_nonce'], 'media_center_save_meta_box_data')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (isset($_POST['post_type']) && 'media-center-post' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Save Main Article Name
    if (isset($_POST['main_article_name'])) {
        update_post_meta($post_id, '_main_article_name', sanitize_text_field($_POST['main_article_name']));
    }

    // Save Main Article URL
    if (isset($_POST['main_article'])) {
        update_post_meta($post_id, '_main_article', esc_url_raw($_POST['main_article']));
    }

    // Save Additional Links
    if (isset($_POST['additional_links'])) {
        $additional_links = array();
        $names = $_POST['additional_links']['name'];
        $urls = $_POST['additional_links']['url'];

        foreach ($names as $index => $name) {
            if (!empty($urls[$index])) {
                $additional_links[] = array(
                    'name' => sanitize_text_field($name),
                    'url' => esc_url_raw($urls[$index]),
                );
            }
        }
        update_post_meta($post_id, '_additional_links', $additional_links);
    }

    if (isset($_POST['gallery_images'])) {
        $gallery_images = array_map('intval', $_POST['gallery_images']);
        update_post_meta($post_id, '_gallery_images', $gallery_images);
    } else {
        delete_post_meta($post_id, '_gallery_images');
    }
}
add_action('save_post', 'media_center_save_meta_box_data');
?>