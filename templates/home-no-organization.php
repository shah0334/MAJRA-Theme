<?php
/* Template Name: Dashboard - Home No Organization */

get_header('dashboard');
global $language;
?>

<main id="primary" style="padding-top: 69px;" class="site-main bg-cp-cream-light">

    <!-- Welcome Banner -->
    <section class="welcome-banner position-relative overflow-hidden py-5">
        <!-- Background Decoration -->
        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/home-no-org-bg.svg" alt="" class="position-absolute top-50 start-50 translate-middle" style="width: 120%; height: 120%; z-index: 0; pointer-events: none; object-fit: contain;">

        <div class="container position-relative z-1">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1><?php echo $language['DASHBOARD']['HOME_NO_ORG']['WELCOME']['TITLE_PREFIX']; ?> <span class="highlight"><?php echo $language['DASHBOARD']['HOME_NO_ORG']['WELCOME']['TITLE_HIGHLIGHT']; ?></span></h1>
                </div>
                <div class="col-lg-6">
                    <p><?php echo $language['DASHBOARD']['HOME_NO_ORG']['WELCOME']['TEXT']; ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Steps Section -->
    <section class="steps-section py-5 position-relative overflow-hidden">

        <div class="container position-relative z-1">
            <div class="text-center mb-5">
                <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-3 fs-1">How It Works</h2>
                <p class="font-graphik text-secondary fs-5">Follow these 3 simple steps to register and manage your projects effectively.</p>
            </div>
            
            <div class="row g-4">
                <!-- Step 1 -->
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-icon d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 64px; height: 64px; background-color: #FC9C63;">
                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.1667 24.5V22.1667C22.1667 20.929 21.675 19.742 20.7998 18.8668C19.9247 17.9917 18.7377 17.5 17.5 17.5H10.5C9.26232 17.5 8.07534 17.9917 7.20017 18.8668C6.325 19.742 5.83333 20.929 5.83333 22.1667V24.5" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 12.8333C16.5773 12.8333 18.6667 10.744 18.6667 8.16667C18.6667 5.58934 16.5773 3.5 14 3.5C11.4227 3.5 9.33333 5.58934 9.33333 8.16667C9.33333 10.744 11.4227 12.8333 14 12.8333Z" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <h3><?php echo $language['DASHBOARD']['HOME_NO_ORG']['STEPS']['STEP_1']['TITLE']; ?></h3>
                        <p><?php echo $language['DASHBOARD']['HOME_NO_ORG']['STEPS']['STEP_1']['TEXT']; ?></p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-icon d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 64px; height: 64px; background-color: #FC9C63;">
                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21.0003 2.33337H7.00033C5.71166 2.33337 4.66699 3.37804 4.66699 4.66671V23.3334C4.66699 24.622 5.71166 25.6667 7.00033 25.6667H21.0003C22.289 25.6667 23.3337 24.622 23.3337 23.3334V4.66671C23.3337 3.37804 22.289 2.33337 21.0003 2.33337Z" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10.5 25.6667V21H17.5V25.6667" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.33301 7H9.34467" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M18.667 7H18.6787" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 7H14.0117" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 11.6667H14.0117" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 16.3333H14.0117" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M18.667 11.6666H18.6787" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M18.667 16.3334H18.6787" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.33301 11.6666H9.34467" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.33301 16.3334H9.34467" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <h3><?php echo $language['DASHBOARD']['HOME_NO_ORG']['STEPS']['STEP_2']['TITLE']; ?></h3>
                        <p><?php echo $language['DASHBOARD']['HOME_NO_ORG']['STEPS']['STEP_2']['TEXT']; ?></p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="col-md-4">
                    <div class="step-card">
                        <div class="step-icon d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 64px; height: 64px; background-color: #FC9C63;">
                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24.5 17.5V22.1667C24.5 22.7855 24.2542 23.379 23.8166 23.8166C23.379 24.2542 22.7855 24.5 22.1667 24.5H5.83333C5.2145 24.5 4.621 24.2542 4.18342 23.8166C3.74583 23.379 3.5 22.7855 3.5 22.1667V17.5" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M19.8337 9.33333L14.0003 3.5L8.16699 9.33333" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 3.5V17.5" stroke="white" stroke-width="2.33333" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <h3><?php echo $language['DASHBOARD']['HOME_NO_ORG']['STEPS']['STEP_3']['TITLE']; ?></h3>
                        <p><?php echo $language['DASHBOARD']['HOME_NO_ORG']['STEPS']['STEP_3']['TEXT']; ?></p>
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
    
    if ( current_user_can('manage_options') ) {
        $data = $db->get_all_projects('', 1, 5);
    } else {
        $data = $db->get_projects_by_applicant( $user_id, '', 1, 5 );
    }
    $projects = $data['results'];

    // If Organization exists (Complete or Draft but linked correctly to show "Home with Org" logic?)
    // The current logic only checked $org_profile. 
    // If $org_profile is true, we assume the user has a "dashboard".
    // If NOT, we fall back here.
    
    // However, user might have projects (e.g. started creation flow) but maybe org profile isn't "fully" set in a way that satisfaction the first check? 
    // OR simply the user wants the "No Organization" page to ALSO list projects if they exist (e.g. drafts).

    if ( $org_profile ) {
        get_template_part('template-parts/dashboard/home-with-organization');
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
                                            <li><a class="dropdown-item" href="<?php echo SIC_Routes::get_view_project_url($project->project_id); ?>">View</a></li>
                                            <?php if ( !current_user_can('manage_options') && $project->submission_status !== 'submitted' ): ?>
                                            <li><a class="dropdown-item" href="<?php echo esc_url($edit_url); ?>">Edit</a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ( !current_user_can('manage_options') ): ?>
                <div class="mt-4">
                    <a href="<?php echo SIC_Routes::get_create_org_url(); ?>" class="btn-custom-primary">Complete Organization Profile</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php 
    } else {
    ?>
    <!-- No Organization State -->
    <!-- No Organization State -->
    <section class="no-org-state py-5">
        <div class="container">
            <div class="bg-white border rounded-5 shadow-sm p-5 text-center position-relative overflow-hidden" style="border-color: #f3f4f6 !important;">
                
                <h2 class="text-start position-absolute top-0 start-0 m-4 font-mackay fw-bold text-cp-deep-ocean fs-4">My Project Submissions</h2>

                <div class="d-flex flex-column align-items-center justify-content-center py-5">
                    
                    <!-- Icon Container -->
                    <div class="position-relative mb-4" style="width: 130px; height: 130px;">
                        <!-- Blur Background -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 rounded-circle" style="background: linear-gradient(135deg, rgba(59, 196, 189, 0.1) 0%, rgba(252, 156, 99, 0.1) 100%); filter: blur(24px);"></div>
                        <!-- Border/Tint Background -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 rounded-circle d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, rgba(59, 196, 189, 0.05) 0%, rgba(252, 156, 99, 0.05) 100%); border: 1px solid rgba(59, 196, 189, 0.1);">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/no-org-icon.svg" alt="" width="64" height="64">
                        </div>
                    </div>

                    <h3 class="font-mackay fw-bold text-cp-deep-ocean mb-3 fs-4">No Organization</h3>
                    <p class="font-graphik text-secondary mx-auto mb-4" style="font-size: 16px; max-width: 420px; color: #4a5565;">To submit your sustainability projects and compete for recognition, you'll need to add your organization details first.</p>

                    <?php if ( !current_user_can('manage_options') ): ?>
                    <a href="<?php echo SIC_Routes::get_create_org_url(); ?>" class="btn rounded-pill px-5 py-3 fw-semibold text-white shadow-sm font-graphik" style="background-color: #FC9C63; border: none; font-size: 16px; line-height: 24px;">Add Your Organization</a>
                    <?php else: ?>
                    <p class="text-secondary">Admin View: No organizations found.</p>
                    <?php endif; ?>
                
                </div>
            </div>
        </div>
    </section>
    <?php } ?>

</main>

<?php
get_footer('dashboard');
?>

