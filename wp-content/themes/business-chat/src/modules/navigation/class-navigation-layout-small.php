<?php

namespace SuperbThemesCustomizer\Modules\Navigation;

use SuperbThemesCustomizer\CustomizerControls;

class NavigationLayoutSmall
{
    public function __construct()
    {
        $this->Render();
    }

    public function Render()
    {
?>
        <nav id="primary-site-navigation" class="primary-menu main-navigation clearfix">
            <?php new NavigationMobile(); ?>
            <div class="top-nav-wrapper">
                <div class="content-wrap">
                    <div class="header-content-container">
                        <div class="logo-container">
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

                        <?php new NavigationMenu(); ?>
                    </div>
                </div>
        </nav>
<?php
    }
}
