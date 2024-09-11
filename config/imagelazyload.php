<?php
/*
 * This file is part of ImageLazyLoad.
 *
 * (c) 2024 Debjyoti Kar <debjyotikar001@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
return [
  /*
  |--------------------------------------------------------------------------
  | Enable Image Lazy Loading
  |--------------------------------------------------------------------------
  |
  | This option allows you to enable or disable lazy loading functionality
  | for images in your application. If set to true, lazy loading will be
  | active. If set to false, images will load normally without lazy loading.
  |
  | Default: true
  |
  */
  'enabled' => env('IMGLAZYLOAD_ENABLED', true),

  /*
  |--------------------------------------------------------------------------
  | Jquery for Lazy Loading
  |--------------------------------------------------------------------------
  |
  | This option determines whether jQuery or plain JavaScript will be used
  | to handle lazy loading. If set to true, the jQuery library will be used,
  | and you can specify its CDN URL below. If set to false, plain JavaScript
  | will handle the lazy loading.
  |
  | Default: false
  |
  */
  'jquery' => env('IMGLAZYLOAD_JQUERY', false),
  'jqueryUrl' => env('IMGLAZYLOAD_JQUERY_URL', 'https://code.jquery.com/jquery-3.7.1.min.js'),
  
];
