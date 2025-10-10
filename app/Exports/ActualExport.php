<?php

namespace App\Exports;

use App\Models\Directa;
use App\Models\Ppna;
use App\Models\Luarrab;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActualExport implements FromCollection, WithHeadings
{
    protected $workorder_id;

    public function __construct($workorder_id = null)
    {
        $this->workorder_id = $workorder_id;
    }

    public function collection()
    {
        // ===== DIRECT COST ACTUAL =====
       $directas = Directa::whereHas('direct_ps', function ($query) {
        $query->where('workorder_id', $this->workorder_id);
    })
    ->get(['direct_p_s_id', 'Qty', 'Unit', 'Date_actual', 'Toko', 'Transaksi', 'Total'])
    ->map(fn($row) => [
        'Sumber' => 'Direct Cost Actual',
        'Item' => $row->direct_ps->Item ?? '-',
        'Qty' => $row->Qty,
        'Unit' => $row->Unit,
        'Tanggal Actual' => $row->Date_actual,
        'Toko' => $row->Toko,
        'Transaksi' => $row->Transaksi == 1 ? 'Transfer' : 'Cash',
        'Total' => $row->Total,
    ]);
        // 🔹 PPN Actual
        $ppnas = Ppna::WhereHas('ppn', function ($query) { 
            $query->where('workorder_id', $this->workorder_id);
         })
            ->get(['ppns_id', 'Qty', 'Unit', 'Date_actual', 'Toko', 'Transaksi', 'Total'])
            ->map(fn($row) => [
                'Sumber' => 'PPN actual',
                'Item' => $row->ppn->Item??'-',
                'Qty' => $row->Qty,
                'Unit' => $row->Unit,
                'Tanggal Actual' => $row->Date_actual,
                'Toko' => $row->Toko,
                'Transaksi' => $row->Transaksi == 1 ? 'Transfer' : 'Cash',
                'Total' => $row->Total,
            ]);

        // 🔹 Luar RAB
        $luarrabs = Luarrab::query()
            ->when($this->workorder_id, fn($q) => $q->where('workorder_id', $this->workorder_id))
            ->get(['luarrabps_id as item_id', 'Item', 'Qty', 'Unit', 'Date_actual', 'Toko', 'Transaksi', 'Total'])
            ->map(fn($row) => [
                'Sumber' => 'Luar RAB',
                'Item' => $row->Item ?? $row->item_id,
                'Qty' => $row->Qty,
                'Unit' => $row->Unit,
                'Tanggal Actual' => $row->Date_actual,
                'Toko' => $row->Toko,
                'Transaksi' => $row->Transaksi == 1 ? 'Transfer' : 'Cash',
                'Total' => $row->Total,
            ]);

        // 🔹 Gabungkan semua data
        return collect()
            ->merge($directas)
            ->merge($ppnas)
            ->merge($luarrabs);
    }

    public function headings(): array
    {
        return [
            'Sumber',
            'Item',
            'Qty',
            'Unit',
            'Tanggal Actual',
            'Toko',
            'Transaksi',
            'Total',
        ];
    }
}
