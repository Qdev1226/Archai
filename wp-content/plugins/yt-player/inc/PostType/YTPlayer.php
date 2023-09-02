<?php
namespace YTP\PostType;

class YTPlayer{
    protected static $_instance = null;
    protected $post_type = 'ytplayer';

    /**
     * construct function
     */
    public function register(){
        add_action('init', [$this, 'init']);
        if ( is_admin() ) {
            add_filter( 'post_row_actions', [$this, 'remove_row_actions'], 10, 2 );
            add_action('edit_form_after_title', [$this, 'edit_form_after_title']);
            add_filter('manage_ytplayer_posts_columns', [$this, 'columns_head_only'], 10);
            add_action('manage_ytplayer_posts_custom_column', [$this, 'column_content'], 10, 2);
            add_filter('post_updated_messages', [$this, 'updated_messages']);

            add_action('admin_head-post.php', [$this, 'hide_publish_actions']);
            add_action('admin_head-post-new.php', [$this, 'hide_publish_actions']);	
            add_filter( 'gettext', [$this, 'pdfp_change_publish_button'], 10, 2 );
            
            // add_filter( 'filter_block_editor_meta_boxes', [$this, 'remove_metabox'] );
            // add_action('use_block_editor_for_post', [$this, 'forceGutenberg'], 10, 2);

            if (class_exists('\CSF')) {
                $prefix = '_ytp';
                \CSF::createMetabox($prefix, array(
                    'title' => 'Configure Your Video Player',
                    'post_type' => 'ytplayer',
                    // 'data_type' => 'unserialize',
                ));
    
                $this->configure($prefix);
                // $this->controls();
                // $this->branding();
                // $this->endscreen();
            }
        }
    }


    /**
     * init
     */
    public function init(){
        register_post_type( 'ytplayer',
            array(
                'labels' => array(
                    'name' => __( 'YT Players'),
                    'singular_name' => __( 'YT Player' ),
                    'add_new' => __( 'Add New Player' ),
                    'add_new_item' => __( 'Add new' ),
                    'edit_item' => __( 'Edit' ),
                    'new_item' => __( 'New' ),
                    'view_item' => __( 'View' ),
                    'search_items'       => __( 'Search'),
                    'not_found' => __( 'Sorry, we couldn\'t find any item you are looking for.' )
                ),
                'public' => false,
                'show_ui' => true, 									
                'publicly_queryable' => true,
                'exclude_from_search' => true,
                'menu_position' => 14,
                'menu_icon' =>YTP_PLUGIN_DIR .'img/icon.png',
                'has_archive' => false,
                'hierarchical' => false,
                'capability_type' => 'page',
                'rewrite' => array( 'slug' => 'ytplayer' ),
                'supports' => array( 'title' )
            )
        );
    }

    /**
     * Remove Row
     */
    function remove_row_actions( $idtions ) {
        global $post;
        if( $post->post_type == $this->post_type ) {
            unset( $idtions['view'] );
            unset( $idtions['inline hide-if-no-js'] );
        }
        return $idtions;
    }

    function edit_form_after_title(){
        global $post;	
        if($post->post_type== $this->post_type){
        ?>	
        <div class="ytp_playlist_shortcode">
                <div class="shortcode-heading">
                    <div class="icon"><span class="dashicons dashicons-video-alt3"></span> <?php _e("WP Podcast", "ytp") ?></div>
                    <div class="text"> <a href="https://bplugins.com/support/" target="_blank"><?php _e("Supports", "ytp") ?></a></div>
                </div>
                <div class="shortcode-left">
                    <h3><?php _e("Shortcode", "ytp") ?></h3>
                    <p><?php _e("Copy and paste this shortcode into your posts, pages and widget:", "ytp") ?></p>
                    <div class="shortcode" selectable>[ytplayer id='<?php echo esc_attr($post->ID); ?>']</div>
                </div>
                <div class="shortcode-right">
                    <h3><?php _e("Template Include", "ytp") ?></h3>
                    <p><?php _e("Copy and paste the PHP code into your template file:", "ytp"); ?></p>
                    <div class="shortcode">&lt;?php echo do_shortcode('[ytplayer id="<?php echo esc_html($post->ID); ?>"]');
                    ?&gt;</div>
                </div>
            </div>
        <?php   
        }
    }
    
