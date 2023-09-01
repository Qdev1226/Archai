<?php
namespace WPMapBlock;

class Migration
{
    public static function init()
    {
        $self = new self();
        $self->run_migration();
    }
    public function run_migration()
    {
        if (get_option('wp_map_block_version') != WPMAPBLOCK_VERSION) {
            update_option('wp_map_block_version', WPMAPBLOCK_VERSION);
        }
        if (!get_option('wp_map_block_first_install_time')) {
            add_option('wp_map_block_first_install_time', time() + (get_option('gmt_offset') * HOUR_IN_SECONDS));
        }
    }
}
