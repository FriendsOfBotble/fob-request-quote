# Changelog

All notable changes to this project will be documented in this file.

## [1.0.0] - 2024-09-11

### Added
- Initial release of FOB Request Quote plugin
- Modal-based quote request form with Bootstrap integration
- Product-specific quote requests with product information display
- Advanced email validation using Botble's EmailRule
- Phone number validation using Botble's PhoneNumberRule
- Admin dashboard for managing quote requests
- Status tracking system (Pending, Processing, Completed)
- Internal admin notes for quote management
- Email notifications for administrators
- Customer confirmation email system
- Configurable settings panel with multiple options
- Form builder integration using Botble's FormFront pattern
- Additional information display in quote form
- Multi-language support with translation files
- Database migrations and seeders
- Placeholder text for all form fields
- Helper text for all settings fields

### Features
- **Form Fields**: Name, Email, Phone, Company, Quantity, Message
- **Validation**: Advanced email and phone validation with configurable patterns
- **Status Management**: Pending, Processing, Completed workflow
- **Email System**: Admin notifications and customer confirmations
- **Settings**: Button appearance, display rules, email configuration
- **Integration**: Seamless integration with Botble e-commerce products

### Technical
- Built on Botble CMS 7.0+
- **Self-contained plugin** - no external dependencies!
- Uses Botble's built-in validation rules
- Form builder pattern implementation
- Theme-independent styling with inline CSS
- CSRF protection and input sanitization
- Database migrations and comprehensive seeder

### Requirements
- PHP 8.1+
- Botble CMS 7.0+
- E-commerce plugin activated

### Installation
1. Extract plugin to `platform/plugins/fob-request-quote`
2. Activate plugin in admin panel
3. Run migrations: `php artisan migrate`
4. (Optional) Seed data: `php artisan db:seed --class="FriendsOfBotble\RequestQuote\Database\Seeders\RequestQuoteSeeder"`

**No composer commands required!** The plugin is completely self-contained.