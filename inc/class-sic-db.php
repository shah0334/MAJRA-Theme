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

    /**
     * Save a file record to the database.
     */
    public function save_file( $upload_result, $cycle_id, $applicant_id ) {
        if ( is_wp_error( $upload_result ) ) {
            return false;
        }

        $this->wpdb->insert( 
            self::TBL_FILES, 
            [
                'cycle_id'               => $cycle_id,
                'storage_provider'       => 'local',
                'storage_url'            => $upload_result['url'],
                'storage_key'            => $upload_result['file'], // Storing local path as key for now
                'original_filename'      => basename($upload_result['file']),
                'mime_type'              => $upload_result['type'],
                'uploaded_by_applicant_id' => $applicant_id
            ],
            ['%d', '%s', '%s', '%s', '%s', '%s', '%d']
        );

        return $this->wpdb->insert_id;
    }

    /**
     * Create or Update Organization Profile.
     */
    public function create_organization( $user_id, $data, $files = [] ) {
        // 1. Get Applicant
        $applicant = $this->get_applicant_by_id( $user_id );
        if ( ! $applicant ) {
            return new WP_Error( 'invalid_user', 'Applicant not found' );
        }
        $applicant_id = $applicant->applicant_id;

        // 2. Get Active Cycle
        $cycle_id = $this->get_active_cycle_id();
        if ( ! $cycle_id ) {
            // Fallback if no cycle is active/seeded (shouldn't happen in prod)
             return new WP_Error( 'no_cycle', 'No active cycle found' );
        }

        // 3. Get or Create Parent Organization
        $org_id = $this->wpdb->get_var( $this->wpdb->prepare( 
            "SELECT organization_id FROM " . self::TBL_ORGANIZATIONS . " WHERE created_by_applicant_id = %d", 
            $applicant_id
        ));

        if ( ! $org_id ) {
            $this->wpdb->insert( 
                self::TBL_ORGANIZATIONS, 
                ['created_by_applicant_id' => $applicant_id, 'canonical_name' => $data['organization_name']], 
                ['%d', '%s'] 
            );
            $org_id = $this->wpdb->insert_id;
            
            // Add as member/owner
            $this->wpdb->insert(
                self::TBL_ORG_MEMBERS,
                ['organization_id' => $org_id, 'applicant_id' => $applicant_id, 'member_role' => 'owner'],
                ['%d', '%d', '%s']
            );
        }

        // 4. Create/Update Org Profile for this Cycle
        // Check if exists
        $profile_id = $this->wpdb->get_var( $this->wpdb->prepare(
             "SELECT org_profile_id FROM " . self::TBL_ORG_PROFILES . " WHERE organization_id = %d AND cycle_id = %d",
             $org_id, $cycle_id
        ));

        $profile_data = [
            'cycle_id'                => $cycle_id,
            'organization_id'         => $org_id,
            'created_by_applicant_id' => $applicant_id,
            'organization_name'       => $data['organization_name'],
            'trade_license_number'    => $data['trade_license_number'],
            'website_url'             => $data['website_url'],
            'emirate_of_registration' => $data['emirate'],
            'legal_entity_type'       => $data['entity_type'],
            'industry'                => $data['industry'],
            'is_freezone'             => isset($data['is_freezone']) ? 1 : 0,
            'business_activity_type'  => $data['business_activity'],
            'number_of_employees'     => intval($data['employees']),
            'annual_turnover_band'    => $data['turnover'],
            'csr_implemented'         => $data['csr_activity'] === 'yes' ? 1 : 0,
            'status'                  => 'draft' // or finalized if submitting
        ];

        if ( $profile_id ) {
            $this->wpdb->update( self::TBL_ORG_PROFILES, $profile_data, ['org_profile_id' => $profile_id] );
        } else {
            $this->wpdb->insert( self::TBL_ORG_PROFILES, $profile_data );
            $profile_id = $this->wpdb->insert_id;
        }

        // 5. Handle Files linking
        if ( isset($files['logo_id']) && $files['logo_id'] ) {
            $this->link_profile_file( $profile_id, 'logo', $files['logo_id'] );
        }
        if ( isset($files['license_id']) && $files['license_id'] ) {
             $this->link_profile_file( $profile_id, 'trade_license_certificate', $files['license_id'] );
        }

        // 6. Handle CSR Activities
        // Clear existing for update logic (simple approach) then re-insert
        $this->wpdb->delete( self::TBL_ORG_CSR_ACTIVITIES, ['org_profile_id' => $profile_id] );
        
        if ( !empty($data['csr_initiatives']) && is_array($data['csr_initiatives']) ) {
            foreach ($data['csr_initiatives'] as $activity) {
                if ( !empty($activity['name']) ) {
                    $this->wpdb->insert( 
                        self::TBL_ORG_CSR_ACTIVITIES, 
                        [
                            'org_profile_id' => $profile_id,
                            'program_name' => $activity['name'],
                            'allocated_amount_aed' => floatval($activity['amount'])
                        ],
                        ['%d', '%s', '%f']
                    );
                }
            }
        }

        return $profile_id;
    }

    private function link_profile_file( $profile_id, $role, $file_id ) {
        $this->wpdb->replace( 
            self::TBL_ORG_PROFILE_FILES, 
            ['org_profile_id' => $profile_id, 'file_role' => $role, 'file_id' => $file_id],
            ['%d', '%s', '%d']
        );
    }
}
?>
