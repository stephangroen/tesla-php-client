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

## Example call
Calls are very simple, read the source code and API docs to find out all available calls. For example, set the charge limit to 90 percent:
```php
$tesla = new StephanGroen\Tesla\Tesla();
$tesla->setChargeLimit(90);
```

## Client response
All calls just return an array with the data as described in the API docs mentioned above.
