<?php

class RonisBT_Banners_Block_Adminhtml_Banners_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        if( ! $row->getImage()) {
            return '';
        }
        $url = Mage::getBaseUrl('media') . DS . $row->getImage();
        $html = "<img src='$url' width='100' height='auto'>";
        return $html;
    }
}
