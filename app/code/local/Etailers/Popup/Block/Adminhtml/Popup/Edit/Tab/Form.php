<?php

class Etailers_Popup_Block_Adminhtml_Popup_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('popup_form', array('legend'=>Mage::helper('popup')->__('Popup Information')));

      $yesNoOptions = array(
          array(
              'value'     => 1,
              'label'     => Mage::helper('adminhtml')->__('Yes'),
          ),
          array(
              'value'     => 0,
              'label'     => Mage::helper('adminhtml')->__('No'),
          ),
      );

       /**
		* Check is single store mode
		*/
		if (!Mage::app()->isSingleStoreMode()) {
            $isElementDisabled = false;
			$fieldset->addField('store_id', 'multiselect', array(
				'name'      => 'stores[]',
				'label'     => Mage::helper('cms')->__('Store View'),
				'title'     => Mage::helper('cms')->__('Store View'),
				'required'  => true,
				'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
				'disabled'  => $isElementDisabled
			));
		}
		else {
			$fieldset->addField('store_id', 'hidden', array(
				'name'      => 'stores[]',
				'value'     => Mage::app()->getStore(true)->getId()
			));
		}
		
      $fieldset->addField('popup_title', 'text', array(
          'label'     => Mage::helper('popup')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'style'     => 'width:700px;',
          'name'      => 'popup_title',
      ));

	  $fieldset->addField('popup_description', 'textarea', array(
          'label'     => Mage::helper('popup')->__('Description'),
          'style'     => 'width:700px; height:100px;',
          'name'      => 'popup_description',
      ));

      $fieldset->addField('utm_campaign', 'text', array(
          'label'     => Mage::helper('popup')->__('Campaign Expression'),
          'name'      => 'utm_campaign',
          'note'      => Mage::helper('popup')->__('Display for specific campaign only'),
      ));

      $fieldset->addField('url_expression', 'text', array(
          'label'     => Mage::helper('popup')->__('Match URL Expression'),
          'name'      => 'url_expression',
      ));

      $fieldset->addField('full_action_name', 'text', array(
          'label'     => Mage::helper('popup')->__('Match Full Action Name Expression'),
          'name'      => 'full_action_name',
          'note'      => Mage::helper('popup')->__('cms_page_view,catalog_category_view,catalog_product_view,checkout_cart_index'),
      ));

      $fieldset->addField('show_after', 'text', array(
          'label'     => Mage::helper('popup')->__('Show After Popups'),
          'name'      => 'show_after',
          'note'      => Mage::helper('popup')->__('Comma separated popup ids to display after'),
      ));

      $fieldset->addField('max_show_times', 'text', array(
          'label'     => Mage::helper('popup')->__('Maximum Expositions'),
          'name'      => 'max_show_times',
          'class'     => 'validate-number',
      ));

      $fieldset->addField('first_show_delay', 'text', array(
          'label'     => Mage::helper('popup')->__('First Exposition Delay, s'),
          'class'     => 'validate-number',
          'name'      => 'first_show_delay',
      ));

      $fieldset->addField('min_show_interval', 'text', array(
          'label'     => Mage::helper('popup')->__('Minimum Exposition Interval, s'),
          'class'     => 'validate-number',
          'name'      => 'min_show_interval',
      ));
            
        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
		$fieldset->addField('popup_date_start', 'date', array(
			'name'   => 'popup_date_start',
			'label'  => Mage::helper('popup')->__('Start Date'),
			'title'  => Mage::helper('popup')->__('Start Date'),
			'image'  => $this->getSkinUrl('images/grid-cal.gif'),
			'required'  => true,
			'format'       => $dateFormatIso
		));

	   $fieldset->addField('popup_date_end', 'date', array(
			'name'   => 'popup_date_end',
			'label'  => Mage::helper('popup')->__('End Date'),
			'title'  => Mage::helper('popup')->__('End Date'),
			'image'  => $this->getSkinUrl('images/grid-cal.gif'),
			'required'  => true,
			'format'       => $dateFormatIso
		));
		
      $fieldset->addField('popup_coupon', 'checkbox', array(
          'label'     => Mage::helper('popup')->__('Coupon'),
          'name'      => 'popup_coupon',
          'value'     => 1,
      ));
      
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('popup')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('popup')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('popup')->__('Disabled'),
              ),
          ),
      ));

      $fieldset->addField('is_collapsed', 'select', array(
          'label'     => Mage::helper('popup')->__('Is Collapsed'),
          'name'      => 'is_collapsed',
          'values'    => $yesNoOptions
      ));

      $fieldset->addField('can_collapse', 'select', array(
          'label'     => Mage::helper('popup')->__('Can Collapse'),
          'name'      => 'can_collapse',
          'values'    => $yesNoOptions
      ));

      $fieldset->addField('can_close', 'select', array(
          'label'     => Mage::helper('popup')->__('Can Close'),
          'name'      => 'can_close',
          'values'    => $yesNoOptions
      ));
     
     /*
     $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('popup')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
		
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('popup')->__('Content'),
          'title'     => Mage::helper('popup')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     */
     
      if ( Mage::getSingleton('adminhtml/session')->getPopupData() )
      {   $popupData = Mage::getSingleton('adminhtml/session')->getPopupData();
          $form->setValues($popupData);
          $form->getElement('popup_coupon')->setIsChecked($popupData["popup_coupon"]);
          Mage::getSingleton('adminhtml/session')->setPopupData(null);
      } elseif ( Mage::registry('popup_data') ) {
          $popupData = Mage::registry('popup_data');
          $form->setValues($popupData->getData());
          $form->getElement('popup_coupon')->setIsChecked($popupData["popup_coupon"]);
      }
      return parent::_prepareForm();
  }
}
