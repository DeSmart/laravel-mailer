[![Build Status](https://travis-ci.org/DeSmart/laravel-mailer.svg)](https://travis-ci.org/DeSmart/laravel-mailer)
[![Total Downloads](https://poser.pugx.org/DeSmart/laravel-mailer/downloads.svg)](https://packagist.org/packages/DeSmart/laravel-mailer)
[![License](https://poser.pugx.org/DeSmart/laravel-mailer/license.svg)](https://packagist.org/packages/DeSmart/laravel-mailer)

# desmart/laravel-mailer

Did you by mistake send e-mails from dev machine to production users? We did. 

To prevent this situations we created a simple catch-all extension for default Laravel mailer. It will send e-mails only to whitelisted addresses (or to a fallback e-mail address).

This package is meant only for dev/test/staging environments.

## Installation

  1. Add package to composer: `composer require "desmart/laravel-mailer:1.2.*"`
  2. Publish configuration: `php artisan vendor:publish`
  3. Edit configuration file: `config/desmart-laravel-mailer.php`
  4. **Replace** `Illuminate\Mail\MailServiceProvider` with `DeSmart\LaravelMailer\MailServiceProvider`
    
## How it works?

When mailer is enabled it replaces default `\Illuminate\Mail\Mailer`. Every `to()`, `cc()`, `bcc()` method call will be intercepted. 

If e-mail address is not in white list (note: we only do white lists by domain so be careful with this) it will be changed to `DESMART_LARAVEL_MAILER_EMAIL` address from your `.env` file.

That way every e-mail sent by Laravel will be redirected only to trusted users.

## Laravel compatibility
This package should not break compatibility with Laravel Mailer.

### Laravel 4.2
To use `desmart/laravel-mailer` with Laravel 4.2 switch version to `1.1.*`

### Laravel 4.1
To use `desmart/laravel-mailer` with Laravel 4.1 switch version to `1.0.*`
