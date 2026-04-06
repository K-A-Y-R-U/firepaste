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

        View::composer(['layouts.header', 'layouts.footer', 'livewire.header', 'posts.show', 'vip.show'], function ($view) {
            try {
                $settings = GeneralSetting::first();
                $siteName = $settings?->site_name ?? config('app.name', 'Firepaste');
                $moreConfigs = $settings ? (json_decode($settings->more_configs, true) ?? []) : [];
            } catch (\Exception $e) {
                $siteName = config('app.name', 'Firepaste');
                $moreConfigs = [];
            }

            $view->with('siteName', $siteName)
                 ->with('moreConfigs', $moreConfigs);
        });
    }
}