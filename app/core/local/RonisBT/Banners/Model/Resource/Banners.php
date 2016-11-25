<?php

class RonisBT_Banners_Model_Resource_Banners extends Mage_Core_Model_Mysql4_Abstract
{

    public function _construct()
    {
        $this->_init('ronisbt_banners/table_banners', 'banner_id');
    }

}
