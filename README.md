# Image Lazy Load for Laravel

A Laravel package to help users implement image lazy loading using PHP and JavaScript, optimizing page load speeds by only loading images when they are in view. It runs automatically when you load a view or page. Increase your website performance on page load and save bandwidth.

## Installation

Image Lazy Load for Laravel requires PHP 7.4 or higher. This particular version supports Laravel 8.x, 9.x, 10.x, and 11.x.

To get the latest version, simply require the project using [Composer](https://getcomposer.org):

```sh
composer require debjyotikar001/image-lazy-load
```

## Configuration

Image Lazy Load for Laravel supports optional configuration. To get started, you'll need to publish all vendor assets:

```sh
php artisan vendor:publish --provider="Debjyotikar001\ImageLazyLoad\ImageLazyLoadServiceProvider"
```

This will create a config/imagelazyload.php file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package.

## Register the Middleware
In order to add image lazy loading functionality in Laravel, you need to add the ImgLazyload middleware.

### (Laravel 10 or older)
In `app/Http/Kernel.php` file:

```php
protected $middleware = [
  // other middleware
  \Debjyotikar001\ImageLazyLoad\Middleware\ImgLazyload::class,
];
```
or 
```php
protected $middleware = [
  // other middleware
  'imageLazyLoad',
];
```
You can use the middleware class or middleware alias `imageLazyLoad` in web middleware or route.

### (Laravel 11 or newer)
In `bootstrap/app.php` file:

```php
->withMiddleware(function (Middleware $middleware) {
  $middleware->web(append: [
    // other middleware
    \Debjyotikar001\ImageLazyLoad\Middleware\ImgLazyload::class,
  ]);
})
```
or 
```php
->withMiddleware(function (Middleware $middleware) {
  $middleware->web(append: [
    // other middleware
    'imageLazyLoad',
  ]);
})
```
You can use the middleware class or middleware alias `imageLazyLoad` in web middleware or route.

## Usage
This is how you can use ImageLazyLoad for Laravel in your project.

### Enable
You must set `true` on `enabled` in the `config/imagelazyload.php` file enable image lazy loading functionality. For example:

```php
'enabled' => env('IMGLAZYLOAD_ENABLED', true),
```

### Jquery for Lazy Loading
If you want to use Jquery to handle lazy loading, then set `true` on `jquery` and you can specify its CDN URL on `jqueryUrl` in the `config/imagelazyload.php` file. For example:

```php
'jquery' => env('IMGLAZYLOAD_JQUERY', true),
'jqueryUrl' => env('IMGLAZYLOAD_JQUERY_URL', 'https://code.jquery.com/jquery-3.7.1.min.js'),
```

### Allowed Environments
If you want to disable it in specific environments such as during local development or testing to simplify debugging. Then set environments values in a comma (`,`) separated string in the `config/imagelazyload.php` file, default `local,production,staging`. For example:

```php
'allowed_envs' => env('IMGLAZYLOAD_ALLOWED_ENVS', 'local,production,staging'),
```

### Skip or Ignore specific Routes Urls
If you want to skip or ignore specific routes urls, then you have to set paths in the `config/imagelazyload.php` file. You can use '*' as wildcard. For example:

```php
'skip_urls' => [
    '/',
    'about',
    'user/*',
    '*_dashboard',
    '*/download/*',
  ],
```
#### Example URLs:
- `/`: Home URL will be excluded from minification.
- `about`: This exact URL will be excluded from minification.
- `user/*`: Any URL starting with `user/` (like `user/profile`, `user/settings`) will be excluded.
- `*_dashboard`: Any URL ending with `_dashboard` (like `admin_dashboard`, `user_dashboard`) will be excluded.
- `*/download/*`: Any URL has `download` (like `pdf/download/001`, `image/download/debjyotikar001`) will be excluded.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Contributions are welcome! Please see [CONTRIBUTING](CONTRIBUTING.md) for details on how to get started.

## License

Image Lazy Load is licensed under the [MIT license](LICENSE).

## Support

If you are having general issues with this package, feel free to contact us on [debjyotikar001@gmail.com](mailto:debjyotikar001@gmail.com)
