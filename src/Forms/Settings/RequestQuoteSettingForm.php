<?php

namespace FriendsOfBotble\RequestQuote\Forms\Settings;

use Botble\Base\Forms\FieldOptions\CoreIconFieldOption;
use Botble\Base\Forms\FieldOptions\EditorFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CoreIconField;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Setting\Forms\SettingForm;
use FriendsOfBotble\RequestQuote\Http\Requests\Settings\RequestQuoteSettingRequest;
use FriendsOfBotble\RequestQuote\Support\RequestQuoteFields;

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
                    ->helperText(trans('plugins/fob-request-quote::request-quote.enable_request_quote_helper'))
            )
            ->add(
                'request_quote_button_icon',
                CoreIconField::class,
                CoreIconFieldOption::make()
                    ->label(trans('plugins/fob-request-quote::request-quote.button_icon'))
                    ->value(setting('request_quote_button_icon', 'ti ti-file-text'))
                    ->helperText(trans('plugins/fob-request-quote::request-quote.button_icon_helper'))
            )
            ->add(
                'request_quote_show_for_out_of_stock',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-request-quote::request-quote.show_for_out_of_stock'))
                    ->value(setting('request_quote_show_for_out_of_stock', false))
                    ->helperText(trans('plugins/fob-request-quote::request-quote.show_for_out_of_stock_helper'))
            )
            ->add(
                'request_quote_show_always',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-request-quote::request-quote.show_always'))
                    ->value(setting('request_quote_show_always', true))
                    ->helperText(trans('plugins/fob-request-quote::request-quote.show_always_helper'))
            )
            ->add(
                'request_quote_send_confirmation',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-request-quote::request-quote.send_confirmation_email'))
                    ->value(setting('request_quote_send_confirmation', true))
                    ->helperText(trans('plugins/fob-request-quote::request-quote.send_confirmation_email_helper'))
            )
            ->add(
                'field_settings_heading',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content($this->fieldSettingsHtml())
            )
            ->add(
                'request_quote_button_radius',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/fob-request-quote::request-quote.button_radius'))
                    ->value(setting('request_quote_button_radius', 4))
                    ->helperText(trans('plugins/fob-request-quote::request-quote.button_radius_helper'))
            )
            ->add(
                'request_quote_show_form_info',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(trans('plugins/fob-request-quote::request-quote.show_form_info'))
                    ->value(setting('request_quote_show_form_info', false))
                    ->helperText(trans('plugins/fob-request-quote::request-quote.show_form_info_helper'))
            )
            ->add(
                'request_quote_form_info_content',
                EditorField::class,
                EditorFieldOption::make()
                    ->label(trans('plugins/fob-request-quote::request-quote.form_info_content'))
                    ->value(setting('request_quote_form_info_content', ''))
                    ->placeholder(trans('plugins/fob-request-quote::request-quote.form_info_content_placeholder'))
                    ->helperText(trans('plugins/fob-request-quote::request-quote.form_info_content_helper'))
                    ->maxLength(2000)
            );
    }

    protected function fieldSettingsHtml(): string
    {
        $rows = '';

        foreach (RequestQuoteFields::FIELDS as $field) {
            $label = e(RequestQuoteFields::label($field));
            $enabledChecked = RequestQuoteFields::isEnabled($field) ? ' checked' : '';
            $requiredChecked = RequestQuoteFields::isRequired($field) ? ' checked' : '';

            $rows .= <<<HTML
                <tr>
                    <td>
                        <div class="fw-medium">{$label}</div>
                    </td>
                    <td class="text-center">
                        <label class="form-check form-switch justify-content-center mb-0">
                            <input class="form-check-input request-quote-field-toggle" type="checkbox" name="request_quote_enabled_fields[{$field}]" value="1" data-field="{$field}"{$enabledChecked}>
                        </label>
                    </td>
                    <td class="text-center">
                        <label class="form-check form-switch justify-content-center mb-0">
                            <input class="form-check-input request-quote-field-required" type="checkbox" name="request_quote_required_fields[{$field}]" value="1" data-field="{$field}"{$requiredChecked}>
                        </label>
                    </td>
                </tr>
            HTML;
        }

        return sprintf(
            <<<'HTML'
                <div class="request-quote-field-settings mt-4">
                    <div class="mb-3">
                        <h4 class="mb-1">%s</h4>
                        <p class="text-muted mb-0">%s</p>
                    </div>
                    <div class="table-responsive border rounded">
                        <table class="table table-vcenter card-table mb-0">
                            <thead>
                                <tr>
                                    <th>%s</th>
                                    <th class="text-center" style="width: 140px;">%s</th>
                                    <th class="text-center" style="width: 140px;">%s</th>
                                </tr>
                            </thead>
                            <tbody>%s</tbody>
                        </table>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        document.querySelectorAll('.request-quote-field-toggle').forEach(function (toggle) {
                            function syncRequiredState() {
                                var required = document.querySelector('.request-quote-field-required[data-field="' + toggle.dataset.field + '"]');
                                if (!required) {
                                    return;
                                }

                                required.disabled = !toggle.checked;

                                if (!toggle.checked) {
                                    required.checked = false;
                                }
                            }

                            toggle.addEventListener('change', syncRequiredState);
                            syncRequiredState();
                        });
                    });
                </script>
            HTML,
            e(trans('plugins/fob-request-quote::request-quote.field_settings')),
            e(trans('plugins/fob-request-quote::request-quote.field_settings_helper')),
            e(trans('plugins/fob-request-quote::request-quote.field_name')),
            e(trans('plugins/fob-request-quote::request-quote.field_visible')),
            e(trans('plugins/fob-request-quote::request-quote.field_required')),
            $rows
        );
    }
}
