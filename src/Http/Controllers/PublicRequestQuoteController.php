<?php

namespace FriendsOfBotble\RequestQuote\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use FriendsOfBotble\RequestQuote\Http\Requests\RequestQuoteRequest;
use FriendsOfBotble\RequestQuote\Models\RequestQuote;
use FriendsOfBotble\RequestQuote\Services\RequestQuoteService;
use Throwable;

class PublicRequestQuoteController extends BaseController
{
    public function submit(
        RequestQuoteRequest $request,
        BaseHttpResponse $response,
        RequestQuoteService $requestQuoteService
    ) {
        $product = Product::query()->find($request->input('product_id'));

        if (! $product || ! $requestQuoteService->isEnabledForProduct($product)) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/fob-request-quote::request-quote.product_disabled'));
        }

        $quote = RequestQuote::query()->create($this->quotePayload($request->validated()));

        $quote->setRelation('product', $product);

        $this->sendEmails($quote);

        return $response
            ->setMessage(trans('plugins/fob-request-quote::request-quote.success_message'));
    }

    protected function sendEmails(RequestQuote $quote): void
    {
        try {
            $emailVariables = [
                'quote_name' => $quote->name,
                'quote_email' => $quote->email,
                'quote_phone' => $quote->phone ?: '--',
                'quote_company' => $quote->company ?: '--',
                'quote_quantity' => $quote->quantity,
                'quote_state' => $quote->state ?: '--',
                'quote_city' => $quote->city ?: '--',
                'quote_address' => $quote->address ?: '--',
                'quote_attributes' => $this->formatAttributes($quote->attributes),
                'quote_message' => $quote->message ?: '--',
                'product_name' => $quote->product->name ?? '--',
                'product_sku' => $quote->product->sku ?? '--',
                'product_url' => $quote->product ? route('public.single', $quote->product->url) : '#',
                'admin_link' => route('request-quote.show', $quote->id),
                'site_title' => setting('admin_title', config('app.name')),
            ];

            $emailHandler = EmailHandler::setModule('fob-request-quote')
                ->setVariableValues($emailVariables);

            $receiverEmails = $this->resolveReceiverEmails($quote);

            if (! empty($receiverEmails)) {
                $adminTemplateContent = $emailHandler->getTemplateContent('admin-notification', 'plugins');

                if (! empty($adminTemplateContent)) {
                    $emailHandler->sendUsingTemplate('admin-notification', $receiverEmails);
                }
            }

            if ($quote->email && setting('request_quote_send_confirmation', true)) {
                $customerTemplateContent = $emailHandler->getTemplateContent('customer-confirmation', 'plugins');

                if (! empty($customerTemplateContent)) {
                    $emailHandler->sendUsingTemplate('customer-confirmation', $quote->email);
                }
            }
        } catch (Throwable $e) {
            BaseHelper::logError($e);
        }
    }

    protected function quotePayload(array $payload): array
    {
        if (empty($payload['quantity'])) {
            $payload['quantity'] = 1;
        }

        $payload['state'] = $this->resolveLocationName($payload['state'] ?? null, 'Botble\\Location\\Models\\State');
        $payload['city'] = $this->resolveLocationName($payload['city'] ?? null, 'Botble\\Location\\Models\\City');

        $payload['attributes'] = array_filter(
            $payload['attributes'] ?? [],
            fn ($value) => filled($value)
        );

        return $payload;
    }

    protected function resolveLocationName(?string $value, string $modelClass): ?string
    {
        if (! $value || ! class_exists($modelClass) || ! is_numeric($value)) {
            return $value;
        }

        return $modelClass::query()->whereKey($value)->value('name') ?: $value;
    }

    protected function resolveReceiverEmails(RequestQuote $quote): array
    {
        $vendorEmail = $quote->product?->original_product?->store?->email
            ?: $quote->product?->store?->email;

        if ($vendorEmail && filter_var($vendorEmail, FILTER_VALIDATE_EMAIL)) {
            return [$vendorEmail];
        }

        return EcommerceHelper::getAdminNotificationEmails();
    }

    protected function formatAttributes(?array $attributes): string
    {
        if (empty($attributes)) {
            return '--';
        }

        return collect($attributes)
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value, $label) => sprintf('%s: %s', $label, $value))
            ->implode("\n");
    }
}
