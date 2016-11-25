<?php

class RonisBT_Banners_Block_Banners extends Mage_Core_Block_Template
{

    public function getBannersCollection()
    {
        $modelStatus = Mage::getModel('ronisbt_banners/source_status');
        $bannersCollection = Mage::getModel('ronisbt_banners/banners')
            ->getCollection()
            ->addFieldToFilter('status', $modelStatus::ENABLED)
            ->setOrder('position', 'ASC')
            ->setPageSize(100);
        return $bannersCollection;
    }

}
