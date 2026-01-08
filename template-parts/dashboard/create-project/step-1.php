<?php
/**
 * Step 1: Project Profile
 */
$db = SIC_DB::get_instance();
$user_id = get_current_user_id();
$applicant = $db->get_applicant_by_wp_user($user_id); 
// Note: get_applicant_by_wp_user might return boolean false if not implemented, using get_applicant_by_id logic or session
// Fallback to session applicant id
$applicant_id = isset($_SESSION['sic_user_id']) ? $_SESSION['sic_user_id'] : 0;
$orgs = $db->get_organizations_by_applicant_id($applicant_id);

$selected_org_id = isset($_GET['org_id']) ? intval($_GET['org_id']) : 0;
$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

$project = null;
if ( $project_id ) {
    $project = $db->get_project($project_id);
    if ( $project && $project->org_profile_id ) {
        // Find org id from profile
        // For now, simpler to just trust the project data or fetch profile to get org_id
        // $selected_org_id = ...
    }
}

// Handle Form Submission
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sic_project_action']) ) {
    // Verify nonce
    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'sic_create_project' ) ) {
        wp_die( 'Security check failed' );
    }

    $org_profile_id = intval($_POST['org_profile_id']);
    
    // Validate Org Profile ID belongs to user? (Skip for now, trust dropdown)

    $submission_data = [
        'project_name'        => sanitize_text_field($_POST['project_name']),
        'project_stage'       => sanitize_text_field($_POST['project_stage']),
        'project_description' => sanitize_textarea_field($_POST['project_description']),
        'start_date'          => sanitize_text_field($_POST['start_date']),
        'end_date'            => sanitize_text_field($_POST['end_date']),
        // Image upload handling to be added here
    ];

    if ( $project_id ) {
        // Update existing
        $db->update_project($project_id, $submission_data);
        $new_project_id = $project_id;
    } else {
        // Create new
        $new_project_id = $db->create_project($org_profile_id, $applicant_id, $submission_data);
        if ( ! is_wp_error($new_project_id) ) {
            $db->update_project($new_project_id, $submission_data); // Update rest of fields
        }
    }

    if ( ! is_wp_error($new_project_id) ) {
        // Handle Image Upload
        if ( !empty($_FILES['project_image']['name']) ) {
            $storage = SIC_Storage::get_instance();
            $upload = $storage->upload_file($_FILES['project_image'], 'project-profiles');
            
            if ( ! is_wp_error($upload) ) {
                $cycle_id = $db->get_active_cycle_id();
                $file_id = $db->save_file($upload, $cycle_id, $applicant_id);
                if ( $file_id ) {
                    $db->link_project_file($new_project_id, 'profile_image', $file_id);
                }
            }
        }

        // Redirect to Step 2
        wp_redirect( add_query_arg(['step' => 2, 'project_id' => $new_project_id], SIC_Routes::get_create_project_url()) );
        exit;
    } else {
        $error_message = $new_project_id->get_error_message();
    }
}
?>

<!-- Eligibility Banner -->
<div class="eligibility-banner p-4 mb-5">
    <p class="font-graphik text-cp-deep-ocean mb-0 fs-6">
        <strong>Only CSR and Sustainability initiatives, programs, events, and projects executed in the United Arab Emirates are eligible.</strong><br>
        All projects submitted to the Sustainable Impact Challenge will be reviewed by Majra for the Qualification Certificate or Verification Stamp, if eligible, which adds to your project’s credibility and opens new opportunities.
    </p>
</div>

<?php if ( isset($error_message) ): ?>
    <div class="alert alert-danger mb-4"><?php echo esc_html($error_message); ?></div>
<?php endif; ?>

