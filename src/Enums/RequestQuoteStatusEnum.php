<?php

namespace FriendsOfBotble\RequestQuote\Enums;

use Botble\Base\Facades\Html;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;

class RequestQuoteStatusEnum extends Enum
{
    public const PENDING = 'pending';
    
    public const PROCESSING = 'processing';

    public const COMPLETED = 'completed';

    public static $langPath = 'plugins/fob-request-quote::request-quote.statuses';

    public function toHtml(): HtmlString|string
    {
        return match ($this->value) {
            self::PENDING => Html::tag('span', self::PENDING()->label(), ['class' => 'badge bg-yellow text-yellow-fg']),
            self::PROCESSING => Html::tag('span', self::PROCESSING()->label(), ['class' => 'badge bg-blue text-blue-fg']),
            self::COMPLETED => Html::tag('span', self::COMPLETED()->label(), ['class' => 'badge bg-green text-green-fg']),
            default => parent::toHtml(),
        };
    }
}