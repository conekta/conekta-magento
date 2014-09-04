<?php
class Conekta_Oxxo_Block_Adminhtml_System_Config_Days extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $days = new Varien_Data_Form_Element_Text;
        $data = array(
            'name'      => $element->getName(),
            'html_id'   => $element->getId()
        );
        $days->setData($data);
        if (is_numeric($element->getValue())) {
            $days->setValue($element->getValue());
        } else {
            $days->setValue(30);
        }
        $days->setForm($element->getForm());
        $html = $days->getElementHtml();
        $javaScript = "
            <script type=\"text/javascript\">
                Event.observe(window, 'load', function() {
                    days=$('{$element->getHtmlId()}').value;
                    if (isNaN(days)) {
                        $('{$element->getHtmlId()}').value = '';
                    }
                });
                Event.observe('{$element->getHtmlId()}', 'change', function(){
                    days=$('{$element->getHtmlId()}').value;
                    if (isNaN(days)) {
                        $('{$element->getHtmlId()}').value = '';
                    }
                });
            </script>";
        $html .= $javaScript;
        return $html;
    }
}