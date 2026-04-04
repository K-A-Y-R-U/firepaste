<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Css;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\GeneralSetting;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        FilamentAsset::register([
            Css::make('custom-styles', '/build/assets/filament.css'),
        ]);

        // Compartir configuraciones con vistas Blade (mismo patrón que ya usas)
        View::composer(['layouts.header', 'layouts.footer', 'livewire.header', 'posts.show', 'vip.show'], function ($view) {
            $settings = GeneralSetting::first();
            $siteName = $settings ? $settings->site_name : config('app.name', 'Laravel');
            $moreConfigs = $settings ? json_decode($settings->more_configs, true) : [];
            
            $view->with('siteName', $siteName)
                 ->with('moreConfigs', $moreConfigs);
        });
    }
}