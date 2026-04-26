<?php

namespace FriendsOfBotble\RequestQuote\Http\Controllers\Settings;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Setting\Http\Controllers\SettingController;
use FriendsOfBotble\RequestQuote\Forms\Settings\RequestQuoteSettingForm;
use FriendsOfBotble\RequestQuote\Http\Requests\Settings\RequestQuoteSettingRequest;

class RequestQuoteSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/fob-request-quote::request-quote.settings.title'));

        return RequestQuoteSettingForm::create()->renderForm();
    }

    public function update(RequestQuoteSettingRequest $request): BaseHttpResponse
    {
        $enabledFields = [];
        $requiredFields = [];
        $enabledFieldsInput = $request->input('request_quote_enabled_fields', []);
        $requiredFieldsInput = $request->input('request_quote_required_fields', []);

        foreach (['name', 'email', 'phone', 'company', 'quantity', 'message', 'state', 'city', 'address', 'attributes'] as $field) {
            if (isset($enabledFieldsInput[$field])) {
                $enabledFields[$field] = '1';
            }

            if (isset($requiredFieldsInput[$field])) {
                $requiredFields[$field] = '1';
            }
        }

        $settings = $request->validated();
        $settings['request_quote_enabled_fields'] = $enabledFields;
        $settings['request_quote_required_fields'] = $requiredFields;

        return $this->performUpdate($settings);
    }
}
