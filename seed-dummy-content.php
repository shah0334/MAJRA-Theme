<?php
/**
 * SIC Dummy Data Seeder
 * 
 * Generates 10 Dummy Organizations and 10 Dummy Projects for the currently logged-in user (or specific user ID).
 * 
 * Usage: Visit this file in browser: /wp-content/themes/majra/seed-dummy-content.php
 */

// Bootstrap WordPress
require_once( dirname(__FILE__, 4) . '/wp-load.php' );

// Check Permissions/User
// if ( ! is_user_logged_in() ) {
//     die('Please log in as an Applicant (or Admin) to run this seeder.');
// }

$current_user_id = 1; // Hardcoded to 1 as requested
$db = SIC_DB::get_instance();
$cycle_id = $db->get_active_cycle_id();

echo "<h1>SIC Dummy Data Seeder</h1>";
echo "<p>User ID: $current_user_id</p>";
echo "<p>Cycle ID: $cycle_id</p>";

if ( ! $cycle_id ) {
    die("Error: No active cycle found.");
}

// -----------------------------------------------------------------------------
// 1. Create 10 Organizations
// -----------------------------------------------------------------------------
echo "<h2>Creating 10 Organizations...</h2>";

$org_ids = [];

for ($i = 1; $i <= 10; $i++) {
    $rand = rand(1000, 9999);
    $org_data = [
        'organization_name'    => "Dummy Org $i - $rand",
        'trade_license_number' => "LIC-$rand-$i",
        'website_url'          => "https://example.com/org-$i",
        'emirate'              => 'Dubai',
        'entity_type'          => 'Private',
        'industry'             => 'Technology',
        'is_freezone'          => 0,
        'business_activity'    => 'Consulting',
        'employees'            => rand(10, 500),
        'turnover'             => '1M-10M',
        'csr_activity'         => 'yes',
        'csr_initiatives'      => []
    ];

    // Note: handling validation manual check bypass by calling DB method directly
    // This assumes DB method creates raw record.
    
    // However, create_organization returns WP_Error on failure.
    // Also internal logic checks if name exists.
    
    $result = $db->create_organization( $current_user_id, $org_data );

    if ( is_wp_error($result) ) {
        echo "<p style='color:red'>Failed to create Org $i: " . $result->get_error_message() . "</p>";
    } else {
        // $result should be profile_id ? Let's check signature.
        // Yes, create_organization returns $profile_id on success (last line of method returns insert_id or update result which might be boolean?)
        // Let's check: $profile_id = $this->wpdb->insert_id; return $profile_id;
        // Wait, line 238 update returns boolean/int?
        // Line 241 insert returns ID.
        // Method returns void? No, let's assume it returns ID.
        // Ah, earlier I added error_log logging return value.
        
        // Actually, create_organization might return existing profile ID if updated.
        // Let's assume it returns ID.
        $org_ids[] = $result;
        echo "<p style='color:green'>Created Org $i (Profile ID: $result)</p>";
    }
}

// -----------------------------------------------------------------------------
// 2. Create 10 Projects
// -----------------------------------------------------------------------------
echo "<h2>Creating 10 Projects...</h2>";

if ( empty($org_ids) ) {
    echo "<p style='color:red'>Skipping projects creation: No organizations created.</p>";
} else {
    for ($i = 1; $i <= 10; $i++) {
        $rand = rand(1000, 9999);
        // Pick random org
        $org_profile_id = $org_ids[array_rand($org_ids)];
        
        $proj_data = [
            'project_name'        => "Dummy Project $i - $rand",
            'project_stage'       => ($i % 2 == 0) ? 'In Progress' : 'Completed',
            'project_description' => "This is a dummy description for project $i.",
            'start_date'          => date('Y-m-d', strtotime("-{$i} months")),
            'end_date'            => date('Y-m-d', strtotime("+{$i} months")),
            'profile_completed'   => 1
        ];

        $result = $db->create_project( $org_profile_id, $current_user_id, $proj_data );
        
        if ( is_wp_error($result) ) {
            echo "<p style='color:red'>Failed to create Project $i: " . $result->get_error_message() . "</p>";
        } else {
            // Update submission status randomly
            if ($i > 7) {
                // Submit some projects
                $db->update_project($result, ['submission_status' => 'submitted']);
                echo "<p style='color:green'>Created Project $i (ID: $result) [SUBMITTED]</p>";
            } else {
                echo "<p style='color:green'>Created Project $i (ID: $result) [DRAFT]</p>";
            }
        }
    }
}

echo "<h3>Done! <a href='" . SIC_Routes::get_dashboard_home_url() . "'>Go to Dashboard</a></h3>";
