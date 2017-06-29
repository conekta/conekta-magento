<?php
include_once(Mage::getBaseDir('lib') . DS . 'Conekta' . DS . 'lib' . DS . 'Conekta.php');
class Conekta_Card_Model_Observer{
    public function processPayment($event){
        if (!class_exists('Conekta\Conekta')) {
            error_log("Plugin miss Conekta PHP lib dependency. Clone the repository using 'git clone --recursive git@github.com:conekta/conekta-magento.git'", 0);
            throw new Mage_Payment_Model_Info_Exception("Payment module unavailable. Please contact system administrator.");
        }
        if($event->payment->getMethod() == Mage::getModel('Conekta_Card_Model_Card')->getCode()){
            \Conekta\Conekta::setApiKey(Mage::getStoreConfig('payment/webhook/privatekey'));
            \Conekta\Conekta::setApiVersion("2.0.0");
            \Conekta\Conekta::setPlugin("Magento 1");
            \Conekta\Conekta::setLocale(Mage::app()->getLocale()->getLocaleCode());

            $order        = $event->payment->getOrder();
            $order_params = array();
            $days         =
                $event->payment->getMethodInstance()->getConfigData('my_date');
            $order_params["line_items"]     = self::getLineItems($order);
            $order_params["shipping_lines"] = self::getShippingLines($order);
            $order_params["currency"]       =
                Mage::app()->getStore()->getCurrentCurrencyCode();

            $discount_lines                   = self::getDiscountLines($order);
            $order_params["discount_lines"]   = $discount_lines;
            $order_params["tax_lines"]        = self::getTaxLines($order);
            $order_params["customer_info"]    = self::getCustomerInfo($order);
            $shipping_contact                 = self::getShippingContact($order);

            if (!empty($shipping_contact)) {
                $order_params["shipping_contact"] = $shipping_contact;
            }
            $charge_amount     = (intval(((float) $order->grandTotal) * 10000)) / 100;
            $order_params["metadata"]         = array(
                "checkout_id"      => $order->getIncrementId(),
                "soft_validations" => true,
                "total_charge"      => $charge_amount
            );
            $data = Mage::app()->getRequest()->getParam('payment');
            $charge_expiration = strtotime("+".$days." days");
            $charge_params     = self::getCharge(
                $charge_amount,
                $data['conekta_token'],
                $data['monthly_installments']
            );

            $order_params = self::checkBalance($order_params, $charge_amount);

            try {
                $create_order = true;

                $conekta_order_id =
                    Mage::getSingleton('core/session')->getConektaOrderID();

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
            } catch (\Conekta\Handler $e){
                throw new Mage_Payment_Model_Info_Exception($e->getMessage());
            }
            $card = Mage::app()->getRequest()->getParam('card');
            Mage::getSingleton('core/session')->unsConektaOrderID();
            $event->payment->setCardToken($data['conekta_token']);
            $event->payment->setCardMonthlyInstallments($charge->monthly_installments);
            $event->payment->setChargeAuthorization($charge->payment_method->auth_code);
            $event->payment->setChargeId($order->getIncrementId());
            $event->payment->setCcOwner($charge->payment_method->name);
            $event->payment->setCcLast4($charge->payment_method->last4);
            $event->payment->setCcType($charge->payment_method->brand);
            $event->payment->setCardBin($card['bin']);

            //Update Quote
            $quote   = $order->getQuote();
            $payment = $quote->getPayment();
            $payment->setCardToken($data['conekta_token']);
            $payment->setCardMonthlyInstallments($charge->monthly_installments);
            $payment->setChargeAuthorization($charge->payment_method->auth_code);

            $payment->setCcOwner($charge->payment_method->name);
            $payment->setCcLast4($charge->payment_method->last4);
            $payment->setCcType($charge->payment_method->brand);
            $payment->setCardBin($card['bin']);

            $payment->setChargeId($charge->id);
            $quote->collectTotals();
            $quote->save();
            $order->setQuote($quote);
            $order->save();
        }
        return $event;
    }

    public function checkBalance($order, $total) {
        $amount = 0;

        foreach ($order['line_items'] as $line_item) {
            $amount = $amount +
                ($line_item['unit_price'] * $line_item['quantity']);
        }

        foreach ($order['shipping_lines'] as $shipping_line) {
            $amount = $amount + $shipping_line['amount'];
        }

        foreach ($order['discount_lines'] as $discount_line) {
            $amount = $amount - $discount_line['amount'];
        }

        foreach ($order['tax_lines'] as $tax_line) {
            $amount = $amount + $tax_line['amount'];
        }

        if ($amount != $total) {
            $adjustment = $total - $amount;

            $order['tax_lines'][0]['amount'] =
                $order['tax_lines'][0]['amount'] + $adjustment;

            if (empty($order['tax_lines'][0]['description'])) {
                $order['tax_lines'][0]['description'] = 'Round Adjustment';
            }
        }

        return $order;
    }

    public function getCharge($amount, $token_id) {
        $charge = array(
            'payment_method' => array(
                'type'     => 'card',
                'token_id' => $token_id
            ),
            'amount' => $amount
        );
        $data = Mage::app()->getRequest()->getParam('payment');
        if ($data['monthly_installments'] != 0) {
            $charge["payment_method"]["monthly_installments"] =
                $data['monthly_installments'];
        }

        return $charge;
    }

