<?php
/* Template Name: Dashboard - Create Organization */

// Load language before form processing
global $language;

// Form Handling Logic
$error_msg = '';
$success_msg = '';

if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'sic_create_org' ) {
    error_log('SIC DEBUG: create org post received. POST: ' . print_r($_POST, true));
    
    if ( ! isset($_SESSION['sic_user_id']) ) {
        error_log('SIC DEBUG: User not logged in (session)');
        wp_redirect( SIC_Routes::get_login_url() );
        exit;
    }

    $db = SIC_DB::get_instance();
    $storage = SIC_Storage::get_instance();
    $user_id = $_SESSION['sic_user_id'];
    $cycle_id = $db->get_active_cycle_id();

    // 1. Handle File Uploads
    $logo_id = null;
    $license_id = null;
    $max_logo_size = 2 * 1024 * 1024; // 2MB
    $max_license_size = 5 * 1024 * 1024; // 5MB

    if ( !empty($_FILES['org_logo']['name']) ) {
        if ($_FILES['org_logo']['size'] > $max_logo_size) {
            $error_msg = $language['DASHBOARD']['ORG_FORM']['ERR_LOGO_SIZE'];
        } else {
            $upload = $storage->upload_file($_FILES['org_logo'], 'org-logos');
            if ( !is_wp_error($upload) ) {
                $logo_id = $db->save_file($upload, $cycle_id, $user_id);
            } else {
                $error_msg = $upload->get_error_message();
            }
        }
    }

    if ( empty($error_msg) && !empty($_FILES['org_license_file']['name']) ) {
        if ($_FILES['org_license_file']['size'] > $max_license_size) {
            $error_msg = $language['DASHBOARD']['ORG_FORM']['ERR_LICENSE_SIZE'];
        } else {
            $upload = $storage->upload_file($_FILES['org_license_file'], 'org-licenses');
            if ( !is_wp_error($upload) ) {
                $license_id = $db->save_file($upload, $cycle_id, $user_id);
            } else {
                $error_msg = $upload->get_error_message();
            }
        }
    }

    // Abort if file validation failed
    if ( !empty($error_msg) ) {
        error_log('SIC DEBUG: File validation failed: ' . $error_msg);
        // Do nothing, fall through to display error
    } else {
        // 2. Prepare Data
        $org_data = [
            'organization_name'    => sanitize_text_field($_POST['org_name']),
            'trade_license_number' => sanitize_text_field($_POST['org_license_number']),
            'website_url'          => esc_url_raw($_POST['org_website']),
            'emirate'              => sanitize_text_field($_POST['org_emirate']),
            'entity_type'          => sanitize_text_field($_POST['org_entity_type']),
            'industry'             => sanitize_text_field($_POST['org_industry']),
            'is_freezone'          => sanitize_text_field($_POST['org_is_freezone']),
            'business_activity'    => sanitize_text_field($_POST['org_activity_type']),
            'employees'            => intval($_POST['org_employees']),
            'turnover'             => sanitize_text_field($_POST['org_turnover']),
            'contact_phone'        => sanitize_text_field($_POST['contact_phone']),
            'iban_number'          => sanitize_text_field($_POST['iban_number']),
            'csr_activity'         => $_POST['csr_activity'] ?? 'no',
            'csr_initiatives'      => []
        ];

        // Validation: Check negative values
        if ($org_data['employees'] < 0) {
             // We need to fetch is_rtl or define it, but for now assuming global logic or hardcoded
             // The previous code used $is_rtl which might be undefined if not set in this scope?
             // Let's use English/Arabic fallback if available or just hardcode english for safety if unsure
             global $language; 
             // Correction: $is_rtl logic was probably somewhere else or implicit. 
             // I'll stick to the string I saw before:
             $error_msg = 'Error: Number of employees cannot be negative.'; 
        }

        error_log('SIC DEBUG: Org Data prepared: ' . print_r($org_data, true));

    if ( empty($error_msg) ) {
        if ( !empty($_POST['csr_name']) ) {
            $org_data['csr_initiatives'][] = [
                'name'   => sanitize_text_field($_POST['csr_name']),
                'amount' => floatval($_POST['csr_amount'])
            ];
        }

        // 3. Save to DB
        error_log('SIC DEBUG: Calling create_organization...');
        $result = $db->create_organization( $user_id, $org_data, [
            'logo_id' => $logo_id,
            'license_id' => $license_id
        ]);
        error_log('SIC DEBUG: create_organization result: ' . (is_wp_error($result) ? $result->get_error_message() : $result));
    
        if ( is_wp_error($result) ) {
            $error_msg = $result->get_error_message();
        } else {
            // Success
            wp_redirect( SIC_Routes::get_dashboard_home_url() . '?success=org_created' ); // Redirect to dashboard with success param
            exit;
        }
    }
    } // End else from file validation check
}

