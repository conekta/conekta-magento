![alt tag](https://raw.github.com/conekta/conekta-magento/master/readme_files/cover.png)

Magento Plugin v.0.5.9
=======================
This plugin is an official and stable version of the Conekta Magento extension. It bundles functionality to process credit cards, bank and OXXO payments securely as well as send email notifications to your customers when they complete a successful purchase.

Don't worry about managing the status of your orders, the plugin will automatically changes orders to paid as long as your webhooks are properly configured.

Features
--------
Current version features:

* Online and offline payments
* Automatic order status management
* Email notifications on successful purchase

![alt tag](https://raw.github.com/conekta/conekta-magento/master/readme_files/invoice.png)

* Sandbox testing capability.
* Client side validation for credit cards.

![alt tag](https://raw.github.com/conekta/conekta-magento/master/readme_files/card_validation.png)

* All card data is sent directly to Conekta's servers so you don't have to be PCI compliant.

![alt tag](https://raw.github.com/conekta/conekta-magento/master/readme_files/server_validation.png)

Magento Version Compatibility
-----------------------------
The plugin has been tested in Magento 1.7, 1.8, and 1.9. Support is not guaranteed for untested versions.

Installation
-----------

	Clone the module using git clone --recursive git@github.com:conekta/conekta-magento.git

There is no custom installation for this plugin, just the default:

  * Copy the folder and paste it in the folder where you have installed Magento.
  * In the Magento admin, navigate to 'System-Cache Management'. Select and disable all Cache Types.  Additionally, click "Flush Magento Cache" and "Flush Cache Storage".  These steps will allow you to start testing the plugin.
  * In the 'System->Configuration' section, click the 'Payment Methods' link in the left hand navigation.  Check that the payment methods "Pago con Tarjeta de Débito / Crédito", "Pago con Oxxo" and "Pago con Transferencia Bancaria" appear. If these payment methods do not show up, check that your magento user has priviledges to access the Magento folder.
  * Each of the payment methods should should 'Enabled'=>'Yes', in the 'Api Keys' section for the payment methods paste the api keys found in https://admin.conekta.io#developers.keys, e.g.
    
Api Public Key: 
    `key_KJysdbf6PotS2ut2`
Api Private Key: 
    `key_eYvWV7gSDkNYXsmr`
    
![alt tag](https://raw.github.com/conekta/conekta-magento/master/readme_files/admin.png)

Inventory Notes
---------------

Order status can be changed dynamically via "New order status" and "Order status when Conekta sends payment notification" field using the admin in payment methods configuration.

To receive payment notifications, adjuts your webhook in your Conekta Admin the following url:
    `http://mymagento-store.com/index.php/webhook/ajax/listener`

Where mymagento-store.com is a placeholder and it should be replaced with the your website url.

[UPDATE]

You can now set your webhook url in the admin configuration.

Included modules
----------------
This version includes the following modules:

* `Conekta_Bank`
* `Conekta_Card`
* `Conekta_Oxxo`
* `Conekta_Spei`
* `Conekta_Webhook`

License
-------
Developed by [Conekta](https://www.conekta.io). Available under [MIT License](LICENSE).
