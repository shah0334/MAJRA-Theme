<?php
/* Template Name: Dashboard - View Project */

get_header('dashboard');
global $language;
global $current_language;
$db = SIC_DB::get_instance();

$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;
// Access Check
$can_view = false;
$project = null;

if ( $project_id ) {
    $project = $db->get_project($project_id);
}

if ( !$project ) {
    ?>
    <main id="primary" class="site-main bg-cp-cream-light py-5">
        <div class="container">
            <div class="alert alert-warning"><?php echo $language['DASHBOARD']['VIEW_PROJ']['ERR_NOT_FOUND']; ?></div>
            <a href="<?php echo SIC_Routes::get_my_projects_url(); ?>" class="btn btn-outline-secondary mt-3"><?php echo $language['DASHBOARD']['VIEW_PROJ']['BACK_BTN']; ?></a>
        </div>
    </main>
    <?php
    get_footer('dashboard');
    exit;
}

// Access Check
if ( !current_user_can('manage_options') ) {
    $current_applicant_id = isset($_SESSION['sic_user_id']) ? $_SESSION['sic_user_id'] : 0;
    if ( !$project || $project->created_by_applicant_id != $current_applicant_id ) {
        ?>
        <main id="primary" class="site-main bg-cp-cream-light py-5">
            <div class="container">
                <div class="alert alert-danger"><?php echo $language['DASHBOARD']['VIEW_PROJ']['ERR_UNAUTHORIZED']; ?></div>
            </div>
        </main>
        <?php
        get_footer('dashboard');
        exit;
    }
}

// Fetch Organization Name for context
$org_profile = $db->get_org_profile_by_id( $project->org_profile_id );
$org_name = $org_profile ? $org_profile->canonical_name : 'Unknown Organization';

// Fetch Files
$project_files = $db->get_project_files($project_id);
$hero_image_url = '';
$evidence_files = [];

// Separate files (Hero Image vs Evidence)
// 'profile_image' role for hero (from Step 1), others for evidence via file_role mapping or just all displayed
foreach ($project_files as $f) {
    if ( $f->file_role === 'profile_image' ) {
        $hero_image_url = $f->file_url;
    } else {
        $evidence_files[] = $f;
    }
}

// If no hero image found, maybe use a default or the first image file found?
if ( empty($hero_image_url) ) {
     // Check if we have a generic one or leave empty
     $hero_image_url = get_stylesheet_directory_uri() . '/assets/img/project_placeholder.png'; // Fallback or empty
}

// Helper for Boolean Yes/No
function sic_yes_no($val) {
    global $language;
    return $val ? $language['DASHBOARD']['VIEW_PROJ']['YES'] : $language['DASHBOARD']['VIEW_PROJ']['NO'];
}

// Helper for SDG Colors
function get_sdg_color($num) {
    $colors = [
        1 => '#E5243B', 2 => '#DDA63A', 3 => '#4C9F38', 4 => '#C5192D', 5 => '#FF3A21',
        6 => '#26BDE2', 7 => '#FCC30B', 8 => '#A21942', 9 => '#FD6925', 10 => '#DD1367',
        11 => '#FD9D24', 12 => '#BF8B2E', 13 => '#3F7E44', 14 => '#0A97D9', 15 => '#56C02B',
        16 => '#00689D', 17 => '#19486A'
    ];
    return isset($colors[$num]) ? $colors[$num] : '#333';
}

$sdg_names = $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SDG_NAMES']; 

?>

