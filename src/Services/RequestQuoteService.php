<?php

namespace FriendsOfBotble\RequestQuote\Services;

use Botble\Base\Facades\MetaBox;
use Botble\Ecommerce\Models\Product;

class RequestQuoteService
{
    public function isEnabledForProduct($product): bool
    {
        if (! $product instanceof Product) {
            return false;
        }

        if (! setting('request_quote_enabled', true)) {
            return false;
        }

        $showAlways = (bool) setting('request_quote_show_always', true);
        $showForOutOfStock = (bool) setting('request_quote_show_for_out_of_stock', false);

        $productSetting = MetaBox::getMetaData($product, 'request_quote_enabled', true);

        if ($productSetting !== '') {
            return (bool) $productSetting;
        }

        if ($showAlways) {
            return true;
        }

        if ($showForOutOfStock && $product->isOutOfStock()) {
            return true;
        }

        return false;
    }
}
