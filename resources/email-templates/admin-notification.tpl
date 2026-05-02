{{ header }}

<div class="bb-main-content">
    <table class="bb-box" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td class="bb-content bb-pb-0" align="center">
                    <table class="bb-icon bb-icon-lg bb-bg-blue" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td valign="middle" align="center">
                                    <img src="{{ 'mail' | icon_url }}" class="bb-va-middle" width="40" height="40" alt="Icon" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <h1 class="bb-text-center bb-m-0 bb-mt-md">{{ 'plugins/fob-request-quote::request-quote.email.new_quote_request' | trans }}</h1>
                </td>
            </tr>
            <tr>
                <td class="bb-content">
                    <p>{{ 'plugins/fob-request-quote::request-quote.email.admin_greeting' | trans }}</p>
                </td>
            </tr>
            <tr>
                <td class="bb-content bb-pt-0">
                    <table class="bb-row" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="bb-col">
                                    <div class="bb-box bb-bg-light bb-p-md">
                                        <h4 class="bb-m-0 bb-mb-sm">{{ 'plugins/fob-request-quote::request-quote.email.customer_information' | trans }}</h4>
                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td class="bb-text-muted" style="padding: 5px 0;">{{ 'plugins/fob-request-quote::request-quote.name' | trans }}:</td>
                                                    <td style="padding: 5px 0;"><strong>{{ quote_name }}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td class="bb-text-muted" style="padding: 5px 0;">{{ 'plugins/fob-request-quote::request-quote.email_address' | trans }}:</td>
                                                    <td style="padding: 5px 0;"><strong>{{ quote_email }}</strong></td>
                                                </tr>
                                                {% if quote_phone != '--' %}
                                                <tr>
                                                    <td class="bb-text-muted" style="padding: 5px 0;">{{ 'plugins/fob-request-quote::request-quote.phone' | trans }}:</td>
                                                    <td style="padding: 5px 0;"><strong>{{ quote_phone }}</strong></td>
                                                </tr>
                                                {% endif %}
                                                {% if quote_company != '--' %}
                                                <tr>
                                                    <td class="bb-text-muted" style="padding: 5px 0;">{{ 'plugins/fob-request-quote::request-quote.company' | trans }}:</td>
                                                    <td style="padding: 5px 0;"><strong>{{ quote_company }}</strong></td>
                                                </tr>
                                                {% endif %}
                                                {% if quote_state != '--' %}
                                                <tr>
                                                    <td class="bb-text-muted" style="padding: 5px 0;">{{ 'plugins/fob-request-quote::request-quote.state' | trans }}:</td>
                                                    <td style="padding: 5px 0;"><strong>{{ quote_state }}</strong></td>
                                                </tr>
                                                {% endif %}
                                                {% if quote_city != '--' %}
                                                <tr>
                                                    <td class="bb-text-muted" style="padding: 5px 0;">{{ 'plugins/fob-request-quote::request-quote.city' | trans }}:</td>
                                                    <td style="padding: 5px 0;"><strong>{{ quote_city }}</strong></td>
                                                </tr>
                                                {% endif %}
                                                {% if quote_address != '--' %}
                                                <tr>
                                                    <td class="bb-text-muted" style="padding: 5px 0;">{{ 'plugins/fob-request-quote::request-quote.address' | trans }}:</td>
                                                    <td style="padding: 5px 0;"><strong>{{ quote_address }}</strong></td>
                                                </tr>
                                                {% endif %}
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                                <td class="bb-col-spacer"></td>
                                <td class="bb-col">
                                    <div class="bb-box bb-bg-light bb-p-md">
                                        <h4 class="bb-m-0 bb-mb-sm">{{ 'plugins/fob-request-quote::request-quote.email.product_information' | trans }}</h4>
                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td class="bb-text-muted" style="padding: 5px 0;">{{ 'plugins/fob-request-quote::request-quote.product' | trans }}:</td>
                                                    <td style="padding: 5px 0;"><strong>{{ product_name }}</strong></td>
                                                </tr>
                                                {% if product_sku != '--' %}
                                                <tr>
                                                    <td class="bb-text-muted" style="padding: 5px 0;">{{ 'plugins/fob-request-quote::request-quote.sku' | trans }}:</td>
                                                    <td style="padding: 5px 0;"><strong>{{ product_sku }}</strong></td>
                                                </tr>
                                                {% endif %}
                                                <tr>
                                                    <td class="bb-text-muted" style="padding: 5px 0;">{{ 'plugins/fob-request-quote::request-quote.quantity' | trans }}:</td>
                                                    <td style="padding: 5px 0;"><strong>{{ quote_quantity }}</strong></td>
                                                </tr>
                                                {% if quote_attributes != '--' %}
                                                <tr>
                                                    <td class="bb-text-muted" style="padding: 5px 0;">{{ 'plugins/fob-request-quote::request-quote.attributes' | trans }}:</td>
                                                    <td style="padding: 5px 0;"><strong>{{ quote_attributes }}</strong></td>
                                                </tr>
                                                {% endif %}
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            {% if quote_message != '--' %}
            <tr>
                <td class="bb-content bb-pt-0">
                    <div class="bb-box bb-border bb-p-md">
                        <table cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td valign="top" style="padding-right: 10px;">
                                        <img src="{{ 'mail' | icon_url }}" width="24" height="24" alt="Message Icon" />
                                    </td>
                                    <td>
                                        <h4 class="bb-m-0 bb-mb-sm">{{ 'plugins/fob-request-quote::request-quote.message' | trans }}</h4>
                                        <div class="bb-text-muted">
                                            {{ quote_message }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
            {% endif %}
            <tr>
                <td class="bb-content bb-border-top bb-text-center">
                    <table cellspacing="0" cellpadding="0" align="center">
                        <tr>
                            <td>
                                <a href="{{ admin_link }}" class="bb-btn bb-bg-blue bb-text-white">
                                    {{ 'plugins/fob-request-quote::request-quote.email.view_in_admin' | trans }}
                                </a>
                            </td>
                        </tr>
                    </table>
                    <p class="bb-text-muted bb-text-sm bb-mt-md">
                        {{ 'plugins/fob-request-quote::request-quote.email.admin_footer' | trans }}
                    </p>
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{ footer }}
