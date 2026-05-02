<?php

use Botble\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'FriendsOfBotble\RequestQuote\Http\Controllers'], function (): void {
    AdminHelper::registerRoutes(function (): void {
        Route::group(['prefix' => 'request-quotes', 'as' => 'request-quote.'], function (): void {
            Route::resource('', 'RequestQuoteController')
                ->except(['create', 'store', 'edit', 'update'])
                ->parameters(['' => 'quote']);

            Route::get('{quote}', [
                'as' => 'show',
                'uses' => 'RequestQuoteController@show',
                'permission' => 'request-quote.index',
            ])->wherePrimaryKey('quote');

            Route::put('{quote}/notes', [
                'as' => 'update-notes',
                'uses' => 'RequestQuoteController@updateNotes',
                'permission' => 'request-quote.index',
            ]);

            Route::put('{quote}/status', [
                'as' => 'update-status',
                'uses' => 'RequestQuoteController@updateStatus',
                'permission' => 'request-quote.index',
            ]);
        });

        Route::group(['prefix' => 'settings'], function (): void {
            Route::get('request-quote', [
                'as' => 'request-quote.settings',
                'uses' => 'Settings\RequestQuoteSettingController@edit',
                'permission' => 'request-quote.settings',
            ]);

            Route::put('request-quote', [
                'as' => 'request-quote.settings.update',
                'uses' => 'Settings\RequestQuoteSettingController@update',
                'permission' => 'request-quote.settings',
            ]);
        });
    });

    Route::group(['prefix' => 'request-quote', 'as' => 'public.request-quote.'], function (): void {
        Route::post('submit', [
            'as' => 'submit',
            'uses' => 'PublicRequestQuoteController@submit',
        ]);
    });
});
