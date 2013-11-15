<?php
class Wsu_Storeuser_Helper_Access extends Mage_Core_Helper_Abstract {
    // Sets store_id's for cms object, keeping in mind that unavailable stores are
    // not visible in multiselect, but should not dissapear after save
    public function setCmsObjectStores($object) {
        if (!Mage::getSingleton('storeuser/role')->isPermissionsEnabled()) {
            return;
        }
        $origData            = $object->getOrigData();
        $saveData            = $object->getData();
        $objectIsNew         = empty($origData);
        $allowedStoreviewIds = Mage::getSingleton('storeuser/role')->getAllowedStoreviewIds();
        if ($object instanceof Mage_Cms_Model_Page) {
            $tosaveStoreIds = $saveData['stores'];
            if (!$objectIsNew) {
                $originalStoreIds = $origData['store_id'];
                $preserveStoreIds = array_diff($originalStoreIds, $allowedStoreviewIds);
                $tosaveStoreIds   = array_intersect($tosaveStoreIds, $allowedStoreviewIds);
                $tosaveStoreIds   = array_unique(array_merge($preserveStoreIds, $tosaveStoreIds));
            }
            $object->setData('stores', $tosaveStoreIds);
        } else if ($object instanceof Mage_Cms_Model_Block) {
            $tosaveStoreIds = $saveData['stores'];
            if (!$objectIsNew) {
                $originalStoreIds = $origData['store_id'];
                $preserveStoreIds = array_diff($originalStoreIds, $allowedStoreviewIds);
                $tosaveStoreIds   = array_intersect($tosaveStoreIds, $allowedStoreviewIds);
                $tosaveStoreIds   = array_unique(array_merge($preserveStoreIds, $tosaveStoreIds));
            }
            $object->setData('stores', $tosaveStoreIds);
        } else if ($object instanceof Mage_Widget_Model_Widget_Instance) {
            $tosaveStoreIds = explode(',', $saveData['store_ids']);
            if (!$objectIsNew) {
                $originalStoreIds = explode(',', $origData['store_ids']);
                $preserveStoreIds = array_diff($originalStoreIds, $allowedStoreviewIds);
                $tosaveStoreIds   = array_intersect($tosaveStoreIds, $allowedStoreviewIds);
                $tosaveStoreIds   = array_unique(array_merge($preserveStoreIds, $tosaveStoreIds));
            }
            $object->setData('store_ids', implode(',', $tosaveStoreIds));
        } else if ($object instanceof Mage_Poll_Model_Poll) {
            $tosaveStoreIds = $saveData['store_ids'];
            if (!$objectIsNew) {
                $originalStoreIds = Mage::getModel('poll/poll')->load($object->getPollId())->getStoreIds();
                $preserveStoreIds = array_diff($originalStoreIds, $allowedStoreviewIds);
                $tosaveStoreIds   = array_intersect($tosaveStoreIds, $allowedStoreviewIds);
                $tosaveStoreIds   = array_unique(array_merge($preserveStoreIds, $tosaveStoreIds));
            }
            $object->setData('store_ids', $tosaveStoreIds);
        }
    }
    // If a product is assigned to website(s) not available for current role, we should preserve these assignments
    public function setProductWebsites($product) {
        if (!Mage::getSingleton('storeuser/role')->isPermissionsEnabled()) {
            return;
        }
        $originalProduct    = Mage::getModel('catalog/product')->load($product->getId());
        $allowedWebsites    = Mage::getSingleton('storeuser/role')->getAllowedWebsiteIds();
        $originalWebsiteIds = $originalProduct->getWebsiteIds();
        $toSaveWebsiteIds   = $product->getWebsiteIds();
        $preserveWebsiteIds = array_diff($originalWebsiteIds, $allowedWebsites);
        $toSaveWebsiteIds   = array_unique(array_merge($preserveWebsiteIds, $toSaveWebsiteIds));
        $product->setWebsiteIds($toSaveWebsiteIds);
    }
    /*
     * @return array
     */
    public function getFilteredStoreIds($storeIds) {
        $allowedStoreIds = Mage::getSingleton('storeuser/role')->getAllowedStoreviewIds();
        if (null == $allowedStoreIds) {
            $allowedStoreIds = array();
        }
        if (empty($storeIds)) {
            return $allowedStoreIds;
        }
        if (!empty($allowedStoreIds)) {
            return array_values(array_intersect($storeIds, $allowedStoreIds));
        }
        return $storeIds;
    }
}