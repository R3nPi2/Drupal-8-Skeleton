### INTRODUCTION

**Twitter API Search** module provides a block that will show 
embed tweets resulting from a Twitter API v1.1 Search query.

If you are not familiar with _Twitter API_, go to 
[https://developer.twitter.com/en/docs/tweets/search/overview/standard](https://developer.twitter.com/en/docs/tweets/search/overview/standard).

You will need a Twitter 
[_a developer account_](https://developer.twitter.com/en/dashboard) 
and [_a registered app_](https://developer.twitter.com/en/apps) 
and configure your 
[_Twitter App 
credentials_](https://developer.twitter.com/en/docs/basics/authentication/guides/access-tokens.html).

### REQUIREMENTS

External library 
[twitter-api-php](https://github.com/J7mbo/twitter-api-php) 
is required by this module.

If you install it with `composer`, the library will be auto-intalled, but if 
your install this module manually make shure that file 
`libraries/twitter-api-php/TwitterAPIExchange.php` exists.

### INSTALLATION

1.  Require or download _Twitter API Search_ module 
[using Composer to manage Drupal site 
dependencies](https://www.drupal.org/docs/develop/using-composer/using-composer-to-manage-drupal-site-dependencies), 
which will also download the required 
[twitter-api-php](https://github.com/J7mbo/twitter-api-php) 
dependency library. If not, you will have to install the library manually and 
place it in `libraries/twitter-api-php/TwitterAPIExchange.php`.

    To install with composer, simply run the following command from your project 
    package root (where the main composer.json file is sited):

    ```
    composer require 'drupal/twitter_api_search'
    ```

2. Enable the module.

### CONFIGURATION

1. Configure Twitterp App credentials going to 
_Configuration >> System >> Twitter API_ or 
_/admin/config/system/twitter-api-search_.

    To get this credentials you will need a Twitter 
    [_developer account_](https://developer.twitter.com/en/dashboard), 
    [_a registered app_](https://developer.twitter.com/en/apps) 
    and configure your 
    [_Twitter App 
    credentials_](https://developer.twitter.com/en/docs/basics/authentication/guides/access-tokens.html).

2. Go to _Structure >> Block layout_ , place a _Twitter API Search block_ and 
set custom block configuration settings.

#### AUTHOR

* [R3n Pi2](https://github.com/R3nPi2)
