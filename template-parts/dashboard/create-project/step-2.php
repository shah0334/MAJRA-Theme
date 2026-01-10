<?php
/**
 * Step 2: Project Details
 */
$db = SIC_DB::get_instance();
global $language;
$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

if ( ! $project_id ) {
    wp_redirect( SIC_Routes::get_create_project_url() );
    exit;
}

$project = $db->get_project($project_id);
if ( ! $project ) {
    wp_die('Project not found');
}

// Fetch existing relations for pre-filling
// Note: We need methods in SIC_DB to fetch these. For now, assuming empty or need to add get_project_relations later.
// To keep it simple, I'll update get_project to fetch these or just do a direct query here if needed, 
// OR I can add a helper method in this file for now.
// Let's rely on SIC_DB::get_project returning them if possible, otherwise we might lose saved state on refresh.
// *Update plan*: I'll assume for now we don't display saved relations until I update SIC_DB, 
// but the submission will work. TO DO: Update SIC_DB::get_project to include relations.

// Handle Form Submission
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sic_project_action']) ) {
    // Verify nonce
    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'sic_save_step_2' ) ) {
        wp_die( 'Security check failed' );
    }

    // Impact Areas Mapping
    $impact_areas_map = [
        1 => 'impact_area_1', 2 => 'impact_area_2', 3 => 'impact_area_3',
        4 => 'impact_area_4', 5 => 'impact_area_5', 6 => 'impact_area_6'
    ];
    $impact_areas = [];
    foreach ($impact_areas_map as $id => $name) {
        if ( isset($_POST[$name]) ) $impact_areas[] = $id;
    }

    // Beneficiaries Mapping
    $beneficiaries_map = [
        1 => 'beneficiary_1', 2 => 'beneficiary_2', 3 => 'beneficiary_3',
        4 => 'beneficiary_4', 5 => 'beneficiary_5', 6 => 'beneficiary_6',
        7 => 'beneficiary_7', 8 => 'beneficiary_8', 9 => 'beneficiary_9'
    ];
    $beneficiaries = [];
    foreach ($beneficiaries_map as $id => $name) {
        if ( isset($_POST[$name]) ) $beneficiaries[] = $id;
    }

    // SDGs
    $sdgs = isset($_POST['sdgs']) ? array_map('intval', explode(',', $_POST['sdgs'])) : [];

    $submission_data = [
        'total_beneficiaries_targeted' => intval($_POST['total_beneficiaries_targeted']),
        'total_beneficiaries_reached'  => intval($_POST['total_beneficiaries_reached']),
        'contributes_env_social'       => sanitize_text_field($_POST['contributes_env_social']),
        'has_governance_monitoring'    => sanitize_text_field($_POST['has_governance_monitoring']),
        'details_completed'            => 1,
        
        // Multi-selects passed to update_project
        'impact_areas' => $impact_areas,
        'beneficiaries' => $beneficiaries,
        'sdgs' => $sdgs
    ];

    $result = $db->update_project($project_id, $submission_data);

    if ( ! is_wp_error($result) ) {
        // Redirect to Step 3
        wp_redirect( add_query_arg(['step' => 3, 'project_id' => $project_id], SIC_Routes::get_create_project_url()) );
        exit;
    } else {
        $error_message = $result->get_error_message();
    }
}
?>

<!-- Eligibility Banner -->
<div class="eligibility-banner p-4 mb-5">
     <div class="d-flex align-items-center">
         <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; background-color: rgba(59, 196, 189, 0.1);">
            <i class="bi bi-info-circle text-cp-aqua-marine"></i>
         </div>
        <p class="font-graphik text-cp-deep-ocean mb-0 fs-6">
            <strong><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['ELIGIBILITY_NOTICE_LABEL']; ?></strong> <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['ELIGIBILITY_NOTICE_TEXT']; ?>
        </p>
     </div>
</div>

<?php if ( isset($error_message) ): ?>
    <div class="alert alert-danger mb-4"><?php echo esc_html($error_message); ?></div>
<?php endif; ?>

