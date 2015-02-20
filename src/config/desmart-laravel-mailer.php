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
  'white_list' => [
    'example.com',
  ],

  /*
   |--------------------------------------------------------------------------
   | Enabled / disabled
   |--------------------------------------------------------------------------
   |
   */
  'enabled' => false,

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
  'email' => ''

];
