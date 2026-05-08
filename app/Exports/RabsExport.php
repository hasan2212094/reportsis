<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RabsExport implements FromCollection, WithHeadings
{
    protected Collection $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($rab) {
            return [
                $rab->workorder->kode_wo ?? '-',
                $rab->nama_barang,
                optional($rab->user)->name ?? '-',
                $rab->qty,
                $rab->nama_toko,
                $rab->no_pr,
                $rab->status_ppn ? 'PPN' : 'Non PPN',
                $rab->status_pengajuan_label,
                $rab->tanggal_approved?->format('d-m-Y') ?? '-',
                $rab->tanggal_pengajuan?->format('d-m-Y') ?? '-',
                $rab->nominal ?? 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No. WO',
            'Nama Barang',
            'Nama Pengaju',
            'Qty',
            'Nama Toko',
            'No PR',
            'PPN',
            'Status',
            'Tanggal Approved',
            'Tanggal Pengajuan',
            'Nominal',
        ];
    }
}
