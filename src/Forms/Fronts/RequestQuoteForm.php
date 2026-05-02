<?php

namespace FriendsOfBotble\RequestQuote\Forms\Fronts;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\EmailFieldOption;
use Botble\Base\Forms\FieldOptions\HiddenFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\HiddenField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Theme\FormFront;
use FriendsOfBotble\RequestQuote\Http\Requests\RequestQuoteRequest;
use FriendsOfBotble\RequestQuote\Models\RequestQuote;
use FriendsOfBotble\RequestQuote\Support\RequestQuoteFields;

class RequestQuoteForm extends FormFront
{
    protected string $errorBag = 'request_quote';

    public static function formTitle(): string
    {
        return trans('plugins/fob-request-quote::request-quote.modal_title');
    }

    public function setup(): void
    {
        $this
            ->contentOnly()
            ->setUrl(route('public.request-quote.submit'))
            ->setFormOption('id', 'requestQuoteForm')
            ->setFormOption('class', 'request-quote-form')
            ->setValidatorClass(RequestQuoteRequest::class)
            ->model(RequestQuote::class)
            ->add(
                'product_id',
                HiddenField::class,
                HiddenFieldOption::make()
                    ->value('')
                    ->addAttribute('id', 'quote_product_id')
            )
            ->add(
                'request_quote_hp',
                TextField::class,
                TextFieldOption::make()
                    ->label('')
                    ->cssClass('form-control')
                    ->addAttribute('tabindex', '-1')
                    ->addAttribute('autocomplete', 'off')
                    ->wrapperAttributes(['style' => 'display:none'])
            )
            ->add(
                'product_display',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content(
                        '<div class="mb-3">
                            <p class="text-muted mb-3">
                                <strong>'.trans('plugins/fob-request-quote::request-quote.product').':</strong>
                                <span id="quote_product_name">-</span>
                                <br><small class="text-muted">'.trans('plugins/fob-request-quote::request-quote.sku').': <span id="quote_product_sku">-</span></small>
                            </p>
                        </div>'
                    )
            );

        $this->addBasicFields();
        $this->addLocationFields();
        $this->addAttributesField();
        $this->addMessageField();

        $this
            ->add(
                'messages',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content(
                        '<div class="alert alert-info d-none" id="quoteSuccessMessage">'.
                        trans('plugins/fob-request-quote::request-quote.success_message').
                        '</div>
                        <div class="alert alert-danger d-none" id="quoteErrorMessage"></div>'
                    )
            )
            ->when(setting('request_quote_show_form_info', false) && setting('request_quote_form_info_content'), function (FormAbstract $form) {
                $form
                    ->add(
                        'form_info',
                        HtmlField::class,
                        HtmlFieldOption::make()
                            ->content(BaseHelper::clean(setting('request_quote_form_info_content')))
                    );
            });
    }

    protected function addBasicFields(): void
    {
        $this->add('row_start_1', HtmlField::class, HtmlFieldOption::make()->content('<div class="row">'));

        $this->addTextColumn('name', TextField::class, TextFieldOption::make()
            ->placeholder(trans('plugins/fob-request-quote::request-quote.name_placeholder'))
            ->addAttribute('id', 'quote_name'));

        $this->addTextColumn('email', EmailField::class, EmailFieldOption::make()
            ->placeholder(trans('plugins/fob-request-quote::request-quote.email_placeholder'))
            ->addAttribute('id', 'quote_email')
            ->addAttribute('pattern', '^[A-Za-z0-9._%+\\-]+@[A-Za-z0-9.\\-]+\\.[A-Za-z]{2,}$'));

        $this->addTextColumn('phone', TextField::class, TextFieldOption::make()
            ->placeholder(trans('plugins/fob-request-quote::request-quote.phone_placeholder'))
            ->addAttribute('id', 'quote_phone')
            ->addAttribute('type', 'tel')
            ->addAttribute('inputmode', 'numeric')
            ->addAttribute('pattern', '^[0-9]{9,20}$')
            ->addAttribute('minlength', '9')
            ->addAttribute('maxlength', '20'));

        $this->addTextColumn('company', TextField::class, TextFieldOption::make()
            ->placeholder(trans('plugins/fob-request-quote::request-quote.company_placeholder'))
            ->addAttribute('id', 'quote_company'));

        $this->add('row_end_1', HtmlField::class, HtmlFieldOption::make()->content('</div>'));

        if (RequestQuoteFields::isEnabled('quantity')) {
            $this->add('quantity_wrapper_start', HtmlField::class, HtmlFieldOption::make()->content('<div class="mb-3">'))
                ->add(
                    'quantity',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(RequestQuoteFields::label('quantity'))
                        ->cssClass('form-control')
                        ->labelAttributes(['class' => $this->labelClass('quantity')])
                        ->placeholder(trans('plugins/fob-request-quote::request-quote.quantity_placeholder'))
                        ->addAttribute('id', 'quote_quantity')
                        ->addAttribute('min', '1')
                        ->addAttribute('required', RequestQuoteFields::isRequired('quantity') ? 'required' : null)
                        ->value(1)
                        ->wrapperAttributes(false)
                )
                ->add('quantity_wrapper_end', HtmlField::class, HtmlFieldOption::make()->content('</div>'));
        }
    }

    protected function addTextColumn(string $field, string $fieldClass, TextFieldOption|EmailFieldOption $options): void
    {
        if (! RequestQuoteFields::isEnabled($field)) {
            return;
        }

        $this
            ->add("col_start_$field", HtmlField::class, HtmlFieldOption::make()->content('<div class="col-md-6 mb-3">'))
            ->add(
                $field,
                $fieldClass,
                $options
                    ->label(RequestQuoteFields::label($field))
                    ->cssClass('form-control')
                    ->labelAttributes(['class' => $this->labelClass($field)])
                    ->addAttribute('required', RequestQuoteFields::isRequired($field) ? 'required' : null)
                    ->wrapperAttributes(false)
            )
            ->add("col_end_$field", HtmlField::class, HtmlFieldOption::make()->content('</div>'));
    }

