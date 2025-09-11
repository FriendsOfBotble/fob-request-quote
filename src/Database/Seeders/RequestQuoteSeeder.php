<?php

namespace FriendsOfBotble\RequestQuote\Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\Product;
use Botble\Setting\Facades\Setting;
use FriendsOfBotble\RequestQuote\Enums\RequestQuoteStatusEnum;
use FriendsOfBotble\RequestQuote\Models\RequestQuote;

class RequestQuoteSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->seedSettings();

        RequestQuote::query()->truncate();

        $faker = $this->fake();

        $products = Product::query()
            ->inRandomOrder()
            ->limit(20)
            ->get();

        if ($products->isEmpty()) {
            $this->command->warn('No products found. Please run product seeders first.');

            return;
        }

        $statuses = [
            RequestQuoteStatusEnum::PENDING,
            RequestQuoteStatusEnum::PROCESSING,
            RequestQuoteStatusEnum::COMPLETED,
        ];

        $companies = [
            'ABC Corporation',
            'XYZ Industries',
            'Global Trading Co.',
            'Tech Solutions Ltd.',
            'Innovation Hub Inc.',
            null,
            null,
            null,
        ];

        foreach ($products as $product) {
            $numberOfQuotes = rand(0, 3);

            for ($i = 0; $i < $numberOfQuotes; $i++) {
                $createdAt = $faker->dateTimeBetween('-3 months');
                $status = $faker->randomElement($statuses);

                $data = [
                    'product_id' => $product->id,
                    'name' => $faker->name(),
                    'email' => $faker->safeEmail(),
                    'phone' => rand(0, 10) > 3 ? $faker->phoneNumber() : null,
                    'company' => $faker->randomElement($companies),
                    'quantity' => $faker->numberBetween(1, 100),
                    'message' => rand(0, 10) > 4 ? $faker->paragraph(rand(2, 5)) : null,
                    'status' => $status,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ];

                if ($status === RequestQuoteStatusEnum::PROCESSING) {
                    $data['admin_notes'] = rand(0, 10) > 5
                        ? 'Processing quote request. Checking inventory and pricing.'
                        : 'Contacting customer for additional details.';
                    $data['updated_at'] = $faker->dateTimeBetween($createdAt);
                }

                if ($status === RequestQuoteStatusEnum::COMPLETED) {
                    $notes = [
                        'Quote sent to customer. Awaiting response.',
                        'Customer accepted quote. Order placed.',
                        'Quote provided. Customer will decide later.',
                        'Bulk discount applied. Quote sent via email.',
                        'Special pricing approved. Quote valid for 30 days.',
                    ];
                    $data['admin_notes'] = $faker->randomElement($notes);
                    $data['updated_at'] = $faker->dateTimeBetween($createdAt);
                }

                RequestQuote::query()->create($data);
            }
        }

        $totalQuotes = RequestQuote::query()->count();
        $this->command->info("Created {$totalQuotes} quote requests successfully!");

        $pendingCount = RequestQuote::query()->where('status', RequestQuoteStatusEnum::PENDING)->count();
        $processingCount = RequestQuote::query()->where('status', RequestQuoteStatusEnum::PROCESSING)->count();
        $completedCount = RequestQuote::query()->where('status', RequestQuoteStatusEnum::COMPLETED)->count();

        $this->command->table(
            ['Status', 'Count'],
            [
                ['Pending', $pendingCount],
                ['Processing', $processingCount],
                ['Completed', $completedCount],
                ['Total', $totalQuotes],
            ]
        );
    }

    protected function seedSettings(): void
    {
        $settings = [
            'request_quote_enabled' => true,
            'request_quote_receiver_emails' => 'quotes@example.com, sales@example.com',
            'request_quote_button_icon' => 'ti ti-file-text',
            'request_quote_button_radius' => 4,
            'request_quote_show_for_out_of_stock' => true,
            'request_quote_show_always' => true,
            'request_quote_send_confirmation' => true,
            'request_quote_show_form_info' => true,
            'request_quote_form_info_content' => '<div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; padding: 15px; margin-top: 20px;">
                <p style="margin-top: 0; font-weight: 600; color: #495057;">Important Information:</p>
                <ul style="margin-bottom: 0; padding-left: 20px; color: #6c757d;">
                    <li>Response time: Within 24-48 business hours</li>
                    <li>Bulk orders qualify for special discounts</li>
                    <li>All quotes valid for 30 days</li>
                    <li>For urgent requests, call: 1-800-EXAMPLE</li>
                </ul>
            </div>',
        ];

        Setting::set($settings);
        Setting::save();

        $this->command->info('Request Quote settings have been configured successfully!');
        $this->command->table(
            ['Setting', 'Value'],
            collect($settings)->map(function ($value, $key) {
                if (is_bool($value)) {
                    $value = $value ? 'Enabled' : 'Disabled';
                } elseif (str_contains($key, 'form_info_content')) {
                    $value = 'HTML content configured';
                }

                return [str_replace('request_quote_', '', $key), $value];
            })->toArray()
        );
    }
}
