<?php

class Etailers_Popup_Block_Adminhtml_Newsletter_Subscriber_Renderer_Popup extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        return $row->getData("last_popup_id")
            ? "<span><b>ID {$row->getData("last_popup_id")}</b> - {$row->getData("popup_title")}</span>"
            : '';
    }
}

?>
