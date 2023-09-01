<?php
namespace WPMapBlock;

class Installer
{
    public static function init()
    {
        $self = new self();
        $self->set_option();
    }
    public function set_option()
    {
        if (!get_option('wp_map_block_version')) {
            add_option('wp_map_block_version', WPMAPBLOCK_VERSION);
        }
        if (!get_option('wp_map_block_first_install_time')) {
            add_option('wp_map_block_first_install_time', time() + (get_option('gmt_offset') * HOUR_IN_SECONDS));
        }
    }
}
