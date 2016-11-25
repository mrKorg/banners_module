<?php

class RonisBT_Banners_Helper_Image extends Mage_Core_Helper_Abstract
{
    const XML_NODE_PRODUCT_BASE_IMAGE_WIDTH = 'catalog/product_image/base_width';
    const XML_NODE_PRODUCT_SMALL_IMAGE_WIDTH = 'catalog/product_image/small_width';
    const XML_NODE_PRODUCT_MAX_DIMENSION = 'catalog/product_image/max_dimension';

    /**
     * Current model
     *
     * @var RonisBT_Banners_Model_Banners_Image
     */
    protected $_model;

    /**
     * Scheduled for resize image
     *
     * @var bool
     */
    protected $_scheduleResize = false;

    /**
     * Scheduled for rotate image
     *
     * @var bool
     */
    protected $_scheduleRotate = false;

    /**
     * Angle
     *
     * @var int
     */
    protected $_angle;

    /**
     * Current Banner
     *
     * @var RonisBT_Banners_Model_Banners_Image
     */
    protected $_banner;

    /**
     * Image File
     *
     * @var string
     */
    protected $_imageFile;

    /**
     * Image Placeholder
     *
     * @var string
     */
    protected $_placeholder;

    /**
     * Reset all previous data
     *
     * @return RonisBT_Banners_Helper_Image
     */
    protected function _reset()
    {
        $this->_model = null;
        $this->_scheduleResize = false;
        $this->_scheduleRotate = false;
        $this->_angle = null;
        $this->_banner = null;
        $this->_imageFile = null;
        return $this;
    }

    /**
     * Initialize Helper to work with Image
     *
     * @param RonisBT_Banners_Model_Banners_Image $banner
     * @param string $attributeName
     * @param mixed $imageFile
     * @return RonisBT_Banners_Helper_Image
     */
    public function init($banner, $attributeName, $imageFile=null)
    {
        $this->_reset();
        $this->_setModel(Mage::getModel('ronisbt_banners/banners_image'));
        $this->_getModel()->setDestinationSubdir($attributeName);
        $this->setBanner($banner);

        if ($imageFile) {
            $this->setImageFile($imageFile);
        } else {
            // add for work original size
            $this->_getModel()->setBaseFile($this->getBanner()->getData($this->_getModel()->getDestinationSubdir()));
        }
        return $this;
    }

    /**
     * Schedule resize of the image
     * $width *or* $height can be null - in this case, lacking dimension will be calculated.
     *
     * @see RonisBT_Banners_Model_Banners_Image
     * @param int $width
     * @param int $height
     * @return RonisBT_Banners_Helper_Image
     */
    public function resize($width, $height = null)
    {
        $this->_getModel()->setWidth($width)->setHeight($height);
        $this->_scheduleResize = true;
        return $this;
    }

    /**
     * Set image quality, values in percentage from 0 to 100
     *
     * @param int $quality
     * @return RonisBT_Banners_Helper_Image
     */
    public function setQuality($quality)
    {
        $this->_getModel()->setQuality($quality);
        return $this;
    }

    /**
     * Guarantee, that image picture width/height will not be distorted.
     * Applicable before calling resize()
     * It is true by default.
     *
     * @see RonisBT_Banners_Model_Banners_Image
     * @param bool $flag
     * @return RonisBT_Banners_Helper_Image
     */
    public function keepAspectRatio($flag)
    {
        $this->_getModel()->setKeepAspectRatio($flag);
        return $this;
    }

    /**
     * Guarantee, that image will have dimensions, set in $width/$height
     * Applicable before calling resize()
     * Not applicable, if keepAspectRatio(false)
     *
     * $position - TODO, not used for now - picture position inside the frame.
     *
     * @see RonisBT_Banners_Model_Banners_Image
     * @param bool $flag
     * @param array $position
     * @return RonisBT_Banners_Helper_Image
     */
    public function keepFrame($flag, $position = array('center', 'middle'))
    {
        $this->_getModel()->setKeepFrame($flag);
        return $this;
    }

    /**
     * Guarantee, that image will not lose transparency if any.
     * Applicable before calling resize()
     * It is true by default.
     *
     * $alphaOpacity - TODO, not used for now
     *
     * @see RonisBT_Banners_Model_Banners_Image
     * @param bool $flag
     * @param int $alphaOpacity
     * @return RonisBT_Banners_Helper_Image
     */
    public function keepTransparency($flag, $alphaOpacity = null)
    {
        $this->_getModel()->setKeepTransparency($flag);
        return $this;
    }

    /**
     * Guarantee, that image picture will not be bigger, than it was.
     * Applicable before calling resize()
     * It is false by default
     *
     * @param bool $flag
     * @return RonisBT_Banners_Helper_Image
     */
    public function constrainOnly($flag)
    {
        $this->_getModel()->setConstrainOnly($flag);
        return $this;
    }

    /**
     * Set color to fill image frame with.
     * Applicable before calling resize()
     * The keepTransparency(true) overrides this (if image has transparent color)
     * It is white by default.
     *
     * @see RonisBT_Banners_Model_Banners_Image
     * @param array $colorRGB
     * @return RonisBT_Banners_Helper_Image
     */
    public function backgroundColor($colorRGB)
    {
        // assume that 3 params were given instead of array
        if (!is_array($colorRGB)) {
            $colorRGB = func_get_args();
        }
        $this->_getModel()->setBackgroundColor($colorRGB);
        return $this;
    }

