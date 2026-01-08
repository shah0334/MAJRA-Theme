<?php
/**
 * Dashboard Home - With Organization State
 */
$db = SIC_DB::get_instance();
$user_id = isset($_SESSION['sic_user_id']) ? $_SESSION['sic_user_id'] : get_current_user_id();
$projects = $db->get_projects_by_applicant( $user_id );
?>
<section class="projects-section py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="font-mackay text-cp-deep-ocean mb-0">My Project Submissions</h2>
            <a href="<?php echo SIC_Routes::get_create_project_url(); ?>" class="btn btn-custom-aqua text-white rounded-pill px-4 fw-bold">
                <i class="bi bi-plus-lg me-2"></i> Create New Project
            </a>
        </div>

        <!-- Projects Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 ps-4 border-0 font-graphik text-secondary fw-medium">Project Name</th>
                            <th class="py-3 border-0 font-graphik text-secondary fw-medium">Organization</th>
                            <th class="py-3 border-0 font-graphik text-secondary fw-medium">Stage</th>
                            <th class="py-3 border-0 font-graphik text-secondary fw-medium">Submission Date</th>
                            <th class="py-3 border-0 font-graphik text-secondary fw-medium">Status</th>
                            <th class="py-3 pe-4 border-0 text-end font-graphik text-secondary fw-medium">Action</th>
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
                                <p class="font-graphik text-secondary mb-3">No projects found for this cycle.</p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ( $projects as $project ): 
                                $status_class = 'status-draft'; // You might need to add specific CSS classes for statuses like 'status-draft', 'status-review' if not present in your CSS, or reuse existing badge classes
                                $status_label = 'Draft';
                                $badge_class = 'bg-secondary';
                                
                                if ( $project->submission_status === 'submitted' ) {
                                    $status_class = 'status-review';
                                    $status_label = 'Submitted';
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
                                            <li><a class="dropdown-item" href="<?php echo esc_url($edit_url); ?>">Edit</a></li>
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
