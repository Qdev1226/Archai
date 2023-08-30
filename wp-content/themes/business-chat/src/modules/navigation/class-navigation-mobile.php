<?php

namespace SuperbThemesCustomizer\Modules\Navigation;

class NavigationMobile
{
    public function __construct()
    {
        if (!\has_nav_menu('menu-1')) {
            return;
        }
        $this->Render();
    }

    public function Render()
    {
?>
        <a href="#" class="nav-pull smenu-hide toggle-mobile-menu menu-toggle" aria-expanded="false">
            <?php if (has_custom_logo()) : ?>
                <img src="<?php echo esc_url(wp_get_attachment_url(get_theme_mod('custom_logo'))); ?>">
            <?php else : ?>
                <span class="logofont site-title">
                    <?php esc_html(bloginfo('name')); ?>
                </span>
            <?php endif; ?>
            <span class="navigation-icon-nav">
                <svg width="24" height="18" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.33301 1H22.6663" stroke="#2D2D2D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M1.33301 9H22.6663" stroke="#2D2D2D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M1.33301 17H22.6663" stroke="#2D2D2D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </a>
<?php
    }
}
