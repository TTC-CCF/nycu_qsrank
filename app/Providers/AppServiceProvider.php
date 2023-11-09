<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public $output;
    function __construct() {
        $this->output = new \Symfony\Component\Console\Output\ConsoleOutput();
      }
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') == 'local')
            URL::forceScheme('http');
        elseif (env('APP_ENV') == 'production')
            URL::forceScheme('https');
    }
}
