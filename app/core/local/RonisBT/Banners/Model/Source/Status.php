<?php

class RonisBT_Banners_Model_Source_Status
{

    const ENABLED = '1';
    const DISABLED = '0';

    /**
     * Options getter
     * @return array
     */

    public function toOptionArray()
    {
        return array(
            array('value' => self::ENABLED, 'label' => 'Enabled'),
            array('value' => self::DISABLED, 'label' => 'Disabled'),
        );
    }

    /**
     * Get options in "key-value" format
     * @return array
     */

    public function toArray()
    {
        return array(
            self::ENABLED  => 'Enabled',
            self::DISABLED => 'Disabled'
        );
    }

}
