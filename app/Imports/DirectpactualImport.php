<?php

use App\Models\Directa;
use App\Models\Workorder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class DirectpImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
       try {
    // Abaikan baris kosong
    if (!isset($row['item_id']) || empty($row['item_id'])) {
        return null;
    }

    // Ambil langsung nilai ID dari Excel
    $workorderId = !empty($row['workorder_id']) ? (int) $row['workorder_id'] : null;

    // Parsing tanggal (misalnya format Excel: 14/10/2025)
    $date = $this->parseDate($row['date_pengajuan'] ?? null);

    return new Directa([
        'item_id'        => $row['item_id'],
        'Item'           => $row['item'] ?? null,
        'Qty'            => $row['qty'] ?? 0,
        'Unit'           => $row['unit'] ?? null,
        'Needed_by'      => $row['needed_by'] ?? null,
        'workorder_id'   => $workorderId, // ✅ langsung isi ID
        'Date_pengajuan' => $date,
        'Total'          => $row['total'] ?? 0,
        'Notes'          => $row['notes'] ?? null,
    ]);
} catch (\Exception $e) {
    Log::error('❌ Gagal import baris: ' . json_encode($row) . ' — ' . $e->getMessage());
    return null;
}
    }

    /**
     * Konversi berbagai format tanggal menjadi Y-m-d
     */
    private function parseDate($value)
    {
        if (!$value) return null;

    try {
        // Jika nilai berupa angka (format tanggal Excel)
        if (is_numeric($value)) {
            // Excel menyimpan tanggal sebagai jumlah hari sejak 1900-01-01
            return \Carbon\Carbon::createFromTimestamp(($value - 25569) * 86400)->format('Y-m-d');
        }

        // Jika format teks biasa (misal 14/10/2025)
        return \Carbon\Carbon::createFromFormat('d/m/Y', trim($value))->format('Y-m-d');
    } catch (\Exception $e) {
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e2) {
            Log::warning("⚠️ Gagal parse tanggal: {$value}");
            return null;
        }
    }
    }
}
