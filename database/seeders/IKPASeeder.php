<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departement;
use App\Models\IkpaScore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IKPASeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        IkpaScore::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rows = [
            'KANWIL - DITJEN AHU' => [
                'deviation_dipa' => 100.00,
                'revisi_dipa' => 100.00,
                'penyerapan_anggaran' => 100.00,
                'capaian_output' => 100.00,
                'penyelesaian_tagihan' => 100.00,
                'pengelolaan_up_tup' => 0.00,
                'belanja_kontraktual' => 100.00,
            ],
            'KANWIL - BSK' => [
                'deviation_dipa' => 100.00,
                'revisi_dipa' => 100.00,
                'penyerapan_anggaran' => 100.00,
                'capaian_output' => 100.00,
                'penyelesaian_tagihan' => 0.00,
                'pengelolaan_up_tup' => 99.41,
                'belanja_kontraktual' => 0.00,
            ],
            'KANWIL - SEKJEN' => [
                'deviation_dipa' => 100.00,
                'revisi_dipa' => 100.00,
                'penyerapan_anggaran' => 98.98,
                'capaian_output' => 100.00,
                'penyelesaian_tagihan' => 100.00,
                'pengelolaan_up_tup' => 96.09,
                'belanja_kontraktual' => 100.00,
            ],
            'KANWIL - DITJEN PP' => [
                'deviation_dipa' => 86.99,
                'revisi_dipa' => 100.00,
                'penyerapan_anggaran' => 100.00,
                'capaian_output' => 100.00,
                'penyelesaian_tagihan' => 0.00,
                'pengelolaan_up_tup' => 99.36,
                'belanja_kontraktual' => 0.00,
            ],
            'KANWIL - DITJEN HKI' => [
                'deviation_dipa' => 74.30,
                'revisi_dipa' => 100.00,
                'penyerapan_anggaran' => 100.00,
                'capaian_output' => 100.00,
                'penyelesaian_tagihan' => 0.00,
                'pengelolaan_up_tup' => 0.00,
                'belanja_kontraktual' => 0.00,
            ],
            'KANWIL - BPHN' => [
                'deviation_dipa' => 69.97,
                'revisi_dipa' => 100.00,
                'penyerapan_anggaran' => 82.03,
                'capaian_output' => 100.00,
                'penyelesaian_tagihan' => 0.00,
                'pengelolaan_up_tup' => 99.08,
                'belanja_kontraktual' => 0.00,
            ],
        ];

        // years & months to seed
        $yearsMonths = [
            2024 => range(1, 12),
            2025 => range(1, 10), // sampai Oktober 2025
        ];

        foreach ($rows as $satker => $base) {
            $departement = Departement::firstOrCreate(['title' => $satker]);

            foreach ($yearsMonths as $tahun => $months) {
                foreach ($months as $bulan) {
                    // beri variasi kecil tiap bulan (+/- up to 1% of base for some metrics)
                    $variation = function ($value) {
                        if ($value == 0) return 0.00;
                        $delta = $value * (rand(-50, 50) / 10000); // +/- 0.5%
                        return round(max(0, $value + $delta), 2);
                    };

                    $deviation = $variation($base['deviation_dipa']);
                    $revisi = $variation($base['revisi_dipa']);
                    $penyerapan = $variation($base['penyerapan_anggaran']);
                    $capaian = $variation($base['capaian_output']);
                    $penyelesaian = $variation($base['penyelesaian_tagihan']);
                    $up_tup = $variation($base['pengelolaan_up_tup']);
                    $kontraktual = $variation($base['belanja_kontraktual']);

                    // hitung nilai_ikpa sebagai rata-rata indikator (hitung only indicators count)
                    $indicators = array_filter([
                        $deviation, $revisi, $penyerapan, $capaian,
                        $penyelesaian, $up_tup, $kontraktual
                    ], fn($v) => $v !== null);

                    $nilaiIkpa = count($indicators) ? round(array_sum($indicators) / count($indicators), 2) : 0.00;

                    IkpaScore::create([
                        'uuid' => Str::uuid(),
                        'departement_id' => $departement->pkid,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'deviation_dipa' => $deviation,
                        'revisi_dipa' => $revisi,
                        'penyerapan_anggaran' => $penyerapan,
                        'capaian_output' => $capaian,
                        'penyelesaian_tagihan' => $penyelesaian,
                        'pengelolaan_up_tup' => $up_tup,
                        'belanja_kontraktual' => $kontraktual,
                        'nilai_ikpa' => $nilaiIkpa,
                    ]);
                }
            }
        }
    }
}
