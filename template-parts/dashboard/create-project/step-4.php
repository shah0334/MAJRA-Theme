<?php
/**
 * Step 4: Verification (Pinpoint)
 */
$db = SIC_DB::get_instance();
global $language;
$project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

if ( ! $project_id ) {
    wp_redirect( SIC_Routes::get_create_project_url() );
    exit;
}

$project = $db->get_project($project_id);
if ( ! $project ) {
    echo '<div class="alert alert-danger">Project not found.</div>';
    return;
}


// Handle Form Submission
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sic_project_action']) ) {
    // Verify nonce
    if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'sic_save_step_4' ) ) {
        echo '<div class="alert alert-danger">Security check failed.</div>';
        return;
    }

    $submission_data = [
        'location_search_text' => sanitize_text_field($_POST['location_search_text']),
        'location_address'     => sanitize_text_field($_POST['location_address']),
        'pinpoint_completed'   => 1
    ];

    // Optional: dummy lat/long if provided by a map later
    if ( !empty($_POST['latitude']) ) $submission_data['latitude'] = floatval($_POST['latitude']);
    if ( !empty($_POST['longitude']) ) $submission_data['longitude'] = floatval($_POST['longitude']);
    if ( !empty($_POST['location_place_id']) ) $submission_data['location_place_id'] = sanitize_text_field($_POST['location_place_id']);

    $db->update_project($project_id, $submission_data);
    
    // Redirect to Step 5
    wp_redirect( add_query_arg(['step' => 5, 'project_id' => $project_id], SIC_Routes::get_create_project_url()) );
    exit;
}
?>

<!-- Eligibility Banner -->
<div class="eligibility-banner p-4 mb-5" style="border-color: #FC9C63; background: linear-gradient(to bottom, #FFF5EC, #FFFFFF);">
     <p class="font-graphik text-cp-deep-ocean mb-0 fs-6">
        <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_4']['ELIGIBILITY_OPTIONAL_TEXT']; ?>
    </p>
</div>

