<?php

namespace SuperbThemesCustomizer\Utils;

/**
 * Customizer: Add Control: Custom: Radio Image
 *
 * This file demonstrates how to add a custom radio-image control to the Customizer.
 *
 * @package code-examples
 * @copyright Copyright (c) 2015, WordPress Theme Review Team
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 */

/**
 * Create a Radio-Image control
 *
 * This class incorporates code from the Kirki Customizer Framework and from a tutorial
 * written by Otto Wood.
 *
 * The Kirki Customizer Framework, Copyright Aristeides Stathopoulos (@aristath),
 * is licensed under the terms of the GNU GPL, Version 2 (or later).
 *
 * @link https://github.com/reduxframework/kirki/
 * @link http://ottopress.com/2012/making-a-custom-control-for-the-theme-customizer/
 */

/*
   Modified by SuperbThemes
*/
class CustomRadioImageControl extends \WP_Customize_Control
{
    /**
     * Declare the control type.
     *
     * @access public
     * @var string
     */
    public $type = 'radio-image';

    /**
     * Enqueue scripts and styles for the custom control.
     *
     * Scripts are hooked at {@see 'customize_controls_enqueue_scripts'}.
     *
     * Note, you can also enqueue stylesheets here as well. Stylesheets are hooked
     * at 'customize_controls_print_styles'.
     *
     * @access public
     */
    public function enqueue()
    {
        wp_enqueue_script('jquery-ui-button');
    }

    /**
     * Render the control to be displayed in the Customizer.
     */
    public function render_content()
    {
        if (empty($this->choices)) {
            return;
        }

        $svg_kses_args = array(
            'svg'   => array(
                'class' => true,
                'aria-hidden' => true,
                'aria-labelledby' => true,
                'role' => true,
                'xmlns' => true,
                'width' => true,
                'height' => true,
                'viewbox' => true
            ),
            'g'     => array('fill' => true, 'transform' => true),
            'rect'  => array('width' => true, 'height' => true, 'fill' => true, 'transform' => true, 'x' => true, 'y' => true, 'stroke-width' => true, 'rx' => true),
            'title' => array('title' => true),
            'circle' => array('cx' => true, 'cy' => true, 'transform' => true, 'r' => true),
            'path'  => array(
                'd' => true,
                'fill' => true,
                'stroke' => true,
                'transform' => true,
            )
        );

        $name = '_customize-radio-' . $this->id;
?>
        <span class="customize-control-title">
            <?php echo esc_attr($this->label); ?>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
        </span>
        <div id="input_<?php echo esc_attr($this->id); ?>" class="image">
            <?php foreach ($this->choices as $value => $choice) : ?>
                <input class="image-select" type="radio" value="<?php echo esc_attr($value); ?>" id="<?php echo esc_attr($this->id . $value); ?>" name="<?php echo esc_attr($name); ?>" <?php $this->link();
                                                                                                                                                                                        checked($this->value(), $value); ?>>
                <label for="<?php echo esc_attr($this->id . $value); ?>">
                    <?php echo wp_kses($choice, $svg_kses_args); ?>
                </label>
                </input>
            <?php endforeach; ?>
        </div>
        <script>
            jQuery(document).ready(function($) {
                "use strict";
                $('[id="input_<?php echo esc_attr($this->id); ?>"]').buttonset();
            });
        </script>
<?php
    }
}
