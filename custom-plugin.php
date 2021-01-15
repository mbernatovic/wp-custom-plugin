<?php
/*
Plugin Name: TEST
Description: test...
Author: mbernatovic
*/

class OceanBlueCustomForms{

    function register(){
        add_action( 'admin_init', array($this,'add_ajax_actions') );
        add_action('init', array($this, 'obf_add_settings_page'));
        add_action('admin_menu', 'ob_form_add_settings_page');
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
    }

    public function add_ajax_actions() {
        add_action('wp_ajax_nopriv_obf_api_call',array( &$this,'obf_api_call'));
        add_action('wp_ajax_obf_api_call',array( &$this,'obf_api_call'));}


        function activate(){
        flush_rewrite_rules();
    }

    function deactivate(){
        flush_rewrite_rules();
    }


    function register_shortcodes(){        
        add_shortcode('ob_custom_form', array($this,'create_ob_form'));
    }

    function enqueue(){
        wp_enqueue_scripts('ob_custom_form_main_css' , plugins_url('/styles/main.css', __FILE__));
    }


    function ob_form_add_settings_page() {

        //create new top-level menu
        add_menu_page( 'OB Custom Forms', 'OB Custom Forms', 'manage_options', 'oceanblue_form_admin_page' , array($this,'obf_admin_index'), '', 110);
        add_action( 'admin_init',  array($this,'obf_register_settings'));
    
    }

    public function obf_admin_index(){
        require_once plugin_dir_path(__FILE__). 'templates/admin-template.php';
    }

    public function obf_api_call(){
        echo 'test';
        die();
        //require_once plugin_dir_path(__FILE__). 'api.php';
    }


    function create_ob_form($atts = '', $content = null){
        ob_start(); 
    
        $form_atts = shortcode_atts( array(
            'bitrix_send' => false,
            'cm_send' => false,
            'cm_list_name' => '',
            'input_fields' => '',
            'custom_template' => '',
            'form_id' => '',
            'return_message' => ''
        ), $atts );
        include(dirname( __FILE__ ) . DIRECTORY_SEPARATOR . "templates/form-template.php" );
    
        return ob_get_clean();
    }
}


if(class_exists('OceanBlueCustomForms')){
    $obForms = new OceanBlueCustomForms();
    $obForms->register();
    $obForms->register_shortcodes();
}


register_activation_hook(__FILE__, array($obForms, 'activate'));
register_deactivation_hook(__FILE__, array($obForms, 'deactivate'));
});