get_header('dashboard');
global $language;

// Restrict Admin Access
if ( current_user_can('manage_options') ) {
    wp_safe_redirect( SIC_Routes::get_dashboard_home_url() );
    exit;
}
?>

<main id="primary" class="site-main bg-cp-cream-light py-5">
    <div class="container">
        <!-- Eligibility Banner -->
        <div class="eligibility-banner p-4 mb-5">
            <p class="font-graphik text-cp-deep-ocean mb-0 fs-6">
                <?php echo $language['DASHBOARD']['ORG_FORM']['BANNER_TEXT']; ?>
            </p>
        </div>

        <?php if ($error_msg): ?>
            <!-- Also trigger toast for visibility -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof showToast === 'function') {
                        showToast("<?php echo esc_js($error_msg); ?>", 'error');
                    }
                });
            </script>
            <div class="alert alert-danger mb-4"><?php echo esc_html($error_msg); ?></div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="row mb-5">
            <div class="col-12">
                <h1 class="font-mackay fw-bold text-cp-deep-ocean mb-0"><?php echo $language['DASHBOARD']['ORG_FORM']['PAGE_TITLE']; ?></h1>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Form -->
            <div class="col-lg-8">
                <form method="POST" enctype="multipart/form-data" action="<?php echo esc_url(SIC_Routes::get_create_org_url()); ?>" id="createOrgForm">
                    <input type="hidden" name="action" value="sic_create_org">
                    
                    <!-- Organization Details Section -->
                    <div class="bg-white rounded-lg p-5 shadow-sm mb-4">
                        <div class="row g-4">
                            <!-- Row 1 -->
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['ORG_NAME']; ?> <span class="text-danger">*</span></label>
                                <input type="text" name="org_name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['TRADE_LICENSE_NUM']; ?> <span class="text-danger">*</span></label>
                                <input type="text" name="org_license_number" class="form-control" required>
                            </div>

                            <!-- Row 2 -->
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['LOGO']; ?> <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="file" name="org_logo" id="org_logo" class="form-control ps-3 pe-5" accept="image/png, image/jpeg" required>
                                    <i class="bi bi-upload position-absolute top-50 end-0 translate-middle-y me-3 text-secondary pe-none"></i>
                                </div>
                                <div id="org_logo_preview" class="mt-2"></div>
                                <div class="form-text text-secondary mt-2"><?php echo $language['DASHBOARD']['ORG_FORM']['LOGO_HELP']; ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['WEBSITE']; ?> <span class="text-danger">*</span></label>
                                <input type="url" name="org_website" class="form-control" required>
                            </div>

                            <!-- Row 3: Contact & IBAN -->
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['CONTACT_NUM']; ?> <span class="text-danger">*</span></label>
                                <input type="text" name="contact_phone" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['IBAN_NUM']; ?> <span class="text-danger">*</span></label>
                                <input type="text" name="iban_number" class="form-control" required>
                            </div>

                            <!-- Row 4 -->
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['TRADE_LICENSE_FILE']; ?> <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="file" name="org_license_file" id="org_license_file" class="form-control ps-3 pe-5" accept="application/pdf" required>
                                    <i class="bi bi-file-earmark-text position-absolute top-50 end-0 translate-middle-y me-3 text-secondary pe-none"></i>
                                </div>
                                <div id="org_license_preview" class="mt-2"></div>
                                <div class="form-text text-secondary mt-2"><?php echo $language['DASHBOARD']['ORG_FORM']['TRADE_LICENSE_HELP']; ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['EMIRATE']; ?> <span class="text-danger">*</span></label>
                                <select name="org_emirate" class="form-select" required>
                                    <option value="" disabled selected><?php echo $language['DASHBOARD']['ORG_FORM']['SELECT_EMIRATE']; ?></option>
                                    <option value="Dubai">Dubai</option>
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
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['ENTITY_TYPE']; ?> <span class="text-danger">*</span></label>
                                <select name="org_entity_type" class="form-select" required>
                                    <option value="" disabled selected><?php echo $language['DASHBOARD']['ORG_FORM']['SELECT_ENTITY']; ?></option>
                                    <option value="Limited Liability Company">Limited Liability Company</option>
                                    <option value="Sole Proprietorship">Sole Proprietorship</option>
                                    <option value="Partnership">Partnership</option>
                                    <option value="Public Joint Stock Company">Public Joint Stock Company</option>
                                    <option value="Private Joint Stock Company">Private Joint Stock Company</option>
                                    <option value="Branch">Branch of Foreign/Local Company</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['INDUSTRY']; ?> <span class="text-danger">*</span></label>
                                <select name="org_industry" class="form-select" required>
                                    <option value="" disabled selected><?php echo $language['DASHBOARD']['ORG_FORM']['SELECT_INDUSTRY']; ?></option>
                                    <option value="Energy">Energy</option>
                                    <option value="Technology">Technology</option>
                                    <option value="Manufacturing">Manufacturing</option>
                                    <option value="Healthcare">Healthcare</option>
                                    <option value="Education">Education</option>
                                    <option value="Retail">Retail</option>
                                    <option value="Real Estate">Real Estate</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <!-- Row 5 -->
                             <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['IS_FREEZONE']; ?> <span class="text-danger">*</span></label>
                                <select name="org_is_freezone" class="form-select" required>
                                    <option value="" disabled selected>Select...</option>
                                    <option value="No"><?php echo $language['DASHBOARD']['ORG_FORM']['NO']; ?></option>
                                    <option value="Yes"><?php echo $language['DASHBOARD']['ORG_FORM']['YES']; ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Classification Profile & CSR Declaration -->
                    <div class="bg-white rounded-lg p-5 shadow-sm mb-4">
                        <h2 class="font-graphik fw-bold text-cp-deep-ocean mb-2 fs-4"><?php echo $language['DASHBOARD']['ORG_FORM']['CLASSIFICATION_PROFILE']; ?> <span class="fw-light text-secondary fs-6"><?php echo $language['DASHBOARD']['ORG_FORM']['FOR_REPORTING_ONLY']; ?></span></h2>
                        
                        <div class="row g-4 mt-2">
                            <!-- Row 1 -->
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['ACTIVITY_TYPE']; ?> <span class="text-danger">*</span></label>
                                <select name="org_activity_type" class="form-select" required>
                                    <option value="" disabled selected><?php echo $language['DASHBOARD']['ORG_FORM']['SELECT_ACTIVITY']; ?></option>
                                    <option value="Industry">Industry</option>
                                    <option value="Service">Service</option>
                                    <option value="Trading">Trading</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['EMPLOYEES']; ?> <span class="text-danger">*</span></label>
                                 <input type="number" min="0" name="org_employees" class="form-control" required>
                            </div>
                            
                            <!-- Row 2 -->
                            <div class="col-md-6">
                                <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['TURNOVER']; ?> <span class="text-danger">*</span></label>
                                 <select name="org_turnover" class="form-select" required>
                                    <option value="" disabled selected><?php echo $language['DASHBOARD']['ORG_FORM']['SELECT_TURNOVER']; ?></option>
                                    <option value="< AED 50 million">< AED 50 million</option>
                                    <option value="AED 50m - 100m">AED 50m - 100m</option>
                                    <option value="> AED 100m">> AED 100m</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-5">
                            <h2 class="font-graphik fw-bold text-cp-deep-ocean mb-3 fs-4"><?php echo $language['DASHBOARD']['ORG_FORM']['CSR_DECLARATION']; ?></h2>
                            <p class="font-graphik text-secondary small mb-4">
                                <?php echo $language['DASHBOARD']['ORG_FORM']['CSR_DECLARATION_TEXT']; ?>
                            </p>

                            <div class="mb-4">
                                 <label class="form-label font-graphik fw-medium text-cp-deep-ocean d-block mb-3"><?php echo $language['DASHBOARD']['ORG_FORM']['CSR_QUESTION']; ?> <span class="text-danger">*</span></label>
                                 <div class="d-flex gap-4">
                                     <div class="form-check">
                                        <input class="form-check-input" type="radio" name="csr_activity" id="csrYes" value="yes" checked>
                                        <label class="form-check-label font-graphik" for="csrYes"><?php echo $language['DASHBOARD']['ORG_FORM']['YES']; ?></label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="csr_activity" id="csrNo" value="no">
                                        <label class="form-check-label font-graphik" for="csrNo"><?php echo $language['DASHBOARD']['ORG_FORM']['NO']; ?></label>
                                    </div>
                                 </div>
                            </div>

                            <div class="row g-4" id="csr-fields">
                                <div class="col-md-6">
                                    <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['PROJ_NAME']; ?></label>
                                    <input type="text" name="csr_name" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label font-graphik fw-medium text-cp-deep-ocean"><?php echo $language['DASHBOARD']['ORG_FORM']['AMOUNT']; ?></label>
                                    <input type="number" name="csr_amount" class="form-control" step="0.01">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="mb-5">
                         <button type="submit" class="btn btn-custom-aqua w-auto px-5 py-3 rounded-pill fw-bold text-white fs-6"><?php echo $language['DASHBOARD']['ORG_FORM']['CREATE_BTN']; ?></button>
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
                            <?php echo $language['DASHBOARD']['ORG_FORM']['GUIDANCE_TEXT']; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createOrgForm');
    
    // File Preview Logic
    function handleFilePreview(inputId, previewId, isImage = true, maxSizeMB = 5, errorMsg = '') {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        
        if (!input || !preview) return;

        input.addEventListener('change', function() {
            const file = this.files[0];
            preview.innerHTML = ''; // Clear previous preview

            if (file) {
                // Size Validation
                let limit = maxSizeMB;
                // Force 2MB for logo if somehow the argument wasn't passed correctly
                if (inputId === 'org_logo') {
                    limit = 2;
                }

                const maxSize = limit * 1024 * 1024;
                if (file.size > maxSize) {
                    const msg = errorMsg || 'Error: File size exceeds ' + limit + 'MB limit. Please upload a smaller file.';
                    preview.innerHTML = '<span class="text-danger small">' + msg + '</span>';
                    
                    if (typeof showToast === 'function') {
                        showToast(msg, 'error');
                    }
                    
                    this.value = ''; // Clear input
                    return;
                }

                if (isImage) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-thumbnail';
                            img.style.maxHeight = '150px';
                            preview.appendChild(img);
                        }
                        reader.readAsDataURL(file);
                    } else {
                        preview.innerHTML = '<span class="text-danger"><?php echo $language['DASHBOARD']['ORG_FORM']['ERR_INVALID_IMG']; ?></span>';
                    }
                } else {
                    // For non-image files (PDFs), show name
                     const fileInfo = document.createElement('div');
                     fileInfo.className = 'd-flex align-items-center p-2 border rounded bg-light';
                     fileInfo.innerHTML = '<i class="bi bi-file-earmark-pdf fs-4 text-danger me-2"></i> <span class="text-truncate">' + file.name + '</span>';
                     preview.appendChild(fileInfo);
                }
            }
        });
    }

    handleFilePreview('org_logo', 'org_logo_preview', true, 2, '<?php echo $language['DASHBOARD']['ORG_FORM']['ERR_LOGO_SIZE']; ?>'); // 2MB for Logo
    handleFilePreview('org_license_file', 'org_license_preview', false, 5, '<?php echo $language['DASHBOARD']['ORG_FORM']['ERR_LICENSE_SIZE']; ?>'); // 5MB for License


    if (form) {
        form.addEventListener('submit', function(e) {
            // If we get here, validation passed or novalidate is on.
            // console.log('Form submitting...');
        });

        // Catch invalid fields
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('invalid', function() {
                input.classList.add('is-invalid'); // Add bootstrap error class
            });
            input.addEventListener('input', function() {
                input.classList.remove('is-invalid');
            });
        });
    }

    // CSR Conditional Visibility
    const csrYes = document.getElementById('csrYes');
    const csrNo = document.getElementById('csrNo');
    const csrFields = document.getElementById('csr-fields');
    
    function toggleCsrFields() {
        if (!csrYes || !csrNo || !csrFields) return;
        
        if (csrYes.checked) {
            csrFields.style.display = 'flex'; // Restore row flex display
            // Optional: make them required if shown? User request didn't specify, but implies data is expected if "Yes".
            // Leaving as optional per current PHP logic unless specified.
        } else {
            csrFields.style.display = 'none';
            // Clear values if hidden?
             const inputs = csrFields.querySelectorAll('input');
             inputs.forEach(input => input.value = '');
        }
    }

    // Initialize
    toggleCsrFields();

    // Add listeners
    if (csrYes && csrNo) {
        csrYes.addEventListener('change', toggleCsrFields);
        csrNo.addEventListener('change', toggleCsrFields);
    }

    // Negative Number Validation (Employees)
    const employeeInput = document.querySelector('input[name="org_employees"]');
    if (employeeInput) {
        employeeInput.addEventListener('input', function() {
            const val = parseInt(this.value);
            if (val < 0) {
                 const isRTL = document.body.classList.contains('rtl');
                 const msg = isRTL ? 'القيمة لا يمكن أن تكون سلبية' : 'Value cannot be negative';
                 this.setCustomValidity(msg);
                 
                 // Visual feedback
                 // Check if error div exists, if not create little helper? 
                 // Or just rely on standard validation bubble. Let's add red border.
                 this.classList.add('is-invalid');
                 
                 // Optional: Add helper text if not present
                 let errorDiv = this.parentElement.querySelector('.invalid-feedback');
                 if (!errorDiv) {
                     errorDiv = document.createElement('div');
                     errorDiv.className = 'invalid-feedback';
                     this.parentElement.appendChild(errorDiv);
                 }
                 errorDiv.textContent = msg;
            } else {
                 this.setCustomValidity('');
                 this.classList.remove('is-invalid');
            }
        });
    }
});
</script>

<?php
get_footer('dashboard');
?>
