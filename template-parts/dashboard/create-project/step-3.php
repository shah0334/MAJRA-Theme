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
    wp_die('Project not found');
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
        wp_die( 'Security check failed' );
    }

    $errors = [];
    $cycle_id = $project->cycle_id;
    $applicant_id = $project->created_by_applicant_id;

    // Helper to upload and link
    $handle_upload = function($file_input_name, $role) use ($db, $storage, $cycle_id, $applicant_id, $project_id, &$errors) {
        if ( !empty($_FILES[$file_input_name]['name']) ) {
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
        }
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

        <form method="POST" enctype="multipart/form-data">
            <?php wp_nonce_field( 'sic_save_step_3' ); ?>
            <input type="hidden" name="sic_project_action" value="save_step_3">
            
            <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                
                <!-- Photos -->
                <div class="mb-5">
                    <div class="d-flex justify-content-between">
                         <label class="form-label font-graphik fw-bold text-cp-deep-ocean mb-1"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['PHOTOS_LABEL']; ?></label>
                         <?php // TODO: Show existing file indicator if previously uploaded ?>
                    </div>
                    <p class="font-graphik text-secondary small mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['PHOTOS_DESC']; ?></p>
                    
                    <div class="custom-upload-zone text-center p-5 rounded-3 border-dashed position-relative" style="border: 1px dashed #D0D5DD; background-color: #FFFFFF;">
                        <input type="file" name="photos_file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                                <i class="bi bi-upload text-secondary fs-4"></i>
                            </div>
                        </div>
                        <h6 class="font-graphik fw-bold text-cp-deep-ocean mb-1 fs-6"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['CLICK_TO_UPLOAD']; ?></h6>
                        <p class="font-graphik text-secondary x-small mb-0"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['FORMAT_PDF']; ?></p>
                         <p class="font-graphik text-secondary x-small mb-0"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['MAX_SIZE']; ?></p>
                    </div>
                </div>

                <!-- Impact Report -->
                <div class="mb-5">
                    <label class="form-label font-graphik fw-bold text-cp-deep-ocean mb-1"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['IMPACT_REPORT_LABEL']; ?></label>
                    <p class="font-graphik text-secondary small mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['IMPACT_REPORT_DESC']; ?></p>
                    
                    <div class="custom-upload-zone text-center p-5 rounded-3 border-dashed position-relative" style="border: 1px dashed #D0D5DD; background-color: #FFFFFF;">
                        <input type="file" name="impact_report_file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                                <i class="bi bi-upload text-secondary fs-4"></i>
                            </div>
                        </div>
                        <h6 class="font-graphik fw-bold text-cp-deep-ocean mb-1 fs-6"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['CLICK_TO_UPLOAD']; ?></h6>
                        <p class="font-graphik text-secondary x-small mb-0"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['FORMAT_PDF']; ?></p>
                        <p class="font-graphik text-secondary x-small mb-0"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['MAX_SIZE']; ?></p>
                    </div>
                </div>

                <!-- Testimonials -->
                <div class="mb-5">
                    <label class="form-label font-graphik fw-bold text-cp-deep-ocean mb-1"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['TESTIMONIALS_LABEL']; ?></label>
                    <p class="font-graphik text-secondary small mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['TESTIMONIALS_DESC']; ?></p>
                    
                    <div class="mb-3">
                        <label class="form-label font-graphik text-cp-deep-ocean small"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['LINK_LABEL']; ?></label>
                        <input type="url" name="media_link" class="form-control bg-light border-0 fs-6" placeholder="https://example.com" value="<?php echo esc_url($media_link); ?>">
                    </div>

                    <label class="form-label font-graphik text-cp-deep-ocean small"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['FILE_LABEL']; ?></label>
                     <div class="custom-upload-zone text-center p-5 rounded-3 border-dashed position-relative" style="border: 1px dashed #D0D5DD; background-color: #FFFFFF;">
                        <input type="file" name="testimonials_file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                                <i class="bi bi-upload text-secondary fs-4"></i>
                            </div>
                        </div>
                        <h6 class="font-graphik fw-bold text-cp-deep-ocean mb-1 fs-6"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['CLICK_TO_UPLOAD']; ?></h6>
                        <p class="font-graphik text-secondary x-small mb-0"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['FORMAT_PDF']; ?></p>
                        <p class="font-graphik text-secondary x-small mb-0"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['MAX_SIZE']; ?></p>
                    </div>
                </div>

                <!-- Sustainable Impact Plan -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-1">
                        <label class="form-label font-graphik fw-bold text-cp-deep-ocean mb-0 me-2"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SUST_PLAN_LABEL']; ?></label>
                        <i class="bi bi-info-circle text-secondary" style="font-size: 14px;" data-bs-toggle="tooltip" title="<?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SUST_PLAN_TOOLTIP']; ?>"></i>
                    </div>
                    <p class="font-graphik text-secondary small mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SUST_PLAN_DESC']; ?></p>
                    
                    <div class="custom-upload-zone text-center p-5 rounded-3 border-dashed position-relative" style="border: 1px dashed #D0D5DD; background-color: #FFFFFF;">
                        <input type="file" name="sustainable_plan_file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                                <i class="bi bi-upload text-secondary fs-4"></i>
                            </div>
                        </div>
                        <h6 class="font-graphik fw-bold text-cp-deep-ocean mb-1 fs-6"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['CLICK_TO_UPLOAD']; ?></h6>
                         <p class="font-graphik text-secondary x-small mb-0"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['FORMAT_PDF']; ?></p>
                        <p class="font-graphik text-secondary x-small mb-0"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['MAX_SIZE']; ?></p>
                    </div>
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
        <div class="guidance-panel-detail position-relative rounded-4 overflow-hidden shadow-sm p-4 h-100" style="background-color: #f7fafb;">
             <!-- Content -->
             <div class="position-relative z-1">
                <h3 class="font-mackay fw-bold text-cp-deep-ocean mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SIDEBAR_TITLE']; ?></h3>
                <p class="font-graphik fw-bold text-cp-deep-ocean small mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SIDEBAR_SUBTITLE']; ?></p>
                <div class="font-graphik text-cp-deep-ocean small" style="line-height: 1.6;">
                    <p class="mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SIDEBAR_TEXT_1']; ?></p>
                    <p class="mb-4"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SIDEBAR_TEXT_2']; ?></p>
                    
                    <div class="bg-light p-3 rounded-3 mb-4 d-flex gap-2 align-items-start" style="background-color: #E6F6F6 !important;">
                         <i class="bi bi-info-circle text-cp-aqua-marine mt-1"></i>
                         <p class="mb-0 x-small fw-bold"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SIDEBAR_NOTE']; ?></p>
                    </div>

                    <p><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_3']['SIDEBAR_TEXT_3']; ?></p>
                </div>
             </div>
             
             <!-- Background Image Overlay (Bottom) -->
             <div class="position-absolute bottom-0 start-0 w-100" style="height: 30%;">
                 <!-- Placeholder for the cityscape image shown in design -->
                 <div style="width: 100%; height: 100%; background: linear-gradient(to top, #00041C, transparent); opacity: 0.8;"></div>
             </div>
        </div>
    </div>
</div>
