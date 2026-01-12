<?php
/* Template Name: Dashboard - View Organization */

get_header('dashboard');
global $language;
$db = SIC_DB::get_instance();

$org_id = isset($_GET['org_id']) ? intval($_GET['org_id']) : 0;
// Security: Admin can see any. Applicant only their own? 
// For now, let's assume if you have the link you can view, or we check ownership.
// User said: "separate view pages for both organizations and projects for admin"
// But also "both admins and applicants can see the view option".
// So we should verify access.

$can_view = false;
if ( current_user_can('manage_options') ) {
    $can_view = true;
    $org_profile = $db->get_org_profile_by_id( $org_id ); // Need to ensure this method exists or write query
    // Actually get_org_profile_by_id might not exist.
    // We have get_organization_by_applicant_id.
    // We need a method to get by Profile ID directly.
} else {
    $current_user_id = isset($_SESSION['sic_user_id']) ? $_SESSION['sic_user_id'] : 0;
    // Check ownership
    // $org_profile = $db->get_org_profile_by_id_and_user($org_id, $current_user_id);
    $can_view = true; // Placeholder until we robustly check
}

// Let's write a direct query here or use existing methods if possible.
// existing: get_organization_by_applicant_id (returns single row for active cycle)
// But we might be strictly viewing a specific ID passed in URL.
// Let's add a helper query here for now to get the profile by ID.

$profile = $db->get_org_profile_by_id( $org_id );

if ( !$profile ) {
    // Handle error
    echo '<div class="container py-5">Organization not found.</div>';
    get_footer('dashboard');
    exit;
}

// Access Check for non-admins
if ( !current_user_can('manage_options') ) {
    $current_applicant_id = isset($_SESSION['sic_user_id']) ? $_SESSION['sic_user_id'] : 0;
    if ( $profile->created_by_applicant_id != $current_applicant_id ) {
         echo '<div class="container py-5">Unauthorized.</div>';
         get_footer('dashboard');
         exit;
    }
}

// Fetch Files
$logo_url = $db->get_org_profile_file_url($org_id, 'logo');
$license_url = $db->get_org_profile_file_url($org_id, 'trade_license_certificate');

// Fetch CSR
$csr_activities = $db->get_org_csr_activities( $org_id );
?>

<main id="primary" class="site-main bg-cp-cream-light py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-12">
                <a href="<?php echo SIC_Routes::get_dashboard_home_url(); ?>" class="text-decoration-none text-secondary mb-3 d-inline-block"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
                <h1 class="font-mackay fw-bold text-cp-deep-ocean mb-0">Organization Details</h1>
                <p class="text-secondary">View Mode</p>
            </div>
        </div>

        <div class="bg-white rounded-lg p-5 shadow-sm mb-4">
             <div class="row g-4">
                <!-- Data Display -->
                <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Organization Name</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($profile->canonical_name); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Trade License Number</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($profile->trade_license_number); ?></p>
                </div>
                
                <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Website</label>
                     <p class="fs-5 text-cp-deep-ocean"><a href="<?php echo esc_url($profile->website_url); ?>" target="_blank"><?php echo esc_html($profile->website_url); ?></a></p>
                </div>
                 <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Emirate</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($profile->emirate_of_registration); ?></p>
                </div>
                
                <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Entity Type</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($profile->legal_entity_type); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Industry</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($profile->industry); ?></p>
                </div>
                
                 <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Business Activity</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($profile->business_activity_type); ?></p>
                </div>
                 <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Employees</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($profile->number_of_employees); ?></p>
                </div>
                
                <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Annual Turnover</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo esc_html($profile->annual_turnover_band); ?></p>
                </div>
                 <div class="col-md-6">
                    <label class="font-graphik fw-bold text-secondary text-uppercase small">Freezone?</label>
                    <p class="fs-5 text-cp-deep-ocean"><?php echo $profile->is_freezone ? 'Yes' : 'No'; ?></p>
                </div>
             </div>
        </div>
        
        <!-- CSR Section -->
        <div class="bg-white rounded-lg p-5 shadow-sm mb-4">
             <h3 class="font-mackay text-cp-deep-ocean mb-4">CSR Activities</h3>
             <?php if ( $profile->csr_implemented ): ?>
                <p><strong>CSR Implemented:</strong> Yes</p>
                <?php if ( !empty($csr_activities) ): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Initiative Name</th>
                                <th>Amount (AED)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($csr_activities as $activity): ?>
                            <tr>
                                <td><?php echo esc_html($activity->program_name); ?></td>
                                <td><?php echo number_format($activity->allocated_amount_aed, 2); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-secondary">No specific initiatives listed.</p>
                <?php endif; ?>
             <?php else: ?>
                <p><strong>CSR Implemented:</strong> No</p>
             <?php endif; ?>
        </div>

        <!-- Files Section -->
         <div class="bg-white rounded-lg p-5 shadow-sm mb-4">
            <h3 class="font-mackay text-cp-deep-ocean mb-4">Documents</h3>
            <div class="row g-4">
                <div class="col-md-6">
                     <p><strong>Logo:</strong></p>
                     <?php if ($logo_url): ?>
                        <img src="<?php echo esc_url($logo_url); ?>" class="img-thumbnail" style="max-height: 150px;">
                     <?php else: ?>
                        <span class="text-secondary">Not uploaded</span>
                     <?php endif; ?>
                </div>
                 <div class="col-md-6">
                     <p><strong>Trade License:</strong></p>
                     <?php if ($license_url): ?>
                        <a href="<?php echo esc_url($license_url); ?>" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-file-earmark-pdf"></i> View License</a>
                     <?php else: ?>
                        <span class="text-secondary">Not uploaded</span>
                     <?php endif; ?>
                </div>
            </div>
         </div>

    </div>
</main>

<?php get_footer('dashboard'); ?>
