<?php
namespace YTP\Base;
use YTP\Helper\Utils;
use YTP\Helper\Import;

class Initialize {

    public function register(){
        add_action('wp_footer', [$this, 'wpFooter']);
        add_action('admin_init', [$this, 'pluginsLoaded']);
    }

    public function wpFooter(){
        ?>
        <style>
            :root {
                --plyr-video-control-background-hover: <?php echo esc_attr(Utils::getOptionDeep('ytp_option','brandColor', '#00affa')) ?>;
                --plyr-range-fill-background: <?php echo esc_attr(Utils::getOptionDeep('ytp_option','brandColor', '#00affa')) ?>
            }
        </style>
        <?php
    }

    public function pluginsLoaded(){
        $imported_version = get_option('ytp_import_ver', 0);
        if($imported_version < '1.0.0'){
            Import::meta();
            update_option('ytp_import_ver', '1.0.0');
        }
    }

}