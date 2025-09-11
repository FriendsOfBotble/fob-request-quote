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
        return $this->performUpdate($request->validated());
    }
}
