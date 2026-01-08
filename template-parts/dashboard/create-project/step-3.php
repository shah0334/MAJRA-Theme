<?php
/**
 * Step 3: Supporting Evidence
 */
$db = SIC_DB::get_instance();
$storage = SIC_Storage::get_instance();
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
        <strong>This section is optional for now, but will be required if your project is shortlisted.</strong><br>
        In addition to the challenge, Majra will review eligible projects for the Qualification Certificate or Verification Stamp. By submitting this evidence now, you help expedite the review of your project for these elite recognitions.
    </p>
</div>

<?php if ( isset($error_message) ): ?>
    <div class="alert alert-danger mb-4"><?php echo $error_message; ?></div>
<?php endif; ?>

<div class="row">
    <!-- Main Form Column -->
    <div class="col-lg-8">
        <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-3">Supporting Evidence</h2>
        <p class="font-graphik text-secondary mb-5">Help us understand the scope, impact, and alignment of your project</p>

        <form method="POST" enctype="multipart/form-data">
            <?php wp_nonce_field( 'sic_save_step_3' ); ?>
            <input type="hidden" name="sic_project_action" value="save_step_3">
            
            <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                
                <!-- Photos -->
                <div class="mb-5">
                    <div class="d-flex justify-content-between">
                         <label class="form-label font-graphik fw-bold text-cp-deep-ocean mb-1">Photos</label>
                         <?php // TODO: Show existing file indicator if previously uploaded ?>
                    </div>
                    <p class="font-graphik text-secondary small mb-3">Upload a consolidated file of your photos showing project implementation.</p>
                    
                    <div class="custom-upload-zone text-center p-5 rounded-3 border-dashed position-relative" style="border: 1px dashed #D0D5DD; background-color: #FFFFFF;">
                        <input type="file" name="photos_file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                                <i class="bi bi-upload text-secondary fs-4"></i>
                            </div>
                        </div>
                        <h6 class="font-graphik fw-bold text-cp-deep-ocean mb-1 fs-6">Click to upload file</h6>
                        <p class="font-graphik text-secondary x-small mb-0">Accepted format: PDF only</p>
                         <p class="font-graphik text-secondary x-small mb-0">Max size: 25 MB</p>
                    </div>
                </div>

                <!-- Impact Report -->
                <div class="mb-5">
                    <label class="form-label font-graphik fw-bold text-cp-deep-ocean mb-1">Impact Report</label>
                    <p class="font-graphik text-secondary small mb-3">Upload a consolidated document detailing measurable project results, beneficiary outcomes, and supporting data (e.g. surveys, focus groups).</p>
                    
                    <div class="custom-upload-zone text-center p-5 rounded-3 border-dashed position-relative" style="border: 1px dashed #D0D5DD; background-color: #FFFFFF;">
                        <input type="file" name="impact_report_file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                                <i class="bi bi-upload text-secondary fs-4"></i>
                            </div>
                        </div>
                        <h6 class="font-graphik fw-bold text-cp-deep-ocean mb-1 fs-6">Click to upload file</h6>
                        <p class="font-graphik text-secondary x-small mb-0">Accepted format: PDF only</p>
                        <p class="font-graphik text-secondary x-small mb-0">Max size: 25 MB</p>
                    </div>
                </div>

                <!-- Testimonials -->
                <div class="mb-5">
                    <label class="form-label font-graphik fw-bold text-cp-deep-ocean mb-1">Testimonials and/or Media Coverage</label>
                    <p class="font-graphik text-secondary small mb-3">Upload a consolidated file showcasing testimonials or media coverage supporting your project.</p>
                    
                    <div class="mb-3">
                        <label class="form-label font-graphik text-cp-deep-ocean small">Link</label>
                        <input type="url" name="media_link" class="form-control bg-light border-0 fs-6" placeholder="https://example.com" value="<?php echo esc_url($media_link); ?>">
                    </div>

                    <label class="form-label font-graphik text-cp-deep-ocean small">File</label>
                     <div class="custom-upload-zone text-center p-5 rounded-3 border-dashed position-relative" style="border: 1px dashed #D0D5DD; background-color: #FFFFFF;">
                        <input type="file" name="testimonials_file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                                <i class="bi bi-upload text-secondary fs-4"></i>
                            </div>
                        </div>
                        <h6 class="font-graphik fw-bold text-cp-deep-ocean mb-1 fs-6">Click to upload file</h6>
                        <p class="font-graphik text-secondary x-small mb-0">Accepted format: PDF only</p>
                        <p class="font-graphik text-secondary x-small mb-0">Max size: 25 MB</p>
                    </div>
                </div>

                <!-- Sustainable Impact Plan -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-1">
                        <label class="form-label font-graphik fw-bold text-cp-deep-ocean mb-0 me-2">Sustainable Impact Plan</label>
                        <i class="bi bi-info-circle text-secondary" style="font-size: 14px;" data-bs-toggle="tooltip" title="Info about Sustainable Impact Plan"></i>
                    </div>
                    <p class="font-graphik text-secondary small mb-3">Upload a consolidated brief plan outlining how you intend to scale your project's impact and ensure long-term sustainability.</p>
                    
                    <div class="custom-upload-zone text-center p-5 rounded-3 border-dashed position-relative" style="border: 1px dashed #D0D5DD; background-color: #FFFFFF;">
                        <input type="file" name="sustainable_plan_file" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 48px; height: 48px;">
                                <i class="bi bi-upload text-secondary fs-4"></i>
                            </div>
                        </div>
                        <h6 class="font-graphik fw-bold text-cp-deep-ocean mb-1 fs-6">Click to upload file</h6>
                         <p class="font-graphik text-secondary x-small mb-0">Accepted format: PDF only</p>
                        <p class="font-graphik text-secondary x-small mb-0">Max size: 25 MB</p>
                    </div>
                </div>

            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between pt-4 border-top">
                <a href="<?php echo add_query_arg(['step' => 2, 'project_id' => $project_id], SIC_Routes::get_create_project_url()); ?>" class="btn btn-white border px-4 py-2 rounded-3 text-cp-deep-ocean fw-medium">Back</a>
                <button type="submit" class="btn btn-custom-aqua px-4 py-2 rounded-3 text-white fw-medium">Next</button>
            </div>

        </form>
    </div>

    <!-- Sidebar Column -->
    <div class="col-lg-4">
        <div class="guidance-panel-detail position-relative rounded-4 overflow-hidden shadow-sm p-4 h-100" style="background-color: #f7fafb;">
             <!-- Content -->
             <div class="position-relative z-1">
                <h3 class="font-mackay fw-bold text-cp-deep-ocean mb-3">Here’s your opportunity to showcase what you’ve accomplished.</h3>
                <p class="font-graphik fw-bold text-cp-deep-ocean small mb-3">Upload evidence of your impact.</p>
                <div class="font-graphik text-cp-deep-ocean small" style="line-height: 1.6;">
                    <p class="mb-3"><strong>The Impact Report</strong> should detail your project's results and highlight measurable impact. Where possible, include quantifiable outcomes for beneficiaries along with supporting data (such as surveys, focus groups, etc.).</p>
                    <p class="mb-4"><strong>The Sustainable Impact Plan</strong> is a brief plan outlining how you intend to scale your project's impact and ensure long-term sustainability. This may include key priorities, expected outcomes, and any resources or funding considerations that support this journey.</p>
                    
                    <div class="bg-light p-3 rounded-3 mb-4 d-flex gap-2 align-items-start" style="background-color: #E6F6F6 !important;">
                         <i class="bi bi-info-circle text-cp-aqua-marine mt-1"></i>
                         <p class="mb-0 x-small fw-bold">We strongly recommend you submit any available supporting evidence at this stage.</p>
                    </div>

                    <p>In addition to the Sustainable Impact Challenge, Majra will review eligible projects for the Qualification Certificate or Verification Stamp. These distinguished recognitions enhance the credibility of your CSR and Sustainability projects and open the door to new opportunities and potential support.</p>
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
