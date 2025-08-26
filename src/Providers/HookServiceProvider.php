<?php

namespace FriendsOfBotble\RequestQuote\Providers;

use Botble\Ecommerce\Models\Product;
use Illuminate\Support\ServiceProvider;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_filter(ECOMMERCE_PRODUCT_DETAIL_EXTRA_HTML, function ($html, $product) {
            if ($product instanceof Product && setting('request_quote_enabled', true)) {
                $showForOutOfStock = setting('request_quote_show_for_out_of_stock', false);
                $showAlways = setting('request_quote_show_always', true);
                
                if ($showAlways || ($showForOutOfStock && $product->isOutOfStock())) {
                    return $html . view('plugins/fob-request-quote::button', compact('product'));
                }
            }

            return $html;
        }, 200, 2);
        
        add_filter(THEME_FRONT_FOOTER, function (?string $data): ?string {
            if (setting('request_quote_enabled', true)) {
                return $data . view('plugins/fob-request-quote::modal')->render();
            }
            
            return $data;
        }, 200);
    }
}