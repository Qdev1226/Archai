<?php

namespace SuperbThemesCustomizer;

use SuperbThemesCustomizer\Utils\CustomizerItem;
use SuperbThemesCustomizer\Utils\CustomizerType;
use SuperbThemesCustomizer\CustomizerPanels;
use SuperbThemesCustomizer\CustomizerSections;

class CustomizerControls
{
    const GENERAL_BOXMODE = 'general_layout_boxmode';
    const GENERAL_BOXMODE_HIDE_MOBILE = 'general_layout_boxmode_hide_mobile';
    const GENERAL_BORDER_RADIUS_ELEMENTS = '--business-chat-element-border-radius';
    const GENERAL_BORDER_RADIUS_BUTTONS = '--business-chat-button-border-radius';

    const HEADER_METASLIDER_SHORTCODE = 'header_metaslider_overwrite';

    const HEADER_TITLE = 'header_img_text';
    const HEADER_TAGLINE = 'header_img_text_tagline';
    const HEADER_BUTTON_TEXT = 'header_img_button_text';
    const HEADER_BUTTON_LINK = 'header_img_button_link';

    const SITE_IDENTITY_LOGO_HEIGHT = '--business-chat-logo-height';
    const SITE_IDENTITY_HIDE_TAGLINE = 'navigation_hide_tagline';

    const NAVIGATION_LAYOUT_STYLE = 'navigation_layout_style';
    const NAVIGATION_LAYOUT_CHOICE_SMALL = 'navigation_layout_style_choice_small';
    const NAVIGATION_LAYOUT_CHOICE_LARGE = 'navigation_layout_style_choice_large';
    const NAVIGATION_LAYOUT_CHOICE_BUSINESS = 'navigation_layout_style_choice_business';
    const NAVIGATION_RIGHTALIGNED_BUTTON_TEXT = 'navigation_large_rightalignedbutton_text';
    const NAVIGATION_RIGHTALIGNED_BUTTON_LINK = 'navigation_large_rightalignedbutton_link';
    const NAVIGATION_RIGHTALIGNED_BUTTON_TARGETBLANK = 'navigation_large_rightalignedbutton_link_targetblank';
    const NAVIGATION_SEARCHBAR_ENABLED = 'navigation_searchbar_enabled';

    ////
    const BLOGFEED_COLUMNS_STYLE = 'blogfeed_columns_style';
    //
    const BLOGFEED_ONE_COLUMNS = 'blogfeed_onecolumn';
    const BLOGFEED_TWO_COLUMNS = 'blogfeed_twocolumn';
    const BLOGFEED_THREE_COLUMNS = 'blogfeed_three_columns';
    const BLOGFEED_TWO_COLUMNS_MASONRY = 'blogfeed_twocolumn_masonry';
    const BLOGFEED_THREE_COLUMNS_MASONRY = 'blogfeed_three_colums_masonry';
    /////
    const BLOGFEED_HIDE_SIDEBAR = 'blogfeed_show_sidebar';
    const BLOGFEED_HIDE_BYLINE_IMAGE = 'blogfeed_hide_authorimage';
    const BLOGFEED_SHOW_READMORE_BUTTON = 'blogfeed_show_readmore_button';
    ////
    const BLOGFEED_FEATURED_IMAGE_STYLE = 'blogfeed_featured_image_style';
    //
    const BLOGFEED_FEATURED_IMAGE_CHOICE_FULL_IMAGE = 'blogfeed_featured_image_style_fullimage';
    const BLOGFEED_FEATURED_IMAGE_CHOICE_COVER_IMAGE = 'blogfeed_featured_image_style_cover';
    const BLOGFEED_FEATURED_IMAGE_CHOICE_FULL_IMAGE_COVER_BLUR = 'blogfeed_featured_image_style_coverblur';
    ////
    const BLOGFEED_FEATURED_IMAGE_PLACEHOLDER = 'blogfeed_featured_image_placeholder';
    //

    ////
    const SINGLE_FEATURED_IMAGE_STYLE = 'SINGLE_featured_image_style';
    //
    const SINGLE_FEATURED_IMAGE_CHOICE_FULL_IMAGE = 'SINGLE_featured_image_style_fullimage';
    const SINGLE_FEATURED_IMAGE_CHOICE_COVER_IMAGE = 'SINGLE_featured_image_style_cover';
    const SINGLE_FEATURED_IMAGE_CHOICE_FULL_IMAGE_COVER_BLUR = 'SINGLE_featured_image_style_coverblur';
    ////
    const SINGLE_HIDE_BYLINE_IMAGE = 'single_hide_bylineauthorimage';
    const SINGLE_HIDE_RELATED_POSTS = 'postpage_show_hide_relatedposts';

