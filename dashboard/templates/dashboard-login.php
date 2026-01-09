<?php
/**
 * Template Name: Dashboard Login
 */

// If logged in, redirect to dashboard
if ( isset($_SESSION['sic_user_id']) ) {
    wp_redirect( SIC_Routes::get_dashboard_home_url() );
    exit;
}

// Handle Login Action
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'sic_mock_login' ) {
    $db = SIC_DB::get_instance();
    $applicant = $db->get_or_create_dummy_applicant();
    
    if ( $applicant ) {
        $_SESSION['sic_user_id'] = $applicant->applicant_id;
        $_SESSION['sic_user_name'] = $applicant->first_name . ' ' . $applicant->last_name;
        wp_redirect( SIC_Routes::get_dashboard_home_url() );
        exit;
    }
}

get_header(); // Use main header but we might hide nav
?>

<div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="bg-white p-5 rounded-4 shadow-sm text-center" style="max-width: 450px; width: 100%;">
        <div class="mb-4">
             <!-- Use Majra Logo if available or placeholder -->
             <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-2">Welcome Back</h2>
             <p class="font-graphik text-secondary">Sign in to the SIC Submission Portal</p>
        </div>

        <form method="POST">
            <input type="hidden" name="action" value="sic_mock_login">
            
            <div class="mb-4 alert alert-info font-graphik small text-start">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Note:</strong> This is a mock login for development purposes. You will be signed in as a test user.
            </div>

            <button type="submit" class="btn btn-lg w-100 text-white font-graphik fw-medium" style="background-color: var(--cp-aqua-marine);">
                Login with Dummy User
            </button>
            
            <div class="mt-4 pt-4 border-top">
                <p class="small text-secondary font-graphik">UAE PASS integration coming soon.</p>
            </div>
        </form>
    </div>
</div>

<?php get_footer(); ?>
