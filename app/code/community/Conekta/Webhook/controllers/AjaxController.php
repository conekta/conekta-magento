<?php
class Conekta_Webhook_AjaxController extends Mage_Core_Controller_Front_Action {
  public function listenerAction() {
    $body = @file_get_contents('php://input');
    self::authenticateEvent($body, $_SERVER['HTTP_DIGEST']);
    $event = json_decode($body);
    $charge = $event->data->object;
    sleep(2);
    $charge_id = $charge->metadata->checkout_id;
    $charge_id_matches_format = preg_match('/^[a-z_\-0-9]+$/i', $charge_id);
    if ($charge_id_matches_format) {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $query = "SELECT parent_id FROM " . $resource->getTableName('sales/order_payment') . " WHERE charge_id = '" . $charge_id . "' LIMIT 1";
        $entity_id = $readConnection->fetchOne($query);
    }

    $entity_id_matches_format = preg_match('/^[0-9]+$/i', $entity_id);
    if ($entity_id_matches_format) {
      $query = 'SELECT increment_id FROM ' . $resource->getTableName('sales/order') . " WHERE entity_id = '" . $entity_id . "' LIMIT 1";
      $increment_id = $readConnection->fetchOne($query);
      $order = Mage::getModel('sales/order')->loadByIncrementId($increment_id);
    }

    if ($charge_id_matches_format && $entity_id_matches_format && $event->type == "order.paid" && $order->getId()) {   
      if ($order->hasInvoices() != true) {
        $invoice = $order->prepareInvoice();
        $invoice->register();
        Mage::getModel('core/resource_transaction')
        ->addObject($invoice)
        ->addObject($invoice->getOrder())
        ->save();
        $invoice->sendEmail(true, '');
      }
      $orderStatus = $order->getPayment()->getMethodInstance()->getConfigData('webhook_notification_order_status');
      if (!(strpos($order->getStatus(), $orderStatus) !== false)) {
        $order->addStatusToHistory($orderStatus);
        $order->setData('state', $orderStatus);
        $order->save();
      }
    }
  }

  public function authenticateEvent($body, $digest) {
    $private_key_string = Mage::getStoreConfig('payment/webhook/signature_key');
    if (!empty($private_key_string) && !empty($body)) {
      if (!empty($digest)) {
        $private_key = openssl_pkey_get_private($private_key_string);
        $encrypted_message = base64_decode($digest);
        $sha256_message = "";
        $bool = openssl_private_decrypt($encrypted_message, $sha256_message, $private_key);
        if (hash("sha256", $body) != $sha256_message) {
          throw new Exception("Event not authenticated");
        }
      } else {
        throw new Exception("Empty digest");
      }
    }
  }
}
?>
