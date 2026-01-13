<?php
/**
 * Step 6: Review & Submit (Accept Disclaimer)
 */
?>

<div class="row justify-content-center">
    <!-- Centered Content Column -->
    <div class="col-lg-10">
        
        <?php
        $project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;
        
        global $language;
        
        // Handle Submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sic_project_action']) && $_POST['sic_project_action'] === 'submit_project') {
            if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'sic_submit_project' ) ) {
                echo '<div class="alert alert-danger">Security check failed.</div>';
                return;
            }
            
            // Check if disclaimer is checked (Server side check)
            if ( !isset($_POST['disclaimer_accepted']) ) {
                $error_message = "Please accept the declaration to proceed.";
            } else {
                $db = SIC_DB::get_instance();
                
                // Validate Completion of Steps 1-5
                $validation = $db->validate_project_completion($project_id);
                if ( is_wp_error($validation) ) {
                    $error_message = $validation->get_error_message();
                } else {
                    $update_result = $db->update_project($project_id, [
                        'submission_status' => 'submitted',
                        'profile_completed' => 1 // Mark profile/application as complete
                    ]);
                    
                    if ( $update_result ) {
                        // Redirect to self with success flag to show modal
                        $current_url = remove_query_arg(['_wpnonce', 'sic_project_action'], $_SERVER['REQUEST_URI']);
                        $success_url = add_query_arg(['submission_success' => '1'], $current_url);
                        wp_redirect($success_url);
                        exit;
                    } else {
                         $error_message = "An error occurred while submitting your project. Please try again.";
                    }
                }
            }
        }
        
        if ( isset($error_message) ) {
            echo '<div class="alert alert-danger rounded-3 mb-4">' . wp_kses_post($error_message) . '</div>';
        }
        ?>

        <form method="POST">
            <?php wp_nonce_field( 'sic_submit_project' ); ?>
            <input type="hidden" name="sic_project_action" value="submit_project">
            
            <div class="bg-white rounded-4 p-5 shadow-sm mb-5 text-center">
                
                <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-5" style="font-size: 32px;"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_6']['TITLE']; ?></h2>

                 <!-- Disclaimer Checkbox -->
                 <div class="d-flex align-items-start justify-content-center mb-4 text-start" style="max-width: 750px; margin: 0 auto;">
                     <div class="me-3 mt-1">
                         <input class="form-check-input border-2" type="checkbox" name="disclaimer_accepted" id="disclaimerParams" style="width: 24px; height: 24px; cursor: pointer;" required>
                     </div>
                     <div>
                         <label class="form-check-label font-mackay fw-bold text-cp-deep-ocean fs-5 mb-2" for="disclaimerParams" style="line-height:1.4; cursor: pointer;">
                             <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_6']['DISCLAIMER_TEXT']; ?>
                         </label>
                         <p class="text-secondary small mb-0 mt-1"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_6']['MANDATORY_NOTE']; ?></p>
                     </div>
                 </div>

                 <!-- Terms & Conditions Box -->
                 <div class="p-4 rounded-3 text-start mb-5" style="max-width: 750px; margin: 0 auto; background-color: #F9FAFB; border: 1px solid #E5E7EB;">
                     <p class="font-graphik text-secondary small mb-0">
                         <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_6']['TERMS_TEXT_START']; ?> 
                         <a href="#" class="text-cp-aqua-marine text-decoration-none fw-bold"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_6']['TERMS_LINK']; ?> <i class="bi bi-info-circle ms-1" style="font-size: 10px;"></i></a> 
                         <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_6']['AND']; ?> 
                         <a href="#" class="text-cp-aqua-marine text-decoration-none fw-bold"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_6']['PRIVACY_LINK']; ?> <i class="bi bi-info-circle ms-1" style="font-size: 10px;"></i></a>.
                     </p>
                 </div>

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-center gap-3">
                    <a href="<?php echo add_query_arg(['step' => 5, 'project_id' => $project_id], SIC_Routes::get_create_project_url()); ?>" class="btn btn-white border px-5 py-2 rounded-3 text-cp-deep-ocean fw-medium" style="min-width: 140px;"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_6']['BACK_BTN']; ?></a>
                    <!-- Changed type to submit to trigger PHP processing -->
                    <button type="submit" class="btn btn-custom-aqua px-5 py-2 rounded-3 text-white fw-medium" style="min-width: 140px;"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_6']['DONE_BTN']; ?></button>
                </div>

            </div>

        </form>
    </div>
</div>

<!-- Completion Modal -->
<div class="modal fade" id="completionModal" tabindex="-1" aria-labelledby="completionModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 p-0 text-center position-relative overflow-visible shadow-lg" style="width: 520px; border-radius: 16px;">
      
      <!-- Overlapping Checkmark Icon -->
      <div class="position-absolute top-0 start-50 translate-middle d-flex align-items-center justify-content-center" style="margin-top: -30px;">
          <!-- Outer Ring -->
          <div class="rounded-circle position-absolute" style="width: 108px; height: 108px; border: 4px solid rgba(59, 196, 189, 0.2); opacity: 0.34;"></div>
          <!-- Inner Circle -->
          <div class="rounded-circle d-flex align-items-center justify-content-center text-white position-relative" style="width: 66px; height: 66px; background-color: #3BC4BD; box-shadow: 0px 10px 15px -3px rgba(59, 196, 189, 0.3), 0px 4px 6px -4px rgba(59, 196, 189, 0.3);">
              <i class="bi bi-check-lg fs-2"></i>
          </div>
      </div>

      <div class="modal-body pt-5 px-5 pb-5 mt-4">
        <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-3 mt-3" style="font-size: 28px; line-height: 32px; color: #101828;"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_6']['MODAL_TITLE']; ?></h2>
        
        <div class="font-graphik text-secondary mb-4" style="font-size: 16px; line-height: 24px; color: #475467;">
            <p class="mb-2"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_6']['MODAL_TEXT_1']; ?></p>
            <p class="mb-0"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_6']['MODAL_TEXT_2']; ?></p>
        </div>
        
        <a href="<?php echo SIC_Routes::get_dashboard_home_url(); ?>" class="btn btn-white border w-100 rounded-3 fw-medium thankyou-close-btn" style="border-color: #3BC4BD !important; color: #0a0a0a; padding-top: 10px; padding-bottom: 10px; font-size: 14px;"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_6']['CLOSE_BTN']; ?></a>
      </div>
    </div>
  </div>
</div>

<!-- Scripts required for Modal functionality -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Trigger Modal if Success -->
<?php if ( isset($_GET['submission_success']) && $_GET['submission_success'] == '1' ): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('completionModal'), {
            keyboard: false
        });
        myModal.show();
    });
</script>
<?php endif; ?>