    //
    const SHORTPIXEL_ENABLE = 'shortpixel_spst_enable';

    const RANGE_VARIABLE_CONTROLS = array(
        self::SITE_IDENTITY_LOGO_HEIGHT,
    );

    private static $CONTROL_DEFAULTS = array(
        self::SITE_IDENTITY_LOGO_HEIGHT => 65,
        self::BLOGFEED_COLUMNS_STYLE => self::BLOGFEED_TWO_COLUMNS_MASONRY,
        self::BLOGFEED_FEATURED_IMAGE_STYLE => self::BLOGFEED_FEATURED_IMAGE_CHOICE_FULL_IMAGE,
        self::SINGLE_FEATURED_IMAGE_STYLE => self::BLOGFEED_FEATURED_IMAGE_CHOICE_FULL_IMAGE,
        self::NAVIGATION_LAYOUT_STYLE => self::NAVIGATION_LAYOUT_CHOICE_BUSINESS,
        self::BLOGFEED_HIDE_SIDEBAR => "1",
        self::SINGLE_HIDE_RELATED_POSTS => "1",
        self::NAVIGATION_RIGHTALIGNED_BUTTON_TARGETBLANK => "1",
        self::NAVIGATION_SEARCHBAR_ENABLED => "",
        self::GENERAL_BOXMODE => "",
        self::GENERAL_BOXMODE_HIDE_MOBILE => "1",
        self::GENERAL_BORDER_RADIUS_ELEMENTS => 4,
        self::GENERAL_BORDER_RADIUS_BUTTONS => 4,
        self::SITE_IDENTITY_HIDE_TAGLINE => "1",
        self::BLOGFEED_SHOW_READMORE_BUTTON => "1",
        self::SHORTPIXEL_ENABLE => "",
        self::SINGLE_HIDE_BYLINE_IMAGE => "1",
        self::BLOGFEED_HIDE_BYLINE_IMAGE => "1"
    );

