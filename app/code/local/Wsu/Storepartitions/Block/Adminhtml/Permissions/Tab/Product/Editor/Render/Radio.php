<?php
class Wsu_Storepartitions_Block_Adminhtml_Permissions_Tab_Product_Editor_Render_Radio extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Radio {

    public function renderHeader() {
        $checked = '';
        if ($filter = $this->getColumn()->getFilter()) {
            $checked = $filter->getValue() ? ' checked ' : '';
        }

        $disabled = '';
        if ($this->getColumn()->getDisabled()) {
            $disabled = ' disabled ';
        }
        $html = '<label>';
        $html .= '<input type="radio" ';
        $html .= 'name="' . $this->getColumn()->getHtmlName() . '" ';
        $html .= 'onclick="' . $this->getColumn()->getGrid()->getJsObjectName() . '.checkCheckboxes(this)" ';
        $html .= 'class="checkbox"' . $checked . $disabled . ' ';
        $html .= 'value="' . $this->getColumn()->getRadioValue() . '" ';
        $html .= 'title="'.Mage::helper('adminhtml')->__('Select All') . '"/>';
        $html .= ' <span>'.$this->getColumn()->getHeader().'</span> ';
        $html .= '</label>';

        return $html;
    }

    public function render(Varien_Object $row) {
        $values = $this->getColumn()->getValues();
        $value  = $row->getData($this->getColumn()->getIndex());

        if($this->getColumn()->getRadioValue() == '')
        {
            $checked = !in_array($row->getId(), $values) ? ' checked' : '';
        }
        elseif (is_array($values)) {
            $checked = in_array($row->getId(), $values) ? ' checked' : '';
        } else {
            $checked = ($row->getId() === $this->getColumn()->getValue()) ? ' checked' : '';
        }
        $disabled = '';
        if ($this->getColumn()->getDisabled()) {
            $disabled = ' disabled';
        }

        $html = '<label><input type="radio" name="' . $this->getColumn()->getHtmlName() . '['.$row->getId().']" ';
        $html .= 'value="' . $this->getColumn()->getRadioValue() . '" class="radio"' . $checked . $disabled . ' rawid='.$row->getId().' />';

        $options = $this->getColumn()->getOptions();
        $optionsScope = $this->getColumn()->getOptionsScope();
        if (!empty($options) && is_array($options)) {
            if (isset($options[$value])) {
                $html .= $this->escapeHtml($options[$value]);
            }
        }
        $html .= '</label>';
        return $html;
    }
}