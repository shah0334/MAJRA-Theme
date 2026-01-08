<?php
/**
 * Step 4: Verification (Pinpoint)
 */
?>

<!-- Eligibility Banner -->
<div class="eligibility-banner p-4 mb-5" style="border-color: #FC9C63; background: linear-gradient(to bottom, #FFF5EC, #FFFFFF);">
     <p class="font-graphik text-cp-deep-ocean mb-0 fs-6">
        <strong>This section is optional for now, but will be required if your project is shortlisted.</strong><br>
        In addition to the challenge, Majra will review eligible projects for the Qualification Certificate or Verification Stamp. By submitting this evidence now, you help expedite the review of your project for these elite recognitions.
    </p>
</div>

<div class="row">
    <!-- Main Form Column -->
    <div class="col-lg-8">
        <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-3">Pin Project</h2>
        <p class="font-graphik text-secondary mb-5">Select the primary location of this project.</p>

        <form>
            <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                
                <!-- Search Bar -->
                <div class="input-group mb-3 border rounded-3 overflow-hidden" style="border-color: #D1D5DC;">
                     <span class="input-group-text bg-white border-0 ps-3">
                         <i class="bi bi-search text-secondary"></i>
                     </span>
                    <input type="text" class="form-control border-0 shadow-none ps-2" placeholder="Search for location" style="height: 50px;">
                    <button class="btn btn-light border-start px-4" type="button">Search</button>
                </div>

                <!-- Map Placeholder -->
                <div class="map-placeholder rounded-3 w-100" style="height: 400px; background-color: #F3F4F6;">
                     <!-- Real map would go here -->
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between pt-4 border-top">
                <a href="?step=3" class="btn btn-white border px-4 py-2 rounded-3 text-cp-deep-ocean fw-medium">Back</a>
                <a href="?step=5" class="btn btn-custom-aqua px-4 py-2 rounded-3 text-white fw-medium">Next</a>
            </div>

        </form>
    </div>

    <!-- Sidebar Column -->
    <div class="col-lg-4">
        <div class="guidance-panel-detail position-relative rounded-4 overflow-hidden shadow-sm p-4 h-100" style="background-color: #f7fafb;">
             <!-- Content -->
             <div class="position-relative z-1">
                <h3 class="font-mackay fw-bold text-cp-deep-ocean mb-3">Pin your project on the Sustainable Impact Map.</h3>
                <p class="font-graphik fw-bold text-cp-deep-ocean small mb-3">Fill in your project information.</p>
                <div class="font-graphik text-cp-deep-ocean small" style="line-height: 1.6;">
                    <p class="mb-3">Great, you’ve successfully submitted your project. Now, simply search for your project’s primary location in the search bar and select the preferred location.</p>
                </div>
             </div>
             
             <!-- Background Image Overlay (Bottom) -->
             <div class="position-absolute bottom-0 start-0 w-100" style="height: 40%;">
                 <img src="<?php echo get_template_directory_uri(); ?>/assets/images/map-sidebar-bg.png" alt="" class="w-100 h-100 object-fit-cover" style="opacity: 0.8; mix-blend-mode: overlay;">
                  <!-- Fallback gradient if image not present -->
                 <div style="width: 100%; height: 100%; background: linear-gradient(to top, rgba(0,0,0,0.2), transparent); position: absolute; top:0; left:0;"></div>
             </div>
        </div>
    </div>
</div>
