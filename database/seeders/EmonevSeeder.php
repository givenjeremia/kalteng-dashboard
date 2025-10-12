<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departement;
use App\Models\Emonev;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmonevSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Emonev::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $rows = [
            'KANWIL - SEKJEN' => ['anggaran' => 3471.00, 'fisik' => 3591.00, 'gap' => 120.00, 'kinerja_satker' => 100.00],
            'KANWIL - DITJEN AHU' => ['anggaran' => 1494.00, 'fisik' => 1723.00, 'gap' => 229.00, 'kinerja_satker' => 100.00],
            'KANWIL - BSK' => ['anggaran' => 2404.00, 'fisik' => 2668.00, 'gap' => 264.00, 'kinerja_satker' => 100.00],
            'KANWIL - BPHN' => ['anggaran' => 2002.00, 'fisik' => 2426.00, 'gap' => 424.00, 'kinerja_satker' => 100.00],
            'KANWIL - DITJEN PP' => ['anggaran' => 1924.00, 'fisik' => 2389.00, 'gap' => 465.00, 'kinerja_satker' => 100.00],
            'KANWIL - DITJEN HKI' => ['anggaran' => 1502.00, 'fisik' => 2567.00, 'gap' => 1065.00, 'kinerja_satker' => 100.00],
        ];

        $yearsMonths = [
            2024 => range(1, 12),
            2025 => range(1, 10),
        ];

        foreach ($rows as $satker => $base) {
            $departement = Departement::firstOrCreate(['title' => $satker]);

            foreach ($yearsMonths as $tahun => $months) {
                foreach ($months as $bulan) {
                    // sedikit variasi per bulan (misal anggaran/fisik naik turun sedikit)
                    $anggaran = round($base['anggaran'] * (1 + rand(-200, 200) / 10000), 2); // +/-2%
                    $fisik = round($base['fisik'] * (1 + rand(-200, 200) / 10000), 2);
                    $gap = round(max(0, $base['gap'] + rand(-50, 50)), 2);
                    $kinerja = round($base['kinerja_satker'] * (1 + rand(-50, 50) / 10000), 2);

                    Emonev::create([
                        'uuid' => Str::uuid(),
                        'departement_id' => $departement->pkid,
                        'tahun' => $tahun,
                        'bulan' => $bulan,
                        'anggaran' => $anggaran,
                        'fisik' => $fisik,
                        'gap' => $gap,
                        'kinerja_satker' => $kinerja,
                        'keterangan' => 'LENGKAP',
                    ]);
                }
            }
        }
    }
}
