<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Budget;
use App\Models\Departement;
use App\Models\Ceiling;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class NewBudgetSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Budget::truncate();
        Ceiling::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $satkers = [
            'KANWIL - SEKJEN',
            'KANWIL - BSK',
            'KANWIL - BPHN',
            'KANWIL - DITJEN PP',
            'KANWIL - DITJEN HKI'
        ];

        foreach ($satkers as $satker) {
            $department = Departement::firstOrCreate(['title' => $satker]);

            // Tentukan jenis belanja yang berlaku untuk satker ini
            $types = $this->getTypeForSatker($satker);

            // Buat ceilings per tahun (2024 & 2025)
            $ceilings2024 = $this->createCeilings($department->pkid, 2024, $types);
            $ceilings2025 = $this->createCeilings($department->pkid, 2025, $types);

            // Buat budgets per tahun (realisasi naik tiap bulan)
            $this->createBudgetsWithGrowth($department->pkid, $ceilings2024, 2024, 12);
            $this->createBudgetsWithGrowth($department->pkid, $ceilings2025, 2025, 9);
        }
    }

    /**
     * Tentukan jenis belanja berdasarkan satker
     */
    private function getTypeForSatker($satker)
    {
        return match ($satker) {
            'KANWIL - SEKJEN' => ['pegawai', 'barang', 'modal'], // semua jenis
            'KANWIL - BPHN'   => ['barang', 'modal'],             // tanpa pegawai
            default            => ['barang'],                      // hanya barang
        };
    }

    /**
     * Membuat record ceilings per tahun untuk setiap type_data
     */
    private function createCeilings($departement_id, $tahun, array $types)
    {
        $ceilings = [];

        foreach ($types as $type) {
            $ceilings[$type] = Ceiling::create([
                'uuid' => Str::uuid(),
                'departement_id' => $departement_id,
                'tahun' => $tahun,
                'bulan' => 0,
                'type_data' => $type,
                'nominal' => match ($type) {
                    'pegawai' => rand(800_000_000, 1_500_000_000),
                    'barang'  => rand(600_000_000, 1_200_000_000),
                    'modal'   => rand(400_000_000, 900_000_000),
                },
            ]);
        }

        return $ceilings;
    }

    /**
     * Membuat data budget bulanan dengan pertumbuhan meningkat
     */
    private function createBudgetsWithGrowth($departement_id, $ceilings, $tahun, $totalBulan)
    {
        // Hitung target realisasi per type_data (90–95% dari pagu)
        $targetPegawai = isset($ceilings['pegawai']) ? $ceilings['pegawai']->nominal * rand(90, 95) / 100 : 0;
        $targetBarang  = isset($ceilings['barang'])  ? $ceilings['barang']->nominal  * rand(90, 95) / 100 : 0;
        $targetModal   = isset($ceilings['modal'])   ? $ceilings['modal']->nominal   * rand(90, 95) / 100 : 0;

        // Distribusi pertumbuhan bulanan
        $growthRates = $this->generateGrowthRates($totalBulan);

        $realisasiPegawaiTotal = 0;
        $realisasiBarangTotal  = 0;
        $realisasiModalTotal   = 0;

        for ($bulan = 1; $bulan <= $totalBulan; $bulan++) {
            $factor = $growthRates[$bulan - 1];

            $pegawai = intval($targetPegawai * $factor);
            $barang  = intval($targetBarang * $factor);
            $modal   = intval($targetModal * $factor);

            if ($bulan == $totalBulan) {
                if ($targetPegawai) $pegawai = intval($targetPegawai - $realisasiPegawaiTotal);
                if ($targetBarang)  $barang  = intval($targetBarang - $realisasiBarangTotal);
                if ($targetModal)   $modal   = intval($targetModal - $realisasiModalTotal);
            }

            Budget::create([
                'uuid' => Str::uuid(),
                'departement_id' => $departement_id,
                'ceiling_id' => collect($ceilings)->first()?->pkid, // ambil salah satu ceiling_id
                'tahun' => $tahun,
                'bulan' => $bulan,
                'realisasi_pegawai' => $pegawai,
                'realisasi_barang'  => $barang,
                'realisasi_modal'   => $modal,
            ]);

            $realisasiPegawaiTotal += $pegawai;
            $realisasiBarangTotal  += $barang;
            $realisasiModalTotal   += $modal;
        }
    }

    /**
     * Membentuk pertumbuhan realisasi tiap bulan (naik eksponensial)
     */
    private function generateGrowthRates($months)
    {
        $rates = [];
        $total = 0;

        for ($i = 1; $i <= $months; $i++) {
            $rate = pow($i / $months, 1.5);
            $rates[] = $rate;
            $total += $rate;
        }

        return array_map(fn($r) => $r / $total, $rates);
    }
}
