<div class="modal fade" id="requestQuoteModal" tabindex="-1" aria-labelledby="requestQuoteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestQuoteModalLabel">
                    {{ trans('plugins/fob-request-quote::request-quote.modal_title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="requestQuoteForm" action="{{ route('public.request-quote.submit') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="quote_product_id" value="">
                    
                    <div class="mb-3">
                        <p class="text-muted mb-3">
                            <strong>{{ trans('plugins/fob-request-quote::request-quote.product') }}:</strong> 
                            <span id="quote_product_name">-</span>
                            <br><small class="text-muted">{{ trans('plugins/fob-request-quote::request-quote.sku') }}: <span id="quote_product_sku">-</span></small>
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quote_name" class="form-label required">
                                {{ trans('plugins/fob-request-quote::request-quote.name') }}
                            </label>
                            <input type="text" class="form-control" id="quote_name" name="name" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="quote_email" class="form-label required">
                                {{ trans('plugins/fob-request-quote::request-quote.email_address') }}
                            </label>
                            <input type="email" class="form-control" id="quote_email" name="email" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="quote_phone" class="form-label">
                                {{ trans('plugins/fob-request-quote::request-quote.phone') }}
                            </label>
                            <input type="tel" class="form-control" id="quote_phone" name="phone">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="quote_company" class="form-label">
                                {{ trans('plugins/fob-request-quote::request-quote.company') }}
                            </label>
                            <input type="text" class="form-control" id="quote_company" name="company">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="quote_quantity" class="form-label required">
                            {{ trans('plugins/fob-request-quote::request-quote.quantity') }}
                        </label>
                        <input type="number" class="form-control" id="quote_quantity" name="quantity" min="1" value="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="quote_message" class="form-label">
                            {{ trans('plugins/fob-request-quote::request-quote.message') }}
                        </label>
                        <textarea class="form-control" id="quote_message" name="message" rows="4" 
                                  placeholder="{{ trans('plugins/fob-request-quote::request-quote.message_placeholder') }}"></textarea>
                    </div>

                    <div class="alert alert-info d-none" id="quoteSuccessMessage">
                        {{ trans('plugins/fob-request-quote::request-quote.success_message') }}
                    </div>

                    <div class="alert alert-danger d-none" id="quoteErrorMessage"></div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ trans('plugins/fob-request-quote::request-quote.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitQuoteBtn">
                        <span class="spinner-border spinner-border-sm d-none me-2" role="status" aria-hidden="true"></span>
                        {{ trans('plugins/fob-request-quote::request-quote.submit') }}
                    </button>
                </div>
            </form>
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