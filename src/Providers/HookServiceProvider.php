<?php

namespace FriendsOfBotble\RequestQuote\Providers;

use Botble\Base\Facades\MetaBox;
use Botble\Ecommerce\Models\Product;
use FriendsOfBotble\RequestQuote\Services\RequestQuoteService;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter(ECOMMERCE_PRODUCT_DETAIL_EXTRA_HTML, function ($html, $product) {
            $requestQuoteService = app(RequestQuoteService::class);

            if ($requestQuoteService->isEnabledForProduct($product)) {
                return $html . view('plugins/fob-request-quote::button', compact('product'));
            }

            return $html;
        }, 200, 2);

        add_filter(THEME_FRONT_FOOTER, function (?string $data): ?string {
            if (setting('request_quote_enabled', true)) {
                return $data . view('plugins/fob-request-quote::modal')->render();
            }

            return $data;
        }, 200);

        add_action(BASE_ACTION_META_BOXES, function ($context, $object) {
            if (get_class($object) === Product::class && $context === 'advanced') {
                MetaBox::addMetaBox(
                    'request_quote_product_box',
                    trans('plugins/fob-request-quote::request-quote.product_settings.title'),
                    function () use ($object) {
                        return view('plugins/fob-request-quote::product-settings', [
                            'product' => $object,
                        ]);
                    },
                    get_class($object),
                    $context
                );
            }
        }, 30, 2);

        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, function ($type, $request, $object) {
            if (get_class($object) === Product::class) {
                $this->saveProductSettings($object, $request);
            }
        }, 30, 3);

        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, function ($type, $request, $object) {
            if (get_class($object) === Product::class) {
                $this->saveProductSettings($object, $request);
            }
        }, 30, 3);
    }

    protected function saveProductSettings(Product $product, $request): void
    {
        $requestQuoteEnabled = $request->boolean(
            'request_quote_enabled',
            (bool) setting('request_quote_show_always', true)
        );

        MetaBox::saveMetaBoxData($product, 'request_quote_enabled', $requestQuoteEnabled);
    }
}