<div class="row">
    <!-- Main Form Column -->
    <div class="col-lg-8">
      
        <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['TITLE']; ?></h2>
        <p class="font-graphik text-secondary mb-5"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SUBTITLE']; ?></p>

        <form method="POST">
            <?php wp_nonce_field( 'sic_save_step_2' ); ?>
            <input type="hidden" name="sic_project_action" value="save_step_2">
            
            <!-- Project Impact Areas -->
            <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                <h3 class="font-graphik fw-bold text-cp-deep-ocean mb-2 fs-5"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['IMPACT_AREAS_TITLE']; ?></h3>
                <p class="font-graphik text-secondary small mb-4"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['IMPACT_AREAS_DESC']; ?></p>
                
                <div class="row g-3">
                    <div class="col-md-6"><div class="form-check"><input name="impact_area_1" class="form-check-input" type="checkbox" id="ia1" <?php checked(in_array(1, $project->impact_areas ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="ia1"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['IA_ART_CULTURE']; ?></label></div></div>
                    <div class="col-md-6"><div class="form-check"><input name="impact_area_2" class="form-check-input" type="checkbox" id="ia2" <?php checked(in_array(2, $project->impact_areas ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="ia2"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['IA_ENV']; ?></label></div></div>
                    <div class="col-md-6"><div class="form-check"><input name="impact_area_3" class="form-check-input" type="checkbox" id="ia3" <?php checked(in_array(3, $project->impact_areas ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="ia3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['IA_TECH']; ?></label></div></div>
                    <div class="col-md-6"><div class="form-check"><input name="impact_area_4" class="form-check-input" type="checkbox" id="ia4" <?php checked(in_array(4, $project->impact_areas ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="ia4"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['IA_HEALTH']; ?></label></div></div>
                    <div class="col-md-6"><div class="form-check"><input name="impact_area_5" class="form-check-input" type="checkbox" id="ia5" <?php checked(in_array(5, $project->impact_areas ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="ia5"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['IA_SPORTS']; ?></label></div></div>
                    <div class="col-md-6"><div class="form-check"><input name="impact_area_6" class="form-check-input" type="checkbox" id="ia6" <?php checked(in_array(6, $project->impact_areas ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="ia6"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['IA_EDU']; ?></label></div></div>
                </div>
            </div>

            <!-- Beneficiaries -->
            <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                 <h3 class="font-graphik fw-bold text-cp-deep-ocean mb-2 fs-5"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['BENEFICIARIES_TITLE']; ?></h3>
                <p class="font-graphik text-secondary small mb-4"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['BENEFICIARIES_DESC']; ?></p>
                 
                 <div class="row g-3 mb-4">
                    <div class="col-md-6"><div class="form-check"><input name="beneficiary_1" class="form-check-input" type="checkbox" id="b1" <?php checked(in_array(1, $project->beneficiaries ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="b1"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['BEN_YOUTH']; ?></label></div></div>
                    <div class="col-md-6"><div class="form-check"><input name="beneficiary_2" class="form-check-input" type="checkbox" id="b2" <?php checked(in_array(2, $project->beneficiaries ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="b2"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['BEN_WOMEN']; ?></label></div></div>
                    <div class="col-md-6"><div class="form-check"><input name="beneficiary_3" class="form-check-input" type="checkbox" id="b3" <?php checked(in_array(3, $project->beneficiaries ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="b3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['BEN_ELDERLY']; ?></label></div></div>
                    <div class="col-md-6"><div class="form-check"><input name="beneficiary_4" class="form-check-input" type="checkbox" id="b4" <?php checked(in_array(4, $project->beneficiaries ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="b4"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['BEN_POD']; ?></label></div></div>
                    <div class="col-md-6"><div class="form-check"><input name="beneficiary_5" class="form-check-input" type="checkbox" id="b5" <?php checked(in_array(5, $project->beneficiaries ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="b5"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['BEN_FAMILIES']; ?></label></div></div>
                    <div class="col-md-6"><div class="form-check"><input name="beneficiary_6" class="form-check-input" type="checkbox" id="b6" <?php checked(in_array(6, $project->beneficiaries ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="b6"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['BEN_SMALL_BIZ']; ?></label></div></div>
                    <div class="col-md-6"><div class="form-check"><input name="beneficiary_7" class="form-check-input" type="checkbox" id="b7" <?php checked(in_array(7, $project->beneficiaries ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="b7"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['BEN_CREATIVE']; ?></label></div></div>
                    <div class="col-md-6"><div class="form-check"><input name="beneficiary_8" class="form-check-input" type="checkbox" id="b8" <?php checked(in_array(8, $project->beneficiaries ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="b8"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['BEN_THIRD_SECTOR']; ?></label></div></div>
                    <div class="col-md-6"><div class="form-check"><input name="beneficiary_9" class="form-check-input" type="checkbox" id="b9" <?php checked(in_array(9, $project->beneficiaries ?? [])); ?>><label class="form-check-label font-graphik text-cp-deep-ocean small" for="b9"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['BEN_PUBLIC']; ?></label></div></div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                         <label class="form-label font-graphik fw-medium text-cp-deep-ocean small"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['TOTAL_BEN_TARGETED']; ?></label>
                         <input type="number" name="total_beneficiaries_targeted" class="form-control" placeholder="e.g., 500" value="<?php echo esc_attr($project->total_beneficiaries_targeted); ?>">
                    </div>
                    <div class="col-md-6">
                         <label class="form-label font-graphik fw-medium text-cp-deep-ocean small"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['TOTAL_BEN_REACHED']; ?></label>
                         <input type="number" name="total_beneficiaries_reached" class="form-control" placeholder="e.g., 435" value="<?php echo esc_attr($project->total_beneficiaries_reached); ?>">
                    </div>
                </div>
                <div class="mt-2">
                     <small class="text-cp-aqua-marine font-graphik">Please make sure to upload relevant evidence in the next step.</small>
                </div>
            </div>

            <!-- Sustainability & Governance -->
             <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                 <h3 class="font-graphik fw-bold text-cp-deep-ocean mb-2 fs-5"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SUST_GOV_TITLE']; ?></h3>
                 <p class="font-graphik text-secondary small mb-4"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SUST_GOV_DESC']; ?></p>

                 <div class="mb-3">
                     <label class="form-label font-graphik fw-medium text-cp-deep-ocean small"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['CONTRIBUTES_ENV_LABEL']; ?></label>
                     <select name="contributes_env_social" class="form-select">
                         <option value="" disabled <?php selected(!$project->contributes_env_social); ?>><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SELECT_OPTION']; ?></option>
                         <option value="Yes" <?php selected($project->contributes_env_social == 'Yes'); ?>>Yes</option>
                         <option value="No" <?php selected($project->contributes_env_social == 'No'); ?>>No</option>
                     </select>
                 </div>
                 <div>
                     <label class="form-label font-graphik fw-medium text-cp-deep-ocean small"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['HAS_GOV_LABEL']; ?></label>
                     <select name="has_governance_monitoring" class="form-select">
                         <option value="" disabled <?php selected(!$project->has_governance_monitoring); ?>><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SELECT_OPTION']; ?></option>
                          <option value="Yes" <?php selected($project->has_governance_monitoring == 'Yes'); ?>>Yes</option>
                         <option value="No" <?php selected($project->has_governance_monitoring == 'No'); ?>>No</option>
                     </select>
                 </div>
             </div>

             <!-- SDG Alignment -->
              <div class="bg-white rounded-4 p-4 shadow-sm mb-5">
                   <h3 class="font-graphik fw-bold text-cp-deep-ocean mb-2 fs-5"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SDG_TITLE']; ?></h3>
                   <div class="d-flex justify-content-between align-items-center mb-4">
                        <p class="font-graphik text-secondary small mb-0"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SDG_DESC']; ?></p>
                        <span id="sdg-count" class="text-cp-aqua-marine small font-graphik fw-medium">0/3 <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SDG_COUNT_SUFFIX']; ?></span>
                   </div>
                   
                   <input type="hidden" name="sdgs" id="sdg-input" value="">

                   <div class="row g-2">
                       <?php 
                       $sdgs = $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SDG_NAMES'];
                       foreach($sdgs as $num => $name):
                         $is_selected = in_array($num, $project->sdgs ?? []);
                         $card_class = $is_selected ? 'sdg-card border rounded-3 p-3 h-100 cursor-pointer d-flex align-items-center gap-3 selected' : 'sdg-card border rounded-3 p-3 h-100 cursor-pointer d-flex align-items-center gap-3';
                       ?>
                       <div class="col-md-4">
                           <div class="<?php echo $card_class; ?>" data-sdg="<?php echo $num; ?>" onclick="toggleSDG(this)">
                               <div class="sdg-icon rounded-3 d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px; background-color: var(--sdg-<?php echo $num; ?>, #E5243B); font-size: 14px;">
                                   <?php echo $num; ?>
                               </div>
                               <span class="font-graphik text-cp-deep-ocean small fw-medium text-truncate-2" style="font-size: 11px; line-height: 1.3;"><?php echo $name; ?></span>
                           </div>
                       </div>
                       <?php endforeach; ?>
                   </div>
              </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between pt-4 border-top">
                <a href="<?php echo add_query_arg(['step' => 1, 'project_id' => $project_id], SIC_Routes::get_create_project_url()); ?>" class="btn btn-white border px-4 py-2 rounded-3 text-cp-deep-ocean fw-medium"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['BACK_BTN']; ?></a>
                <button type="submit" class="btn btn-custom-aqua px-4 py-2 rounded-3 text-white fw-medium"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['NEXT_BTN']; ?></button>
            </div>

        </form>
    </div>

    <!-- Sidebar Column -->
    <div class="col-lg-4">
        <div class="guidance-panel-detail position-relative rounded-4 overflow-hidden shadow-sm p-4 h-50" style="background-color: #f7fafb;">
             <!-- Content -->
             <div class="position-relative z-1">
                <h3 class="font-mackay fw-bold text-cp-deep-ocean mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SIDEBAR_TITLE']; ?></h3>
                <p class="font-graphik fw-medium text-cp-deep-ocean mb-4"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SIDEBAR_SUBTITLE']; ?></p>
                <div class="font-graphik text-cp-deep-ocean small" style="line-height: 1.6;">
                    <p class="mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SIDEBAR_TEXT_1']; ?></p>
                     <p class="mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SIDEBAR_TEXT_2']; ?></p>
                    <p><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SIDEBAR_TEXT_3']; ?></p>
                </div>
             </div>
             
             <!-- Background Image Overlay -->
             <div class="position-absolute bottom-0 start-0 w-100 h-50" style="background: linear-gradient(to top, #f7fafb 10, transparent 100%); z-index: 1; pointer-events: none;"></div>
             <img src="<?php echo get_template_directory_uri(); ?>/assets/img/step-2-sidebar-bg.png" alt="" class="position-absolute bottom-0 start-0 w-100" style="height: 60%; object-fit: cover; z-index: 0; opacity: 1;">
        </div>
    </div>
</div>

<script>
let selectedSDGs = <?php echo json_encode(array_map('strval', $project->sdgs ?? [])); ?>;
document.addEventListener('DOMContentLoaded', function() {
    updateSDGInput();
});

function toggleSDG(element) {
    const id = element.getAttribute('data-sdg');
    
    if (selectedSDGs.includes(id)) {
        // Deselect
        selectedSDGs = selectedSDGs.filter(sdg => sdg !== id);
        element.classList.remove('selected');
        // element.style.borderColor = 'inherit';
        // element.style.backgroundColor = 'white';
    } else {
        // Select (limit 3)
        if (selectedSDGs.length >= 3) {
            alert('<?php echo esc_js($language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SDG_LIMIT_ALERT']); ?>');
            return;
        }
        selectedSDGs.push(id);
        element.classList.add('selected');
    }
    
    updateSDGInput();
}

function updateSDGInput() {
    document.getElementById('sdg-input').value = selectedSDGs.join(',');
    document.getElementById('sdg-count').innerText = selectedSDGs.length + '/3 <?php echo esc_js($language['DASHBOARD']['PROJ_WIZARD']['STEP_2']['SDG_COUNT_SUFFIX']); ?>';
}
</script>

<style>
/* SDG Colors */
:root {
    --sdg-1: #E5243B; --sdg-2: #DDA63A; --sdg-3: #4C9F38; --sdg-4: #C5192D;
    --sdg-5: #FF3A21; --sdg-6: #26BDE2; --sdg-7: #FCC30B; --sdg-8: #A21942;
    --sdg-9: #FD6925; --sdg-10: #DD1367; --sdg-11: #FD9D24; --sdg-12: #BF8B2E;
    --sdg-13: #3F7E44; --sdg-14: #0A97D9; --sdg-15: #56C02B; --sdg-16: #00689D; --sdg-17: #19486A;
}
.sdg-card {
    transition: all 0.2s ease;
    border-color: #dee2e6; /* explicit default */
}
.sdg-card:hover {
    background-color: #f8f9fa;
}
.sdg-card.selected {
    border-color: var(--cp-aqua-marine) !important;
    background-color: #f0fdfc;
    box-shadow: 0 0 0 1px var(--cp-aqua-marine);
}
.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
