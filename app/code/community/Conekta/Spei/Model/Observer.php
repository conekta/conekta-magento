<?php
include_once(Mage::getBaseDir('lib') . DS . 'Conekta' . DS . 'lib' . DS . 'Conekta.php');
class Conekta_Spei_Model_Observer{
  public function processPayment($event){
    if (!class_exists('Conekta\Conekta')) {
      error_log("Plugin miss Conekta PHP lib dependency. Clone the repository using 'git clone --recursive git@github.com:conekta/conekta-magento.git'", 0);
      throw new Mage_Payment_Model_Info_Exception("Payment module unavailable. Please contact system administrator.");
    }
    if($event->payment->getMethod() == Mage::getModel('Conekta_Spei_Model_Spei')->getCode()){
      \Conekta\Conekta::setApiKey(Mage::getStoreConfig('payment/webhook/privatekey'));
      \Conekta\Conekta::setApiVersion("2.0.0");
      \Conekta\Conekta::setPlugin("Magento 1");
      \Conekta\Conekta::setLocale(Mage::app()->getLocale()->getLocaleCode());
      
      $order = $event->payment->getOrder();
      $order_params = array();
      $days = $event->payment->getMethodInstance()->getConfigData('my_date');
      $order_params["currency"]         = Mage::app()->getStore()->getCurrentCurrencyCode();
      $order_params["line_items"]       = self::getLineItems($order);
      $order_params["shipping_lines"]   = self::getShippingLines($order);
      $discount_lines                   = self::getDiscountLines($order);
      if (!empty($discount_lines)) {
        $order_params["discount_lines"]   = $discount_lines;
      } else {
        $order_params["discount_lines"]   = array();
      }
      $order_params["tax_lines"]        = self::getTaxLines($order);
      $order_params["customer_info"]    = self::getCustomerInfo($order);
      $order_params["shipping_contact"] = self::getShippingContact($order);
      $order_params["metadata"]  = array("checkout_id" => $order->getIncrementId(), "soft_validations" => true);
      $charge_params                    = self::getCharge(intval(((float) $order->grandTotal) * 100), strtotime("+".$days." days"));

      
      try {
        $create_order = true;
        
        $conekta_order_id = Mage::getSingleton('core/session')->getConektaOrderID();
        if (!empty($conekta_order_id)) {
          $conekta_order = \Conekta\Order::find($conekta_order_id);
          $conekta_order->update($order_params);
          $create_order = ($conekta_order->metadata->checkout_id != $order_params["metadata"]["checkout_id"]);
        }

        if ($create_order) {
          $conekta_order = \Conekta\Order::create($order_params);
          $conekta_order_id = Mage::getSingleton('core/session')->setConektaOrderID($conekta_order->id);
        }
        
        $conekta_order->createCharge($charge_params);
        $charge = $conekta_order->charges[0];
      } catch (\Conekta\ErrorList $e){
        throw new Mage_Payment_Model_Info_Exception($e->details[0]->getMessage());
      }

      Mage::getSingleton('core/session')->unsConektaOrderID();
      $event->payment->setSpeiClabe($charge->payment_method->receiving_account_number);
      $event->payment->setSpeiBank($charge->payment_method->bank);
      $event->payment->setChargeId($charge->id);
      //Update Quote
      $order = $order;
      $quote = $order->getQuote();
      $payment = $quote->getPayment();
      $payment->setSpeiClabe($charge->payment_method->receiving_account_number);
      $payment->setSpeiBank($charge->payment_method->bank);
      $payment->setChargeId($charge->id);
      $quote->collectTotals();
      $quote->save();
      $order->setQuote($quote);
      $order->save();
    }
    return $event;
  }

  public function getCharge($amount, $expiry_date) {
    $charge = array(
      'payment_method' => array(
          'type' => 'spei',
          'expires_at' => $expiry_date
      ),
      'amount' => $amount
    );
    return $charge;
  }

