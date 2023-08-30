<?php

namespace SuperbThemesCustomizer\Modules\Navigation;

use SuperbThemesCustomizer\CustomizerControls;

class NavigationLayoutBusiness
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
                        <?php
                        $button_text = CustomizerControls::GetSelectedOrDefault(CustomizerControls::NAVIGATION_RIGHTALIGNED_BUTTON_TEXT);
                        $has_button_text = $button_text !== false && !empty($button_text);
                        $has_searchbar = CustomizerControls::GetSelectedOrDefault(CustomizerControls::NAVIGATION_SEARCHBAR_ENABLED) == "1";
                        if ($has_button_text || $has_searchbar) : ?>
                            <div class="header-content-button-container">
                                <?php

                                if ($has_button_text) :
                                    $some_link = CustomizerControls::GetSelectedOrDefault(CustomizerControls::NAVIGATION_RIGHTALIGNED_BUTTON_LINK);
                                    $some_link = $some_link === false || empty($some_link) ? "#" : $some_link;
                                    $some_link_target_blank = CustomizerControls::GetSelectedOrDefault(CustomizerControls::NAVIGATION_RIGHTALIGNED_BUTTON_TARGETBLANK);
                                ?>
                                    <a href="<?php echo esc_url($some_link); ?>" <?php if ($some_link_target_blank == '1') : ?>target="_blank" <?php endif; ?>><?php echo esc_html($button_text); ?></a>
                                <?php endif; ?>

                                <?php if ($has_searchbar) : ?>
                                    <form role="search" method="get" action="<?= esc_url(home_url('/')) ?>" class="navigation-layout-search-bar-wrapper">
                                        <input type="search" placeholder="<?= esc_attr_e("Search", 'business-chat'); ?>" class="navigation-layout-search-bar-input" value="<?= get_search_query(); ?>" name="s" required>
                                        <button class="navigation-layout-search-button" type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 48 48" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none">
                                                    <g>
                                                        <path d="M38.4765585,35.0824459 L47.2970563,43.9029437 C48.2343146,44.840202 48.2343146,46.359798 47.2970563,47.2970563 C46.359798,48.2343146 44.840202,48.2343146 43.9029437,47.2970563 L35.0824459,38.4765585 C31.3872359,41.4324651 26.7000329,43.2 21.6,43.2 C9.6706494,43.2 0,33.5293506 0,21.6 C0,9.6706494 9.6706494,0 21.6,0 C33.5293506,0 43.2,9.6706494 43.2,21.6 C43.2,26.7000329 41.4324651,31.3872359 38.4765585,35.0824459 Z M33.6993263,33.2553386 C36.6100235,30.2344677 38.4,26.1262696 38.4,21.6 C38.4,12.3216162 30.8783838,4.8 21.6,4.8 C12.3216162,4.8 4.8,12.3216162 4.8,21.6 C4.8,30.8783838 12.3216162,38.4 21.6,38.4 C26.1262696,38.4 30.2344677,36.6100235 33.2553386,33.6993263 C33.3185,33.6171808 33.3877017,33.5381858 33.4629437,33.4629437 C33.5381858,33.3877017 33.6171808,33.3185 33.6993263,33.2553386 Z" />
                                                    </g>
                                                </g>
                                            </svg>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
        </nav>
<?php
    }
}
