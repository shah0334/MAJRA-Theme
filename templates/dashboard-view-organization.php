<?php
/* Template Name: Dashboard - View Organization */

get_header('dashboard');
global $language;
$db = SIC_DB::get_instance();

$org_id = isset($_GET['org_id']) ? intval($_GET['org_id']) : 0;

// Access Check
$can_view = false;
$profile = $db->get_org_profile_by_id( $org_id );

if ( !$profile ) {
    ?>
    <main id="primary" class="site-main bg-cp-cream-light py-5">
        <div class="container">
            <div class="alert alert-warning">Organization not found.</div>
            <a href="<?php echo SIC_Routes::get_dashboard_home_url(); ?>" class="btn btn-outline-primary mt-3">Back to Dashboard</a>
        </div>
    </main>
    <?php
    get_footer('dashboard');
    exit;
}

if ( !current_user_can('manage_options') ) {
    $current_applicant_id = isset($_SESSION['sic_user_id']) ? $_SESSION['sic_user_id'] : 0;
    
    // Safety check: created_by_applicant_id must match session
    if ( !$current_applicant_id || $profile->created_by_applicant_id != $current_applicant_id ) {
         ?>
         <main id="primary" class="site-main bg-cp-cream-light py-5">
             <div class="container">
                 <div class="alert alert-danger">Unauthorized access. This organization does not belong to you.</div>
             </div>
         </main>
         <?php
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
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-4">
            <div>
                <h1 class="font-mackay fw-bold text-cp-deep-ocean mb-1"><?php echo $language['DASHBOARD']['VIEW_ORG']['PAGE_TITLE']; ?></h1>
                <p class="text-secondary mb-0"><?php echo $language['DASHBOARD']['VIEW_ORG']['PAGE_SUBTITLE']; ?></p>
            </div>
            <div>
                <a href="<?php echo SIC_Routes::get_my_organizations_url(); ?>" class="btn btn-outline-secondary rounded-pill px-4 d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left"></i> <?php echo $language['DASHBOARD']['VIEW_ORG']['BACK_BTN']; ?>
                </a>
            </div>
        </div>

        <!-- Info Alert -->
        <div class="info-alert-custom mb-4">
            <i class="bi bi-info-circle"></i>
            <div>
                <?php echo $language['DASHBOARD']['VIEW_ORG']['INFO_ALERT']; ?>
            </div>
        </div>

        <!-- 1. Organization Overview -->
        <div class="details-section-card">
            <div class="details-header">
                <h2><?php echo $language['DASHBOARD']['VIEW_ORG']['SECTION_OVERVIEW']; ?></h2>
            </div>
            
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_ORG_NAME']; ?></div>
                <div class="details-value"><?php echo esc_html($profile->canonical_name); ?></div>
            </div>
            
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_TRADE_LICENSE']; ?></div>
                <div class="details-value"><?php echo esc_html($profile->trade_license_number); ?></div>
            </div>
            
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_WEBSITE']; ?></div>
                <div class="details-value">
                    <?php if ( filter_var($profile->website_url, FILTER_VALIDATE_URL) ): ?>
                        <a href="<?php echo esc_url($profile->website_url); ?>" target="_blank" class="text-cp-app-blue text-decoration-none">
                            <?php echo esc_html($profile->website_url); ?> <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                        </a>
                    <?php else: ?>
                        <?php echo esc_html($profile->website_url); ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_CONTACT_NUM']; ?></div>
                <div class="details-value"><?php echo esc_html($profile->contact_phone); ?></div>
            </div>

            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_BANK_NAME']; ?></div>
                <div class="details-value"><?php echo esc_html($profile->bank_name); ?></div>
            </div>

            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_IBAN_NUM']; ?></div>
                <div class="details-value"><?php echo esc_html($profile->iban_number); ?></div>
            </div>
            
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_EMIRATE']; ?></div>
                <div class="details-value"><?php echo esc_html($profile->emirate_of_registration); ?></div>
            </div>
            
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_ENTITY_TYPE']; ?></div>
                <div class="details-value"><?php echo esc_html($profile->legal_entity_type); ?></div>
            </div>
            
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_INDUSTRY']; ?></div>
                <div class="details-value"><?php echo esc_html($profile->industry); ?></div>
            </div>
            
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_FREEZONE']; ?></div>
                <div class="details-value"><?php echo $profile->is_freezone ? $language['DASHBOARD']['VIEW_ORG']['YES'] : $language['DASHBOARD']['VIEW_ORG']['NO']; ?></div>
            </div>
        </div>

        <!-- 2. Legal Documents -->
        <div class="details-section-card">
             <div class="details-header">
                <h2><?php echo $language['DASHBOARD']['VIEW_ORG']['SECTION_DOCUMENTS']; ?></h2>
            </div>
            <div class="p-4">
                <div class="file-download-card">
                    <div class="d-flex align-items-center">
                        <div class="file-icon-wrapper">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div class="file-info">
                            <h4><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_TRADE_LICENSE_FILE']; ?></h4>
                            <div class="file-meta">
                                <?php 
                                // We don't have exact filename or size easily available without more queries, 
                                // so we'll just show a generic label or try to parse the URL if possible, 
                                // but for now keeping it simple as per Figma "Trade-License.pdf" style not critical to be dynamic
                                echo basename($license_url); 
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($license_url): ?>
                    <a href="<?php echo esc_url($license_url); ?>" target="_blank" class="btn-download-outline" download>
                        <i class="bi bi-download"></i> <?php echo $language['DASHBOARD']['VIEW_ORG']['BTN_DOWNLOAD']; ?>
                    </a>
                    <?php else: ?>
                        <span class="text-secondary small"><?php echo $language['DASHBOARD']['VIEW_ORG']['NOT_UPLOADED']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- 3. Classification Profile -->
        <div class="details-section-card">
            <div class="details-header">
                <h2><?php echo $language['DASHBOARD']['VIEW_ORG']['SECTION_CLASSIFICATION']; ?></h2>
            </div>
             <div class="p-3 bg-warning-subtle mx-4 mt-4 rounded border border-warning-subtle text-warning-emphasis small">
                <i class="bi bi-exclamation-circle me-2"></i> <?php echo $language['DASHBOARD']['VIEW_ORG']['ALERT_REPORTING_ONLY']; ?>
            </div>

            <div class="details-row border-top mt-3"> <!-- Added border top since we have content above it inside body -->
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_BUSINESS_ACTIVITY']; ?></div>
                <div class="details-value"><?php echo esc_html($profile->business_activity_type); ?></div>
            </div>
            
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_EMPLOYEES']; ?></div>
                <div class="details-value"><?php echo esc_html($profile->number_of_employees); ?></div>
            </div>
            
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_TURNOVER']; ?></div>
                <div class="details-value"><?php echo esc_html($profile->annual_turnover_band); ?></div>
            </div>
        </div>

        <!-- 4. CSR Declaration Summary -->
        <div class="details-section-card">
            <div class="details-header">
                <h2><?php echo $language['DASHBOARD']['VIEW_ORG']['SECTION_CSR']; ?></h2>
            </div>
            
            <div class="details-row">
                <div class="details-label"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_CSR_IMPLEMENTED']; ?></div>
                <div class="details-value">
                    <div class="csr-status-indicator">
                        <span class="csr-dot <?php echo $profile->csr_implemented ? 'yes' : 'no'; ?>"></span>
                        <?php echo $profile->csr_implemented ? $language['DASHBOARD']['VIEW_ORG']['YES'] : $language['DASHBOARD']['VIEW_ORG']['NO']; ?>
                    </div>
                </div>
            </div>
            
            <?php if ( $profile->csr_implemented ): ?>
             <div class="px-4 py-3 bg-white">
                <h3 class="font-graphik text-secondary fs-6 fw-bold mb-3"><?php echo $language['DASHBOARD']['VIEW_ORG']['SUBHEADER_CSR_ACTIVITIES']; ?></h3>
                
                <?php if ( !empty($csr_activities) ): ?>
                    <?php foreach( $csr_activities as $activity ): ?>
                    <div class="bg-cp-cream-light border rounded-3 p-3 mb-3" style="background-color: #f9fafb; border-color: #e5e7eb;">
                        <div class="row">
                            <div class="col-md-6 mb-2 mb-md-0">
                                <label class="font-graphik text-secondary d-block mb-1" style="font-size: 12px;"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_PROJ_NAME']; ?></label>
                                <div class="font-graphik text-cp-deep-ocean fw-medium" style="font-size: 14px; color: #111827;"><?php echo esc_html($activity->program_name); ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="font-graphik text-secondary d-block mb-1" style="font-size: 12px;"><?php echo $language['DASHBOARD']['VIEW_ORG']['LBL_AMOUNT']; ?></label>
                                <div class="font-graphik text-cp-deep-ocean fw-bold" style="font-size: 14px; color: #111827;"><?php echo number_format($activity->allocated_amount_aed); ?></div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-secondary small fst-italic mb-0"><?php echo $language['DASHBOARD']['VIEW_ORG']['NO_ACTIVITIES']; ?></p>
                <?php endif; ?>
                
             </div>
            <?php endif; ?>

            <!-- Declaration Text -->
            <div class="px-4 pb-4">
                 <div class="p-3 rounded bg-light border">
                    <p class="mb-0 font-graphik text-secondary small">
                        <?php echo $language['DASHBOARD']['ORG_FORM']['CSR_DECLARATION_TEXT']; ?>
                    </p>
                 </div>
            </div>

        </div>
        
        <div class="mb-5">
             <a href="<?php echo SIC_Routes::get_my_organizations_url(); ?>" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i> <?php echo $language['DASHBOARD']['VIEW_ORG']['BACK_BTN']; ?>
            </a>
        </div>

    </div>
</main>

<?php get_footer('dashboard'); ?>
