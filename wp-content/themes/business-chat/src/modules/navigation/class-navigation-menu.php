<?php

namespace SuperbThemesCustomizer\Modules\Navigation;

class NavigationMenu
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
        <div class="center-main-menu">
            <?php if (class_exists('WooCommerce')) : ?>
                <div class="wc-nav-content">
                <?php endif; ?>
                <?php
                wp_nav_menu(array(
                    'theme_location'    => 'menu-1',
                    'menu_id'            => 'primary-menu',
                    'menu_class'        => 'pmenu'
                ));
                ?>
                <?php if (class_exists('WooCommerce')) : ?>
                    <div class="cart-header cart-header-desktop">
                        <a class="cart-customlocation" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'business-chat'); ?>">
                            <svg aria-hidden="true" role="img" focusable="false" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M21.9353 20.0337L20.7493 8.51772C20.7003 8.0402 20.2981 7.67725 19.8181 7.67725H4.21338C3.73464 7.67725 3.33264 8.03898 3.28239 8.51523L2.06458 20.0368C1.96408 21.0424 2.29928 22.0529 2.98399 22.8097C3.66874 23.566 4.63999 24.0001 5.64897 24.0001H18.3827C19.387 24.0001 20.3492 23.5747 21.0214 22.8322C21.7031 22.081 22.0361 21.0623 21.9353 20.0337ZM19.6348 21.5748C19.3115 21.9312 18.8668 22.1275 18.3827 22.1275H5.6493C5.16836 22.1275 4.70303 21.9181 4.37252 21.553C4.042 21.1878 3.88005 20.7031 3.92749 20.2284L5.056 9.55014H18.9732L20.0724 20.2216C20.1223 20.7281 19.9666 21.2087 19.6348 21.5748Z" fill="currentColor"></path>
                                <path d="M12.1717 0C9.21181 0 6.80365 2.40811 6.80365 5.36803V8.6138H8.67622V5.36803C8.67622 3.44053 10.2442 1.87256 12.1717 1.87256C14.0992 1.87256 15.6674 3.44053 15.6674 5.36803V8.6138H17.5397V5.36803C17.5397 2.40811 15.1316 0 12.1717 0Z" fill="currentColor"></path>
                            </svg>
                            <span class="cart-icon-number"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
                            <div class="cart-preview">
                                <?php
                                foreach (WC()->cart->get_cart() as $item) {
                                    $product = $item['data'];
                                    echo "<div class='cart-preview-tem'>";
                                    echo "<div class='thumb-container'>";
                                    echo $product->get_image('thumb');
                                    echo "</div>";
                                    echo "" . esc_html($item['quantity']) . ' x ' . esc_html($product->get_title());
                                    echo "<span>";
                                    echo esc_html(get_woocommerce_currency_symbol());
                                    echo "" . esc_html($product->get_price()) . "</span></div>";
                                }
                                ?>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
                <?php if (class_exists('WooCommerce')) : ?>
                </div>
            <?php endif; ?>
        </div>
<?php
    }
}
