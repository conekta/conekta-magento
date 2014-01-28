magento_plugin
==============

Installation
-----------

  * Copy the folder and paste it in the folder where you have installed Magento.
  * In the Magento admin, navigate to 'System-Cache Management'. Select and disable all Cache Types.  Additionally, click "Flush Magento Cache" and "Flush Cache Storage".  These steps will allow you to start testing the plugin.
  * In the 'System->Configuration' section, click the 'Payment Methods' link in the left hand navigation.  Check that the payment methods "Pago con Tarjeta de Débito / Crédito", "Pago con Oxxo" and "Pago con Transferencia Bancaria" appear. If these payment methods do not show up, check that your magento user has priviledges to access the Magento folder.
  * Each of the payment methods should should 'Enabled'=>'Yes', in the 'Api Keys' section for the payment methods paste the api keys found in https://admin.conekta.io#developers.keys, e.g.
    
Api Public Key: 
    `EpnQNGMghzDrytvfpqtG`
Api Private Key: 
    `1tv5yJp3xnVZ7eK67m4h`


You will also need to configure payment notifications for your server.  Within https://admin.conekta.io#developers.webhooks, create a new url which points to your server (e.g. http://www.my_domain.com/magento-store.com/public/index.php/tarjeta/ajax/listener).  After having completed these steps the payment methods should be enabled for testing.

Inventory Notes
---------------

Your product inventory will only be adjusted when your server receives a payment notification from Conekta, this allows for offline payment methods like Bank Deposits and Oxxo to be processed

Dependency Notes
-----------

Included modules for the plugin include:
  * Conekta_Banco
  * Conekta_Oxxo
  * Conekta_Tarjeta
  * Conekta_Ficha
  
The Conekta_Ficha module must be enabled for inventory adjustments to work correctly.  If not enabled, the inventory will be decremented regardless of successful payments or not.
