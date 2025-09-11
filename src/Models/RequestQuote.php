<?php

namespace FriendsOfBotble\RequestQuote\Models;

use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Models\Product;
use FriendsOfBotble\RequestQuote\Enums\RequestQuoteStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestQuote extends BaseModel
{
    protected $table = 'fob_request_quotes';

    protected $fillable = [
        'product_id',
        'name',
        'email',
        'phone',
        'company',
        'quantity',
        'message',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'status' => RequestQuoteStatusEnum::class,
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
