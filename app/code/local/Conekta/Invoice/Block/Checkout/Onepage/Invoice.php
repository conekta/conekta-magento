<?php
class Conekta_Ficha_Block_Checkout_Onepage_Ficha extends Mage_Checkout_Block_Onepage_Abstract{
	protected function _construct()
	{
		$this->getCheckout()->setStepData('ficha', array(
            'label'     => Mage::helper('checkout')->__('Ficha de Pago'),
            'is_show'   => $this->isShow()
		));
		parent::_construct();
	}
}
