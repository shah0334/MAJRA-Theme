<?php
/**
 * Standalone Migration Script
 * Usage: Visit this file in your browser while logged in as an Administrator.
 * URL: [your-site]/wp-content/themes/majra/run-migration.php
 */

define('WP_USE_THEMES', false);

// Path to wp-load.php (Adjust if your folder structure differs)
// Current location: wp-content/themes/majra/run-migration.php
require_once(dirname(__FILE__) . '/../../../wp-load.php');

// Security Check: Must be Admin
if ( ! current_user_can('manage_options') ) {
    wp_die('Access Denied. You must be an Administrator to run this script.');
}

// Run Migration
echo "<h1>Starting Migration...</h1>";

if ( class_exists('SIC_DB') ) {
    $db = SIC_DB::get_instance();
    
    if ( method_exists($db, 'migrate_contact_iban') ) {
        $db->migrate_contact_iban();
        echo "<p style='color:green;'><strong>Success:</strong> Migration method executed.</p>";
        echo "<p>Checked/Added columns: <code>contact_phone</code>, <code>iban_number</code>, <code>bank_name</code> to <code>sic_organization_profiles</code>.</p>";
    } else {
        echo "<p style='color:red;'><strong>Error:</strong> Method <code>migrate_contact_iban</code> not found in SIC_DB class.</p>";
    }
} else {
    echo "<p style='color:red;'><strong>Error:</strong> SIC_DB class not found.</p>";
}

echo "<p>Migration complete. You can verify by creating an organization.</p>";
echo "<p><em>Please delete this file after use.</em></p>";
