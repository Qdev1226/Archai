<?php

namespace YTP;

class Init
{
    public static function get_services()
    {
        return [
            PostType\YTPlayer::class,
            Base\Initialize::class,
            Page\Settings::class,
            Page\HowTo::class,
            Services\Shortcode::class,
            Services\Shortcode::class,
            Services\EnqueueAssets::class,
            Model\Ajax::class
        ];
    }
    
    public static function register_services()
    {
        foreach ( self::get_services() as $class ) {
            $services = self::instantiate( $class );
            if ( method_exists( $services, 'register' ) ) {
                $services->register();
            }
        }
    }
    
    private static function instantiate( $class )
    {
        if ( class_exists( $class ) ) {
            return new $class();
        }
        return new \stdClass();
    }

}