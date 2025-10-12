<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departement;
use App\Models\EPerformance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EPerformanceSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        EPerformance::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rows = [
            'KANWIL - BPHN' => ['target' => 4.00],
            'KANWIL - BSK' => ['target' => 1.00],
            'KANWIL - DITJEN AHU' => ['target' => 2.00],
            'KANWIL - DITJEN HKI' => ['target' => 2.00],
            'KANWIL - DITJEN PP' => ['target' => 2.00],
            'KANWIL - SEKJEN' => ['target' => 1.00],
        ];

        $yearsMonths = [
            2024 => range(1, 12),
            2025 => range(1, 10),
        ];

        foreach ($rows as $satker => $base) {
            $departement = Departement::firstOrCreate(['title' => $satker]);

            foreach ($yearsMonths as $tahun => $months) {
                foreach ($months as $bulan) {
                    // sedikit variasi pada capaian (misal 0 - 2% fluctuation)
                    $tercapai = round($base['target'] * (1 + rand(-100, 100) / 10000), 2); // +/-1%
                    $tidak_tercapai = max(0, round($base['target'] - $tercapai, 2));
                    $persentase = $base['target'] > 0 ? round(($tercapai / $base['target']) * 100, 2) : 0;

                    EPerformance::create([
                        'uuid' => Str::uuid(),
                        'departement_id' => $departement->pkid,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'target' => $base['target'],
                        'tercapai' => $tercapai,
                        'tidak_tercapai' => $tidak_tercapai,
                        'persentase_capaian' => $persentase,
                    ]);
                }
            }
        }
    }
}
