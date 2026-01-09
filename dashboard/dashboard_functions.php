<?php
/**
 * SIC Dashboard Module Functions
 * 
 * Isolated dashboard functionality for plug-and-play architecture
 * 
 * @package MAJRA
 * @subpackage SIC_Dashboard
 */

/**
 * Load SIC Classes
 */
require get_template_directory() . '/dashboard/inc/class-sic-db.php';
require get_template_directory() . '/dashboard/inc/class-sic-storage.php';
require get_template_directory() . '/dashboard/inc/class-sic-routes.php';
require get_template_directory() . '/dashboard/inc/auth-check.php';

/**
 * Enqueue Dashboard Styles
 */
function sic_dashboard_enqueue_styles() {
    wp_enqueue_style( 
        'sic-custom-pages', 
        get_template_directory_uri() . '/dashboard/assets/css/custom-pages.css',
        array(),
        _S_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'sic_dashboard_enqueue_styles' );

/**
 * Helper function to load dashboard header
 */
function get_dashboard_header() {
    get_template_part('dashboard/header-dashboard');
}

/**
 * Helper function to load dashboard footer
 */
function get_dashboard_footer() {
    get_template_part('dashboard/footer-dashboard');
}
