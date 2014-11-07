[![Build Status](https://api.travis-ci.org/DeSmart/laravel-mailer.png)](https://travis-ci.org/DeSmart/laravel-mailer)

# desmart/laravel-mailer

Did you by mistake send e-mails from dev machine to production users? We did. 

To prevent this situations we created a simple catch-all extension for default Laravel mailer. It will send e-mails only to whitelisted addresses (or to a fallback e-mail address).

This package is meant only for dev/test/staging environments.

## Installation

  1. Add package to composer: `composer require "desmart/laravel-mailer:1.0.*"`
  2. Publish configuration: `php artisan config:publish desmart/laravel-mailer`
  3. Edit configuration file: `app/config/packages/desmart/laravel-mailer/mailer.php` 
  4. Add ServiceProvider to `app.php` - `'DeSmart\Mailer\MailServiceProvider'`
    * **Info** - add line after default *MailServiceProvider*: `Illuminate\Mail\MailServiceProvider`
    
## How it works?

When mailer is enabled it replaces default `\Illuminate\Mail\Mailer`. Every `to()`, `cc()`, `bcc()` method call will be intercepted. 

If e-mail address is not in whitelist (note: we only do whitelists by domain so be careful with this) it will be changed to `mailer::mailer.email` address.

That way every e-mail sent by Laravel will be redirected only to trusted users.
