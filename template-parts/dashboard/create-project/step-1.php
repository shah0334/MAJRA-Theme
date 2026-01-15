<?php
/**
 * Step 1: Project Profile
 */
$db = SIC_DB::get_instance();
global $language;
$user_id = get_current_user_id();
$applicant = $db->get_applicant_by_wp_user($user_id); 
// Note: get_applicant_by_wp_user might return boolean false if not implemented, using get_applicant_by_id logic or session
// Fallback to session applicant id
$applicant_id = isset($_SESSION['sic_user_id']) ? $_SESSION['sic_user_id'] : 0;
// Fetch orgs with high limit for dropdown
$org_data = $db->get_organizations_by_applicant_id($applicant_id, '', 1, 100); 
$orgs = $org_data['results'];

$selected_org_id = isset($_GET['org_id']) ? intval($_GET['org_id']) : 0;
$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

$project = null;
if ( $project_id ) {
    $project = $db->get_project($project_id);

    if ( $project && $project->org_profile_id ) {
        $selected_org_id = $project->org_profile_id;
    }
    
    // Fetch existing profile image
    $files = $db->get_project_files($project_id);
    foreach ($files as $f) {
        if ($f->file_role === 'profile_image') {
            $profile_image_url = $f->file_url;
            break;
        }
    }
}

