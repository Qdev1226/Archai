<?php
namespace YTP\Page;

class HowTo{

    public function register(){
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    public function admin_menu(){
        add_submenu_page( 'edit.php?post_type=ytplayer', 'How To Use', 'How To Use', 'manage_options', 'ytp_howto', [$this, 'ytp_howto_page_callback'] );
    }

    function ytp_howto_page_callback() {
        ?>
            <div class="howto_wrap"><div id="icon-tools" class="icon32"></div>
                <h2>How to use?</h2>
                <h2>Watch the video to learn how to use the plugin. </h2>
                <br/>
                <a target="_blank" href="https://www.youtube.com/embed/NGvVtSXcZK4"><?php _e('Watch Tutorial', 'ytp'); ?></a>
                <br /> 
            </div>
        <?php
    }
}