<div class="row">
    <!-- Main Form Column -->
    <div class="col-lg-8">
        <h2 class="font-mackay fw-bold text-cp-deep-ocean mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_4']['TITLE']; ?></h2>
        <p class="font-graphik text-secondary mb-5"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_4']['SUBTITLE']; ?></p>

        <form method="POST">
            <?php wp_nonce_field( 'sic_save_step_4' ); ?>
            <input type="hidden" name="sic_project_action" value="save_step_4">
            
            <div class="bg-white rounded-4 p-4 shadow-sm mb-4">
                
                <!-- Search Bar -->
                <div class="mb-4">
                    <label class="form-label font-graphik fw-bold text-cp-deep-ocean mb-1 d-none"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_4']['SEARCH_LABEL']; ?></label>
                    <div class="input-group mb-3 border rounded-3 overflow-hidden p-1 bg-white" style="border-color: #D1D5DC;">
                        <span class="input-group-text bg-white border-0 ps-3 pe-2">
                             <i class="bi bi-search text-secondary"></i>
                        </span>
                        <input type="text" id="location-search" name="location_search_text" class="form-control border-0 shadow-none ps-2" placeholder="<?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_4']['SEARCH_PLACEHOLDER']; ?>" style="height: 48px;" value="<?php echo esc_attr($project->location_search_text); ?>">
                         <button type="button" class="btn btn-secondary px-4 rounded-3 text-white" style="background-color: #D1D5DB; border: none; margin: 4px;">Search</button>
                    </div>
                </div>

                <!-- Manual Address Input (Hidden as per design) -->
                <div class="mb-3 d-none">
                    <label class="form-label font-graphik fw-bold text-cp-deep-ocean mb-1"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_4']['ADDRESS_LABEL']; ?></label>
                    <input type="text" id="location-address" name="location_address" class="form-control bg-light border-0 fs-6" placeholder="<?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_4']['ADDRESS_PLACEHOLDER']; ?>" value="<?php echo esc_attr($project->location_address); ?>">
                </div>

                <!-- Map Container -->
                <div id="project-map" class="rounded-3 w-100 mb-3" style="height: 442px; background-color: #F3F4F6;"></div>
                
                <p class="font-graphik small text-secondary"><i class="bi bi-info-circle me-1"></i> <?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_4']['MAP_INFO']; ?></p>

                <!-- Hidden inputs -->
                <input type="hidden" id="latitude" name="latitude" value="<?php echo esc_attr($project->latitude); ?>">
                <input type="hidden" id="longitude" name="longitude" value="<?php echo esc_attr($project->longitude); ?>">
                <input type="hidden" id="location_place_id" name="location_place_id" value="<?php echo esc_attr($project->location_place_id); ?>">

            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-between pt-4 border-top">
                <a href="<?php echo add_query_arg(['step' => 3, 'project_id' => $project_id], SIC_Routes::get_create_project_url()); ?>" class="btn btn-white border px-4 py-2 rounded-3 text-cp-deep-ocean fw-medium"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_4']['BACK_BTN']; ?></a>
                <button type="submit" class="btn btn-custom-aqua px-4 py-2 rounded-3 text-white fw-medium"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_4']['NEXT_BTN']; ?></button>
            </div>

        </form>
    </div>

    <!-- Sidebar Column -->
    <div class="col-lg-4">
        <div class="guidance-panel-detail position-relative rounded-4 overflow-hidden shadow-sm p-4 h-100" style="background-color: #f7fafb;">
             <!-- Content -->
             <div class="position-relative z-1">
                <h3 class="font-mackay fw-bold text-cp-deep-ocean mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_4']['SIDEBAR_TITLE']; ?></h3>
                <p class="font-graphik fw-bold text-cp-deep-ocean small mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_4']['SIDEBAR_SUBTITLE']; ?></p>
                <div class="font-graphik text-cp-deep-ocean small" style="line-height: 1.6;">
                    <p class="mb-3"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_4']['SIDEBAR_TEXT']; ?></p>
                </div>
             </div>
             
             <!-- Background Image Overlay -->
             <div class="position-absolute bottom-0 start-0 w-100 h-50" style="background: linear-gradient(to top, #f7fafb 10%, transparent 100%); z-index: 1; pointer-events: none;"></div>
             <img src="<?php echo get_template_directory_uri(); ?>/assets/img/step-4-sidebar-bg.png" alt="" class="position-absolute bottom-0 start-0 w-100" style="height: 60%; object-fit: cover; z-index: 0; opacity: 1;">
        </div>
    </div>
</div>

<script>
function initMap() {
    if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
        console.error("Google Maps API is not loaded.");
        document.getElementById("project-map").innerHTML = '<div class="d-flex align-items-center justify-content-center h-100 text-danger"><?php echo $language['DASHBOARD']['PROJ_WIZARD']['STEP_4']['MAP_ERROR']; ?></div>';
        return;
    }

    const defaultLat = 25.2048; // Dubai default
    const defaultLng = 55.2708;

    const savedLat = parseFloat(document.getElementById('latitude').value) || defaultLat;
    const savedLng = parseFloat(document.getElementById('longitude').value) || defaultLng;

    const mapOptions = {
        center: { lat: savedLat, lng: savedLng },
        zoom: 13,
    };

    const map = new google.maps.Map(document.getElementById("project-map"), mapOptions);

    const marker = new google.maps.Marker({
        position: { lat: savedLat, lng: savedLng },
        map: map,
        draggable: true,
        title: "Project Location"
    });

    // Autocomplete
    const input = document.getElementById("location-search");
    const autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo("bounds", map);

    autocomplete.addListener("place_changed", () => {
        const place = autocomplete.getPlace();

        if (!place.geometry || !place.geometry.location) {
            return;
        }

        // Update Map
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }

        // Update Marker
        marker.setPosition(place.geometry.location);

        // Update Fields
        updateHiddenFields(place.geometry.location.lat(), place.geometry.location.lng(), place.place_id, place.formatted_address);
    });

    // Marker Drag Event
    marker.addListener("dragend", () => {
        const position = marker.getPosition();
        updateHiddenFields(position.lat(), position.lng(), '', '');
        // Reverse geocoding could be added here to fetch address on drag
    });

    function updateHiddenFields(lat, lng, placeId, address) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        
        if(placeId) document.getElementById('location_place_id').value = placeId;
        
        // Only update address if it comes from autocomplete
        if(address) document.getElementById('location-address').value = address;
    }
}
</script>
