<?php
/**
 * Step 3: Supporting Evidence
 */
$db = SIC_DB::get_instance();
$storage = SIC_Storage::get_instance();
global $language;
$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

if ( ! $project_id ) {
    wp_redirect( SIC_Routes::get_create_project_url() );
    exit;
}

$project = $db->get_project($project_id);
if ( ! $project ) {
    echo '<div class="alert alert-danger">Project not found.</div>';
    return;
}


// Fetch existing link
$existing_links = $db->get_project_links($project_id);
$media_link = '';
foreach ($existing_links as $link) {
    if ($link->link_role === 'testimonials_media_coverage') {
        $media_link = $link->url;
        break;
    }
}

// Handle Form Submission
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sic_project_action']) ) {
    // Verify nonce
    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'sic_save_step_3' ) ) {
        echo '<div class="alert alert-danger">Security check failed.</div>';
        return;
    }

    $errors = [];
    $cycle_id = $project->cycle_id;
    $applicant_id = $project->created_by_applicant_id;

    // Helper to upload and link
    $handle_upload = function($file_input_name, $role) use ($db, $storage, $cycle_id, $applicant_id, $project_id, &$errors) {
        if ( !empty($_FILES[$file_input_name]['name']) ) {
            // Check File Size (25MB = 25 * 1024 * 1024 = 26214400 bytes)
            if ($_FILES[$file_input_name]['size'] > 26214400) {
                 $errors[] = "Error uploading $role: " . $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['FILE_SIZE_25MB_ERROR'];
                 return false;
            }

            $upload = $storage->upload_file($_FILES[$file_input_name], 'project-evidence');
            if ( ! is_wp_error($upload) ) {
                $file_id = $db->save_file($upload, $cycle_id, $applicant_id);
                if ( $file_id ) {
                    $db->link_project_file($project_id, $role, $file_id);
                    return true;
                }
            } else {
                $errors[] = "Error uploading $role: " . $upload->get_error_message();
            }
        }
        return false;
    };

    // Process Uploads
    $handle_upload('photos_file', 'photos');
    $handle_upload('impact_report_file', 'impact_report');
    $handle_upload('testimonials_file', 'testimonials_file');
    $handle_upload('sustainable_plan_file', 'sustainable_impact_plan');

    // Process Link
    if ( isset($_POST['media_link']) ) {
        $url = esc_url_raw($_POST['media_link']);
        if ( $url ) {
            $db->save_project_link($project_id, 'testimonials_media_coverage', $url);
        } else {
             // Handle clear link case if needed, or just ignore (it remains empty)
             // Ideally we should allow clearing it?
             // save_project_link updates if exists. if we pass empty url?
             // The db method handles update. If url empty, it saves empty.
             $db->save_project_link($project_id, 'testimonials_media_coverage', '');
        }
    }

    // Validation: Check Mandatory Files
    $current_files = $db->get_project_files($project_id);
    $files_map = [];
    foreach ($current_files as $f) {
        $files_map[$f->file_role] = $f;
    }

    // Photos
    if ( !isset($files_map['photos']) ) {
        $errors[] = $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['PHOTOS_LABEL'] . ' is required.'; 
    }
    // Impact Report
    if ( !isset($files_map['impact_report']) ) {
        $errors[] = $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['IMPACT_REPORT_LABEL'] . ' is required.';
    }
    // Sustainable Plan
    if ( !isset($files_map['sustainable_impact_plan']) ) {
         $errors[] = $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SUST_PLAN_LABEL'] . ' is required.';
    }

    // Testimonials (File OR Link)
    $has_testim_file = isset($files_map['testimonials_file']);
    $has_testim_link = !empty($_POST['media_link']); // Use POST value as it's the current intent

    if ( !$has_testim_file && !$has_testim_link ) {
        $errors[] = "Please provide a Testimonials file OR a Media Link.";
    }

    // Update Status
    if ( empty($errors) ) {
        $db->update_project($project_id, ['evidence_completed' => 1]);
        
        // Redirect to Step 4
        wp_redirect( add_query_arg(['step' => 4, 'project_id' => $project_id], SIC_Routes::get_create_project_url()) );
        exit;
    } else {
        $error_message = implode('<br>', $errors);
    }
}
?>

