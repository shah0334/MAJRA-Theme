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
    $user_index = isset($_POST['user_index']) ? intval($_POST['user_index']) : 1;
    $db = SIC_DB::get_instance();
    $applicant = $db->get_or_create_dummy_applicant($user_index);
    
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

            <?php
            // Check DB Connection
            $db = SIC_DB::get_instance();
            $is_connected = $db->is_connected();
            $error_msg = $db->get_last_error();
            $is_external = $db->is_using_external_db();
            $db_info = $db->get_config_info();
            
            // 1. Show Connection Source Warning if falling back to WP DB
            if ( ! $is_external ) {
                ?>
                <div class="alert alert-warning font-graphik small text-start">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <strong>Config Warning:</strong> Using Local WordPress Database (External DB config missing or invalid).
                </div>
                <?php
            } else {
                ?>
                <div class="alert alert-success font-graphik small text-start py-2">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-database-check me-2 fs-5"></i>
                        <div>
                            <strong>Connected to External Database</strong><br>
                            <span class="text-muted" style="font-size: 0.9em;">
                                Host: <?php echo esc_html($db_info['host']); ?> | DB: <?php echo esc_html($db_info['name']); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php
            }

            // 2. Show Error if Connection Failed or Tables Missing
            if ( ! $is_connected ) {
                ?>
                <div class="alert alert-danger font-graphik small text-start">
                    <div class="mb-2">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Connection Error:</strong> <?php echo esc_html( $error_msg ); ?>
                    </div>
                    <div class="mb-2 text-muted" style="font-size: 0.85em;">
                         Attempted: Host: <?php echo esc_html($db_info['host']); ?> | DB: <?php echo esc_html($db_info['name']); ?>
                    </div>
                    
                    <?php if ( strpos($error_msg, 'does not exist') !== false ): ?>
                        <div class="mt-2 pt-2 border-top border-danger-subtle">
                            <p class="mb-2 small">The database is connected but tables are missing.</p>
                            <a href="<?php echo esc_url( get_template_directory_uri() . '/setup-sic-db.php' ); ?>" class="btn btn-sm btn-danger w-100" target="_blank">
                                <i class="bi bi-tools me-1"></i> Run Database Setup Script
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
            }
            ?>

            <button type="submit" name="user_index" value="1" class="btn btn-lg w-100 text-white font-graphik fw-medium mb-3" style="background-color: var(--cp-aqua-marine);">
                Login with Dummy User 1
            </button>
            <button type="submit" name="user_index" value="2" class="btn btn-lg w-100 text-white font-graphik fw-medium" style="background-color: var(--cp-deep-ocean);">
                Login with Dummy User 2
            </button>
            
            <div class="mt-4 pt-4 border-top">
                <p class="small text-secondary font-graphik">UAE PASS integration coming soon.</p>
            </div>
        </form>
    </div>
</div>

<?php get_footer(); ?>
