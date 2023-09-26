<?php
/**
 * @package     bPlugins
 * @copyright   Copyright (c) 2015, bPlugins LLC.
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License Version 3
 * @since       1.0.0
 */

 $this_sdk_version = '1.1.2';

 if(!class_exists('BPlugins_SDK')){

    // require all elements
    require_once(dirname(__FILE__).'/require.php');

    class BPlugins_SDK {

        protected $file = null;
        public $prefix = '';
        protected $config = [];
        protected $__FILE__ = __FILE__;
        private $lc = null;
        
        function __construct($__FILE__, $config = null){
            $this->__FILE__ = $__FILE__;
            $config_file = plugin_dir_path( $this->__FILE__ ).'bsdk_config.json';

            if($config){
                $this->config = (object) wp_parse_args(json_decode(json_encode($config)), WP_B__CONFIG);
            }elseif(file_exists($config_file)){
                $this->config =  (object) wp_parse_args(json_decode(file_get_contents($config_file)), WP_B__CONFIG);
            }else {
                $config_file = plugin_dir_path( $this->__FILE__ ).basename(__DIR__).'/config.json';
                if(file_exists($config_file)){
                    $this->config =  (object) wp_parse_args(json_decode(file_get_contents($config_file)), WP_B__CONFIG);
                }else {
                    $this->config =  (object) wp_parse_args($config, WP_B__CONFIG);
                }
            }
            
            $this->prefix = $this->config->prefix ?? '';

            if($this->config->features->license && class_exists('BSDKLicense')){
                $this->lc = new BSDKLicense($this->config, $__FILE__);
            }
            if(\is_admin()){
                if($this->config->features->optIn){
                    new Activate($this->config, $__FILE__);
                }
            }
            $this->register();
        }
    
        function register(){
            add_action( 'admin_init', [$this, 'register_settings'] );
            add_action( 'rest_api_init', [$this, 'register_settings']);
            add_action('admin_enqueue_scripts', [$this, 'localizeScript']);
            add_action('plugins_loaded', [$this, 'i18n']);
        }

        function i18n(){
            load_plugin_textdomain('bPlugins-sdk', false, plugin_dir_url( __FILE__ ) . '/languages/');
        }

        function register_settings(){
            register_setting( $this->prefix."_pipe", $this->prefix."_pipe", array(
                'show_in_rest' => array(
                    'name' => $this->prefix."_pipe",
                    'schema' => array(
                        'type'  => 'string',
                    ),
                ),
                'type' => 'string',
                'default' => $this->pipe_default_value(),
                'sanitize_callback' => 'sanitize_text_field',
            ));
        }

        function pipe_default_value(){
            $pipe = get_option( $this->prefix."_pipe" );
            if( $pipe ){
                return $pipe;
            }else{
                return "{}";
            }
        }

        function localizeScript(){
            $data = [
                'ajaxURL' => admin_url('admin-ajax.php'),
                'email' => get_option('admin_email'),
                'nonce' => wp_create_nonce( 'wp_ajax' )
            ];
            wp_localize_script( 'bsdk-license', $this->prefix."Layer", $data);
            
            if($this->config->blockHandler){
                wp_localize_script($this->config->blockHandler, $this->prefix."Layer", $data);
            }
        }

        public function can_use_premium_feature(){
            return $this->lc->isPipe ?? false;
        }

        public function habijabi(){
            return $this->lc->isPipe;
        }

        public function uninstall_plugin(  ){
            deactivate_plugins( plugin_basename( $this->__FILE__ ) );
        }
    }
 }


