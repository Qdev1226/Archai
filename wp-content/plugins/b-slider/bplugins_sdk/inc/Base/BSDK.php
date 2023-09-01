<?php
class BSDK{

    protected $prefix = '';
    protected $config = '';
    protected $base_name = null;
    protected $plugin_name = '';
    protected $product = "";
    public $isPipe = false;
    protected $key = null;
    protected $__FILE__ = null;
    protected $_upgraded = false;
    protected $version = false;
    protected $dir = __DIR__;
    protected $test = null;
    protected $blockHandler = null;


    function __construct($config, $__FILE__){
        $this->config = $config;
        $this->prefix = $this->config->prefix;
        $this->__FILE__ = $__FILE__;
        $this->base_name = plugin_basename( $this->__FILE__ );
        $this->blockHandler = $this->config->blockHandler;

        $license = str_replace('8ysg', true, stripslashes($this->__($this->prefix."_pipe")));
        
        if($license){
            $license = json_decode(str_replace('4z5xg', 'false', $license), true);
            $this->isPipe = isset($license['zn8mpz8gt']) ? $license['zn8mpz8gt'] : false;
            $this->key = isset($license['jga']) ? $license['jga'] : false;
        }else {
            $o_license = $this->__($this->prefix);
            $this->isPipe = isset($o_license['active']) ? $o_license['active'] : false;
            $this->key = isset($o_license['key']) ? $o_license['key'] : false;
        }

        if( ! function_exists('get_plugin_data') ){
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        }

        $plugin_data = \get_plugin_data( $this->__FILE__ );
        $this->plugin_name = $plugin_data['Name'];
        $this->version = $plugin_data['Version'];
    }

    // function 

    function __($name){
        $data = get_option($name);
        $this->_upgraded = (boolean) $data;
        return $data;
    }

}


