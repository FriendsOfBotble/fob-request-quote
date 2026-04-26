<?php

namespace FriendsOfBotble\RequestQuote\Support;

use Illuminate\Support\Arr;

class RequestQuoteFields
{
    public const FIELDS = [
        'name',
        'email',
        'phone',
        'company',
        'quantity',
        'message',
        'state',
        'city',
        'address',
        'attributes',
    ];

    public static function enabledFields(): array
    {
        return self::normalizeSetting(
            setting('request_quote_enabled_fields', self::defaultEnabledFields()),
            self::defaultEnabledFields()
        );
    }

    public static function requiredFields(): array
    {
        return self::normalizeSetting(
            setting('request_quote_required_fields', self::defaultRequiredFields()),
            self::defaultRequiredFields()
        );
    }

    public static function isEnabled(string $field): bool
    {
        return (bool) Arr::get(self::enabledFields(), $field, false);
    }

    public static function isRequired(string $field): bool
    {
        return self::isEnabled($field) && (bool) Arr::get(self::requiredFields(), $field, false);
    }

    public static function label(string $field): string
    {
        $translationKey = match ($field) {
            'email' => 'email_address',
            default => $field,
        };

        return trans("plugins/fob-request-quote::request-quote.$translationKey");
    }

    public static function defaultEnabledFields(): array
    {
        return array_fill_keys(self::FIELDS, '1');
    }

    public static function defaultRequiredFields(): array
    {
        return [
            'name' => '1',
            'email' => '1',
            'quantity' => '1',
            'state' => '1',
            'city' => '1',
            'address' => '1',
        ];
    }

    protected static function normalizeSetting(mixed $value, array $default): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $value = $decoded;
            }
        }

        if (! is_array($value)) {
            return $default;
        }

        $normalized = [];

        foreach (self::FIELDS as $field) {
            if (Arr::get($value, $field)) {
                $normalized[$field] = '1';
            }
        }

        return $normalized;
    }
}
