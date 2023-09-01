<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package marketingly
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php 
	if ( ! function_exists( 'wp_body_open' ) ) {
    function wp_body_open() {
        do_action( 'wp_body_open' );
    }
} ?>
	<div id="page" class="site"> 

	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'marketingly' ); ?></a>

			<!--Nav start-->
			<header id="masthead" class="sheader site-header clearfix">
				<nav id="primary-site-navigation" class="primary-menu main-navigation clearfix">

					<a href="#" id="pull" class="smenu-hide toggle-mobile-menu menu-toggle" aria-controls="secondary-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'marketingly' ); ?></a>
					<div class="top-nav-wrapper">
						<div class="content-wrap">
							<div class="logo-container"> 
								<?php if ( has_custom_logo() ) : ?>
								<?php the_custom_logo(); ?>
							<?php else : ?>
							<a class="logofont" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						<?php endif; ?>
					</div>
					<div class="center-main-menu">
						<?php
						wp_nav_menu( array(
							'theme_location'	=> 'menu-1',
							'menu_id'			=> 'primary-menu',
							'menu_class'		=> 'pmenu'
							) );
							?>
						</div>
					</div>
				</div>
			</nav>
			<div class="super-menu clearfix">
				<div class="super-menu-inner">
					

						<?php if ( has_custom_logo() ) : ?>
						<?php the_custom_logo(); ?>
					<?php else : ?>
					<a class="logofont" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				<?php endif; ?>

				<a href="#" id="pull" class="toggle-mobile-menu menu-toggle" aria-controls="secondary-menu" aria-expanded="false"></a>
		</div>
	</div>
	<div id="mobile-menu-overlay"></div>
</header>
<!--Nav end-->
<?php if ( is_front_page() ) : ?>

	<!-- Header img -->
	<?php if ( get_header_image() ) : ?>
	<div class="header-img-activated">
		<div class="bottom-header-wrapper">
			<img src="<?php echo esc_url(( get_header_image()) ); ?>" alt="<?php echo esc_attr(( get_bloginfo( 'title' )) ); ?>" />
		</div>
	<?php endif; ?>
	<!-- / Header img -->

<?php endif; ?>


<div class="content-wrap">
	<?php if ( is_front_page() ) : ?>
	<!-- Upper widgets -->
	<div class="header-widgets-wrapper">
		<?php if ( is_active_sidebar( 'headerwidget-1' ) ) : ?>
		<div class="header-widgets-three header-widgets-left">
			<?php dynamic_sidebar( 'headerwidget-1' ); ?>
		</div>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'headerwidget-2' ) ) : ?>
	<div class="header-widgets-three header-widgets-middle">
		<?php dynamic_sidebar( 'headerwidget-2' ); ?>
	</div>
<?php endif; ?>

<?php if ( is_active_sidebar( 'headerwidget-3' ) ) : ?>
	<div class="header-widgets-three header-widgets-right">
		<?php dynamic_sidebar( 'headerwidget-3' ); ?>				
	</div>
<?php endif; ?>
</div>
<!-- / Upper widgets -->
<?php endif; ?>

</div>

<?php if ( is_front_page() ) : ?>
	<?php if ( get_header_image() ) : ?>
</div>
<?php endif; ?>
<?php endif; ?>

<div id="content" class="site-content clearfix">
	<div class="content-wrap">
