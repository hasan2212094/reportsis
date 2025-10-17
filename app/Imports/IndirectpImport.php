<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Indirectp;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IndirectpImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
         try {
            // Abaikan baris kosong
            if (!isset($row['item_id']) || empty($row['item_id'])) {
                return null;
            }

            // Parsing tanggal (format Excel bisa angka atau teks)
            $date = $this->parseDate($row['date_pengajuan'] ?? null);
            //  dd($row); 
            return new Indirectp([
                'item_id'        => $row['item_id'],
                'Item'           => $row['item'] ?? null,
                'Qty'            => $row['qty'] ?? 0,
                'Unit'           => $row['unit'] ?? null,
                'Needed_by'      => $row['needed_by'] ?? null,
                'Date_pengajuan' => $date,
                'Total'          => $row['total'] ?? 0,
                'Notes'          => $row['notes'] ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Gagal import: ' . json_encode($row) . ' — ' . $e->getMessage());
            return null;
        }
    }

    private function parseDate($value)
    {
        if (!$value) return null;

        try {
            // Jika Excel numeric (misal 45944)
            if (is_numeric($value)) {
                return Carbon::createFromTimestamp(($value - 25569) * 86400)->format('Y-m-d');
            }

            // Format teks d/m/Y
            return Carbon::createFromFormat('d/m/Y', trim($value))->format('Y-m-d');
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
