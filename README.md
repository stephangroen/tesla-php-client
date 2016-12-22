# tesla-php-client
A PHP client for easy integration of the Tesla API

This client is very basic, so most changes to the Tesla API will automatically be accomodated for. You can find the docs of the API at http://docs.timdorr.apiary.io


## Installation
This project can easily be installed through Composer.

```
composer require stephangroen/tesla-php-client
```

## One-time authentication process
Before you can start, you will first need to get an access token. This is a one-time process. Fortunatly the client makes this very simple. Get the client_id and client_secret here: http://pastebin.com/fX6ejAHd. You need to add them to you environment via a .env file in your existing project or use the functions supplied by the client. You need your email address and password you use for My Tesla. With these credentials you can get the access token.
```php
$tesla = new StephanGroen\Tesla\Tesla();
$tesla->setClientId('client_id_here');
$tesla->setClientSecret('client_secret_here');
$accessToken = $tesla->getAccessToken('your_username', 'your_password');
```
The access token is the only authentication token you need after this one-time process.

## Use the client
Next time you'd like to use the client, initiate it with the access token:
```php
$tesla = new StephanGroen\Tesla\Tesla('your_access_token');
```
## Get and set your vehicle id
In order to execute vehicle specific calls, you need your vehicle id. Retrieve this by requesting all vehicles for your account:
```php
$tesla = new StephanGroen\Tesla\Tesla();
$tesla->vehicles();
```
This will return an array with information about your vehicles. Extract the `id` from the respone, not the `vehicle_id` which is used for Tesla internal purposes. You might want to store this id locally for future use. When you have the id, let the client know as follows:
```php
$tesla = new StephanGroen\Tesla\Tesla();
$tesla->setVehicleId(123);
```

## Example call
Calls are very simple, read the source code and API docs to find out all available calls. For example, set the charge limit to 90 percent:
```php
$tesla = new StephanGroen\Tesla\Tesla('87dsfg76sdfg765sdfg765dsfg76fgds76');
$tesla->setVehicleId(123);
$tesla->setChargeLimit(90);
```
So, for every use you initiate the client and set the vehicle id to execute calls for.

## Client response
All calls just return an array with the data as described in the API docs mentioned above.

## 408 responses
You might get some 408 responses when your vehicle still needs to wake up. Wait for a moment and try again.
