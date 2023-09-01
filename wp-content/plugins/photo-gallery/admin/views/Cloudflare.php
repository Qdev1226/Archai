<?php

class CloudflareView_bwg {
  public function __construct() {
    wp_enqueue_style(BWG()->prefix . '_cdn');
  }

  public function display() {
    echo $this->body();
  }

  public function body() {
    $upgrade_link = WDWLibrary::pro_button_link('From Gallery CDN page');
    $cloudflare_cdn_benefits = array(
      array(
        'title' => __('Enterprise CDN', 'photo-gallery'),
        'desc' => __('Get ultra-fast content delivery over Cloudflare’s<br> global edge network spread in over 275 cities.
                            <br>Remove latency and improve performance.', 'photo-gallery'),
      ),
      array(
        'title' => __('Full page cache', 'photo-gallery'),
        'desc' => __('Enable full page caching for static pages<br> to read entirely from the cache, improving
                            <br>server response time and loading.', 'photo-gallery'),
      ),
      array(
        'title' => __('Free SSL Certificate', 'photo-gallery'),
        'desc' => __('Cloudflare’s SSL improves load times<br>and protects website visitors ensuring a better<br> user experience.', 'photo-gallery'),
      ),
      array(
        'title' => __('DDoS and bot protection', 'photo-gallery'),
        'desc' => __('Cloudflare protection secures websites while<br> ensuring the performance of legitimate traffic
                            <br> is not compromised.', 'photo-gallery'),
      ),
      array(
        'title' => __('Mobile optimization with Mirage', 'photo-gallery'),
        'desc' => __('Mirage automatically resizes the images<br> depending on the device and connection<br> of your visitors.', 'photo-gallery'),
      ),
      array(
        'title' => __('Web application firewall (WAF)', 'photo-gallery'),
        'desc' => __('Monitor, filter and protect data through<br> Cloudflare’s WAF. Secure your websites<br>
                            from critical threats and vulnerabilities.', 'photo-gallery'),
      ),
    );
    ?>
    <div class="bwg-wp-container">
    <div class="bwg-container-with-border">
      <p class="bwg-page-main-title"><?php esc_html_e('Cloudflare CDN', 'photo-gallery'); ?></p>
      <p class="bwg-page-main-desc"><?php
        esc_html_e('Enable Cloudflare CDN to get pro optimization for your images.', 'photo-gallery');
        ?>
      </p>
      <div class="bwg-cdn-tools">
        <p class="bwg-main-text">
          <b><?php esc_html_e('30%', 'photo-gallery'); ?></b>
          <?php esc_html_e('higher PageSpeed score', 'photo-gallery'); ?>
          <img src="<?php echo esc_url(BWG()->plugin_url . '/images/icons/higher_PageSpeed_score.svg'); ?>"
               alt="higher PageSpeed score">
        </p>
        <p class="bwg-main-text">
          <b><?php esc_html_e('50%', 'photo-gallery'); ?></b>
          <?php esc_html_e('faster load times', 'photo-gallery'); ?>
          <img src="<?php echo esc_url(BWG()->plugin_url . '/images/icons/faster_load_times.svg'); ?>"
               alt="faster load times">
        </p>
        <p class="bwg-main-text">
          <b><?php esc_html_e('275', 'photo-gallery'); ?></b>
          <?php esc_html_e('caching locations worldwide', 'photo-gallery'); ?>
          <img src="<?php echo esc_url(BWG()->plugin_url . '/images/icons/caching_locations_worldwide.svg'); ?>"
               alt="caching locations worldwide">
        </p>
      </div>
      <?php
      if ( $upgrade_link ) {
        ?>
      <div class="bwg-button-container-right">
        <a class="bwg-green-button" href="<?php echo esc_url($upgrade_link); ?>">
          <?php esc_html_e('Upgrade to Pro', 'photo-gallery'); ?>
        </a>
      </div>
        <?php
      }
      ?>
      <div class="bwg-cdn-benefits-main-container">
        <div class="bwg-cdn-benefits-head">
          <p class="bwg-cdn-benefits-head-title">
            <?php esc_html_e('Benefits of Cloudflare Enterprise', 'photo-gallery'); ?>
          </p>
          <p class="bwg-cdn-style-line"></p>
        </div>
        <p class="bwg-page-main-desc"><?php
          echo wp_kses(__('Enterprise is the highest plan of Cloudflare worth $5000/mo. 10Web partnered with Cloudflare to
                        <br>provide you with all of their benefits within Booster Pro. Read more about how enterprise compares
                         <br>to free and pro ', 'photo-gallery'), array(
                                                                                            'br' => array(),
                                                                                            'a' => array(),
                                                                                          )) . '<a target="_blank" href="https://www.cloudflare.com/plans/#overview">' . esc_html__('Cloudflare pricing plans.', 'photo-gallery') . '</a>'; ?>
        </p>
        <div class="bwg-cdn-benefits">
          <?php
          foreach ( $cloudflare_cdn_benefits as $benefit ) { ?>
            <div class="bwg-cdn-each-benefit">
              <p class="bwg-main-text two-each-benefit-title">
                <?php echo esc_html($benefit['title']) ?>
              </p>
              <p class="bwg-main-text">
                <?php echo wp_kses($benefit['desc'], array( 'br' => array() )); ?>
              </p>
            </div>
          <?php }
          ?>
        </div>
      </div>
    </div>
    </div><?php
  }
}
