<?php

class admin {

    // This function will populate the options if the plugin is activated for the first time.
    // It will also protect the options if the plugin is deactivated (common in troubleshooting WP related issues)
    // We may want to add an option to remove DB entries...
    public function activate() {

        $option_name = 'clearent_opts';
        $options = get_option($option_name);

        $options['environment'] = isset($options['environment']) ? $options['environment'] : 'sandbox';
        $options['success_url'] = isset($options['success_url']) ? $options['success_url'] : '-1';
        $options['sb_api_key'] = isset($options['sb_api_key']) ? $options['sb_api_key'] : '';
        $options['prod_api_key'] = isset($options['prod_api_key']) ? $options['prod_api_key'] : '';
        $options['enable_debug'] = isset($options['enable_debug']) ? $options['enable_debug'] : 'disabled';

        update_option($option_name, $options);

    }

    // Initialize admin page
    public function admin_menu(){
        require_once('settings.php');
        $settings = new settings();
        // add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
        $wp_clearent_page = add_options_page('Clearent Payments', 'Clearent Payments', 'manage_options', 'clearent_option_group', array($settings, 'settingsPage'));
        add_action('admin_print_scripts-' . $wp_clearent_page, array($this, 'admin_scripts'));  // Load our admin page scripts (our page only)
        add_action('admin_print_styles-' . $wp_clearent_page, array($this, 'wp_clearent_admin_print_styles'));    // Load our admin page stylesheet (our page only)
    }

    public function admin_scripts() {
        wp_enqueue_script('admin.js', plugins_url('/js/admin.js', dirname(__FILE__) ));
        wp_enqueue_script('loading.js', plugins_url('/js/loading.js', dirname(__FILE__) ));
    }

    public function wp_clearent_admin_print_styles() {
        wp_enqueue_style('admin.css', plugins_url('/css/admin.css', dirname(__FILE__) ));
        wp_enqueue_style('loading.css', plugins_url('/css/loading.css', dirname(__FILE__) ));
    }

    // Register Plugin settings array
    public function registerSettings() {
        $option_name = 'clearent_opts';
        register_setting('clearent_option_group', $option_name, array($this, 'validate_options'));
    }

    public function validate_options($input) {
        $valid = array();
        $valid['environment'] = isset($input['environment']) ? $input['environment'] : 'sandbox';
        $valid['success_url'] = isset($input['success_url']) ? $input['success_url'] : '-1';
        $valid['sb_api_key'] = isset($input['sb_api_key']) ? $input['sb_api_key'] : '';
        $valid['prod_api_key'] = isset($input['prod_api_key']) ? $input['prod_api_key'] : '';
        $valid['enable_debug'] = isset($input['enable_debug']) ? $input['enable_debug'] : '';

        return $valid;
    }

    public function add_action_links ( $links ) {
        $mylinks = array(
            '<a href="' . admin_url( 'options-general.php?page=clearent_option_group' ) . '">Settings</a>',
        );
        return array_merge( $mylinks, $links  );
    }

    public function install_db() {
        global $wpdb;

        $table_name = $wpdb->prefix . "clearent_transaction";
        $charset_collate = $wpdb->get_charset_collate();

        // mysql char can hold up to 30 characters - switch to varchar if more is needed
        $sql = "CREATE TABLE $table_name (
            id CHAR(25) NOT NULL,
            environment CHAR(12) NOT NULL,
            display_message VARCHAR(255),
            transaction_type CHAR(15) NOT NULL,
            amount CHAR(10) NOT NULL,
            sales_tax_amount CHAR(10),
            card CHAR(19) NOT NULL,
            exp_date CHAR(4) NOT NULL,
            invoice VARCHAR(32),
            purchase_order VARCHAR(32),
            email_address VARCHAR(96),
            customer_id  VARCHAR(32),
            order_id VARCHAR(32),
            description TEXT,
            comments TEXT,
            billing_firstname VARCHAR(32),
            billing_lastname VARCHAR(32),
            billing_company VARCHAR(32),
            billing_street VARCHAR(128),
            billing_street2 VARCHAR(128),
            billing_city VARCHAR(128),
            billing_state VARCHAR(40),
            billing_zip CHAR(10),
            billing_country VARCHAR(128),
            billing_phone VARCHAR(32),
            billing_is_shipping tinyint(1),
            shipping_firstname VARCHAR(32),
            shipping_lastname VARCHAR(32),
            shipping_company VARCHAR(32),
            shipping_street VARCHAR(128),
            shipping_street2 VARCHAR(128),
            shipping_city VARCHAR(128),
            shipping_state VARCHAR(40),
            shipping_zip CHAR(10),
            shipping_country VARCHAR(128),
            shipping_phone VARCHAR(32),
            client_ip VARCHAR(45),
            transaction_id CHAR(30),
            authorization_code VARCHAR(32),
            exchange_id VARCHAR(128),
            result VARCHAR(32),
            result_code CHAR(10),
            response_raw TEXT,
            user_agent VARCHAR(255),
            date_added DATETIME NOT NULL,
            date_modified DATETIME NOT NULL,
            PRIMARY KEY  (id)
        )  $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

    }


}