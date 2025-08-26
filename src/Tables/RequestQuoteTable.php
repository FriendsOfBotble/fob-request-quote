<?php

namespace FriendsOfBotble\RequestQuote\Tables;

use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\ViewAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\EmailColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use FriendsOfBotble\RequestQuote\Models\RequestQuote;
use Illuminate\Database\Eloquent\Builder;

class RequestQuoteTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(RequestQuote::class)
            ->addActions([
                ViewAction::make()->route('request-quote.show'),
                DeleteAction::make()->route('request-quote.destroy'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('request-quote.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()
                    ->route('request-quote.show'),
                EmailColumn::make(),
                FormattedColumn::make('product_id')
                    ->title(trans('plugins/fob-request-quote::request-quote.product'))
                    ->searchable(false)
                    ->orderable(false)
                    ->renderUsing(function (FormattedColumn $column) {
                        $item = $column->getItem();
                        if ($item->product) {
                            return sprintf(
                                '<a href="%s" target="_blank">%s</a>',
                                route('products.edit', $item->product->original_product->id),
                                e($item->product->name)
                            );
                        }
                        return null;
                    })
                    ->withEmptyState(),
                Column::make('quantity')
                    ->title(trans('plugins/fob-request-quote::request-quote.quantity'))
                    ->searchable(false),
                StatusColumn::make(),
                CreatedAtColumn::make()
                    ->title(trans('plugins/fob-request-quote::request-quote.submitted_at')),
            ])
            ->addFilters([])
            ->queryUsing(function (Builder $query) {
                $query->with(['product']);
            });
    }
}
