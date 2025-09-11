<?php

namespace FriendsOfBotble\RequestQuote\Http\Requests\Settings;

use Botble\Support\Http\Requests\Request;

class RequestQuoteSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'request_quote_enabled' => ['nullable', 'boolean'],
            'request_quote_receiver_emails' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (! empty($value)) {
                    $emails = array_map('trim', explode(',', $value));
                    foreach ($emails as $email) {
                        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $fail("The email '$email' is not a valid email address.");
                        }
                    }
                }
            }],
            'request_quote_button_icon' => ['nullable', 'string', 'max:50'],
            'request_quote_show_for_out_of_stock' => ['nullable', 'boolean'],
            'request_quote_show_always' => ['nullable', 'boolean'],
            'request_quote_send_confirmation' => ['nullable', 'boolean'],
            'request_quote_button_radius' => ['nullable', 'integer', 'min:0', 'max:50'],
            'request_quote_show_form_info' => ['nullable', 'boolean'],
            'request_quote_form_info_content' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