    public function __construct()
    {

        /*
        */

        /*
        *
        * SHORTPIXEL
        *
        */
        // Requires >= WP 6.0 
        global $wp_version;
        if (version_compare($wp_version, '6') >= 0) {
            new CustomizerItem(self::SHORTPIXEL_ENABLE, array(
                "type" => CustomizerType::CONTROL_CHECKBOX,
                "label" => __('Enable Image Optimization', 'business-chat'),
                "description" => __('When this setting is enabled, images on your website will be automatically optimized using ShortPixel and delivered as the modern image format .webp through their free CDN.', 'business-chat'),
                "section" => CustomizerSections::SHORTPIXEL,
                "default" => self::$CONTROL_DEFAULTS[self::SHORTPIXEL_ENABLE]
            ));
        }
        /*
        */

        /*
        *
        * GENERAL
        *
        */
        new CustomizerItem(self::GENERAL_BOXMODE, array(
            "type" => CustomizerType::CONTROL_CHECKBOX,
            "label" => __('Boxed Layout', 'business-chat'),
            "description" => __('When this setting is enabled, elements on the website will be boxed.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::GENERAL,
            "default" => self::$CONTROL_DEFAULTS[self::GENERAL_BOXMODE]
        ));

        new CustomizerItem(self::GENERAL_BOXMODE_HIDE_MOBILE, array(
            "type" => CustomizerType::CONTROL_CHECKBOX,
            "label" => __('Disable Boxed Layout on Mobile', 'business-chat'),
            "description" => __('When this setting is enabled, and Boxed Layout is enabled, the boxed layout will not be applied on mobile devices and other low-width screens.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::GENERAL,
            "default" => self::$CONTROL_DEFAULTS[self::GENERAL_BOXMODE_HIDE_MOBILE],
            "conditions" => array(
                self::GENERAL_BOXMODE => array(
                    "1"
                )
            )
        ));


        /*
        *
        * HEADER METASLIDER
        *
        */
        new CustomizerItem(self::HEADER_METASLIDER_SHORTCODE, array(
            "type" => CustomizerType::CONTROL_TEXT,
            "label" => __('MetaSlider Shortcode', 'business-chat'),
            "description" => __('Add your MetaSlider slider shortcode in this field to use the Slider as your header. This will be used instead of the default theme header.', 'business-chat'),
            "section" => CustomizerPanels::HEADER . CustomizerSections::HEADER_METASLIDER,
            "priority" => 1,
        ));

        /*
        *
        * HEADER DEFAULT
        *
        */
        /* Header */
        new CustomizerItem(self::HEADER_TITLE, array(
            "type" => CustomizerType::CONTROL_TEXT,
            "label" => __('Title', 'business-chat'),
            "description" => __('The title text displayed in your header.', 'business-chat'),
            "section" => CustomizerPanels::HEADER . CustomizerSections::HEADER_DEFAULT,
        ));
        new CustomizerItem(self::HEADER_TAGLINE, array(
            "type" => CustomizerType::CONTROL_TEXT,
            "label" => __('Tagline', 'business-chat'),
            "description" => __('The tagline text displayed in your header.', 'business-chat'),
            "section" => CustomizerPanels::HEADER . CustomizerSections::HEADER_DEFAULT,
        ));
        new CustomizerItem(self::HEADER_BUTTON_TEXT, array(
            "type" => CustomizerType::CONTROL_TEXT,
            "label" => __('Button Text', 'business-chat'),
            "description" => __('The button text displayed in your header.', 'business-chat'),
            "section" => CustomizerPanels::HEADER . CustomizerSections::HEADER_DEFAULT,
        ));
        new CustomizerItem(self::HEADER_BUTTON_LINK, array(
            "type" => CustomizerType::CONTROL_TEXT,
            "label" => __('Button Link', 'business-chat'),
            "description" => __('The link used by the button in your header.', 'business-chat'),
            "section" => CustomizerPanels::HEADER . CustomizerSections::HEADER_DEFAULT,
        ));

        /*
        *
        * SITE IDENTITY
        *
        */
        new CustomizerItem(self::SITE_IDENTITY_LOGO_HEIGHT, array(
            "type" => CustomizerType::CONTROL_RANGE,
            "label" => __('Logo Height', 'business-chat'),
            "description" => __('Sets the height limit for the logo image, if once is selected.', 'business-chat'),
            "section" => 'title_tagline',
            "priority" => 1,
            "default" => self::$CONTROL_DEFAULTS[self::SITE_IDENTITY_LOGO_HEIGHT],
            "range" => array(
                'min' => 25,
                'max' => 200,
                'step' => 1
            )
        ));

        new CustomizerItem(self::SITE_IDENTITY_HIDE_TAGLINE, array(
            "type" => CustomizerType::CONTROL_CHECKBOX,
            "label" => __('Hide Tagline Only', 'business-chat'),
            "section" => 'title_tagline',
            "default" => self::$CONTROL_DEFAULTS[self::SITE_IDENTITY_HIDE_TAGLINE]
        ));

        /*
        *
        * NAVIGATION
        *
        */
        /* Layout */

        new CustomizerItem(self::NAVIGATION_LAYOUT_STYLE, array(
            "type" => CustomizerType::CONTROL_RADIO_IMAGE,
            "label" => __('Navigation Layout', 'business-chat'),
            "description" => __('Select the layout of the navigation area on your website.', 'business-chat'),
            "priority" => 1,
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::NAVIGATION,
            "default" => self::$CONTROL_DEFAULTS[self::NAVIGATION_LAYOUT_STYLE],
            "choices" => array(
                self::NAVIGATION_LAYOUT_CHOICE_SMALL =>  '<svg xmlns="http://www.w3.org/2000/svg" width="119.958" height="37.94" viewBox="0 0 119.958 37.94"><title>' . esc_html__("Small Navigation Layout", "business-chat") . '</title><g transform="translate(-49.021 -37.125)"><rect width="30.966" height="8.753" transform="translate(57.387 44.969)" /><rect width="9.966" height="3.753" transform="translate(151 47.469)" /><rect width="9.966" height="3.753" transform="translate(137 47.469)" /><rect width="9.966" height="3.753" transform="translate(123 47.469)" /><rect width="9.966" height="3.753" transform="translate(109 47.469)" /><path d="M373.5,57.034H254.566v37.94H374.524V57.034ZM256.559,92.981V59.027H372.532V92.981Z" transform="translate(-205.545 -19.909)"></path></g></svg>',
                self::NAVIGATION_LAYOUT_CHOICE_BUSINESS =>  '<svg xmlns="http://www.w3.org/2000/svg" width="119.958" height="37.94" viewBox="0 0 119.958 37.94"><title>' . esc_html__("Business Navigation Layout", "business-chat") . '</title><g transform="translate(-49.021 -37.125)"><rect width="19.966" height="8.753" transform="translate(57.387 44.969)"></rect><rect width="19.966" height="8.753" transform="translate(141 44.969)"></rect><rect width="9.966" height="3.753" transform="translate(120 47.469)"></rect><rect width="9.966" height="3.753" transform="translate(105 47.469)"></rect><rect width="9.966" height="3.753" transform="translate(89 47.469)"></rect><path d="M373.5,57.034H254.566v37.94H374.524V57.034ZM256.559,92.981V59.027H372.532V92.981Z" transform="translate(-205.545 -19.909)"></path></g></svg>',
            )
        ));

        new CustomizerItem(self::NAVIGATION_RIGHTALIGNED_BUTTON_TEXT, array(
            "type" => CustomizerType::CONTROL_TEXT,
            "label" => __('Right-Aligned Button Text', 'business-chat'),
            "description" => __('If the Full Navigation Layout is active, sets the text of the button in the top right side of the navigation.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::NAVIGATION,
            "priority" => 1,
            "conditions" => array(
                self::NAVIGATION_LAYOUT_STYLE => array(
                    self::NAVIGATION_LAYOUT_CHOICE_BUSINESS
                )
            )
        ));

        new CustomizerItem(self::NAVIGATION_RIGHTALIGNED_BUTTON_LINK, array(
            "type" => CustomizerType::CONTROL_TEXT,
            "label" => __('Right-Aligned Button Link', 'business-chat'),
            "description" => __('If the Full Navigation Layout is active, sets the link of the button in the top right side of the navigation.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::NAVIGATION,
            "priority" => 1,
            "conditions" => array(
                self::NAVIGATION_LAYOUT_STYLE => array(
                    self::NAVIGATION_LAYOUT_CHOICE_BUSINESS
                )
            )
        ));
        new CustomizerItem(self::NAVIGATION_RIGHTALIGNED_BUTTON_TARGETBLANK, array(
            "type" => CustomizerType::CONTROL_CHECKBOX,
            "label" => __('Open Link in new Window/Tab', 'business-chat'),
            "description" => __('When this setting is enabled, the link of the button will be opened in a new window/tab when clicked.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::NAVIGATION,
            "priority" => 1,
            "default" => self::$CONTROL_DEFAULTS[self::NAVIGATION_RIGHTALIGNED_BUTTON_TARGETBLANK],
            "conditions" => array(
                self::NAVIGATION_LAYOUT_STYLE => array(
                    self::NAVIGATION_LAYOUT_CHOICE_BUSINESS
                )
            )
        ));

        new CustomizerItem(self::NAVIGATION_SEARCHBAR_ENABLED, array(
            "type" => CustomizerType::CONTROL_CHECKBOX,
            "label" => __('Search Bar & Button', 'business-chat'),
            "description" => __('When this setting is enabled, a search button and bar will be added to the navigation layout.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::NAVIGATION,
            "default" => self::$CONTROL_DEFAULTS[self::NAVIGATION_SEARCHBAR_ENABLED],
            "conditions" => array(
                self::NAVIGATION_LAYOUT_STYLE => array(
                    self::NAVIGATION_LAYOUT_CHOICE_BUSINESS
                )
            )
        ));


        /*
        *
        * SIDEBAR
        *
        */
        /* Layout */
        new CustomizerItem(self::BLOGFEED_HIDE_SIDEBAR, array(
            "type" => CustomizerType::CONTROL_CHECKBOX,
            "label" => __('Hide Sidebar on Blog Feed, Search Page and Archive Pages', 'business-chat'),
            "description" => __('Enabling this setting will hide the sidebar on the blog feed, search page and archive pages and use the full width of the page for the page content.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::SIDEBAR,
            "default" => self::$CONTROL_DEFAULTS[self::BLOGFEED_HIDE_SIDEBAR]
        ));


        /*
        *
        * BLOG FEED
        *
        */
        /* Layout */
        new CustomizerItem(self::BLOGFEED_COLUMNS_STYLE, array(
            "type" => CustomizerType::CONTROL_RADIO_IMAGE,
            "label" => __('Blog Feed Column Layout', 'business-chat'),
            "description" => __('Select the layout of the columns on your blog feed.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::BLOG,
            "default" => self::$CONTROL_DEFAULTS[self::BLOGFEED_COLUMNS_STYLE],
            "choices" => array(
                self::BLOGFEED_ONE_COLUMNS => '<svg xmlns="http://www.w3.org/2000/svg" width="119.958" height="119.939" viewBox="0 0 119.958 119.939"><title>' . esc_html__("1-Column Layout", "business-chat") . '</title><g transform="translate(-154 -253)"><g transform="translate(-100.545 196.091)"><rect width="76.966" height="33.753" transform="translate(275.933 66.878)" /><rect width="73.583" height="1.984" transform="translate(275.933 104.646)" /><rect width="65.932" height="1.984" transform="translate(275.933 111.672)" /><rect width="76.966" height="33.753" transform="translate(275.933 122.027)" /><rect width="73.583" height="1.984" transform="translate(275.933 159.795)" /><rect width="65.932" height="1.984" transform="translate(275.933 166.821)" /><path d="M373.5,57.034H254.566v119.94H374.524V57.034ZM256.559,174.981V59.027H372.532V174.981Z" /></g></g></svg>',
                self::BLOGFEED_TWO_COLUMNS_MASONRY => '<svg width="120" height="120" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg"><title>' . esc_html__("2-Column Masonry Layout", "business-chat") . '</title><g><rect x="1" y="1" width="118" height="118" rx="3" fill="none" stroke-width="2"/><rect x="6" y="6" width="51" height="20" rx="4" /><rect x="6" y="30" width="28" height="4" rx="2" /><rect x="6" y="38" width="16" height="4" rx="2" /><rect x="63" y="78" width="51" height="20" rx="4" /><rect x="63" y="102" width="28" height="4" rx="2" /><rect x="63" y="110" width="16" height="4" rx="2" /><rect x="6" y="50" width="51" height="48" rx="4" /><rect x="6" y="102" width="28" height="4" rx="2" /><rect x="6" y="110" width="16" height="4" rx="2" /><rect x="63" y="6" width="51" height="48" rx="4" /><rect x="63" y="58" width="28" height="4" rx="2" /><rect x="63" y="66" width="16" height="4" rx="2" /></g></svg>',
                self::BLOGFEED_THREE_COLUMNS_MASONRY => '<svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg"><title>' . esc_html__("3-Column Masonry Layout", "business-chat") . '</title><g><rect x="1" y="1" width="118" height="118" rx="3" fill="none" stroke-width="2"/><rect x="6" y="6" width="32" height="20" rx="4" /><rect x="6" y="30" width="28" height="4" rx="2" /><rect x="6" y="38" width="16" height="4" rx="2" /><rect x="82" y="6" width="32" height="20" rx="4" /><rect x="82" y="30" width="28" height="4" rx="2" /><rect x="82" y="38" width="16" height="4" rx="2" /><rect x="6" y="50" width="32" height="48" rx="4" /><rect x="6" y="102" width="28" height="4" rx="2" /><rect x="6" y="110" width="16" height="4" rx="2" /><rect x="82" y="50" width="32" height="48" rx="4" /><rect x="82" y="102" width="28" height="4" rx="2" /><rect x="82" y="110" width="16" height="4" rx="2" /><rect x="44" y="6" width="32" height="48" rx="4" /><rect x="44" y="58" width="28" height="4" rx="2" /><rect x="44" y="66" width="16" height="4" rx="2" /><rect x="44" y="78" width="32" height="20" rx="4" /><rect x="44" y="102" width="28" height="4" rx="2" /><rect x="44" y="110" width="16" height="4" rx="2" /></g></svg>'
            )
        ));

        new CustomizerItem(self::BLOGFEED_SHOW_READMORE_BUTTON, array(
            "type" => CustomizerType::CONTROL_CHECKBOX,
            "label" => __('Show "Continue reading" Button', 'business-chat'),
            "description" => __('Enabling this setting will add a "Continue reading" button below every blog post excerpt, if "Show Full Posts" is not enabled.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::BLOG,
            "default" => self::$CONTROL_DEFAULTS[self::BLOGFEED_SHOW_READMORE_BUTTON]
        ));

        new CustomizerItem(self::BLOGFEED_HIDE_BYLINE_IMAGE, array(
            "type" => CustomizerType::CONTROL_CHECKBOX,
            "label" => __('Hide Author Image from Byline', 'business-chat'),
            "description" => __('Enabling this setting will hide the author image from the byline.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::BLOG,
            "default" => self::$CONTROL_DEFAULTS[self::BLOGFEED_HIDE_BYLINE_IMAGE]
        ));

        new CustomizerItem(self::BLOGFEED_FEATURED_IMAGE_STYLE, array(
            "type" => CustomizerType::CONTROL_RADIO,
            "label" => __('Featured Image Layout', 'business-chat'),
            "description" => __('Select the layout of the featured images on your blog feed.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::BLOG,
            "default" => self::$CONTROL_DEFAULTS[self::BLOGFEED_FEATURED_IMAGE_STYLE],
            "choices" => array(
                self::BLOGFEED_FEATURED_IMAGE_CHOICE_FULL_IMAGE => "Full Image",
                self::BLOGFEED_FEATURED_IMAGE_CHOICE_COVER_IMAGE => "Scale to fit Recommended Size",
                self::BLOGFEED_FEATURED_IMAGE_CHOICE_FULL_IMAGE_COVER_BLUR => "Keep Full Image, But Fill Background to Recommended Size"
            )
        ));

        new CustomizerItem(self::BLOGFEED_FEATURED_IMAGE_PLACEHOLDER, array(
            "type" => CustomizerType::CONTROL_CHECKBOX,
            "label" => __('Display Placeholder Featured Image', 'business-chat'),
            "description" => __('Enabling this setting will display a placeholder featured image for all posts that do not have a featured image set.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::BLOG,
            "default" => 0
        ));


        /*
        *
        * SINGLE / POSTS & PAGES / POSTS / PAGES
        *
        */
        /* Layout */
        new CustomizerItem(self::SINGLE_FEATURED_IMAGE_STYLE, array(
            "type" => CustomizerType::CONTROL_RADIO,
            "label" => __('Featured Image Layout', 'business-chat'),
            "description" => __('Select the layout of the featured images on your blog feed.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::SINGLE,
            "default" => self::$CONTROL_DEFAULTS[self::SINGLE_FEATURED_IMAGE_STYLE],
            "choices" => array(
                self::SINGLE_FEATURED_IMAGE_CHOICE_FULL_IMAGE => "Full Image",
                self::SINGLE_FEATURED_IMAGE_CHOICE_COVER_IMAGE => "Scale to fit Recommended Size",
                self::SINGLE_FEATURED_IMAGE_CHOICE_FULL_IMAGE_COVER_BLUR => "Keep Full Image, But Fill Background to Recommended Size"
            )
        ));

        new CustomizerItem(self::SINGLE_HIDE_BYLINE_IMAGE, array(
            "type" => CustomizerType::CONTROL_CHECKBOX,
            "label" => __('Hide Author Image from Byline', 'business-chat'),
            "description" => __('Enabling this setting will hide the author image from the byline.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::SINGLE,
            "default" => self::$CONTROL_DEFAULTS[self::SINGLE_HIDE_BYLINE_IMAGE]
        ));

        new CustomizerItem(self::SINGLE_HIDE_RELATED_POSTS, array(
            "type" => CustomizerType::CONTROL_CHECKBOX,
            "label" => __('Hide Related Posts', 'business-chat'),
            "description" => __('Enabling this setting will hide the related posts section.', 'business-chat'),
            "section" => CustomizerPanels::LAYOUT . CustomizerSections::SINGLE,
            "default" => self::$CONTROL_DEFAULTS[self::SINGLE_HIDE_RELATED_POSTS]
        ));
    }

    public static function OverwriteDefault($control, $value)
    {
        self::$CONTROL_DEFAULTS[$control] = $value;
    }

    public static function GetSelectedOrDefault($control)
    {
        $theme_mod = \get_theme_mod($control);
        if (($theme_mod || empty($theme_mod)) && $theme_mod !== false) {
            return $theme_mod;
        }

        return self::GetDefault($control);
    }

    public static function GetDefault($control)
    {
        if (isset(self::$CONTROL_DEFAULTS[$control])) {
            return self::$CONTROL_DEFAULTS[$control];
        }
        // No default for control found
        return false;
    }
}