    public function getLineItems($order) {
        $items      = $order->getAllVisibleItems();
        $line_items = array();

        foreach ($items as $itemId => $item){
            $name         = $item->getName();
            $sku          = $item->getSku();
            $unit_price   = floatval($item->getPrice() * 1000);
            $description  = $item->getDescription();
            $product_type = $item->getProductType();

            if (empty($description)) {
                $description = $name;
            }
            $product_type = array(product_type);
            $line_items = array_merge($line_items, array(array(
                'name'        => $name,
                'description' => $description,
                'unit_price'  => intval(round(floatval($unit_price) / 10), 2),
                'quantity'    => intval($item->getQtyOrdered()),
                'sku'         => $sku,
                'tags'        => $product_type
            ))
            );
        }

        return $line_items;
    }

    public function getShippingContact($order) {
        $shipping_contact = array();
        $quote            = $order->getQuote();
        $email            = $quote->getBillingAddress()->getEmail();
        $billing          = $order->getBillingAddress()->getData();
        $shipping_address = $order->getShippingAddress();

        if (empty($email)) {
            $email = $quote->getCustomerEmail();
        }

        if ($shipping_address) {
            $shipping_data               = $shipping_address->getData();
            $shipping_contact["phone"]   = $billing['telephone'];
            $shipping_contact["receiver"] = preg_replace('!\s+!', ' ', $billing['firstname'] . ' ' . $billing['middlename'] . ' ' . $billing['lastname']);

            $address                      = array();
            $address["street1"]           = $shipping_data['street'];
            $address["city"]              = $shipping_data['city'];
            $address["state"]             = isset($shipping_data['region']) ? $shipping_data['region'] : "";
            $address["country"]           = $shipping_data['country_id'];
            $address["postal_code"]       = $shipping_data['postcode'];
            $shipping_contact["address"]  = $address;
            $shipping_contact["metadata"] = array("soft_validations" => true);
        }

        return $shipping_contact;
    }

    public function getShippingLines($order) {
        $quote            = $order->getQuote();
        $shipping_method  = $quote->getShippingAddress()->getShippingMethod();
        $shipping_address = $order->getShippingAddress();
        $shipping_lines   = array();
        $shipping_line    = array();

        if ($order->getShippingAmount() > 0) {
            $shipping_tax             = $order->getShippingTaxAmount();
            $shipping_cost            = $order->getShippingAmount() + $shipping_tax;
            $shipping_line["amount"]  = intval($shipping_cost * 100);
            $shipping_line["method"]  = $shipping_method;
            $shipping_line["carrier"] = $shipping_method;
            $shipping_lines           = array($shipping_line);
        } elseif ($shipping_address) {
            $shipping_line["amount"]  = 0;
            $shipping_line["method"]  = $shipping_method;
            $shipping_line["carrier"] = $shipping_method;
            $shipping_lines           = array($shipping_line);
        }

        return $shipping_lines;
    }

    public function getDiscountLines($order) {
        $discount_lines       = array();
        $totalDiscount        = abs(intval($order->getDiscountAmount() * 100));
        $totalDiscountCoupons = 0;

        foreach ($order->getAllItems() as $item) {
            if (floatval($item->getDiscountAmount()) > 0.0) {
                $description = $order->getDiscountDescription();

                if (empty($description)) {
                    $description = "discount_code";
                }

                $discount_line           = array();
                $discount_line["code"]   = $description;
                $discount_line["type"]   = "coupon";
                $discount_line["amount"] = abs(intval($order->getDiscountAmount() * 100));
                $discount_lines          =
                    array_merge($discount_lines, array($discount_line));

                $totalDiscountCoupons = $totalDiscountCoupons + $discount_line["amount"];
            }
        }

        // Discount exceeds unit price or shipping.
        if (floatval($totalDiscount) > 0.0 && $totalDiscount != $totalDiscountCoupons) {
            $discount_lines          = array();
            $discount_line           = array();
            $discount_line["code"]   = "discount";
            $discount_line["type"]   = "coupon";
            $discount_line["amount"] = $totalDiscount;
            $discount_lines          =
                array_merge($discount_lines, array($discount_line));
        }

        return $discount_lines;
    }

    public function getTaxLines($order) {
        $tax_lines = array();

        foreach ($order->getAllItems() as $item) {
            $tax_line                = array();
            $tax_description         = self::getTaxName($item);
            $tax_line["description"] = $tax_description;
            $tax_line["amount"]      = (intval($item->getTaxAmount() * 10000 )/ 100);
            $tax_lines               = array_merge($tax_lines, array($tax_line));
        }

        return $tax_lines;
    }

    public function getTaxName($item){
        $product_id     = $item->getProductId();
        $_product       = Mage::getModel('catalog/product')->load($product_id);
        $tax_class_id   = $_product->getTaxClassId();
        $tax_class      = Mage::getModel('tax/class')->load($tax_class_id);
        $tax_class_name = $tax_class->getClassName();
        if(empty($tax_class_name)){
            $tax_class_name = "tax";
        }

        return $tax_class_name;
    }

    public function getCustomerInfo($order) {
        $quote   = $order->getQuote();
        $email   = $quote->getBillingAddress()->getEmail();
        $billing = $order->getBillingAddress()->getData();

        if (empty($email)) {
            $email = $quote->getCustomerEmail();
        }

        $customer_info          = array();
        $customer_info["email"] = $email;
        $customer_info["phone"] = $billing['telephone'];
        $customer_info["name"]  =
            preg_replace('!\s+!', ' ', $billing['firstname'] . ' ' . $billing['middlename'] . ' ' . $billing['lastname']);
        $customer_info["metadata"] = array("soft_validations" => true);    

        return $customer_info;
    }
}