<div class="row">
    <!-- Main Form Column -->
    <div class="col-lg-8">
        <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-4">Project Profile</h2>

        <form method="POST" enctype="multipart/form-data">
            <?php wp_nonce_field( 'sic_create_project' ); ?>
            <input type="hidden" name="sic_project_action" value="save_step_1">

            <!-- Select Organization -->
            <div class="mb-4">
                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Select Organization <span class="text-danger">*</span></label>
                <div class="d-flex gap-3">
                    <select name="org_profile_id" class="form-select form-select-lg bg-light border-0 fs-6 flex-grow-1" required <?php echo $project_id ? 'disabled' : ''; ?>>
                        <option value="" disabled <?php selected(!$selected_org_id); ?>>Select the Organization for your project</option>
                        <?php if ($orgs): foreach ($orgs as $org): ?>
                            <option value="<?php echo esc_attr($org->org_profile_id); ?>" <?php selected($selected_org_id, $org->org_profile_id); ?>><?php echo esc_html($org->organization_name); ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                    <?php if ($project_id): ?>
                         <input type="hidden" name="org_profile_id" value="<?php echo esc_attr($project ? $project->org_profile_id : ''); ?>">
                    <?php endif; ?>
                    
                    <a href="<?php echo SIC_Routes::get_create_org_url(); ?>" class="btn btn-outline-info text-nowrap px-4" style="border-color: #3bc4bd; color: #3bc4bd;">
                        Add Organization / إضافة مؤسسة
                    </a>
                </div>
            </div>

            <!-- Project Name -->
            <div class="mb-4">
                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Project Name <span class="text-danger">*</span></label>
                <input type="text" name="project_name" class="form-control form-control-lg bg-light border-0 fs-6" placeholder="Enter the official name of your project" value="<?php echo $project ? esc_attr($project->project_name) : ''; ?>" required>
            </div>

            <!-- Project Status -->
            <div class="mb-4">
                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Project Status <span class="text-danger">*</span></label>
                <select name="project_stage" class="form-select form-select-lg bg-light border-0 fs-6" required>
                    <option value="" disabled <?php selected(empty($project)); ?>>Select the current implementation stage of your project</option>
                    <option value="Planned" <?php selected($project && $project->project_stage == 'Planned'); ?>>Planned</option>
                    <option value="In Progress" <?php selected($project && $project->project_stage == 'In Progress'); ?>>In Progress</option>
                    <option value="Completed" <?php selected($project && $project->project_stage == 'Completed'); ?>>Completed</option>
                </select>
            </div>

            <!-- Project Description -->
            <div class="mb-4">
                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Project Description <span class="text-danger">*</span></label>
                <textarea name="project_description" class="form-control form-control-lg bg-light border-0 fs-6" rows="5" placeholder="Provide a brief overview of what your project aims to achieve and describe the positive impact it can give (maximum 5000 characters)" required><?php echo $project ? esc_textarea($project->project_description) : ''; ?></textarea>
            </div>

            <!-- Start/End Date -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Project Start Date <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="date" name="start_date" class="form-control form-control-lg bg-light border-0 fs-6" value="<?php echo $project ? esc_attr($project->start_date) : ''; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Project End Date <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="date" name="end_date" class="form-control form-control-lg bg-light border-0 fs-6" value="<?php echo $project ? esc_attr($project->end_date) : ''; ?>">
                    </div>
                </div>
            </div>

            <!-- Project Profile Image -->
            <div class="mb-5">
                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Project Profile Image (Public) <span class="text-danger">*</span></label>
                <div class="position-relative">
                    <input type="file" name="project_image" class="form-control form-control-lg bg-light border-0 fs-6 ps-3 pe-5" style="padding-top: 1rem; padding-bottom: 1rem;">
                    <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary" style="pointer-events: none;">
                        Upload an image for the project's public listing.
                    </span>
                    <i class="bi bi-upload position-absolute top-50 end-0 translate-middle-y me-3 text-secondary"></i>
                </div>
            </div>
            
            <div class="text-end mb-5">
                 <button type="submit" class="btn btn-custom-aqua w-auto px-5 py-3 rounded-pill fw-bold text-white fs-6">Save & Continue</button>
            </div>

        </form>
        
        <!-- Navigation Buttons -->
        <!-- <div class="d-flex justify-content-between pt-4 border-top">
            <a href="#" class="btn btn-white border px-4 py-2 rounded-3 text-cp-deep-ocean fw-medium">Back</a>
            <a href="?step=2" class="btn btn-custom-aqua px-4 py-2 rounded-3 text-white fw-medium">Next</a>
        </div> -->
    </div>

    <!-- Sidebar Column -->
    <div class="col-lg-4">
        <div class="guidance-panel-detail position-relative rounded-4 overflow-hidden shadow-sm p-4 h-100" style="background-color: #f7fafb;">
             <!-- Content -->
             <div class="position-relative z-1">
                <h3 class="font-mackay fw-bold text-cp-deep-ocean mb-3">Let’s get into the details.</h3>
                <p class="font-graphik fw-medium text-cp-deep-ocean mb-4">Tell us about your project and its impact to date.</p>
                <div class="font-graphik text-cp-deep-ocean small" style="line-height: 1.6;">
                    <p class="mb-3">This section is your opportunity to describe your project, its objectives, and the results achieved so far. Please ensure all responses are accurate and verifiable, as shortlisted projects will be required to submit supporting evidence for the Sustainable Impact Award.</p>
                    <p>Shortlisted projects will also be shared nationally for public review and voting across the CSR and sustainability categories. Ensure that all information and materials submitted reflect your organization and project in the most credible professional manner.</p>
                </div>
             </div>
             
             <!-- Background Image Overlay (Placeholder/Gradient for now) -->
             <div class="position-absolute bottom-0 start-0 w-100 h-50" style="background: linear-gradient(to top, rgba(59, 196, 189, 0.2), transparent); pointer-events: none;"></div>
        </div>
    </div>
</div>
