# Mageworx Stock Validation Plugin for the Delivery Date module
The module allows you to use stock validation (using the MSI Magento module) to suggest the most correct delivery dates.

**Base extension [Mageworx Delivery Date](https://www.mageworx.com/delivery-date-magento-2.html) is required*

## Installation

### Upload using composer

1. Log into Magento server (or switch to) as a user who has permissions to write to the Magento file system.
2. Install package using composer: `composer require mageworx/module-deliverydate-stock-validation`

### Upload by copying code

1. Log into Magento server (or switch to) as a user who has permissions to write to the Magento file system.
2. Download the "Ready to paste" package from your customer's area, unzip it and upload the 'app' folder to your Magento install dir.


### Enable the extension

1. Log in to the Magento server as, or switch to, a user who has permissions to write to the Magento file system.
2. Go to your Magento install dir:
```
cd <your Magento install dir> 
```

3. And finally, update the database:
```
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
```

## How to use

This plugin allows you to use the setting "Use Product Salable Quantity" in the [Magento 2 Delivery date module](https://www.mageworx.com/delivery-date-magento-2.html).

This setting allows you to hide the estimated delivery feature on the product page if the salable quantity of this product equals or less than 0. This setting also hides the delivery date functionality on the checkout if at least one product with salable quantity equals or less than 0 is added to the cart.

This setting can be helpful if you have the integration with any Pre-order extension, which displays the Pre-order button on the products if their salable quantity equals or less than 0 (i.e. out of stock).
