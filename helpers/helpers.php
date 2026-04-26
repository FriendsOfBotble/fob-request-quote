<?php

use FriendsOfBotble\RequestQuote\Support\RequestQuoteFields;

if (! function_exists('requestQuoteFieldIsEnabled')) {
    function requestQuoteFieldIsEnabled(string $field): bool
    {
        return RequestQuoteFields::isEnabled($field);
    }
}

if (! function_exists('requestQuoteFieldIsRequired')) {
    function requestQuoteFieldIsRequired(string $field): bool
    {
        return RequestQuoteFields::isRequired($field);
    }
}
