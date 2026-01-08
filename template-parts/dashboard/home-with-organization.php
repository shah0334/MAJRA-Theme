<?php
/**
 * Dashboard Home - With Organization State
 */
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
                            <th class="py-3 border-0 font-graphik text-secondary fw-medium">Cycle</th>
                            <th class="py-3 border-0 font-graphik text-secondary fw-medium">Status</th>
                            <th class="py-3 border-0 font-graphik text-secondary fw-medium">Last Modified</th>
                            <th class="py-3 pe-4 border-0 text-end font-graphik text-secondary fw-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Empty State Row -->
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-secondary mb-3">
                                    <i class="bi bi-folder2-open fs-1"></i>
                                </div>
                                <p class="font-graphik text-secondary mb-3">No projects found for this cycle.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
