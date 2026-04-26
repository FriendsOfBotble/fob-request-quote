@php
    use FriendsOfBotble\RequestQuote\Forms\Fronts\RequestQuoteForm;
    
    $form = RequestQuoteForm::create();
@endphp

<div class="modal fade" id="requestQuoteModal" tabindex="-1" aria-labelledby="requestQuoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestQuoteModalLabel">
                    {{ trans('plugins/fob-request-quote::request-quote.modal_title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {!! $form->renderForm() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ trans('plugins/fob-request-quote::request-quote.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary" id="submitQuoteBtn" form="requestQuoteForm">
                    <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                    {{ trans('plugins/fob-request-quote::request-quote.submit') }}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.form-label.required::after {
    content: ' *';
    color: #dc3545;
}

.request-quote-attribute-group {
    margin-bottom: 14px;
}

.request-quote-attribute-options {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.request-quote-attribute-option {
    min-width: 52px;
    min-height: 36px;
    padding: 8px 14px;
    border: 1px solid #dce1e7;
    background: #fff;
    color: #1f2937;
    border-radius: 6px;
    font-size: 13px;
    line-height: 1;
}

.request-quote-attribute-option.is-selected {
    border-color: var(--primary-color, #206bc4);
    box-shadow: inset 0 0 0 1px var(--primary-color, #206bc4);
}

.request-quote-attribute-option:disabled {
    opacity: .45;
    cursor: not-allowed;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('requestQuoteForm');
    const submitBtn = document.getElementById('submitQuoteBtn');
    const spinner = submitBtn.querySelector('.spinner-border');
    const successMessage = document.getElementById('quoteSuccessMessage');
    const errorMessage = document.getElementById('quoteErrorMessage');
    const modal = document.getElementById('requestQuoteModal');
    const countrySelect = document.getElementById('quote_country');
    const stateSelect = document.getElementById('quote_state');
    const citySelect = document.getElementById('quote_city');
    const phoneInput = document.getElementById('quote_phone');
    const attributesFields = document.getElementById('quote_attributes_fields');

    function attributeLabel(wrapper, fallback) {
        return (wrapper.querySelector('.bb-product-attribute-swatch-title')?.textContent || fallback || '')
            .replace(':', '')
            .trim();
    }

    function attributeOptionText(option) {
        return option.querySelector('.bb-product-attribute-text-display, .bb-product-attribute-swatch-item-tooltip')?.textContent?.trim()
            || option.textContent?.trim()
            || '';
    }

    function attributeGroups() {
        return Array.from(document.querySelectorAll('.product-attributes .attribute-swatches-wrapper, .product-attribute-swatches .attribute-swatches-wrapper'))
            .filter(wrapper => !wrapper.closest('#requestQuoteModal'))
            .map(wrapper => {
                const label = attributeLabel(wrapper, wrapper.getAttribute('data-slug'));
                const options = [];

                wrapper.querySelectorAll('.attribute-swatch-item').forEach(option => {
                    const input = option.querySelector('input');
                    const text = attributeOptionText(option);

                    if (!input || !text) {
                        return;
                    }

                    options.push({
                        value: input.value || text,
                        text: text,
                        checked: input.checked,
                        disabled: input.disabled || option.classList.contains('disabled'),
                        visual: option.querySelector('.bb-product-attribute-swatch-display')?.getAttribute('style') || '',
                    });
                });

                wrapper.querySelectorAll('select.product-filter-item option').forEach(option => {
                    if (!option.value) {
                        return;
                    }

                    options.push({
                        value: option.value,
                        text: option.textContent.trim(),
                        checked: option.selected,
                        disabled: option.disabled,
                        visual: '',
                    });
                });

                return { label, options };
            })
            .filter(group => group.label && group.options.length);
    }

    function renderAttributeFields() {
        if (!attributesFields) {
            return;
        }

        const groups = attributeGroups();
        const isRequired = attributesFields.closest('#quote_attributes_wrapper')?.getAttribute('data-required') === '1';

        attributesFields.innerHTML = '';

        if (!groups.length) {
            attributesFields.closest('#quote_attributes_wrapper')?.classList.add('d-none');
            return;
        }

        attributesFields.closest('#quote_attributes_wrapper')?.classList.remove('d-none');

        groups.forEach((group, index) => {
            const section = document.createElement('div');
            section.className = 'request-quote-attribute-group';

            const selected = group.options.find(option => option.checked && !option.disabled);
            const label = document.createElement('label');
            label.className = 'form-label' + (isRequired ? ' required' : '');
            label.textContent = group.label;

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'attributes[' + group.label + ']';
            hidden.value = selected?.text || '';
            hidden.required = isRequired && index === 0;

            const options = document.createElement('div');
            options.className = 'request-quote-attribute-options';

            group.options.forEach(option => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'request-quote-attribute-option' + (option.text === hidden.value ? ' is-selected' : '');
                button.disabled = option.disabled;
                button.setAttribute('aria-pressed', option.text === hidden.value ? 'true' : 'false');
                button.textContent = option.visual ? '' : option.text;

                if (option.visual) {
                    const swatch = document.createElement('span');
                    swatch.className = 'd-inline-block rounded-circle';
                    swatch.style.cssText = option.visual + '; width: 18px; height: 18px; vertical-align: middle;';
                    button.appendChild(swatch);
                    button.title = option.text;
                }

                button.addEventListener('click', function() {
                    hidden.value = option.text;
                    options.querySelectorAll('.request-quote-attribute-option').forEach(item => {
                        item.classList.remove('is-selected');
                        item.setAttribute('aria-pressed', 'false');
                    });
                    button.classList.add('is-selected');
                    button.setAttribute('aria-pressed', 'true');
                });

                options.appendChild(button);
            });

            section.appendChild(label);
            section.appendChild(hidden);
            section.appendChild(options);
            attributesFields.appendChild(section);
        });
    }

    function appendOptions(select, items) {
        items.forEach(item => {
            if (!item.id || item.id === 0 || item.id === '0') {
                return;
            }

            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            select.appendChild(option);
        });
    }

    function normalizeLocationItems(response) {
        let items = response.data || [];

        if (items.data) {
            items = items.data;
        }

        if (Array.isArray(items)) {
            return items;
        }

        if (items && typeof items === 'object') {
            return Object.keys(items).map(id => ({ id: id, name: items[id] }));
        }

        return [];
    }

    function withQuery(url, key, value) {
        return url + (url.indexOf('?') === -1 ? '?' : '&') + key + '=' + encodeURIComponent(value);
    }

    function refreshSelect(select) {
        if (window.jQuery && jQuery.fn.niceSelect) {
            jQuery(select).niceSelect('update');
        }
    }

    function loadStates(countryId) {
        if (!stateSelect) {
            return;
        }

        const url = stateSelect.getAttribute('data-states-url');

        if (!url) {
            return;
        }

        stateSelect.innerHTML = '<option value="">{{ trans('plugins/fob-request-quote::request-quote.select_state') }}</option>';

        if (citySelect) {
            citySelect.innerHTML = '<option value="">{{ trans('plugins/fob-request-quote::request-quote.select_city') }}</option>';
        }

        if (!countryId) {
            refreshSelect(stateSelect);

            if (citySelect) {
                refreshSelect(citySelect);
            }

            return;
        }

        fetch(withQuery(url, 'country_id', countryId), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(response => {
            appendOptions(stateSelect, normalizeLocationItems(response));
            refreshSelect(stateSelect);

            if (citySelect) {
                refreshSelect(citySelect);
            }
        });
    }

    function loadCities(stateId) {
        if (!citySelect) {
            return;
        }

        citySelect.innerHTML = '<option value="">{{ trans('plugins/fob-request-quote::request-quote.select_city') }}</option>';

        if (!stateId) {
            refreshSelect(citySelect);

            return;
        }

        const url = (stateSelect ? stateSelect.getAttribute('data-cities-url') : '') || citySelect.getAttribute('data-cities-url');

        if (!url) {
            refreshSelect(citySelect);

            return;
        }

        fetch(withQuery(url, 'state_id', stateId), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(response => {
            appendOptions(citySelect, normalizeLocationItems(response));
            refreshSelect(citySelect);
        });
    }
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.request-quote-btn')) {
            const btn = e.target.closest('.request-quote-btn');
            const productId = btn.getAttribute('data-product-id');
            const productName = btn.getAttribute('data-product-name');
            const productSku = btn.getAttribute('data-product-sku') || '-';
            
            document.getElementById('quote_product_id').value = productId;
            document.getElementById('quote_product_name').textContent = productName;
            document.getElementById('quote_product_sku').textContent = productSku;
            renderAttributeFields();
            loadStates(countrySelect ? countrySelect.value : '');
        }
    });

    if (stateSelect) {
        stateSelect.addEventListener('change', function() {
            loadCities(this.value);
        });
    }

    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D+/g, '').slice(0, 20);
        });
    }
    
    modal.addEventListener('hidden.bs.modal', function () {
        form.reset();
        successMessage.classList.add('d-none');
        errorMessage.classList.add('d-none');
        submitBtn.disabled = false;
        spinner.classList.add('d-none');
        if (citySelect) {
            citySelect.innerHTML = '<option value="">{{ trans('plugins/fob-request-quote::request-quote.select_city') }}</option>';
        }
        renderAttributeFields();
    });

    loadStates(countrySelect ? countrySelect.value : '');
    renderAttributeFields();
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        successMessage.classList.add('d-none');
        errorMessage.classList.add('d-none');
        
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');
        
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error === false) {
                successMessage.classList.remove('d-none');
                
                setTimeout(() => {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                }, 4000);
            } else {
                errorMessage.textContent = data.message || '{{ trans('plugins/fob-request-quote::request-quote.error_message') }}';
                errorMessage.classList.remove('d-none');
            }
        })
        .catch(error => {
            errorMessage.textContent = '{{ trans('plugins/fob-request-quote::request-quote.error_message') }}';
            errorMessage.classList.remove('d-none');
        })
        .finally(() => {
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        });
    });
});
</script>
