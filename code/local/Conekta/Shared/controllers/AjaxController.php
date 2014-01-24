<?php
class Ajax_Controller extends Mage_Core_Controller_Front_Action {
	
    public function listenerAction() {
		$body = @file_get_contents('php://input');
		$event = json_decode($body);
		$charge = $event->data->object;
		$line_items = $charge->line_items;
		foreach ($line_items as $item) {
			// Changing the type to 'simple' allows qty to change
			$product = Mage::getModel('catalog/product')->load($item->sku); 
			$product->setTypeId("simple");
			$product->save();
			$stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($item->sku);
			$quantity = $item->quantity;
			// Allows negative stock
			$new_qty = $stock->getQty() - $quantity;
			$stock->setQty($new_qty);
			$stock->setData('is_in_stock', $new_qty > 0 ? 1 : 0)
			->save();
		}
	}
}
?>
