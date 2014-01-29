<?php
class Conekta_Invoice_Block_Checkout_Onepage_Invoice extends Mage_Checkout_Block_Onepage_Abstract{
	protected function _construct()
	{
		$this->getCheckout()->setStepData('invoice', array(
            'label'     => Mage::helper('checkout')->__('Ficha de Pago'),
            'is_show'   => $this->isShow()
		));
		parent::_construct();
	}
}