<!-- Eligibility Banner -->
<div class="eligibility-banner p-4 mb-5" style="border-color: #FC9C63; background: linear-gradient(to bottom, #FFF5EC, #FFFFFF);">
     <p class="font-graphik text-cp-deep-ocean mb-0 fs-6">
        <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['ELIGIBILITY_OPTIONAL_TEXT']; ?>
    </p>
</div>

<?php if ( isset($error_message) ): ?>
    <div class="alert alert-danger mb-4"><?php echo $error_message; ?></div>
<?php endif; ?>

<div class="row">
    <!-- Main Form Column -->
    <div class="col-lg-8">
        <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['TITLE']; ?></h2>
        <p class="font-graphik text-secondary mb-5"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SUBTITLE']; ?></p>

        <form method="POST" enctype="multipart/form-data" id="step_3_form" novalidate>
            <?php wp_nonce_field( 'sic_save_step_3' ); ?>
            <input type="hidden" name="sic_project_action" value="save_step_3">
            
            <!-- Photos -->
            <div class="mb-5">
                <div class="d-flex justify-content-between">
                        <label class="form-label font-graphik fw-bold text-cp-deep-ocean mb-1"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['PHOTOS_LABEL']; ?> <span class="text-danger">*</span></label>
                </div>
                <p class="font-graphik text-secondary small mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['PHOTOS_DESC']; ?></p>
                
                <div class="upload-container" id="container_photos">
                    <div class="custom-upload-zone text-center p-5 rounded-3 border-dashed position-relative bg-white" style="border: 1px dashed #D0D5DD;">
                        <input type="file" name="photos_file" id="photos_file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept=".pdf" onchange="handleStep3FilePreview(this, 'container_photos')">
                        <div class="default-view">
                            <div class="mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                                    <i class="bi bi-upload text-secondary fs-4 pe-none"></i>
                                </div>
                            </div>
                            <h6 class="font-graphik fw-bold text-cp-deep-ocean mb-1 fs-6"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['CLICK_TO_UPLOAD']; ?></h6>
                            <p class="font-graphik text-secondary x-small mb-0"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['FORMAT_PDF']; ?></p>
                                <p class="font-graphik text-secondary x-small mb-0">Max 25MB</p>
                        </div>
                    </div>
                    <div class="preview-view d-none mt-3">
                        <!-- JS injected content -->
                    </div>
                    <div class="error-feedback text-danger small mt-2 d-none"></div>
                </div>
            </div>

            <!-- Impact Report -->
            <div class="mb-5">
                <label class="form-label font-graphik fw-bold text-cp-deep-ocean mb-1"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['IMPACT_REPORT_LABEL']; ?> <span class="text-danger">*</span></label>
                <p class="font-graphik text-secondary small mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['IMPACT_REPORT_DESC']; ?></p>
                
                <div class="upload-container" id="container_impact">
                    <div class="custom-upload-zone text-center p-5 rounded-3 border-dashed position-relative bg-white" style="border: 1px dashed #D0D5DD;">
                        <input type="file" name="impact_report_file" id="impact_report_file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept=".pdf" onchange="handleStep3FilePreview(this, 'container_impact')">
                        <div class="default-view">
                            <div class="mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                                    <i class="bi bi-upload text-secondary fs-4 pe-none"></i>
                                </div>
                            </div>
                            <h6 class="font-graphik fw-bold text-cp-deep-ocean mb-1 fs-6"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['CLICK_TO_UPLOAD']; ?></h6>
                            <p class="font-graphik text-secondary x-small mb-0"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['FORMAT_PDF']; ?></p>
                            <p class="font-graphik text-secondary x-small mb-0">Max 25MB</p>
                        </div>
                    </div>
                    <div class="preview-view d-none mt-3"></div>
                    <div class="error-feedback text-danger small mt-2 d-none"></div>
                </div>
            </div>

            <!-- Testimonials -->
            <div class="mb-5">
                <label class="form-label font-graphik fw-bold text-cp-deep-ocean mb-1"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['TESTIMONIALS_LABEL']; ?> <span class="text-danger">*</span></label>
                <p class="font-graphik text-secondary small mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['TESTIMONIALS_DESC']; ?></p>
                
                <div class="mb-3">
                    <label class="form-label font-graphik text-cp-deep-ocean small"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['LINK_LABEL']; ?></label>
                    <input type="url" name="media_link" class="form-control" placeholder="https://example.com" value="<?php echo esc_url($media_link); ?>">
                </div>

                <label class="form-label font-graphik text-cp-deep-ocean small"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['FILE_LABEL']; ?></label>
                <div class="upload-container" id="container_testimonials">
                    <div class="custom-upload-zone text-center p-5 rounded-3 border-dashed position-relative bg-white" style="border: 1px dashed #D0D5DD;">
                        <input type="file" name="testimonials_file" id="testimonials_file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept=".pdf" onchange="handleStep3FilePreview(this, 'container_testimonials')">
                        <div class="default-view">
                            <div class="mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                                    <i class="bi bi-upload text-secondary fs-4 pe-none"></i>
                                </div>
                            </div>
                            <h6 class="font-graphik fw-bold text-cp-deep-ocean mb-1 fs-6"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['CLICK_TO_UPLOAD']; ?></h6>
                            <p class="font-graphik text-secondary x-small mb-0"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['FORMAT_PDF']; ?></p>
                            <p class="font-graphik text-secondary x-small mb-0">Max 25MB</p>
                        </div>
                    </div>
                    <div class="preview-view d-none mt-3"></div>
                    <div class="error-feedback text-danger small mt-2 d-none"></div>
                </div>
            </div>

            <!-- Sustainable Impact Plan -->
            <div class="mb-4">
                <div class="d-flex align-items-center mb-1">
                    <label class="form-label font-graphik fw-bold text-cp-deep-ocean mb-0 me-2"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SUST_PLAN_LABEL']; ?> <span class="text-danger">*</span></label>
                    <i class="bi bi-info-circle text-secondary" style="font-size: 14px;" data-bs-toggle="tooltip" title="<?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SUST_PLAN_TOOLTIP']; ?>"></i>
                </div>
                <p class="font-graphik text-secondary small mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SUST_PLAN_DESC']; ?></p>
                
                <div class="upload-container" id="container_plan">
                    <div class="custom-upload-zone text-center p-5 rounded-3 border-dashed position-relative bg-white" style="border: 1px dashed #D0D5DD;">
                        <input type="file" name="sustainable_plan_file" id="sustainable_plan_file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept=".pdf" onchange="handleStep3FilePreview(this, 'container_plan')">
                        <div class="default-view">
                            <div class="mb-3">
                                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                                    <i class="bi bi-upload text-secondary fs-4 pe-none"></i>
                                </div>
                            </div>
                            <h6 class="font-graphik fw-bold text-cp-deep-ocean mb-1 fs-6"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['CLICK_TO_UPLOAD']; ?></h6>
                                <p class="font-graphik text-secondary x-small mb-0"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['FORMAT_PDF']; ?></p>
                            <p class="font-graphik text-secondary x-small mb-0">Max 25MB</p>
                        </div>
                    </div>
                    <div class="preview-view d-none mt-3"></div>
                    <div class="error-feedback text-danger small mt-2 d-none"></div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between pt-4 border-top">
                <a href="<?php echo add_query_arg(['step' => 2, 'project_id' => $project_id], SIC_Routes::get_create_project_url()); ?>" class="btn btn-white border px-4 py-2 rounded-3 text-cp-deep-ocean fw-medium"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['BACK_BTN']; ?></a>
                <button type="submit" class="btn btn-custom-aqua px-4 py-2 rounded-3 text-white fw-medium"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['NEXT_BTN']; ?></button>
            </div>

        </form>
    </div>

    <!-- Sidebar Column -->
    <div class="col-lg-4">
        <div class="guidance-panel-detail position-relative rounded-4 overflow-hidden shadow-sm p-4" style="background-color: #f7fafb; height: 65%;">
             <!-- Content -->
             <div class="position-relative z-1 ps-2 pt-2">
                <h3 class="font-mackay fw-bold text-cp-deep-ocean mb-3" style="font-size: 18px; line-height: 27px;"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SIDEBAR_TITLE']; ?></h3>
                <p class="font-graphik fw-bold text-cp-deep-ocean small mb-3" style="font-size: 14px; line-height: 22.75px;"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SIDEBAR_SUBTITLE']; ?></p>
                <div class="font-graphik text-cp-deep-ocean small" style="font-size: 14px; line-height: 22.75px;">
                    <p class="mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SIDEBAR_TEXT_1']; ?></p>
                    <p class="mb-4"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SIDEBAR_TEXT_2']; ?></p>
                    
                    <div class="bg-light p-3 rounded-3 mb-4 d-flex gap-2 align-items-start" style="background-color: #E6F6F6 !important;">
                         <i class="bi bi-info-circle text-cp-aqua-marine mt-1"></i>
                         <p class="mb-0 x-small fw-bold"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SIDEBAR_NOTE']; ?></p>
                    </div>

                    <p><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SIDEBAR_TEXT_3']; ?></p>
                </div>
             </div>
             
             <!-- Background Image -->
             <div class="position-absolute top-0 start-0 w-100 h-100 z-0">
                 <img src="<?php echo get_template_directory_uri(); ?>/assets/img/step-3-sidebar-bg.png" alt="" style="position: absolute; width: 100%; height: 100%; max-width: none; object-fit: cover; object-position: 77% 37%;">
             </div>
        </div>
    </div>
