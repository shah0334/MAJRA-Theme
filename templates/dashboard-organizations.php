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
            
            // Pagination & Search Params
            $paged = isset($_GET['curr_page']) ? max(1, intval($_GET['curr_page'])) : 1;
            $limit = 10;
            $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

            if ( current_user_can('manage_options') ) {
                $data = $db->get_all_organizations($search, $paged, $limit);
            } else {
                $user_id = isset($_SESSION['sic_user_id']) ? $_SESSION['sic_user_id'] : 0;
                $data = $db->get_organizations_by_applicant_id( $user_id, $search, $paged, $limit );
            }
            
            $org_profiles = $data['results'];
            $total_records = $data['total'];
            $total_pages = ceil($total_records / $limit);
            ?>

            <!-- Registered Organizations Section -->
            <div class="bg-white rounded-4 p-4 shadow-sm">
                <!-- Header & Create Button -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <h2 class="font-graphik fw-bold text-cp-deep-ocean m-0 fs-5"><?php echo $language['DASHBOARD']['DASHBOARD_ORG']['REGISTERED_ORG_TITLE']; ?></h2>
                        <i class="bi bi-info-circle text-secondary" data-bs-toggle="tooltip" title="<?php echo $language['DASHBOARD']['DASHBOARD_ORG']['TOOLTIP']; ?>"></i>
                    </div>
                    <?php if ( !current_user_can('manage_options') ): ?>
                    <!-- Create Button -->
                    <a href="<?php echo SIC_Routes::get_create_org_url(); ?>" class="btn btn-custom-aqua text-white rounded-pill px-4 fw-bold"><?php echo $language['DASHBOARD']['DASHBOARD_ORG']['CREATE_BTN']; ?></a>
                    <?php endif; ?>
                </div>

                <!-- Search Bar -->
                <div class="mb-4">
                    <form method="GET" action="">
                        <div class="position-relative">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                            <input type="text" name="search" class="form-control form-control-lg bg-light border-0 ps-5 fs-6" placeholder="<?php echo $language['DASHBOARD']['DASHBOARD_ORG']['SEARCH_PLACEHOLDER']; ?>" value="<?php echo esc_attr($search); ?>">
                        </div>
                    </form>
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
                                        <?php echo date('d F Y', strtotime($org_profile->created_at)); ?>
                                    </td>
                                    <td class="pe-3 py-3 text-end">
                                        <div class="d-flex align-items-center justify-content-end gap-3">
                                            <a href="<?php echo SIC_Routes::get_view_org_url($org_profile->org_profile_id); ?>" class="btn btn-link p-0 text-secondary" title="<?php echo $language['DASHBOARD']['DASHBOARD_ORG']['VIEW']; ?>"><i class="bi bi-eye"></i></a>
                                            <?php if ( !current_user_can('manage_options') ): ?>
                                            <a href="<?php echo SIC_Routes::get_create_project_url( $org_profile->org_profile_id ); ?>" class="text-cp-coral-sunset fw-bold text-decoration-none border-bottom border-cp-coral-sunset"><?php echo $language['DASHBOARD']['DASHBOARD_ORG']['CREATE_PROJECT']; ?></a>
                                            <?php endif; ?>
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

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                    <div class="text-secondary small">
                        Showing <?php echo (($paged - 1) * $limit) + 1; ?> to <?php echo min($paged * $limit, $total_records); ?> of <?php echo $total_records; ?> entries
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination mb-0">
                            <!-- Previous -->
                            <li class="page-item <?php echo ($paged <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link border-0 text-secondary" href="<?php echo add_query_arg(['curr_page' => $paged - 1, 'search' => $search]); ?>">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>
                            
                            <!-- Pages -->
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($paged == $i) ? 'active' : ''; ?>">
                                <a class="page-link border-0 rounded-circle d-flex align-items-center justify-content-center mx-1 <?php echo ($paged == $i) ? 'bg-cp-platinum text-cp-deep-ocean fw-bold' : 'text-secondary'; ?>" 
                                href="<?php echo add_query_arg(['curr_page' => $i, 'search' => $search]); ?>" style="width: 32px; height: 32px;">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                            <?php endfor; ?>

                            <!-- Next -->
                            <li class="page-item <?php echo ($paged >= $total_pages) ? 'disabled' : ''; ?>">
                                <a class="page-link border-0 text-secondary" href="<?php echo add_query_arg(['curr_page' => $paged + 1, 'search' => $search]); ?>">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer('dashboard');
?>
