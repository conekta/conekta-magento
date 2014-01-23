<?php
class Conekta_Banco_Model_Banco extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'banco';
	protected $_formBlockType = 'banco/form_banco';
	protected $_infoBlockType = 'banco/info_banco';

	public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();
        $info->setNumeroServicio($data->getNumeroServicio());
        $info->setNombreServicio($data->getNombreServicio());
        $info->setReferencia($data->getReferencia());
        $info->setBanco($data->getBanco());
        return $this;
    }
}
?>
