<?php
/**
* Magento
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@magentocommerce.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade Magento to newer
* versions in the future. If you wish to customize Magento for your
* needs please refer to http://www.magentocommerce.com for more information.
*
* @category    Mage
* @package     Mage_Checkout
* @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
* @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*/

require_once(dirname(__FILE__) . '/../../Shared/controllers/ReceiptController.php');
class Conekta_Oxxo_OnepageController extends Receipt_Controller
{
    public function setOxxoReceipt($result, $data) {
		$pattern = "</tfoot>";
		$new_html = '<tr><td style="padding:5px;" class="a-left" colspan="4"><p>Tu pago será procesado hasta las 10 AM del siguiente día hábil en que realizaste el pago. En ese momento, recibirás un correo electrónico confirmando tu pago. Esta ficha de pago no tiene ningún valor comercial, fiscal o monetario. Es una referencia que contiene los datos necesarios para que quien haya realizado una compra y pueda realizar el pago o transferencia bancaria.</p><img id="codigo_barras" src="'. $data['codigo_barras'] .'" alt="'. $data['codigo_barras'] .'" /></td></tr><tfoot>';
		$html = str_replace($pattern, $new_html, $result['update_section']['html']);
		$result['update_section']['html'] = $html;
		return $result;
	}
}
