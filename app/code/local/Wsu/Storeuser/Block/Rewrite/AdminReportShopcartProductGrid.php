<?php
class Wsu_Storeuser_Block_Rewrite_AdminReportShopcartProductGrid extends Mage_Adminhtml_Block_Report_Shopcart_Product_Grid {
    protected function _prepareCollection() {
        $role       = Mage::getSingleton('storeuser/role');
        $collection = Mage::getResourceModel('reports/quote_collection');
        if (version_compare(Mage::getVersion(), '1.6.0.0', '<')) {
            $collection->prepareForProductsInCarts()->setSelectCountSqlType(Mage_Reports_Model_Mysql4_Quote_Collection::SELECT_COUNT_SQL_TYPE_CART);
        } else {
            $collection->prepareForProductsInCarts()->setSelectCountSqlType(Mage_Reports_Model_Resource_Quote_Collection::SELECT_COUNT_SQL_TYPE_CART);
        }
        if ($role->isPermissionsEnabled()) {
            if (!Mage::helper('storeuser')->isShowingAllProducts()) {
                if ($role->isScopeStore()) {
                    $collection->getSelect()->joinLeft(array(
                        'product_cat' => Mage::getSingleton('core/resource')->getTableName('catalog_category_product')
                    ), 'product_cat.product_id = e.entity_id', array());
                    $collection->getSelect()->where(' product_cat.category_id in (' . join(',', $role->getAllowedCategoryIds()) . ')
                        or product_cat.category_id IS NULL ');
                    $collection->getSelect()->distinct(true);
                }
                if ($role->isScopeWebsite()) {
                    $collection->addStoreFilter($role->getAllowedStoreviewIds());
                }
            }
        }
        $this->setCollection($collection);
        return Mage_Adminhtml_Block_Widget_Grid::_prepareCollection();
    }
    public function getRowUrl($row) {
        $role = Mage::getSingleton('storeuser/role');
        if ($role->isPermissionsEnabled()) {
            $stores = $role->getAllowedStoreviewIds();
            return $this->getUrl('*/catalog_product/edit', array(
                'store' => $stores[0],
                'id' => $row->getEntityId()
            ));
        }
        return parent::getRowUrl($row);
    }
}