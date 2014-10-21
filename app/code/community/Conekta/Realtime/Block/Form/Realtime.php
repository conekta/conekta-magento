<?php
class Conekta_Realtime_Block_Form_Realtime extends Mage_Payment_Block_Form
{
	protected function _construct()
	{
		parent::_construct();
		$this->setTemplate('realtime/form/realtime.phtml');
	}
}
