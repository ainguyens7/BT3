<?php

namespace App\Services;

use App\Factory\RepositoryFactory;
use App\Helpers\Helpers as Util;

class AssetThemeService
{
    protected $shopMeta;

    public function __construct()
    {
        $this->shopMeta = RepositoryFactory::shopsMetaReposity();
    }

    public function getAssetStyleSheet($shopId = '')
    {
        $vendorStyle = config('stylesheet.vendor');
        $tagVendorStyle = Util::tag_stylesheet($vendorStyle);
        $shopMeta = $this->shopMeta->detail($shopId);
        $style = config('settings.style');
        if (!is_null($shopMeta)) {
            if (isset($shopMeta->style)) {
                if ($shopMeta->style <= 2) {
                    $style = 2;
                } else {
                    $style = 5;
                }
            }
        }
        $styleLink = config("stylesheet.{$style}");
        $tagStyle = Util::tag_stylesheet($styleLink);
        $result = "{$tagVendorStyle} \n {$tagStyle}";
        return $result;
    }

    public function getScript()
    {
        $src = config('script_src.alireview_core');
        $tagScript = Util::tag_script($src);
        return $tagScript;
    }

    public function getCustomizeStyle($shopId = '')
    {
        $settings = Util::getSettings($shopId);

        $style_customize = isset($settings['style_customize']) ? $settings['style_customize'] : config('settings.style_customize');
        $code_css = isset($settings['code_css']) ? $settings['code_css'] : config('settings.code_css');
        $view_part = 'comment.cusstomize-css';

        $args_views = [
            'code_css' => $code_css,
            'style_customize' => $style_customize,
            'settings' => $settings
        ];

        $view = view($view_part, $args_views)->render();
        return $view;
    }

    public function getAlireviewAssetCore($shopId)
    {
        $styleSheet = $this->getAssetStyleSheet($shopId);
        $scriptTag = $this->getScript();
        $customizeStyle = $this->getCustomizeStyle($shopId);

        return "{$scriptTag} \n {$styleSheet} \n {$customizeStyle}";
    }
}