<?php

class RonisBT_Banners_Model_Banners extends Mage_Core_Model_Abstract
{

    public function _construct()
    {
        parent::_construct();
        $this->_init('ronisbt_banners/banners');
    }

    public function beforeSave($time)
    {
        $this->setCreatedAt($time);
    }
}
