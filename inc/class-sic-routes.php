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
     * Get Login URL
     */
    public static function get_login_url() {
        return home_url( '/' . self::SLUG_LOGIN . '/' );
    }

    /**
     * Get Dashboard Home URL
     */
    public static function get_dashboard_home_url() {
        return home_url( '/' . self::SLUG_DASHBOARD_HOME . '/' );
    }

    /**
     * Get Create Organization URL
     */
    public static function get_create_org_url() {
        return home_url( '/' . self::SLUG_CREATE_ORG . '/' );
    }

    /**
     * Get Create Project URL
     */
    public static function get_create_project_url() {
        return home_url( '/' . self::SLUG_CREATE_PROJECT . '/' );
    }

    /**
     * Get My Projects URL
     */
    public static function get_my_projects_url() {
        return home_url( '/' . self::SLUG_MY_PROJECTS . '/' );
    }

    /**
     * Get My Organizations URL
     */
    public static function get_my_organizations_url() {
        return home_url( '/' . self::SLUG_MY_ORGANIZATIONS . '/' );
    }
}
