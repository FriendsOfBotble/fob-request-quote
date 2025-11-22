<?php

namespace FriendsOfBotble\RequestQuote\Http\Controllers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\EmailHandler;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use FriendsOfBotble\RequestQuote\Http\Requests\RequestQuoteRequest;
use FriendsOfBotble\RequestQuote\Models\RequestQuote;
use Throwable;

class PublicRequestQuoteController extends BaseController
{
    public function submit(RequestQuoteRequest $request, BaseHttpResponse $response)
    {
        $quote = RequestQuote::query()->create($request->validated());

        $quote->load('product');

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
                $receiverEmails = get_admin_email()->all();
            } else {
                $receiverEmails = array_map('trim', explode(',', $receiverEmails));
                $receiverEmails = array_filter($receiverEmails, fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL));
            }

            if (! empty($receiverEmails)) {
                $adminTemplateContent = $emailHandler->getTemplateContent('admin-notification', 'plugins');

                if (! empty($adminTemplateContent)) {
                    $emailHandler->sendUsingTemplate('admin-notification', $receiverEmails);
                }
            }

            if (setting('request_quote_send_confirmation', true)) {
                $customerTemplateContent = $emailHandler->getTemplateContent('customer-confirmation', 'plugins');

                if (! empty($customerTemplateContent)) {
                    $emailHandler->sendUsingTemplate('customer-confirmation', $quote->email);
                }
            }
        } catch (Throwable $e) {
            BaseHelper::logError($e);
        }
    }
}
