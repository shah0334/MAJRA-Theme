<?php
/**
 * SIC Storage Handler
 * 
 * Handles file uploads and retrieval. 
 * Abstraction layer to support future migration to Azure Blob Storage.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class SIC_Storage {

    private static $instance = null;

    public static function get_instance() {
        if ( self::$instance == null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Init any cloud storage clients here in the future
    }

    /**
     * Upload a file.
     * 
     * @param array $file_array The $_FILES item (e.g. $_FILES['my_file']).
     * @param string $directory Optional subdirectory (e.g. 'project-files').
     * @return array|WP_Error Array with 'url' and 'file' (path), or WP_Error.
     */
    public function upload_file( $file_array, $directory = 'sic-uploads' ) {
        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        }

        // Add a filter to change the upload directory securely
        $upload_dir_filter = function( $uploads ) use ( $directory ) {
            $uploads['subdir'] = '/' . $directory . $uploads['subdir'];
            $uploads['path']   = $uploads['basedir'] . $uploads['subdir'];
            $uploads['url']    = $uploads['baseurl'] . $uploads['subdir'];
            return $uploads;
        };

        add_filter( 'upload_dir', $upload_dir_filter );

        $overrides = [ 'test_form' => false ]; // Necessary for custom form handlers
        
        $movefile = wp_handle_upload( $file_array, $overrides );

        remove_filter( 'upload_dir', $upload_dir_filter );

        if ( $movefile && ! isset( $movefile['error'] ) ) {
            // Check if we need to store metadata about this file in sic_files table?
            // For now, return the path info so the caller can save to DB.
            return $movefile;
        } else {
            return new WP_Error( 'upload_error', $movefile['error'] );
        }
    }

    /**
     * Get the public URL for a stored file.
     * 
     * @param string $file_path The stored file path or identifier.
     * @return string Public URL.
     */
    public function get_file_url( $file_path ) {
        // Just return the URL if it's already a URL
        if ( filter_var($file_path, FILTER_VALIDATE_URL) ) {
            return $file_path;
        }

        // If it's a relative path or local path, convert to URL
        // Implementation depends on what we store in the DB.
        // Assuming we store the full URL for now in the DB based on wp_handle_upload return.
        return $file_path;
    }
}
