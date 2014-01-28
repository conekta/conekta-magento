<?php
class Ajax_Controller extends Mage_Core_Controller_Front_Action {
	
    public function listenerAction() {
		$body = @file_get_contents('php://input');
		$event = json_decode($body);
		$charge = $event->data->object;
		$line_items = $charge->details->line_items;
		//$quote = Mage::getModel('sales/quote')->load($charge->reference_id);
		if (strpos($event->type, "charge.paid") !== false) {
		//if (isset($quote) && $quote->getIsActive()) {
			foreach ($line_items as $item) {
				if (intval($item->unit_price) > 0){
					$product = Mage::getModel('catalog/product')->loadByAttribute('sku',$item->sku);
					$stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($product);
					$quantity = $item->quantity;
					// Allows negative stock
					$new_qty = $stock->getQty() - $quantity;
					$stock->setQty($new_qty);
					$stock->setData('is_in_stock', $new_qty > 0 ? 1 : 0)
					->save();
				}
			}
			//$quote->setIsActive(false);
			//$quote->save();
		//}
		}
	}
}
?>
