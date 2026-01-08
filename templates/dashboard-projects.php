<?php
/* Template Name: Dashboard - My Projects */

get_header('dashboard');
?>

<main id="primary" class="site-main bg-cp-cream-light py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-lg-8">
                <h1 class="font-mackay fw-bold text-cp-deep-ocean mb-3">My Projects</h1>
                <p class="font-graphik text-cp-deep-ocean fs-5">
                    Track the status of your submitted projects and manage your entries for the Sustainability Impact Challenge.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="#" class="btn-custom-primary">Submit New Project</a>
            </div>
        </div>

        <!-- Projects List Card -->
        <div class="bg-white rounded-lg p-4 p-lg-5 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="font-graphik fw-medium text-cp-deep-ocean m-0">Project Listings</h2>
                
                <!-- Filter/Search (Optional placeholder) -->
                <div class="d-none d-md-block">
                    <div class="input-group">
                        <input type="text" class="form-control border-end-0 rounded-start-pill ps-4" placeholder="Search projects...">
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
                            <th scope="col">Project Name</th>
                            <th scope="col">Organization Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Submission Date</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Row 1 -->
                        <tr>
                            <td class="fw-semibold text-cp-deep-ocean">Net-Zero Biofuel</td>
                            <td class="text-secondary">Neutral Fuels LLC</td>
                            <td><span class="badge bg-light text-cp-deep-ocean border rounded-pill px-3 py-2 fw-normal">Environment</span></td>
                            <td class="text-secondary">Mar 28, 2025</td>
                            <td><span class="status-badge status-review">Under Review</span></td>
                            <td class="text-end">
                                <button class="btn btn-link text-secondary p-0">
                                    <i class="bi bi-three-dots-vertical fs-5"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Row 2 -->
                        <tr>
                            <td class="fw-semibold text-cp-deep-ocean">Green Education Initiative</td>
                            <td class="text-secondary">Future Skills Academy</td>
                            <td><span class="badge bg-light text-cp-deep-ocean border rounded-pill px-3 py-2 fw-normal">Social</span></td>
                            <td class="text-secondary">Apr 02, 2025</td>
                            <td><span class="status-badge status-draft">Draft</span></td>
                            <td class="text-end">
                                <button class="btn btn-link text-secondary p-0">
                                    <i class="bi bi-three-dots-vertical fs-5"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Row 3 -->
                        <tr>
                            <td class="fw-semibold text-cp-deep-ocean">Solar Powered Community</td>
                            <td class="text-secondary">Green Earth Co.</td>
                            <td><span class="badge bg-light text-cp-deep-ocean border rounded-pill px-3 py-2 fw-normal">Environment</span></td>
                            <td class="text-secondary">Mar 15, 2025</td>
                            <td><span class="status-badge status-approved">Approved</span></td>
                            <td class="text-end">
                                <button class="btn btn-link text-secondary p-0">
                                    <i class="bi bi-three-dots-vertical fs-5"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Empty State (Hidden by default, shown if no rows) -->
             <!-- 
            <div class="text-center py-5">
                <i class="bi bi-folder2-open fs-1 text-secondary opacity-50 mb-3 d-block"></i>
                <p class="text-secondary">No projects submitted yet.</p>
            </div> 
            -->

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <p class="text-secondary mb-0 small">Showing 1 to 3 of 3 entries</p>
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link border-0 rounded-circle mx-1" href="#"><i class="bi bi-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link border-0 rounded-circle mx-1 bg-cp-coral-sunset" href="#">1</a></li>
                        <li class="page-item disabled"><a class="page-link border-0 rounded-circle mx-1" href="#"><i class="bi bi-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</main>

<?php
get_footer('dashboard');
?>
