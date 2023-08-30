<?php

namespace SuperbThemesCustomizer\Utils;

if (!class_exists('WP_Customize_Control')) {
    return;
}

class RefocusButtonControl extends \WP_Customize_Control
{
    private $buttons;

    public function __construct($manager, $id, $args = array(), $buttons = array())
    {
        parent::__construct($manager, $id, $args);
        $this->buttons = $buttons;
    }

    public function render_content()
    {
?>
        <div class="superbthemes-customizer-refocus-button-wrapper">
            <p>
                <?php esc_html_e("Other customization sections are available for this element. Click the buttons below to switch to other customization sections.", "business-chat"); ?>
            </p>
            <?php
            foreach ($this->buttons as $button) { ?>
                <input id="<?php echo esc_attr($button->GetId()); ?>" type="button" class="superbthemes-customizer-refocus-button button button-primary" value="<?php echo esc_attr($button->GetTitle()); ?>" />
            <?php
            } ?>
        </div>
<?php
    }
}
