<?php

namespace Debjyotikar001\ImageLazyLoad;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Debjyotikar001\ImageLazyLoad\Middleware\ImgLazyload;

class ImageLazyLoadServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap the application events.
   */
  public function boot(Router $router)
  {
    $router->aliasMiddleware('imageLazyLoad', ImgLazyload::class);
    $this->publishes([
      __DIR__.'/../config/imagelazyload.php' => config_path('imagelazyload.php'),
    ], 'config');
  }

  /**
   * Register the service provider.
   */
  public function register()
  {
    $this->mergeConfigFrom(__DIR__.'/../config/imagelazyload.php', 'imagelazyload.php');
  }
}
