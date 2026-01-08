<?php
$PROJECTS_POST_TYPE     = 'projects-post';
$PROJECTS_POST_SLUG     = 'projects';
$PROJECTS_CATEGORY      = 'projects_category';
$PROJECTS_CATEGORY_SLUG = 'project-categories';

function register_post_type_projects() {

    global $PROJECTS_POST_TYPE, $PROJECTS_POST_SLUG, $PROJECTS_CATEGORY, $PROJECTS_CATEGORY_SLUG;

    $labels = array(
        'name'                  => _x('Projects', 'Post type general name', 'textdomain'),
        'singular_name'         => _x('Project', 'Post type singular name', 'textdomain'),
        'menu_name'             => _x('Projects', 'Admin Menu text', 'textdomain'),
        'name_admin_bar'        => _x('Project', 'Add New on Toolbar', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'add_new_item'          => __('Add New Project', 'textdomain'),
        'new_item'              => __('New Project', 'textdomain'),
        'edit_item'             => __('Edit Project', 'textdomain'),
        'view_item'             => __('View Project', 'textdomain'),
        'all_items'             => __('All Projects', 'textdomain'),
        'search_items'          => __('Search Projects', 'textdomain'),
        'not_found'             => __('No projects found.', 'textdomain'),
        'not_found_in_trash'    => __('No projects found in Trash.', 'textdomain'),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'has_archive'           => false,
        'rewrite'               => array('slug' => $PROJECTS_POST_SLUG),
        'supports'              => array('title', 'thumbnail', 'editor'),
        'menu_icon'             => 'dashicons-portfolio',
        //'show_in_rest'          => true,
    );

    register_post_type($PROJECTS_POST_TYPE, $args);

    // Register custom taxonomy for categories
    register_taxonomy(
        $PROJECTS_CATEGORY,
        $PROJECTS_POST_TYPE,
        array(
            'labels'            => array(
                'name'          => __('project Categories', 'textdomain'),
                'singular_name' => __('project Category', 'textdomain'),
                'menu_name'     => __('Categories'),
            ),
            'hierarchical'      => true, // Hierarchical like categories
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'meta_box_cb'       => 'project_category_radio_meta_box',
            'rewrite'           => array('slug' => $PROJECTS_CATEGORY_SLUG), // Unique slug
            'has_archive'       => false,
        )
    );

    // Add predefined categories
    // $cats = array(
    //     'Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Ras Al Khaimah', 'Umm Al Quwain'
    // );
    // foreach ($cats as $cat) {
    //     if (!term_exists($cat, $PROJECTS_CATEGORY)) {
    //         wp_insert_term($cat, $PROJECTS_CATEGORY);
    //     }
    // }
}
add_action('init', 'register_post_type_projects');

function project_category_radio_meta_box($post, $box) {
    $taxonomy = $box['args']['taxonomy']; // Get the taxonomy
    $terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false)); // Fetch all terms
    $current_term = wp_get_object_terms($post->ID, $taxonomy, array('fields' => 'ids')); // Current selected term
    if (!empty($terms)) {
        echo '<div class="taxonomy-radio">';
        foreach ($terms as $term) {
            $checked = (!empty($current_term) && in_array($term->term_id, $current_term)) ? 'checked="checked"' : ''; // Set checked if selected
            echo '<label style="display: block; margin-bottom: 5px;">';
            echo '<input type="checkbox" name="tax_input[' . esc_attr($taxonomy) . ']" value="' . esc_attr($term->term_id) . '" ' . $checked . '>';
            echo esc_html($term->name);
            echo '</label>';
        }
        echo '</div>';
    } else {
        echo '<p>No categories available.</p>';
    }
}

