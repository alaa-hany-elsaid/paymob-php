Unofficial Paymob Gateway API in PHP
====

Paymob-php to pay with Payment gateway

Installation
------------

Install the latest version with:

```bash
$ composer require  alaa-hany/paymob-php

```

Requirements
------------

* PHP 5.6 or higher is required

Basic usage
-----------

```php
   use \Alaa\Paymob\Paymob ;
    // add paymob config to Paymob facade in AppServiceProvider 
    // inside your serviceProvider 
    Paymob::setConfig([
        "api_key" => "API_KEY_HERE" ,
        "card_integration"  => "VALUE",
        "wallet_integration"  => "VALUE",
        "card_iframe"  => "VALUE",
    ]);
    // cart or wallet 
    // for wallet mobile first 
    /**
    * $amount 
    * $billing_data --> customer info [ first_name , last_name , phone , email  ] required
    * $items = [] 
    * $delivery_needed = false, 
    * $additional_info = []
    * $lock_order_when_paid = false 
    * 
    */
   Paymob::card( $amount_cents ,  $billing_data );
   Paymob::mobileWallet($mobile , $amount_cents ,  $billing_data )
   // all of these return [ 'order_id'  , "url"  ]
   ``` 

* Laravel
    * `set config to Paymob facade in any service provider`

Note
----
If you need any non exists operation , you are welcome to order it . <br>
Contact me on : <br>
&nbsp;&nbsp;Email : [elboray.alaa1@icloud.com](mailto:elboray.alaa1@icloud.com) <br>
&nbsp;&nbsp;whatsapp : [+201063745208](https://wa.me/201063745208)

License
-------
alaa-hany/paymob-php is licensed under the MIT License.