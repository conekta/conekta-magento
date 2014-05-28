conekta-magento
==============

Features
---------------

	* Online and offline payments.
	* Automatic order status management.
	* Email notifications on successful purchase.
![alt tag](https://raw.github.com/conekta/conekta-magento/master/invoice.png)
	* Sandbox testing capability.
	* Client side validation for credit cards.
![alt tag](https://raw.github.com/conekta/conekta-magento/master/validation.png)
	* Card validation at Conekta's servers so you don't have to be PCI.
![alt tag](https://raw.github.com/conekta/conekta-magento/master/server_validation_at_conekta.png)

Magento Version Compatibility
-----------
The plugin has been tested for Magento 1.7 and 1.8. Conflicts can be found if installed in non tested Magento versions.

Installation
-----------

There is no custom installation for this plugin, just the default:

  * Copy the folder and paste it in the folder where you have installed Magento.
  * In the Magento admin, navigate to 'System-Cache Management'. Select and disable all Cache Types.  Additionally, click "Flush Magento Cache" and "Flush Cache Storage".  These steps will allow you to start testing the plugin.
  * In the 'System->Configuration' section, click the 'Payment Methods' link in the left hand navigation.  Check that the payment methods "Pago con Tarjeta de Débito / Crédito", "Pago con Oxxo" and "Pago con Transferencia Bancaria" appear. If these payment methods do not show up, check that your magento user has priviledges to access the Magento folder.
  * Each of the payment methods should should 'Enabled'=>'Yes', in the 'Api Keys' section for the payment methods paste the api keys found in https://admin.conekta.io#developers.keys, e.g.
    
Api Public Key: 
    `key_KJysdbf6PotS2ut2`
Api Private Key: 
    `key_eYvWV7gSDkNYXsmr`
    
![alt tag](https://raw.github.com/conekta/conekta-magento/master/admin.png)

Inventory Notes
---------------

For credit card purchases, the order status will change to complete everytime there is a successful purchase from Conekta. This is done using an Obsever inside the Card module and it can be changed to meet more specific requirements. 

You can also use the Webhook module to manage orders for offline payments using the following url:
    `http://mymagento-store.com/index.php/webhook/ajax/listener`

Modules in this plugin
-----------

  * Conekta_Bank
  * Conekta_Card
  * Conekta_Oxxo
  * Conekta_Webhook

