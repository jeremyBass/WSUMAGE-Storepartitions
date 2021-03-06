<?php
class Wsu_Storepartitions_Block_Rewrite_AdminPermissionsButtons extends Mage_Adminhtml_Block_Permissions_Buttons {
    protected function _prepareLayout() {
        $duplicateUrl    = $this->getUrl('storepartitions/adminhtml_role/duplicate/', array(
            'rid' => $this->getRequest()->getParam('rid')
        ));
        $onclick         = 'window.location.href=\'' . $duplicateUrl . '\'';
        $duplicateButton = $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
            'label' => Mage::helper('adminhtml')->__('Duplicate Role'),
            'onclick' => $onclick,
            'class' => 'add'
        ));
        $this->setChild('duplicateButton', $duplicateButton);
        return parent::_prepareLayout();
    }
    public function getBackButtonHtml() {
        return $this->getChildHtml('duplicateButton') . parent::getBackButtonHtml();
    }
    public function getResetButtonHtml() {
        return $this->getChildHtml('resetButton');
    }
    public function getSaveButtonHtml() {
        return $this->getChildHtml('saveButton');
    }
    public function getDeleteButtonHtml() {
        if (intval($this->getRequest()->getParam('rid')) == 0) {
            return;
        }
        return $this->getChildHtml('deleteButton');
    }
    public function getUser() {
        return Mage::registry('user_data');
    }
}