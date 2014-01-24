<?php
require_once(dirname(__FILE__) . '/../../Shared/controllers/AjaxController.php');
class Conekta_Oxxo_AjaxController extends Ajax_Controller {

    public function indexAction() {
		$key=Mage::getStoreConfig('payment/oxxo/apiprivatekey');
		$quote = Mage::getSingleton('checkout/cart')->getQuote();
		$currency=Mage::getStoreConfig('payment/oxxo/currency');
		$grandTotal = $quote->getGrandTotal();
		$exploded_val=explode(".",$grandTotal);
		$exploded_val=$exploded_val['0']*100;
		require(dirname(__FILE__) . '/../../conekta-php/lib/Conekta.php');
		Conekta::setApiKey($key);
		$s_info = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getData();
		$b_info = Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress()->getData();
		$p_info = Mage::getSingleton('checkout/session')->getQuote()->getItemsCollection(array(), true);
		$n_items = count($p_info->getColumnValues('sku'));
		$line_items = array();
		for ($i = 0; $i < $n_items; $i ++) {
			$name = $p_info->getColumnValues('name');
			$name = $name[$i];
			$sku = $p_info->getColumnValues('id');
			$sku = $sku[$i];
			$price = $p_info->getColumnValues('price');
			$price = $price[$i];
			$description = $p_info->getColumnValues('description');
			$description = $description[$i];
			$product_type = $p_info->getColumnValues('product_type');
			$product_type = $product_type[$i];
			$line_items = array_merge($line_items, array(array(
				"name"=>$name,
				"sku"=>$sku,
				"unit_price"=> $price,
				"description"=>$description,
				"quantity"=> 1,
				"type"=>$product_type
			  ))
			);
		}
		$shipment = array();
		if ($s_info['grand_total'] > 0) {
			$shipment = array(
			  "carrier"=>"estafeta",
			  "service"=>"international",
			  "tracking_id"=>"XXYYZZ-9990000",
			  "price"=> $s_info['grand_total'],
			  "address"=> array(
				"street1"=>"250 Alexis St",
				"city"=>$s_info['city'],
				"state"=>$s_info['region'],
				"country"=>$s_info['country_id'],
				"zip"=>$s_info['postcode'],
			  )
			);
		}
		$reference_id = Mage::getSingleton('checkout/session')->getQuote()->getPayment()->getId();
		try {
			$charge = Conekta_Charge::create(array(
			  "description"=>"Compra en Magento de " . $b_info['email'],
			  "amount"=> $exploded_val,
			  "currency"=> $currency,
			  "reference_id" => $reference_id,
			  "cash"=>array(
				"type"=>"oxxo"
			  ),
			  "details"=> array(
				"name"=> preg_replace('!\s+!', ' ', $b_info['firstname'] . ' ' . $b_info['middlename'] . ' ' . $b_info['firstname']),
				"email"=> $b_info['email'],
				"phone"=> $b_info['telephone'],
				"billing_address"=> array(
				  "company_name"=> $b_info['company'],
				  "street1"=> $b_info['street'],
				  "city"=>$b_info['city'],
				  "state"=>$b_info['region'],
				  "country"=>$b_info['country_id'],
				  "zip"=>$b_info['postcode'],
				  "phone"=>$b_info['telephone'],
				  "email"=>$b_info['email']
				),
				"line_items"=> $line_items
				),
				"shipment"=> $shipment
			  )
			);
			echo '{"barcode":"' . $charge->payment_method->barcode_url . '"}';
		} catch (Conekta_Error $e) {
			echo '{"error":"' . $e->getMessage() . '"}';
		}
    }
}
?>
