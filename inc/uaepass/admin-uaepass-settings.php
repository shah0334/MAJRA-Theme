<?php

// Step 1: Add Admin Menu (Settings → UAE-PASS)
add_action('admin_menu', 'uaepass_add_settings_page');
function uaepass_add_settings_page() {
    add_options_page(
        'UAE-PASS Settings',
        'UAE-PASS Settings',
        'manage_options',
        'uaepass-settings',
        'uaepass_render_settings_page'
    );
}

// Step 2: Register Settings (Settings API)
add_action('admin_init', 'uaepass_register_settings');
function uaepass_register_settings() {
    // Register setting group
    register_setting(
        'uaepass_settings_group',
        'uaepass_settings',
        'uaepass_sanitize_settings'
    );

    // Add section
    add_settings_section(
        'uaepass_main_section',
        'UAE-PASS Configuration',
        null,
        'uaepass-settings'
    );

    // Fields
    add_settings_field(
        'client_id',
        'Client ID',
        'uaepass_client_id_field',
        'uaepass-settings',
        'uaepass_main_section'
    );

    add_settings_field(
        'client_secret',
        'Client Secret',
        'uaepass_client_secret_field',
        'uaepass-settings',
        'uaepass_main_section'
    );

    add_settings_field(
        'redirect_uri',
        'Redirect URI',
        'uaepass_redirect_uri_field',
        'uaepass-settings',
        'uaepass_main_section'
    );

    add_settings_field(
        'environment',
        'Environment',
        'uaepass_environment_field',
        'uaepass-settings',
        'uaepass_main_section'
    );
}

// Step 3: Render Fields
function uaepass_client_id_field() {
    $options = get_option('uaepass_settings');
    ?>
    <input type="text" name="uaepass_settings[client_id]"
           value="<?php echo esc_attr($options['client_id'] ?? ''); ?>"
           class="regular-text">
    <?php
}

function uaepass_client_secret_field() {
    $options = get_option('uaepass_settings');
    ?>
    <input type="password" name="uaepass_settings[client_secret]"
           value="<?php echo esc_attr($options['client_secret'] ?? ''); ?>"
           class="regular-text">
    <?php
}

function uaepass_redirect_uri_field() {
    $options = get_option('uaepass_settings');
    ?>
    <input type="url" name="uaepass_settings[redirect_uri]"
           value="<?php echo esc_attr($options['redirect_uri'] ?? ''); ?>"
           class="regular-text">
    <?php
}

function uaepass_environment_field() {
    $options = get_option('uaepass_settings');
    $env = $options['environment'] ?? 'sandbox';
    ?>
    <select name="uaepass_settings[environment]">
        <option value="sandbox" <?php selected($env, 'sandbox'); ?>>Sandbox</option>
        <option value="production" <?php selected($env, 'production'); ?>>Production</option>
    </select>
    <?php
}

// Step 4: Render Settings Page HTML
function uaepass_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>UAE-PASS Settings</h1>

        <form method="post" action="options.php">
            <?php
                settings_fields('uaepass_settings_group');
                do_settings_sections('uaepass-settings');
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Step 5: Sanitize Inputs (IMPORTANT)
function uaepass_sanitize_settings($input) {
    return [
        'client_id'     => sanitize_text_field($input['client_id'] ?? ''),
        'client_secret' => sanitize_text_field($input['client_secret'] ?? ''),
        'redirect_uri'  => esc_url_raw($input['redirect_uri'] ?? ''),
        'environment'   => in_array($input['environment'], ['sandbox', 'production'])
                            ? $input['environment']
                            : 'sandbox',
    ];
}
?>