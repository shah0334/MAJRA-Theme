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
            wp_redirect( home_url('/sic-auth/') );
            exit;
        }
    }
}
add_action('template_redirect', 'sic_check_dashboard_access');