    protected function addLocationFields(): void
    {
        $hasState = RequestQuoteFields::isEnabled('state');
        $hasCity = RequestQuoteFields::isEnabled('city');

        if ($hasState || $hasCity) {
            $this->add(
                'location_fields',
                HtmlField::class,
                HtmlFieldOption::make()->content($this->locationFieldsHtml($hasState, $hasCity))
            );
        }

        if (RequestQuoteFields::isEnabled('address')) {
            $this->add('address_wrapper_start', HtmlField::class, HtmlFieldOption::make()->content('<div class="mb-3">'))
                ->add(
                    'address',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(RequestQuoteFields::label('address'))
                        ->cssClass('form-control')
                        ->labelAttributes(['class' => $this->labelClass('address')])
                        ->placeholder(trans('plugins/fob-request-quote::request-quote.address_placeholder'))
                        ->addAttribute('id', 'quote_address')
                        ->addAttribute('required', RequestQuoteFields::isRequired('address') ? 'required' : null)
                        ->wrapperAttributes(false)
                )
                ->add('address_wrapper_end', HtmlField::class, HtmlFieldOption::make()->content('</div>'));
        }
    }

    protected function addAttributesField(): void
    {
        if (! RequestQuoteFields::isEnabled('attributes')) {
            return;
        }

        $required = RequestQuoteFields::isRequired('attributes') ? ' data-required="1"' : '';

        $this->add(
            'quote_attributes',
            HtmlField::class,
            HtmlFieldOption::make()->content(sprintf(
                '<div class="mb-3" id="quote_attributes_wrapper"%s>
                    <label class="%s">%s</label>
                    <div class="row g-2" id="quote_attributes_fields"></div>
                    <small class="text-muted">%s</small>
                </div>',
                $required,
                $this->labelClass('attributes'),
                RequestQuoteFields::label('attributes'),
                trans('plugins/fob-request-quote::request-quote.attributes_helper')
            ))
        );
    }

    protected function addMessageField(): void
    {
        if (! RequestQuoteFields::isEnabled('message')) {
            return;
        }

        $this->add('message_wrapper_start', HtmlField::class, HtmlFieldOption::make()->content('<div class="mb-3">'))
            ->add(
                'message',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(RequestQuoteFields::label('message'))
                    ->cssClass('form-control')
                    ->labelAttributes(['class' => $this->labelClass('message')])
                    ->addAttribute('id', 'quote_message')
                    ->addAttribute('required', RequestQuoteFields::isRequired('message') ? 'required' : null)
                    ->rows(3)
                    ->placeholder(trans('plugins/fob-request-quote::request-quote.message_placeholder'))
                    ->wrapperAttributes(false)
            )
            ->add('message_wrapper_end', HtmlField::class, HtmlFieldOption::make()->content('</div>'));
    }

    protected function locationFieldsHtml(bool $hasState, bool $hasCity): string
    {
        $states = $this->states();
        [$statesUrl, $citiesUrl] = $this->locationUrls();
        $countryId = $this->countryId();

        $html = '<input type="hidden" name="country" id="quote_country" value="'.e($countryId).'"><div class="row">';

        if ($hasState) {
            $html .= '<div class="col-md-6 mb-3">
                <label class="'.e($this->labelClass('state')).'" for="quote_state">'.e(RequestQuoteFields::label('state')).'</label>
                <select name="state" id="quote_state" class="form-select" data-states-url="'.e($statesUrl).'" data-cities-url="'.e($citiesUrl).'"'.(RequestQuoteFields::isRequired('state') ? ' required' : '').'>
                    <option value="">'.e(trans('plugins/fob-request-quote::request-quote.select_state')).'</option>';

            foreach ($states as $stateId => $stateName) {
                $html .= '<option value="'.e($stateId).'">'.e($stateName).'</option>';
            }

            $html .= '</select></div>';
        }

        if ($hasCity) {
            $html .= '<div class="col-md-6 mb-3">
                <label class="'.e($this->labelClass('city')).'" for="quote_city">'.e(RequestQuoteFields::label('city')).'</label>
                <select name="city" id="quote_city" class="form-select" data-cities-url="'.e($citiesUrl).'"'.(RequestQuoteFields::isRequired('city') ? ' required' : '').'>
                    <option value="">'.e(trans('plugins/fob-request-quote::request-quote.select_city')).'</option>
                </select>
            </div>';
        }

        return $html.'</div>';
    }

    protected function labelClass(string $field): string
    {
        return RequestQuoteFields::isRequired($field) ? 'form-label required' : 'form-label';
    }

    protected function states(): array
    {
        if (! is_plugin_active('location')) {
            return [];
        }

        return EcommerceHelper::getAvailableStatesByCountry($this->countryId());
    }

    protected function countryId(): string
    {
        return (string) (EcommerceHelper::getDefaultCountryId() ?: EcommerceHelper::getFirstCountryId());
    }

    protected function locationUrls(): array
    {
        $statesUrl = '/ajax/states-by-country';
        $citiesUrl = '/ajax/cities-by-state';

        try {
            $statesUrl = route('ajax.states-by-country', [], false);
        } catch (\Throwable) {
        }

        try {
            $citiesUrl = route('ajax.cities-by-state', [], false);
        } catch (\Throwable) {
        }

        return [$statesUrl, $citiesUrl];
    }
}
