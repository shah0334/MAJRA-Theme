<?php
/**
 * Standalone script to setup SIC Database Tables
 * Usage: Visit this file in browser or run via CLI
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('WP_USE_THEMES', false);

// Try to find wp-load.php
if (file_exists('../../../../../wp-load.php')) {
    require_once('../../../../../wp-load.php');
} elseif (file_exists('../../../wp-load.php')) {
    require_once('../../../wp-load.php');
} else {
    // Last resort fallback
    die('Could not find wp-load.php');
}

// Security check: Only allow admin or CLI
if ( php_sapi_name() !== 'cli' && !current_user_can('manage_options') ) {
   if (!is_user_logged_in()) {
        wp_die('Please log in as administrator to run this script.');
    }
}

echo "<h1>SIC Database Setup</h1>";

$db = SIC_DB::get_instance();
$db_info = $db->get_config_info();

// Check connection only (ignore missing tables)
if ( ! $db->is_connected(false) ) {
    echo "<p style='color:red'><strong>Step 1: Connection Check Failed.</strong></p>";
    echo "<p>Error: " . esc_html($db->get_last_error()) . "</p>";
    echo "<div style='background:#f0f0f0; padding:10px; border-left: 4px solid red;'>";
    echo "Attempted: Host: " . esc_html($db_info['host']) . " | DB: " . esc_html($db_info['name']);
    echo "</div>";
} else {
    echo "<p style='color:green'><strong>Step 1: Connected to Database successfully.</strong></p>";
    echo "<div style='background:#f0f0f0; padding:10px; border-left: 4px solid green;'>";
    echo "Connected to: Host: " . esc_html($db_info['host']) . " | DB: " . esc_html($db_info['name']);
    echo "</div>";
    
    echo "<p>Step 2: Installing tables from schema...</p>";
    $result = $db->install_tables();
    
    if ( is_wp_error($result) ) {
        echo "<p style='color:red'>Installation Failed: " . $result->get_error_message() . "</p>";
    } else {
        echo "<p style='color:green'><strong>Success! Tables have been processed.</strong></p>";
        
        echo "<p>Step 3: Seeding initial data (SIC Program & 2026 Cycle)...</p>";
        $seed_result = $db->seed_tables();
        
        if ( is_wp_error($seed_result) ) {
            echo "<p style='color:red'>Seeding Failed: " . $seed_result->get_error_message() . "</p>";
        } else {
             echo "<p style='color:green'><strong>Success! Data seeded. Cycle 2026 is now active.</strong></p>";
        }

        echo "<p>Check your database to verify tables and data.</p>";
    }
}
?>
