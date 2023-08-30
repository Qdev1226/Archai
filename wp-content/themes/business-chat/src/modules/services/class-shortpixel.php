<?php

namespace SuperbThemesCustomizer\Modules\Services;

use SuperbThemesCustomizer\CustomizerControls;

class ShortPixelController
{
    const API_URL = 'https://cdn.shortpixel.ai/stsp/to_webp,q_lossy,ret_img/';

    public function __construct()
    {
        if (CustomizerControls::GetSelectedOrDefault(CustomizerControls::SHORTPIXEL_ENABLE) != "1") {
            return;
        }

        add_filter('wp_get_attachment_image_src', array($this, 'AddAPIUrlToUrl'));
        add_filter('wp_content_img_tag', array($this, 'AddAPIUrlToContentImgTags'));
        add_filter('wp_calculate_image_srcset', array($this, 'AddAPIUrlToSrcSet'));
    }

    public function AddAPIUrlToUrl($image)
    {
        if (!isset($image[0]) || strpos($image[0], self::API_URL) !== false) {
            return $image;
        }
        $image[0] = self::API_URL . $image[0];
        return $image;
    }

    public function AddAPIUrlToSrcSet($sources)
    {
        foreach ($sources as &$source) {
            if (strpos($source['url'], self::API_URL) !== false) {
                continue;
            }
            $source['url'] = self::API_URL . $source['url'];
        }
        return $sources;
    }

    public function AddAPIUrlToContentImgTags($filtered_image)
    {
        if (strpos($filtered_image, self::API_URL) !== false) {
            return $filtered_image;
        }
        return str_replace('src="', 'src="' . self::API_URL, $filtered_image);
    }
}
