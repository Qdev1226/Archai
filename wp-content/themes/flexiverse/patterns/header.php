<?php
 /**
  * Title: Header
  * Slug: flexiverse/header
  * Categories: flexiverse
  */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"12px","right":"10%","bottom":"12px","left":"10%"},"blockGap":"0px"},"color":{"background":"#0c0e0c"}},"layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group has-background" style="background-color:#0c0e0c;padding-top:12px;padding-right:10%;padding-bottom:12px;padding-left:10%"><!-- wp:group {"align":"full","className":"mobile-aligncenter","layout":{"type":"flex","allowOrientation":false,"justifyContent":"space-between"}} -->
<div class="wp-block-group alignfull mobile-aligncenter"><!-- wp:group {"className":"mobile-aligncenter"} -->
<div class="wp-block-group mobile-aligncenter"><!-- wp:social-links {"iconColor":"white","iconColorValue":"#ffffff","size":"has-small-icon-size","style":{"spacing":{"margin":{"top":"0px","bottom":"0px"},"blockGap":{"top":"20px","left":"20px"}}},"className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"}} -->
<ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-logos-only" style="margin-top:0px;margin-bottom:0px"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"twitter"} /-->

<!-- wp:social-link {"url":"#","service":"youtube"} /-->

<!-- wp:social-link {"url":"#","service":"instagram"} /-->

<!-- wp:social-link {"url":"#","service":"wordpress"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"white","className":"mobile-aligncenter","layout":{"type":"flex","flexWrap":"wrap"}} -->
<div class="wp-block-group mobile-aligncenter has-white-color has-text-color has-link-color"><!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group"><!-- wp:image {"id":8088,"sizeSlug":"full","linkDestination":"none","style":{"color":{}},"className":"is-style-default vertical-aligncenter"} -->
<figure class="wp-block-image size-full is-style-default vertical-aligncenter"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/icon-phone.png" alt="" class="wp-image-8088"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"small"} -->
<p class="has-small-font-size" style="font-style:normal;font-weight:500"> <?php echo esc_html__( '+123 456 789', 'flexiverse' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:image {"id":8087,"sizeSlug":"full","linkDestination":"none","style":{"color":{}},"className":"is-style-default vertical-aligncenter"} -->
<figure class="wp-block-image size-full is-style-default vertical-aligncenter"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/icon-mail.png" alt="" class="wp-image-8087"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"small"} -->
<p class="has-small-font-size" style="font-style:normal;font-weight:500"><a href="mailto:support@example.com"><?php echo esc_html__( 'support@example.com', 'flexiverse' ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:image {"id":8087,"sizeSlug":"full","linkDestination":"none","style":{"color":{}},"className":"is-style-default vertical-aligncenter"} -->
<figure class="wp-block-image size-full is-style-default vertical-aligncenter"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/icon-location.png" alt="" class="wp-image-8087"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"small"} -->
<p class="has-small-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html__( 'Sumter,SC 29150', 'flexiverse' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"12px","right":"10%","bottom":"12px","left":"10%"},"blockGap":"0px"}},"backgroundColor":"background","className":"has-background-background-color","layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group has-background-background-color has-background" style="padding-top:12px;padding-right:10%;padding-bottom:12px;padding-left:10%"><!-- wp:group {"align":"full","style":{"spacing":{"padding":{"bottom":"10px","top":"10px","right":"0px","left":"0px"}}},"layout":{"type":"flex","justifyContent":"space-between"}} -->
<div class="wp-block-group alignfull" style="padding-top:10px;padding-right:0px;padding-bottom:10px;padding-left:0px"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"className":"mobile-aligncenter","layout":{"type":"flex"}} -->
<div class="wp-block-group mobile-aligncenter"><!-- wp:site-logo {"width":49,"shouldSyncIcon":true,"style":{"color":{"duotone":["#000000","#9eef7c"]}}} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"},"fontSize":"tiny"} -->
<div class="wp-block-group has-tiny-font-size"><!-- wp:site-title {"style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}},"typography":{"fontStyle":"normal","fontWeight":"500","fontSize":"1.6rem","letterSpacing":"2px","textTransform":"capitalize"}}} /-->

<!-- wp:paragraph {"fontSize":"extra-small"} -->
<p class="has-extra-small-font-size"><?php echo esc_html__( 'For Every Business', 'flexiverse' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|70"}},"className":"mobile-media-alignjustify","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group mobile-media-alignjustify"><!-- wp:navigation {"ref":37,"textColor":"foreground","overlayBackgroundColor":"background","overlayTextColor":"foreground","layout":{"type":"flex","setCascadingProperties":true,"justifyContent":"left"},"style":{"spacing":{"blockGap":"var:preset|spacing|60"},"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"small"} /-->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"textColor":"foreground","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-foreground-color has-text-color wp-element-button" style="padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)"><?php echo esc_html__( 'Get A Quote', 'flexiverse' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->