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
                <p class="font-graphik text-cp-deep-ocean fs-5">
                    <?php echo $language['DASHBOARD']['HOME_WITH_ORG']['PAGE_SUBTITLE']; ?>
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="<?php echo SIC_Routes::get_create_project_url(); ?>" class="btn-custom-primary"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['SUBMIT_NEW_PROJECT_BTN']; ?></a>
            </div>
        </div>

        <!-- Projects List Card -->
        <div class="bg-white rounded-lg p-4 p-lg-5 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="font-graphik fw-medium text-cp-deep-ocean m-0"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['LISTINGS_TITLE']; ?></h2>
                
                <!-- Filter/Search (Optional placeholder) -->
                <div class="d-none d-md-block">
                    <div class="input-group">
                        <input type="text" class="form-control border-end-0 rounded-start-pill ps-4" placeholder="<?php echo $language['DASHBOARD']['HOME_WITH_ORG']['SEARCH_PLACEHOLDER']; ?>">
                        <span class="input-group-text bg-white border-start-0 rounded-end-pill pe-4">
                            <i class="bi bi-search text-secondary"></i>
                        </span>
                    </div>
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
                        // If using dummy login, session variable should be set
                        
                        $projects = $db->get_projects_by_applicant( $user_id );

                        if ( empty($projects) ):
                        ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <p class="text-secondary mb-0"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['EMPTY_STATE_TEXT']; ?> <a href="<?php echo SIC_Routes::get_create_project_url(); ?>"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['EMPTY_STATE_LINK']; ?></a>.</p>
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
                                            <li><a class="dropdown-item" href="<?php echo esc_url($edit_url); ?>"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['EDIT']; ?></a></li>
                                            <!-- <li><a class="dropdown-item text-danger" href="#">Delete</a></li> -->
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination (Hidden for now until implemented) -->
            <!-- 
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                ... 
            </div>
            -->
        </div>
    </div>
</main>

<?php
get_footer('dashboard');
?>