function projects_meta_boxes() {
    global $PROJECTS_POST_TYPE;

    add_meta_box(
        'projects_details',
        __('Project Details', 'textdomain'),
        'projects_meta_box_callback',
        $PROJECTS_POST_TYPE,
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'projects_meta_boxes');

function projects_meta_box_callback($post) {
    // Retrieve existing values
    $partners_logo = get_post_meta($post->ID, '_partners_logo', true) ?: array();
    $sdgs = get_post_meta($post->ID, '_sdgs', true) ?: array();

    $kpi_title = get_post_meta($post->ID, '_kpi_title', true) ?: null; 
    $kpi_desc = get_post_meta($post->ID, '_kpi_desc', true) ?: null; 
    $kpi_image = get_post_meta($post->ID, '_kpi_image', true) ?: null; 
    $kpi_subtitle = get_post_meta($post->ID, '_kpi_subtitle', true) ?: null; 
    $kpis = get_post_meta($post->ID, '_kpis', true) ?: array();

    $people_attended = get_post_meta($post->ID, '_people_attended', true) ?: 0;
    $young_leaders = get_post_meta($post->ID, '_young_leaders', true) ?: 0;
    $companies_participated = get_post_meta($post->ID, '_companies_participated', true) ?: 0;

    wp_nonce_field('save_projects_meta', 'projects_meta_nonce');

    ?>
    <table class="form-table">
        <tr>
            <th><label for="partners_logo"><?php _e('Partners Logo', 'textdomain'); ?></label></th>
            <td>
                <button type="button" id="add-partners-logo" class="button"><?php _e('Add Logos', 'textdomain'); ?></button>
                <ul id="partners-logo-list">
                    <?php foreach ($partners_logo as $logo_id): ?>
                        <li>
                            <img src="<?php echo wp_get_attachment_image_url($logo_id, 'thumbnail'); ?>" class="d-block" style="max-width: 100px;"/>
                            <input type="hidden" name="partners_logo[]" value="<?php echo esc_attr($logo_id); ?>"/>
                            <button type="button" class="remove-logo button d-block" style="margin-top:0.5rem;width: 100px;"><?php _e('Remove', 'textdomain'); ?></button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </td>
        </tr>
        <tr>
            <th><label for="sdgs"><?php _e('SDGs', 'textdomain'); ?></label></th>
            <td>
                <?php for ($i = 1; $i <= 17; $i++): ?>
                    <div class="d-inline-block" style="margin-right:1rem; margin-bottom:1rem">
                        <label>
                            
                            <input 
                                type="checkbox" 
                                name="sdgs[]" 
                                value="<?php echo $i; ?>" 
                                <?php checked(in_array($i, $sdgs)); ?>>
                            <img loading="lazy" src="<?php echo get_template_directory_uri();?>/assets/img/sdgs/SDG-<?php echo $i; ?>.png" style="width:30px">
                        </label>
                    </div>
                <?php endfor; ?>
            </td>
        </tr>
        <tr>
            <th><label for="kpi_title"><?php _e('KPI Title', 'textdomain'); ?></label></th>
            <td>
                <input type="text" id="kpi_title" name="kpi_title" placeholder="Enter KPI Title" value="<?php echo esc_attr($kpi_title); ?>" style="width: 500px;" />
            </td>
        </tr>
        <tr>
            <th><label for="kpi_desc"><?php _e('KPI Description', 'textdomain'); ?></label></th>
            <td>
                <?php
                    wp_editor(
                        $kpi_desc,             // Initial content
                        'kpi_desc_editor',     // Editor ID
                        array(
                            'textarea_name' => 'kpi_desc', // Field name for saving
                            'textarea_rows' => 5,          // Number of rows
                            'media_buttons' => false        // Enable media buttons
                        )
                    );
                ?>
                <!-- <textarea id="kpi_desc" name="kpi_desc" style="width: 500px;" rows="3" placeholder="Enter KPI Description"><?php echo esc_attr($kpi_desc); ?></textarea> -->
            </td>
        </tr>
        <tr>
            <th><label for="kpi_image"><?php _e('KPI Image', 'textdomain'); ?></label></th>
            <td>
                <button type="button" id="add-kpi-image" class="button"><?php _e('Add Image', 'textdomain'); ?></button>
                <ul id="kpi-image-list">
                    <?php if (!empty($kpi_image)){ ?>
                        <li>
                            <img src="<?php echo wp_get_attachment_image_url($kpi_image, 'thumbnail'); ?>" class="d-block" style="max-width: 100px;"/>
                            <input type="hidden" name="kpi_image" value="<?php echo esc_attr($kpi_image); ?>"/>
                            <button type="button" class="remove-kpi-image button d-block" style="margin-top:0.5rem;width: 100px;"><?php _e('Remove', 'textdomain'); ?></button>
                        </li>
                    <?php } ?>
                </ul>
            </td>
        </tr>
        <tr>
            <th><label for="kpi_subtitle"><?php _e('KPI Subtitle', 'textdomain'); ?></label></th>
            <td>
                <?php
                    wp_editor(
                        $kpi_subtitle,          // Initial content
                        'kpi_subtitle_editor',  // Editor ID
                        array(
                            'textarea_name' => 'kpi_subtitle', // Field name for saving
                            'textarea_rows' => 2,              // Number of rows
                            'media_buttons' => false           // Disable media buttons
                        )
                    );
                ?>
            </td>
        </tr>
        <tr>
            <th><label for="kpis"><?php _e('KPIs', 'textdomain'); ?></label></th>
            <td>
                <div id="additional_kpis_container">
                    <?php
                        if (!empty($kpis)) {
                            foreach ($kpis as $kpi) {
                                $kpi_name  = isset($kpi['name']) ? esc_attr($kpi['name']) : '';
                                $kpi_value = isset($kpi['value']) ? esc_attr($kpi['value']) : '';
                                echo '<div class="link-row" style="margin-bottom: 10px; display: flex; gap: 10px; align-items: center;">
                                    <input type="text" name="kpis[name][]" value="' . $kpi_name . '" placeholder="KPI Name" style="width: 45%;" />
                                    <input type="text" name="kpis[value][]" value="' . $kpi_value . '" placeholder="KPI Value" style="width: 45%;" />
                                    <button type="button" class="remove-kpi-button" style="padding: 5px 10px; background-color: #f44336; color: white; border: none; cursor: pointer;">Remove</button>
                                </div>';
                            }
                        }
                    ?>
                </div>
                <button type="button" id="add_kpi_button" style="margin-top: 10px;"><?php _e('Add KPI', 'textdomain'); ?></button>
            </td>
        </tr>
    </table>

    <style>
        .d-block{ display: block }
        .d-inline-block{
            display: inline-block;
        }
        #kpi-image-list,
        #partners-logo-list{ display: flex; gap: 1rem}
    </style>

    <script>
        document.getElementById('add_kpi_button').addEventListener('click', function () {
            const container = document.getElementById('additional_kpis_container');
            const newRow = document.createElement('div');
            newRow.className = 'link-row';
            newRow.style = 'margin-bottom: 10px; display: flex; gap: 10px; align-items: center;';
            newRow.innerHTML = `
                <input type="text" name="kpis[name][]" placeholder="KPI Name" style="width: 45%;" />
                <input type="text" name="kpis[value][]" placeholder="KPI Value" style="width: 45%;" />
                <button type="button" class="remove-kpi-button" style="padding: 5px 10px; background-color: #f44336; color: white; border: none; cursor: pointer;">Remove</button>
            `;
            container.appendChild(newRow);

            // Attach event listener to the Remove button
            attachRemoveListeners(newRow.querySelector('.remove-kpi-button'));
        });

        function attachRemoveListeners(button) {
            button.addEventListener('click', function () {
                const row = this.parentNode;
                row.parentNode.removeChild(row);
            });
        }

        // Attach Remove listeners to existing buttons
        document.querySelectorAll('.remove-kpi-button').forEach(attachRemoveListeners);
    </script>
    <?php
}

function save_projects_meta($post_id) {
    if (!isset($_POST['projects_meta_nonce']) || !wp_verify_nonce($_POST['projects_meta_nonce'], 'save_projects_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $partners_logo = isset($_POST['partners_logo']) ? array_map('intval', $_POST['partners_logo']) : array();
    $sdgs = isset($_POST['sdgs']) ? array_map('intval', $_POST['sdgs']) : array();

    update_post_meta($post_id, '_partners_logo', $partners_logo);
    update_post_meta($post_id, '_sdgs', $sdgs);
    
    // Save KPIs
    if (isset($_POST['kpis'])) {
        $kpis = array();
        $names   = $_POST['kpis']['name'];
        $values  = $_POST['kpis']['value'];

        foreach ($names as $index => $name) {
            if (!empty($values[$index])) {
                $kpis[] = array(
                    'name' => sanitize_text_field($name),
                    'value' => sanitize_text_field($values[$index]),
                );
            }
        }
        update_post_meta($post_id, '_kpis', $kpis);
    }

    $kpi_title = isset($_POST['kpi_title']) ? strval($_POST['kpi_title']) : '';
    $kpi_desc  = isset($_POST['kpi_desc']) ? strval($_POST['kpi_desc']) : '';
    $kpi_image  = isset($_POST['kpi_image']) ? intval($_POST['kpi_image']) : null;
    $kpi_subtitle = isset($_POST['kpi_subtitle']) ? strval($_POST['kpi_subtitle']) : '';
    

    update_post_meta($post_id, '_kpi_title', $kpi_title);
    update_post_meta($post_id, '_kpi_image', $kpi_image);
    update_post_meta($post_id, '_kpi_desc', $kpi_desc);
    update_post_meta($post_id, '_kpi_subtitle', $kpi_subtitle);
}
add_action('save_post', 'save_projects_meta');

function enqueue_admin_scripts($hook) {
    if ($hook === 'post.php' || $hook === 'post-new.php') {
        wp_enqueue_media();
        wp_enqueue_script('projects-meta-box', get_template_directory_uri() . '/assets/js/projects-meta-box.js', array('jquery'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_admin_scripts');
?>