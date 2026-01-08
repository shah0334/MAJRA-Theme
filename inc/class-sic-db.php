<?php
/**
 * SIC Database Handler
 * 
 * Handles custom database tables, installation, and data access for the SIC Portal.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SIC_DB {

    private static $instance = null;
    private $wpdb;
    
    // Table names (without prefix)
    const TBL_PROGRAMS              = 'sic_programs';
    const TBL_CYCLES                = 'sic_program_cycles';
    const TBL_APPLICANTS            = 'sic_applicants';
    const TBL_ORGANIZATIONS         = 'sic_organizations';
    const TBL_ORG_MEMBERS           = 'sic_organization_members';
    const TBL_ORG_PROFILES          = 'sic_organization_profiles';
    const TBL_FILES                 = 'sic_files';
    const TBL_ORG_PROFILE_FILES     = 'sic_organization_profile_files';
    const TBL_ORG_CSR_ACTIVITIES    = 'sic_org_csr_activities';
    const TBL_PROJECTS              = 'sic_projects';
    const TBL_PROJECT_FILES         = 'sic_project_files';
    const TBL_PROJECT_LINKS         = 'sic_project_links';
    const TBL_IMPACT_AREAS          = 'sic_impact_areas';
    const TBL_PROJECT_IMPACT_AREAS  = 'sic_project_impact_areas';
    const TBL_BENEFICIARY_TYPES     = 'sic_beneficiary_types';
    const TBL_PROJECT_BENEFICIARIES = 'sic_project_beneficiaries';
    const TBL_SDGS                  = 'sic_sdgs';
    const TBL_PROJECT_SDGS          = 'sic_project_sdgs';

    public static function get_instance() {
        if ( self::$instance == null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->connect();
    }

    /**
     * Establish connection to external database using env file credentials.
     */
    private function connect() {
        $env_file = get_template_directory() . '/env';
        if ( ! file_exists( $env_file ) ) {
            // Fallback to global WPDB if env not found (or handle error)
            error_log('SIC DB: .env file not found. using global wpdb');
            global $wpdb;
            $this->wpdb = $wpdb;
            return;
        }

        $env = parse_ini_file( $env_file );
        
        if ( $env && isset($env['DB_USER'], $env['DB_PASSWORD'], $env['DB_NAME'], $env['DB_HOST']) ) {
            $this->wpdb = new wpdb(
                $env['DB_USER'],
                $env['DB_PASSWORD'],
                $env['DB_NAME'],
                $env['DB_HOST']
            );
        } else {
             error_log('SIC DB: Invalid .env configuration. using global wpdb');
             global $wpdb;
             $this->wpdb = $wpdb;
        }
    }

    /**
     * Get the active cycle ID.
     */
    public function get_active_cycle_id() {
        return $this->wpdb->get_var( "
            SELECT cycle_id FROM " . self::TBL_CYCLES . " 
            WHERE is_active = 1 LIMIT 1
        " );
    }

    /**
     * Get or create an Applicant record for a WP User.
     */
    /**
     * Get or create an Applicant record for a WP User.
     */
    public function get_applicant_by_wp_user( $user_id ) {
        // ... (existing logic if needed, or deprecate/ignore for now)
        return false; 
    }

    /**
     * Get or create a dummy applicant for mock authentication.
     */
    public function get_or_create_dummy_applicant() {
        $email = 'dummy@sic.ae';
        
        // Check if exists
        $row = $this->wpdb->get_row( $this->wpdb->prepare( 
            "SELECT * FROM " . self::TBL_APPLICANTS . " WHERE email = %s", 
            $email 
        ) );

        if ( $row ) {
            return $row;
        }

        // Create
        $this->wpdb->insert( 
            self::TBL_APPLICANTS, 
            [
                'email'      => $email,
                'first_name' => 'SIC',
                'last_name'  => 'User',
                'phone'      => '0500000000'
            ],
            ['%s', '%s', '%s', '%s']
        );

        return $this->wpdb->get_row( $this->wpdb->prepare( 
            "SELECT * FROM " . self::TBL_APPLICANTS . " WHERE applicant_id = %d", 
            $this->wpdb->insert_id
        ) );
    }

    public function get_applicant_by_id($id) {
        return $this->wpdb->get_row( $this->wpdb->prepare( 
            "SELECT * FROM " . self::TBL_APPLICANTS . " WHERE applicant_id = %d", 
            $id 
        ) );
    }

    // --- Helper for debugging installation ---
    public function table_exists($table_name) {
        return $this->wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
    }
}
?>
