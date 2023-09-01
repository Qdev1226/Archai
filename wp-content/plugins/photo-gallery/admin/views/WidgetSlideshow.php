<?php

/**
 * Class WidgetSlideshowView_bwg
 */
class WidgetSlideshowView_bwg {
  /**
   * @param $args
   * @param $instance
   */
	function widget($args, $instance) {
		extract($args);

		$title = (isset($instance['title']) ? $instance['title'] : "");
		$gallery_id = (isset($instance['gallery_id']) ? $instance['gallery_id'] : 0);
		$theme_id = (isset($instance['theme_id']) ? $instance['theme_id'] : 0);
		$width = (!empty($instance['width']) ? $instance['width'] : BWG()->options->slideshow_width);
		$height = (!empty($instance['height']) ? $instance['height'] : BWG()->options->slideshow_height);
		$filmstrip_height = (!empty($instance['filmstrip_height']) ? $instance['filmstrip_height'] : BWG()->options->slideshow_filmstrip_height);
		$slideshow_effect = (!empty($instance['effect']) ? $instance['effect'] : "fade");
		$slideshow_interval = (!empty($instance['interval']) ? $instance['interval'] : BWG()->options->slideshow_interval);
		$enable_slideshow_shuffle = (isset($instance['shuffle']) ? $instance['shuffle'] : 0);
		$enable_slideshow_autoplay = (isset($instance['enable_autoplay']) ? $instance['enable_autoplay'] : 0);
		$enable_slideshow_ctrl = (isset($instance['enable_ctrl_btn']) ? $instance['enable_ctrl_btn'] : 0);

		// Before widget.
		echo $before_widget;
		// Title of widget.
		if ($title) {
		  echo $before_title . $title . $after_title;
		}
		// Widget output.
		require_once(BWG()->plugin_dir . '/frontend/controllers/controller.php');
		$controller_class = 'BWGControllerSite';
		$view = 'Slideshow';
		$controller = new $controller_class($view);
    $bwg = WDWLibrary::unique_number();
    $params = array (
		  'from' => 'widget',
		  'gallery_type' => 'slideshow',
		  'gallery_id' => $gallery_id,
		  'theme_id' => $theme_id,
		  'slideshow_width' => $width,
		  'slideshow_height' => $height,
		  'slideshow_filmstrip_height' => $filmstrip_height,
		  'slideshow_effect' => $slideshow_effect,
		  'slideshow_interval' => $slideshow_interval,
		  'enable_slideshow_shuffle' => $enable_slideshow_shuffle,
		  'enable_slideshow_autoplay' => $enable_slideshow_autoplay,
		  'enable_slideshow_ctrl' => $enable_slideshow_ctrl,
		);
		$pairs = WDWLibrary::get_shortcode_option_params( $params );
		$controller->execute($pairs, 1, $bwg);
		// After widget.
		echo $after_widget;
	}
 
