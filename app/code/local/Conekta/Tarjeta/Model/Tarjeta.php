<?php
require_once(dirname(__FILE__) . '/../../Shared/Model/PaymentMethod.php');
class Conekta_Tarjeta_Model_Tarjeta extends Payment_Method
{
	protected $_code = 'tarjeta';
	protected $_formBlockType = 'tarjeta/form_tarjeta';
	protected $_infoBlockType = 'tarjeta/info_tarjeta';

	public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->newInFoInstance();
        $info->setCcOwner($data->getCcNombre())
            ->setCcLast4(substr($data->getCcNumero(), -4))
            ->setCcExpMonth($data->getCcMes())
            ->setCcExpYear($data->getCcAnio());
        return $this;
    }
}
?>
