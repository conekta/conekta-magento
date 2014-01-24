<?php
class Payment_Method extends Mage_Payment_Model_Method_Abstract
{    
    public function newInFoInstance() {
		$info = $this->getInfoInstance();
		$info->setNumeroServicio(null)
			->setNombreServicio(null)
			->setReferencia(null)
			->setBanco(null)
			->setCodigoBarras(null)
			->setCcOwner(null)
            ->setCcLast4(null)
            ->setCcExpMonth(null)
            ->setCcExpYear(null);
        return $info;
	}
}
?>
