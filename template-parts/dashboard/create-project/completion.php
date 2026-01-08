<?php
/**
 * Step 6: Completion (Success)
 */
?>

<div class="row justify-content-center">
    <div class="col-lg-8 text-center py-5">
        <div class="mb-4">
             <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                 <i class="bi bi-check-lg text-cp-aqua-marine fs-1"></i>
             </div>
        </div>
        <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-3">Thank You for Your Submission!</h2>
        <p class="font-graphik text-secondary mb-4 fs-5">
            Your project has been successfully submitted and is now pending review.
        </p>
        <p class="font-graphik text-secondary mb-5">
            We'll send you email notifications to confirm your submission and update you on the status.
        </p>
        
        <a href="<?php echo home_url('/dashboard-projects'); ?>" class="btn btn-custom-aqua px-5 py-3 rounded-pill fw-bold text-white">Back to My Projects</a>
    </div>
</div>
