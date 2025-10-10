<?php

namespace Database\Seeders;

use App\Models\Workorder;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WorkorderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $tahun = 2025; // bisa diubah tiap tahun

          for ($i = 1; $i <= 250; $i++) {
         Workorder::create([
            'kode_wo' => 'WO' . $tahun . str_pad($i, 3, '0', STR_PAD_LEFT),
         ]);
          }
    }
}