</div>

<?php
// Fetch all files for this project
$project_files = [];
if ($project_id) {
    $files = $db->get_project_files($project_id);
    foreach ($files as $f) {
        $project_files[$f->file_role] = [
            'url' => $f->file_url,
            'name' => $f->file_name
        ];
    }
}
?>

<script>
const existingFiles = <?php echo json_encode($project_files); ?>;

document.addEventListener('DOMContentLoaded', function() {
    // Pre-fill files
    if (existingFiles.photos) {
        const ext = existingFiles.photos.name.split('.').pop().toLowerCase();
        const isImg = ['jpg', 'jpeg', 'png', 'gif'].includes(ext);
        showExistingFile('container_photos', existingFiles.photos, isImg);
    }
    if (existingFiles.impact_report) {
         showExistingFile('container_impact', existingFiles.impact_report, false);
    }
    if (existingFiles.testimonials_file) {
         showExistingFile('container_testimonials', existingFiles.testimonials_file, false);
    }
    if (existingFiles.sustainable_impact_plan) {
         showExistingFile('container_plan', existingFiles.sustainable_impact_plan, false);
    }
});

function showExistingFile(containerId, fileData, isImage) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const zone = container.querySelector('.custom-upload-zone');
    const previewView = container.querySelector('.preview-view');
    
    zone.classList.add('d-none');
    previewView.classList.remove('d-none');

    let iconHtml = '<i class="bi bi-file-earmark-text fs-3 text-secondary"></i>';
    if (isImage) {
        iconHtml = `<img src="${fileData.url}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">`;
    } else if (fileData.name.toLowerCase().endsWith('.pdf')) {
         iconHtml = '<i class="bi bi-file-earmark-pdf fs-3 text-danger"></i>';
    }

    previewView.innerHTML = `
        <div class="d-flex align-items-center justify-content-between p-3 bg-white border rounded-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    ${iconHtml}
                </div>
                <div>
                    <p class="mb-0 fw-bold text-cp-deep-ocean small text-truncate" style="max-width: 200px;">
                        <a href="${fileData.url}" target="_blank" class="text-decoration-none text-cp-deep-ocean">${fileData.name}</a>
                    </p>
                    <p class="mb-0 text-secondary x-small">Existing File</p>
                </div>
            </div>
            <button type="button" class="btn btn-link text-danger p-0" onclick="clearFile('${auth_input_id(containerId)}')">
                <i class="bi bi-x-circle fs-5"></i>
            </button>
        </div>
    `;
}
</script>

