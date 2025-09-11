<?php

namespace FriendsOfBotble\RequestQuote\Http\Requests;

use Botble\Base\Rules\EmailRule;
use Botble\Base\Rules\PhoneNumberRule;
use Botble\Support\Http\Requests\Request;

class RequestQuoteRequest extends Request
{
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:ec_products,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', new EmailRule(), 'max:80'],
            'phone' => ['nullable', new PhoneNumberRule()],
            'company' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'product_id' => trans('plugins/fob-request-quote::request-quote.product'),
            'name' => trans('plugins/fob-request-quote::request-quote.name'),
            'email' => trans('plugins/fob-request-quote::request-quote.email_address'),
            'phone' => trans('plugins/fob-request-quote::request-quote.phone'),
            'company' => trans('plugins/fob-request-quote::request-quote.company'),
            'quantity' => trans('plugins/fob-request-quote::request-quote.quantity'),
            'message' => trans('plugins/fob-request-quote::request-quote.message'),
        ];
    }
}
