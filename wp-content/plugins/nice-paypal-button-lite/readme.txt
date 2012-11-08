=== Nice PayPal Button Lite ===
Contributors: mjetpax
Donate link: http://trinitronic.com/index.php/WordPress/wordpress-nice-paypal-button-lite.html
Tags: paypal, button, buy now, ecommerce, shortcode, buy now button, paypal button, paypal buy now button, paypal plugin, paypal plugin for wordpress

Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: trunk

Nice PayPal Button Lite gives you the power to create PayPal Buy Now buttons wherever you choose, by simply adding shortcodes to your post or page.

== Description ==

[Upgrade to the full version of the Nice PayPal Button plugin.](http://trinitronic.com/index.php/WordPress/wordpress-nice-paypal-button.html "Nice PayPal Button Plugin Full Version")

With the Nice PayPal Button Lite plugin you get the most convenient & flexible way to add PayPal Buy Now buttons to posts and pages!

Whether you want to sell a few items or a lot of items, using the traditional PayPal “Buy Now” button, the Nice PayPal Button Lite plugin makes it easy to turn any WordPress page into a point of sale. No need to monkey around with complicated ecommerce solutions, the Nice PayPal Button Lite's implementation is quick, flexible and easy to use. Simply enter the Nice PayPal Button Lite shortcode where you want your PayPal button to appear and start selling.

Example
`[nicepaypallite name="WordPress Coffee Mug" amount="12.50"]`

Some cool features that you get:

* Add Buy Now buttons anywhere on a post or a page.
* Set a dollar amount or allow the payee enter it.
* Supports PayPal Merchant ID numbers.
* Set the item name.
* Supports item numbers (sku numbers).
* Supports per item shipping amounts.
* Supports 2nd shipping amount per each additional unit.
* Support for default button size, large/small
* Support for PayPal window target, current or new.
* Supports PayPal account based shipping calculations.
* Supports PayPal account based shipping using weight in lbs or kgs.
* Supports flat tax.
* Set item quantity.
* Supports PayPal country codes. 
* Sets PayPal payment page language.
* Supports all PayPal supported currency codes.
* Setting for PayPal button language.
* Test mode option, point to PayPal sandbox account.
* Includes convenient admin options page for easy configuration.

== Installation ==

1. Unzip the Nice PayPal Lite plugin download
1. Upload the entire nicePayPalButtonLite folder to your wp-content/plugins/ directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Go to Settings>>Nice PayPal Button Lite
1. Configure the Nice PayPal Button Lite options, i.e. PayPal seller's email address, currency, country code, etc.
1. Click the "Update Settings" button
1. Insert Nice PayPal Button Lite shortcodes in any post or page.
1. That's it! You're all ready to start making money!!

== Frequently Asked Questions ==

Will the Nice PayPal Button Lite create other buttons types, like, Add to Cart, View Cart, Donations, etc?

The Nice PayPal Button Lite only supports Buy Now buttons. However, the full version of the [Nice PayPal Button plugin](http://trinitronic.com/index.php/WordPress/wordpress-nice-paypal-button.html "Nice PayPal Button Plugin Full Version") will allow you to create other button types.

How many buttons can I place in a post or page?

This is unlimited. You may place as many buttons as you would like in a post or page.

Where can I get technical support?

You can get support through our support forum [http://trinitronic.com/supportforum/index.php](http://trinitronic.com/supportforum/index.php "Nice PayPal Button Lite Support Forum").

Where can I find the Nice PayPal Button Lite documentation page?

You can find the Nice PayPal Button Lite Documentation here. [http://trinitronic.com/index.php/WordPress/wordpress-nice-paypal-button-lite-documentation.html](http://trinitronic.com/index.php/WordPress/wordpress-nice-paypal-button-lite-documentation.html "Nice PayPal Button Lite Documentation")

== Changelog ==

= 1.01 =
* Security update: closed various security holes. Update recommended.

= 1.0 =
* First release of the plugin

== Upgrade Notice ==

= 1.01 =
* Security update: closed various security holes. Update recommended.

= 1.0 =
* First release of the plugin

== Screenshots == 

== Documentation ==

**Basic Usage**

Usage is simple. Create a new post or page add your content to it. Wherever you want a PayPal button to appear include a shortcode like the following example. When you view your published post or page the shortcode will be automatically replaced with a corresponding PayPal button.

*Shortcode Example*

[nicepaypallite name="WordPress Coffee Mug" amount="12.50"]

*Shortcode Syntax*

[nicepaypallite attribute_name="attribute_value"]

**Shortcode Attributes**

The Nice PayPal Button Lite plugin provides a number of options through the inclusion of 8 shortcode attributes. These attributes are as follows.

* amount
* name
* sku
* shipping
* shipping2
* tax
* weight
* quantity

Each attribute can be set in the shortcode by including the attribute name followed by an equals sign and the attribute value in quotes.

*Attribute Example*

[nicepaypallite name="WordPress Coffee Mug" amount="12.50" shipping="4.95"]

**Shortcode Attribute Definitions**

*name*

[nicepaypallite name="Item Name"]

The name of the item being sold. If omitted, payers enter their own name at the time of payment.

*amount*

[nicepaypallite amount="0.00"]


The price or amount of the product, service, or contribution, not including shipping & handling, or tax. If omitted from Buy Now payers enter their own amount during checkout on the PayPal payment page. 

*IMPORTANT:* Do not enter a currency symbol, just the numerical amount.

*sku*

[nicepaypallite sku="000"]

The id number or sku number for your item. A pass-through variable for you to track product or service purchased or the contribution made. The value you specify passed back to you upon payment completion.

*shipping*

[nicepaypallite shipping="0.00"]

The cost of shipping this item. If you specify shipping and shipping2 is not defined, this flat amount is charged regardless of the quantity of items purchased.

Default – If profile-based shipping rates are configured, buyers are charged an amount according to the shipping methods they choose.

*shipping2*

[nicepaypallite shipping2="0.00"]

The cost of shipping each additional unit of this item. If omitted and profile-based shipping rates are configured, buyers are charged an amount according to the shipping methods they choose.

*tax*

[nicepaypallite tax="0.00"]

Transaction-based tax override variable. Set this to a flat tax to the transaction regardless of the buyer’s location. This value overrides any tax settings set in your account profile. 

Default – PayPal profile tax settings, if any, apply.

To use your PayPal account tax settings simply exclude the tax attribute.

*quantity*

[nicepaypallite quantity="0"]

The number of items or item quantity included in the purchase. For example, if you wanted to sell 10 golf balls at once, you would set the item quantity to 10.

*weight*

[nicepaypallite weight="0.0"]

The weight of the item. If PayPal account based shipping rates are configured with a basis of weight, the sum of weight values is used to calculate the shipping charges for the transaction. To enable PayPal account based shipping exclude the shipping and shipping2 attributes. After which you can optionally specify a item weight by entering a numerical (float) weight value, e.g. 1.5.

You may specify pounds or kilograms by adding a suffix of lbs or kgs to your weight value. If no suffix is provided the units of weight will default to pounds.

*Example*

[nicepaypallite weight="1.5kgs"]

**Plugin Options**

The Nice PayPal Button Lite plugin allows you to configure the plugin's global settings. Configuration of the plugin is made quick and easy through the use of it's options page. You can find the options page at Admin>>Settings>>Nice PayPal Button Lite. The Global settings that are made available to you are as follows.

*PayPal Account Email / Merchant Account ID*

Enter your valid PayPal account email address. Or, enter your valid Merchant Account ID, which you can find in your PayPal account profile under My Business Info. Payments will be made to the PayPal account associated with this specified email address or merchant id.

*PayPal Test Mode Email / Test Merchant Account ID*

Enter your valid PayPal sandbox test seller email address. Or, enter your valid Merchant Account ID, which you can find in your PayPal sandbox account profile under My Business Info. Test payments will be made to the sandbox account associated with this specified email address. To use this feature you must have a PayPal developer account.

*PayPal Test Mode*

Select on or off to put all PayPal buttons in and out of test mode. When on is specified all transactions are sent to the PayPal sandbox. To use this feature you must have a PayPal developer account.

*Currency Code*

Valid PayPal 3-letter currency codes: Australian Dollars: AUD, Canadian Dollars: CAD, Euros: EUR, Pounds Sterling: GBP, Yen: JPY, U.S. Dollars: USD, New Zealand Dollar: NZD, Swiss Franc: CHF, Hong Kong Dollar: HKD, Singapore Dollar: SGD, Swedish Krona: SEK, Danish Krone: DKK, Polish Zloty: PLN, Norwegian Krone: NOK, Hungarian Forint: HUF, Czech Koruna: CZK, Israeli Shekel: ILS, Mexican Peso: MXN.

*PayPal Country Code*

Enter your 2 digit country code to set the language used on the PayPal payment page. PayPal uses a two-character country code (ISO 3166). Some examples are United States: US, Great Britain: GB, France: FR, Spain: ES, Netherlands: DL, Poland: PL, German: DE. If you don't know your country code, or you can Google PayPal Country Codes.

*Button Language Code*

PayPal uses a 5 character code to designate the language of its buttons. For example, United States English is designated with en_US. Enter the 5 character code for the desired button language. Other code examples are Great Britain English: en_GB, French: fr_FR, Spanish: es_ES, Dutch: nl_NL, Polish: pl_PL, German: de_DE. If you don't know the code for your desired language, log into PayPal use the button creater and search the resulting HTML code for this https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif. Notice the en_US in the URL, it's the language code for the button.

*Default Button Size*

Select the default button size. You can choose between Small and large.

*New Window Target*

Choose how you want the buyer to be sent to PayPal, either in their current browser window or a new browser window.