<main id="primary" class="site-main bg-cp-cream-light py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-4">
            <div>
                <h1 class="font-mackay fw-bold text-cp-deep-ocean mb-1"><?php echo $language['DASHBOARD']['VIEW_PROJ']['PAGE_TITLE']; ?></h1>
                <p class="text-secondary mb-0"><?php echo $language['DASHBOARD']['VIEW_PROJ']['PAGE_SUBTITLE']; ?></p>
            </div>
            <div>
                <a href="<?php echo SIC_Routes::get_my_projects_url(); ?>" class="btn btn-outline-secondary rounded-pill px-4 d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left"></i> <?php echo $language['DASHBOARD']['VIEW_PROJ']['BACK_BTN']; ?>
                </a>
            </div>
        </div>

        <!-- Hero Image -->
        <?php if ($hero_image_url): ?>
        <div class="mb-4">
            <img src="<?php echo esc_url($hero_image_url); ?>" alt="Project Hero" class="project-hero-image" onerror="this.style.display='none'">
        </div>
        <?php endif; ?>

        <!-- 1. Project Overview -->
        <div class="details-section-card">
            <div class="details-header">
                <h2><?php echo $language['DASHBOARD']['VIEW_PROJ']['SECTION_OVERVIEW']; ?></h2>
            </div>
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_PROJ_NAME']; ?></div>
                <div class="details-value"><?php echo esc_html($project->project_name); ?></div>
            </div>
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_ORG_NAME']; ?></div>
                <div class="details-value"><?php echo esc_html($org_name); ?></div>
            </div>
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_STATUS']; ?></div>
                <div class="details-value"><?php echo esc_html($project->project_stage); ?></div>
            </div>
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_START_DATE']; ?></div>
                <div class="details-value"><?php echo $project->start_date ? date('F j, Y', strtotime($project->start_date)) : '-'; ?></div>
            </div>
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_END_DATE']; ?></div>
                <div class="details-value"><?php echo $project->end_date ? date('F j, Y', strtotime($project->end_date)) : '-'; ?></div>
            </div>
        </div>

        <!-- 2. Project Description -->
        <div class="details-section-card">
            <div class="details-header">
                <h2><?php echo $language['DASHBOARD']['VIEW_PROJ']['SECTION_DESC']; ?></h2>
            </div>
            <div class="p-4">
                <p class="mb-0 font-graphik text-cp-deep-ocean" style="line-height: 1.6;">
                    <?php echo nl2br(esc_html($project->project_description)); ?>
                </p>
            </div>
        </div>

        <!-- 3. Impact Scope & Beneficiaries -->
        <div class="details-section-card">
            <div class="details-header">
                <h2><?php echo $language['DASHBOARD']['VIEW_PROJ']['SECTION_IMPACT']; ?></h2>
            </div>
            
            <div class="p-4 border-bottom">
                 <div class="details-label mb-2 d-block w-100"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_MAIN_IMPACT']; ?></div>
                 <div>
                    <?php if (!empty($project->impact_areas_details)): ?>
                        <?php foreach($project->impact_areas_details as $area): 
                            $name = ($current_language == 'ar' && !empty($area->name_ar)) ? $area->name_ar : $area->name;
                        ?>
                            <span class="badge-custom badge-impact"><?php echo esc_html($name); ?></span>
                        <?php endforeach; ?>
                    <?php elseif (!empty($project->impact_areas)): ?>
                         <!-- Fallback if details missing but IDs exist -->
                        <?php foreach($project->impact_areas as $area_id): ?>
                            <span class="badge-custom badge-impact"><?php echo esc_html($area_id); ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-secondary small"><?php echo $language['DASHBOARD']['VIEW_PROJ']['NONE_SELECTED']; ?></span>
                    <?php endif; ?>
                 </div>
            </div>

             <div class="p-4 border-bottom">
                 <div class="details-label mb-2 d-block w-100"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_BENEFICIARIES']; ?></div>
                 <div>
                    <?php if (!empty($project->beneficiaries_details)): ?>
                        <?php foreach($project->beneficiaries_details as $ben): 
                             $name = ($current_language == 'ar' && !empty($ben->name_ar)) ? $ben->name_ar : $ben->name;
                        ?>
                            <span class="badge-custom badge-beneficiary"><?php echo esc_html($name); ?></span>
                        <?php endforeach; ?>
                    <?php elseif (!empty($project->beneficiaries)): ?>
                         <!-- Fallback -->
                        <?php foreach($project->beneficiaries as $ben_id): ?>
                            <span class="badge-custom badge-beneficiary"><?php echo esc_html($ben_id); ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-secondary small"><?php echo $language['DASHBOARD']['VIEW_PROJ']['NONE_SELECTED']; ?></span>
                    <?php endif; ?>
                 </div>
            </div>

            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_TOTAL_TARGETED']; ?></div>
                <div class="details-value fw-bold"><?php echo esc_html($project->total_beneficiaries_targeted); ?></div>
            </div>
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_TOTAL_REACHED']; ?></div>
                <div class="details-value fw-bold"><?php echo esc_html($project->total_beneficiaries_reached); ?></div>
            </div>
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_CONTRIB_UAE']; ?></div>
                <div class="details-value"><?php echo sic_yes_no($project->contributes_env_social); ?></div>
            </div>
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_HAS_GOV']; ?></div>
                <div class="details-value"><?php echo sic_yes_no($project->has_governance_monitoring); ?></div>
            </div>
        </div>

        <!-- 4. SDGs -->
        <div class="details-section-card">
             <div class="details-header">
                <h2><?php echo $language['DASHBOARD']['VIEW_PROJ']['SECTION_SDG']; ?></h2>
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <?php if (!empty($project->sdgs)): ?>
                        <?php foreach($project->sdgs as $sdg_id): 
                            $sdg_num = intval($sdg_id); 
                            $color = get_sdg_color($sdg_num);
                            $name = isset($sdg_names[$sdg_num]) ? $sdg_names[$sdg_num] : 'SDG '.$sdg_num;
                        ?>
                        <div class="col-md-4">
                            <div class="sdg-card">
                                <div class="sdg-icon-wrapper" style="background-color: <?php echo $color; ?>;">
                                    <?php echo $sdg_num; ?>
                                </div>
                                <div class="sdg-text">
                                    <?php echo esc_html($name); ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12"><p class="text-secondary mb-0"><?php echo $language['DASHBOARD']['VIEW_PROJ']['NO_SDGS']; ?></p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 5. Additional Demographic Information -->
        <div class="details-section-card">
            <div class="details-header">
                <h2><?php echo $language['DASHBOARD']['VIEW_PROJ']['SECTION_DEMOGRAPHICS']; ?></h2>
            </div>
            <div class="p-3 bg-warning-subtle mx-4 mt-4 rounded border border-warning-subtle text-warning-emphasis small">
                <i class="bi bi-exclamation-circle me-2"></i> <?php echo $language['DASHBOARD']['VIEW_PROJ']['DEMO_ALERT']; ?>
            </div>

            <div class="p-4">
                <div class="row g-4">
                    <div class="col-md-6 border-bottom pb-3"> <!-- Adjust grid as per design logical separation -->
                         <div class="stat-box">
                            <h5 class="font-mackay fw-bold text-cp-deep-ocean fs-6 mb-3"><?php echo $language['DASHBOARD']['VIEW_PROJ']['SUB_GENDER']; ?></h5>
                            <div class="row">
                                <div class="col-6">
                                    <span class="stat-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_WOMEN_LEADERSHIP']; ?></span>
                                    <span class="stat-value"><?php echo esc_html($project->leadership_women_pct); ?>%</span>
                                </div>
                                <div class="col-6">
                                    <span class="stat-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_WOMEN_TEAM']; ?></span>
                                    <span class="stat-value"><?php echo esc_html($project->team_women_pct); ?>%</span>
                                </div>
                            </div>
                         </div>
                    </div>
                     <div class="col-md-6 border-bottom pb-3">
                         <div class="stat-box">
                            <h5 class="font-mackay fw-bold text-cp-deep-ocean fs-6 mb-3"><?php echo $language['DASHBOARD']['VIEW_PROJ']['SUB_POD']; ?></h5>
                            <div class="row">
                                <div class="col-6">
                                    <span class="stat-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_POD_LEADERSHIP']; ?></span>
                                    <span class="stat-value"><?php echo esc_html($project->leadership_pod_pct); ?>%</span>
                                </div>
                                <div class="col-6">
                                    <span class="stat-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_POD_TEAM']; ?></span>
                                    <span class="stat-value"><?php echo esc_html($project->team_pod_pct); ?>%</span>
                                </div>
                            </div>
                         </div>
                    </div>

                    <div class="col-md-6">
                         <div class="stat-box">
                            <h5 class="font-mackay fw-bold text-cp-deep-ocean fs-6 mb-3"><?php echo $language['DASHBOARD']['VIEW_PROJ']['SUB_YOUTH']; ?></h5>
                             <div class="row">
                                <div class="col-6">
                                    <span class="stat-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_YOUTH_PCT']; ?></span>
                                    <span class="stat-value"><?php echo esc_html($project->team_youth_pct); ?>%</span>
                                </div>
                                <div class="col-6">
                                    <span class="stat-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_YOUTH_ENGAGE']; ?></span>
                                    <span class="stat-value"><?php echo sic_yes_no($project->engages_youth); ?></span>
                                </div>
                            </div>
                         </div>
                    </div>

                    <div class="col-md-6">
                         <div class="stat-box">
                            <h5 class="font-mackay fw-bold text-cp-deep-ocean fs-6 mb-3"><?php echo $language['DASHBOARD']['VIEW_PROJ']['SUB_INFLUENCERS']; ?></h5>
                             <div class="row">
                                <div class="col-12">
                                    <span class="stat-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_INFLUENCERS']; ?></span>
                                    <span class="stat-value"><?php echo sic_yes_no($project->involves_influencers); ?></span>
                                </div>
                            </div>
                         </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- 6. Supporting Evidence -->
        <div class="details-section-card">
            <div class="details-header">
                <h2><?php echo $language['DASHBOARD']['VIEW_PROJ']['SECTION_EVIDENCE']; ?></h2>
            </div>
            <div class="p-4">
                <?php if (!empty($evidence_files)): ?>
                    <?php foreach ($evidence_files as $f): 
                        // Determine icon
                        $icon = 'bi-file-earmark-text';
                        if (strpos($f->file_name, '.pdf') !== false) $icon = 'bi-file-earmark-pdf';
                        elseif (strpos($f->file_name, '.jpg') !== false || strpos($f->file_name, '.png') !== false) $icon = 'bi-image';
                    ?>
                    <div class="mb-3">
                        <div class="file-download-card">
                            <div class="d-flex align-items-center">
                                <div class="file-icon-wrapper">
                                    <i class="bi <?php echo $icon; ?>"></i>
                                </div>
                                <div class="file-info">
                                    <h4><?php echo esc_html($f->file_name); ?></h4>
                                    <div class="file-meta">
                                        <!-- Mocking Size/Date for now as not in DB standard props easily -->
                                        <?php echo $language['DASHBOARD']['VIEW_PROJ']['PREFIX_UPLOADED']; ?> <?php echo date('F j, Y'); ?>
                                    </div>
                                </div>
                            </div>
                            <a href="<?php echo esc_url($f->file_url); ?>" target="_blank" class="btn-download-outline" download>
                                <i class="bi bi-download"></i> <?php echo $language['DASHBOARD']['VIEW_PROJ']['BTN_DOWNLOAD']; ?>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-secondary fst-italic"><?php echo $language['DASHBOARD']['VIEW_PROJ']['NO_DOCS']; ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- 7. Project Location -->
        <div class="details-section-card">
            <div class="details-header">
                <h2><?php echo $language['DASHBOARD']['VIEW_PROJ']['SECTION_LOCATION']; ?></h2>
            </div>
            <div class="p-4">
                <div class="row">
                    <div class="col-12 mb-3">
                         <!-- Map Container -->
                         <div id="project-view-map" class="rounded border" style="height: 300px; width: 100%; background-color: #e5e7eb;"></div>
                    </div>
                    <div class="details-row w-100 border-0 p-0 mb-2">
                         <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_PROJ']['LBL_LOCATION']; ?></div>
                         <div class="details-value"><?php echo esc_html($project->location_address ?: $language['DASHBOARD']['VIEW_PROJ']['NOT_SPECIFIED']); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 8. Declaration -->
        <div class="details-section-card">
            <div class="details-header">
                <h2><?php echo $language['DASHBOARD']['VIEW_PROJ']['SECTION_DECLARATION']; ?></h2>
            </div>
            <div class="p-4">
                <p class="text-secondary small mb-3">
                    <?php echo $language['DASHBOARD']['VIEW_PROJ']['DECLARATION_TEXT']; ?>
                </p>
                <div class="d-flex align-items-center gap-2 mb-2 text-cp-deep-ocean small fw-medium">
                     <i class="bi bi-check-square-fill text-success fs-5"></i> <?php echo $language['DASHBOARD']['VIEW_PROJ']['CHECK_TERMS']; ?>
                </div>
                <div class="d-flex align-items-center gap-2 text-cp-deep-ocean small fw-medium">
                     <i class="bi bi-check-square-fill text-success fs-5"></i> <?php echo $language['DASHBOARD']['VIEW_PROJ']['CHECK_PRIVACY']; ?>
                </div>
            </div>
        </div>

        <div class="mb-5">
             <a href="<?php echo SIC_Routes::get_my_projects_url(); ?>" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> <?php echo $language['DASHBOARD']['VIEW_PROJ']['BACK_BTN']; ?>
            </a>
        </div>

    </div>
</main>

<script>
    function initMap() {
        // Read lat/lng from PHP
        var lat = <?php echo floatval($project->latitude); ?>;
        var lng = <?php echo floatval($project->longitude); ?>;
        
        // If no coordinates, maybe fallback to Dubai default or don't load map
        if (!lat || !lng) {
            lat = 25.2048; 
            lng = 55.2708;
        }

        var mapOptions = {
            center: { lat: lat, lng: lng },
            zoom: 13,
            disableDefaultUI: true, // Clean look for read-only
            zoomControl: true, // Allow zooming
            streetViewControl: false // Disable street view
        };

        var map = new google.maps.Map(document.getElementById("project-view-map"), mapOptions);

        var marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: "<?php echo esc_js($project->project_name); ?>"
        });
    }

    // Ensure map inits if script already loaded
    if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
        initMap();
    }
</script>

<?php get_footer('dashboard'); ?>
