<?php

namespace FriendsOfBotble\RequestQuote\Http\Controllers;

use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use FriendsOfBotble\RequestQuote\Http\Requests\RequestQuoteRequest;
use FriendsOfBotble\RequestQuote\Models\RequestQuote;

class PublicRequestQuoteController extends BaseController
{
    public function submit(RequestQuoteRequest $request, BaseHttpResponse $response)
    {
        try {
            $quote = RequestQuote::query()->create($request->validated());
            
            $quote->load('product');
            
            $emailVariables = [
                'quote_name' => $quote->name,
                'quote_email' => $quote->email,
                'quote_phone' => $quote->phone ?: '--',
                'quote_company' => $quote->company ?: '--',
                'quote_quantity' => $quote->quantity,
                'quote_message' => $quote->message ?: '--',
                'product_name' => $quote->product->name ?? '--',
                'product_sku' => $quote->product->sku ?? '--',
                'product_url' => $quote->product ? route('public.single', $quote->product->url) : '#',
                'admin_link' => route('request-quote.show', $quote->id),
                'site_title' => setting('admin_title', config('app.name')),
            ];
            
            $emailHandler = EmailHandler::setModule('fob-request-quote')
                ->setVariableValues($emailVariables);
            
            $receiverEmails = setting('request_quote_receiver_emails', '');
            
            if (empty($receiverEmails)) {
                $receiverEmails = get_admin_email();
            } else {
                $receiverEmails = array_map('trim', explode(',', $receiverEmails));
                $receiverEmails = array_filter($receiverEmails, function($email) {
                    return filter_var($email, FILTER_VALIDATE_EMAIL);
                });
            }
            
            if (!empty($receiverEmails)) {
                $emailHandler->sendUsingTemplate('admin-notification', $receiverEmails);
            }
            
            if (setting('request_quote_send_confirmation', true)) {
                $emailHandler->sendUsingTemplate('customer-confirmation', $quote->email);
            }
            
            return $response
                ->setMessage(trans('plugins/fob-request-quote::request-quote.success_message'));
        } catch (\Exception $e) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/fob-request-quote::request-quote.error_message'));
        }
    }
}