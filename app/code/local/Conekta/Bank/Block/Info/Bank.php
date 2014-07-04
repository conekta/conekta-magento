<?php
class Conekta_Bank_Block_Info_Bank extends Mage_Payment_Block_Info
{
	
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('bank/info/bank.phtml');
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
			Mage::helper('payment')->__('Service Name') => $info->getBankServiceName(),
			Mage::helper('payment')->__('Service Number') => $info->getBankServiceNumber(),
			Mage::helper('payment')->__('Bank Name') => $info->getBankName(),
			Mage::helper('payment')->__('Bank Name') => $info->getBankReference()
			));
		return $transport;
	}
}
