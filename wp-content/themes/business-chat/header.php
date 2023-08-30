<?php

use SuperbThemesCustomizer\CustomizerControls;
use SuperbThemesCustomizer\Modules\Navigation;

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package business-chat
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php
	if (function_exists('wp_body_open')) {
		wp_body_open();
	} else {
		do_action('wp_body_open');
	}
	?>
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e('Skip to content', 'business-chat'); ?></a>

	<header id="masthead" class="sheader site-header clearfix">
		<?php
		switch (CustomizerControls::GetSelectedOrDefault(CustomizerControls::NAVIGATION_LAYOUT_STYLE)) {
			case CustomizerControls::NAVIGATION_LAYOUT_CHOICE_LARGE:
				new Navigation\NavigationLayoutSmall();
				break;
			case CustomizerControls::NAVIGATION_LAYOUT_CHOICE_BUSINESS:
				new Navigation\NavigationLayoutBusiness();
				break;
			case CustomizerControls::NAVIGATION_LAYOUT_CHOICE_SMALL:
			default:
				new Navigation\NavigationLayoutSmall();
				break;
		}
		?>
		<div class="super-menu clearfix menu-offconvas-mobile-only">
			<div class="super-menu-inner">
				<div class="header-content-container">
					<div class="mob-logo-wrap">
						<?php if (has_custom_logo()) : ?>
							<div class="logo-container-img-wrapper">
							<?php endif; ?>
							<?php if (has_custom_logo()) : ?>
								<?php the_custom_logo(); ?>
							<?php endif; ?>
							<?php if (has_custom_logo()) : ?>
								<div class="logo-container-img-wrapper-text">
								<?php endif; ?>

								<a class="logofont site-title" href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
								<?php if (CustomizerControls::GetSelectedOrDefault(CustomizerControls::SITE_IDENTITY_HIDE_TAGLINE) != '1') : ?>
									<p class="logodescription site-description"><?php bloginfo('description'); ?></p>
								<?php else : ?>
								<?php endif; ?>

								<?php if (has_custom_logo()) : ?>
								</div>
							</div>
						<?php endif; ?>
					</div>


					<?php
					if (\has_nav_menu('menu-1')) : ?>
						<a href="#" class="nav-pull toggle-mobile-menu menu-toggle" aria-expanded="false">
							<span class="navigation-icon">
								<svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M1.33301 1H22.6663" stroke="#2D2D2D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
									<path d="M1.33301 9H22.6663" stroke="#2D2D2D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
									<path d="M1.33301 17H22.6663" stroke="#2D2D2D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
								</svg>
							</span>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</header>

	<?php
	// Maybe use MetaSlider
	$business_chat_overwrite_slider_shortcode = get_theme_mod('header_metaslider_overwrite');
	if (!empty($business_chat_overwrite_slider_shortcode) && shortcode_exists("metaslider") && has_shortcode($business_chat_overwrite_slider_shortcode, "metaslider")) {
		if (is_front_page()) {
			business_chat_theme_metaslider_header_output();
		}
		// else use the default theme header
	} else {
		if (is_front_page()) {
			business_chat_default_theme_header_output();
		}
	} ?>

	<div class="content-wrap">


		<!-- Upper widgets -->
		<?php if (is_active_sidebar('headerwidget-1')) : ?>
			<div class="header-widgets-wrapper">
				<?php dynamic_sidebar('headerwidget-1'); ?>
			</div>
		<?php endif; ?>
		<!-- / Upper widgets -->


	</div>


	<?php
	function business_chat_default_theme_header_output()
	{
	?>
		<!-- Header img -->
		<?php if (get_header_image()) : ?>
			<div class="bottom-header-wrapper">
				<div class="bottom-header-text">
					<?php if (get_theme_mod('header_img_text')) : ?>
						<div class="content-wrap">
							<div class="bottom-header-title"><?php echo wp_kses_post(get_theme_mod('header_img_text')) ?></div>
						</div>
					<?php endif; ?>
					<?php if (get_theme_mod('header_img_text_tagline')) : ?>
						<div class="content-wrap">
							<div class="bottom-header-paragraph"><?php echo wp_kses_post(get_theme_mod('header_img_text_tagline')) ?></div>
						</div>
					<?php endif; ?>
					<!-- Button start -->
					<div class="header-button-wrap">
						<?php if (get_theme_mod('header_img_button_text')) : ?><a href="<?php echo wp_kses_post(get_theme_mod('header_img_button_link')) ?>"><?php echo wp_kses_post(get_theme_mod('header_img_button_text')) ?></a><?php endif; ?>
					</div>
					<!-- Button end -->

				</div>
				<img src="<?php echo esc_url((get_header_image())); ?>" alt="<?php echo esc_attr((get_bloginfo('title'))); ?>" />
			</div>
		<?php endif; ?>
		<!-- / Header img -->
	<?php
	}

	function business_chat_theme_metaslider_header_output()
	{
		echo '<!-- MetaSlider Header -->';
		echo do_shortcode(get_theme_mod('header_metaslider_overwrite'));
		echo '<!-- / MetaSlider Header -->';
	}
	?>