<script>
function handleStep3FilePreview(input, containerId) {
    const container = document.getElementById(containerId);
    const defaultView = container.querySelector('.default-view');
    const previewView = container.querySelector('.preview-view');
    const file = input.files[0];

    if (file) {
        // Validate Size (25MB)
        // Validate Size (25MB)
        if (file.size > 25 * 1024 * 1024) {
             const msg = '<?php echo esc_js($language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['FILE_SIZE_25MB_ERROR']); ?>';
             const errorDiv = container.querySelector('.error-feedback');
             if (errorDiv) {
                 errorDiv.textContent = msg;
                 errorDiv.classList.remove('d-none');
             }
             input.value = ''; // Clear input
             return;
        } else {
             const errorDiv = container.querySelector('.error-feedback');
             if (errorDiv) {
                 errorDiv.textContent = '';
                 errorDiv.classList.add('d-none');
             }
        }

        // Hide default view, show preview
        // But we want to keep the input accessible? 
        // Actually, usually we replace the whole dropzone look or put the preview BELOW it.
        // My code structure: input is absolute over the .custom-upload-zone.
        // If I hide .default-view, the background/border remains.
        
        // Let's replace the visual content of the dropzone OR show a "Selected Card" and hide the big dropzone?
        // Figma shows: When selected, it shows a card with file info.
        
        // Implementation: 
        // Hide the .custom-upload-zone (which contains the input!). 
        // Wait, if I hide the input, user can't change it easily unless I provide a "Remove" button that resets it.
        
        // Better: Hide .custom-upload-zone visually, Show .preview-view.
        // .preview-view will have the "Remove" button which clears input and toggles visibility back.
        
        const zone = container.querySelector('.custom-upload-zone');
        zone.classList.add('d-none');
        previewView.classList.remove('d-none');
        
        const isImage = file.type.startsWith('image/');
        let iconHtml = '<i class="bi bi-file-earmark-text fs-3 text-secondary"></i>';
        if (isImage) {
            iconHtml = `<img src="${URL.createObjectURL(file)}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">`;
        } else if (file.type === 'application/pdf') {
             iconHtml = '<i class="bi bi-file-earmark-pdf fs-3 text-danger"></i>';
        }

        previewView.innerHTML = `
            <div class="d-flex align-items-center justify-content-between p-3 bg-white border rounded-3 shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        ${iconHtml}
                    </div>
                    <div>
                        <p class="mb-0 fw-bold text-cp-deep-ocean small text-truncate" style="max-width: 200px;">${file.name}</p>
                        <p class="mb-0 text-secondary x-small">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                    </div>
                </div>
                <button type="button" class="btn btn-link text-danger p-0" onclick="clearFile('${auth_input_id(containerId)}')">
                    <i class="bi bi-x-circle fs-5"></i>
                </button>
            </div>
        `;
    }
}

