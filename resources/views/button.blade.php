@php
    $buttonIcon = setting('request_quote_button_icon', 'ti ti-file-text');
@endphp

<div class="request-quote-wrapper mt-3 mb-3">
    <button type="button" 
            class="btn btn-outline-primary request-quote-btn" 
            data-bs-toggle="modal" 
            data-bs-target="#requestQuoteModal"
            data-product-id="{{ $product->id }}"
            data-product-name="{{ $product->name }}"
            data-product-sku="{{ $product->sku }}">
        @if($buttonIcon)
            {!! BaseHelper::renderIcon($buttonIcon) !!}
        @endif
        <span>{{ trans('plugins/fob-request-quote::request-quote.button_text') }}</span>
    </button>
</div>

<style>
.request-quote-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: {{ setting('request_quote_button_radius', 4) }}px !important;
}

.request-quote-btn svg {
    width: 18px;
    height: 18px;
    margin: 0;
}
</style>
