<?php
/* Template Name: Dashboard - My Organizations */

get_header('dashboard');
?>

<main id="primary" class="site-main bg-cp-cream-light py-5">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-lg-8">
                <h1 class="font-mackay fw-bold text-cp-deep-ocean mb-3">Your Organization, Your Impact.</h1>
                <p class="font-graphik text-cp-deep-ocean fs-5">
                    Register your organization and submit its CSR & Sustainability projects to showcase its role in shaping the future of the UAE. This is where your Sustainable Impact takes center stage.
                </p>
            </div>
        </div>

        <!-- Registered Organization Card -->
        <div class="bg-white rounded-lg p-5 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h2 class="font-graphik fw-medium text-cp-deep-ocean m-0">Registered Organization</h2>
            </div>

            <!-- No Organization State -->
            <div class="no-org-state pt-0 pb-5">
                <div class="no-org-content shadow-none p-0">
                    <div class="no-org-icon mx-auto mb-4">
                        <i class="bi bi-buildings fs-1 text-cp-aqua-marine"></i>
                    </div>
                    <h3 class="font-mackay fw-bold text-cp-deep-ocean mb-3">No Organization</h3>
                    <p class="font-graphik text-secondary mb-4 mx-auto" style="max-width: 450px;">
                        To submit your sustainability projects and compete for recognition, you'll need to add your organization details first.
                    </p>
                    <a href="<?php echo SIC_Routes::get_create_org_url(); ?>" class="btn-custom-primary">Add Your Organization</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
get_footer('dashboard');
?>
