<?php
/* Template Name: Dashboard - Home No Organization */

get_header('dashboard');
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

</main>

<?php
get_footer('dashboard');
?>
