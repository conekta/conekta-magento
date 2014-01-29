<?php
require_once(dirname(__FILE__) . '/../../Shared/Model/PaymentMethod.php');
class Conekta_Card_Model_Card extends Payment_Method
{
	protected $_code = 'card';
	protected $_formBlockType = 'card/form_card';
	protected $_infoBlockType = 'card/info_card';

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
