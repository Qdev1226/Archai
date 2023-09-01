<?php

/**
 * Class LicensingView_bwg
 */
class LicensingView_bwg {
  public function __construct() {
    wp_enqueue_style(BWG()->prefix . '_licensing');
    wp_enqueue_style(BWG()->prefix . '_tables');
  }

  public function display() {
    ?>
    <div id="featurs_tables">
      <div id="featurs_table1">
        <span>WordPress 3.4+ <?php _e("ready", 'photo-gallery'); ?></span>
        <span>SEO-<?php _e("friendly", 'photo-gallery'); ?></span>
        <span><?php _e("Responsive Design and Layout", 'photo-gallery'); ?></span>
        <span><?php _e("5 Standard Gallery/Album Views", 'photo-gallery'); ?></span>
        <span><?php _e("Watermarking/ Advertising Possibility", 'photo-gallery'); ?></span>
        <span><?php _e("Basic Tag Cloud Widget", 'photo-gallery'); ?></span>
        <span><?php _e("Image Download", 'photo-gallery'); ?></span>
        <span><?php _e("Photo Gallery Slideshow Widget", 'photo-gallery'); ?></span>
        <span><?php _e("Photo Gallery Widget", 'photo-gallery'); ?></span>
        <span><?php _e("Slideshow/Lightbox Effects", 'photo-gallery'); ?></span>
        <span><?php _e("Possibility of Editing/Creating New Themes", 'photo-gallery'); ?></span>
        <span><?php _e("10 Pro Gallery/Album Views", 'photo-gallery'); ?></span>
        <span><?php _e("Image Commenting", 'photo-gallery'); ?></span>
        <span><?php _e("Image Social Sharing", 'photo-gallery'); ?></span>
        <span><?php _e("Photo Gallery Tags Cloud Widget", 'photo-gallery'); ?></span>
        <span><?php _e("Instagram Integration", 'photo-gallery'); ?></span>
        <span>AddThis <?php _e("Integration", 'photo-gallery'); ?></span>
        <span><?php _e("Add-ons Support", 'photo-gallery'); ?></span>
      </div>
      <div id="featurs_table2">
        <span style="padding-top: 18px;height: 39px;"><?php _e("Free", 'photo-gallery'); ?></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span>1</span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
        <span class="no"></span>
      </div>
      <div id="featurs_table3">
        <span><?php _e("Pro Version", 'photo-gallery'); ?></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span>15</span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
        <span class="yes"></span>
      </div>
    </div>
    <div style="float: left; clear: both;">
      <p><?php _e("After purchasing the commercial version follow these steps:", 'photo-gallery'); ?></p>
      <ol>
        <li><?php _e("Deactivate Photo Gallery plugin.", 'photo-gallery'); ?></li>
        <li><?php _e("Delete Photo Gallery plugin.", 'photo-gallery'); ?></li>
        <li><?php _e("Install the downloaded commercial version of the plugin.", 'photo-gallery'); ?></li>
      </ol>
    </div>
    <?php
  }
}
