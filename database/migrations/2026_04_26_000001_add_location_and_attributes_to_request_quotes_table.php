<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('fob_request_quotes')) {
            return;
        }

        $hasName = Schema::hasColumn('fob_request_quotes', 'name');
        $hasEmail = Schema::hasColumn('fob_request_quotes', 'email');
        $missingColumns = collect(['state', 'city', 'address', 'attributes'])
            ->reject(fn (string $column): bool => Schema::hasColumn('fob_request_quotes', $column));

        Schema::table('fob_request_quotes', function (Blueprint $table) use ($hasName, $hasEmail, $missingColumns): void {
            if ($hasName) {
                $table->string('name')->nullable()->change();
            }

            if ($hasEmail) {
                $table->string('email')->nullable()->change();
            }

            if ($missingColumns->contains('state')) {
                $table->string('state')->nullable()->after('quantity');
            }

            if ($missingColumns->contains('city')) {
                $table->string('city')->nullable()->after('state');
            }

            if ($missingColumns->contains('address')) {
                $table->string('address')->nullable()->after('city');
            }

            if ($missingColumns->contains('attributes')) {
                $table->json('attributes')->nullable()->after('address');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('fob_request_quotes')) {
            return;
        }

        $existingColumns = collect(['state', 'city', 'address', 'attributes'])
            ->filter(fn (string $column): bool => Schema::hasColumn('fob_request_quotes', $column));

        if ($existingColumns->isEmpty()) {
            return;
        }

        Schema::table('fob_request_quotes', function (Blueprint $table) use ($existingColumns): void {
            foreach ($existingColumns as $column) {
                $table->dropColumn($column);
            }
        });
    }
};
