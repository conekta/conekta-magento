<?php
require_once(dirname(__FILE__) . '/../../Shared/Model/PaymentMethod.php');
class Conekta_Banco_Model_Banco extends Payment_Method
{
	protected $_code = 'banco';
	protected $_formBlockType = 'banco/form_banco';
	protected $_infoBlockType = 'banco/info_banco';

	public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->newInFoInstance();
        $info->setNumeroServicio($data->getNumeroServicio())
			->setNombreServicio($data->getNombreServicio())
			->setReferencia($data->getReferencia())
			->setBanco($data->getBanco());
        return $this;
    }
}
?>
