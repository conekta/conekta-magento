<?php
class Conekta_Banco_Block_Info_Banco extends Mage_Payment_Block_Info
{
	protected function _prepareSpecificInformation($transport = null)
	{
		if (null !== $this->_paymentSpecificInformation) {
			return $this->_paymentSpecificInformation;
		}
		$info = $this->getInfo();
		$transport = new Varien_Object();
		$transport = parent::_prepareSpecificInformation($transport);
		$transport->addData(array(
			Mage::helper('payment')->__('Banco') => $info->getBanco(),
			Mage::helper('payment')->__('Número de servicio') => $info->getNumeroServicio(),
			Mage::helper('payment')->__('Número de referencia') => $info->getReferencia(),
			Mage::helper('payment')->__('Nombre de servicio') => $info->getNombreServicio()
		));
		return $transport;
	}
}
