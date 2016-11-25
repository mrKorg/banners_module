<?php

class RonisBT_Banners_Adminhtml_BannersController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('cms/ronisbt_banners');
        $this->_addContent($this->getLayout()->createBlock('ronisbt_banners/adminhtml_banners'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        Mage::register('current_banners', Mage::getModel('ronisbt_banners/banners')->load($id));
        $this->loadLayout()->_setActiveMenu('cms/ronisbt_banners');
        $this->_addContent($this->getLayout()->createBlock('ronisbt_banners/adminhtml_banners_edit'));
        $this->renderLayout();
    }

    public function saveAction()
    {
        $id = $this->getRequest()->getParam('id');

        if ($data = $this->getRequest()->getPost()) {
            try {

                $model = Mage::getModel('ronisbt_banners/banners');
                $imageName = '';
                if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                    $uploader = new Mage_Core_Model_File_Uploader('image');
                    $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $uploader->addValidateCallback(
                        Mage_Core_Model_File_Validator_Image::NAME,
                        new Mage_Core_Model_File_Validator_Image(),
                        "validate"
                    );
                    $uploader->save(Mage::getBaseDir('media') . DS . RonisBT_Banners_Helper_Data::MODULE_MEDIA);
                    $imageName = '/ronisbt_banners' . $uploader->getUploadedFileName();
                } else {
                    if (isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                        $imageName = '';
                    } else {
                        $imageName = $data['image']['value'];
                    }
                }

                $model->setData($data)->setId($id)->setImage($imageName);

                if ($model->isObjectNew()) {
                    $model->beforeSave(Mage::app()->getLocale()->storeDate('', null, true));
                }

                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Banner was saved successfully'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array(
                    'id' => $id
                ));
            }
            return;
        }
        Mage::getSingleton('adminhtml/session')->addError($this->__('Unable to find item to save'));
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                Mage::getModel('ronisbt_banners/banners')->setId($id)->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Banner was deleted successfully'));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $banners = $this->getRequest()->getParam('banners', null);

        if (is_array($banners) && sizeof($banners) > 0) {
            try {
                foreach ($banners as $id) {
                    Mage::getModel('ronisbt_banners/banners')->setId($id)->delete();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d banners have been deleted', sizeof($banners)));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        } else {
            $this->_getSession()->addError($this->__('Please select banners'));
        }
        $this->_redirect('*/*');
    }

    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('ronisbt_banners/adminhtml_banners_grid')->toHtml()
        );
    }

}
