{{ header }}

<div class="bb-main-content">
    <table class="bb-box" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td class="bb-content bb-pb-0" align="center">
                    <table class="bb-icon bb-icon-lg bb-bg-green" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td valign="middle" align="center">
                                    <img src="{{ 'check' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/fob-request-quote::request-quote.email.confirmation_title' | trans }}</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <p>{{ 'plugins/fob-request-quote::request-quote.email.customer_greeting' | trans({'name': quote_name}) }}</p>
                    
                    <div class="bb-box bb-bg-light bb-p-md bb-text-center">
                        <img src="{{ 'confetti' | icon_url }}" width="32" height="32" alt="Success" style="margin-bottom: 10px;" />
                        <p class="bb-m-0">{{ 'plugins/fob-request-quote::request-quote.email.confirmation_message' | trans }}</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <h3 class="bb-text-center">{{ 'plugins/fob-request-quote::request-quote.email.quote_details' | trans }}</h3>
                    
                    <table class="bb-box bb-border" cellpadding="0" cellspacing="0" width="100%">
                        <tbody>
                            <tr>
                                <td class="bb-p-md">
                                    <table cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td width="40" valign="top">
                                                    <img src="{{ 'shopping-cart' | icon_url }}" width="32" height="32" alt="Product" />
                                                </td>
                                                <td>
                                                    <div style="margin-bottom: 15px;">
                                                        <div class="bb-text-muted bb-text-sm">{{ 'plugins/fob-request-quote::request-quote.product' | trans }}</div>
                                                        <div style="font-size: 18px; font-weight: bold;">{{ product_name }}</div>
                                                    </div>
                                                    
                                                    <table cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            {% if product_sku != '--' %}
                                                            <tr>
                                                                <td class="bb-text-muted" style="padding: 3px 15px 3px 0;">{{ 'plugins/fob-request-quote::request-quote.sku' | trans }}:</td>
                                                                <td style="padding: 3px 0;"><strong>{{ product_sku }}</strong></td>
                                                            </tr>
                                                            {% endif %}
                                                            <tr>
                                                                <td class="bb-text-muted" style="padding: 3px 15px 3px 0;">{{ 'plugins/fob-request-quote::request-quote.quantity' | trans }}:</td>
                                                                <td style="padding: 3px 0;"><strong style="font-size: 16px; color: #28a745;">{{ quote_quantity }}</strong></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            {% if quote_message != '--' %}
                            <tr>
                                <td class="bb-border-top bb-p-md bb-bg-light">
                                    <table cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td valign="top" style="padding-right: 10px;">
                                                    <img src="{{ 'mail' | icon_url }}" width="24" height="24" alt="Message" />
                                                </td>
                                                <td>
                                                    <div class="bb-text-muted bb-text-sm bb-mb-sm">{{ 'plugins/fob-request-quote::request-quote.your_message' | trans }}</div>
                                                    <div>{{ quote_message }}</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            {% endif %}
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <div class="bb-box bb-bg-blue-light bb-p-md bb-text-center">
                        <table cellpadding="0" cellspacing="0" align="center">
                            <tbody>
                                <tr>
                                    <td valign="middle" style="padding-right: 10px;">
                                        <img src="{{ 'hourglass' | icon_url }}" width="24" height="24" alt="Time" />
                                    </td>
                                    <td>
                                        <p class="bb-m-0">{{ 'plugins/fob-request-quote::request-quote.email.response_time' | trans }}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-text-center">
                    <img src="{{ 'shield-check' | icon_url }}" width="32" height="32" alt="Thank you" style="margin-bottom: 10px;" />
                    <p><strong>{{ 'plugins/fob-request-quote::request-quote.email.thank_you' | trans }}</strong></p>
                    <p class="bb-text-muted bb-text-sm">{{ site_title }}</p>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}