  /**
   * Widget Control Panel.
   *
   * @param $params
   * @param $instance
   */
	function form($params, $instance) {
		extract($params);
		$defaults = array(
		  'title' => __('Photo Gallery Slideshow', 'photo-gallery'),
		  'gallery_id' => 0,
		  'width' => 200,
		  'height' => 200,
		  'filmstrip_height' => 40,
		  'effect' => 'fade',
		  'interval' => 5,
		  'shuffle' => 0,
		  'theme_id' => 0,
		  'enable_ctrl_btn' => 0,
		  'enable_autoplay' => 0,
		);		
		$instance = wp_parse_args((array) $instance, $defaults);
		?>
		<p>
		  <label for="<?php echo $id_title; ?>"><?php _e('Title:', 'photo-gallery'); ?></label>
		  <input class="widefat" id="<?php echo $id_title; ?>" name="<?php echo $name_title; ?>" type="text" value="<?php echo htmlspecialchars( $instance['title'] ); ?>"/>
		</p>    
		<p>
			<label for="<?php echo $id_gallery_id; ?>"><?php _e('Galleries:', 'photo-gallery'); ?></label><br>
		  <select name="<?php echo $name_gallery_id; ?>" id="<?php echo $id_gallery_id; ?>" class="widefat">
			<option value="0"><?php _e('Select', 'photo-gallery'); ?></option>
			<?php
			foreach ($gallery_rows as $gallery_row) {
			  ?>
			  <option value="<?php echo $gallery_row->id; ?>" <?php echo (($instance['gallery_id'] == $gallery_row->id) ? 'selected="selected"' : ''); ?>><?php echo $gallery_row->name; ?></option>
			  <?php
			}
			?>
		  </select>
		</p>
		<p>
		  <label for="<?php echo $id_effect; ?>"><?php _e('Slideshow effect:', 'photo-gallery'); ?></label><br>
		  <select name="<?php echo $name_effect; ?>" id="<?php echo $id_effect; ?>" class="widefat">        
			<?php
			foreach ($slideshow_effects as $key => $slideshow_effect) {
			  ?>
			  <option value="<?php echo $key; ?>"
                <?php if ($instance['effect'] == $key) echo 'selected="selected"'; ?>><?php echo esc_html($slideshow_effect); ?></option>
			  <?php
			}
			?>
		  </select>
		</p>		
		<p>
		  <label><?php _e('Enable shuffle:', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo $name_shuffle; ?>" id="<?php echo $id_shuffle . "_1"; ?>" value="1" <?php if ($instance['shuffle']) echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "1");' /><label for="<?php echo $id_shuffle . "_1"; ?>"><?php _e('Yes', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo $name_shuffle; ?>" id="<?php echo $id_shuffle . "_0"; ?>" value="0" <?php if (!$instance['shuffle']) echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "0");' /><label for="<?php echo $id_shuffle . "_0"; ?>"><?php _e('No', 'photo-gallery'); ?></label>
		  <input type="hidden" name="<?php echo $name_shuffle; ?>" id="<?php echo $id_shuffle; ?>" value="<?php echo $instance['shuffle']; ?>" class="bwg_hidden" />
		</p>
		<p>
		  <label><?php _e('Enable autoplay:', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo $name_enable_autoplay; ?>" id="<?php echo $id_enable_autoplay . "_1"; ?>" value="1" <?php if ($instance['enable_autoplay']) echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "1");' /><label for="<?php echo $id_enable_autoplay . "_1"; ?>"><?php _e('Yes', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo $name_enable_autoplay; ?>" id="<?php echo $id_enable_autoplay . "_0"; ?>" value="0" <?php if (!$instance['enable_autoplay']) echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "0");' /><label for="<?php echo $id_enable_autoplay . "_0"; ?>"><?php _e('No', 'photo-gallery'); ?></label>
		  <input type="hidden" name="<?php echo $name_enable_autoplay; ?>" id="<?php echo $id_enable_autoplay; ?>" value="<?php echo $instance['enable_autoplay']; ?>" class="bwg_hidden" />
		</p>
		 <p>
		  <label><?php _e('Enable control buttons:', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo $name_enable_ctrl_btn; ?>" id="<?php echo $id_enable_ctrl_btn . "_1"; ?>" value="1" <?php if ($instance['enable_ctrl_btn']) echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "1");' /><label for="<?php echo $id_enable_ctrl_btn . "_1"; ?>"><?php _e('Yes', 'photo-gallery'); ?></label><br>
		  <input type="radio" name="<?php echo $name_enable_ctrl_btn; ?>" id="<?php echo $id_enable_ctrl_btn . "_0"; ?>" value="0" <?php if (!$instance['enable_ctrl_btn']) echo 'checked="checked"'; ?> onclick='jQuery(this).nextAll(".bwg_hidden").first().attr("value", "0");' /><label for="<?php echo $id_enable_ctrl_btn . "_0"; ?>"><?php _e('No', 'photo-gallery'); ?></label>
		  <input type="hidden" name="<?php echo $name_enable_ctrl_btn; ?>" id="<?php echo $id_enable_ctrl_btn; ?>" value="<?php echo $instance['enable_ctrl_btn']; ?>" class="bwg_hidden" />
		</p>
		<p>
		  <label for="<?php echo $id_width; ?>"><?php _e('Dimensions:', 'photo-gallery'); ?></label><br>
		  <input class="widefat" style="width:25%;" id="<?php echo $id_width; ?>" name="<?php echo $name_width; ?>" type="text" value="<?php echo $instance['width']; ?>"/> x 
		  <input class="widefat" style="width:25%;" id="<?php echo $id_height; ?>" name="<?php echo $name_height; ?>" type="text" value="<?php echo $instance['height']; ?>"/> px
		</p>
		<p>
		  <label for="<?php echo $id_filmstrip_height; ?>"><?php _e('Filmstrip height:', 'photo-gallery'); ?></label><br>
		  <input class="widefat" style="width: 25%;" id="<?php echo $id_filmstrip_height; ?>" name="<?php echo $name_filmstrip_height; ?>" type="text" value="<?php echo $instance['filmstrip_height']; ?>"/> px
		</p>
		<p>
		  <label for="<?php echo $id_interval; ?>"><?php _e('Time interval:', 'photo-gallery'); ?></label><br>
		  <input class="widefat" style="width:25%;" id="<?php echo $id_interval; ?>" name="<?php echo $name_interval; ?>" type="text" value="<?php echo $instance['interval']; ?>" /> sec.
		</p>
		<p>
		  <label for="<?php echo $id_theme_id; ?>"><?php _e('Themes:', 'photo-gallery'); ?></label><br>
		  <select name="<?php echo $name_theme_id; ?>" id="<?php echo $id_theme_id; ?>" class="widefat">
			<?php
			foreach ($theme_rows as $theme_row) {
			  ?>
			  <option value="<?php echo $theme_row->id; ?>" <?php echo (($instance['theme_id'] == $theme_row->id || $theme_row->default_theme == 1) ? 'selected="selected"' : ''); ?>><?php echo $theme_row->name; ?></option>
			  <?php
			}
			?>
		  </select>
		</p>
		<?php
	}
}
