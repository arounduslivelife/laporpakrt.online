<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $baseUrl = config('app.url', 'http://localhost');
        \Illuminate\Support\Facades\URL::forceRootUrl($baseUrl);

        // The app is served from a subfolder (e.g. http://localhost/laporpakrt/public).
        // Livewire's getUpdateUri() builds a HOST-relative path from the route URI
        // (UrlGenerator::toRoute(..., absolute=false)), so the browser-resolved URL
        // would miss the subfolder and 404. To fix this we register the update route
        // WITH the subfolder prefix so the generated data-update-uri is correct.
        //
        // However, Laravel strips the subfolder base (detected from SCRIPT_NAME) from
        // the incoming request before route matching, so the POST actually arrives as
        // "/livewire/update". We therefore also register a catch-all route at that
        // stripped path so the request reaches the Livewire handler. Both routes use
        // the "web" middleware group so session/CSRF handling works for Livewire.
        $basePath = trim((string) parse_url($baseUrl, PHP_URL_PATH), '/');

        if ($basePath !== '') {
            \Livewire\Livewire::setUpdateRoute(function ($handle) use ($basePath) {
                return \Illuminate\Support\Facades\Route::post('/' . $basePath . '/livewire/update', $handle)
                    ->middleware('web');
            });

            \Illuminate\Support\Facades\Route::post('/livewire/update', [
                \Livewire\Mechanisms\HandleRequests\HandleRequests::class, 'handleUpdate',
            ])->middleware('web');
        }
    }
}
