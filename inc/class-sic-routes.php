<?php
/**
 * SIC Routes Handler
 * 
 * Centralizes all URL definitions for the SIC Portal dashboard.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SIC_Routes {

    // Define Slugs
    const SLUG_LOGIN              = 'sic-auth';
    const SLUG_DASHBOARD_HOME     = 'sic2026-home';
    const SLUG_CREATE_ORG         = 'sic-create-organization';
    const SLUG_CREATE_PROJECT     = 'sic-create-project';
    const SLUG_MY_PROJECTS        = 'sic-projects';
    const SLUG_MY_ORGANIZATIONS   = 'sic-organizations';

    /**
     * Helper to append current language param
     */
    private static function get_url_with_lang($slug) {
        $url = home_url('/' . $slug . '/');
        if ( isset($_GET['d_lang']) && !empty($_GET['d_lang']) ) {
            $url = add_query_arg('d_lang', sanitize_text_field($_GET['d_lang']), $url);
        }
        return $url;
    }

    /**
     * Get Login URL
     */
    public static function get_login_url() {
        return self::get_url_with_lang(self::SLUG_LOGIN);
    }

    /**
     * Get Dashboard Home URL
     */
    public static function get_dashboard_home_url() {
        return self::get_url_with_lang(self::SLUG_DASHBOARD_HOME);
    }

    /**
     * Get Create Organization URL
     */
    public static function get_create_org_url() {
        return self::get_url_with_lang(self::SLUG_CREATE_ORG);
    }

    /**
     * Get Create Project URL
     */
    public static function get_create_project_url( $org_id = null ) {
        $url = self::get_url_with_lang(self::SLUG_CREATE_PROJECT);
        if ( $org_id ) {
            $url = add_query_arg( 'org_id', $org_id, $url );
        }
        return $url;
    }

    /**
     * Get My Projects URL
     */
    public static function get_my_projects_url() {
        return self::get_url_with_lang(self::SLUG_MY_PROJECTS);
    }

    /**
     * Get My Organizations URL
     */
    public static function get_my_organizations_url() {
        return self::get_url_with_lang(self::SLUG_MY_ORGANIZATIONS);
    }

    // View Routes (Admin/Read-Only)
    const SLUG_VIEW_ORG     = 'sic-view-organization'; // Admin or View Mode
    const SLUG_VIEW_PROJECT = 'sic-view-project';      // Admin or View Mode

    public static function get_view_org_url( $org_id ) {
        $url = self::get_url_with_lang(self::SLUG_VIEW_ORG);
        return add_query_arg( 'org_id', $org_id, $url );
    }

    public static function get_view_project_url( $project_id ) {
        $url = self::get_url_with_lang(self::SLUG_VIEW_PROJECT);
        return add_query_arg( 'project_id', $project_id, $url );
    }
}
