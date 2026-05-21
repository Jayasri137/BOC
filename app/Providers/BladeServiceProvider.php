<?php

namespace App\Providers;

use App\Models\Notification;
use App\Repositories\LanguageRepository;
use App\Repositories\NotificationInstanceRepository;
use App\Repositories\SettingRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $setting = SettingRepository::query()->get()->first();
            $languages = Cache::remember('languages', 60 * 24, function () {
                return LanguageRepository::query()->get();
            });

            $data = [
                'name' => config('app.name'),
                'currency' => config('app.currency'),
                'currency_symbol' => config('app.currency_symbol'),
                'logo' => $setting?->logoPath,
                'favicon' => $setting?->faviconPath,
                'footer_text' => $setting?->footer_text,
                'timezone' => config('app.timezone'),
                'currency_position' => $setting?->currency_position,
            ];

            $user = auth()->user();
            $notifications = NotificationInstanceRepository::query()
                ->whereNull('recipient_id')
                ->latest('id')
                ->get();

            $view->with([
                'app_setting' =>
                $data,
                'notificationMessages' =>
                $notifications,
                'languages' => $languages,
            ]);
        });
    }
}
