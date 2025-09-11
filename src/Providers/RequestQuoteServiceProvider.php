<?php

namespace FriendsOfBotble\RequestQuote\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Illuminate\Support\ServiceProvider;

class RequestQuoteServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this->setNamespace('plugins/fob-request-quote')
            ->loadAndPublishConfigurations(['permissions', 'email'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes();

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-request-quote')
                        ->priority(125)
                        ->name('plugins/fob-request-quote::request-quote.menu_name')
                        ->icon('ti ti-file-text')
                        ->route('request-quote.index')
                        ->permissions(['request-quote.index'])
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-request-quote-all')
                        ->parentId('cms-plugins-request-quote')
                        ->priority(1)
                        ->name('plugins/fob-request-quote::request-quote.all_requests')
                        ->icon('ti ti-list-details')
                        ->route('request-quote.index')
                        ->permissions(['request-quote.index'])
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-request-quote-settings')
                        ->parentId('cms-plugins-request-quote')
                        ->priority(2)
                        ->name('plugins/fob-request-quote::request-quote.settings_menu')
                        ->icon('ti ti-settings-2')
                        ->route('request-quote.settings')
                        ->permissions(['request-quote.settings'])
                );
        });

        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('request-quote')
                    ->setTitle(trans('plugins/fob-request-quote::request-quote.settings.title'))
                    ->withIcon('ti ti-file-text')
                    ->withDescription(trans('plugins/fob-request-quote::request-quote.settings.description'))
                    ->withPriority(185)
                    ->withRoute('request-quote.settings')
            );
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);

            EmailHandler::addTemplateSettings('fob-request-quote', config('plugins.fob-request-quote.email', []));
        });
    }
}
