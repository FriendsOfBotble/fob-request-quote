<?php

namespace FriendsOfBotble\RequestQuote\Forms\Settings;

use Botble\Base\Forms\FieldOptions\CoreIconFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CoreIconField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Setting\Forms\SettingForm;
use FriendsOfBotble\RequestQuote\Http\Requests\Settings\RequestQuoteSettingRequest;

class RequestQuoteSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/fob-request-quote::request-quote.setting_title'))
            ->setSectionDescription(trans('plugins/fob-request-quote::request-quote.settings.description'))
            ->setValidatorClass(RequestQuoteSettingRequest::class)
            ->add(
                'request_quote_enabled',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-request-quote::request-quote.enable_request_quote'))
                    ->value(setting('request_quote_enabled', true))
                    ->toArray()
            )
            ->add(
                'request_quote_receiver_emails',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/fob-request-quote::request-quote.receiver_emails'))
                    ->value(setting('request_quote_receiver_emails', ''))
                    ->placeholder(trans('plugins/fob-request-quote::request-quote.receiver_emails_placeholder'))
                    ->helperText(trans('plugins/fob-request-quote::request-quote.receiver_emails_helper'))
                    ->toArray()
            )
            ->add(
                'request_quote_button_icon',
                CoreIconField::class,
                CoreIconFieldOption::make()
                    ->label(trans('plugins/fob-request-quote::request-quote.button_icon'))
                    ->value(setting('request_quote_button_icon', 'ti ti-file-text'))
                    ->toArray()
            )
            ->add(
                'request_quote_show_for_out_of_stock',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-request-quote::request-quote.show_for_out_of_stock'))
                    ->value(setting('request_quote_show_for_out_of_stock', false))
                    ->toArray()
            )
            ->add(
                'request_quote_show_always',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-request-quote::request-quote.show_always'))
                    ->value(setting('request_quote_show_always', true))
                    ->toArray()
            )
            ->add(
                'request_quote_send_confirmation',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-request-quote::request-quote.send_confirmation_email'))
                    ->value(setting('request_quote_send_confirmation', true))
                    ->toArray()
            )
            ->add(
                'request_quote_button_radius',
                TextField::class,
                TextFieldOption::make()
                    ->label('Button Border Radius (px)')
                    ->value(setting('request_quote_button_radius', 4))
                    ->toArray()
            );
    }
}