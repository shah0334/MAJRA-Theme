<?php
/**
 * SIC Access Control
 * Included in functions.php
 */

// Start session if not started
function sic_start_session() {
    if ( ! session_id() ) {
        session_start();
    }
}
add_action('init', 'sic_start_session', 1);

// Handle SIC Logout
function sic_handle_logout() {
    if ( isset($_GET['sic_logout']) && $_GET['sic_logout'] == '1' ) {
        if ( ! session_id() ) session_start();
        
        // Clear specific session vars
        unset($_SESSION['sic_user_id']);
        unset($_SESSION['sic_user_name']);
        
        // Redirect to Login
        wp_redirect( SIC_Routes::get_login_url() );
        exit;
    }
}
add_action('init', 'sic_handle_logout', 5);

// Redirect unauthenticated users from dashboard pages
function sic_check_dashboard_access() {
    $protected_templates = [
        'templates/dashboard-projects.php',
        'templates/dashboard-create-organization.php',
        'templates/dashboard-create-project.php',
        'templates/dashboard-organizations.php',
        'templates/home-no-organization.php'
    ];

    if ( is_page_template( $protected_templates ) ) {
        
        if ( ! isset($_SESSION['sic_user_id']) ) {
            wp_redirect( SIC_Routes::get_login_url() );
            exit;
        }
    }
}
add_action('template_redirect', 'sic_check_dashboard_access');
