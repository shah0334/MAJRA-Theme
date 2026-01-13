<?php
/**
 * SIC Database Migration & Seeder
 * 
 * Usage: 
 * 1. CLI: php run-seed.php
 * 2. Browser: Visit https://majra-local.local/wp-content/themes/majra/run-seed.php (if accessible)
 */

// Database Credentials (from SIC_DB)
$db_host = '127.0.0.1';
$db_user = 'cmjqgkmy_majra_sic_admin';
$db_pass = '+J~Pj!L277Q^';
$db_name = 'cmjqgkmy_majra_sic';

// Connect
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

echo "Connected to database: $db_name\n<br>";

// -----------------------------------------------------------------------------
// 1. Schema Updates (Add name_ar columns)
// -----------------------------------------------------------------------------
echo "<h3>1. Schema Updates</h3>";

$tables_to_update = ['sic_impact_areas', 'sic_beneficiary_types', 'sic_sdgs'];

foreach ($tables_to_update as $table) {
    // Check if column exists
    $result = $mysqli->query("SHOW COLUMNS FROM `$table` LIKE 'name_ar'");
    if ($result && $result->num_rows > 0) {
        echo "Column 'name_ar' already exists in '$table'. Skipping ALTER.\n<br>";
    } else {
        $sql = "ALTER TABLE `$table` ADD COLUMN name_ar VARCHAR(255) NULL AFTER name";
        if ($mysqli->query($sql)) {
            echo "SUCCESS: Added 'name_ar' to '$table'.\n<br>";
        } else {
            echo "ERROR: Could not add 'name_ar' to '$table'. Error: " . $mysqli->error . "\n<br>";
        }
    }
}

// -----------------------------------------------------------------------------
// 2. Seed Data
// -----------------------------------------------------------------------------
echo "<h3>2. Seeding Data</h3>";

// Helper function forUpsert
function upsert_data($mysqli, $table, $id_col, $data) {
    echo "<strong>Seeding $table...</strong>\n<br>";
    $count = 0;
    foreach ($data as $row) {
        $id = intval($row[0]);
        $name = $mysqli->real_escape_string($row[1]);
        $name_ar = $mysqli->real_escape_string($row[2]);

        $sql = "INSERT INTO `$table` ($id_col, name, name_ar) VALUES ($id, '$name', '$name_ar')
                ON DUPLICATE KEY UPDATE name_ar = '$name_ar'"; // Update AR name if ID exists
        
        if ($mysqli->query($sql)) {
            $count++;
        } else {
            echo " - Failed to upsert ID $id: " . $mysqli->error . "\n<br>";
        }
    }
    echo " - Processed $count records.\n<br>";
}

// Data Handling

// Impact Areas
$impact_areas = [
    [1, 'Art, Culture & Heritage', 'الفن والثقافة والتراث'],
    [2, 'Environment', 'البيئة'],
    [3, 'Technology', 'التكنولوجيا'],
    [4, 'Health', 'الصحة'],
    [5, 'Sports', 'الرياضة'],
    [6, 'Education', 'التعليم']
];
upsert_data($mysqli, 'sic_impact_areas', 'impact_area_id', $impact_areas);

// Beneficiaries
$beneficiaries = [
    [1, 'Youth & Students', 'الشباب والطلاب'],
    [2, 'Women & Girls', 'النساء والفتيات'],
    [3, 'Elderly', 'كبار السن'],
    [4, 'People of Determination', 'أصحاب الهمم'],
    [5, 'Families', 'الأسر'],
    [6, 'Small Businesses & Entrepreneurs', 'الشركات الصغيرة ورواد الأعمال'],
    [7, 'Creative Professionals & Innovators', 'المهنيون المبدعون والمبتكرون'],
    [8, 'Third Sector Organizations', 'مؤسسات القطاع الثالث'],
    [9, 'General Public', 'الجمهور العام']
];
upsert_data($mysqli, 'sic_beneficiary_types', 'beneficiary_type_id', $beneficiaries);

// SDGs
$sdgs = [
    [1, 'No Poverty', 'القضاء على الفقر'],
    [2, 'Zero Hunger', 'القضاء التام على الجوع'],
    [3, 'Good Health and Well-being', 'الصحة الجيدة والرفاه'],
    [4, 'Quality Education', 'التعليم الجيد'],
    [5, 'Gender Equality', 'المساواة بين الجنسين'],
    [6, 'Clean Water and Sanitation', 'المياه النظيفة والنظافة الصحية'],
    [7, 'Affordable and Clean Energy', 'طاقة نظيفة وبسعر معقول'],
    [8, 'Decent Work and Economic Growth', 'العمل اللائق ونمو الاقتصاد'],
    [9, 'Industry, Innovation and Infrastructure', 'الصناعة والابتكار والهياكل الأساسية'],
    [10, 'Reduced Inequalities', 'الحد من أوجه عدم المساواة'],
    [11, 'Sustainable Cities and Communities', 'مدن ومجتمعات محلية مستدامة'],
    [12, 'Responsible Consumption and Production', 'الاستهلاك والإنتاج المسؤولان'],
    [13, 'Climate Action', 'العمل المناخي'],
    [14, 'Life Below Water', 'الحياة تحت الماء'],
    [15, 'Life on Land', 'الحياة في البر'],
    [16, 'Peace, Justice and Strong Institutions', 'السلام والعدل والمؤسسات القوية'],
    [17, 'Partnerships for the Goals', 'عقد الشراكات لتحقيق الأهداف']
];
upsert_data($mysqli, 'sic_sdgs', 'sdg_id', $sdgs);

echo "<br><strong>Migration & Seeding Completed!</strong>";
$mysqli->close();
?>
