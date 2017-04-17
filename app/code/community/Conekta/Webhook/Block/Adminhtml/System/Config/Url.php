<?php
include_once(Mage::getBaseDir('lib') . DS . 'Conekta' . DS . 'lib' . DS . 'Conekta.php');
class Conekta_Webhook_Block_Adminhtml_System_Config_Url extends Mage_Adminhtml_Block_System_Config_Form_Field
{
  protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
  {
    if (!class_exists('Conekta\Conekta')) {
      error_log("Plugin miss Conekta PHP lib dependency. Clone the repository using 'git clone --recursive git@github.com:conekta/conekta-magento.git'", 0);
      throw new Mage_Payment_Model_Info_Exception("Payment module unavailable. Please contact system administrator.");
    }
    $privateKey = Mage::getStoreConfig('payment/webhook/privatekey');
    \Conekta\Conekta::setApiKey($privateKey);
    \Conekta\Conekta::setApiVersion("2.0.0");
    \Conekta\Conekta::setPlugin("Magento 1");
    \Conekta\Conekta::setLocale(Mage::app()->getLocale()->getLocaleCode());

    $url = new Varien_Data_Form_Element_Text;
    $data = array(
      'name'      => $element->getName(),
      'html_id'   => $element->getId()
      );
    $url->setData($data);
    $webhook_url = Mage::getBaseUrl() . "index.php/webhook/ajax/listener";

    $elementValue = $element->getValue();
    if (!empty($elementValue)) {
      $url_string = $element->getValue();
    } else {
      $url_string = $webhook_url;
    }

    $url->setValue($url_string);

    $events = array("events" => array("charge.paid"), "production_enabled" => 1, "development_enabled" => 1);
    $error = false;
    $error_message = null;
    if (!empty($privateKey)) {
      try {
        $different = true;
        $webhooks = \Conekta\Webhook::where();
        $urls = array();

        foreach ($webhooks as $webhook) {
           array_push($urls, $webhook->webhook_url);
        }

       if (!in_array($url_string, $urls)){
          $webhook = \Conekta\Webhook::create(array_merge(array("url"=>$url_string), $events));
       } 
      } catch (\Conekta\ErrorList $e){
        $error = true;
        $error_message = $e->details[0]->message;
      }
    }

    $url->setForm($element->getForm());
    $html = $url->getElementHtml();
    $javaScript = "
    <script type=\"text/javascript\">
    Event.observe(window, 'load', function() {
      alert('". $error_message ."');
    });
    </script>";
    if($error) {
      $html .= $javaScript;
    }
    return $html;
  }
}
