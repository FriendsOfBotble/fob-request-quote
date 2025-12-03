@php
    use Botble\Base\Facades\MetaBox;
    use FriendsOfBotble\RequestQuote\Services\RequestQuoteService;

    $productSetting = MetaBox::getMetaData($product, 'request_quote_enabled', true);
    $requestQuoteService = app(RequestQuoteService::class);
    $isEnabled = $productSetting !== '' ? (bool) $productSetting : $requestQuoteService->isEnabledForProduct($product);
@endphp

<div class="mb-3">
    <div class="form-check">
        <input type="hidden" name="request_quote_enabled" value="0">
        <input
            type="checkbox"
            class="form-check-input"
            id="request_quote_enabled"
            name="request_quote_enabled"
            value="1"
            {{ $isEnabled ? 'checked' : '' }}
        >
        <label class="form-check-label" for="request_quote_enabled">
            {{ trans('plugins/fob-request-quote::request-quote.product_settings.enable_label') }}
        </label>
        <p class="form-text">
            {{ trans('plugins/fob-request-quote::request-quote.product_settings.enable_helper') }}
        </p>
    </div>
    @if ($productSetting === '')
        <p class="text-muted small mb-0">
            {{ trans('plugins/fob-request-quote::request-quote.product_settings.inherit_helper') }}
        </p>
    @endif
</div>
