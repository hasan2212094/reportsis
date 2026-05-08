<?php

namespace App\Exports;

use App\Models\Workorder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WorkorderExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // ambil semua data tanpa filter
        return Workorder::all([
            'kode_wo',
            'end_user',
            'customer_name',
            'customer_po_no',
            'customer_po_date',
            'quantity',
            'wo_date',
            'nama_produk',
            'type_unit',
            'pekerjaan_selesai',
            'pekerjaan_termasuk',
            'pekerjaan_tidak_termasuk',
            'garansi',
            'total'
        ]);
    }

    public function headings(): array
    {
        return [
            'WO No',
            'Support By',
            'Customer',
            'PO No',
            'PO Date',
            'Qty',
            'WO Date',
            'Nama Produk',
            'Type Unit',
            'Pekerjaan Selesai',
            'Pekerjaan Termasuk',
            'Pekerjaan Tidak Termasuk',
            'Garansi',
            'Total',
        ];
    }
}
