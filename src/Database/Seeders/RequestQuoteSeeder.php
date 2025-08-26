<?php

namespace FriendsOfBotble\RequestQuote\Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\Product;
use FriendsOfBotble\RequestQuote\Enums\RequestQuoteStatusEnum;
use FriendsOfBotble\RequestQuote\Models\RequestQuote;

class RequestQuoteSeeder extends BaseSeeder
{
    public function run(): void
    {
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
                $createdAt = $faker->dateTimeBetween('-3 months', 'now');
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
                    $data['updated_at'] = $faker->dateTimeBetween($createdAt, 'now');
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
                    $data['updated_at'] = $faker->dateTimeBetween($createdAt, 'now');
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
}