<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_action( 'rest_api_init', 'roi_hunter_easy_register_wp_api_endpoints' );
function roi_hunter_easy_register_wp_api_endpoints() {
    $controller = new RH_Easy_Custom_REST_API();
    $controller->register_routes();
}

// https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
// https://github.com/WP-API/WP-API/issues/2347

class RH_Easy_Custom_REST_API extends WP_REST_Controller {

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {

        $version = '1';
        $namespace = 'roi-hunter-easy/v' . $version;
        $base = 'route';

        // http://store.com/wp-json/roi-hunter-easy/v1/check
        register_rest_route( $namespace, '/check', array(
            array(
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => array( $this, 'do_check' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args'                => array(
                ),
            ),
        ));

        // http://store.com/wp-json/roi-hunter-easy/v1/debug
        register_rest_route( $namespace, '/debug', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'wc_debug' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args'                => array(
                    'clientToken' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return $this->check_clientToken( $param );
                        }
                    ),
                )
            ),
        ));

        // http://store.com/wp-json/roi-hunter-easy/v1/config
        register_rest_route( $namespace, '/config', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_config' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args'                => array(
                    'clientToken' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return $this->check_clientToken( $param );
                        }
                    ),
                )
            ),
        ));

        // http://store.com/wp-json/roi-hunter-easy/v1/state
        register_rest_route( $namespace, '/state', array(
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array( $this, 'get_state' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args'                => array(
                    'clientToken' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return $this->check_clientToken( $param );
                        }
                    ),
                )
            ),
            array(
                'methods'             => WP_REST_Server::EDITABLE,
                'callback'            => array( $this, 'set_state' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args' => $this->allowed_setting_fields( true )
            ),
            array(
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => array( $this, 'clear_state' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args'                => array(
                    'clientToken' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return $this->check_clientToken( $param );
                        }
                    ),
                )
            ),
        ));

        // http://store.com/wp-json/roi-hunter-easy/v1/google-tracking
        register_rest_route( $namespace, '/google-tracking', array(
            array(
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => array( $this, 'clear_google_tracking' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args'                => array(
                    'clientToken' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return $this->check_clientToken( $param );
                        }
                    ),
                )
            ),
        ));

        // http://store.com/wp-json/roi-hunter-easy/v1/fb-tracking
        register_rest_route( $namespace, '/fb-tracking', array(
            array(
                'methods'             => WP_REST_Server::DELETABLE,
                'callback'            => array( $this, 'clear_fb_tracking' ),
                'permission_callback' => array( $this, 'get_items_permissions_check' ),
                'args'                => array(
                    'clientToken' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return $this->check_clientToken( $param );
                        }
                    ),
                )
            ),
        ));

    }

    /**
     * Values stored in settings
     * http://store.com/wp-json/roi-hunter-easy/v1/state
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function get_state( $request ) {

        // Get parameters from request
        $params = $request->get_params();

        // Check clientToken
        if ( ! $this->check_clientToken( $params['clientToken'] ) ) {
            return new WP_Error( '404', __( 'Provided clientToken is not valid or empty', 'roi-hunter-easy' ) );
        }

        // Load state settings
        $helper = new RH_Easy_Helper();
        $collected = $this->allowed_setting_fields( false );
        $data = array();

        foreach( $collected as $key => $value ) {
            $data[ $key ] = $helper->get_option( $key );
        }

        return new WP_REST_Response( $data, 200 );
    }

    /**
     * Set values stored in settings
     * http://store.com/wp-json/roi-hunter-easy/v1/state
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function set_state( $request ) {

        // Get parameters from request
        $params = $request->get_params();

        // Check clientToken
        if ( ! $this->check_clientToken( $params['clientToken'] ) ) {
            return new WP_Error( '404', __( 'Provided clientToken is not valid or empty', 'roi-hunter-easy' ) );
        }

        $helper = new RH_Easy_Helper();
        $collected = $this->allowed_setting_fields( false );

        // Check if params exists in our settings
        if ( count(array_intersect_key($params, $collected)) > 0 ) {

            $data = array();

            // get the value for our settings (skip not existing params)
            foreach( $collected as $key => $value ) {
                if ( $params[ $key ] ) {
                    $data[ $key ] = $params[ $key ];
                }
            }

            // save into plugin settings
            $helper->update_options( $data );

            return new WP_REST_Response( $data, 200 );

        } else {

            return new WP_Error( '404', __( 'Passed parameters are not supported.', 'roi-hunter-easy' ) );

        }

    }

    /**
     * Clear stored values
     * http://store.com/wp-json/roi-hunter-easy/v1/state
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function clear_state( $request ) {

        // Get parameters from request
        $params = $request->get_params();

        // Check clientToken
        if ( ! $this->check_clientToken( $params['clientToken'] ) ) {
            return new WP_Error( '404', __( 'Provided clientToken is not valid or empty', 'roi-hunter-easy' ) );
        }

        $helper = new RH_Easy_Helper();
        $collected = $this->allowed_setting_fields( false );
        $data = array();

        // get the value for our settings (skip not existing params)
        foreach( $collected as $key => $value ) {
            $data[ $key ] = '';
        }

        // save into plugin settings
        $helper->update_options( $data );

        return new WP_REST_Response( $data , 200 );

    }

    /**
     * Clear stored values
     * http://store.com/wp-json/roi-hunter-easy/v1/google-tracking
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function clear_google_tracking( $request ) {

        // Get parameters from request
        $params = $request->get_params();

        // Check clientToken
        if ( ! $this->check_clientToken( $params['clientToken'] ) ) {
            return new WP_Error( '404', __( 'Provided clientToken is not valid or empty', 'roi-hunter-easy' ) );
        }

        $helper = new RH_Easy_Helper();
        $collected = $this->allowed_setting_fields( false );

        $data = array(
            'google_conversion_id' => '',
            'google_conversion_label' => '',
        );

        // save into plugin settings
        $helper->update_options( $data );

        return new WP_REST_Response( $data , 200 );

    }

    /**
     * Clear stored values
     * http://store.com/wp-json/roi-hunter-easy/v1/fb-tracking
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function clear_fb_tracking( $request ) {

        // Get parameters from request
        $params = $request->get_params();

        // Check clientToken
        if ( ! $this->check_clientToken( $params['clientToken'] ) ) {
            return new WP_Error( '404', __( 'Provided clientToken is not valid or empty', 'roi-hunter-easy' ) );
        }

        $helper = new RH_Easy_Helper();
        $collected = $this->allowed_setting_fields( false );

        $data = array(
            'fb_pixel_id' => '',
        );

        // save into plugin settings
        $helper->update_options( $data );

        return new WP_REST_Response( $data , 200 );

    }


    /**
     * Return "rh-easy-active.";
     * http://store.com/wp-json/roi-hunter-easy/v1/check
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function do_check( $request ) {
        $data = 'rh-easy-active.';
        return new WP_REST_Response( $data, 200 );
    }

    /**
     * Return WC debug info;
     * http://store.com/wp-json/roi-hunter-easy/v1/debug
     * see: https://docs.woocommerce.com/document/understanding-the-woocommerce-system-status-report/
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Response
     */
    public function wc_debug( $request ) {

        // Get parameters from request
        $params = $request->get_params();

        // Check clientToken
        if ( ! $this->check_clientToken( $params['clientToken'] ) ) {
            return new WP_Error( '404', __( 'Provided clientToken is not valid or empty', 'roi-hunter-easy' ) );
        }

        $system_status    = new WC_REST_System_Status_Controller();
        $environment      = $system_status->get_environment_info();
        $database         = $system_status->get_database_info();
        //$post_type_counts = $system_status->get_post_type_counts();
        $active_plugins   = $system_status->get_active_plugins();
        $theme            = $system_status->get_theme_info();
        $security         = $system_status->get_security_info();
        //$settings         = $system_status->get_settings();
        //$pages            = $system_status->get_pages();
        //$plugin_updates   = new WC_Plugin_Updates();
        //$untested_plugins = $plugin_updates->get_untested_plugins( WC()->version, 'minor' );

        $data = array(
            'environment' => $environment,
            'database' => $database,
            'active_plugins' => $active_plugins,
            'theme' => $theme,
            'security' => $security,
        );

        // TODO, co všechno se bude vracet od našeho pluginu? I to, co umíme zjistit přes state nebo ještě nějaké dodatečné věci?

        return new WP_REST_Response( $data, 200 );
    }

    /**
     * Return current goostav config
     *
     * @return WP_Error|WP_REST_Response
     */
    public function get_config( $request ) {

        // Get parameters from request
        $params = $request->get_params();

        // Check clientToken
        if ( ! $this->check_clientToken( $params['clientToken'] ) ) {
            return new WP_Error( '404', __( 'Provided clientToken is not valid or empty', 'roi-hunter-easy' ) );
        }

        $helper = new RH_Easy_Helper();
        $applicationConfig = $helper->get_config();

        return new WP_REST_Response( $applicationConfig, 200 );
    }

    /**
     * Check if a given request has access to get items
     *
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|bool
     */
    public function get_items_permissions_check( $request ) {
        return true;
    }

    /**
     * Return array of allowed ROI Hunter Easy settings fields and its type
     *
     * @return array
     */
    private function allowed_setting_fields( $client_token = false ) {

        $fields = array(
            'customer_id' => array( 'type' => 'number' ),
            'access_token' => array( 'type' => 'string' ),
            'google_conversion_id' => array( 'type' => 'number' ),
            'google_conversion_label' => array( 'type' => 'string' ),
            'fb_pixel_id' => array( 'type' => 'string', ),
        );

        if ( $client_token ) {
            $fields['clientToken'] = array( 'type' => 'string', 'validate_callback' => function($param, $request, $key) {
                    return $this->check_clientToken( $param );
                }
            );
        }

        return $fields;

    }

    private function check_clientToken( $token ) {

        $helper = new RH_Easy_Helper();
        if ( $token == $helper->get_option('clientToken') ) {
            return true;
        }

        return false;

    }

  }
