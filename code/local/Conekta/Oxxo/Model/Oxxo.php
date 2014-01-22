<?php
class Conekta_Oxxo_Model_Oxxo extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'oxxo';
	protected $_formBlockType = 'oxxo/form_oxxo';
	protected $_infoBlockType = 'oxxo/info_oxxo';

	public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();
        //$info->setCcType($data->getCcType())
            //->setCcOwner($data->getCcNombre())
            //->setCcLast4(substr($data->getCcNumero(), -4))
            //->setCcNumber($data->getCcNumber())
            //->setCcCid($data->getCcCid())
            //->setCcExpMonth($data->getCcMes())
            //->setCcExpYear($data->getCcAnio())
            //->setCcSsIssue($data->getCcSsIssue())
            //->setCcSsStartMonth($data->getCcSsStartMonth())
            //->setCcSsStartYear($data->getCcSsStartYear())
            //;
        return $this;
    }
}
?>
