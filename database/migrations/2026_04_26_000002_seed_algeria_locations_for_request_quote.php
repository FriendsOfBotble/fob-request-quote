<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('countries') || ! Schema::hasTable('states') || ! Schema::hasTable('cities')) {
            return;
        }

        if (! Schema::hasColumn('countries', 'code')) {
            return;
        }

        $now = now();

        DB::table('countries')->updateOrInsert(
            ['code' => 'DZ'],
            $this->payload('countries', [
                'name' => 'Algeria',
                'nationality' => 'Algerian',
                'order' => 1,
                'is_default' => 1,
                'status' => 'published',
                'updated_at' => $now,
                'created_at' => $now,
            ])
        );

        $countryId = DB::table('countries')->where('code', 'DZ')->value('id');

        if (! $countryId) {
            return;
        }

        $communes = $this->communes();

        foreach ($this->wilayas() as $code => $name) {
            DB::table('states')->updateOrInsert(
                [
                    'country_id' => $countryId,
                    'abbreviation' => $code,
                ],
                $this->payload('states', [
                    'name' => $name,
                    'order' => (int) $code,
                    'is_default' => $code === '16' ? 1 : 0,
                    'status' => 'published',
                    'updated_at' => $now,
                    'created_at' => $now,
                ])
            );

            $stateId = DB::table('states')
                ->where('country_id', $countryId)
                ->where('abbreviation', $code)
                ->value('id');

            if (! $stateId) {
                continue;
            }

            foreach ($communes[(int) $code] ?? [] as $index => $commune) {
                $cityName = $commune['nom'] ?? null;

                if (! $cityName) {
                    continue;
                }

                DB::table('cities')->updateOrInsert(
                    [
                        'country_id' => $countryId,
                        'state_id' => $stateId,
                        'name' => $cityName,
                    ],
                    $this->payload('cities', [
                        'record_id' => sprintf('%s-%s', $code, $index + 1),
                        'zip_code' => $commune['code_postal'] ?? null,
                        'order' => $index + 1,
                        'is_default' => 0,
                        'status' => 'published',
                        'updated_at' => $now,
                        'created_at' => $now,
                    ])
                );
            }
        }

        Cache::flush();
    }

    public function down(): void
    {
        // Keep imported location data. Other checkout and shipping flows may depend on it after import.
    }

    protected function communes(): array
    {
        $path = base_path('platform/plugins/ecotrack/src/Data/all_communes.php');

        if (! is_file($path)) {
            return [];
        }

        $communes = require $path;

        return is_array($communes) ? $communes : [];
    }

    protected function payload(string $table, array $values): array
    {
        return collect($values)
            ->filter(fn ($value, $column) => Schema::hasColumn($table, $column))
            ->all();
    }

    protected function wilayas(): array
    {
        return [
            '01' => 'Adrar',
            '02' => 'Chlef',
            '03' => 'Laghouat',
            '04' => 'Oum El Bouaghi',
            '05' => 'Batna',
            '06' => 'Bejaia',
            '07' => 'Biskra',
            '08' => 'Bechar',
            '09' => 'Blida',
            '10' => 'Bouira',
            '11' => 'Tamanrasset',
            '12' => 'Tebessa',
            '13' => 'Tlemcen',
            '14' => 'Tiaret',
            '15' => 'Tizi Ouzou',
            '16' => 'Alger',
            '17' => 'Djelfa',
            '18' => 'Jijel',
            '19' => 'Setif',
            '20' => 'Saida',
            '21' => 'Skikda',
            '22' => 'Sidi Bel Abbes',
            '23' => 'Annaba',
            '24' => 'Guelma',
            '25' => 'Constantine',
            '26' => 'Medea',
            '27' => 'Mostaganem',
            '28' => "M'Sila",
            '29' => 'Mascara',
            '30' => 'Ouargla',
            '31' => 'Oran',
            '32' => 'El Bayadh',
            '33' => 'Illizi',
            '34' => 'Bordj Bou Arreridj',
            '35' => 'Boumerdes',
            '36' => 'El Tarf',
            '37' => 'Tindouf',
            '38' => 'Tissemsilt',
            '39' => 'El Oued',
            '40' => 'Khenchela',
            '41' => 'Souk Ahras',
            '42' => 'Tipaza',
            '43' => 'Mila',
            '44' => 'Ain Defla',
            '45' => 'Naama',
            '46' => 'Ain Temouchent',
            '47' => 'Ghardaia',
            '48' => 'Relizane',
            '49' => 'Timimoun',
            '50' => 'Bordj Badji Mokhtar',
            '51' => 'Ouled Djellal',
            '52' => 'Beni Abbes',
            '53' => 'In Salah',
            '54' => 'In Guezzam',
            '55' => 'Touggourt',
            '56' => 'Djanet',
            '57' => "El M'Ghair",
            '58' => 'El Menia',
        ];
    }
};
