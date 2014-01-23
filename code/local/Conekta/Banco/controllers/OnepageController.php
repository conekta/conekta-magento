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

//require_once 'Mage/Checkout/controllers/OnepageController.php';
//require_once '/../local/Conekta/Oxxo/controllers/OnepageController.php';
require_once(dirname(__FILE__) . '/../../Oxxo/controllers/OnepageController.php');
class Conekta_Banco_OnepageController extends Conekta_Oxxo_OnepageController
{
	public function savePaymentAction()
    {
        if ($this->_expireAjax()) {
            return;
        }
        try {
            if (!$this->getRequest()->isPost()) {
                $this->_ajaxRedirectResponse();
                return;
            }

            // set payment to quote
            $result = array();
            $data = $this->getRequest()->getPost('payment', array());
            $result = $this->getOnepage()->savePayment($data);

            // get section and redirect data
            $redirectUrl = $this->getOnepage()->getQuote()->getPayment()->getCheckoutRedirectUrl();
            if (empty($result['error']) && !$redirectUrl) {
                $this->loadLayout('checkout_onepage_review');
                $result['goto_section'] = 'review';
                $result['update_section'] = array(
                    'name' => 'review',
                    'html' => $this->_getReviewHtml()
                );
            }
            if ($redirectUrl) {
                $result['redirect'] = $redirectUrl;
            }
        } catch (Mage_Payment_Exception $e) {
            if ($e->getFields()) {
                $result['fields'] = $e->getFields();
            }
            $result['error'] = $e->getMessage();
        } catch (Mage_Core_Exception $e) {
            $result['error'] = $e->getMessage();
        } catch (Exception $e) {
            Mage::logException($e);
            $result['error'] = $this->__('Unable to set Payment Method.');
        }
        
        if ($data['numero_servicio']) {
			$result = $this->setBankReceipt($result, $data);
		} else if ($data['codigo_barras']) {
			$result = $this->setOxxoReceipt($result, $data);
		}
		
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
    
    public function setBankReceipt($result, $data) {
		$pattern = "</tfoot>";
		$new_html = '<tr><td style="padding:5px;" class="a-left" colspan="4"><p>Banco: '. $data['banco'] .'</p><p>N&uacute;mero de servicio: '. $data['numero_servicio'] .'</p><p>Nombre de servicio: '. $data['nombre_servicio'] .'</p><p>N&uacutemero de referencia: '. $data['referencia'] .'</p></td></tr><tfoot>';
		$html = str_replace($pattern, $new_html, $result['update_section']['html']);
		$result['update_section']['html'] = $html;
		return $result;
	}
	
}
