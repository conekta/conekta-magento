<?php
include_once(Mage::getBaseDir('lib') . DS . 'Conekta' . DS . 'lib' . DS . 'Conekta.php');
class Conekta_Webhook_Block_Adminhtml_System_Config_Url extends Mage_Adminhtml_Block_System_Config_Form_Field
{
  protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
  {
    if (!class_exists('Conekta')) {
      error_log("Plugin miss Conekta PHP lib dependency. Clone the repository using 'git clone --recursive git@github.com:conekta/conekta-magento.git'", 0);
      throw new Mage_Payment_Model_Info_Exception("Payment module unavailable. Please contact system administrator.");
    }
    Conekta::setApiKey(Mage::getStoreConfig('payment/webhook/privatekey'));
    Conekta::setApiVersion("1.0.0");
    Conekta::setLocale(Mage::app()->getLocale()->getLocaleCode());

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

    $events = array("events" =>
        array("charge.created", "charge.paid", "charge.under_fraud_review",
        "charge.fraudulent", "charge.refunded", "charge.created", "customer.created",
        "customer.updated", "customer.deleted", "webhook.created", "webhook.updated",
        "webhook.deleted", "charge.chargeback.created", "charge.chargeback.updated",
        "charge.chargeback.under_review", "charge.chargeback.lost", "charge.chargeback.won",
        "payout.created", "payout.retrying", "payout.paid_out", "payout.failed",
        "plan.created", "plan.updated", "plan.deleted", "subscription.created",
        "subscription.paused", "subscription.resumed", "subscription.canceled",
        "subscription.expired", "subscription.updated", "subscription.paid",
        "subscription.payment_failed", "payee.created", "payee.updated",
        "payee.deleted", "payee.payout_method.created",
        "payee.payout_method.updated", "payee.payout_method.deleted"));
    $error = false;
    $error_message = null;
    try {
      $different = true;
      $webhooks = Conekta_Webhook::where();
      foreach ($webhooks as $webhook) {
        if (strpos($webhook->webhook_url, $url_string) !== false) {
          $different = false;
        }
      }
      if ($different) {
        $webhook = Conekta_Webhook::create(array_merge(array("url"=>$url_string), $events));
      }
    } catch(Exception $e) {
      $error = true;
      $error_message = $e->getMessage();
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
