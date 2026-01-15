<?php
/**
 * Template Name: Dashboard - Applicant Profile
 */

get_header('dashboard');
global $language;


$db = SIC_DB::get_instance();

// Get user ID from session or fallback to WordPress user
$user_id = 0;
if (isset($_SESSION['sic_user_id']) && $_SESSION['sic_user_id']) {
    $user_id = intval($_SESSION['sic_user_id']);
} elseif (is_user_logged_in()) {
    $user_id = get_current_user_id();
}

// Fetch applicant data
$applicant = null;
if ($user_id) {
    $applicant = $db->get_applicant_by_id($user_id);
}

// If no applicant found, redirect to login or show error
if (!$applicant) {
    wp_redirect(SIC_Routes::get_login_url());
    exit;
}

$message = '';
$error_message = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sic_profile_action'])) {
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'sic_save_profile')) {
        $error_message = 'Security check failed.';
    } else {
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $email = sanitize_email($_POST['email']);
        $designation = sanitize_text_field($_POST['designation']);
        
        // Basic Validation
        if (empty($first_name) || empty($last_name) || empty($email) || empty($designation)) {
            $error_message = 'Please fill in all required fields.';
        } else {
            $data = [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'designation' => $designation
            ];

            // Use the SIC_DB method to update the external database
            $updated = $db->update_applicant($applicant->applicant_id, $data);

            if ($updated) {
                $message = 'Profile updated successfully.';
                // Refresh data
                $applicant = $db->get_applicant_by_id($applicant->applicant_id);
            } else {
                $error_message = 'Failed to update profile. Please try again.';
            }
        }
    }
}
?>

<main id="primary" class="site-main bg-cp-cream-light py-5">
    <div class="container">
        <!-- Profile Update Notice -->
        <div class="rounded-4 border p-3 mb-4 position-relative" style="background: linear-gradient(180deg, #f0f9f8 0%, #fff 100%); border-color: #FC9C63 !important;">
            <p class="font-graphik text-cp-deep-ocean mb-0" style="font-size: 16px; line-height: 24px; letter-spacing: -0.3125px;">
                Please update your profile before adding your organization.
            </p>
        </div>
        <!-- Header -->
        <div class="mb-5">
            <h1 class="font-mackay fw-bold text-cp-deep-ocean mb-3" style="font-size: 36px; line-height: 44px;">Start the Journey Right Here</h1>
            <p class="font-graphik text-secondary fs-6">Register as an applicant, add your organization, and submit your CSR & Sustainability projects.</p>
            
            <!-- Privacy Notice -->
            <div class="rounded-4 border p-3 mb-4 position-relative" style="background-color: rgba(255, 255, 255, 0.6); border-color: #CBFBF1 !important;">
                <!-- Icon Container -->
                <div class="rounded-circle position-absolute d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background-color: #CBFBF1; top: 14px; left: 20px;">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/privacy-icon.svg" alt="Privacy" style="width: 16px; height: 16px;">
                </div>
                
                <!-- Text Content -->
                <p class="font-graphik text-cp-deep-ocean mb-0" style="margin-left: 44px; font-size: 16px; line-height: 24px; letter-spacing: -0.3125px;">
                    Applicant Details will remain confidential, and will not be part of your project's listing in the Sustainable Impact Challenge.
                </p>
            </div>
        </div>

        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                    <h2 class="font-graphik fw-bold text-cp-deep-ocean mb-4 fs-5">Applicant Details</h2>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-success"><?php echo esc_html($message); ?></div>
                    <?php endif; ?>
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger"><?php echo esc_html($error_message); ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <?php wp_nonce_field('sic_save_profile'); ?>
                        <input type="hidden" name="sic_profile_action" value="save">
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean small">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control bg-light" value="<?php echo esc_attr($applicant->first_name ?? ''); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean small">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control bg-light" value="<?php echo esc_attr($applicant->last_name ?? ''); ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-graphik fw-medium text-cp-deep-ocean small">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control bg-light" value="<?php echo esc_attr($applicant->email ?? ''); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-graphik fw-medium text-cp-deep-ocean small">Designation <span class="text-danger">*</span></label>
                            <input type="text" name="designation" class="form-control bg-light" value="<?php echo esc_attr($applicant->designation ?? ''); ?>" required placeholder="e.g. CSR Manager">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-custom-aqua text-white rounded-pill px-5 fw-bold font-graphik">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
               <div class="rounded-4 shadow-sm p-4 position-relative" style="background-color: #FAEBDA; border: 1px solid #FC9C63;">
                    <!-- Icon Container -->
                    <div class="d-flex align-items-center justify-content-center rounded-circle position-absolute" style="width: 40px; height: 40px; background-color: #FC9C63; top: 17px; left: 13px;">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/info-icon.svg" alt="Info" style="width: 20px; height: 20px;">
                    </div>
                    
                    <!-- Content -->
                    <div class="font-graphik text-cp-deep-ocean" style="margin-left: 53px; font-size: 14px; line-height: 20px; letter-spacing: -0.1504px;">
                        <p class="mb-0">Provide your personal details, including your name, contact information, and designation.</p>
                        <p class="mb-0">&nbsp;</p>
                        <p class="mb-0">Ensure all information is accurate before hitting submit, as you won't be able to edit it later.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php get_footer('dashboard'); ?>
