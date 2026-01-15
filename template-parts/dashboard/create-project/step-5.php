<?php
/**
 * Step 5: Demographic Information
 */
global $language;
?>
<style>
    .form-range::-webkit-slider-thumb {
        background: #3bc4bd !important;
    }
    .form-range::-moz-range-thumb {
        background: #3bc4bd !important;
    }
    .form-range::-ms-thumb {
        background: #3bc4bd !important;
    }
    .form-check-input:checked {
        background-color: #3bc4bd;
        border-color: #3bc4bd;
    }
</style>

<!-- Eligibility Banner -->
<div class="eligibility-banner p-4 mb-5">
     <div class="d-flex align-items-center">
         <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; background-color: rgba(59, 196, 189, 0.1);">
            <i class="bi bi-info-circle text-cp-aqua-marine"></i>
         </div>
        <p class="font-graphik text-cp-deep-ocean mb-0 fs-6">
            <strong><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['ELIGIBILITY_TITLE_LABEL']; ?></strong> <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['ELIGIBILITY_TEXT']; ?>
        </p>
     </div>
</div>

<div class="row">
    <!-- Main Form Column -->
    <div class="col-lg-8">
        <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-4"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['TITLE']; ?></h2>

         <?php
        $project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;
        $project = null;

        if ($project_id) {
            $db = SIC_DB::get_instance();
            // Ensure DB schema has the new column (Temporary fix for development)
            $db->migrate_founder_youth();
            
            $project = $db->get_project($project_id);
        }

        // Handle Form Submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sic_project_action']) && $_POST['sic_project_action'] === 'save_step_5') {
            if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'sic_save_step_5' ) ) {
                echo '<div class="alert alert-danger">Security check failed.</div>';
                return;
            }

            $project_data = [
                'leadership_women_pct' => floatval($_POST['leadership_women_pct']),
                'team_women_pct'       => floatval($_POST['team_women_pct']),
                'leadership_pod_pct'   => floatval($_POST['leadership_pod_pct']),
                'team_pod_pct'         => floatval($_POST['team_pod_pct']),
                'team_youth_pct'       => floatval($_POST['team_youth_pct']),
                'engages_youth'        => isset($_POST['engages_youth']) ? sanitize_text_field($_POST['engages_youth']) : 'No', // DB might expect 1/0 or Yes/No, checking schema... schema says VARCHAR usually or boolean. Reviewing update_project logic... it just passes data. Let's assume Yes/No string based on dropdown.
                'involves_influencers' => isset($_POST['involves_influencers']) ? sanitize_text_field($_POST['involves_influencers']) : 'No',
                'founder_is_youth'     => isset($_POST['founder_is_youth']) ? sanitize_text_field($_POST['founder_is_youth']) : 'No',
                'demographics_completed' => 1
            ];

            // Validation: Check for negative values
            $pct_fields = ['leadership_women_pct', 'team_women_pct', 'leadership_pod_pct', 'team_pod_pct', 'team_youth_pct'];
            foreach ($pct_fields as $field) {
                if ( isset($project_data[$field]) && $project_data[$field] < 0 ) {
                    echo '<div class="alert alert-danger">Error: Percentage values cannot be negative.</div>';
                    return;
                }
            }
            
            // Note: engages_youth and involves_influencers are likely boolean or tinyint in DB? 
            // Checking Step-5 HTML, they are Yes/No. 
            // In typical SQL boolean is 0/1. Let's convert to 1/0 if needed.
            // Just checked schema in memory: `engages_youth` tinyint(1), `involves_influencers` tinyint(1).
            // So we should convert 'Yes' to 1 and 'No' to 0.
            
            $project_data['engages_youth'] = ($_POST['engages_youth'] === 'Yes') ? 1 : 0;
            $project_data['involves_influencers'] = ($_POST['involves_influencers'] === 'Yes') ? 1 : 0;
            $project_data['founder_is_youth'] = ($_POST['founder_is_youth'] === '1' || $_POST['founder_is_youth'] === 'Yes') ? 1 : 0;

            $db->update_project($project_id, $project_data);

            // Redirect to Step 6
            $next_url = add_query_arg(['step' => 6, 'project_id' => $project_id], SIC_Routes::get_create_project_url());
            wp_redirect($next_url);
            exit;
        }
        
        // Helper for values
        $leadership_women = $project ? $project->leadership_women_pct : '';
        $team_women       = $project ? $project->team_women_pct : '';
        $leadership_pod   = $project ? $project->leadership_pod_pct : '';
        $team_pod         = $project ? $project->team_pod_pct : '';
        $team_youth       = $project ? $project->team_youth_pct : '';
        
        $engages_youth_val = $project ? $project->engages_youth : 0;
        $influencers_val   = $project ? $project->involves_influencers : 0;
        $founder_youth_val = $project ? $project->founder_is_youth : 0;
        ?>

        <form method="POST">
            <?php wp_nonce_field( 'sic_save_step_5' ); ?>
            <input type="hidden" name="sic_project_action" value="save_step_5">

            <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                
                <!-- Gender Balance -->
                <div class="mb-4">
                     <h3 class="font-graphik fw-bold text-cp-deep-ocean mb-3 fs-6"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['GENDER_TITLE']; ?></h3>
                     <div class="row g-3">
                         <div class="col-md-6">
                             <label class="form-label font-graphik text-cp-deep-ocean small">
                                 <span class="text-danger">*</span> <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['LEADERSHIP_WOMEN_LABEL']; ?>
                             </label>
                             <div class="d-flex align-items-center gap-2">
                                 <input type="range" class="form-range w-100" min="0" max="100" step="0.01" name="leadership_women_pct" value="<?php echo esc_attr($leadership_women); ?>" required oninput="this.nextElementSibling.textContent = this.value + '%'">
                                 <span class="font-graphik text-cp-deep-ocean" style="min-width: 48px; text-align: right;"><?php echo esc_html(($leadership_women !== '') ? $leadership_women : '0'); ?>%</span>
                             </div>
                             <div class="error-feedback text-danger small mt-1 d-none"></div>
                         </div>
                         <div class="col-md-6">
                             <label class="form-label font-graphik text-cp-deep-ocean small">
                                 <span class="text-danger">*</span> <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['TEAM_WOMEN_LABEL']; ?>
                             </label>
                             <div class="d-flex align-items-center gap-2">
                                 <input type="range" class="form-range w-100" min="0" max="100" step="0.01" name="team_women_pct" value="<?php echo esc_attr($team_women); ?>" required oninput="this.nextElementSibling.textContent = this.value + '%'">
                                 <span class="font-graphik text-cp-deep-ocean" style="min-width: 48px; text-align: right;"><?php echo esc_html(($team_women !== '') ? $team_women : '0'); ?>%</span>
                             </div>
                             <div class="error-feedback text-danger small mt-1 d-none"></div>
                         </div>
                     </div>
                </div>

                <!-- People of Determination -->
                <div class="mb-4">
                     <h3 class="font-graphik fw-bold text-cp-deep-ocean mb-3 fs-6"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['POD_TITLE']; ?></h3>
                     <div class="row g-3">
                         <div class="col-md-6">
                             <label class="form-label font-graphik text-cp-deep-ocean small">
                                 <span class="text-danger">*</span> <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['LEADERSHIP_POD_LABEL']; ?>
                             </label>
                             <div class="d-flex align-items-center gap-2">
                                 <input type="range" class="form-range w-100" min="0" max="100" step="0.01" name="leadership_pod_pct" value="<?php echo esc_attr($leadership_pod); ?>" oninput="this.nextElementSibling.textContent = this.value + '%'">
                                 <span class="font-graphik text-cp-deep-ocean" style="min-width: 48px; text-align: right;"><?php echo esc_html(($leadership_pod !== '') ? $leadership_pod : '0'); ?>%</span>
                             </div>
                             <div class="error-feedback text-danger small mt-1 d-none"></div>
                         </div>
                         <div class="col-md-6">
                             <label class="form-label font-graphik text-cp-deep-ocean small">
                                 <span class="text-danger">*</span> <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['TEAM_POD_LABEL']; ?>
                             </label>
                             <div class="d-flex align-items-center gap-2">
                                 <input type="range" class="form-range w-100" min="0" max="100" step="0.01" name="team_pod_pct" value="<?php echo esc_attr($team_pod); ?>" oninput="this.nextElementSibling.textContent = this.value + '%'">
                                 <span class="font-graphik text-cp-deep-ocean" style="min-width: 48px; text-align: right;"><?php echo esc_html(($team_pod !== '') ? $team_pod : '0'); ?>%</span>
                             </div>
                             <div class="error-feedback text-danger small mt-1 d-none"></div>
                         </div>
                     </div>
                </div>

                <!-- Youth -->
                <div class="mb-4">
                     <h3 class="font-graphik fw-bold text-cp-deep-ocean mb-3 fs-6"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['YOUTH_TITLE']; ?></h3>
                     <div class="mb-3">
                         <label class="form-label font-graphik text-cp-deep-ocean small">
                             <span class="text-danger">*</span> <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['TEAM_YOUTH_LABEL']; ?>
                         </label>
                         <div class="d-flex align-items-center gap-2">
                             <input type="range" class="form-range w-100" min="0" max="100" step="0.01" name="team_youth_pct" value="<?php echo esc_attr($team_youth); ?>" oninput="this.nextElementSibling.textContent = this.value + '%'">
                             <span class="font-graphik text-cp-deep-ocean" style="min-width: 48px; text-align: right;"><?php echo esc_html(($team_youth !== '') ? $team_youth : '0'); ?>%</span>
                         </div>
                         <div class="error-feedback text-danger small mt-1 d-none"></div>
                     </div>
                     <div class="mb-3">
                         <label class="form-label font-graphik text-cp-deep-ocean small">
                             <span class="text-danger">*</span> <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['ENGAGES_YOUTH_LABEL']; ?>
                         </label>
                         <select name="engages_youth" class="form-select bg-light border-0 fs-6">
                             <option disabled <?php selected($engages_youth_val, 0); ?>><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['SELECT_PLACEHOLDER']; ?></option>
                             <option value="Yes" <?php selected($engages_youth_val, 1); ?>><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['YES']; ?></option>
                             <option value="No" <?php if($project && $engages_youth_val == 0) echo 'selected'; ?>><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['NO']; ?></option>
                         </select>
                     </div>
                </div>

                <!-- Influencers -->
                <!--
                <div class="mb-5">
                     <h3 class="font-graphik fw-bold text-cp-deep-ocean mb-3 fs-6"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['INFLUENCERS_TITLE']; ?></h3>
                     <div>
                         <label class="form-label font-graphik text-cp-deep-ocean small">
                             <span class="text-danger">*</span> <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['INVOLVES_INFLUENCERS_LABEL']; ?>
                         </label>
                         <select name="involves_influencers" class="form-select bg-light border-0 fs-6">
                             <option disabled <?php selected($influencers_val, 0); ?>><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['SELECT_PLACEHOLDER']; ?></option>
                             <option value="Yes" <?php selected($influencers_val, 1); ?>><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['YES']; ?></option>
                             <option value="No" <?php if($project && $influencers_val == 0) echo 'selected'; ?>><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['NO']; ?></option>
                         </select>
                     </div>
                </div>
                -->

                <!-- Founder Information (Replaces Influencers) -->
                <div class="mb-5">
                    <h3 class="font-graphik fw-bold text-cp-deep-ocean mb-3 fs-6">Founder Information</h3>
                    <div>
                        <label class="form-label font-graphik text-cp-deep-ocean small mb-3">
                            <span class="text-danger">*</span> Are you the CEO/Founder and within the youth age bracket (18-35 years)?
                        </label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="founder_is_youth" id="founder_youth_yes" value="Yes" <?php checked($founder_youth_val, 1); ?>>
                                <label class="form-check-label font-graphik text-cp-deep-ocean small" for="founder_youth_yes">
                                    <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['YES']; ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="founder_is_youth" id="founder_youth_no" value="No" <?php checked($founder_youth_val, 0); ?>>
                                <label class="form-check-label font-graphik text-cp-deep-ocean small" for="founder_youth_no">
                                    <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['NO']; ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mandatory Notice -->
                <div class="p-3 rounded-3" style="background-color: #FAEBDA; border: 1px solid #FC9C63;">
                    <p class="font-graphik text-cp-deep-ocean x-small mb-0">
                        <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['MANDATORY_NOTICE']; ?>
                    </p>
                </div>

            </div>

             <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between pt-4 border-top">
                <a href="<?php echo add_query_arg(['step' => 4, 'project_id' => $project_id], SIC_Routes::get_create_project_url()); ?>" class="btn btn-white border px-4 py-2 rounded-3 text-cp-deep-ocean fw-medium"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['BACK_BTN']; ?></a>
                <button type="submit" class="btn btn-custom-aqua px-4 py-2 rounded-3 text-white fw-medium"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_5']['NEXT_BTN']; ?></button>
            </div>

        </form>
    </div>

    <!-- Sidebar Column -->
    <div class="col-lg-4">
        <div class="guidance-panel-detail position-relative rounded-4 overflow-hidden shadow-sm p-4" style="background-color: #f7fafb; height: 45%;">
             <!-- Content -->
             <div class="position-relative z-1 ps-2 pt-2">
                <h3 class="font-mackay fw-bold text-cp-deep-ocean mb-3" style="font-size: 18px; line-height: 27px;">Help us understand your project’s reach.</h3>
                <p class="font-graphik fw-bold text-cp-deep-ocean small mb-3" style="font-size: 14px; line-height: 22.75px;">This information is required for reporting and statistical insights only.</p>
                <div class="font-graphik text-cp-deep-ocean small" style="font-size: 14px; line-height: 22.75px;">
                    <p>Completing this section is mandatory, however your responses will not influence public voting results or project assessment.</p>
                </div>
             </div>
             
             <!-- Background Image -->
             <div class="position-absolute top-0 start-0 w-100 h-100 z-0">
                 <img src="<?php echo get_template_directory_uri(); ?>/assets/img/step-5-sidebar-bg.png" alt="" style="position: absolute; width: 100%; height: 210.92%; top: -11.55%; left: 0; max-width: none; object-fit: cover;">
             </div>
        </div>
    </div>
</div>

<script>
(function() {
    // Initialize slider values on load (handles browser cache/soft refreshes)
    function initSliderDisplays() {
        const ranges = document.querySelectorAll('input[type="range"]');
        ranges.forEach(range => {
            // Trigger the inline oninput handler to sync the span text
            range.dispatchEvent(new Event('input'));
        });
    }

    // Run immediately and on DOMContentLoaded
    initSliderDisplays();
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSliderDisplays);
    }
})();
</script>
