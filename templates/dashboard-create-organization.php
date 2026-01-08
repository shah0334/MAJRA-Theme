<?php
/* Template Name: Dashboard - Create Organization */

get_header('dashboard');
?>

<main id="primary" class="site-main bg-cp-cream-light py-5">
    <div class="container">
        <!-- Eligibility Banner -->
        <div class="eligibility-banner p-4 mb-5">
            <p class="font-graphik text-cp-deep-ocean mb-0 fs-6">
                Please ensure you have authorization from the organization being registered to share project details. Organization classification will remain confidential and will not appear in your project’s public listing within the Sustainable Impact Challenge. This information is used solely to help classify your organization in line with the criteria set by the UAE Ministry of Economy.
            </p>
        </div>

        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-12">
                <h1 class="font-mackay fw-bold text-cp-deep-ocean mb-0">Create Organization Profile</h1>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Form -->
            <div class="col-lg-8">
                <form>
                    <!-- Organization Details Section -->
                    <div class="bg-white rounded-lg p-5 shadow-sm mb-4">
                        <div class="row g-4">
                            <!-- Row 1 -->
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Organization name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg bg-light border-0 fs-6" value="Neutral Fuels LLC">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Trade License / Certificate Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg bg-light border-0 fs-6" value="652605">
                            </div>

                            <!-- Row 2 -->
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Logo <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="file" class="form-control form-control-lg bg-light border-0 fs-6 ps-3 pe-5">
                                    <i class="bi bi-upload position-absolute top-50 end-0 translate-middle-y me-3 text-secondary"></i>
                                </div>
                                <div class="form-text text-secondary mt-2">Upload your organization's logo in PNG or JPG (Max 2MB)</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Organization Website <span class="text-danger">*</span></label>
                                <input type="url" class="form-control form-control-lg bg-light border-0 fs-6" value="http://neutralfuels.com">
                            </div>

                            <!-- Row 3 -->
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Trade License / Certificate <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="file" class="form-control form-control-lg bg-light border-0 fs-6 ps-3 pe-5">
                                    <i class="bi bi-file-earmark-text position-absolute top-50 end-0 translate-middle-y me-3 text-secondary"></i>
                                </div>
                                <div class="form-text text-secondary mt-2">Upload a clear, valid trade license (PDF only, max 5MB)</div>
                                <div class="mt-2 text-cp-app-blue">
                                     <a href="#" class="text-decoration-none small">Neutral-Fuels-LLC-DXB-trade-license-2024-2025.pdf</a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Emirate of Registration <span class="text-danger">*</span></label>
                                <select class="form-select form-select-lg bg-light border-0 fs-6">
                                    <option selected>Dubai</option>
                                    <option value="Abu Dhabi">Abu Dhabi</option>
                                    <option value="Sharjah">Sharjah</option>
                                    <option value="Ajman">Ajman</option>
                                    <option value="Umm Al Quwain">Umm Al Quwain</option>
                                    <option value="Ras Al Khaimah">Ras Al Khaimah</option>
                                    <option value="Fujairah">Fujairah</option>
                                </select>
                            </div>

                            <!-- Row 4 -->
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Type of Legal Entity <span class="text-danger">*</span></label>
                                <select class="form-select form-select-lg bg-light border-0 fs-6">
                                    <option selected>Limited Liability Company</option>
                                    <option value="Sole Proprietorship">Sole Proprietorship</option>
                                    <option value="Partnership">Partnership</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Industry <span class="text-danger">*</span></label>
                                <select class="form-select form-select-lg bg-light border-0 fs-6">
                                    <option selected>Select the primary industry sector</option>
                                    <option value="Energy">Energy</option>
                                    <option value="Technology">Technology</option>
                                    <option value="Manufacturing">Manufacturing</option>
                                </select>
                            </div>

                            <!-- Row 5 -->
                             <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Is your organization registered in a Freezone? <span class="text-danger">*</span></label>
                                <select class="form-select form-select-lg bg-light border-0 fs-6">
                                    <option selected>No</option>
                                    <option value="Yes">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Classification Profile & CSR Declaration -->
                    <div class="bg-white rounded-lg p-5 shadow-sm mb-4">
                        <h2 class="font-graphik fw-bold text-cp-deep-ocean mb-2 fs-4">Classification Profile <span class="fw-light text-secondary fs-6">(For Reporting Purposes Only)</span></h2>
                        
                        <div class="row g-4 mt-2">
                            <!-- Row 1 -->
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Type of business activity <span class="text-danger">*</span></label>
                                <select class="form-select form-select-lg bg-light border-0 fs-6">
                                    <option selected>Industry</option>
                                    <option value="Service">Service</option>
                                    <option value="Trading">Trading</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Number of employees <span class="text-danger">*</span></label>
                                 <input type="number" class="form-control form-control-lg bg-light border-0 fs-6" value="32">
                            </div>
                            
                            <!-- Row 2 -->
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Annual turnover <span class="text-danger">*</span></label>
                                 <select class="form-select form-select-lg bg-light border-0 fs-6">
                                    <option selected>< AED 50 million</option>
                                    <option value="AED 50m - 100m">AED 50m - 100m</option>
                                    <option value="> AED 100m">> AED 100m</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-5">
                            <h2 class="font-graphik fw-bold text-cp-deep-ocean mb-3 fs-4">CSR Declaration</h2>
                            <p class="font-graphik text-secondary small mb-4">
                                This information is collected for classification and reporting purposes only and will not appear publicly.
                            </p>

                            <div class="mb-4">
                                 <label class="form-label font-graphik fw-medium text-cp-deep-ocean d-block mb-3">Has your organization implemented any CSR activities or programs in the UAE? <span class="text-danger">*</span></label>
                                 <div class="d-flex gap-4">
                                     <div class="form-check">
                                        <input class="form-check-input" type="radio" name="csrActivities" id="csrYes" checked>
                                        <label class="form-check-label font-graphik" for="csrYes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="csrActivities" id="csrNo">
                                        <label class="form-check-label font-graphik" for="csrNo">No</label>
                                    </div>
                                 </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Project / Program name</label>
                                    <input type="text" class="form-control form-control-lg bg-light border-0 fs-6">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label font-graphik fw-medium text-cp-deep-ocean">Allocated monetary amount (AED)</label>
                                    <input type="text" class="form-control form-control-lg bg-light border-0 fs-6">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="mb-5">
                         <button type="submit" class="btn btn-custom-aqua w-auto px-5 py-3 rounded-pill fw-bold text-white fs-6">Create</button>
                    </div>
                </form>
            </div>

            <!-- Right Column: Guidance Panel -->
            <div class="col-lg-4">
                <div class="guidance-panel bg-cp-sandstone border border-cp-coral-sunset rounded-3 p-4 position-relative">
                    <div class="guidance-icon-container bg-cp-coral-sunset shadow-sm d-flex align-items-center justify-content-center rounded-2 position-absolute">
                        <i class="bi bi-info-circle text-white fs-5"></i>
                    </div>
                    <div class="guidance-content pt-2 ps-4 ms-2">
                        <p class="font-graphik text-cp-deep-ocean mb-0 small lh-base">
                            Your organization represents the foundation for meaningful change; therefore, it is essential to ensure accuracy from the outset. Please carefully review all details before submitting, as submissions cannot be edited once finalized.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
get_footer('dashboard');
?>
