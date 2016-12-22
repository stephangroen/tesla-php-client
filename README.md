# tesla-php-client
A PHP client for easy integration of the Tesla API

This client is very basic, so most changes to the Tesla API will automatically be accomodated for. You can find the docs of the API at http://docs.timdorr.apiary.io


## Installation
This project can easily be installed through Composer.

```
composer require stephangroen/tesla-php-client
```

## Getting Started
Before you can start, you will first need to get an access token. This is a one-time process. Fortunatly the client makes this very simple. You need your email address and password you use for My Tesla.
```php
$tesla = new StephanGroen\Tesla\Tesla();
$accessToken = $tesla->getAccessToken('your_username', 'your_password');
```
Save the access token for future use. Next time you'd like to use the client, initiate it with the access token:
```php
$tesla = new StephanGroen\Tesla\Tesla('your_access_token');
```
## Get and set your vehicle id
In order to execute vehicle specific calls, you need your vehicle id. In order to get this you request all vehicles for your account:
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
