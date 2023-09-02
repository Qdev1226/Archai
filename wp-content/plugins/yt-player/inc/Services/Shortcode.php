<?php
namespace YTP\Services;

use YTP\Helper\Utils;


class Shortcode {

    public function register(){
        add_shortcode('ytp', [$this, 'ytp']);
        add_shortcode('ytplayer', [$this, 'ytplayer']);
    }

    public function ytp($atts, $content){
        extract( shortcode_atts( array(
            'url' => null,
            'autoplay' => false
        ), $atts ) ); 
        Ob_start(); 
    
        $content=str_replace(' ','', $content); 
        $id=str_replace('https://www.youtube.com/watch?v=','',$content); 
        $selector=uniqid();
        
        $width = Utils::getOptionDeep('ytp_option','width',['width' => 100, 'unit' => '%']);
        $controls = Utils::getOptionDeep('ytp_option', 'controls', []);
        
        ?>
        <style>
            <?php echo esc_html("#player$selector") ?>{
                max-width: <?php echo esc_html($width['width'].$width['unit']) ?>;
                margin: 0 auto;
            }
        </style>    
        <div>
            <div id="player<?php echo esc_attr($selector); ?>">
                <div class="plyr__video-embed embed-container player">
                    <iframe
                        src="https://www.youtube.com/embed/<?php echo esc_attr($id); ?>"
                        allowfullscreen
                        allowtransparency
                        allow="autoplay"
                    ></iframe>
                </div>
                <script type="text/javascript">
                    const player<?php echo esc_html($selector); ?> = new Plyr('#player<?php echo esc_html($selector); ?> .player', {
                        controls: <?php echo wp_json_encode(  array_values($controls) ) ?>,
                        youtube: { noCookie: false, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1, start: 0}  
                    });
                </script>
            </div>
        </div>
        <?php 
        $output=ob_get_clean(); return $output; 
        
    }

    public function ytplayer($atts){
        extract( shortcode_atts( array(
            'id' => null,
        ), $atts ) ); 

        $option = get_post_meta($id, '_ytp', true);
        if(!is_array($option)){
            return false;
        }
        
        Ob_start(); 
        $option['controls'] = array_diff($option['controls'], ['restart', 'rewind', 'fast-forward', 'current-time']);
        $width = $option['width']['width']. $option['width']['unit'];
        ?>
        <style>
            <?php echo esc_attr("#player$id"); ?>{
                max-width: <?php echo esc_html($width); ?>;
                margin: 0 auto;
            }
            <?php if($option['hideYoutubeUI'] == '1'){ ?>
                <?php echo esc_attr("#player$id"); ?> iframe{
                    position: absolute;
                    top: -50%;
                    height: 200%;
                }
            <?php } ?>
        </style>
        <div>
            <div id="player<?php echo esc_attr($id); ?>">
                <div class="plyr__video-embed embed-container" id="player">
                    <iframe
                        src="https://www.youtube.com/embed/<?php echo $option['source']; ?>"
                        allowfullscreen
                        allowtransparency
                        allow="autoplay"
                    ></iframe>
                </div>
                <script type="text/javascript">
                    const player<?php echo esc_html($id); ?> = new Plyr('#player<?php echo esc_html($id); ?> #player', {
                        controls:<?php echo wp_json_encode(array_values($option['controls'])) ?>,
                        youtube: { noCookie: false, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1, start: 0} //pro 
                    });
                </script>
            </div>
        </div>
        <?php $output=ob_get_clean(); return $output; 
    }
}