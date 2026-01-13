<?php
/* Template Name: Dashboard - My Projects */

get_header('dashboard');
global $language;
?>

<main id="primary" class="site-main bg-cp-cream-light py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-lg-8">
                <h1 class="font-mackay fw-bold text-cp-deep-ocean mb-3"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['PAGE_TITLE']; ?></h1>
                <p class="font-graphik text-cp-deep-ocean fs-5 mb-4">
                    <?php echo $language['DASHBOARD']['HOME_WITH_ORG']['PAGE_SUBTITLE']; ?>
                </p>
                 <?php if ( !current_user_can('manage_options') ): ?>
                <a href="<?php echo SIC_Routes::get_create_project_url(); ?>" class="btn-custom-primary"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['SUBMIT_NEW_PROJECT_BTN']; ?></a>
                <?php endif; ?>
            </div>
       
        </div>

        <!-- Projects List Card -->
        <div class="bg-white rounded-lg p-4 p-lg-5 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="font-graphik fw-medium text-cp-deep-ocean m-0"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['LISTINGS_TITLE']; ?></h2>
                
                <!-- Filter/Search -->
                <div class="d-none d-md-block">
                    <form method="GET" action="">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control border-end-0 rounded-start-pill ps-4" placeholder="<?php echo $language['DASHBOARD']['HOME_WITH_ORG']['SEARCH_PLACEHOLDER']; ?>" value="<?php echo isset($_GET['search']) ? esc_attr($_GET['search']) : ''; ?>">
                            <button type="submit" class="input-group-text bg-white border-start-0 rounded-end-pill pe-4">
                                <i class="bi bi-search text-secondary"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Projects Table -->
            <div class="table-responsive">
                <table class="table table-hover custom-dashboard-table align-middle">
                    <thead>
                        <tr>
                            <th scope="col"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['PROJECT_NAME']; ?></th>
                            <th scope="col"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['ORGANIZATION']; ?></th>
                            <th scope="col"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['STAGE']; ?></th>
                            <th scope="col"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['SUBMISSION_DATE']; ?></th>
                            <th scope="col"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['STATUS']; ?></th>
                            <th scope="col" class="text-end"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['ACTION']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $db = SIC_DB::get_instance();
                        $user_id = isset($_SESSION['sic_user_id']) ? $_SESSION['sic_user_id'] : get_current_user_id(); // Fallback
                        
                        // Pagination & Search Params
                        $paged = isset($_GET['curr_page']) ? max(1, intval($_GET['curr_page'])) : 1;
                        $limit = 10;
                        $search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

                        if ( current_user_can('manage_options') ) {
                            $data = $db->get_all_projects($search, $paged, $limit);
                        } else {
                            $data = $db->get_projects_by_applicant( $user_id, $search, $paged, $limit );
                        }
                        
                        $projects = $data['results'];
                        $total_records = $data['total'];
                        $total_pages = ceil($total_records / $limit);

                        if ( empty($projects) ):
                        ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <p class="text-secondary mb-0"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['EMPTY_STATE_TEXT']; ?> 
                                    <?php if ( !current_user_can('manage_options') && empty($search) ): ?>
                                    <a href="<?php echo SIC_Routes::get_create_project_url(); ?>"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['EMPTY_STATE_LINK']; ?></a>.
                                    <?php endif; ?>
                                    </p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ( $projects as $project ): 
                                $status_class = 'status-draft';
                                $status_label = $language['DASHBOARD']['HOME_WITH_ORG']['DRAFT'];
                                if ( $project->submission_status === 'submitted' ) {
                                    $status_class = 'status-review'; // or approved
                                    $status_label = $language['DASHBOARD']['HOME_WITH_ORG']['SUBMITTED'];
                                }
                                $edit_url = add_query_arg( ['step' => 1, 'project_id' => $project->project_id], SIC_Routes::get_create_project_url() );
                            ?>
                            <tr>
                                <td class="fw-semibold text-cp-deep-ocean"><?php echo esc_html( $project->project_name ); ?></td>
                                <td class="text-secondary"><?php echo esc_html( $project->organization_name ); ?></td>
                                <td><span class="badge bg-light text-cp-deep-ocean border rounded-pill px-3 py-2 fw-normal"><?php echo esc_html( $project->project_stage ); ?></span></td>
                                <td class="text-secondary"><?php echo date( 'M d, Y', strtotime($project->created_at) ); ?></td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo $status_label; ?></span></td>
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-link text-secondary p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical fs-5"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="<?php echo SIC_Routes::get_view_project_url($project->project_id); ?>">View</a></li>
                                            <?php if ( !current_user_can('manage_options') && $project->submission_status !== 'submitted' ): ?>
                                            <li><a class="dropdown-item" href="<?php echo esc_url($edit_url); ?>"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['EDIT']; ?></a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
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
</main>

<?php
get_footer('dashboard');
?>
