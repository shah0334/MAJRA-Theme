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
        // Check if user already has an organization with THIS name
        $org_id = $this->wpdb->get_var( $this->wpdb->prepare( 
            "SELECT organization_id FROM " . self::TBL_ORGANIZATIONS . " WHERE created_by_applicant_id = %d AND canonical_name = %s", 
            $applicant_id,
            $data['organization_name']
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

    /**
     * Get Organization Profile by Applicant ID for Active Cycle
     */
    public function get_organization_by_applicant_id( $applicant_id ) {
        $cycle_id = $this->get_active_cycle_id();
        if ( ! $cycle_id ) return null;

        $sql = "
            SELECT 
                op.*, 
                o.canonical_name 
            FROM " . self::TBL_ORG_PROFILES . " op
            JOIN " . self::TBL_ORGANIZATIONS . " o ON op.organization_id = o.organization_id
            WHERE op.created_by_applicant_id = %d 
            AND op.cycle_id = %d
        ";

        return $this->wpdb->get_row( $this->wpdb->prepare( $sql, $applicant_id, $cycle_id ) );
    }

    /**
     * Get All Organization Profiles by Applicant ID for Active Cycle
     */
    public function get_organizations_by_applicant_id( $applicant_id ) {
        $cycle_id = $this->get_active_cycle_id();
        if ( ! $cycle_id ) return [];

        $sql = "
            SELECT 
                op.*, 
                o.canonical_name 
            FROM " . self::TBL_ORG_PROFILES . " op
            JOIN " . self::TBL_ORGANIZATIONS . " o ON op.organization_id = o.organization_id
            WHERE op.created_by_applicant_id = %d 
            AND op.cycle_id = %d
        ";

        return $this->wpdb->get_results( $this->wpdb->prepare( $sql, $applicant_id, $cycle_id ) );
    }

    /**
     * Get File URL by File ID
     */
    public function get_file_url( $file_id ) {
        $sql = "SELECT storage_url FROM " . self::TBL_FILES . " WHERE file_id = %d";
        return $this->wpdb->get_var( $this->wpdb->prepare( $sql, $file_id ) );
    }

    /**
     * Get Organization Profile File URL
     */
    public function get_org_profile_file_url( $profile_id, $role ) {
        $sql = "
            SELECT f.storage_url 
            FROM " . self::TBL_ORG_PROFILE_FILES . " opf
            JOIN " . self::TBL_FILES . " f ON opf.file_id = f.file_id
            WHERE opf.org_profile_id = %d AND opf.file_role = %s
        ";
        return $this->wpdb->get_var( $this->wpdb->prepare( $sql, $profile_id, $role ) );
    }

    /**
     * Create a new Project (Draft)
     */
    public function create_project( $org_profile_id, $user_id, $data ) {
        $applicant = $this->get_applicant_by_id( $user_id );
        if ( ! $applicant ) return new WP_Error( 'invalid_user', 'Applicant not found' );
        
        $cycle_id = $this->get_active_cycle_id();
        if ( ! $cycle_id ) return new WP_Error( 'no_cycle', 'No active cycle' );

        if ( ! $org_profile_id ) return new WP_Error( 'invalid_org', 'Organization is required' );

        $project_data = [
            'cycle_id'                => $cycle_id,
            'org_profile_id'          => $org_profile_id,
            'created_by_applicant_id' => $applicant->applicant_id,
            'project_name'            => $data['project_name'],
            'project_stage'           => $data['project_stage'] ?? 'Planned',
            'submission_status'       => 'draft',
            'profile_completed'       => 0 // Initial state
        ];

        $result = $this->wpdb->insert( self::TBL_PROJECTS, $project_data );
        
        if ( $result === false ) {
            return new WP_Error( 'db_insert_error', 'Database Error: ' . $this->wpdb->last_error );
        }
        
        return $this->wpdb->insert_id;
    }

    /**
     * Update Project Data
     */
    public function update_project( $project_id, $data ) {
        // Separate main table data from relations
        $main_fields = [
            'project_name', 'project_stage', 'project_description', 
            'start_date', 'end_date', 'total_beneficiaries_targeted', 
            'total_beneficiaries_reached', 'contributes_env_social', 
            'has_governance_monitoring', 'location_search_text',
            'location_address', 'location_place_id', 'location_provider',
            'latitude', 'longitude', 'leadership_women_pct',
            'team_women_pct', 'leadership_pod_pct', 'team_pod_pct',
            'team_youth_pct', 'engages_youth', 'involves_influencers',
            'submission_status', 'profile_completed', 'details_completed',
            'evidence_completed', 'pinpoint_completed', 'demographics_completed'
        ];

        $update_data = [];
        foreach ( $main_fields as $field ) {
            if ( isset( $data[$field] ) ) {
                $update_data[$field] = $data[$field];
            }
        }

        if ( !empty($update_data) ) {
            $this->wpdb->update( self::TBL_PROJECTS, $update_data, ['project_id' => $project_id] );
        }

        // Handle Relations
        if ( isset($data['impact_areas']) ) {
            $this->set_project_relations($project_id, self::TBL_PROJECT_IMPACT_AREAS, 'impact_area_id', $data['impact_areas']);
        }
        if ( isset($data['sdgs']) ) {
            $this->set_project_relations($project_id, self::TBL_PROJECT_SDGS, 'sdg_id', $data['sdgs']);
        }
        if ( isset($data['beneficiaries']) ) {
            $this->set_project_relations($project_id, self::TBL_PROJECT_BENEFICIARIES, 'beneficiary_type_id', $data['beneficiaries']);
        }

        return true;
    }

    /**
     * Set Project Relations (M:N)
     */
    private function set_project_relations( $project_id, $table, $col_name, $ids ) {
        // Clear existing
        $this->wpdb->delete( $table, ['project_id' => $project_id] );
        
        if ( empty($ids) || !is_array($ids) ) return;

        foreach ( $ids as $id ) {
            $this->wpdb->insert( 
                $table, 
                ['project_id' => $project_id, $col_name => $id],
                ['%d', '%d']
            );
        }
    }

    /**
     * Get Project by ID
     */
    public function get_project( $project_id ) {
        $project = $this->wpdb->get_row( $this->wpdb->prepare( 
            "SELECT * FROM " . self::TBL_PROJECTS . " WHERE project_id = %d", 
            $project_id 
        ));

        if ( ! $project ) return null;

        // Fetch relations
        $project->impact_areas = $this->get_project_relations($project_id, self::TBL_PROJECT_IMPACT_AREAS, 'impact_area_id');
        $project->sdgs = $this->get_project_relations($project_id, self::TBL_PROJECT_SDGS, 'sdg_id');
        $project->beneficiaries = $this->get_project_relations($project_id, self::TBL_PROJECT_BENEFICIARIES, 'beneficiary_type_id');

        return $project;
    }

    /**
     * Get Project Relations (Helper)
     */
    private function get_project_relations( $project_id, $table, $col_name ) {
        return $this->wpdb->get_col( $this->wpdb->prepare( 
            "SELECT $col_name FROM $table WHERE project_id = %d", 
            $project_id 
        ));
    }

    /**
     * Link File to Project
     */
    public function link_project_file( $project_id, $role, $file_id ) {
        $this->wpdb->replace( 
            self::TBL_PROJECT_FILES, 
            ['project_id' => $project_id, 'file_role' => $role, 'file_id' => $file_id],
            ['%d', '%s', '%d']
        );
    }

    /**
     * Get Project Files
     */
    public function get_project_files( $project_id ) {
        $sql = "
            SELECT pf.file_role, f.file_name, f.file_path, f.original_name
            FROM " . self::TBL_PROJECT_FILES . " pf
            JOIN " . self::TBL_FILES . " f ON pf.file_id = f.file_id
            WHERE pf.project_id = %d
        ";
        return $this->wpdb->get_results( $this->wpdb->prepare( $sql, $project_id ) );
    }

    /**
     * Save Project Link
     */
    public function save_project_link( $project_id, $role, $url ) {
        // Simple check if exists
        $existing = $this->wpdb->get_var( $this->wpdb->prepare(
            "SELECT link_id FROM " . self::TBL_PROJECT_LINKS . " WHERE project_id = %d AND link_role = %s",
            $project_id, $role
        ));

        if ( $existing ) {
            $this->wpdb->update( 
                self::TBL_PROJECT_LINKS, 
                ['url' => $url], 
                ['link_id' => $existing]
            );
        } else {
            $this->wpdb->insert( 
                self::TBL_PROJECT_LINKS, 
                ['project_id' => $project_id, 'link_role' => $role, 'url' => $url]
            );
        }
    }

    /**
     * Get Project Links
     */
    public function get_project_links( $project_id ) {
        return $this->wpdb->get_results( $this->wpdb->prepare( 
            "SELECT link_role, url FROM " . self::TBL_PROJECT_LINKS . " WHERE project_id = %d", 
            $project_id 
        ));
    }

    /**
     * Get Projects by Applicant ID (Draft & Submitted)
     */
    public function get_projects_by_applicant( $applicant_id ) {
        $cycle_id = $this->get_active_cycle_id();
        if ( ! $cycle_id ) return [];

        $sql = "
            SELECT 
                p.*, 
                o.canonical_name as organization_name,
                o.organization_id
            FROM " . self::TBL_PROJECTS . " p
            LEFT JOIN " . self::TBL_ORG_PROFILES . " op ON p.org_profile_id = op.org_profile_id
            LEFT JOIN " . self::TBL_ORGANIZATIONS . " o ON op.organization_id = o.organization_id
            WHERE p.created_by_applicant_id = %d 
            AND p.cycle_id = %d
            ORDER BY p.created_at DESC
        ";

        return $this->wpdb->get_results( $this->wpdb->prepare( $sql, $applicant_id, $cycle_id ) );
    }
}
?>
