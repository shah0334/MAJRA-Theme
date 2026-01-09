<?php
/* Template Name: Dashboard - Home No Organization */

get_dashboard_header();
?>

<main id="primary" class="site-main bg-cp-cream-light">

    <!-- Welcome Banner -->
    <section class="welcome-banner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>Welcome to the<br><span class="highlight">Sustainability Impact Challenge</span></h1>
                </div>
                <div class="col-lg-6">
                    <p>Transform your project into a story of impact. Showcase leadership, inspire industry-wide change and help shape the UAE's future. Submit your project today and be recognized for the difference you make.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Steps Section -->
    <section class="steps-section">
        <div class="container">
            <h2>3 Steps to<br><span class="highlight">mark your Impact</span></h2>
            
            <div class="row g-4">
                <!-- Step 1 -->
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-icon">
                            <i class="bi bi-person"></i> <!-- Placeholder for icon -->
                            <span>1</span>
                        </div>
                        <h3>1. Register as Applicant</h3>
                        <p>Provide us with your contact information to reach you.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-icon">
                            <i class="bi bi-building"></i> <!-- Placeholder for icon -->
                            <span>2</span>
                        </div>
                        <h3>2. Add Your Organization</h3>
                        <p>Provide us with your organization information.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-icon">
                            <i class="bi bi-upload"></i> <!-- Placeholder for icon -->
                            <span>3</span>
                        </div>
                        <h3>3. Submit Your Projects</h3>
                        <p>Submit your CSR or Sustainability projects for cash prizes and recognition.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dynamic Content -->
    <?php
    $db = SIC_DB::get_instance();
    $user_id = isset($_SESSION['sic_user_id']) ? $_SESSION['sic_user_id'] : get_current_user_id();
    
    $org_profile = $db->get_organization_by_applicant_id( $user_id );
    $projects = $db->get_projects_by_applicant( $user_id );

    // If Organization exists (Complete or Draft but linked correctly to show "Home with Org" logic?)
    // The current logic only checked $org_profile. 
    // If $org_profile is true, we assume the user has a "dashboard".
    // If NOT, we fall back here.
    
    // However, user might have projects (e.g. started creation flow) but maybe org profile isn't "fully" set in a way that satisfaction the first check? 
    // OR simply the user wants the "No Organization" page to ALSO list projects if they exist (e.g. drafts).

    if ( $org_profile ) {
        get_template_part('dashboard/template-parts/home-with-organization');
    } elseif ( !empty($projects) ) {
        // Show Projects Table even if "No Organization" (Edge case or user request)
    ?>
    <section class="projects-section py-5">
        <div class="container">
            <h2 class="text-start mb-4 font-mackay text-cp-deep-ocean">My Project Submissions</h2>
            
             <div class="bg-white rounded-lg p-4 p-lg-5 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover custom-dashboard-table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">Project Name</th>
                                <th scope="col">Organization Name</th>
                                <th scope="col">Stage</th>
                                <th scope="col">Submission Date</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $projects as $project ): 
                                $status_class = 'status-draft';
                                $status_label = 'Draft';
                                if ( $project->submission_status === 'submitted' ) {
                                    $status_class = 'status-review';
                                    $status_label = 'Submitted';
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
                                            <li><a class="dropdown-item" href="<?php echo esc_url($edit_url); ?>">Edit</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                 <div class="mt-4">
                    <a href="<?php echo SIC_Routes::get_create_org_url(); ?>" class="btn-custom-primary">Complete Organization Profile</a>
                </div>
            </div>
        </div>
    </section>
    <?php 
    } else {
    ?>
    <!-- No Organization State -->
    <section class="no-org-state">
        <div class="container">
            <h2 class="text-start mb-5 font-mackay text-cp-deep-ocean">My Project Submissions</h2>
            <div class="no-org-content">
                <div class="no-org-icon">
                    <i class="bi bi-buildings"></i> <!-- Placeholder for big icon -->
                </div>
                <h3>No Organization</h3>
                <p>To submit your sustainability projects and compete for recognition, you'll need to add your organization details first.</p>
                <a href="<?php echo SIC_Routes::get_create_org_url(); ?>" class="btn-custom-primary">Add Your Organization</a>
            </div>
        </div>
    </section>
    <?php } ?>

</main>

<?php
get_dashboard_footer();
?>

