<?php

namespace FriendsOfBotble\RequestQuote\Http\Requests;

use Botble\Base\Rules\EmailRule;
use Botble\Support\Http\Requests\Request;
use Closure;
use FriendsOfBotble\RequestQuote\Support\RequestQuoteFields;
use Illuminate\Validation\Rule;

class RequestQuoteRequest extends Request
{
    public function rules(): array
    {
        $rules = [
            'product_id' => ['required', 'exists:ec_products,id'],
            'request_quote_hp' => ['prohibited'],
            'name' => $this->fieldRules('name', ['string', 'max:255']),
            'email' => $this->fieldRules('email', [
                'regex:/^[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}$/i',
                new EmailRule,
                'max:80',
            ]),
            'phone' => $this->fieldRules('phone', [
                'regex:/^[0-9]{9,20}$/',
                $this->validPhoneNumber(),
            ]),
            'company' => $this->fieldRules('company', ['string', 'max:255']),
            'quantity' => $this->fieldRules('quantity', ['integer', 'min:1']),
            'state' => $this->fieldRules('state', ['string', 'max:255']),
            'city' => $this->fieldRules('city', ['string', 'max:255']),
            'address' => $this->fieldRules('address', ['string', 'max:255']),
            'attributes' => $this->fieldRules('attributes', ['array', 'max:20']),
            'attributes.*' => ['nullable', 'string', 'max:255'],
            'message' => $this->fieldRules('message', ['string', 'max:1000']),
        ];

        if (! RequestQuoteFields::isEnabled('attributes')) {
            $rules['attributes.*'] = [Rule::excludeIf(true)];
        }

        return $rules;
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
            'state' => trans('plugins/fob-request-quote::request-quote.state'),
            'city' => trans('plugins/fob-request-quote::request-quote.city'),
            'address' => trans('plugins/fob-request-quote::request-quote.address'),
            'attributes' => trans('plugins/fob-request-quote::request-quote.attributes'),
            'message' => trans('plugins/fob-request-quote::request-quote.message'),
        ];
    }

    protected function fieldRules(string $field, array $rules): array
    {
        if (! RequestQuoteFields::isEnabled($field)) {
            return [Rule::excludeIf(true)];
        }

        return array_merge(
            [requestQuoteFieldIsRequired($field) ? 'required' : 'nullable'],
            $rules
        );
    }

    protected function validPhoneNumber(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            if (! is_string($value) || $value === '') {
                return;
            }

            $digits = preg_replace('/\D+/', '', $value);

            if ($digits !== $value || strlen($digits) < 9 || preg_match('/^(\d)\1+$/', $digits)) {
                $fail(trans('validation.regex'));
            }
        };
    }
}
