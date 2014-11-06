<?php
class Conekta_Spei_Block_Info_Spei extends Mage_Payment_Block_Info
{
	
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('spei/info/spei.phtml');
	}
	
	protected function _prepareSpecificInformation($transport = null)
	{
		if (null !== $this->_paymentSpecificInformation) {
			return $this->_paymentSpecificInformation;
		}
		$info = $this->getInfo();
		$transport = new Varien_Object();
		$transport = parent::_prepareSpecificInformation($transport);
		$data = array();
		$data[Mage::helper('payment')->__('CLABE')] = $info->getSpeiClabe();
		//$transport->addData(array(
			//Mage::helper('payment')->__('Expiry Date') => $info->getSpeiExpiryDate(),
			//Mage::helper('payment')->__('Barcode Url') => $info->getSpeiBarcodeUrl()
		//));
		return $transport->setData(array_merge($data, $transport->getData()));
	}
}
