<?php 
namespace YTP\Model;

use YTP\Helper\Import;

class Ajax{

    public function register(){
        add_action('wp_ajax_nopriv_ytp_import_data', [$this, 'ytp_import_data']);
        add_action('wp_ajax_ytp_import_data', [$this, 'ytp_import_data']);
    }

    public function ytp_import_data(){
        Import::meta();
        Import::option();
        echo wp_json_encode(["success" => true]);
        die();
    }
}