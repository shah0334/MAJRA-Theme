<?php
/**
 * Step 1: Project Profile
 */
?>

<!-- Eligibility Banner -->
<div class="eligibility-banner p-4 mb-5">
    <p class="font-graphik text-cp-deep-ocean mb-0 fs-6">
        <strong>Only CSR and Sustainability initiatives, programs, events, and projects executed in the United Arab Emirates are eligible.</strong><br>
        All projects submitted to the Sustainable Impact Challenge will be reviewed by Majra for the Qualification Certificate or Verification Stamp, if eligible, which adds to your project’s credibility and opens new opportunities.
    </p>
</div>

<div class="row">
    <!-- Main Form Column -->
    <div class="col-lg-8">
        <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-4">Project Profile</h2>

        <form>
            <!-- Select Organization -->
            <div class="mb-4">
                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Select Organization <span class="text-danger">*</span></label>
                <div class="d-flex gap-3">
                    <select class="form-select form-select-lg bg-light border-0 fs-6 flex-grow-1">
                        <option selected disabled>Select the Organization for your project</option>
                        <option value="1">Neutral Fuels LLC</option>
                    </select>
                    <button type="button" class="btn btn-outline-info text-nowrap px-4" style="border-color: #3bc4bd; color: #3bc4bd;">
                        Add Organization / إضافة مؤسسة
                    </button>
                </div>
            </div>

            <!-- Project Name -->
            <div class="mb-4">
                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Project Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control form-control-lg bg-light border-0 fs-6" placeholder="Enter the official name of your project">
            </div>

            <!-- Project Status -->
            <div class="mb-4">
                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Project Status <span class="text-danger">*</span></label>
                <select class="form-select form-select-lg bg-light border-0 fs-6">
                    <option selected disabled>Select the current implementation stage of your project</option>
                    <option value="Planned">Planned</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <!-- Project Description -->
            <div class="mb-4">
                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Project Description <span class="text-danger">*</span></label>
                <textarea class="form-control form-control-lg bg-light border-0 fs-6" rows="5" placeholder="Provide a brief overview of what your project aims to achieve and describe the positive impact it can give (maximum 5000 characters)"></textarea>
            </div>

            <!-- Start/End Date -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Project Start Date <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="date" class="form-control form-control-lg bg-light border-0 fs-6">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Project End Date <span class="text-danger">*</span></label>
                    <div class="position-relative">
                        <input type="date" class="form-control form-control-lg bg-light border-0 fs-6">
                    </div>
                </div>
            </div>

            <!-- Project Profile Image -->
            <div class="mb-5">
                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Project Profile Image (Public) <span class="text-danger">*</span></label>
                <div class="position-relative">
                    <input type="file" class="form-control form-control-lg bg-light border-0 fs-6 ps-3 pe-5" style="padding-top: 1rem; padding-bottom: 1rem;">
                    <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary" style="pointer-events: none;">
                        Upload an image for the project's public listing.
                    </span>
                    <i class="bi bi-upload position-absolute top-50 end-0 translate-middle-y me-3 text-secondary"></i>
                </div>
            </div>
            
            <div class="text-end mb-5">
                 <button type="button" class="btn btn-custom-aqua w-auto px-5 py-3 rounded-pill fw-bold text-white fs-6">Save & Continue</button>
            </div>

        </form>
        
        <!-- Navigation Buttons -->
        <div class="d-flex justify-content-between pt-4 border-top">
            <a href="#" class="btn btn-white border px-4 py-2 rounded-3 text-cp-deep-ocean fw-medium">Back</a>
            <a href="?step=2" class="btn btn-custom-aqua px-4 py-2 rounded-3 text-white fw-medium">Next</a>
        </div>
    </div>

    <!-- Sidebar Column -->
    <div class="col-lg-4">
        <div class="guidance-panel-detail position-relative rounded-4 overflow-hidden shadow-sm p-4 h-100" style="background-color: #f7fafb;">
             <!-- Content -->
             <div class="position-relative z-1">
                <h3 class="font-mackay fw-bold text-cp-deep-ocean mb-3">Let’s get into the details.</h3>
                <p class="font-graphik fw-medium text-cp-deep-ocean mb-4">Tell us about your project and its impact to date.</p>
                <div class="font-graphik text-cp-deep-ocean small" style="line-height: 1.6;">
                    <p class="mb-3">This section is your opportunity to describe your project, its objectives, and the results achieved so far. Please ensure all responses are accurate and verifiable, as shortlisted projects will be required to submit supporting evidence for the Sustainable Impact Award.</p>
                    <p>Shortlisted projects will also be shared nationally for public review and voting across the CSR and sustainability categories. Ensure that all information and materials submitted reflect your organization and project in the most credible professional manner.</p>
                </div>
             </div>
             
             <!-- Background Image Overlay (Placeholder/Gradient for now) -->
             <div class="position-absolute bottom-0 start-0 w-100 h-50" style="background: linear-gradient(to top, rgba(59, 196, 189, 0.2), transparent); pointer-events: none;"></div>
        </div>
    </div>
</div>
