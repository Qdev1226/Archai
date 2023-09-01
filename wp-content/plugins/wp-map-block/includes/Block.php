<?php
namespace WPMapBlock;

class Block
{
    public static function init()
    {
        $self = new self();
        add_action('init', [$self, 'register_block']);
    }

    public function register_block()
    {
        register_block_type(
            'wpmapblock/wp-map-block',
            array(
                'editor_style_handles'  => [ 'wp-map-block-editor-css', 'wp-map-block-stylesheets'],
                'editor_script_handles'  => [ 'wp-map-block-js', 'wp-map-block-frontend-js'],
                'render_callback' => [$this, 'render_callback'],
            )
        );
    }
    public static function escaping_array_data($array)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $value = self::escaping_array_data($value);
            } else {
                $value = esc_attr($value);
            }
        }
        return $array;
    }
    public function render_callback($attributes, $content = '')
    {
		wp_enqueue_style('wp-map-block-stylesheets');
		wp_enqueue_script('wpmapblock-leaflet');
		wp_enqueue_script('wpmapblock-leaflet-fullscreen');
		wp_enqueue_script('wp-map-block-frontend-js');

        $settings = [
            'map_marker' => $this->escaping_array_data(isset($attributes['map_marker_list']) ? $attributes['map_marker_list'] : [[
                'lat' 		=> 23.7806365,
                'lng' 		=> 90.4193257,
                'title'		=> 'Bangladesh',
                'content'	=> 'A Beautiful Country'
            ]]),
            'map_zoom' => (isset($attributes['map_zoom']) ? esc_attr($attributes['map_zoom']) : 10),
            'scroll_wheel_zoom' => (isset($attributes['scroll_wheel_zoom']) ? esc_attr($attributes['scroll_wheel_zoom']) : false),
            'map_type' => (isset($attributes['map_type']) ? esc_attr($attributes['map_type']) : 'GM'),
            'center_index' => (isset($attributes['center_index']) ? intval(esc_attr($attributes['center_index'])) : 0),
        ] ;

        $map_width = (isset($attributes['map_width']) ? esc_attr($attributes['map_width']) . '%' : '100%');
        $map_height = (isset($attributes['map_height']) ? esc_attr($attributes['map_height']) . 'px' : '500px');
        $style = "
			width: {$map_width};
			height: {$map_height};
		";

        ob_start(); ?>
		<div id="<?php echo(isset($attributes['map_id']) ? esc_attr($attributes['map_id']) : ''); ?>" data-settings='<?php echo htmlspecialchars(json_encode($settings), ENT_QUOTES, 'UTF-8'); ?>' class="wpmapblockrender" style="<?php echo esc_attr($style); ?>"></div>
        <?php
        $output = ob_get_clean();
        return $output;
    }
}
