<?php
class Conekta_Webhook_AjaxController extends Mage_Core_Controller_Front_Action {
  public function listenerAction() {
    $body = @file_get_contents('php://input');
    $event = json_decode($body);
    $charge = $event->data->object;
    $line_items = $charge->details->line_items;
    sleep(3);
    // search order by charge_id
    $charge_id = $charge->id;
    // check charge_id format
    $charge_id_matches_format = preg_match('/^[a-z_\-0-9]+$/i', $charge_id);
    if ($charge_id_matches_format) {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $query = "SELECT parent_id FROM " . $resource->getTableName('sales/order_payment') . " WHERE charge_id = '" . $charge_id . "' LIMIT 1";
        $entity_id = $readConnection->fetchOne($query);
    }
    // check charge_id format

    // check entity_id format
    $entity_id_matches_format = preg_match('/^[0-9]+$/i', $entity_id);
    if ($entity_id_matches_format) {
      $query = 'SELECT increment_id FROM ' . $resource->getTableName('sales/order') . " WHERE entity_id = '" . $entity_id . "' LIMIT 1";
      $increment_id = $readConnection->fetchOne($query);
      $order = Mage::getModel('sales/order')->loadByIncrementId($increment_id);
    }
    // check entity_id format
    if ($charge_id_matches_format && $entity_id_matches_format && strpos($event->type, "charge.paid") !== false && $order->getId()) {   
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
    } else {
      // Order possible has not been persisted yet. Tell Conekta to retry one hour later.
      header('HTTP/1.0 404 Not Found');
      $this->getResponse()->setHeader('HTTP/1.1','404 Not Found');
      $this->getResponse()->setHeader('Status','404 File not found');
      $this->_forward('defaultNoRoute');
    }
  }
}
?>
