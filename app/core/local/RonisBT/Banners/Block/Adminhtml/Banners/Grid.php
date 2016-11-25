<?php

class RonisBT_Banners_Block_Adminhtml_Banners_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId("cmsBannersGrid");
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('ronisbt_banners/banners')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $helper = Mage::helper('ronisbt_banners');

        $this->addColumn('banner_id', array(
            'header'   => $helper->__('Banner ID'),
            'index'    => 'banner_id',
            'width'    => '30',
            'type'     => 'number'
        ));

        $this->addColumn('title', array(
            'header'   => $helper->__('Title'),
            'index'    => 'title',
            'type'     => 'text',
        ));

        $this->addColumn('image', array(
            'header'   => $helper->__('Image'),
            'index'    => 'image',
            'type'     => 'text',
            'sortable' => false,
            'filter'   => false,
            'renderer' => 'RonisBT_Banners_Block_Adminhtml_Banners_Renderer_Image'
        ));

        $this->addColumn('url', array(
            'header'   => $helper->__('Url'),
            'index'    => 'url',
            'type'     => 'text',
        ));

        $this->addColumn('status', array(
            'header'   => $helper->__('Status'),
            'index'    => 'status',
            'type'     => 'options',
            'options'  => Mage::getModel('ronisbt_banners/source_status')->toArray(),
            'width'    => '30'
        ));

        $this->addColumn('position', array(
            'header'   => $helper->__('Position'),
            'index'    => 'position',
            'type'     => 'number',
            'width'    => '30'
        ));

        $this->addColumn('created_at', array(
            'header'   => $helper->__('Created at'),
            'index'    => 'created_at',
            'type'     => 'date',
            'width'    => '30'
        ));

        $this->addColumn('updated_at', array(
            'header'   => $helper->__('Updated at'),
            'index'    => 'updated_at',
            'type'     => 'date',
            'width'    => '30'
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('banner_id');
        $this->getMassactionBlock()->setFormFieldName('banners');

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => $this->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
        ));
        return $this;
    }

    public function getRowUrl($model)
    {
        return $this->getUrl('*/*/edit', array(
            'id' => $model->getId(),
        ));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
