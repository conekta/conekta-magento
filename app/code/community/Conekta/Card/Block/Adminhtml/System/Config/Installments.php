<?php
class Conekta_Card_Block_Adminhtml_System_Config_Installments extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $installments = new Varien_Data_Form_Element_Text;
        $data = array(
            'name'      => $element->getName(),
            'html_id'   => $element->getId()
        );
        $installments->setData($data);
        if (is_numeric($element->getValue())) {
            $installments->setValue($element->getValue());
        } else {
            $installments->setValue(300.0);
        }
        $installments->setForm($element->getForm());
        $html = $installments->getElementHtml();
        $javaScript = "
            <script type=\"text/javascript\">
                Event.observe(window, 'load', function() {
                    installments=$('{$element->getHtmlId()}').value;
                    if (isNaN(installments)) {
                        $('{$element->getHtmlId()}').value = '';
                    }
                });
                Event.observe('{$element->getHtmlId()}', 'change', function(){
                    installments=$('{$element->getHtmlId()}').value;
                    if (isNaN(installments)) {
                        $('{$element->getHtmlId()}').value = '';
                    }
                });
            </script>";
        $html .= $javaScript;
        return $html;
    }
}