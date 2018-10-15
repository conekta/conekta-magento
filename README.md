![alt tag](https://raw.github.com/conekta/conekta-magento/master/readme_files/cover.png)

<div align="center">

Magento Plugin v.0.9.8
=======================

[![Made with PHP](https://img.shields.io/badge/made%20with-php-red.svg?style=for-the-badge&colorA=ED4040&colorB=C12C2D)](http://php.net) [![By Conekta](https://img.shields.io/badge/by-conekta-red.svg?style=for-the-badge&colorA=ee6130&colorB=00a4ac)](https://conekta.com)
</div>


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
This extension supports PHP version >= 5.6.9 and < 7.0.0
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

* `Conekta_Card`
* `Conekta_Oxxo`
* `Conekta_Spei`
* `Conekta_Webhook`

License
-------
Developed by [Conekta](https://www.conekta.io). Available under [MIT License](LICENSE).

## How to contribute to the project

1. Fork the repository
 
2. Clone the repository
```
    git clone git@github.com:yourUserName/conekta-magento.git
```
3. Create a branch
```
    git checkout develop
    git pull origin develop
    # You should choose the name of your branch
    git checkout -b <feature/my_branch>
```    
4. Make necessary changes and commit those changes
```
    git add .
    git commit -m "my changes"
```
5. Push changes to GitHub
```
    git push origin <feature/my_branch>
```
6. Submit your changes for review, create a pull request

   To create a pull request, you need to have made your code changes on a separate branch. This branch should be named like this: **feature/my_feature** or **fix/my_fix**.

   Make sure that, if you add new features to our library, be sure that corresponding **unit tests** are added.

   If you go to your repository on GitHub, you’ll see a Compare & pull request button. Click on that button.

***

## We are always hiring!

If you are a comfortable working with a range of backend languages (Java, Python, Ruby, PHP, etc) and frameworks, you have solid foundation in data structures, algorithms and software design with strong analytical and debugging skills, check our open positions: https://www.conekta.com/careers
