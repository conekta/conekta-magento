## [0.9.8]() - 2018-05-09

### Fix

- Order without associated charges 
- Cardholder name validation
- Cvc number validation
- Card number validation
- Card expiration date validation

## [0.9.7]() - 2017-04-17
### Fix
- Avoiding to send null when shipping contact does not exists
- Fix version bump

## [0.9.6]() - 2017-03-27
### Change
- The 404 response was updated to also check "order.paid" event type

## [0.9.5]() - 2017-03-22
### Fix
- Webhooks configurations in Admin screen are working again, now the user decides what to do with the order status' when the webhook is received

## [0.9.4]() - 2017-03-21
### Fix
- The order status was set incorrectly after webhook notification

## [0.9.3]() - 2017-03-21
### Fix
- Fix when state is not set

## [0.9.2](https://github.com/conekta/conekta-magento/releases/tag/v0.9.2) - 2017-03-16
### Fix
- Fix bug when the tax name is empty

## [0.9.1](https://github.com/conekta/conekta-magento/releases/tag/v0.9.1) - 2017-03-16
### Feature
- Adding soft validations for shipping contact and contact info
### Change
- Magento wrapper implementation instead plain POST's
- Code style conventions
- Error Handler implementation
- Fix on webhooks listener
### Update
- lib/Update to 3.3.0

## [0.9.0](https://github.com/conekta/conekta-magento/releases/tag/v.0.9.0) - 2017-03-15
### Remove
- Bank (Bancomer) Payment Method
### Fix
- Workaround for tax round bug
- Fix for monthly installments

## [0.8.6]() - 2017-03-10
### Fix
- Default shipping line

## [0.8.5]() - 2017-03-10
### Fix
- Fix tax lines

## [0.8.2]() - 2017-03-04
### Change
- Avoid using empty on return functions

## [0.8.1]() - 2017-03-01
### Change
- Change SPEI reference to receiving_account_number

## [0.8.0]() - 2017-03-01
### Fix
- Fix email translations

## [0.7.9]() - 2017-03-01
### Fix
- Fix email translations

## [0.7.8]() - 2017-02-21
### Change
- Delete discount lines when necessary

## [0.7.7]() - 2017-02-21
### Change
- Fallback to total discount if coupons have complicated rules

## [0.7.6]() - 2017-02-21
### Feature
- Add soft validations

## [0.7.5]() - 2017-02-20
### Fix
- Fix Discount items

## [0.7.4]() - 2017-02-16
### Fix
- Fix Oxxo translations

## [0.7.0](https://github.com/conekta/conekta-magento/releases/tag/0.7.0) - 2017-02-28
### Update
- Update plugin to use API 2.0.0

## [0.6.0](https://github.com/conekta/conekta-magento/releases/tag/v.0.6.0) - 2016-10-28
### Fix
- Fix add webhook

## [0.5.9](https://github.com/conekta/conekta-magento/releases/tag/v.0.5.9) - 2016-07-12
### Change
- Remove all unnecessary events for webhook creation

## [0.4.0](https://github.com/conekta/conekta-magento/releases/tag/v0.4.0) - 2015-03-18
### Remove
- Eliminate race condition in webhook listener

## [0.3.3](https://github.com/conekta/conekta-magento/releases/tag/v0.3.3) - 2014-12-31
### Update
- Update version in all modules

## [0.2.8](https://github.com/conekta/conekta-magento/releases/tag/0.2.8) - 2014-11-27
### Remove
- Remove Amasty Onepage integration because plugin is broken

## [0.2.7](https://github.com/conekta/conekta-magento/releases/tag/0.2.7) - 2014-11-27
### Feature
- Webhook notificiation retries

## [0.2.9](https://github.com/conekta/conekta-magento/releases/tag/v0.2.9) - 2014-11-27
### Update
- Update plugin versionhttps://github.com/conekta/conekta-magento/releases/tag/0.2.5


## [0.2.5](https://github.com/conekta/conekta-magento/releases/tag/0.2.5) - 2014-11-06
### Change
- Change order status logic for offline payments
- Send email data to Conekta
- Remove manual status changes from plugin, Magento does this automatically when invoice is generated
- Send new order e-mail from Observer and send invoice e-mail from webhook
- Move plugin to community folder
- Separate JS tokenization logic from card template
- Remove grand total from shipping array
- Standardize barcode url size
### Feature
- Add spei module
- Add support for IWD OPC
- Add support for OneStepCheckout
- Add Oxxo Reference to db and email notifications
- Add dynamic order status when Conekta notifies that the order has been paid
- Add expiry date for offline payments.
- Add real time module (disabled)

## [0.1.5](https://github.com/conekta/conekta-magento/releases/tag/v0.1.5) - 2015-06-08
### Change
- Remove manual status changes from plugin, Magento does this automatically when invoice is generated
- Send new order e-mail from Observer and send invoice e-mail from webhook

## [0.1.4](https://github.com/conekta/conekta-magento/releases/tag/v0.1.4) - 2014-07-25
### Feature
- Add warning and logging if module misses dependencies

## [0.1.3](https://github.com/conekta/conekta-magento/releases/tag/v0.1.3) - 2014-06-09
### Feature
- Add support for Magento 1.9.0.1

## [0.1.2](https://github.com/conekta/conekta-magento/releases/tag/v0.1.2) - 2014-06-02
### Update
- Update observer logic to support virtual orders

## [0.1.1](https://github.com/conekta/conekta-magento/releases/tag/v0.1.1) - 2014-05-29
### Fix
- Fix offline payment order status
- Fix email notifications

## [0.1.0](https://github.com/conekta/conekta-magento/releases/tag/v0.1.0) - 2014-05-28
### Update (First Stable Version)
- Online and offline payments
- Automatic order status management
- Email notifications on successful purchase
- Sandbox testing capability
- Client side validation for credit cards
- Card validation at Conekta's servers so you don't have to be PCI