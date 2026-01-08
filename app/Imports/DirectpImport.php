<?php

namespace App\Imports;

use App\Models\DirectP;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DirectpImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {

            // Skip row jika kosong
            if (empty($row['item_id'])) {
                return null;
            }

            // Bersihkan angka qty
            $qty = $this->cleanNumber($row['qty'] ?? 0);

            // Bersihkan total (hapus titik ribuan)
            $total = $this->cleanNumber($row['total'] ?? 0);

            // Parse tanggal
            $date = $this->parseDate($row['date_pengajuan'] ?? null);

            return new DirectP([
                'item_id'        => trim($row['item_id']),
                'Item'           => trim($row['item'] ?? null),
                'Qty'            => $qty,
                'Unit'           => trim($row['unit'] ?? null),
                'Needed_by'      => trim($row['needed_by'] ?? null),
                'workorder_id'   => $row['workorder_id'] ?? null,   // boleh kosong
                'Date_pengajuan' => $date,
                'Total'          => $total,
                'Notes'          => trim($row['notes'] ?? null),
            ]);

        } catch (\Exception $e) {
            Log::error("IMPORT ERROR: " . $e->getMessage(), $row);
            return null;
        }
    }

    private function cleanNumber($value)
    {
        if (!$value) return 0;

        // Hapus spasi dan titik ribuan
        $value = str_replace([' ', '.', ','], ['', '', ''], $value);

        return is_numeric($value) ? (int) $value : 0;
    }

    private function parseDate($value)
    {
        if (!$value) return null;

        try {
            if (is_numeric($value)) {
                return Carbon::createFromTimestamp(($value - 25569) * 86400)->format('Y-m-d');
            }

            return Carbon::createFromFormat('d/m/Y', trim($value))->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                return Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $e2) {
                Log::warning("Tanggal gagal diparse: {$value}");
                return null;
            }
        }
    }
}
