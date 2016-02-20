<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Crypt;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //
        parent::boot($router);
        $this->adjustLocale();
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }

    /**
     * Adjust the locale with different browser languages
     */
    private function adjustLocale()
    {
        if (request()->hasCookie('lang')) {
            $this->setLocale(Crypt::decrypt(request()->cookie('lang')));
        } else {
            request()->header('accept-language') ? $this->setLocale(substr(request()->header('accept-language'), 0, 2)) : null;
        }
    }

    /**
     * Switch locale
     *
     * @param $locale
     */
    private function setLocale($locale)
    {
        switch ($locale) {
            case "en":
            case "zh":
                app()->setLocale($locale);
                return;
            default:
                return;
        }
    }
}
