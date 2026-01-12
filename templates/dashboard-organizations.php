<?php
/* Template Name: Dashboard - My Organizations */

get_header('dashboard');
global $language;
?>

<main id="primary" class="site-main bg-cp-cream-light py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-lg-8">
                <h1 class="font-mackay fw-bold text-cp-deep-ocean mb-3"><?php echo $language['DASHBOARD']['DASHBOARD_ORG']['PAGE_TITLE']; ?></h1>
                <p class="font-graphik text-cp-deep-ocean fs-5">
                    <?php echo $language['DASHBOARD']['DASHBOARD_ORG']['PAGE_SUBTITLE']; ?>
                </p>
            </div>
        </div>

            <?php
            $db = SIC_DB::get_instance();
            if ( current_user_can('manage_options') ) {
                $org_profiles = $db->get_all_organizations();
            } else {
                $user_id = isset($_SESSION['sic_user_id']) ? $_SESSION['sic_user_id'] : 0;
                $org_profiles = $db->get_organizations_by_applicant_id( $user_id );
            }
            
            // Should show table even if empty, as per typical dashboard behavior, or keep existing logic?
            // "the table is missing a create button, user can create multiple organizations" -> implies create button should be always visible or available.
            
            // Let's change the condition: ALWAYS show the table structure (or at least the header/create button), and loop through rows.
            // If empty, show "No organizations found".
            
            ?>
            <!-- Registered Organizations Section -->
            <div class="bg-white rounded-4 p-4 shadow-sm">
                <!-- Header & Create Button -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <h2 class="font-graphik fw-bold text-cp-deep-ocean m-0 fs-5"><?php echo $language['DASHBOARD']['DASHBOARD_ORG']['REGISTERED_ORG_TITLE']; ?></h2>
                        <i class="bi bi-info-circle text-secondary" data-bs-toggle="tooltip" title="<?php echo $language['DASHBOARD']['DASHBOARD_ORG']['TOOLTIP']; ?>"></i>
                    </div>
                    <!-- Create Button -->
                    <a href="<?php echo SIC_Routes::get_create_org_url(); ?>" class="btn btn-custom-aqua text-white rounded-pill px-4 fw-bold"><?php echo $language['DASHBOARD']['DASHBOARD_ORG']['CREATE_BTN']; ?></a>
                </div>

                <!-- Search Bar -->
                <div class="mb-4">
                    <div class="position-relative">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                        <input type="text" class="form-control form-control-lg bg-light border-0 ps-5 fs-6" placeholder="<?php echo $language['DASHBOARD']['DASHBOARD_ORG']['SEARCH_PLACEHOLDER']; ?>">
                    </div>
                </div>

                <!-- Organizations Table -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 ps-3 border-0 font-graphik text-secondary fw-bold small"><?php echo $language['DASHBOARD']['DASHBOARD_ORG']['ORG_NAME']; ?></th>
                                <th class="py-3 border-0 font-graphik text-secondary fw-bold small"><?php echo $language['DASHBOARD']['DASHBOARD_ORG']['CIVIL_NUM']; ?></th>
                                <th class="py-3 border-0 font-graphik text-secondary fw-bold small"><?php echo $language['DASHBOARD']['DASHBOARD_ORG']['EMIRATE']; ?></th>
                                <th class="py-3 border-0 font-graphik text-secondary fw-bold small"><?php echo $language['DASHBOARD']['DASHBOARD_ORG']['DATE']; ?></th>
                                <th class="py-3 pe-3 border-0 font-graphik text-secondary fw-bold small text-end"><?php echo $language['DASHBOARD']['DASHBOARD_ORG']['ACTION']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ( !empty($org_profiles) ) : ?>
                                <?php foreach ( $org_profiles as $org_profile ) : ?>
                                <tr>
                                    <td class="ps-3 py-3 font-graphik fw-medium text-cp-deep-ocean">
                                        <?php echo esc_html($org_profile->organization_name); ?>
                                    </td>
                                    <td class="py-3 font-graphik text-secondary">
                                        <?php echo esc_html($org_profile->trade_license_number); ?>
                                    </td>
                                    <td class="py-3 font-graphik text-secondary">
                                        <?php echo esc_html($org_profile->emirate_of_registration); ?>
                                    </td>
                                    <td class="py-3 font-graphik text-secondary">
                                        <i class="bi bi-calendar4 me-2"></i>
                                        <?php echo date('d F Y'); // Placeholder ?>
                                    </td>
                                    <td class="pe-3 py-3 text-end">
                                        <div class="d-flex align-items-center justify-content-end gap-3">
                                            <button class="btn btn-link p-0 text-secondary" title="<?php echo $language['DASHBOARD']['DASHBOARD_ORG']['VIEW']; ?>"><i class="bi bi-eye"></i></button>
                                            <a href="<?php echo SIC_Routes::get_create_project_url( $org_profile->org_profile_id ); ?>" class="text-cp-coral-sunset fw-bold text-decoration-none border-bottom border-cp-coral-sunset"><?php echo $language['DASHBOARD']['DASHBOARD_ORG']['CREATE_PROJECT']; ?></a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-secondary"><?php echo $language['DASHBOARD']['DASHBOARD_ORG']['EMPTY_STATE']; ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
get_footer('dashboard');
?>
