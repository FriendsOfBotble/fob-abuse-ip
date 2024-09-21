<?php

namespace FriendsOfBotble\AbuseIP\Providers;

use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use FriendsOfBotble\AbuseIP\Commands\UpdateAbuseIps;
use FriendsOfBotble\AbuseIP\Http\Middleware\AbuseIP;

class AbuseIPServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/fob-abuse-ip')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadAndPublishTranslations()
            ->loadRoutes();

        if ($this->app->runningInConsole()) {
            $this->commands([UpdateAbuseIps::class]);
        }

        if (setting('fob_abuse_ip_enabled', true)) {
            $this->app['router']->pushMiddlewareToGroup('web', AbuseIP::class);
        }

        PanelSectionManager::default()->beforeRendering(function () {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('fob-abuse-ip-settings')
                    ->setTitle(trans('plugins/fob-abuse-ip::abuse-ip.settings.title'))
                    ->withIcon('ti ti-wall-off')
                    ->withDescription(trans('plugins/fob-abuse-ip::abuse-ip.settings.description'))
                    ->withPriority(120)
                    ->withRoute('abuse-ip.settings')
            );
        });
    }
}
