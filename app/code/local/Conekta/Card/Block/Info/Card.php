<?php
class Conekta_Card_Block_Info_Card extends Mage_Payment_Block_Info
{
	protected function _prepareSpecificInformation($transport = null)
	{
		if (null !== $this->_paymentSpecificInformation) {
			return $this->_paymentSpecificInformation;
		}
		$info = $this->getInfo();
		$transport = new Varien_Object();
		$transport = parent::_prepareSpecificInformation($transport);
		$fecha = ($info->getCcExpMonth() . '/' . $info->getCcExpYear());
		$last4 = ('xxxx-xxxx-xxxx-' . $info->getCcLast4());
		$transport->addData(array(
			Mage::helper('payment')->__('Nombre del Tarjetahabiente') => $info->getCcOwner(),
			Mage::helper('payment')->__('Número de la card') => $last4,
			Mage::helper('payment')->__('Fecha de expiración') => $fecha
		));
		return $transport;
	}
}
