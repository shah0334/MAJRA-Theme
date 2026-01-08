<?php
/**
 * DB Check Script
 * Place this in wp-content/themes/majra/db-check.php and visit via browser.
 */

// Load WordPress
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

if (!class_exists('SIC_DB')) {
    die("SIC_DB class not found.");
}

// Get DB Instance (connects via env)
$sic_db = SIC_DB::get_instance();

// Reflection to access private wpdb property for checking
$reflection = new ReflectionClass($sic_db);
$property = $reflection->getProperty('wpdb');
$property->setAccessible(true);
$db_conn = $property->getValue($sic_db);

echo "<h1>SIC Database Status</h1>";

// Check Connection
if ($db_conn && $db_conn->ready) {
    echo "<p style='color:green'><strong>Connection Successful!</strong></p>";
    echo "DB Name: " . $db_conn->dbname . "<br>";
    echo "DB Host: " . $db_conn->dbhost . "<br>";
} else {
    echo "<p style='color:red'><strong>Connection Failed.</strong></p>";
    if ($db_conn && !empty($db_conn->error)) {
        echo "Error: " . $db_conn->error;
    }
    // die(); // Don't die, show table status anyway (it will confirm failure)
}

// Check Tables
$tables = [
    'sic_programs',
    'sic_program_cycles',
    'sic_applicants',
    'sic_organizations',
    'sic_organization_members',
    'sic_organization_profiles',
    'sic_files',
    'sic_projects',
    'sic_impact_areas',
    'sic_beneficiary_types',
    'sic_sdgs'
];

echo "<table border='1' cellpadding='10' style='border-collapse:collapse; width:100%; max-width:600px; margin-top:20px;'>";
echo "<tr><th>Table Name</th><th>Status</th><th>Row Count</th></tr>";

foreach ($tables as $table) {
    // If not connected, this will fail gracefully or return null
    $table_exists = $db_conn->get_var("SHOW TABLES LIKE '$table'") === $table;
    $count = 0;
    $status = "<span style='color:red'>Missing</span>";
    
    if ($table_exists) {
        $status = "<span style='color:green'>Exists</span>";
        $count = $db_conn->get_var("SELECT COUNT(*) FROM $table");
    }

    echo "<tr>";
    echo "<td>$table</td>";
    echo "<td>$status</td>";
    echo "<td>$count</td>";
    echo "</tr>";
}
echo "</table>";
