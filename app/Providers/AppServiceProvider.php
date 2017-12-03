<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    //
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    // Custom Helpers
    require_once __DIR__ . '/../Http/Helpers/auth_helper.php';
    require_once __DIR__ . '/../Http/Helpers/opportunities_helper.php';

  }
}
