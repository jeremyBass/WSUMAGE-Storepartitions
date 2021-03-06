<?php
class Wsu_Storepartitions_Block_Rewrite_AdminCatalogCategoryEditForm extends Mage_Adminhtml_Block_Catalog_Category_Edit_Form {
    public function _prepareLayout() {
        $role = Mage::getSingleton('storepartitions/role');
        if ($role->isPermissionsEnabled() && !$role->isAllowedToDelete()) {
            $category = $this->getCategory()->setIsDeleteable(false);
            Mage::unregister('category');
            Mage::register('category', $category);
        }
        return parent::_prepareLayout();
    }
} 