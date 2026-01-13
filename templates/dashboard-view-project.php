<?php
/* Template Name: Dashboard - View Project */

get_header('dashboard');
global $language;
$db = SIC_DB::get_instance();

$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;
// Access Check
// Admin or Owner
$can_view = false;
$project = null;

if ( $project_id ) {
    $project = $db->get_project($project_id);
}

if ( !$project ) {
    ?>
    <main id="primary" class="site-main bg-cp-cream-light py-5">
        <div class="container">
            <div class="alert alert-warning">Project not found.</div>
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
                <div class="alert alert-danger">Unauthorized access. This project does not belong to you.</div>
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
$files_by_role = [];
foreach ($project_files as $f) {
    $files_by_role[$f->file_role][] = $f;
}

// Helper for Boolean Yes/No
function sic_yes_no($val) {
    return $val ? 'Yes' : 'No';
}
?>

<main id="primary" class="site-main bg-cp-cream-light py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-12">
                <a href="<?php echo SIC_Routes::get_dashboard_home_url(); ?>" class="text-decoration-none text-secondary mb-3 d-inline-block"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
                <h1 class="font-mackay fw-bold text-cp-deep-ocean mb-0">Project Details</h1>
                <p class="text-secondary"><?php echo esc_html($org_name); ?> | View Mode</p>
            </div>
        </div>

        <!-- Step 1: Project Profile -->
        <div class="bg-white rounded-lg p-5 shadow-sm mb-4">
             <h3 class="font-mackay text-cp-deep-ocean mb-4 border-bottom pb-2">1. Project Profile</h3>
             <div class="row g-4">
                <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Project Name</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($project->project_name); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Stage</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($project->project_stage); ?></p>
                </div>
                <div class="col-12">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Description</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo nl2br(esc_html($project->project_description)); ?></p>
                </div>
                 <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Start Date</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($project->start_date); ?></p>
                </div>
                 <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">End Date</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($project->end_date); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Target Beneficiaries</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($project->total_beneficiaries_targeted); ?></p>
                </div>
                 <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Reached Beneficiaries</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($project->total_beneficiaries_reached); ?></p>
                </div>
             </div>
        </div>

        <!-- Step 2: Project Details -->
        <div class="bg-white rounded-lg p-5 shadow-sm mb-4">
             <h3 class="font-mackay text-cp-deep-ocean mb-4 border-bottom pb-2">2. Project Details</h3>
              <div class="row g-4">
                <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Contributes to Env/Social?</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo sic_yes_no($project->contributes_env_social); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Has Governance/Monitoring?</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo sic_yes_no($project->has_governance_monitoring); ?></p>
                </div>
                
                <div class="col-12">
                     <label class="font-graphik fw-bold text-secondary text-uppercase small">Impact Areas</label>
                     <p>
                        <?php 
                        if (!empty($project->impact_areas)) {
                             // Fetch names if possible, currently we have IDs.
                             // For this simple view, we might show IDs or need a lookup.
                             // Let's assume IDs for now or fetch names in a real scenario.
                             echo implode(', ', $project->impact_areas); 
                        } else { echo 'None'; }
                        ?>
                     </p>
                </div>
                 <div class="col-12">
                     <label class="font-graphik fw-bold text-secondary text-uppercase small">SDGs</label>
                      <p>
                        <?php 
                        if (!empty($project->sdgs)) {
                             echo implode(', ', $project->sdgs); 
                        } else { echo 'None'; }
                        ?>
                     </p>
                </div>
              </div>
        </div>

        <!-- Step 3: Evidence -->
        <div class="bg-white rounded-lg p-5 shadow-sm mb-4">
            <h3 class="font-mackay text-cp-deep-ocean mb-4 border-bottom pb-2">3. Evidence & Media</h3>
            <?php if (!empty($files_by_role)): ?>
                <?php foreach ($files_by_role as $role => $files): ?>
                    <h5 class="text-capitalize mt-3"><?php echo str_replace('_', ' ', $role); ?></h5>
                    <ul class="list-unstyled">
                    <?php foreach ($files as $f): ?>
                        <li>
                            <a href="<?php echo esc_url($f->file_url); ?>" target="_blank" class="text-decoration-none">
                                <i class="bi bi-file-earmark me-2"></i> <?php echo esc_html($f->file_name); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-secondary">No files uploaded.</p>
            <?php endif; ?>
        </div>

        <!-- Step 4: Location -->
        <div class="bg-white rounded-lg p-5 shadow-sm mb-4">
             <h3 class="font-mackay text-cp-deep-ocean mb-4 border-bottom pb-2">4. Location</h3>
             <p><strong>Address:</strong> <?php echo esc_html($project->location_address ?: 'N/A'); ?></p>
             <!-- Could add map here if API key available -->
        </div>

         <!-- Step 5: Demographics -->
        <div class="bg-white rounded-lg p-5 shadow-sm mb-4">
             <h3 class="font-mackay text-cp-deep-ocean mb-4 border-bottom pb-2">5. Demographics</h3>
              <div class="row g-4">
                <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Leadership Women %</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($project->leadership_women_pct); ?>%</p>
                </div>
                <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Team Women %</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($project->team_women_pct); ?>%</p>
                </div>
                 <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Leadership POD %</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($project->leadership_pod_pct); ?>%</p>
                </div>
                <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Team POD %</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($project->team_pod_pct); ?>%</p>
                </div>
             </div>
        </div>

    </div>
</main>

<?php get_footer('dashboard'); ?>
