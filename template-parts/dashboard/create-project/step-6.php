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
        
        // Handle Submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sic_project_action']) && $_POST['sic_project_action'] === 'submit_project') {
            if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'sic_submit_project' ) ) {
                wp_die( 'Security check failed' );
            }
            
            // Check if disclaimer is checked (Server side check)
            if ( !isset($_POST['disclaimer_accepted']) ) {
                $error_message = "Please accept the declaration to proceed.";
            } else {
                $db = SIC_DB::get_instance();
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
        
        if ( isset($error_message) ) {
            echo '<div class="alert alert-danger rounded-3 mb-4">' . esc_html($error_message) . '</div>';
        }
        ?>

        <form method="POST">
            <?php wp_nonce_field( 'sic_submit_project' ); ?>
            <input type="hidden" name="sic_project_action" value="submit_project">
            
            <div class="bg-white rounded-4 p-5 shadow-sm mb-5 text-center">
                
                <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-5">Accept Disclaimer</h2>

                 <!-- Disclaimer Checkbox -->
                 <div class="d-flex align-items-start justify-content-center mb-4 text-start" style="max-width: 800px; margin: 0 auto;">
                     <div class="me-3 mt-1">
                         <input class="form-check-input border-2" type="checkbox" name="disclaimer_accepted" id="disclaimerParams" style="width: 20px; height: 20px;" required>
                     </div>
                     <div>
                         <label class="form-check-label font-graphik fw-bold text-cp-deep-ocean fs-6 mb-2" for="disclaimerParams" style="line-height:1.5;">
                             I solemnly declare that all information submitted is true and accurate and fully complies with the laws and regulations of the United Arab Emirates. I acknowledge that providing false information may lead to immediate disqualification.
                         </label>
                         <p class="text-secondary small mb-0">* This declaration is mandatory</p>
                     </div>
                 </div>

                 <!-- Terms & Conditions Box -->
                 <div class="bg-light p-4 rounded-3 text-start mb-5" style="max-width: 800px; margin: 0 auto; background-color: #F9FAFB;">
                     <p class="font-graphik text-secondary small mb-0">
                         By submitting this form you acknowledge that you have read, understood, and agree to abide by our 
                         <a href="#" class="text-cp-aqua-marine text-decoration-none fw-bold">Terms & Conditions <i class="bi bi-info-circle ms-1" style="font-size: 10px;"></i></a> 
                         and 
                         <a href="#" class="text-cp-aqua-marine text-decoration-none fw-bold">Privacy Policy <i class="bi bi-info-circle ms-1" style="font-size: 10px;"></i></a>.
                     </p>
                 </div>

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-center gap-3">
                    <a href="<?php echo add_query_arg(['step' => 5, 'project_id' => $project_id], SIC_Routes::get_create_project_url()); ?>" class="btn btn-white border px-5 py-2 rounded-3 text-cp-deep-ocean fw-medium" style="min-width: 140px;">Back</a>
                    <!-- Changed type to submit to trigger PHP processing -->
                    <button type="submit" class="btn btn-custom-aqua px-5 py-2 rounded-3 text-white fw-medium" style="min-width: 140px;">Done</button>
                </div>

            </div>

        </form>
    </div>
</div>

<!-- Completion Modal -->
<div class="modal fade" id="completionModal" tabindex="-1" aria-labelledby="completionModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 p-4 text-center position-relative overflow-visible" style="margin-top: 50px;">
      
      <!-- Overlapping Checkmark Icon -->
      <div class="position-absolute top-0 start-50 translate-middle rounded-circle bg-white d-flex align-items-center justify-content-center" style="width: 108px; height: 108px; margin-top: -20px; box-shadow: 0px 4px 20px rgba(0,0,0,0.05);">
          <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 66px; height: 66px; background-color: #3BC4BD; box-shadow: 0px 10px 15px -3px rgba(59, 196, 189, 0.3);">
              <i class="bi bi-check-lg fs-2"></i>
          </div>
      </div>

      <div class="modal-body pt-5 mt-4">
        <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-3" style="font-size: 28px;">Thank You for Your Submission!</h2>
        <p class="font-graphik text-secondary mb-3">Your project has been successfully submitted and is now under review.</p>
        <p class="font-graphik text-secondary mb-4">We'll keep you informed via email with confirmation and updates on progress.</p>
        
        <a href="<?php echo SIC_Routes::get_dashboard_home_url(); ?>" class="btn btn-white border border-custom-aqua text-cp-deep-ocean px-4 py-2 rounded-3 fw-medium" style="border-color: #3BC4BD !important;">Close</a>
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
