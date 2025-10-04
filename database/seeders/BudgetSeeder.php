<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Budget;
use App\Models\Departement;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        $satkers = [
            'KANWIL - SEKJEN',
            'KANWIL - BSK',
            'KANWIL - BPHN',
            'KANWIL - DITJEN PP',
            'KANWIL - DITJEN HKI'
        ];

        foreach ($satkers as $satker) {
            $department = Departement::firstOrCreate(['title' => $satker]);

            // Tahun 2024 (semua bulan 1–12)
            for ($bulan = 1; $bulan <= 12; $bulan++) {
                Budget::firstOrCreate(
                    [
                        'departement_id' => $department->pkid,
                        'tahun' => 2024,
                        'bulan' => $bulan,
                    ],
                    $this->dummyData()
                );
            }

            // Tahun 2025 (bulan 1–9 saja)
            for ($bulan = 1; $bulan <= 9; $bulan++) {
                Budget::firstOrCreate(
                    [
                        'departement_id' => $department->pkid,
                        'tahun' => 2025,
                        'bulan' => $bulan,
                    ],
                    $this->dummyData()
                );
            }
        }
    }

    private function dummyData(): array
    {
        return [
            'pagu_pegawai' => rand(500_000_000, 2_000_000_000),
            'realisasi_pegawai' => rand(100_000_000, 500_000_000),
            'pagu_barang' => rand(500_000_000, 2_000_000_000),
            'realisasi_barang' => rand(100_000_000, 500_000_000),
            'pagu_modal' => rand(500_000_000, 2_000_000_000),
            'realisasi_modal' => rand(100_000_000, 500_000_000),
        ];
    }
}
