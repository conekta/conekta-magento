<?php
class Conekta_Card_Block_Info_Card extends Mage_Payment_Block_Info
{
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('card/info/card.phtml');
	}
	
	protected function _prepareSpecificInformation($transport = null)
	{
		if (null !== $this->_paymentSpecificInformation) {
			return $this->_paymentSpecificInformation;
		}
		$info = $this->getInfo();
		$transport = new Varien_Object();
		$transport = parent::_prepareSpecificInformation($transport);
		$transport->addData(array(
			Mage::helper('payment')->__('Card Token') => $info->getCardToken(),
			Mage::helper('payment')->__('Charge Authorization No#') => $info->getChargeAuthorization()
			));
		return $transport;
	}
}