function auth_input_id(containerId) {
    // Helper to find input id from container id (reverse engineer or just pass input id)
    // Actually handleStep3FilePreview should pass both.
    // I'll fix the onclick above to pass logic.
    return containerId; // Logic handled in clearFile
}

function clearFile(containerId) {
    const container = document.getElementById(containerId);
    const input = container.querySelector('input[type="file"]');
    const zone = container.querySelector('.custom-upload-zone');
    const previewView = container.querySelector('.preview-view');
    const errorDiv = container.querySelector('.error-feedback');
    
    input.value = ''; // Clear file
    zone.classList.remove('d-none');
    previewView.classList.add('d-none');
    previewView.innerHTML = '';
    
    // Clear errors if any
    if (errorDiv) {
        errorDiv.textContent = '';
        errorDiv.classList.add('d-none');
    }
    
    // Also remove the form validation error if present (special case for our custom validation)
    const invalidFeedback = container.parentNode.querySelector('.invalid-feedback');
    if (invalidFeedback) {
        invalidFeedback.remove();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Form Validation logic
    const form = document.getElementById('step_3_form');
    const isRTL = document.body.classList.contains('rtl');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredMsg = isRTL ? 'هذا الحقل مطلوب' : 'This field is required';
            const testimMsg = isRTL ? 'يرجى تقديم ملف أو رابط' : 'Please provide a file or a link';

            function checkFile(containerId) {
                const container = document.getElementById(containerId);
                const input = container.querySelector('input[type="file"]');
                // It's valid if input has value OR if preview-view is NOT hidden (meaning existing file showed)
                // Note: when clearing file, we hide preview-view.
                const hasExisting = !container.querySelector('.preview-view').classList.contains('d-none');
                
                if (!input.value && !hasExisting) {
                    showError(container, requiredMsg);
                    return false;
                } else {
                    removeError(container);
                    return true;
                }
            }

            // 1. Photos
            if (!checkFile('container_photos')) isValid = false;
            
            // 2. Impact Report
            if (!checkFile('container_impact')) isValid = false;

            // 3. Sustainable Plan
            if (!checkFile('container_plan')) isValid = false;

            // 4. Testimonials (File OR Link)
            const testimContainer = document.getElementById('container_testimonials');
            const testimInput = testimContainer.querySelector('input[type="file"]');
            const testimLink = form.querySelector('input[name="media_link"]');
            const hasTestimFile = testimInput.value || !testimContainer.querySelector('.preview-view').classList.contains('d-none');
            const hasTestimLink = testimLink.value.trim().length > 0;

            if (!hasTestimFile && !hasTestimLink) {
                isValid = false;
                // Show error under one of them or the main container. Let's show under file upload for consistency
                showError(testimContainer, testimMsg);
            } else {
                removeError(testimContainer);
            }
            
            if (!isValid) {
                e.preventDefault();
                const firstError = document.querySelector('.invalid-feedback');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        // Add listeners to clear errors on change
        // We already have onchange="handleStep3FilePreview..." which helps, 
        // but we might need to hook into it or event listeners.
        // Let's add explicit listeners.
        
        ['container_photos', 'container_impact', 'container_plan', 'container_testimonials'].forEach(id => {
            const container = document.getElementById(id);
            const input = container.querySelector('input[type="file"]');
            input.addEventListener('change', function() {
                if (this.value) removeError(container);
            });
        });
        
        const linkInput = form.querySelector('input[name="media_link"]');
        if (linkInput) {
            linkInput.addEventListener('input', function() {
                 if (this.value.trim()) removeError(document.getElementById('container_testimonials'));
            });
        }
    }

    function showError(container, msg) {
        // Validation "container" is the div.upload-container mostly
        // Error should go AFTER the .upload-container
        // Wait, the structure is <div class="mb-5">... <div class="upload-container">...</div> </div>
        // Let's attach to the upload-container parent or just append to upload-container?
        // Appending to upload-container is fine.
        
        let existing = container.parentNode.querySelector('.invalid-feedback');
        // Actually for testimonials, if we attach to container_testimonials, it's inside the .mb-5
        
        if (!existing) {
             const div = document.createElement('div');
             div.className = 'invalid-feedback d-block mt-2';
             div.innerText = msg;
             container.insertAdjacentElement('afterend', div);
        } else {
             existing.innerText = msg;
             existing.style.display = 'block';
        }
    }

    function removeError(container) {
        const existing = container.parentNode.querySelector('.invalid-feedback');
        if (existing) {
            existing.remove();
        }
    }
});
</script>
