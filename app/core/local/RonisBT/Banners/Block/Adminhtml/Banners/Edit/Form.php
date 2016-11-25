<?php

class RonisBT_Banners_Block_Adminhtml_Banners_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $helper = Mage::helper('ronisbt_banners');
        $model = Mage::registry('current_banners');

        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array(
                'id' => $this->getRequest()->getParam('id')
            )),
            'method' => 'post',
            'enctype' => 'multipart/form-data'
        ));

        $this->setForm($form);

        $fieldset = $form->addFieldset('banners_form', array('legend' => $helper->__('Banner Information')));

        $fieldset->addField('title', 'text', array(
            'label' => $helper->__('Title'),
            'required' => true,
            'name' => 'title',
        ));

        $fieldset->addField('image', 'image', array(
            'label' => $helper->__('Image'),
            'required' => true,
            'name' => 'image',
        ));

        $fieldset->addField('url', 'text', array(
            'label' => $helper->__('Banner url'),
            'required' => true,
            'name' => 'url',
        ));

        $fieldset->addField('position', 'text', array(
            'label' => $helper->__('Position'),
            'required' => true,
            'class'     => 'required-entry validate-digits',
            'name' => 'position',
        ));

        $fieldset->addField('status', 'select', array(
            'label'     => $helper->__('Status'),
            'values'    => Mage::getModel('ronisbt_banners/source_status')->toArray(),
            'name'      => 'status',
        ));

        $form->setUseContainer(true);

        $formData = array_merge($model->getData(), array('image' => $model->getImage()));
        $form->setValues($formData);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
