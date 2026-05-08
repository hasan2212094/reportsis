<?php

namespace Database\Seeders;

use App\Models\Pengajuannomor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengajuannomorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $tahun = 2026;
       $maxNomor = 20000;
       $data = [];

for ($bulan = 1; $bulan <= 12; $bulan++) {
    for ($i = 1; $i <= $maxNomor; $i++) {
        $data[] = [
            'nomor' => 'RD/' . str_pad($i, 5, '0', STR_PAD_LEFT) . '/' . $bulan . '/' . $tahun,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (count($data) === 1000) {
            Pengajuannomor::insert($data);
            $data = [];
        }
    }
}

Pengajuannomor::insert($data); // sisa

    }
}
