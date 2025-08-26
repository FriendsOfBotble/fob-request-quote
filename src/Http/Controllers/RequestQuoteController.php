<?php

namespace FriendsOfBotble\RequestQuote\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use FriendsOfBotble\RequestQuote\Enums\RequestQuoteStatusEnum;
use FriendsOfBotble\RequestQuote\Models\RequestQuote;
use FriendsOfBotble\RequestQuote\Tables\RequestQuoteTable;
use Illuminate\Http\Request;

class RequestQuoteController extends BaseController
{
    public function index(RequestQuoteTable $table)
    {
        $this->pageTitle(trans('plugins/fob-request-quote::request-quote.requests'));

        return $table->renderTable();
    }

    public function show($quote)
    {
        $quote = RequestQuote::query()->with('product')->findOrFail($quote);

        $this->pageTitle(trans('plugins/fob-request-quote::request-quote.view_request'));

        return view('plugins/fob-request-quote::show', compact('quote'));
    }

    public function destroy($quote, BaseHttpResponse $response)
    {
        $quote = RequestQuote::query()->findOrFail($quote);
        $quote->delete();

        return $response
            ->setNextUrl(route('request-quote.index'))
            ->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function updateNotes($quote, Request $request, BaseHttpResponse $response)
    {
        $quote = RequestQuote::query()->findOrFail($quote);
        $quote->admin_notes = $request->input('admin_notes');
        $quote->save();

        return $response
            ->setPreviousUrl(route('request-quote.show', $quote))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function updateStatus($quote, Request $request, BaseHttpResponse $response)
    {
        $quote = RequestQuote::query()->findOrFail($quote);
        
        $status = $request->input('status');
        if (in_array($status, RequestQuoteStatusEnum::values())) {
            $quote->status = $status;
            $quote->save();
        }

        return $response
            ->setPreviousUrl(route('request-quote.show', $quote))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}