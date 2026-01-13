<?php
/**
 * Dashboard Home - With Organization State
 */
$db = SIC_DB::get_instance();
global $language;
$user_id = isset($_SESSION['sic_user_id']) ? $_SESSION['sic_user_id'] : get_current_user_id();
if ( current_user_can('manage_options') ) {
    $projects = $db->get_all_projects();
} else {
    $projects = $db->get_projects_by_applicant( $user_id );
}
?>
<section class="projects-section py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="font-mackay text-cp-deep-ocean mb-0"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['MY_PROJECT_SUBMISSIONS']; ?></h2>
            <?php if ( !current_user_can('manage_options') ): ?>
            <a href="<?php echo SIC_Routes::get_create_project_url(); ?>" class="btn btn-custom-aqua text-white rounded-pill px-4 fw-bold">
                <i class="bi bi-plus-lg me-2"></i> <?php echo $language['DASHBOARD']['HOME_WITH_ORG']['CREATE_NEW_PROJECT']; ?>
            </a>
            <?php endif; ?>
        </div>

        <!-- Projects Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 ps-4 border-0 font-graphik text-secondary fw-medium"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['PROJECT_NAME']; ?></th>
                            <th class="py-3 border-0 font-graphik text-secondary fw-medium"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['ORGANIZATION']; ?></th>
                            <th class="py-3 border-0 font-graphik text-secondary fw-medium"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['STAGE']; ?></th>
                            <th class="py-3 border-0 font-graphik text-secondary fw-medium"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['SUBMISSION_DATE']; ?></th>
                            <th class="py-3 border-0 font-graphik text-secondary fw-medium"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['STATUS']; ?></th>
                            <th class="py-3 pe-4 border-0 text-end font-graphik text-secondary fw-medium"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['ACTION']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ( empty($projects) ): ?>
                        <!-- Empty State Row -->
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-secondary mb-3">
                                    <i class="bi bi-folder2-open fs-1"></i>
                                </div>
                                <p class="font-graphik text-secondary mb-3"><?php echo $language['DASHBOARD']['HOME_WITH_ORG']['NO_PROJECTS']; ?></p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ( $projects as $project ): 
                                $status_class = 'status-draft'; // You might need to add specific CSS classes for statuses like 'status-draft', 'status-review' if not present in your CSS, or reuse existing badge classes
                                $status_label = $language['DASHBOARD']['HOME_WITH_ORG']['DRAFT'];
                                $badge_class = 'bg-secondary';
                                
                                if ( $project->submission_status === 'submitted' ) {
                                    $status_class = 'status-review';
                                    $status_label = $language['DASHBOARD']['HOME_WITH_ORG']['SUBMITTED'];
                                    $badge_class = 'bg-info'; // Example
                                }
                                
                                // Consistent styling with dashboard-projects.php
                                // Using the same status badge classes if defined globally
                                
                                $edit_url = add_query_arg( ['step' => 1, 'project_id' => $project->project_id], SIC_Routes::get_create_project_url() );
                            ?>
                            <tr>
                                <td class="py-3 ps-4 fw-semibold text-cp-deep-ocean"><?php echo esc_html( $project->project_name ); ?></td>
                                <td class="py-3 text-secondary"><?php echo esc_html( $project->organization_name ); ?></td>
                                <td class="py-3"><span class="badge bg-light text-cp-deep-ocean border rounded-pill px-3 py-2 fw-normal"><?php echo esc_html( $project->project_stage ); ?></span></td>
                                <td class="py-3 text-secondary"><?php echo date( 'M d, Y', strtotime($project->created_at) ); ?></td>
                                <td class="py-3">
                                    <span class="status-badge <?php echo $status_class; ?>"><?php echo $status_label; ?></span>
                                </td>
                                <td class="py-3 pe-4 text-end">
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
        </div>
    </div>
</section>
