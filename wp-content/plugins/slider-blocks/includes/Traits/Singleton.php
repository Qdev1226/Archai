<?php
/**
 * Singleton Trait
 */
namespace GutSliders\Traits;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

trait Singleton {
    /**
     * Initialize the plugin
     */
    public static function init(){
        static $instance = false; 
        if( ! $instance ) {
            $instance = new self();
        }
        return $instance;
    }
}