# iPay Package
[![Build Status](https://travis-ci.org/doge/wow.svg)](https://travis-ci.org/doge/wow)
[![downloads](https://img.shields.io/packagist/dt/uisits/ipay.svg?style=plastic)](https://img.shields.io/packagist/dt/uisits/ipay.svg?style=plastic)
[![Issues](https://img.shields.io/github/issues/uisits/ipay.svg)](https://github.com/uisits/ipay/issues)
[![Release](https://img.shields.io/github/release/uisits/ipay.svg?style=plastic)](https://github.com/uisits/ipay/releases)
***
## About
uisits/ipay is package for ipay payment gateway. Please refer to the inter departmental iPay reference guide to get more details.

[iPAY Departmental Reference Guide](https://drive.google.com/open?id=13XY_XSrydsaGZQNrKirC3XFWy_l-UGY0Z94AFbYzmv0)

***

## Installation Guide
***
- Note
    - Before installing this package make sure you install the following packages
    1. nesbot/carbon
        ```
        composer require nesbot/carbon
        ```
    2. guzzlehttp\guzzle
        ```
        composer require guzzlehttp/guzzle
        ```
***
### Installation Steps
1. Run
    ```
    composer require uisits/ipay
    ```
2. Add Service Provider to your config/app.php
    ```
    uisits\ipay\IpayServiceProvider::class,
    ```
    Alternatively Add a Facade to your config/app.php (Under aliases array)
    ```
    'Ipay' => uisits\ipay\app\Facade\IpayController::class,
    ```
3. Publish the resources
    ```
    php artisan vendor:publish
    ```
4. Migrate the database
    ```
    php artisan migrate
    ```
5. Edit the config/ipay.php file

6. In your Controller to Send a payment write following code
    ```
    use uisits\ipay\app\Http\Controllers\IpayController as ipay;

    $ipay = new ipay();
    $redirect_url = $ipay->initiatePayment($amount);

    If you need to save the transaction details in another table you can fetch the latest transaction from the Ipay table and insert the records in the new table.

    Validate Url then send redirect away

    For Ex:
    if(\filter_var($redirect_url,FILTER_VALIDATE_URL)){
        return redirect()->away($redirect_url);
    }

    ```
    **OR Alternatively using Facade**

    ```
    use Ipay;

    $redirect_url = Ipay::initiatePayment($amount);

    Validate Url then send redirect away

    If you need to save the transaction details in another table you can fetch the latest transaction from the Ipay table and insert the records in the new table.

    For Ex:
    if(\filter_var($redirect_url,FILTER_VALIDATE_URL)){
        return redirect()->away($redirect_url);
    }
    ```

7. To Capture the payment send the request from ipay server which is mapped to your application
    ```
    $ipay->capturePayment($request);
    ```
    Or Using Facade
    ```
    Ipay::capturePayment($request);
    ```
    The **capturePayment()** function returns **true** or **throws error**.

***
## Docs
1. initiatePayment()
    >**Info: Initiate the Payment Request with Ipay Server**
    Parameters: float $amount
    return: string URL to redirect to Ipay server for payment form
2. capturePayment()
    >**Info: Send a Capture Request to Ipay Server**
    Parameters: HTTP $request with TOKEN from iPay Server
    return: boolean true Successful transaction | throw error for Failed transaction
***
## Running Tests
>**Note:**
    1. You require .env.example file with all necessary parameters for iPay to work. These parameters can be found in the Interdepartmental Ipay Guide listed above. Please copy the .env.example file to vendor/uisits/ipay/.env.example path.
    2. Please run composer install to install all dependencies including --dev dependencies
    3. Navigate to vendor/uisits/ipay and run phpunit
***
## TODO
- Option for user to save transaction to Database or not
- Add Costs to Table