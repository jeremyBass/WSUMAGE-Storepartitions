<?php
class Wsu_Storepartitions_Block_Rewrite_AdminhtmlWidgetInstanceEdit extends Mage_Widget_Block_Adminhtml_Widget_Instance_Edit {
    protected function _preparelayout() {
        parent::_prepareLayout();
        $role = Mage::getSingleton('storepartitions/role');
        if ($role->isPermissionsEnabled()) {
            $widgetInstance = Mage::registry('current_widget_instance');
            // checking if we have permissions to edit this widget
            if ($widgetInstance->getId() && is_array($widgetInstance->getStoreIds()) && !array_intersect($widgetInstance->getStoreIds(), $role->getAllowedStoreviewIds())) {
                Mage::app()->getResponse()->setRedirect(Mage::getUrl('*/*'));
            }
            if (!$widgetInstance->getStoreIds() || array_diff($widgetInstance->getStoreIds(), $role->getAllowedStoreviewIds())) {
                $this->_removeButton('delete');
            }
        }
        return $this;
    }
}