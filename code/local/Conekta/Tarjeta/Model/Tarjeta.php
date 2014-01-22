<?php
class Conekta_Tarjeta_Model_Tarjeta extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'tarjeta';
	protected $_formBlockType = 'tarjeta/form_tarjeta';
	protected $_infoBlockType = 'tarjeta/info_tarjeta';

	public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();
        $info->setCcType($data->getCcType())
            ->setCcOwner($data->getCcNombre())
            ->setCcLast4(substr($data->getCcNumero(), -4))
            ->setCcNumber($data->getCcNumber())
            ->setCcCid($data->getCcCid())
            ->setCcExpMonth($data->getCcMes())
            ->setCcExpYear($data->getCcAnio())
            ->setCcSsIssue($data->getCcSsIssue())
            ->setCcSsStartMonth($data->getCcSsStartMonth())
            ->setCcSsStartYear($data->getCcSsStartYear())
            ;
        return $this;
    }
}
?>
