@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="page-header">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ trans('plugins/fob-request-quote::request-quote.name') }}
                    </div>
                    <h2 class="page-title">
                        {{ trans('plugins/fob-request-quote::request-quote.view_request') }} #{{ $quote->id }}
                    </h2>
                </div>
                <div class="col-auto ms-auto">
                    <div class="btn-list">
                        <a href="{{ route('request-quote.index') }}" class="btn">
                            <x-core::icon name="ti ti-arrow-left" />
                            {{ trans('plugins/fob-request-quote::request-quote.back_to_list') }}
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                            <x-core::icon name="ti ti-trash" />
                            {{ trans('plugins/fob-request-quote::request-quote.delete_request') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-lg-8">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">{{ trans('plugins/fob-request-quote::request-quote.email.quote_details') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="datagrid">
                                        <div class="datagrid-title">{{ trans('plugins/fob-request-quote::request-quote.email.customer_information') }}</div>
                                        <div class="datagrid-content">
                                            <dl class="row">
                                                <dt class="col-5">{{ trans('plugins/fob-request-quote::request-quote.name') }}:</dt>
                                                <dd class="col-7 fw-bold">{{ $quote->name }}</dd>
                                                
                                                <dt class="col-5">{{ trans('plugins/fob-request-quote::request-quote.email_address') }}:</dt>
                                                <dd class="col-7">
                                                    <a href="mailto:{{ $quote->email }}" class="text-decoration-none">
                                                        <x-core::icon name="ti ti-mail" class="me-1" />
                                                        {{ $quote->email }}
                                                    </a>
                                                </dd>
                                                
                                                @if($quote->phone)
                                                <dt class="col-5">{{ trans('plugins/fob-request-quote::request-quote.phone') }}:</dt>
                                                <dd class="col-7">
                                                    <x-core::icon name="ti ti-phone" class="me-1" />
                                                    {{ $quote->phone }}
                                                </dd>
                                                @endif
                                                
                                                @if($quote->company)
                                                <dt class="col-5">{{ trans('plugins/fob-request-quote::request-quote.company') }}:</dt>
                                                <dd class="col-7">
                                                    <x-core::icon name="ti ti-building" class="me-1" />
                                                    {{ $quote->company }}
                                                </dd>
                                                @endif

                                                @if($quote->state)
                                                <dt class="col-5">{{ trans('plugins/fob-request-quote::request-quote.state') }}:</dt>
                                                <dd class="col-7">{{ $quote->state }}</dd>
                                                @endif

                                                @if($quote->city)
                                                <dt class="col-5">{{ trans('plugins/fob-request-quote::request-quote.city') }}:</dt>
                                                <dd class="col-7">{{ $quote->city }}</dd>
                                                @endif

                                                @if($quote->address)
                                                <dt class="col-5">{{ trans('plugins/fob-request-quote::request-quote.address') }}:</dt>
                                                <dd class="col-7">{{ $quote->address }}</dd>
                                                @endif
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="datagrid">
                                        <div class="datagrid-title">{{ trans('plugins/fob-request-quote::request-quote.email.product_information') }}</div>
                                        <div class="datagrid-content">
                                            <dl class="row">
                                                <dt class="col-5">{{ trans('plugins/fob-request-quote::request-quote.product') }}:</dt>
                                                <dd class="col-7">
                                                    @if($quote->product)
                                                        <a href="{{ route('products.edit', $quote->product->id) }}" target="_blank" class="text-decoration-none">
                                                            <x-core::icon name="ti ti-external-link" class="me-1" />
                                                            {{ $quote->product->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">--</span>
                                                    @endif
                                                </dd>
                                                
                                                @if($quote->product && $quote->product->sku)
                                                <dt class="col-5">{{ trans('plugins/fob-request-quote::request-quote.sku') }}:</dt>
                                                <dd class="col-7">
                                                    <code>{{ $quote->product->sku }}</code>
                                                </dd>
                                                @endif
                                                
                                                <dt class="col-5">{{ trans('plugins/fob-request-quote::request-quote.quantity') }}:</dt>
                                                <dd class="col-7">
                                                    <span class="badge bg-blue text-blue-fg">{{ number_format($quote->quantity) }}</span>
                                                </dd>

                                                @if($quote->attributes)
                                                <dt class="col-5">{{ trans('plugins/fob-request-quote::request-quote.attributes') }}:</dt>
                                                <dd class="col-7">
                                                    @foreach($quote->attributes as $label => $value)
                                                        @if($value)
                                                            <div><strong>{{ $label }}:</strong> {{ $value }}</div>
                                                        @endif
                                                    @endforeach
                                                </dd>
                                                @endif
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($quote->message)
                            <div class="mt-4">
                                <label class="form-label">
                                    <x-core::icon name="ti ti-message-2" class="me-1" />
                                    {{ trans('plugins/fob-request-quote::request-quote.message') }}
                                </label>
                                <div class="p-3 bg-blue-lt rounded">
                                    {{ $quote->message }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">{{ trans('plugins/fob-request-quote::request-quote.information') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">{{ trans('plugins/fob-request-quote::request-quote.status') }}</div>
                                    <div class="datagrid-content">
                                        {!! $quote->status->toHtml() !!}
                                        
                                        @if ($quote->status == \FriendsOfBotble\RequestQuote\Enums\RequestQuoteStatusEnum::PENDING)
                                            <button type="button" class="btn btn-sm btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#statusChangeModal">
                                                {{ trans('plugins/fob-request-quote::request-quote.mark_as_processing') }}
                                            </button>
                                        @elseif ($quote->status == \FriendsOfBotble\RequestQuote\Enums\RequestQuoteStatusEnum::PROCESSING)
                                            <button type="button" class="btn btn-sm btn-success ms-2" data-bs-toggle="modal" data-bs-target="#statusChangeModal">
                                                {{ trans('plugins/fob-request-quote::request-quote.mark_as_completed') }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">{{ trans('plugins/fob-request-quote::request-quote.submitted_at') }}</div>
                                    <div class="datagrid-content">
                                        <x-core::icon name="ti ti-calendar" class="me-1" />
                                        {{ $quote->created_at->format('M d, Y') }}
                                        <div class="text-muted small">{{ $quote->created_at->format('h:i A') }}</div>
                                    </div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">{{ trans('plugins/fob-request-quote::request-quote.updated_at') }}</div>
                                    <div class="datagrid-content">
                                        <x-core::icon name="ti ti-clock" class="me-1" />
                                        {{ $quote->updated_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <x-core::icon name="ti ti-notes" class="me-1" />
                                {{ trans('plugins/fob-request-quote::request-quote.admin_notes') }}
                            </h3>
                        </div>
                        <form action="{{ route('request-quote.update-notes', $quote->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="mb-3">
                                    <textarea name="admin_notes" 
                                              class="form-control" 
                                              rows="5" 
                                              placeholder="{{ trans('plugins/fob-request-quote::request-quote.add_a_note') }}">{{ $quote->admin_notes }}</textarea>
                                    <small class="form-hint">{{ trans('plugins/fob-request-quote::request-quote.admin_notes_hint', ['hint' => 'Internal notes about this quote request']) }}</small>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <x-core::icon name="ti ti-device-floppy" class="me-1" />
                                    {{ trans('plugins/fob-request-quote::request-quote.save_changes') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Change Confirmation Modal --}}
    @if ($quote->status != \FriendsOfBotble\RequestQuote\Enums\RequestQuoteStatusEnum::COMPLETED)
    <div class="modal modal-blur fade" id="statusChangeModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status 
                    @if($quote->status == \FriendsOfBotble\RequestQuote\Enums\RequestQuoteStatusEnum::PENDING)
                        bg-primary
                    @else
                        bg-success
                    @endif
                "></div>
                <div class="modal-body text-center py-4">
                    <x-core::icon name="ti ti-alert-circle" class="mb-2 text-warning icon-lg" />
                    
                    <h3>{{ trans('plugins/fob-request-quote::request-quote.confirm_status_change') }}</h3>
                    <div class="text-muted">
                        @if ($quote->status == \FriendsOfBotble\RequestQuote\Enums\RequestQuoteStatusEnum::PENDING)
                            {{ trans('plugins/fob-request-quote::request-quote.confirm_mark_as_processing') }}
                        @elseif ($quote->status == \FriendsOfBotble\RequestQuote\Enums\RequestQuoteStatusEnum::PROCESSING)
                            {{ trans('plugins/fob-request-quote::request-quote.confirm_mark_as_completed') }}
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn w-100" data-bs-dismiss="modal">
                                    {{ trans('plugins/fob-request-quote::request-quote.cancel') }}
                                </button>
                            </div>
                            <div class="col">
                                <form action="{{ route('request-quote.update-status', $quote->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    @if ($quote->status == \FriendsOfBotble\RequestQuote\Enums\RequestQuoteStatusEnum::PENDING)
                                        <input type="hidden" name="status" value="{{ \FriendsOfBotble\RequestQuote\Enums\RequestQuoteStatusEnum::PROCESSING }}">
                                        <button type="submit" class="btn btn-primary w-100">
                                            {{ trans('plugins/fob-request-quote::request-quote.confirm') }}
                                        </button>
                                    @elseif ($quote->status == \FriendsOfBotble\RequestQuote\Enums\RequestQuoteStatusEnum::PROCESSING)
                                        <input type="hidden" name="status" value="{{ \FriendsOfBotble\RequestQuote\Enums\RequestQuoteStatusEnum::COMPLETED }}">
                                        <button type="submit" class="btn btn-success w-100">
                                            {{ trans('plugins/fob-request-quote::request-quote.confirm') }}
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    <div class="modal modal-blur fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <x-core::icon name="ti ti-alert-triangle" class="mb-2 text-danger icon-lg" />
                    
                    <h3>{{ trans('plugins/fob-request-quote::request-quote.are_you_sure') }}</h3>
                    <div class="text-muted">
                        {{ trans('plugins/fob-request-quote::request-quote.confirm_delete_message') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn w-100" data-bs-dismiss="modal">
                                    {{ trans('plugins/fob-request-quote::request-quote.cancel') }}
                                </button>
                            </div>
                            <div class="col">
                                <form action="{{ route('request-quote.destroy', $quote->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        {{ trans('plugins/fob-request-quote::request-quote.delete_confirm') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
