<?php

class Etailers_Popup_Block_Popup extends Mage_Core_Block_Template
{
    const REGEXP_EXPRESSION = '/[a-z0-9\-\.\*\_\\\(\)\[\]]+/i';

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getPopup()
    {
        if (!$this->hasData('popup')) {
            $this->setData('popup', Mage::registry('popup'));
        }
        return $this->getData('popup');

    }

    public function getPopupActive()
    {
        $preview = $this->getRequest()->getParam('popup');
        $popupId = $this->getRequest()->getParam('popupid');
        if ($preview == "preview" && !empty($popupId)) {
            $popup = Mage::getModel('popup/popup')->load($popupId);
        } else {
            //$popup = Mage::getModel('popup/popup')->getPopupActive();
            $popup = $this->getActivePopup();
        }


        if ($popup && $popup->getId()) {
            // If there is a link, process template tag
            if (!empty($popup["popup_url"])) {
                $helper = Mage::helper('cms');
                $processor = $helper->getPageTemplateProcessor();
                $popup["popup_url"] = $processor->filter($popup["popup_url"]);
            }

            // If there is a content, process template tag
            if (!empty($popup["popup_content_html"])) {
                $helper = Mage::helper('cms');
                $processor = $helper->getPageTemplateProcessor();
                $popup["popup_content_html"] = $processor->filter($popup["popup_content_html"]);
            }

            $popupId = $popup["popup_id"];

            $storeId = Mage::app()->getStore()->getId();

            //Check Cookie
            $popupState = Mage::getSingleton('core/cookie')->get('popup_container_' . $popupId);

            // If there's any cookie or new promo or preview mode
            if ((!$popup->getMaxShowTimes()
                || empty($popupState['shown_times'])
                || $popupState['shown_times'] < $popup->getMaxShowTimes())
                || $this->getRequest()->getParam('popup') == 'preview') {

                if (!$this->getRequest()->getParam('popup')) {
                    //Mage::getSingleton('core/cookie')->set('cookie_popup_' . $storeId, $popupId, (3600 * 24 * 7));
                }
                if (!empty($popup["popup_image"])) {
                    $pathImage = Mage::getBaseDir('media') . "/popup/" . $popup["popup_image"];
                    if (file_exists($pathImage)) {
                        $imageObj = new Varien_Image($pathImage);
                        $popup['popup_image_width'] = $imageObj->getOriginalWidth();
                        $popup['popup_image_height'] = $imageObj->getOriginalHeight();
                    }
                }

                return $popup;
            }
        } else {
            return false;
        }
    }

    public function getActivePopup()
    {
        /**
         * @var $collection Etailers_Popup_Model_Mysql4_Popup_Collection
         */
        $collection = Mage::getResourceModel('popup/popup_collection');
        $resource = $collection->getResource();
        $adapter = $collection->getConnection();
        $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, Mage::app()->getStore()->getId());
        $collection->getSelect()
            ->join(
                array('popup_store' => 'popup_store'),
                $adapter->quoteInto('popup_store.popup_id = main_table.popup_id
                    AND popup_store.store_id IN (?)', $storeIds),
                array('store_id')
            )
            ->order('popup_store.store_id DESC');

        $collection->addFieldToFilter('status', Etailers_Popup_Model_Status::STATUS_ENABLED);
        $collection->addFieldToFilter('popup_date_start', array('lteq' => date("Y-m-d")));
        $collection->addFieldToFilter(
            array('popup_date_end', 'popup_date_end'),
            array(
                array('gteq' => date("Y-m-d")),
                array('null' => true))
        );
        if ($campaign = $this->getCampaign()){
            $collection->addFieldToFilter(
                array('utm_campaign', 'utm_campaign'),
                array($campaign, array('null' => true)));
        } else {
            //$collection->addFieldToFilter('utm_campaign', array('null' => true));
        }

        while ($popup = $collection->fetchItem()){
            if ($popup->getUrlExpression()){
                $uri = $this->getRequest()->getRequestUri();
                if (preg_match(self::REGEXP_EXPRESSION, $popup->getUrlExpression())
                    && preg_match($popup->getUrlExpression(), $uri)){
                    $resource->unserializeFields($popup);
                    return $popup;
                }
            } else {
                $resource->unserializeFields($popup);
                return $popup;
            }
        }
        return false;
    }

    public function getFormActionUrl()
    {
        return $this->getUrl('popup/index/ajaxsubscribe', array('_secure' => true));
    }

    /**
     * @param $popup Etailers_Popup_Model_Popup
     * @return string
     */

    public function getPopupJson($popup)
    {
        $serializableAttributes = array(
            'popup_id',
            'utm_campaign',
            'max_show_times',
            'first_show_delay',
            'min_show_interval',
            'popup_date_start',
            'popup_date_end',
            'fancybox_settings',
            'is_collapsed',
            'can_collapse',
            'can_close',
            'popup_url'
        );

        return json_encode($popup->toArray($serializableAttributes), JSON_NUMERIC_CHECK);

        return $popup->toJson($serializableAttributes);
    }

    public function getCampaign()
    {
        parse_str($this->getRequest()->getCookie('sourceInfo'), $sourceInfo);
        return $this->getRequest()->getParam('c')
            ? $this->getRequest()->getParam('c')
            : ($this->getRequest()->getParam('utm_campaign')
                ? $this->getRequest()->getParam('utm_campaign')
                : (isset($sourceInfo['c'])
                    ? $sourceInfo['c']
                    : (isset($sourceInfo['utm_campaign'])
                        ? $sourceInfo['utm_campaign']
                        : null))
            );
    }
}
