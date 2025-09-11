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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('requestQuoteForm');
    const submitBtn = document.getElementById('submitQuoteBtn');
    const spinner = submitBtn.querySelector('.spinner-border');
    const successMessage = document.getElementById('quoteSuccessMessage');
    const errorMessage = document.getElementById('quoteErrorMessage');
    const modal = document.getElementById('requestQuoteModal');
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.request-quote-btn')) {
            const btn = e.target.closest('.request-quote-btn');
            const productId = btn.getAttribute('data-product-id');
            const productName = btn.getAttribute('data-product-name');
            const productSku = btn.getAttribute('data-product-sku') || '-';
            
            document.getElementById('quote_product_id').value = productId;
            document.getElementById('quote_product_name').textContent = productName;
            document.getElementById('quote_product_sku').textContent = productSku;
        }
    });
    
    modal.addEventListener('hidden.bs.modal', function () {
        form.reset();
        successMessage.classList.add('d-none');
        errorMessage.classList.add('d-none');
        submitBtn.disabled = false;
        spinner.classList.add('d-none');
    });
    
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
                form.reset();
                
                setTimeout(() => {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                }, 2000);
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