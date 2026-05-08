<?php

namespace App\Services;

use App\Models\Rabpengajuan;

class KodeRabService
{
    public function generate(): string
    {
        $bulan = now()->format('m');
        $tahun = now()->format('Y');

        // Ambil nomor terakhir bulan & tahun ini
        $last = Rabpengajuan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->lockForUpdate()
            ->orderByDesc('id')
            ->first();

        $number = 1;

        if ($last && preg_match('/RD\/(\d+)\//', $last->kode_rab, $match)) {
            $number = intval($match[1]) + 1;
        }

        $numberFormatted = str_pad($number, 5, '0', STR_PAD_LEFT);

        return "RD/{$numberFormatted}/{$bulan}/{$tahun}";
    }
}
