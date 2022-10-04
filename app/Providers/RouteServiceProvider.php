<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Tightenco\Ziggy\BladeRouteGenerator;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        $this->configs();
        $this->translations();
        $this->ziggy();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

    protected function configs(): void
    {
        Route::get('js/configs.js', function () {
            $configs['lang'] = config('app.locale');
            $configs['rtl'] = config('app.rtl');
            $configs['version'] = config('app.version');
            $configs['name'] = config('app.name');

            return response('window._app = '.json_encode($configs).';')
                ->header('Content-Type', 'text/javascript');
        })->name('configs.js');
    }

    protected function translations(): void
    {
        Route::get('js/translations.js', function () {
            /*$strings = \Cache::rememberForever("lang_{$lang}.js", function () use ($lang) {
                $strings = [];
                foreach (glob(lang_path($lang).DIRECTORY_SEPARATOR.'*.php') as $file) {
                    $strings[basename($file, '.php')] = require $file;
                }
                return $strings;
            });*/

            $lang = config('app.locale');
            $strings = [];

            foreach (glob(lang_path($lang).DIRECTORY_SEPARATOR.'*.php') as $file) {
                $strings[basename($file, '.php')] = require $file;
            }

            if (file_exists(lang_path("{$lang}.json"))) {
                $json = \File::get(lang_path("{$lang}.json"));
                $strings['json'] = json_decode($json, true);
            }

            return response('window.i18n = '.json_encode($strings).';')
                ->header('Content-Type', 'text/javascript');
        })->name('translations.js');
    }

    protected function ziggy(): void
    {
        Route::get('js/ziggy.js', function () {
            return response(
                trim(str_replace(
                    ['<script type="text/javascript">', '</script>', "\n"],
                    '',
                    app(BladeRouteGenerator::class)->generate()
                ))
            )->header('Content-Type', 'text/javascript');
        })->name('ziggy.js');
    }
}