    /**
     * Rotate image into specified angle
     *
     * @param int $angle
     * @return RonisBT_Banners_Helper_Image
     */
    public function rotate($angle)
    {
        $this->setAngle($angle);
        $this->_getModel()->setAngle($angle);
        $this->_scheduleRotate = true;
        return $this;
    }

    /**
     * Set placeholder
     *
     * @param string $fileName
     * @return void
     */
    public function placeholder($fileName)
    {
        $this->_placeholder = $fileName;
    }

    /**
     * Get Placeholder
     *
     * @return string
     */
    public function getPlaceholder()
    {
        if (!$this->_placeholder) {
            $attr = $this->_getModel()->getDestinationSubdir();
            $this->_placeholder = 'images/catalog/product/placeholder/'.$attr.'.jpg';
        }
        return $this->_placeholder;
    }

    /**
     * Return Image URL
     *
     * @return string
     */
    public function __toString()
    {
        try {
            $model = $this->_getModel();
            if ($this->getImageFile()) {
                $model->setBaseFile($this->getImageFile());
            } else {
                $model->setBaseFile($this->getBanner()->getData($model->getDestinationSubdir()));
            }

            if ($model->isCached()) {
                return $model->getUrl();
            } else {
                if ($this->_scheduleRotate) {
                    $model->rotate($this->getAngle());
                }

                if ($this->_scheduleResize) {
                    $model->resize();
                }

                $url = $model->saveFile()->getUrl();
            }
        } catch (Exception $e) {
            $url = Mage::getDesign()->getSkinUrl($this->getPlaceholder());
        }
        return $url;
    }

    /**
     * Set current Image model
     *
     * @param RonisBT_Banners_Model_Banners_Image $model
     * @return RonisBT_Banners_Helper_Image
     */
    protected function _setModel($model)
    {
        $this->_model = $model;
        return $this;
    }

    /**
     * Get current Image model
     *
     * @return RonisBT_Banners_Model_Banners_Image
     */
    protected function _getModel()
    {
        return $this->_model;
    }

    /**
     * Set Rotation Angle
     *
     * @param int $angle
     * @return RonisBT_Banners_Helper_Image
     */
    protected function setAngle($angle)
    {
        $this->_angle = $angle;
        return $this;
    }

    /**
     * Get Rotation Angle
     *
     * @return int
     */
    protected function getAngle()
    {
        return $this->_angle;
    }

    /**
     * Set current Banner
     *
     * @param RonisBT_Banners_Model_Banners_Image $banner
     * @return RonisBT_Banners_Helper_Image
     */
    protected function setBanner($banner)
    {
        $this->_banner = $banner;
        return $this;
    }

    /**
     * Get current Banner
     *
     * @return RonisBT_Banners_Model_Banners_Image
     */
    protected function getBanner()
    {
        return $this->_banner;
    }

    /**
     * Set Image file
     *
     * @param string $file
     * @return RonisBT_Banners_Helper_Image
     */
    protected function setImageFile($file)
    {
        $this->_imageFile = $file;
        return $this;
    }

    /**
     * Get Image file
     *
     * @return string
     */
    protected function getImageFile()
    {
        return $this->_imageFile;
    }

    /**
     * Retrieve size from string
     *
     * @param string $string
     * @return array|bool
     */
    protected function parseSize($string)
    {
        $size = explode('x', strtolower($string));
        if (sizeof($size) == 2) {
            return array(
                'width' => ($size[0] > 0) ? $size[0] : null,
                'heigth' => ($size[1] > 0) ? $size[1] : null,
            );
        }
        return false;
    }

    /**
     * Retrieve original image width
     *
     * @return int|null
     */
    public function getOriginalWidth()
    {
        return $this->_getModel()->getImageProcessor()->getOriginalWidth();
    }

    /**
     * Retrieve original image height
     *
     * @deprecated
     * @return int|null
     */
    public function getOriginalHeigh()
    {
        return $this->getOriginalHeight();
    }

    /**
     * Retrieve original image height
     *
     * @return int|null
     */
    public function getOriginalHeight()
    {
        return $this->_getModel()->getImageProcessor()->getOriginalHeight();
    }

    /**
     * Retrieve Original image size as array
     * 0 - width, 1 - height
     *
     * @return array
     */
    public function getOriginalSizeArray()
    {
        return array(
            $this->getOriginalWidth(),
            $this->getOriginalHeight()
        );
    }

    /**
     * Check - is this file an image
     *
     * @param string $filePath
     * @return bool
     * @throws Mage_Core_Exception
     */
    public function validateUploadFile($filePath) {
        $maxDimension = Mage::getStoreConfig(self::XML_NODE_PRODUCT_MAX_DIMENSION);
        $imageInfo = getimagesize($filePath);
        if (!$imageInfo) {
            Mage::throwException($this->__('Disallowed file type.'));
        }

        if ($imageInfo[0] > $maxDimension || $imageInfo[1] > $maxDimension) {
            Mage::throwException($this->__('Disalollowed file format.'));
        }

        $_processor = new Varien_Image($filePath);
        return $_processor->getMimeType() !== null;
    }

}