  public function getLineItems($order) {
    $items = $order->getAllVisibleItems();
    $line_items = array();
    $i = 0;
    foreach ($items as $itemId => $item){
      $name = $item->getName();
      $sku = $item->getSku();
      $price = intval($item->getPrice() * 100) * $item->getQtyOrdered();
      $description = $item->getDescription();
      if (!$description) $description = $name;
      $product_type = $item->getProductType();
      $line_items = array_merge($line_items, array(array(
        'name'        => $name,
        'description' => $description,
        'unit_price'  => $price,
        'quantity'    => 1,
        'sku'         => $sku,
        'type'        => "physical",
        'tags'        => [$product_type]
        ))
      );
      $i = $i + 1;
    }
    return $line_items;
  }

  public function getShippingContact($order) {
    $shipping_contact = array();
    $quote = $order->getQuote();
    $email = $quote->getBillingAddress()->getEmail();
    if (!$email) $email = $quote->getCustomerEmail();
    $billing = $order->getBillingAddress()->getData();
    $shipping_address = $order->getShippingAddress();
    $shipping_data = $shipping_address->getData();

    $shipping_contact["email"] = $email;
    $shipping_contact["phone"] = $billing['telephone'];
    $shipping_contact["receiver"] = preg_replace('!\s+!', ' ', $billing['firstname'] . ' ' . $billing['middlename'] . ' ' . $billing['lastname']);
    $address = array();
    $address["street1"] = $shipping_data['street'];
    $address["city"] = $shipping_data['city'];
    $address["state"] = $shipping_data['region'];
    $address["country"] = $shipping_data['country_id'];
    $address["postal_code"] = $shipping_data['postcode'];
    $shipping_contact["address"] = $address;
    return $shipping_contact;
  }

  public function getShippingLines($order) {
    $shipping_lines = array();
    if ($order->getShippingAmount() > 0) {
      $shipping_line = array();
      $shipping_line["amount"] = intval(($order->getShippingAmount()+$order->getShippingTaxAmount()) * 100);
      $shipping_line["description"] = "Shipping total amount";
      $shipping_line["method"] = "custom";
      $shipping_line["carrier"] = "custom";
      $shipping_lines = array_merge($shipping_lines, array($shipping_line));
    }
    return $shipping_lines;
  }

  public function getDiscountLines($order) {
    $discount_lines = array();
    
    $totalDiscount = abs(intval($order->getDiscountAmount() * 100));
    $totalDiscountCoupons = 0;

    foreach ($order->getAllItems() as $item) {
      if (floatval($item->getDiscountAmount()) > 0.0) {
        $description = $order->getDiscountDescription();
        if (!empty($description)) {
          $description = "discount_code";
        }
        $discount_line = array();
        $discount_line["code"] = $description;
        $discount_line["type"] = "coupon";
        $discount_line["amount"] = intval($item->getDiscountAmount() * 100);
        $discount_lines = array_merge($discount_lines, array($discount_line));

       $totalDiscountCoupons = $totalDiscountCoupons + $discount_line["amount"];
      }
    }

    // Discount exceeds unit price or shipping.
    if (floatval($totalDiscount) > 0.0 && $totalDiscount != $totalDiscountCoupons) {
      $discount_lines = array();
      $discount_line = array();
      $description = "discount_code";
      $discount_line["code"] = $description;
      $discount_line["type"] = "coupon";
      $discount_line["amount"] = $totalDiscount;
      $discount_lines = array_merge($discount_lines, array($discount_line));
    }
    return $discount_lines;
  }

  public function getTaxLines($order) {
    $customer = $order->getCustomer();
    $tax_lines = array();
    if ($customer->getTaxvat() > 0) {
      $tax_line = array();
      $tax_line["description"] = $customer->getTaxClassId();
      $tax_line["amount"] = $customer->getTaxvat();
      $tax_lines = array_merge($tax_lines, $tax_line);
    }
    return $tax_lines;
  }

  public function getCustomerInfo($order) {
    $quote = $order->getQuote();
    $email = $quote->getBillingAddress()->getEmail();
    if (!$email) $email = $quote->getCustomerEmail();
    $billing = $order->getBillingAddress()->getData();
    $customer_info = array();
    $customer_info["name"] = preg_replace('!\s+!', ' ', $billing['firstname'] . ' ' . $billing['middlename'] . ' ' . $billing['lastname']);
    $customer_info["email"] = $email;
    $customer_info["phone"] = $billing['telephone'];
    return $customer_info;
  }
}
