<?php
if (!defined('ABSPATH')) {
    exit;
}

class UAE_PASS_Authentication
{
    private array $settings;

    public function __construct()
    {
        $this->settings = get_option('uaepass_settings', []);
    }

    public function init()
    {
        add_action('init', [$this, 'handle_uaepass_callback']);
    }

    /* -----------------------------
     * Credentials
     * ----------------------------- */

    public function get_client_id(): string
    {
        return $this->settings['client_id'] ?? '';
    }

    public function get_client_secret(): string
    {
        return $this->settings['client_secret'] ?? '';
    }

    public function get_redirect_uri(): string
    {
        return $this->settings['redirect_uri'] ?? site_url('/impact-challenge/login');
    }

    /* -----------------------------
     * Environment & Base URL
     * ----------------------------- */

    public function get_environment(): string
    {
        return strtolower($this->settings['environment'] ?? 'sandbox');
    }

    public function get_base_url(): string
    {
        return in_array($this->get_environment(), ['live', 'production'], true)
            ? 'https://id.uaepass.ae/idshub'
            : 'https://stg-id.uaepass.ae/idshub';
    }

    /* -----------------------------
     * OAuth Endpoints
     * ----------------------------- */

    public function get_authorize_url(): string
    {
        return $this->get_base_url() . '/authorize';
    }

    public function get_token_url(): string
    {
        return $this->get_base_url() . '/token';
    }

    public function get_userinfo_url(): string
    {
        return $this->get_base_url() . '/userinfo';
    }

    /* -----------------------------
     * Authorization URL Builder
     * ----------------------------- */

    public function get_login_url(): string
    {
        $params = [
            'client_id' => $this->get_client_id(),
            'redirect_uri' => $this->get_redirect_uri(),
            'response_type' => 'code',
            'scope' => 'urn:uae:digitalid:profile:general',
            'state' => wp_create_nonce('uaepass_state'),
            'acr_values' => 'urn:safelayer:tws:policies:authentication:level:low',
        ];
        return urldecode($this->get_authorize_url() . '?' . http_build_query($params));
    }

    // Handle UAE PASS callback
    public function handle_uaepass_callback(){
        if (isset($_GET['code']) && isset($_GET['state'])) {
            $state = $_GET['state'] ?? '';
            if (!wp_verify_nonce($state, 'uaepass_state')) {
                die('Invalid state. Possible CSRF attack!');
            }
            $code = sanitize_text_field($_GET['code']);
            $tokenResponse = $this->get_access_token($code);
            if( $tokenResponse['success'] ){
                $token = $tokenResponse['token'];
                $user_info = $this->get_user_info($token);
                if ($user_info && isset($user_info['uuid'])) {

                    $db = SIC_DB::get_instance();
                    $applicant = $db->get_or_create_applicant( $user_info );
                    if( $applicant ){
                        $_SESSION['sic_user_id'] = $applicant->applicant_id;
                        $_SESSION['sic_user_name'] = $applicant->first_name . ' ' . $applicant->last_name;
                        wp_redirect( SIC_Routes::get_dashboard_home_url() );
                        exit;
                    }
                }
                $params = [
                    'message' => 'UAE PASS Login Error: Emirates ID not found in user info.'
                ];
                wp_redirect( urldecode(SIC_Routes::get_login_url() . '?' . http_build_query($params)) );
                exit;
            }else{
                $params = [
                    'message' => $tokenResponse['error']
                ];
                wp_redirect( urldecode(SIC_Routes::get_login_url() . '?' . http_build_query($params)) );
            }
        }
        else if (isset($_GET['error']) && isset($_GET['state'])) {
            $state = $_GET['state'] ?? '';
            if (!wp_verify_nonce($state, 'uaepass_state')) {
                die('Invalid state. Possible CSRF attack!');
            }

            $params = [
                'message' => sanitize_text_field($_GET['error'] ?? '')
            ];
            wp_safe_redirect(
                add_query_arg($params, SIC_Routes::get_login_url())
            );
            exit;
        }
    }

    // Exchange authorization code for access token
    private function get_access_token($code)
    {
        $response = wp_remote_post($this->get_token_url(), [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->get_client_id() . ':' . $this->get_client_secret()),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => [
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->get_redirect_uri(),
            ],
        ]);

        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'error' => $response->get_error_message()
            );
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if( $body['access_token'] ){
            return array(
                'success' => true,
                'token' => $body['access_token']
            );
        }else{
            return array(
                'success' => false,
                'error' => $body['error_description'] ?? 'Unkown error'
            );
        }
    }

    // Fetch user info using access token
    private function get_user_info($token)
    {
        $response = wp_remote_get($this->get_userinfo_url(), [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        if (is_wp_error($response)) {
            //error_log('UAE PASS Login Error: ' . $response->get_error_message());
            return false;
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }
}



?>