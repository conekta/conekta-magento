<?php
class Conekta_Tarjeta_Block_Form_Tarjeta extends Mage_Payment_Block_Form
{
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('tarjeta/form/tarjeta.phtml');
	}
 
}
