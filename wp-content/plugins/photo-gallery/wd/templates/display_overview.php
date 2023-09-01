<div class="tenweb_overview">
  <div class="tenweb_header">
    <div class="container tenweb_clear container_top">
      <div class="tenweb_logo">
        <a href="https://10web.io/" target="_blank">
          <div></div>
        </a>
      </div>
      <div class="tenweb_header_right tenweb_clear">
        <div class="inline-block header_text"><?php echo sprintf(__("Get Premium %s and Other Solutions Essential for Your WordPress Site.", 'photo-gallery'), $wd_options->plugin_title); ?>
        </div>
        <a href="https://my.10web.io/checkout/" target="_blank"
           class="button"><?php _e("Try Free", 'photo-gallery'); ?></a>
      </div>
    </div>
    <div class="tenweb_header_divider">
    </div>
    <div class="container container_bottom">
      <div class="plugin_info">
        <img src="<?php echo $wd_options->overview_welcome_image; ?>" class="plugin_logo">
        <h2><?php _e("Premium ", 'photo-gallery'); ?><?php echo $wd_options->plugin_title; ?></h2>
        <div class="and"> &</div>
      </div>
      <div class="plan_features tenweb_clear">
        <div class="plan_feature pro_plugins">
          <div class="logo"></div>
          <h3><?php _e("60+ pro plugins/Extensions", 'photo-gallery'); ?></h3>
          <p><?php _e("Access 60+ plugins and extensions, including key plugins, such as gallery, form, slider, social plugins and more.", 'photo-gallery'); ?></p>
        </div>
        <div class="plan_feature dashboard">
          <div class="logo"></div>
          <h3><?php _e("Unified dashboard", 'photo-gallery'); ?></h3>
          <p><?php _e("Manage your WordPress websites all in one place within a single dashboard. No need to switch between sites.", 'photo-gallery'); ?></p>
        </div>
        <div class="plan_feature pro_themes">
          <div class="logo"></div>
          <h3><?php _e("Premium WordPress themes", 'photo-gallery'); ?></h3>
          <p><?php _e("Professionally designed, responsive themes for your website. Build fully-functional, elegant websites effortlessly.", 'photo-gallery'); ?></p>
        </div>
        <?php if ( $wd_options->plugin_wordpress_slug != "backup-wd" ) { ?>
          <div class="plan_feature backup">
            <div class="logo"></div>
            <h3><?php _e("Backup", 'photo-gallery'); ?></h3>
            <p><?php _e("10Web cloud storage space. Easily and securely backup your website in our storage.", 'photo-gallery'); ?></p>
          </div>
        <?php } ?>
        <?php if ( $wd_options->plugin_wordpress_slug != "seo-by-10web" ) { ?>
          <div class="plan_feature seo">
            <div class="logo"></div>
            <h3><?php _e("SEO", 'photo-gallery'); ?></h3>
            <p><?php _e("Improve search rankings of your WordPress site with a comprehensive search engine optimization solution.", 'photo-gallery'); ?></p>
          </div>
        <?php } ?>
        <div class="plan_feature security">
          <div class="logo"></div>
          <h3><?php _e("Security", 'photo-gallery'); ?></h3>
          <p><?php _e("Protect your WordPress site from security issues and threats with a powerful security service coming soon to 10Web.", 'photo-gallery'); ?></p>
        </div>
        <?php if ( $wd_options->plugin_wordpress_slug != "image-optimizer-wd" ) { ?>
          <div class="plan_feature image_optimizer">
            <div class="logo"></div>
            <h3><?php _e("Image optimization", 'photo-gallery'); ?></h3>
            <p><?php _e("Automatically resize and compress all images on your website to save space and improve site speed.", 'photo-gallery'); ?></p>
          </div>
        <?php } ?>
        <div class="plan_feature hosting">
          <div class="logo"></div>
          <h3><?php _e("Hosting", 'photo-gallery'); ?></h3>
          <p><?php _e("We’ll soon be offering affordable hosting solution with WordPress-friendly features and great customer support.", 'photo-gallery'); ?></p>
        </div>
      </div>
      <a href="https://my.10web.io/checkout/" target="_blank"
         class="button"><?php _e("Get free for 14 days", 'photo-gallery'); ?></a>
      <div><a href="https://10web.io/" target="_blank"
              class="more white"><?php _e("Learn More", 'photo-gallery'); ?></a></div>
    </div>
  </div>
  <?php if ( count($wd_options->plugin_features) ) { ?>
    <div class="tenweb_plugin_features">
      <div class="container">
        <h2><?php _e("Premium ", 'photo-gallery'); ?><?php echo $wd_options->plugin_title; ?><?php _e(" features you get!", 'photo-gallery'); ?></h2>
        <div class="plugin_features tenweb_clear">
          <?php foreach ( $wd_options->plugin_features as $features ) { ?>
            <div class="plugin_feature">
              <div class="plugin_feature_logo">
                <img src="<?php echo $features['logo']; ?>">
              </div>
              <h3><?php echo $features['title']; ?></h3>
              <p><?php echo $features['description']; ?></p>
            </div>
          <?php } ?>
        </div>
        <div class="and circle"> &</div>
        <h3 class="more_features"><?php _e("More great features of the plugin", 'photo-gallery'); ?></h3>
        <a href="<?php echo $wd_options->plugin_wd_url; ?>" target="_blank"
           class="more blue"><?php _e("Learn More", 'photo-gallery'); ?></a>
      </div>
    </div>
  <?php }
  if ( trim($wd_options->plugin_wd_demo_link) != "" || trim($wd_options->plugin_wd_docs_link) != "" ) { ?>
    <div class="tenweb_how_it_works">
      <div class="container">
        <h2><?php _e("Checkout how it works", 'photo-gallery'); ?></h2>
        <?php
        if ( trim($wd_options->plugin_wd_demo_link) != "" ) { ?>
          <a href="<?php echo $wd_options->plugin_wd_demo_link; ?>" target="_blank"
             class="button transparent"><?php _e("Demo", 'photo-gallery'); ?></a>
        <?php }
        if ( trim($wd_options->plugin_wd_docs_link) != "" ) { ?>
          <a href="<?php echo $wd_options->plugin_wd_docs_link; ?>" target="_blank"
             class="button transparent"><?php _e("User Guide", 'photo-gallery'); ?></a>
        <?php } ?>
      </div>
    </div>
  <?php } ?>
  <div class="tenweb_footer <?php echo trim($wd_options->plugin_wd_demo_link) == "" ? "without_demo" : ""; ?>">
    <div class="container">
      <h2><?php echo $wd_options->description; ?></h2>
      <p><?php echo sprintf(__("Get Premium %s and Other Solutions Essential for Your WordPress Site.", 'photo-gallery'), $wd_options->plugin_title); ?></p>
      <a href="https://my.10web.io/checkout/" target="_blank"
         class="button"><?php _e("Get free for 14 days", 'photo-gallery'); ?></a>
    </div>
  </div>
</div>