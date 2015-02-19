<?php

return [

  /*
   |--------------------------------------------------------------------------
   | White list of domains
   |--------------------------------------------------------------------------
   |
   | List of domains for which e-mail can be sent.
   |
   */
  'white_list' => explode('|', env('DESMART_LARAVEL_MAILER_WHITE_LIST', '')),

  /*
   |--------------------------------------------------------------------------
   | Enabled / disabled
   |--------------------------------------------------------------------------
   |
   */
  'enabled' => env('DESMART_LARAVEL_MAILER_ENABLED', false),

  /*
   |--------------------------------------------------------------------------
   | Fallback e-mail address
   |--------------------------------------------------------------------------
   |
   | Supported values: Closure or string.
   | Value in this field should be developer e-mail.
   | 
   | All e-mails (which are not whitelisted) sent by Laravel will be sent to this address instead.
   |
   */
  'email' => env('DESMART_LARAVEL_MAILER_EMAIL', '')

];
