<?php

class RonisBT_Banners_Helper_Data extends Mage_Core_Helper_Abstract
{
    const MODULE_MEDIA = 'ronisbt_banners';

    public function getImagePath($imageName = 0)
    {
        $path = Mage::getBaseDir('media') . DS . self::MODULE_MEDIA;
        if ($imageName) {
            return $path . $imageName;
        } else {
            return $path;
        }
    }

    public function getImageUrl($imageName = 0)
    {
        $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . DS . self::MODULE_MEDIA;
        if ($imageName) {
            return $url . $imageName;
        } else {
            return $url;
        }
    }
}
