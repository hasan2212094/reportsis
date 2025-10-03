<?php

namespace App\Exports;

use App\Models\Ppna;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class PpnaExport implements FromCollection, WithHeadings, WithCustomStartCell, WithStyles, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
  public function collection()
{
    return Ppna::with('ppn')
        ->get()
        ->map(function ($item) {
            return [
                $item->ppn->Item ?? '-',
                $item->ppn->Qty ?? '-',
                $item->ppn->Unit ?? '-',
                $item->ppn->Needed_by ?? '-',
                $item->Qty,
                $item->Unit,
                $item->Date_actual,
                $item->Toko,
                $item->Transaksi == 0 ? 'CASH' : 'TRANSFER',
                $item->Total,
            ];
        });
}
    public function startCell(): string
    {
        return 'A1';
    }

    public function headings(): array
    {
        return [
               ['LAPORAN PPN ACTUAL'], // judul utama di baris 1
           [ 'Item',
            'Qty',
            'Unit',
            'Kebutuhan',
            'Qty',
            'Unit',
            'Date_actual',
            'Toko',
            'Transaksi',
            'Total',
        ],
        ];
    }
     public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]], // Judul
            2 => ['font' => ['bold' => true]], // Heading tabel
        ];
    }

       public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->mergeCells('A1:J1'); // merge judul dari A1 sampai J1
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            },
        ];
    }
}
