<?php
// Load WordPress
require_once('../../../wp-load.php');

global $wpdb;

echo "--- Recent CSR Activities (Raw SQL) ---\n";
// The table name in class-sic-db is 'sic_org_csr_activities' but let's check if prefix is needed?
// SIC_DB uses hardcoded names in constants but also relies on $wpdb context?
// Actually in SIC_DB:
// const TBL_ORG_CSR_ACTIVITIES    = 'sic_org_csr_activities';
// And usage: "SELECT * FROM " . self::TBL_ORG_CSR_ACTIVITIES
// It seems to assume NO prefix if it doesn't use $wpdb->prefix.
// Let's verify if $wpdb->prefix is blank or not.

$rows = $wpdb->get_results("SELECT * FROM sic_org_csr_activities ORDER BY activity_id DESC LIMIT 5");
print_r($rows);

echo "\n--- Recent Org Profiles (Raw SQL) ---\n";
$profiles = $wpdb->get_results("SELECT * FROM sic_organization_profiles ORDER BY org_profile_id DESC LIMIT 1");
print_r($profiles);

if (!empty($profiles)) {
    $pid = $profiles[0]->org_profile_id;
    echo "\n--- SIC_DB::get_org_csr_activities($pid) ---\n";
    $db = SIC_DB::get_instance();
    $activities = $db->get_org_csr_activities($pid);
    print_r($activities);
}