// Handle Form Submission
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sic_project_action']) ) {
    // Verify nonce
            if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'sic_create_project' ) ) {
                echo '<div class="alert alert-danger">Security check failed.</div>';
                return;
            }

    $org_profile_id = intval($_POST['org_profile_id']);
    
    // Validate Org Profile ID belongs to user? (Skip for now, trust dropdown)

    $submission_data = [
        'project_name'        => sanitize_text_field($_POST['project_name']),
        'project_stage'       => sanitize_text_field($_POST['project_stage']),
        'project_description' => sanitize_textarea_field($_POST['project_description']),
        'start_date'          => sanitize_text_field($_POST['start_date']),
        'end_date'            => sanitize_text_field($_POST['end_date']),
        'profile_completed'   => 1 // Mark Step 1 as completed
        // Image upload handling to be added here
    ];

    // Date Validation
    if ( !empty($submission_data['start_date']) && !empty($submission_data['end_date']) ) {
        if ( strtotime($submission_data['end_date']) < strtotime($submission_data['start_date']) ) {
             $error_message = 'Error: Project end date cannot be earlier than project start date.';
        }
    }

    if ( ! isset($error_message) ) {
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
    } else {
        $new_project_id = new WP_Error('validation_error', $error_message);
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
        <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['ELIGIBILITY_TEXT']; ?>
    </p>
</div>

<?php if ( isset($error_message) ): ?>
    <div class="alert alert-danger mb-4"><?php echo esc_html($error_message); ?></div>
<?php endif; ?>

<div class="row">
    <!-- Main Form Column -->
    <div class="col-lg-8">
        <form method="POST" enctype="multipart/form-data" novalidate id="create-project-step-1-form">
            <?php wp_nonce_field( 'sic_create_project' ); ?>
            <input type="hidden" name="sic_project_action" value="save_step_1">

            <div class="bg-white rounded-4 p-4 shadow-sm">
                <h2 class="font-graphik fw-medium text-cp-deep-ocean mb-4 fs-5"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['TITLE']; ?></h2>

                <!-- Select Organization -->
                <div class="mb-4">
                    <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['SELECT_ORG']; ?> <span class="text-danger">*</span></label>
                    <div class="d-flex gap-3">
                        <select name="org_profile_id" class="form-select flex-grow-1" required <?php echo $project_id ? 'disabled' : ''; ?>>
                            <option value="" disabled <?php selected(!$selected_org_id); ?>><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['SELECT_ORG_DEFAULT']; ?></option>
                            <?php if ($orgs): foreach ($orgs as $org): ?>
                                <option value="<?php echo esc_attr($org->org_profile_id); ?>" <?php selected($selected_org_id, $org->org_profile_id); ?>><?php echo esc_html($org->organization_name); ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                        <?php if ($project_id): ?>
                             <input type="hidden" name="org_profile_id" value="<?php echo esc_attr($project ? $project->org_profile_id : ''); ?>">
                        <?php endif; ?>
                    
                        <a href="<?php echo SIC_Routes::get_create_org_url(); ?>" class="btn btn-outline-info text-nowrap px-4 btn-add-org-custom" style="border-color: #3bc4bd; color: #3bc4bd;">
                            <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['ADD_ORG_BTN']; ?>
                        </a>
                    </div>
                </div>

                <!-- Project Name -->
                <div class="mb-4">
                    <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['PROJ_NAME_LABEL']; ?> <span class="text-danger">*</span></label>
                    <input type="text" name="project_name" class="form-control" placeholder="<?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['PROJ_NAME_PLACEHOLDER']; ?>" value="<?php echo $project ? esc_attr($project->project_name) : ''; ?>" required>
                    <div class="form-text font-graphik fw-medium mt-1" style="color: #0020f6; font-size: 14px;">This title will appear in announcements if your project wins.</div>
                </div>

                <!-- Project Status -->
                <div class="mb-4">
                    <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['PROJ_STATUS_LABEL']; ?> <span class="text-danger">*</span></label>
                    <select name="project_stage" class="form-select" required>
                        <option value="" disabled <?php selected(empty($project)); ?>><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['PROJ_STATUS_DEFAULT']; ?></option>
                        <option value="Planned" <?php selected($project && $project->project_stage == 'Planned'); ?>><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['STATUS_PLANNED']; ?></option>
                        <option value="In Progress" <?php selected($project && $project->project_stage == 'In Progress'); ?>><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['STATUS_IN_PROGRESS']; ?></option>
                        <option value="Completed" <?php selected($project && $project->project_stage == 'Completed'); ?>><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['STATUS_COMPLETED']; ?></option>
                    </select>
                </div>

                <!-- Project Description -->
                <div class="mb-4">
                    <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['PROJ_DESC_LABEL']; ?> <span class="text-danger">*</span></label>
                    <textarea name="project_description" class="form-control" rows="5" placeholder="<?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['PROJ_DESC_PLACEHOLDER']; ?>" required><?php echo $project ? esc_textarea($project->project_description) : ''; ?></textarea>
                </div>

                <!-- Start/End Date -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['START_DATE']; ?> <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="date" name="start_date" class="form-control" value="<?php echo $project ? esc_attr($project->start_date) : ''; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['END_DATE']; ?> <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="date" name="end_date" class="form-control" value="<?php echo $project ? esc_attr($project->end_date) : ''; ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Project Profile Image -->
                <div class="mb-4">
                    <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['PROJ_IMG_LABEL']; ?> <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="file" name="project_image" id="project_image" class="form-control ps-3 pe-5" <?php echo !empty($profile_image_url) ? '' : 'required'; ?>>
                        <i class="bi bi-upload position-absolute top-50 end-0 translate-middle-y me-3 text-secondary pe-none"></i>
                    </div>
                    <div id="project_image_preview" class="mt-2"></div>
                    <div class="form-text text-secondary mt-2"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['PROJ_IMG_HELP']; ?></div>
                </div>
                
                <div class="text-end">
                     <button type="submit" class="btn btn-custom-aqua w-auto px-5 py-3 rounded-3 fw-medium text-white font-graphik" style="font-size: 14px; line-height: 20px;"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_1']['SAVE_BTN']; ?></button>
                </div>
            </div>
        
        <!-- Navigation Buttons -->
        <!-- <div class="d-flex justify-content-between pt-4 border-top">
            <a href="#" class="btn btn-white border px-4 py-2 rounded-3 text-cp-deep-ocean fw-medium">Back</a>
            <?php 
                $next_url = '?step=2';
                if ($project_id) {
                    $next_url = add_query_arg(['step' => 2, 'project_id' => $project_id], SIC_Routes::get_create_project_url());
                }
            ?>
            <a href="<?php echo esc_url($next_url); ?>" class="btn btn-custom-aqua px-4 py-2 rounded-3 text-white fw-medium">Next</a>
        </div> -->
    </div>

    <!-- Sidebar Column -->
    <div class="col-lg-4">
        <div class="guidance-panel-detail position-relative rounded-4 overflow-hidden shadow-sm p-4" style="background-color: #f7fafb; height: 75%;">
             <!-- Content -->
             <div class="position-relative z-1">
                <h3 class="font-mackay fw-bold text-cp-deep-ocean mb-3" style="font-size: 18px; line-height: 27px;">Let’s get into the details.</h3>
                <p class="font-graphik fw-medium text-cp-deep-ocean mb-4" style="font-size: 14px; line-height: 22.75px;">Tell us about your project and its impact to date.</p>
                <div class="font-graphik text-cp-deep-ocean small" style="font-size: 14px; line-height: 21px;">
                    <p class="mb-3">This section is your opportunity to describe your project, its objectives, and the results achieved so far. Please ensure all responses are accurate and verifiable, as shortlisted projects will be required to submit supporting evidence for the Sustainable Impact Award.</p>
                    <p>Shortlisted projects will also be shared nationally for public review and voting across the CSR and sustainability categories. Ensure that all information and materials submitted reflect your organization and project in the most credible professional manner.</p>
                </div>
             </div>
             
             <!-- Background Image -->
             <div class="position-absolute top-0 start-0 w-100 h-100 z-0">
                 <img src="<?php echo get_template_directory_uri(); ?>/assets/img/step-1-sidebar-bg.png" alt="" style="position: absolute; width: 100%; height: 100%; max-width: none; object-fit: cover; object-position: 77% 37%;">
             </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function handleFilePreview(inputId, previewId, isImage = true) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        
        if (!input || !preview) return;

        input.addEventListener('change', function() {
            const file = this.files[0];
            preview.innerHTML = ''; // Clear previous preview

            if (file) {
                if (isImage) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-thumbnail';
                            img.style.maxHeight = '150px';
                            preview.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    } else {
                        preview.innerHTML = '<span class="text-danger">Invalid file type. Please select an image.</span>';
                    }
                }
            }
        });
    }

    // Existing file logic
    const existingProfileImage = "<?php echo isset($profile_image_url) ? esc_url($profile_image_url) : ''; ?>";
    
    handleFilePreview('project_image', 'project_image_preview', true);

    if (existingProfileImage) {
        const preview = document.getElementById('project_image_preview');
        const img = document.createElement('img');
        img.src = existingProfileImage;
        img.className = 'img-thumbnail';
        img.style.maxHeight = '150px';
        preview.appendChild(img);
    }

    // Date Validation
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');
    
    // Initial RTL Check
    const isRTL = document.body.classList.contains('rtl');

    function validateDates() {
        if (startDateInput.value && endDateInput.value) {
            if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
                const msg = isRTL ? 'خطأ: تاريخ الانتهاء لا يمكن أن يكون قبل تاريخ البدء' : 'Error: End date cannot be earlier than start date';
                showError(endDateInput, msg);
            } else {
                removeError(endDateInput);
            }
        }
    }

    if (startDateInput && endDateInput) {
        startDateInput.addEventListener('change', validateDates);
        endDateInput.addEventListener('change', validateDates);
    }

    // Custom Form Validation
    const form = document.getElementById('create-project-step-1-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = form.querySelectorAll('[required]');
            const requiredMsg = isRTL ? 'هذا الحقل مطلوب' : 'This field is required';

            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    showError(field, requiredMsg);
                } else {
                    // Only remove if it's not the end date failing date logic
                    if (field !== endDateInput || (field === endDateInput && (!startDateInput.value || new Date(endDateInput.value) >= new Date(startDateInput.value)))) {
                         removeError(field);
                    }
                }
            });
            
            // Explicitly run date validation again
             if (startDateInput && endDateInput && startDateInput.value && endDateInput.value) {
                if (new Date(endDateInput.value) < new Date(startDateInput.value)) {
                    isValid = false;
                     // Error handled by existing change listener or let's ensure it shows
                    const msg = isRTL ? 'خطأ: تاريخ الانتهاء لا يمكن أن يكون قبل تاريخ البدء' : 'Error: End date cannot be earlier than start date';
                    showError(endDateInput, msg);
                }
            }

            if (!isValid) {
                e.preventDefault();
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });

        const inputs = form.querySelectorAll('[required]');
        inputs.forEach(function(input) {
            const eventType = (input.tagName === 'SELECT' || input.type === 'file' || input.type === 'date') ? 'change' : 'input';
            input.addEventListener(eventType, function() {
                if (this.value.trim()) {
                     removeError(this);
                     if (this === endDateInput || this === startDateInput) {
                         validateDates();
                     }
                }
            });
        });
    }

    function showError(field, msg) {
        field.classList.add('is-invalid');
        let parent = field.parentNode;
        let container = parent;

        if (field.tagName === 'SELECT' && parent.classList.contains('d-flex')) {
            container = parent.parentNode;
        }
        
        let existing = container.querySelector('.invalid-feedback');
        if (!existing) {
            const div = document.createElement('div');
            div.className = 'invalid-feedback d-block';
            div.innerText = msg;
            container.appendChild(div);
        } else {
            existing.innerText = msg;
            existing.style.display = 'block';
        }
    }

    function removeError(field) {
        field.classList.remove('is-invalid');
        let parent = field.parentNode;
        let container = parent;
        
        if (field.tagName === 'SELECT' && parent.classList.contains('d-flex')) {
            container = parent.parentNode;
        }
        
        const existing = container.querySelector('.invalid-feedback');
        if (existing) {
            existing.remove();
        }
    }
});
</script>