    // CREATE TWO FUNCTIONS TO HANDLE THE COLUMN
    function columns_head_only($defaults) {
        unset($defaults['date']);
        $defaults['shortcode'] = 'ShortCode';
        $defaults['date'] = 'Date';
        return $defaults;
    }

    function column_content($column_name, $post_ID) {
        if ($column_name == 'shortcode') {
            echo '<div class="ytp_front_shortcode"><input style="text-align: center; border: none; outline: none; background-color: #1e8cbe; color: #fff; padding: 4px 10px; border-radius: 3px;" value="[ytplayer id='. esc_attr($post_ID) . ']" ><span class="htooltip">Copy To Clipboard</span></div>';
        }
    }
    
    function updated_messages( $messages ) {
        $messages[$this->post_type][1] = __('updated ');
        return $messages;
    }

    public function hide_publish_actions(){
        global $post;
        if($post->post_type == $this->post_type){
            echo '
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions{
                        display:none;
                    }
                </style>
            ';
        }
    }

    function remove_metabox($metaboxs) {
        global $post;
        $screen = get_current_screen();

        if($screen->post_type === $this->post_type){
            return false;
        }
        return $metaboxs;
    }

    public function forceGutenberg($use, $post) {
        $gutenberg = (boolean) get_option('pdfp_gutenberg_enable', false);
        $isGutenberg = (boolean) get_post_meta($post->ID, 'isGutenberg', true);
        $pluginUpdated = 1630223686;
        $publishDate = get_the_date('U', $post);
        $currentTime = current_time("U");

    
        if ($this->post_type === $post->post_type) {
            if($gutenberg){
                if($post->post_status == 'auto-draft' ){
                    update_post_meta($post->ID, 'isGutenberg', true);
                    return true;
                }else {
                    if($isGutenberg || $pluginUpdated < $publishDate){
                        return true;
                    }else {
                        remove_post_type_support($this->post_type, 'editor');
                        return false;
                    }
                }
            }else {
                if($isGutenberg){
                    return true;
                }else {
                    remove_post_type_support($this->post_type, 'editor');
                    return false;
                }
            }
        }

        return $use;
    }

    function pdfp_change_publish_button( $translation, $text ) {
        if ( $this->post_type == get_post_type())
        if ( $text == 'Publish' )
            return 'Save';
        return $translation;
    }

    public function configure($prefix){
        \CSF::createSection($prefix, array(
            // 'parent' => 'ytp_playerio',
            'title' => ' ',
            'fields' => array(
                array(
                    'id' => 'source',
                    'title' => 'Video URL/ID',
                    'type'  => 'text',
                ),
                array(
                    'id' => 'controls',
                    'type' => 'button_set',
                    'title' => 'Controls',
                    'multiple' => true,
                    'options' => array(
                      'play-large' => 'Play Large',
                      'play' => 'Play',
                      'progress' => 'Progressbar',
                      'duration' => 'Duration',
                      'current-time' => 'Current Time',
                      'mute' => 'Mute Button',
                      'volume' => 'Volume Control',
                      'settings' => 'Setting Button',
                      'fullscreen' => 'Fullscreen'
                    ),
                    'default' => ['play-large', 'play', 'progress', 'duration', 'current-time','mute', 'volume', 'settings', 'fullscreen']
                ),
                array(
                    'id' => 'width',
                    'type' => 'dimensions',
                    'title' => 'Player Width',
                    'height' => false,
                    'default' => [
                        'unit' => '%',
                        'width' => 100,
                    ]
                ),
            )
        ));
    }

}
