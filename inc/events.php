<?php
function register_post_type_events() {
    $labels = array(
        'name'                  => _x('Events', 'Post type general name', 'textdomain'),
        'singular_name'         => _x('Event', 'Post type singular name', 'textdomain'),
        'menu_name'             => _x('Events', 'Admin Menu text', 'textdomain'),
        'name_admin_bar'        => _x('Events', 'Add New on Toolbar', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'add_new_item'          => __('Add New Event', 'textdomain'),
        'new_item'              => __('New Event', 'textdomain'),
        'edit_item'             => __('Edit Event', 'textdomain'),
        'view_item'             => __('View Event', 'textdomain'),
        'all_items'             => __('All Events', 'textdomain'),
        'search_items'          => __('Search Events', 'textdomain'),
        'parent_item_colon'     => __('Parent Events:', 'textdomain'),
        'not_found'             => __('No events found.', 'textdomain'),
        'not_found_in_trash'    => __('No events found in Trash.', 'textdomain'),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,
        'has_archive'           => false,
        'rewrite'               => ['slug' => 'events'],
        'supports'              => array('title', 'editor', 'thumbnail'),
        'menu_icon'             => 'dashicons-calendar',
    );

    register_post_type('events', $args);
}
add_action('init', 'register_post_type_events');

/*
 * Register ACF fields (date & time) for Events programmatically.
 * Requires Advanced Custom Fields plugin active.
 */
if (function_exists('acf_add_local_field_group')) {
    add_action('acf/init', function() {
        acf_add_local_field_group(array(
            'key' => 'group_events_details',
            'title' => 'Event Details',
            'fields' => array(
                // array(
                //     'key' => 'field_event_start_date',
                //     'label' => 'Event Start Date',
                //     'name' => 'event_start_date',
                //     'type' => 'date_time_picker',
                //     'display_format'=> 'd/m/Y h:i A',
                //     'return_format' => 'Y-m-d h:i A',
                //     'first_day' => 1,
                // ),
                // array(
                //     'key' => 'field_event_end_date',
                //     'label' => 'Event End Date',
                //     'name' => 'event_end_date',
                //     'type' => 'date_time_picker',
                //     'display_format'=> 'd/m/Y h:i A',
                //     'return_format' => 'Y-m-d h:i A',
                //     'first_day' => 1,
                // ),
                array(
                    'key' => 'field_event_banner',
                    'label' => 'Banner',
                    'name' => 'event_banner',
                    'type' => 'image',
                    'return_format' => 'id',
                    'library' => 'all',
                ), 
                array(
                    'key' => 'field_event_subtitle',
                    'label' => 'Event Subtitle',
                    'name' => 'event_subtitle',
                    'type' => 'textarea',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_event_date_location_and_time',
                    'label' => 'Event Date, Location & Time',
                    'name' => 'event_date_location_and_time',
                    'type' => 'textarea',
                    'instructions' => 'E.g. Thursday, 27 November 2025 | Space 42 Arena, Abu Dhabi | 9:00 AM - 5:00 PM',
                ),
                array(
                    'key' => 'field_event_location',
                    'label' => 'Event Location (Google Map Iframe)',
                    'name' => 'event_location',
                    'type' => 'textarea',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_event_registration_link',
                    'label' => 'Registration Link',
                    'name' => 'event_registration_link',
                    'type' => 'url',
                    'placeholder' => '',
                ),
                array(
                    'key' => 'field_event_download_agenda_link',
                    'label' => 'Download Agenda Link',
                    'name' => 'event_download_agenda_link',
                    'type' => 'url',
                    'placeholder' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'events',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(),
        ));

        acf_add_local_field_group(array(
            'key' => 'group_events_cards',
            'title' => 'Event Cards',
            'fields' => array(
                array(
                    'key' => 'field_cards',
                    'label' => 'Cards',
                    'name' => 'cards',
                    'type' => 'repeater',
                    'button_label' => 'Add Card',
                    'min' => 0,
                    'max' => 3,
                    'layout' => 'row',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_session_card_content',
                            'label' => 'Content',
                            'name' => 'card_content',
                            'type'  => 'wysiwyg',
                            'tabs'  => 'all', 
                            'toolbar' => 'full',
                            'media_upload' => 0,
                        ),
                    ),
                    'collapsed' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'events',
                    ),
                ),
            ),
            'menu_order' => 1,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(),
        ));

        acf_add_local_field_group(array(
            'key' => 'group_event_sessions',
            'title' => 'Event Sessions',
            'fields' => array(
                array(
                    'key' => 'field_sessions',
                    'label' => 'Sessions',
                    'name' => 'sessions',
                    'type' => 'repeater',
                    'button_label' => 'Add Session',
                    'min' => 0,
                    'max' => 0,
                    'layout' => 'row',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_session_name',
                            'label' => 'Session Name',
                            'name' => 'name',
                            'type' => 'text',
                        ),
                        array(
                            'key' => 'field_session_datate_time',
                            'label' => 'Session Date & Time',
                            'name' => 'date_time',
                            'type' => 'date_time_picker',
                            'display_format' => 'd/m/Y g:i a',
                            'return_format' => 'Y-m-d H:i',
                        ),
                        array(
                            'key' => 'field_session_description',
                            'label' => 'Session Description',
                            'name' => 'description',
                            'type'  => 'wysiwyg',
                            'tabs'  => 'all', 
                            'toolbar' => 'full',
                            'media_upload' => 0,
                        ),
                        array(
                            'key' => 'field_session_location',
                            'label' => 'Session Location',
                            'name' => 'session_location',
                            'type' => 'text',
                            'placeholder' => '',
                        ),
                        array(
                            'key' => 'field_session_speakers',
                            'label' => 'Speakers',
                            'name' => 'speakers',
                            'type' => 'repeater',
                            'button_label' => 'Add Speaker',
                            'min' => 0,
                            'max' => 0,
                            'layout' => 'row',
                            'sub_fields' => array(
                                array(
                                    'key' => 'field_speaker_name',
                                    'label' => 'Name',
                                    'name' => 'name',
                                    'type' => 'text',
                                ), 
                                array(
                                    'key' => 'field_speaker_image',
                                    'label' => 'Image',
                                    'name' => 'image',
                                    'type' => 'image',
                                    'return_format' => 'url',
                                    'preview_size' => 'medium',
                                    'library' => 'all',
                                ),                          
                                array(
                                    'key' => 'field_speaker_details',
                                    'label' => 'Position',
                                    'name' => 'details',
                                    'type' => 'textarea',
                                    'rows' => 4,
                                ),
                            ),
                        ),
                    ),
                ),
            ),            
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'events',
                    ),
                ),
            ),
            'menu_order' => 2,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => array(),   
        ));
    });
}