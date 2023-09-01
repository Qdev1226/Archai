<div class="tenweb_subscribe">
  <div class="tenweb_subscribe-content">
    <div class="texts">
      <div class="hi_there"></div>
      <h2><?php _e("Hi there!", 'photo-gallery'); ?></h2>
      <h5><?php _e("Allow 10Web to collect some usage data", 'photo-gallery'); ?></h5>
      <p>
        <?php echo sprintf(__("This will allow you to get more out of your plugin experience - get awesome customer support, receive exclusive deals and discounts on premium products and more. You can choose to skip this step, %s will still work just fine.", 'photo-gallery'), $wd_options->plugin_title); ?>
      </p>
    </div>
    <div class="permissions"><?php _e("What data is being collected?", 'photo-gallery'); ?></div>
    <div class="list tenweb_clear">
      <div class="list_item user_info">
        <div class="list_logo"></div>
        <div class="list_text_wrap"> <?php _e("Your name & Email address", 'photo-gallery'); ?></div>
      </div>
      <div class="list_item wp_info">
        <div class="list_logo"></div>
        <div class="list_text_wrap"> <?php _e("Site URL, Wordpress version", 'photo-gallery'); ?></div>
      </div>
      <div class="list_item plugins_info">
        <div class="list_logo"></div>
        <div class="list_text_wrap"> <?php _e("List of plugins", 'photo-gallery'); ?></div>
      </div>
    </div>
    <div class="btns">
      <a href="<?php echo "admin.php?page=" . $wd_options->prefix . "_subscribe&" . $wd_options->prefix . "_sub_action=allow"; ?>" class="allow_and_continue button"><?php _e("Allow & Continue", 'photo-gallery'); ?></a>
      <img src="<?php echo $wd_options->wd_url_img . '/loader.gif'; ?>" class="wd_loader">
      <a href="<?php echo "admin.php?page=" . $wd_options->prefix . "_subscribe&" . $wd_options->prefix . "_sub_action=skip"; ?>" class="skip more"><?php _e("Skip", 'photo-gallery'); ?></a>
    </div>
  </div>
  <div class="tenweb_subscribe-top-footer">
    <?php _e("We will not sell, share, or distribute your personal information to third parties.", 'photo-gallery'); ?>
  </div>
  <div class="tenweb_subscribe-footer">
    <ul class="tenweb_footer-menu tenweb_clear">
      <li>
        <a href="https://10web.io/privacy-policy/" target="_blank">
          <?php _e("Privacy Policy", 'photo-gallery'); ?>
        </a>
      </li>
      <li>|</li>
      <li>
        <a href="https://10web.io/terms-of-services/" target="_blank">
          <?php _e("Terms of Use", 'photo-gallery'); ?>
        </a>
      </li>
    </ul>
  </div>
</div>