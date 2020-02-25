#Laravel-Zaptrance

Laravel Package for working with Zaptrance Payment System.

## Installation

[PHP](https://php.net) 5.4+ or [HHVM](http://hhvm.com) 3.3+, and [Composer](https://getcomposer.org) are required.

To get the latest version of Laravel Zaptrance, just require it

composer require zaptrance/payment

Or add the following line to the require block of your `composer.json` file.
...
"zaptrance/payment
...
You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once Laravel Zaptrace is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.
If you use **Laravel >= 5.5** you can skip this step and go to [**`configuration`**]

* `Zaptrance\Payment\ZaptranceServiceProvider::class,`

Also, register the Facade like so:

```php
'aliases' => [
    ...
    "Zaptrance"=> Zaptrance\Payment\Facades\Zaptrance::class,
    ...
]
```
## Configuration

You can publish the configuration file using this command:

```bash
php artisan vendor:publish --provider="Zaptrance\Payment\ZaptranceServiceProvider"
```
A configuration-file named `zaptrance.php` with some sensible defaults will be placed in your `config` directory:

```php
<?php

return [

    /**
     * Merchant API Key From Zaptrance Merchant Dashboard
     *
     */
    "apiKey"=> 'MERCHANT_API_KEY',


    /**
     * Merchant ID From Zaptrance Merchant Dashboard
     *
     */
    "merchantId" => 'MERCHANT_ID',

    /**
     * Zaptrance Payment URL
     *
     */
    "requestUrl"=>'REQUEST_URL'

];
```
##General payment flow

Though there are multiple ways to pay an order, most payment gateways expect you to follow the following flow in your checkout process:

###1. The customer is redirected to the payment provider
After the customer has gone through the checkout process and is ready to pay, the customer must be redirected to site of the payment provider.

The redirection is accomplished by submitting a form with some hidden fields. The form must post to the site of the payment provider. The hidden fields minimally specify the amount that must be paid, the order id and a hash.


###2. The customer pays on the site of the payment provider
The customer arrived on the site of the payment provider and gets to choose a payment method. All steps necessary to pay the order are taken care of by the payment provider.

###3. The customer gets redirected back
After having paid the order the customer is redirected back. In the redirection request to the shop-site some values are returned. The values are usually the order id, a paymentresult and a hash.

The hash is calculated out of some of the fields returned and a secret non-public value. This hash is used to verify if the request is valid and comes from the payment provider. It is paramount that this hash is thoroughly checked.

## Usage

Open your config/zaptrance file and add your MERCHANT_API_KEY, MERCHANT ID and payment url like so:

```php
MERCHANT_API_KEY=xxxxxxxxxxxxx
MERCHANT_ID=xxxxxxxxxxxxx
REQUEST_URL=https://
```

Set up routes and controller methods like so:



```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Zaptrance;

class PaymentController extends Controller
{

    /**
     * Redirect the User to Zaptrance Payment Page
     * @return Url
     */
    public function makePayment()
    {
    	/**
			$service_id is the id of the Service The transaction is made for 
			Its from Service Dashboard
    	*/
        return return Zaptrance::makePayment($serviceid)->redirectNow();
    }

    /**
     * Obtain Paystack payment information
     * @return void
     */

}

```

A sample form will look like so:

```html
<form method="POST" action="{{ Your Payment Route}}" accept-charset="UTF-8" class="form-horizontal" role="form">
        <div class="row" style="margin-bottom:40px;">
          <div class="col-md-8 col-md-offset-2">
            <p>
                <div>
                    Lagos Eyo Print Tee Shirt
                    ₦ 2,950
                </div>
            </p>
            <input type="hidden" name="amount" value="2950">
                    <input type="hidden" name="transaction_id" value="" >
                    <input type="hidden" name="email" value="Kss.com">
                    <input type="hidden" name="service_token" value="Zaptrance Service Key">
                    <input type="submit"  value="Pay" class="btn btn-primary">


            <p>
              <button class="btn btn-success btn-lg btn-block" type="submit" value="Pay Now!">
              <i class="fa fa-plus-circle fa-lg"></i> Pay Now!
              </button>
            </p>
          </div>
        </div>
</form>
```


